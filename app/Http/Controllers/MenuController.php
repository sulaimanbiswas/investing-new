<?php

namespace App\Http\Controllers;

use App\Models\Platform;
use App\Models\UserOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    /**
     * Display platforms menu with VIP levels
     */
    public function index(Request $request)
    {
        $vipLevel = $request->input('vip_level', 'all');

        $query = Platform::query();

        // Filter by VIP level based on package_name
        if ($vipLevel !== 'all') {
            $query->where('package_name', $vipLevel);
        }

        $platforms = $query->orderBy('start_price')->get();

        // Check if user has any active orders
        $user = Auth::user();
        $hasActiveOrder = UserOrder::whereHas('userOrderSet', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->where('status', 'unpaid')
            ->exists();

        return view('user.menu.index', compact('platforms', 'vipLevel', 'hasActiveOrder'));
    }

    /**
     * Display platform details with order statistics
     */
    public function show(Platform $platform)
    {
        $user = Auth::user();

        // Today's orders count (completed orders only)
        $todayOrdersCount = UserOrder::whereHas('userOrderSet', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->whereDate('paid_at', Carbon::today())
            ->where('status', 'paid')
            ->count();

        // Today's commission (profit) - from paid orders today
        $todayCommission = UserOrder::whereHas('userOrderSet', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->whereDate('paid_at', Carbon::today())
            ->where('status', 'paid')
            ->sum('profit_amount') ?? 0;

        // Yesterday's commission (profit) - from paid orders yesterday
        $yesterdayCommission = UserOrder::whereHas('userOrderSet', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->whereDate('paid_at', Carbon::yesterday())
            ->where('status', 'paid')
            ->sum('profit_amount') ?? 0;

        // Cash gap between task will be calculated later based on unpaid order
        $cashGap = 0;

        // Yesterday's team commission - Calculate from referrals' orders
        $yesterdayTeamCommission = UserOrder::whereHas('userOrderSet', function ($query) use ($user) {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('referred_by', $user->id);
            });
        })
            ->whereDate('paid_at', Carbon::yesterday())
            ->where('status', 'paid')
            ->sum('profit_amount') ?? 0;

        // Find user's VIP platform based on balance
        $userVipPlatform = Platform::where('start_price', '<=', $user->balance)
            ->where('end_price', '>=', $user->balance)
            ->first();

        $userVipLevel = $userVipPlatform ? strtoupper($userVipPlatform->package_name) : null;
        $hasVipLevel = $userVipPlatform !== null;

        // Get first unpaid order ONLY from user's VIP level platform (sorted by price)
        $unpaidOrder = null;
        if ($userVipPlatform) {
            $unpaidOrder = UserOrder::whereHas('userOrderSet', function ($query) use ($user, $userVipPlatform) {
                $query->where('user_id', $user->id)
                    ->whereHas('orderSet', function ($q) use ($userVipPlatform) {
                        $q->where('platform_id', $userVipPlatform->id);
                    });
            })
                ->where('status', 'unpaid')
                ->orderBy('order_amount', 'asc') // Sort by price ascending
                ->with(['productPackageItem.product', 'productPackageItem.productPackage'])
                ->first();
        }

        // Count today's completed orders
        $todayCompletedCount = UserOrder::whereHas('userOrderSet', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->whereDate('paid_at', Carbon::today())
            ->where('status', 'paid')
            ->count();

        // Check if user's balance is within platform range
        $canGrabOrder = $user->balance >= $platform->start_price && $user->balance <= $platform->end_price;

        // Check if user has reached daily order limit
        $hasReachedDailyLimit = $todayCompletedCount >= $user->daily_order_limit;

        // Check if this is user's VIP platform - only show orders on user's VIP level platform
        $isUserVipPlatform = $userVipPlatform && $userVipPlatform->id === $platform->id;

        // Hide orders if viewing different platform than user's VIP level
        if (!$isUserVipPlatform) {
            $unpaidOrder = null;
        }

        // Calculate cash gap if there's an unpaid order (how much more balance needed)
        if ($unpaidOrder) {
            $cashGap = max(0, $unpaidOrder->order_amount - $user->balance);
        }

        return view('user.menu.show', compact(
            'platform',
            'user',
            'todayOrdersCount',
            'todayCommission',
            'yesterdayCommission',
            'cashGap',
            'yesterdayTeamCommission',
            'unpaidOrder',
            'canGrabOrder',
            'hasReachedDailyLimit',
            'todayCompletedCount',
            'userVipLevel',
            'isUserVipPlatform',
            'hasVipLevel'
        ));
    }

    /**
     * Grab a new order from the platform
     */
    public function grabOrder(Platform $platform)
    {
        $user = Auth::user();

        // Check daily order limit
        $todayCompletedCount = UserOrder::whereHas('userOrderSet', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->whereDate('paid_at', Carbon::today())
            ->where('status', 'paid')
            ->count();

        if ($todayCompletedCount >= $user->daily_order_limit) {
            return response()->json([
                'success' => false,
                'message' => "You have reached your daily order limit ({$user->daily_order_limit} orders)."
            ]);
        }

        // Check if user's balance is within platform range
        if ($user->balance < $platform->start_price || $user->balance > $platform->end_price) {
            // Find the correct platform for user's balance
            $correctPlatform = Platform::where('start_price', '<=', $user->balance)
                ->where('end_price', '>=', $user->balance)
                ->first();

            $correctPlatformName = $correctPlatform ? $correctPlatform->name : 'appropriate platform';
            $userVipLevel = $correctPlatform ? strtoupper($correctPlatform->package_name) : 'current VIP';

            return response()->json([
                'success' => false,
                'message' => "You are in {$userVipLevel}, go to {$correctPlatformName} to get tasks."
            ]);
        }

        // Check if user already has any unpaid order (from their VIP level platform)
        $existingOrder = UserOrder::whereHas('userOrderSet', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->where('status', 'unpaid')
            ->exists();

        if ($existingOrder) {
            return response()->json([
                'success' => false,
                'message' => 'You already have an unpaid order. Please complete it first.'
            ]);
        }

        // Get a random active order set for this platform
        $orderSet = \App\Models\OrderSet::where('platform_id', $platform->id)
            ->where('is_active', true)
            ->inRandomOrder()
            ->first();

        if (!$orderSet) {
            return response()->json([
                'success' => false,
                'message' => 'No orders available for this platform at the moment.'
            ]);
        }

        // Get or create user order set
        $userOrderSet = \App\Models\UserOrderSet::firstOrCreate(
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

        // Get a random product package item from this order set
        $productPackageItem = \App\Models\ProductPackageItem::whereHas('productPackage', function ($query) use ($orderSet) {
            $query->where('order_set_id', $orderSet->id)
                ->where('is_active', true);
        })
            ->with(['product', 'productPackage'])
            ->inRandomOrder()
            ->first();

        if (!$productPackageItem) {
            return response()->json([
                'success' => false,
                'message' => 'No products available in this order set.'
            ]);
        }

        // Create the order
        $orderAmount = $productPackageItem->price * $productPackageItem->quantity;
        $profitAmount = ($orderAmount * $productPackageItem->productPackage->profit_percentage) / 100;

        $order = UserOrder::create([
            'user_order_set_id' => $userOrderSet->id,
            'product_package_item_id' => $productPackageItem->id,
            'order_number' => UserOrder::generateOrderNumber(),
            'type' => $productPackageItem->productPackage->type,
            'product_name' => $productPackageItem->product->name,
            'quantity' => $productPackageItem->quantity,
            'price' => $productPackageItem->price,
            'order_amount' => $orderAmount,
            'profit_amount' => $profitAmount,
            'balance_after' => $user->balance,
            'status' => 'unpaid',
            'manage_crypto' => [
                [
                    'name' => $productPackageItem->product->name,
                    'quantity' => $productPackageItem->quantity,
                    'price' => $productPackageItem->price,
                ]
            ],
        ]);

        return response()->json([
            'success' => true,
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'product_name' => $order->product_name,
                'quantity' => $order->quantity,
                'price' => number_format($order->price, 2),
                'order_amount' => number_format($order->order_amount, 2),
                'commission' => number_format($order->profit_amount, 2),
                'expected_income' => number_format($user->balance + $order->profit_amount, 2),
                'manage_crypto' => $order->manage_crypto,
            ]
        ]);
    }

    /**
     * Submit/Pay for an order
     */
    public function submitOrder(Request $request, UserOrder $order)
    {
        $user = Auth::user();

        // Verify order belongs to user
        if ($order->userOrderSet->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        // Check if order is already paid
        if ($order->status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'This order has already been paid.'
            ]);
        }

        // Check if user has sufficient balance to complete the order
        if ($user->balance < $order->order_amount) {
            $needed = $order->order_amount - $user->balance;
            return response()->json([
                'success' => false,
                'message' => "Insufficient balance. You need $" . number_format($needed, 2) . " more USDT to complete this order. Please deposit first."
            ]);
        }

        // Update order status and user balance
        $profitAmount = (float) $order->profit_amount;
        $orderAmount = (float) $order->order_amount;

        $order->update([
            'status' => 'paid',
            'paid_at' => now(),
            'balance_after' => (float) $user->balance + $profitAmount,
        ]);

        // Add profit to user balance
        $user->increment('balance', $profitAmount);

        // Update user order set
        $order->userOrderSet->increment('completed_products');
        $order->userOrderSet->increment('total_amount', $orderAmount);
        $order->userOrderSet->increment('profit_amount', $profitAmount);

        return response()->json([
            'success' => true,
            'message' => 'Order completed successfully!',
            'new_balance' => number_format((float) $user->fresh()->balance, 2)
        ]);
    }
}
