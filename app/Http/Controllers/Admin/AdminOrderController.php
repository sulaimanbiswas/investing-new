<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserOrder;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    /**
     * Display all completed user orders
     */
    public function index(Request $request)
    {
        $query = UserOrder::with(['userOrderSet.user'])
            ->where('status', 'paid')
            ->orderByDesc('paid_at');

        // Search filter (order number, user name, email, username)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('userOrderSet.user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('username', 'like', "%{$search}%");
                    });
            });
        }

        // Date range filter
        if ($from = $request->input('date_from')) {
            $query->whereDate('paid_at', '>=', $from);
        }
        if ($to = $request->input('date_to')) {
            $query->whereDate('paid_at', '<=', $to);
        }

        // Amount range filter
        if ($minAmount = $request->input('min_amount')) {
            $query->where('order_amount', '>=', $minAmount);
        }
        if ($maxAmount = $request->input('max_amount')) {
            $query->where('order_amount', '<=', $maxAmount);
        }

        $orders = $query->paginate(50)->withQueryString();

        // Statistics
        $totalOrders = UserOrder::where('status', 'paid')->count();
        $totalAmount = UserOrder::where('status', 'paid')->sum('order_amount');
        $totalProfit = UserOrder::where('status', 'paid')->sum('profit_amount');
        $todayOrders = UserOrder::where('status', 'paid')
            ->whereDate('paid_at', now()->toDateString())
            ->count();

        return view('admin.orders.index', compact(
            'orders',
            'totalOrders',
            'totalAmount',
            'totalProfit',
            'todayOrders'
        ));
    }

    /**
     * Show order details
     */
    public function show(UserOrder $order)
    {
        $order->load(['userOrderSet.user', 'productPackageItem']);

        return view('admin.orders.show', compact('order'));
    }
}
