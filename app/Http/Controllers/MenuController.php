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

        // Today's orders count
        $todayOrdersCount = UserOrder::whereHas('userOrderSet', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->whereDate('created_at', Carbon::today())
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

        // Cash gap between task (difference between balance and next task requirement)
        $nextTaskRequirement = $platform->start_price; // Minimum balance required for this platform
        $cashGap = max(0, $nextTaskRequirement - $user->balance);

        // Yesterday's team commission - Calculate from referrals' orders
        $yesterdayTeamCommission = UserOrder::whereHas('userOrderSet', function ($query) use ($user) {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('referred_by', $user->id);
            });
        })
            ->whereDate('paid_at', Carbon::yesterday())
            ->where('status', 'paid')
            ->sum('profit_amount') ?? 0;

        return view('user.menu.show', compact(
            'platform',
            'user',
            'todayOrdersCount',
            'todayCommission',
            'yesterdayCommission',
            'cashGap',
            'yesterdayTeamCommission'
        ));
    }
}
