<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReferralCommission;
use App\Models\User;
use Illuminate\Http\Request;

class ReferralCommissionReportController extends Controller
{
    public function index(Request $request)
    {
        $query = ReferralCommission::with(['user', 'referredUser', 'deposit']);

        // Filters
        if ($userId = $request->input('user_id')) {
            $query->where('user_id', $userId);
        }

        if ($level = $request->input('level')) {
            $query->where('level', $level);
        }

        if ($startDate = $request->input('start_date')) {
            $query->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate = $request->input('end_date')) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        // Statistics
        $stats = [
            'total_commissions' => ReferralCommission::sum('commission_amount'),
            'level1_commissions' => ReferralCommission::where('level', 1)->sum('commission_amount'),
            'level2_commissions' => ReferralCommission::where('level', 2)->sum('commission_amount'),
            'level3_commissions' => ReferralCommission::where('level', 3)->sum('commission_amount'),
            'total_records' => ReferralCommission::count(),
        ];

        $commissions = $query->orderBy('created_at', 'desc')->paginate(50);
        $users = User::where('is_admin', false)->orderBy('name')->get();

        return view('admin.reports.referral-commissions', compact('commissions', 'stats', 'users'));
    }
}
