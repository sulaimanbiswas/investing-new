<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\LoginHistory;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalUsers = User::where('is_admin', 0)->count();

        $activeUsers = User::where('is_admin', 0)
            ->where('status', 'active')
            ->count();

        $loginLast24h = LoginHistory::where('status', 'success')
            ->where('created_at', '>=', Carbon::now()->subDay())
            ->count();

        // Deposit statistics
        $totalDeposited = Deposit::where('status', 'approved')->sum('amount');
        $pendingDeposits = Deposit::where('status', 'pending')->count();
        $rejectedDeposits = Deposit::where('status', 'rejected')->count();
        $approvedDepositsCount = Deposit::where('status', 'approved')->count();
        $totalDepositRequests = Deposit::count();

        // Withdrawal statistics
        $totalWithdrawn = Withdrawal::where('status', 'approved')->sum('amount');
        $pendingWithdrawals = Withdrawal::where('status', 'pending')->count();
        $rejectedWithdrawals = Withdrawal::where('status', 'rejected')->count();
        $approvedWithdrawalsCount = Withdrawal::where('status', 'approved')->count();
        $totalWithdrawalRequests = Withdrawal::count();

        // Currency
        $currencySymbol = setting('currency_symbol') ?? 'USDT';

        // Daily series (last 7 days including today)
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $depositDailyRaw = Deposit::selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->where('status', 'approved')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->pluck('total', 'date');

        $withdrawDailyRaw = Withdrawal::selectRaw('DATE(created_at) as date, SUM(amount) as total')
            ->where('status', 'approved')
            ->where('created_at', '>=', $startDate)
            ->groupBy('date')
            ->pluck('total', 'date');

        $chartLabels = [];
        $depositSeries = [];
        $withdrawalSeries = [];
        for ($i = 0; $i <= 6; $i++) {
            $date = $startDate->copy()->addDays($i);
            $key = $date->toDateString();
            $chartLabels[] = $date->format('M d');
            $depositSeries[] = (float) ($depositDailyRaw[$key] ?? 0);
            $withdrawalSeries[] = (float) ($withdrawDailyRaw[$key] ?? 0);
        }

        $netFlow = $totalDeposited - $totalWithdrawn;

        // Tables for pending items
        $pendingDepositRows = Deposit::with('user')
            ->where('status', 'pending')
            ->latest()
            ->limit(10)
            ->get();

        $pendingWithdrawalRows = Withdrawal::with('user')
            ->where('status', 'pending')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'loginLast24h' => $loginLast24h,
            'totalDeposited' => $totalDeposited,
            'pendingDeposits' => $pendingDeposits,
            'rejectedDeposits' => $rejectedDeposits,
            'approvedDepositsCount' => $approvedDepositsCount,
            'totalDepositRequests' => $totalDepositRequests,
            'totalWithdrawn' => $totalWithdrawn,
            'pendingWithdrawals' => $pendingWithdrawals,
            'rejectedWithdrawals' => $rejectedWithdrawals,
            'approvedWithdrawalsCount' => $approvedWithdrawalsCount,
            'totalWithdrawalRequests' => $totalWithdrawalRequests,
            'currencySymbol' => $currencySymbol,
            'chartLabels' => $chartLabels,
            'depositSeries' => $depositSeries,
            'withdrawalSeries' => $withdrawalSeries,
            'netFlow' => $netFlow,
            'pendingDepositRows' => $pendingDepositRows,
            'pendingWithdrawalRows' => $pendingWithdrawalRows,
        ]);
    }
}
