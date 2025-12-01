<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class TeamsController extends Controller
{
    /**
     * Display the teams page with 3-level referral tree
     */
    public function index()
    {
        $user = Auth::user();

        // Get Level 1: Direct referrals
        $level1 = $user->referrals()->with('referrals')->get();
        $level1Count = $level1->count();

        // Get Level 2: Referrals of referrals
        $level2 = User::whereIn('referred_by', $level1->pluck('id'))
            ->with('referrer')
            ->get();
        $level2Count = $level2->count();

        // Get Level 3: Referrals of level 2
        $level3 = User::whereIn('referred_by', $level2->pluck('id'))
            ->with('referrer')
            ->get();
        $level3Count = $level3->count();

        // Calculate total team size
        $totalTeamSize = $level1Count + $level2Count + $level3Count;

        return view('user.teams.index', compact(
            'level1',
            'level2',
            'level3',
            'level1Count',
            'level2Count',
            'level3Count',
            'totalTeamSize'
        ));
    }
}
