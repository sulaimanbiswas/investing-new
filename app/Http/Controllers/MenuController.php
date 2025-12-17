<?php

namespace App\Http\Controllers;

use App\Models\Platform;
use App\Models\UserOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    /**
     * Display platforms menu with VIP levels
     */
    public function index(Request $request)
    {
        $vipLevel = $request->input('vip_level', 'all');

        $query = Platform::query();

        // Filter by VIP level based on package_name
        if ($vipLevel !== 'all') {
            $query->where('package_name', $vipLevel);
        }

        $platforms = $query->orderBy('start_price')->get();

        // Check if user has any active orders
        $user = Auth::user();
        $hasActiveOrder = UserOrder::whereHas('userOrderSet', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->where('status', 'unpaid')
            ->exists();

        return view('user.menu.index', compact('platforms', 'vipLevel', 'hasActiveOrder'));
    }

    /**
     * Display platform details with order statistics
     */
    public function show(Platform $platform)
    {
        $user = Auth::user();

        // Today's orders count (completed orders only)
        $todayOrdersCount = UserOrder::whereHas('userOrderSet', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->whereDate('paid_at', Carbon::today())
            ->where('status', 'paid')
            ->count();

        // Today's commission (profit) - from paid orders today
        $todayCommission = UserOrder::whereHas('userOrderSet', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->whereDate('paid_at', Carbon::today())
            ->where('status', 'paid')
            ->sum('profit_amount') ?? 0;

        // Yesterday's commission (profit) - from paid orders yesterday
        $yesterdayCommission = UserOrder::whereHas('userOrderSet', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->whereDate('paid_at', Carbon::yesterday())
            ->where('status', 'paid')
            ->sum('profit_amount') ?? 0;

        // Cash gap between task will be calculated later based on unpaid order
        $cashGap = 0;

        // Yesterday's team commission - Calculate from referrals' orders
        $yesterdayTeamCommission = UserOrder::whereHas('userOrderSet', function ($query) use ($user) {
            $query->whereHas('user', function ($q) use ($user) {
                $q->where('referred_by', $user->id);
            });
        })
            ->whereDate('paid_at', Carbon::yesterday())
            ->where('status', 'paid')
            ->sum('profit_amount') ?? 0;

        // Find user's VIP platform based on balance
        $userVipPlatform = Platform::where('start_price', '<=', $user->balance)
            ->where('end_price', '>=', $user->balance)
            ->first();

        $userVipLevel = $userVipPlatform ? strtoupper($userVipPlatform->package_name) : null;
        $hasVipLevel = $userVipPlatform !== null;

        // Check if this is user's VIP platform
        $isUserVipPlatform = $userVipPlatform && $userVipPlatform->id === $platform->id;

        // Get first unpaid order - ONLY show on user's current VIP level platform
        $unpaidOrder = null;
        if ($isUserVipPlatform) {
            $unpaidOrder = UserOrder::whereHas('userOrderSet', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
                ->where('status', 'unpaid')
                ->with(['productPackageItem.product', 'productPackageItem.productPackage'])
                ->first();
        }

        // Count today's completed orders
        $todayCompletedCount = UserOrder::whereHas('userOrderSet', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->whereDate('paid_at', Carbon::today())
            ->where('status', 'paid')
            ->count();

        // Check if user's balance is within platform range
        $canGrabOrder = $user->balance >= $platform->start_price && $user->balance <= $platform->end_price;

        // Check if user has reached daily order limit
        $hasReachedDailyLimit = $todayCompletedCount >= $user->daily_order_limit;

        // Calculate cash gap if there's an unpaid order (how much more balance needed)
        if ($unpaidOrder) {
            $cashGap = max(0, $unpaidOrder->order_amount - $user->balance);
        }

        return view('user.menu.show', compact(
            'platform',
            'user',
            'todayOrdersCount',
            'todayCommission',
            'yesterdayCommission',
            'cashGap',
            'yesterdayTeamCommission',
            'unpaidOrder',
            'canGrabOrder',
            'hasReachedDailyLimit',
            'todayCompletedCount',
            'userVipLevel',
            'isUserVipPlatform',
            'hasVipLevel'
        ));
    }

    /**
     * Grab a new order from the platform
     */
    public function grabOrder(Platform $platform)
    {
        $user = Auth::user();

        // Check daily order limit
        $todayCompletedCount = UserOrder::whereHas('userOrderSet', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->whereDate('paid_at', Carbon::today())
            ->where('status', 'paid')
            ->count();

        if ($todayCompletedCount >= $user->daily_order_limit) {
            return response()->json([
                'success' => false,
                'message' => "You have reached your daily order limit ({$user->daily_order_limit} orders)."
            ]);
        }

        // Check if user's balance is within platform range
        if ($user->balance < $platform->start_price || $user->balance > $platform->end_price) {
            // Find the correct platform for user's balance
            $correctPlatform = Platform::where('start_price', '<=', $user->balance)
                ->where('end_price', '>=', $user->balance)
                ->first();

            $correctPlatformName = $correctPlatform ? $correctPlatform->name : 'appropriate platform';
            $userVipLevel = $correctPlatform ? strtoupper($correctPlatform->package_name) : 'current VIP';

            return response()->json([
                'success' => false,
                'message' => "You are in {$userVipLevel}, go to {$correctPlatformName} to get tasks."
            ]);
        }

        // Check if user already has any unpaid order (from their VIP level platform)
        $existingOrder = UserOrder::whereHas('userOrderSet', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->where('status', 'unpaid')
            ->exists();

        if ($existingOrder) {
            return response()->json([
                'success' => false,
                'message' => 'You already have an unpaid order. Please complete it first.'
            ]);
        }

        // Get a random active order set for this platform
        $orderSet = \App\Models\OrderSet::where('platform_id', $platform->id)
            ->where('is_active', true)
            ->inRandomOrder()
            ->first();

        if (!$orderSet) {
            return response()->json([
                'success' => false,
                'message' => 'No orders available for this platform at the moment.'
            ]);
        }

        // Get or create user order set
        $userOrderSet = \App\Models\UserOrderSet::firstOrCreate(
            [
                'user_id' => $user->id,
                'order_set_id' => $orderSet->id,
            ],
            [
                'total_products' => 0,
                'completed_products' => 0,
                'total_amount' => 0,
                'profit_amount' => 0,
                'status' => 'active',
                'assigned_at' => now(),
            ]
        );

        // Get a random product package item from this order set
        $productPackageItem = \App\Models\ProductPackageItem::whereHas('productPackage', function ($query) use ($orderSet) {
            $query->where('order_set_id', $orderSet->id)
                ->where('is_active', true);
        })
            ->with(['product', 'productPackage'])
            ->inRandomOrder()
            ->first();

        if (!$productPackageItem) {
            return response()->json([
                'success' => false,
                'message' => 'No products available in this order set.'
            ]);
        }

        // Create the order
        $orderAmount = $productPackageItem->price * $productPackageItem->quantity;
        $profitAmount = ($orderAmount * $productPackageItem->productPackage->profit_percentage) / 100;

        $order = UserOrder::create([
            'user_order_set_id' => $userOrderSet->id,
            'product_package_item_id' => $productPackageItem->id,
            'order_number' => UserOrder::generateOrderNumber(),
            'type' => $productPackageItem->productPackage->type,
            'product_name' => $productPackageItem->product->name,
            'quantity' => $productPackageItem->quantity,
            'price' => $productPackageItem->price,
            'order_amount' => $orderAmount,
            'profit_amount' => $profitAmount,
            'balance_after' => $user->balance,
            'status' => 'unpaid',
            'manage_crypto' => [
                [
                    'name' => $productPackageItem->product->name,
                    'quantity' => $productPackageItem->quantity,
                    'price' => $productPackageItem->price,
                    'image' => $productPackageItem->product->image,
                ]
            ],
        ]);

        return response()->json([
            'success' => true,
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'product_name' => $order->product_name,
                'quantity' => $order->quantity,
                'price' => number_format((float) $order->price, 2),
                'order_amount' => number_format((float) $order->order_amount, 2),
                'commission' => number_format((float) $order->profit_amount, 2),
                'expected_income' => number_format((float) $user->balance + (float) $order->profit_amount, 2),
                'manage_crypto' => $order->manage_crypto,
            ]
        ]);
    }

    /**
     * Submit/Pay for an order
     */
    public function submitOrder(Request $request, UserOrder $order)
    {
        $user = Auth::user();
        \Log::info('SubmitOrder Auth User', [
            'user_id' => $user ? $user->id : null,
            'user_email' => $user ? $user->email : null,
            'order_id' => $order->id,
            'order_user_order_set_id' => $order->user_order_set_id,
        ]);

        // Verify order belongs to user
        if ($order->userOrderSet->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        // Check if order is already paid
        if ($order->status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'This order has already been paid.'
            ]);
        }

        // Check if user has sufficient balance to complete the order
        if ($user->balance < $order->order_amount) {
            $needed = $order->order_amount - $user->balance;
            return response()->json([
                'success' => false,
                'message' => "Insufficient balance. You need $" . number_format($needed, 2) . " more USDT to complete this order. Please deposit first."
            ]);
        }

        // Update order status and user balance
        $profitAmount = (float) $order->profit_amount;
        $orderAmount = (float) $order->order_amount;
        $balanceBefore = (float) $user->balance;

        // Transaction 1: Deduct order amount from balance
        $balanceAfterPayment = $balanceBefore - $orderAmount;
        $user->decrement('balance', $orderAmount);

        $baseTime = now();

        // Log transaction: order payment (deduction)
        $txn1 = \App\Models\Transaction::create([
            'user_id' => $user->id,
            'type' => 'order_payment',
            'reference_id' => $order->id,
            'amount' => -$orderAmount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfterPayment,
            'remarks' => 'Payment for order ' . $order->order_number,
        ]);
        $txn1->created_at = $baseTime;
        $txn1->save();

        // Transaction 2: Return principal to balance
        $balanceBeforePrincipalReturn = (float) $user->fresh()->balance; // after deduction
        $balanceAfterPrincipalReturn = $balanceBeforePrincipalReturn + $orderAmount;
        $user->increment('balance', $orderAmount);

        $txn2 = \App\Models\Transaction::create([
            'user_id' => $user->id,
            'type' => 'order_principal_return',
            'reference_id' => $order->id,
            'amount' => $orderAmount,
            'balance_before' => $balanceBeforePrincipalReturn,
            'balance_after' => $balanceAfterPrincipalReturn,
            'remarks' => 'Principal returned for order ' . $order->order_number,
        ]);
        $txn2->created_at = $baseTime->copy()->addSeconds(1);
        $txn2->save();

        // Transaction 3: Add profit to balance
        $balanceBeforeProfit = (float) $user->fresh()->balance;
        $balanceAfterProfit = $balanceBeforeProfit + $profitAmount;
        $user->increment('balance', $profitAmount);

        $txn3 = \App\Models\Transaction::create([
            'user_id' => $user->id,
            'type' => 'order_profit',
            'reference_id' => $order->id,
            'amount' => $profitAmount,
            'balance_before' => $balanceBeforeProfit,
            'balance_after' => $balanceAfterProfit,
            'remarks' => 'Commission from order ' . $order->order_number,
        ]);
        $txn3->created_at = $baseTime->copy()->addSeconds(2);
        $txn3->save();

        // Update order status
        $order->update([
            'status' => 'paid',
            'paid_at' => now(),
            'balance_after' => $balanceAfterProfit,
        ]);

        // Update user order set
        $order->userOrderSet->increment('completed_products');
        $order->userOrderSet->increment('total_amount', $orderAmount);
        $order->userOrderSet->increment('profit_amount', $profitAmount);

        return response()->json([
            'success' => true,
            'message' => 'Order completed successfully!',
            'new_balance' => number_format((float) $user->fresh()->balance, 2)
        ]);
    }
}
