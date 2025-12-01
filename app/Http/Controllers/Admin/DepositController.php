<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Gateway;
use App\Models\User;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    /**
     * Display deposit history with filters
     */
    public function index(Request $request)
    {
        $query = Deposit::with(['user', 'gateway']);

        // User filter
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Gateway filter
        if ($request->filled('gateway_id')) {
            $query->where('gateway_id', $request->gateway_id);
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
                    ->orWhere('txn_id', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('username', 'like', "%{$search}%");
                    });
            });
        }

        $deposits = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get filter data
        $users = User::orderBy('name')->get(['id', 'name', 'username', 'email']);
        $gateways = Gateway::where('type', 'payment')->get(['id', 'name']);

        // Calculate statistics
        $stats = [
            'approved' => Deposit::where('status', 'approved')->sum('amount'),
            'pending' => Deposit::where('status', 'pending')->sum('amount'),
            'rejected' => Deposit::where('status', 'rejected')->sum('amount'),
            'initialed' => Deposit::where('status', 'initialed')->sum('amount'),
        ];

        return view('admin.deposits.index', compact('deposits', 'users', 'gateways', 'stats'));
    }

    /**
     * Show deposit details
     */
    public function show(Deposit $deposit)
    {
        $deposit->load(['user.deposits', 'gateway']);
        return view('admin.deposits.show', compact('deposit'));
    }

    /**
     * Update deposit status
     */
    public function updateStatus(Request $request, Deposit $deposit)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'admin_note' => 'nullable|string|max:1000'
        ]);

        $deposit->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note
        ]);

        // If approved, add balance to user
        if ($request->status === 'approved' && $deposit->status !== 'approved') {
            $deposit->user->increment('balance', $deposit->amount);
        }

        flash()->success('Deposit status updated successfully');

        return redirect()->back();
    }
}