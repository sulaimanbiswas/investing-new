<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }

    public function active()
    {
        return view('admin.users.active');
    }

    public function banned()
    {
        return view('admin.users.banned');
    }
}
