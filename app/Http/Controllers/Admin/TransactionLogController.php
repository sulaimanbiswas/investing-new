<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\View\View;

class TransactionLogController extends Controller
{
    public function index(): View
    {
        $typeFilter = request('type', 'all'); // all|order_payment|order_profit|deposit|withdrawal
        $userId = request('user_id');
        $startDate = request('start_date');
        $endDate = request('end_date');
        $page = (int) request('page', 1);
        $perPage = 20;

        $query = Transaction::with('user')->latest();

        if ($typeFilter !== 'all') {
            $query->where('type', $typeFilter);
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59',
            ]);
        }

        $transactions = $query->paginate($perPage, ['*'], 'page', $page)->withQueryString();

        $stats = [
            'net' => Transaction::sum('amount'),
            'order_payment' => Transaction::where('type', 'order_payment')->sum('amount'),
            'order_principal_return' => Transaction::where('type', 'order_principal_return')->sum('amount'),
            'order_profit' => Transaction::where('type', 'order_profit')->sum('amount'),
            'deposit' => Transaction::where('type', 'deposit')->sum('amount'),
            'withdrawal' => Transaction::where('type', 'withdrawal')->sum('amount'),
        ];

        $users = User::orderBy('name')->get(['id', 'name', 'username', 'email']);

        return view('admin.transactions.index', [
            'records' => $transactions,
            'typeFilter' => $typeFilter,
            'userId' => $userId,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'stats' => $stats,
            'users' => $users,
        ]);
    }
}
