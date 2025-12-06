<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $availableBalance = $user->balance - $user->freeze_amount;

        return view('user.withdrawal.index', [
            'user' => $user,
            'availableBalance' => $availableBalance,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $availableBalance = $user->balance - $user->freeze_amount;

        $data = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'wallet_address' => 'required|string|max:255',
        ]);

        // Check if amount exceeds available balance
        if ($data['amount'] > $availableBalance) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient available balance. Available: $' . number_format($availableBalance, 2),
            ]);
        }

        // Generate unique order number
        $orderNumber = 'WD' . strtoupper(uniqid());

        // Create withdrawal request
        Withdrawal::create([
            'user_id' => $user->id,
            'order_number' => $orderNumber,
            'amount' => $data['amount'],
            'wallet_address' => $data['wallet_address'],
            'currency' => 'USDT',
            'status' => 'pending',
        ]);

        // Update user's wallet address if changed
        if ($user->withdrawal_address !== $data['wallet_address']) {
            $user->withdrawal_address = $data['wallet_address'];
            $user->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Your withdrawal request has been submitted successfully. Please wait for admin approval.',
        ]);
    }

    public function records()
    {
        $withdrawals = Auth::user()
            ->withdrawals()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('user.withdrawal.records', compact('withdrawals'));
    }
}
