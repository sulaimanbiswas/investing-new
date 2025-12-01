<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('is_admin', false);

        // Search by username or email
        if ($search = $request->string('search')->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Date range filter
        if ($from = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $users = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        // Load relationships
        $user->load(['deposits.gateway', 'referrals']);

        // Calculate statistics
        $stats = [
            'balance' => 0, // TODO: Implement balance from wallet system
            'total_deposits' => $user->deposits()->where('status', 'approved')->sum('amount'),
            'total_withdrawals' => 0, // TODO: Implement when withdrawal system is ready
            'total_transactions' => $user->deposits()->count(),
            'total_invest' => 0, // TODO: Implement when investment system is ready
            'total_referral_commission' => 0, // TODO: Implement when commission system is ready
            'total_binary_commission' => 0, // TODO: Implement when binary system is ready
            'total_bv' => 0, // TODO: Implement when BV system is ready
        ];

        return view('admin.users.show', compact('user', 'stats'));
    }
}
