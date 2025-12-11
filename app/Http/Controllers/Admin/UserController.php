<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\OrderSet;
use App\Models\UserOrderSet;
use App\Models\UserOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

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

        // Status filter
        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        // Date range filter
        if ($from = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $users = $query->with(['userOrderSets.orderSet'])
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

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
            'total_withdrawals' => $user->withdrawals()->where('status', 'approved')->sum('amount'),
            'total_transactions' => $user->deposits()->count(),
            'total_invest' => 0, // TODO: Implement when investment system is ready
            'total_referral_commission' => \App\Models\ReferralCommission::where('user_id', $user->id)->sum('commission_amount'),
            'total_binary_commission' => 0, // TODO: Implement when binary system is ready
            'total_bv' => 0, // TODO: Implement when BV system is ready
        ];

        // Get all order sets for assignment dropdown
        $orderSets = OrderSet::where('is_active', true)->orderBy('name')->get();

        // Get assigned order sets for this user
        $userOrderSets = UserOrderSet::with('orderSet')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        // Get all user orders with relationships
        $userOrders = UserOrder::with(['userOrderSet.orderSet', 'productPackageItem.productPackage'])
            ->whereHas('userOrderSet', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderByRaw("CASE WHEN status = 'unpaid' THEN 0 ELSE 1 END")
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.users.show', compact('user', 'stats', 'orderSets', 'userOrderSets', 'userOrders'));
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
                ->with('error', 'Insufficient balance. User has USDT' . number_format((float)$user->balance, 2) . ' but you tried to subtract USDT' . number_format((float)$request->amount, 2) . '.');
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

    public function assignOrderSet(Request $request, User $user)
    {
        $request->validate([
            'order_set_id' => 'required|exists:order_sets,id',
        ]);

        $orderSet = OrderSet::with('productPackages.items.product')->findOrFail($request->order_set_id);

        // Check if already assigned
        $existing = UserOrderSet::where('user_id', $user->id)
            ->where('order_set_id', $orderSet->id)
            ->where('status', '!=', 'completed')
            ->first();

        if ($existing) {
            return redirect()
                ->route('admin.users.show', $user)
                ->with('error', 'This order set is already assigned to this user.');
        }

        DB::beginTransaction();
        try {
            if ($orderSet->productPackages->isEmpty()) {
                DB::rollBack();
                return redirect()
                    ->route('admin.users.show', $user)
                    ->with('error', 'This order set has no packages.');
            }

            // Calculate totals
            $totalAmount = 0;
            $totalPackages = $orderSet->productPackages->count();

            foreach ($orderSet->productPackages as $package) {
                foreach ($package->items as $item) {
                    $totalAmount += ($item->quantity * $item->price);
                }
            }

            // Create user order set
            $userOrderSet = UserOrderSet::create([
                'user_id' => $user->id,
                'order_set_id' => $orderSet->id,
                'total_products' => $totalPackages,
                'completed_products' => 0,
                'total_amount' => $totalAmount,
                'profit_amount' => 0,
                'status' => 'active',
                'assigned_at' => now(),
            ]);

            // Create ONE order per package
            foreach ($orderSet->productPackages as $package) {
                $orderAmount = 0;
                $manageCrypto = [];

                // Get first item for basic order info
                $firstItem = $package->items->first();

                // Build manage_crypto array with all products in this package
                foreach ($package->items as $item) {
                    $itemAmount = $item->quantity * $item->price;
                    $orderAmount += $itemAmount;

                    $manageCrypto[] = [
                        'name' => $item->product->name,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'image' => $item->product->image,
                    ];
                }

                // Calculate profit from package profit_percentage
                $profitAmount = $orderAmount * ($package->profit_percentage / 100);

                UserOrder::create([
                    'user_order_set_id' => $userOrderSet->id,
                    'product_package_item_id' => $firstItem->id,
                    'order_number' => UserOrder::generateOrderNumber(),
                    'type' => 'normal',
                    'product_name' => $firstItem->product->name,
                    'quantity' => $firstItem->quantity,
                    'price' => $firstItem->price,
                    'order_amount' => $orderAmount,
                    'profit_amount' => $profitAmount,
                    'balance_after' => $user->balance,
                    'status' => 'unpaid',
                    'manage_crypto' => $manageCrypto,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('admin.users.show', $user)
                ->with('success', 'Order set assigned successfully! Total ' . $totalPackages . ' orders created.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('admin.users.show', $user)
                ->with('error', 'Failed to assign order set: ' . $e->getMessage());
        }
    }

    public function deleteUserOrderSet(Request $request, User $user, UserOrderSet $userOrderSet)
    {
        // Verify the order set belongs to this user
        if ($userOrderSet->user_id !== $user->id) {
            return redirect()
                ->route('admin.users.show', $user)
                ->with('error', 'Unauthorized action.');
        }

        try {
            $orderSetName = $userOrderSet->orderSet->name;

            // Delete all user orders associated with this order set
            UserOrder::where('user_order_set_id', $userOrderSet->id)->delete();

            // Delete the user order set
            $userOrderSet->delete();

            return redirect()
                ->route('admin.users.show', $user)
                ->with('success', "Order set '{$orderSetName}' and its associated orders have been removed successfully.");
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.users.show', $user)
                ->with('error', 'Failed to delete order set: ' . $e->getMessage());
        }
    }

    public function updateManagement(Request $request, User $user)
    {
        $request->validate([
            'daily_order_limit' => 'required|integer|min:0',
            'freeze_amount' => 'required|numeric|min:0',
            'withdrawal_address' => 'nullable|string|max:255',
        ]);

        $user->update([
            'daily_order_limit' => $request->daily_order_limit,
            'freeze_amount' => $request->freeze_amount,
            'withdrawal_address' => $request->withdrawal_address,
        ]);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'User management settings updated successfully!');
    }

    public function updateCommissions(Request $request, User $user)
    {
        $request->validate([
            'level1_commission' => 'required|numeric|min:0|max:100',
            'level2_commission' => 'required|numeric|min:0|max:100',
            'level3_commission' => 'required|numeric|min:0|max:100',
        ]);

        $user->update([
            'level1_commission' => $request->level1_commission,
            'level2_commission' => $request->level2_commission,
            'level3_commission' => $request->level3_commission,
        ]);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'Referral commission rates updated successfully for ' . $user->username . '!');
    }

    public function loginAsUser(User $user)
    {
        // Generate a URL-safe token for user login to avoid "/" or "+" in the path
        $token = Str::random(64);

        // Store token in cache for 5 minutes with user ID
        Cache::put('admin_login_as_' . $token, [
            'user_id' => $user->id,
            'admin_id' => Auth::id(),
        ], now()->addMinutes(5));

        // Return URL to open in new tab
        $loginUrl = route('admin.users.impersonate', ['token' => $token]);

        return response()->json([
            'success' => true,
            'url' => $loginUrl
        ]);
    }

    public function impersonate($token)
    {
        // Get data from cache
        $data = Cache::get('admin_login_as_' . $token);

        if (!$data) {
            return redirect()->route('login')->with('error', 'Invalid or expired login token.');
        }

        // Delete token from cache (one-time use)
        Cache::forget('admin_login_as_' . $token);

        // Find user
        $user = User::find($data['user_id']);

        if (!$user) {
            return redirect()->route('login')->with('error', 'User not found.');
        }

        // Store admin info in session for return button
        Session::put('impersonated_by_admin', $data['admin_id']);
        Session::put('original_user_id', $user->id);

        // Login as user
        Auth::guard('web')->login($user);
        request()->session()->regenerate();

        return redirect()->route('dashboard')->with('success', 'You are now logged in as ' . $user->username);
    }
    public function returnToAdmin()
    {
        $adminId = Session::get('impersonated_by_admin');
        $userId = Session::get('original_user_id');

        if (!$adminId) {
            return redirect()->route('dashboard')->with('error', 'No admin impersonation session found.');
        }

        // Find admin before any logout
        $admin = User::find($adminId);

        if (!$admin || !$admin->is_admin) {
            Session::forget('impersonated_by_admin');
            Session::forget('original_user_id');
            return redirect()->route('admin.login')->with('error', 'Admin account not found.');
        }

        // Logout user from web guard
        Auth::guard('web')->logout();

        // Clear the session markers before logging in as admin
        Session::forget('impersonated_by_admin');
        Session::forget('original_user_id');

        // Login as admin using admin guard
        Auth::guard('admin')->login($admin);

        // Regenerate session for security
        request()->session()->regenerate();

        // Redirect back to user details page if we have the user ID
        if ($userId) {
            return redirect()->route('admin.users.show', $userId)->with('success', 'Returned to admin panel.');
        }

        return redirect()->route('admin.dashboard')->with('success', 'Returned to admin panel.');
    }

    public function banUser(Request $request, User $user)
    {
        // Prevent banning admin users
        if ($user->is_admin) {
            return back()->with('error', 'Cannot ban admin users.');
        }

        // Validate the ban reason
        $request->validate([
            'ban_reason' => 'required|string|max:500',
        ]);

        // Ban the user by changing status to banned
        $user->update([
            'status' => 'banned',
            'ban_reason' => $request->ban_reason,
            'banned_at' => now(),
        ]);

        // If user is currently logged in, log them out
        if (Auth::guard('web')->id() === $user->id) {
            Auth::guard('web')->logout();
        }

        return back()->with('success', 'User has been banned successfully.');
    }

    public function unbanUser(User $user)
    {
        // Unban the user by changing status to active
        $user->update([
            'status' => 'active',
            'ban_reason' => null,
            'banned_at' => null,
        ]);

        return back()->with('success', 'User has been unbanned successfully.');
    }

    public function userTree(User $user)
    {
        // Load all referrals recursively (up to 4 levels)
        $user->load([
            'referrals' => function ($query) {
                $query->with([
                    'referrals' => function ($query) {
                        $query->with([
                            'referrals' => function ($query) {
                                $query->with('referrals');
                            }
                        ]);
                    }
                ]);
            }
        ]);

        return view('admin.users.tree', compact('user'));
    }
}
