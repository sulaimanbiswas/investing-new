<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal;
use App\Models\Gateway;
use App\Models\UserOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class WithdrawalController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $availableBalance = $user->balance - $user->freeze_amount;

        $gateways = Gateway::where('type', 'withdrawal')
            ->where('is_active', 1)
            ->get();

        return view('user.withdrawal.index', [
            'user' => $user,
            'availableBalance' => $availableBalance,
            'gateways' => $gateways,
        ]);
    }

    public function checkPendingOrders()
    {
        $user = Auth::user();

        // Check if user has any unpaid orders
        $hasPendingOrders = UserOrder::whereHas('userOrderSet', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->where('status', 'unpaid')
            ->exists();

        return response()->json([
            'has_pending_orders' => $hasPendingOrders
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $availableBalance = $user->balance - $user->freeze_amount;

        $data = $request->validate([
            'gateway_id' => 'required|exists:gateways,id',
            'amount' => 'required|numeric|min:0.01',
            'withdrawal_password' => 'required|string',
            'custom_data' => 'nullable|array',
        ]);

        // Validate withdrawal password
        if ($data['withdrawal_password'] !== $user->withdrawal_password) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid withdrawal password.',
            ]);
        }

        // Get gateway and validate
        $gateway = Gateway::where('id', $data['gateway_id'])
            ->where('type', 'withdrawal')
            ->where('is_active', 1)
            ->firstOrFail();

        // Validate amount against gateway limits
        if ($data['amount'] < $gateway->min_limit || $data['amount'] > $gateway->max_limit) {
            return response()->json([
                'success' => false,
                'message' => 'Amount must be between ' . $gateway->min_limit . ' and ' . $gateway->max_limit . ' ' . $gateway->currency,
            ]);
        }

        // Check if amount exceeds available balance
        if ($data['amount'] > $availableBalance) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient available balance. Available: ' . number_format($availableBalance, 2) . ' USDT',
            ]);
        }

        // Generate unique order number
        $orderNumber = 'WD' . strtoupper(uniqid());

        // Create withdrawal request - use user's default wallet address
        Withdrawal::create([
            'user_id' => $user->id,
            'gateway_id' => $gateway->id,
            'order_number' => $orderNumber,
            'amount' => $data['amount'],
            'currency' => $gateway->currency,
            'wallet_address' => $user->withdrawal_address, // Use default wallet address
            'status' => 'pending',
            'custom_data' => $data['custom_data'] ?? null,
        ]);

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
