<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginHistory;
use App\Models\User;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function loginHistory(Request $request)
    {
        $query = LoginHistory::with('user');

        // Search filter (username, email, IP, country)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('ip_address', 'like', "%{$search}%")
                    ->orWhere('country', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('username', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by user (from URL or form)
        if ($userId = $request->input('user_id')) {
            $query->where('user_id', $userId);
        }

        // Filter by status
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Filter by device
        if ($device = $request->input('device')) {
            $query->where('device', $device);
        }

        // Date range filter
        if ($from = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $loginHistories = $query->latest()->paginate(20)->withQueryString();

        // Get selected user for display
        $selectedUser = null;
        if ($userId) {
            $selectedUser = User::find($userId);
        }

        // Get all users for dropdown
        $users = User::select('id', 'name', 'username', 'email')->orderBy('name')->get();

        return view('admin.reports.login-history', compact('loginHistories', 'selectedUser', 'users'));
    }
}
