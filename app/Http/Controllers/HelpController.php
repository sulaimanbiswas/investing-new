<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HelpController extends Controller
{
    /**
     * Display the help page
     */
    public function index()
    {
        $user = Auth::user();

        return view('user.help.index', compact('user'));
    }
}
