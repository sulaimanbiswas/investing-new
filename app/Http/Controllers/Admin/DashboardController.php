<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\LoginHistory;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Get total users (excluding admins)
        $totalUsers = User::where('is_admin', 0)->count();

        // Get active users (users who have logged in)
        $activeUsers = User::where('is_admin', 0)
            ->whereHas('loginHistories', function ($query) {
                $query->where('status', 'success');
            })
            ->distinct()
            ->count();

        // Get deposit statistics
        $totalDeposited = Deposit::where('status', 'approved')->sum('amount');
        $pendingDeposits = Deposit::where('status', 'pending')->count();
        $rejectedDeposits = Deposit::where('status', 'rejected')->count();

        // Get withdrawal statistics
        $totalWithdrawn = Withdrawal::where('status', 'approved')->sum('amount');
        $pendingWithdrawals = Withdrawal::where('status', 'pending')->count();
        $rejectedWithdrawals = Withdrawal::where('status', 'rejected')->count();

        // Get currency symbol for display
        $currencySymbol = setting('currency_symbol') ?? 'USDT';

        $data = [
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'totalDeposited' => $totalDeposited,
            'pendingDeposits' => $pendingDeposits,
            'rejectedDeposits' => $rejectedDeposits,
            'totalWithdrawn' => $totalWithdrawn,
            'pendingWithdrawals' => $pendingWithdrawals,
            'rejectedWithdrawals' => $rejectedWithdrawals,
            'currencySymbol' => $currencySymbol,
        ];

        return view('admin.dashboard', $data);
    }
}
