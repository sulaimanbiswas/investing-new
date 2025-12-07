<?php

namespace App\Http\Controllers;

use App\Services\ReferralCommissionService;
use Illuminate\Http\Request;

class ReferralCommissionController extends Controller
{
    protected $commissionService;

    public function __construct(ReferralCommissionService $commissionService)
    {
        $this->commissionService = $commissionService;
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        // Get commission statistics
        $totals = $this->commissionService->getUserTotalCommissions($user);

        // Get commission history with pagination
        $commissions = $this->commissionService->getUserCommissionHistory($user, 20);

        // Calculate total earnings
        $totalEarnings = $totals['level1'] + $totals['level2'] + $totals['level3'];

        return view('user.commissions.index', compact('commissions', 'totals', 'totalEarnings'));
    }
}
