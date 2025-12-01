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
            'balance' => $user->balance,
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

    public function addBalance(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'note' => 'nullable|string|max:500',
        ]);

        // Add balance to user
        $user->increment('balance', $request->amount);

        // TODO: Log this transaction when transaction history system is implemented
        // Transaction::create([
        //     'user_id' => $user->id,
        //     'type' => 'balance_add',
        //     'amount' => $request->amount,
        //     'note' => $request->note,
        //     'admin_id' => auth()->id(),
        // ]);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'Successfully added USDT' . number_format($request->amount, 2) . ' to ' . $user->username . '\'s balance.');
    }

    public function subtractBalance(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'note' => 'nullable|string|max:500',
        ]);

        // Check if user has sufficient balance
        if ($user->balance < $request->amount) {
            return redirect()
                ->route('admin.users.show', $user)
                ->with('error', 'Insufficient balance. User has USDT' . number_format($user->balance, 2) . ' but you tried to subtract USDT' . number_format($request->amount, 2) . '.');
        }

        // Subtract balance from user
        $user->decrement('balance', $request->amount);

        // TODO: Log this transaction when transaction history system is implemented
        // Transaction::create([
        //     'user_id' => $user->id,
        //     'type' => 'balance_subtract',
        //     'amount' => $request->amount,
        //     'note' => $request->note,
        //     'admin_id' => auth()->id(),
        // ]);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'Successfully subtracted USDT' . number_format($request->amount, 2) . ' from ' . $user->username . '\'s balance.');
    }

    public function changePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => bcrypt($request->password),
        ]);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'Password changed successfully for user: ' . $user->username);
    }
}
