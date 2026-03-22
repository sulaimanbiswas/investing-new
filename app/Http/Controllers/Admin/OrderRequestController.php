<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderRequest;
use App\Models\OrderSet;
use App\Models\Platform;
use App\Models\ProductPackageItem;
use App\Models\User;
use App\Models\UserOrder;
use App\Models\UserOrderSet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class OrderRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = OrderRequest::with(['user', 'platform', 'processedBy'])
            ->orderByDesc('requested_at');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%");
                })->orWhereHas('platform', function ($pq) use ($search) {
                    $pq->where('name', 'like', "%{$search}%")
                        ->orWhere('package_name', 'like', "%{$search}%");
                });
            });
        }

        $orderRequests = $query->paginate(20)->withQueryString();

        $stats = [
            'pending' => OrderRequest::where('status', 'pending')->count(),
            'accepted' => OrderRequest::where('status', 'accepted')->count(),
            'rejected' => OrderRequest::where('status', 'rejected')->count(),
        ];

        return view('admin.order-requests.index', compact('orderRequests', 'stats'));
    }

    public function updateStatus(Request $request, OrderRequest $orderRequest)
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected',
            'admin_note' => 'nullable|string|max:1000',
        ]);

        if ($orderRequest->status !== 'pending') {
            return redirect()->back()->with('error', 'This request has already been processed.');
        }

        if ($request->status === 'rejected' && !$request->filled('admin_note')) {
            throw ValidationException::withMessages([
                'admin_note' => 'Admin note is required when rejecting a request.',
            ]);
        }

        try {
            DB::transaction(function () use ($request, $orderRequest) {
                if ($request->status === 'accepted') {
                    $this->assignRequestedOrder($orderRequest->user);
                }

                $orderRequest->update([
                    'status' => $request->status,
                    'admin_note' => $request->admin_note,
                    'processed_by' => auth('admin')->id(),
                    'processed_at' => now(),
                ]);
            });
        } catch (RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        flash()->success('Order request updated successfully.');

        return redirect()->back();
    }

    private function assignRequestedOrder(User $user): void
    {
        $hasUnpaidOrder = UserOrder::whereHas('userOrderSet', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->where('status', 'unpaid')
            ->exists();

        if ($hasUnpaidOrder) {
            throw new RuntimeException('User already has an unpaid order.');
        }

        $platform = Platform::where('start_price', '<=', $user->balance)
            ->where('end_price', '>=', $user->balance)
            ->first();

        if (!$platform) {
            throw new RuntimeException('No matching platform found for this user balance.');
        }

        $orderSet = OrderSet::where('platform_id', $platform->id)
            ->where('is_active', true)
            ->inRandomOrder()
            ->first();

        if (!$orderSet) {
            throw new RuntimeException('No active order set is available for this platform.');
        }

        $productPackageItem = ProductPackageItem::whereHas('productPackage', function ($query) use ($orderSet) {
            $query->where('order_set_id', $orderSet->id)
                ->where('is_active', true);
        })
            ->with(['product', 'productPackage'])
            ->inRandomOrder()
            ->first();

        if (!$productPackageItem) {
            throw new RuntimeException('No product package item is available for the selected order set.');
        }

        $userOrderSet = UserOrderSet::firstOrCreate(
            [
                'user_id' => $user->id,
                'order_set_id' => $orderSet->id,
            ],
            [
                'total_products' => 0,
                'completed_products' => 0,
                'total_amount' => 0,
                'profit_amount' => 0,
                'status' => 'active',
                'assigned_at' => now(),
            ]
        );

        $orderAmount = $productPackageItem->price * $productPackageItem->quantity;
        $profitAmount = ($orderAmount * $productPackageItem->productPackage->profit_percentage) / 100;

        UserOrder::create([
            'user_order_set_id' => $userOrderSet->id,
            'product_package_item_id' => $productPackageItem->id,
            'order_number' => UserOrder::generateOrderNumber(),
            'type' => $productPackageItem->productPackage->type,
            'order_amount' => $orderAmount,
            'profit_amount' => $profitAmount,
            'balance_after' => $user->balance,
            'status' => 'unpaid',
            'manage_crypto' => [
                [
                    'name' => $productPackageItem->product->name,
                    'quantity' => $productPackageItem->quantity,
                    'price' => $productPackageItem->price,
                    'image' => $productPackageItem->product->image,
                ]
            ],
        ]);
    }
}
