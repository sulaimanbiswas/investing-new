<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\UserOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RecordController extends Controller
{
    public function index(): View
    {
        $userId = Auth::id();
        $activeTab = request('tab', 'complete');

        // Get transaction statistics
        $stats = [
            'total_deposits' => Transaction::where('user_id', $userId)
                ->where('type', 'deposit')
                ->sum('amount'),
            'total_withdrawals' => abs(Transaction::where('user_id', $userId)
                ->where('type', 'withdrawal')
                ->sum('amount')),
            'total_order_payments' => abs(Transaction::where('user_id', $userId)
                ->where('type', 'order_payment')
                ->sum('amount')),
            'total_order_profits' => Transaction::where('user_id', $userId)
                ->where('type', 'order_profit')
                ->sum('amount'),
            'total_principal_returns' => Transaction::where('user_id', $userId)
                ->where('type', 'order_principal_return')
                ->sum('amount'),
            'net_balance' => Transaction::where('user_id', $userId)->sum('amount'),
        ];

        // Get data based on active tab
        $incompleteOrders = collect();
        $completeOrders = collect();
        $transactions = collect();

        if ($activeTab === 'incomplete') {
            // Get the first unpaid order globally for this user (oldest first)
            // This ensures orders are completed in the sequence they were assigned
            $firstUnpaidOrder = UserOrder::with(['userOrderSet.orderSet.platform', 'productPackageItem.productPackage'])
                ->whereHas('userOrderSet', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->where('status', 'unpaid')
                ->orderBy('created_at', 'asc')
                ->first();

            $incompleteOrders = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15);
            if ($firstUnpaidOrder) {
                $incompleteOrders = new \Illuminate\Pagination\LengthAwarePaginator(
                    [$firstUnpaidOrder],
                    1,
                    15,
                    1,
                    [
                        'path' => request()->url(),
                        'query' => request()->query(),
                    ]
                );
            }
        } elseif ($activeTab === 'complete') {
            $completeOrders = UserOrder::whereHas('userOrderSet', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
                ->where('status', 'paid')
                ->with(['productPackageItem.productPackage'])
                ->latest('paid_at')
                ->paginate(15)
                ->withQueryString();
        } elseif ($activeTab === 'transactions') {
            $transactions = Transaction::where('user_id', $userId)
                ->latest()
                ->paginate(perPage: 15)
                ->withQueryString();
        }

        return view('user.records.index', compact(
            'incompleteOrders',
            'completeOrders',
            'transactions',
            'stats',
            'activeTab'
        ));
    }
}