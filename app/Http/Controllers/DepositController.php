<?php

namespace App\Http\Controllers;

use App\Models\Gateway;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    /**
     * Display deposit page with available gateways
     */
    public function index()
    {
        // Check for pending deposit
        $pendingDeposit = Deposit::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->with('gateway')
            ->first();

        $gateways = Gateway::where('type', 'payment')
            ->where('is_active', 1)
            ->get();

        return view('user.deposit.index', compact('gateways', 'pendingDeposit'));
    }

    /**
     * Display deposit confirmation page and create initialed deposit
     */
    public function confirm(Request $request)
    {
        $request->validate([
            'deposit_id' => 'required|exists:deposits,id'
        ]);

        $deposit = Deposit::where('id', $request->deposit_id)
            ->where('user_id', Auth::id())
            ->where('status', 'initialed')
            ->with('gateway')
            ->firstOrFail();

        $gateway = $deposit->gateway;

        return view('user.deposit.confirm', compact('gateway', 'deposit'));
    }

    /**
     * Create initialed deposit via AJAX (Deposit Now button)
     */
    public function createInitialed(Request $request)
    {
        $request->validate([
            'gateway' => 'required|exists:gateways,id',
            'amount' => 'required|numeric|min:0.01'
        ]);

        $gateway = Gateway::findOrFail($request->gateway);

        if ($request->amount < $gateway->min_limit || $request->amount > $gateway->max_limit) {
            return response()->json(['success' => false, 'message' => 'Amount must be between ' . $gateway->min_limit . ' and ' . $gateway->max_limit]);
        }

        // Prevent multiple initialed deposits for this user/gateway/amount
        $deposit = Deposit::where('user_id', Auth::id())
            ->where('gateway_id', $gateway->id)
            ->where('amount', $request->amount)
            ->where('status', 'initialed')
            ->latest('created_at')
            ->first();

        if (!$deposit) {
            $deposit = Deposit::create([
                'user_id' => Auth::id(),
                'gateway_id' => $gateway->id,
                'amount' => $request->amount,
                'currency' => $gateway->currency,
                'status' => 'initialed',
                'order_number' => 'DEP' . time() . rand(1000, 9999)
            ]);
        }

        return response()->json([
            'success' => true,
            'redirect' => route('deposit.confirm', ['deposit_id' => $deposit->id])
        ]);
    }

    /**
     * Update deposit to pending status when user confirms payment
     */
    public function store(Request $request)
    {
        $request->validate([
            'deposit_id' => 'required|exists:deposits,id',
            'txn_id' => 'nullable|string|max:255',
            'screenshot' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120'
        ]);

        $deposit = Deposit::where('id', $request->deposit_id)
            ->where('user_id', Auth::id())
            ->where('status', 'initialed')
            ->firstOrFail();

        $data = ['status' => 'pending'];

        // Handle transaction ID
        if ($request->filled('txn_id')) {
            $data['txn_id'] = $request->txn_id;
        }

        // Handle screenshot upload
        if ($request->hasFile('screenshot')) {
            $file = $request->file('screenshot');
            $publicDir = public_path('uploads/deposits');

            if (!is_dir($publicDir)) {
                mkdir($publicDir, 0775, true);
            }

            $filename = 'deposit_' . $deposit->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($publicDir, $filename);

            $data['screenshot_path'] = '/uploads/deposits/' . $filename;
        }

        // Update to pending status with transaction details
        $deposit->update($data);

        // Flash success message for next page load
        flash()
            ->options([
                'timeout' => 5000,
                'position' => 'top-center'
            ])
            ->success('Your deposit request has been successfully submitted. Please wait for admin approval.');

        return response()->json([
            'success' => true,
            'redirect' => route('deposit.records')
        ]);
    }

    /**
     * Display user's deposit records
     */
    public function records()
    {
        $deposits = Deposit::where('user_id', Auth::id())
            ->with('gateway')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.deposit.records', compact('deposits'));
    }
}
