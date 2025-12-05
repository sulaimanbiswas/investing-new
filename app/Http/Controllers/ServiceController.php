<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    /**
     * Display the service center page
     */
    public function index()
    {
        $user = Auth::user();

        return view('user.service.index', compact('user'));
    }
}
