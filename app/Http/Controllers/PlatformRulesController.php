<?php

namespace App\Http\Controllers;

use App\Models\PlatformRule;
use Illuminate\Http\Request;

class PlatformRulesController extends Controller
{
    /**
     * Display all active platform rules
     */
    public function index()
    {
        $platformRules = PlatformRule::where('is_active', true)
            ->orderBy('sort_by')
            ->get();

        return view('user.platform-rules.index', compact('platformRules'));
    }

    /**
     * Display a single platform rule
     */
    public function show($id)
    {
        $platformRule = PlatformRule::where('is_active', true)
            ->findOrFail($id);

        return view('user.platform-rules.show', compact('platformRule'));
    }

    /**
     * Get latest platform rules for dashboard
     */
    public function latest()
    {
        return PlatformRule::where('is_active', true)
            ->orderBy('sort_by')
            ->take(4)
            ->get();
    }
}
