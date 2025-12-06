<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal;
use App\Models\User;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    /**
     * Display withdrawal history with filters
     */
    public function index(Request $request)
    {
        $query = Withdrawal::with(['user']);

        // User filter
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date range filter
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('wallet_address', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('username', 'like', "%{$search}%");
                    });
            });
        }

        $withdrawals = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get filter data
        $users = User::orderBy('name')->get(['id', 'name', 'username', 'email']);

        // Calculate statistics
        $stats = [
            'approved' => Withdrawal::where('status', 'approved')->sum('amount'),
            'pending' => Withdrawal::where('status', 'pending')->sum('amount'),
            'rejected' => Withdrawal::where('status', 'rejected')->sum('amount'),
        ];

        return view('admin.withdrawals.index', compact('withdrawals', 'users', 'stats'));
    }

    /**
     * Show withdrawal details
     */
    public function show(Withdrawal $withdrawal)
    {
        $withdrawal->load(['user.withdrawals']);
        return view('admin.withdrawals.show', compact('withdrawal'));
    }

    /**
     * Update withdrawal status
     */
    public function updateStatus(Request $request, Withdrawal $withdrawal)
    {
        $rules = [
            'status' => 'required|in:pending,approved,rejected',
        ];

        // Admin note is required for rejection
        if ($request->status === 'rejected') {
            $rules['admin_note'] = 'required|string|max:1000';
        } else {
            $rules['admin_note'] = 'nullable|string|max:1000';
        }

        $request->validate($rules);

        // Check if this is a new approval or rejection (status changing from pending)
        $wasPending = $withdrawal->status === 'pending';

        $withdrawal->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note,
        ]);

        // If approved for the first time, deduct balance from user
        if ($request->status === 'approved' && $wasPending) {
            $withdrawal->user->decrement('balance', (float)$withdrawal->amount);
        }

        // If rejected after being approved, refund balance to user
        if ($request->status === 'rejected' && !$wasPending && $withdrawal->status !== 'pending') {
            $withdrawal->user->increment('balance', (float)$withdrawal->amount);
        }

        flash()->success('Withdrawal status updated successfully');

        return redirect()->back();
    }
}
