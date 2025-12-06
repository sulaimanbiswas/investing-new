<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RecordController extends Controller
{
    public function index(): View
    {
        $userId = Auth::id();
        $activeTab = request('tab', 'all');

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

        // Get transactions based on active tab
        $query = Transaction::where('user_id', $userId)->latest();

        if ($activeTab !== 'all') {
            $query->where('type', $activeTab);
        }

        $transactions = $query->paginate(15)->withQueryString();

        return view('user.records.index', compact(
            'transactions',
            'stats',
            'activeTab'
        ));
    }
}
