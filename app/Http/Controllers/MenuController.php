<?php

namespace App\Http\Controllers;

use App\Models\Platform;
use Illuminate\Http\Request;

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

        return view('user.menu.index', compact('platforms', 'vipLevel'));
    }
}
