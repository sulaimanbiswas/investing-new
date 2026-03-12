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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('is_admin', false);

        // Search by username, phone, or email
        if ($search = $request->string('search')->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
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

    public function admins(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin || (int) $admin->id !== 1) {
            abort(403, 'Only super admin can access admin users page.');
        }

        $query = User::where('is_admin', true);

        // Search by username, phone, or email
        if ($search = $request->string('search')->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
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

        $users = $query->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.admins', compact('users'));
    }

    public function show(User $user)
    {
        $admin = Auth::guard('admin')->user();

        // Only super admin can open user ID 1 profile.
        if ((int) $user->id === 1 && (!$admin || (int) $admin->id !== 1)) {
            abort(403, 'Only super admin can access this profile.');
        }

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
            ->orderBy('created_at', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        // Get all user orders with relationships
        $userOrders = UserOrder::with(['userOrderSet.orderSet', 'productPackageItem.productPackage'])
            ->whereHas('userOrderSet', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderByRaw("CASE WHEN status = 'unpaid' THEN 0 ELSE 1 END, 
                         CASE WHEN status = 'unpaid' THEN created_at ELSE NULL END,
                         CASE WHEN status = 'unpaid' THEN id ELSE NULL END,
                         CASE WHEN status = 'paid' THEN paid_at ELSE NULL END DESC")
            ->paginate(25);

        $gateways = \App\Models\Gateway::where('type', 'withdrawal')
            ->where('is_active', 1)
            ->get();

        return view('admin.users.show', compact('user', 'stats', 'orderSets', 'userOrderSets', 'userOrders', 'gateways'));
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
            'password' => $request->password,
        ]);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'Password changed successfully for user: ' . $user->username);
    }

    public function assignOrderSet(Request $request, User $user)
    {
        $validated = $request->validate([
            'order_set_id' => 'required|integer|exists:order_sets,id',
        ]);

        $orderSetId = (int) $validated['order_set_id'];
        $orderSet = OrderSet::with('productPackages.items.product')->findOrFail($orderSetId);

        // Validate order set has packages
        if ($orderSet->productPackages->isEmpty()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This order set has no packages. Cannot assign empty order set.'
                ], 422);
            }
            return redirect()
                ->route('admin.users.show', $user)
                ->with('error', 'This order set has no packages. Cannot assign empty order set.');
        }

        DB::beginTransaction();
        try {
            // Calculate totals for this assignment
            $totalAmount = 0;
            $totalPackages = $orderSet->productPackages->count();

            foreach ($orderSet->productPackages->sortBy('id') as $package) {
                foreach ($package->items as $item) {
                    $totalAmount += ($item->quantity * $item->price);
                }
            }

            // Create new user order set every time (allow unlimited assignments)
            $userOrderSet = UserOrderSet::create([
                'user_id' => $user->id,
                'order_set_id' => $orderSetId,
                'total_products' => $totalPackages,
                'completed_products' => 0,
                'total_amount' => $totalAmount,
                'profit_amount' => 0,
                'status' => 'active',
                'assigned_at' => now(),
            ]);

            // Ensure user order set was created
            if (!$userOrderSet || !$userOrderSet->id) {
                throw new \Exception('Failed to create user order set record');
            }

            // Create ONE order per package
            $ordersCreated = 0;
            foreach ($orderSet->productPackages->sortBy('id') as $package) {
                $orderAmount = 0;
                $manageCrypto = [];

                // Get first item for basic order info
                $items = $package->items->sortBy('id');
                $firstItem = $items->first();

                if (!$firstItem) {
                    continue; // Skip if package has no items
                }

                // Build manage_crypto array with all products in this package
                foreach ($items as $item) {
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

                $newOrder = UserOrder::create([
                    'user_order_set_id' => $userOrderSet->id,
                    'product_package_item_id' => $firstItem->id,
                    'order_number' => UserOrder::generateOrderNumber(),
                    'type' => 'normal',
                    'order_amount' => $orderAmount,
                    'profit_amount' => $profitAmount,
                    'balance_after' => $user->balance,
                    'status' => 'unpaid',
                    'manage_crypto' => $manageCrypto,
                ]);

                if (!$newOrder || !$newOrder->id) {
                    throw new \Exception('Failed to create user order');
                }

                $ordersCreated++;
            }

            DB::commit();

            $message = 'Order set "' . $orderSet->name . '" assigned! ' . $ordersCreated . ' orders created.';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message
                ], 200);
            }

            return redirect()
                ->route('admin.users.show', $user)
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to assign order set', [
                'user_id' => $user->id,
                'order_set_id' => $orderSetId,
                'error' => $e->getMessage(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to assign order set: ' . $e->getMessage()
                ], 500);
            }

            return redirect()
                ->route('admin.users.show', $user)
                ->with('error', 'Failed to assign order set: ' . $e->getMessage());
        }
    }

    public function deleteUserOrderSet(Request $request, User $user, UserOrderSet $userOrderSet)
    {
        try {
            // Verify the order set belongs to this user (defensive check)
            if ($userOrderSet->user_id != $user->id) {
                $message = 'Order set does not belong to this user.';
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $message], 422);
                }
                return redirect()->back()->with('error', $message);
            }

            $orderSetName = $userOrderSet->orderSet->name;

            // Delete all user orders associated with this order set
            UserOrder::where('user_order_set_id', $userOrderSet->id)->delete();

            // Delete the user order set
            $userOrderSet->delete();

            $successMessage = "Order set '{$orderSetName}' and its associated orders have been removed successfully.";

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => $successMessage], 200);
            }

            return redirect()
                ->route('admin.users.show', $user)
                ->with('success', $successMessage);
        } catch (\Exception $e) {
            $errorMessage = 'Failed to delete order set: ' . $e->getMessage();

            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $errorMessage], 500);
            }

            return redirect()
                ->route('admin.users.show', $user)
                ->with('error', $errorMessage);
        }
    }

    public function updateOrder(Request $request, User $user, UserOrder $order)
    {
        $belongsToUser = $order->userOrderSet && (int) $order->userOrderSet->user_id === (int) $user->id;
        if (!$belongsToUser) {
            return redirect()
                ->route('admin.users.show', $user)
                ->with('error', 'This order does not belong to the selected user.');
        }

        if ($order->status !== 'unpaid') {
            return redirect()
                ->route('admin.users.show', $user)
                ->with('error', 'Only unpaid orders can be updated.');
        }

        $validated = $request->validate([
            'order_amount' => 'nullable|numeric|min:0',
            'profit_amount' => 'nullable|numeric|min:0',
            'profit_percentage' => 'required|numeric|min:0',
            'manage_crypto' => 'nullable|array',
            'manage_crypto.*.quantity' => 'nullable|numeric|min:0',
            'manage_crypto.*.price' => 'nullable|numeric|min:0',
        ]);

        $existingManageCrypto = collect($order->manage_crypto ?? [])->values();
        $incomingManageCrypto = collect($validated['manage_crypto'] ?? [])->values();

        $manageCrypto = $existingManageCrypto->map(function ($item, $index) use ($incomingManageCrypto) {
            $incoming = $incomingManageCrypto->get($index, []);

            $quantity = array_key_exists('quantity', $incoming)
                ? (float) ($incoming['quantity'] ?? 0)
                : (float) ($item['quantity'] ?? 0);

            $price = array_key_exists('price', $incoming)
                ? (float) ($incoming['price'] ?? 0)
                : (float) ($item['price'] ?? 0);

            return [
                'name' => $item['name'] ?? null,
                'quantity' => $quantity,
                'price' => $price,
                'image' => $item['image'] ?? null,
            ];
        })->all();

        $calculatedOrderAmount = collect($manageCrypto)->sum(function ($item) {
            return ((float) ($item['quantity'] ?? 0)) * ((float) ($item['price'] ?? 0));
        });

        if ($calculatedOrderAmount <= 0) {
            $calculatedOrderAmount = isset($validated['order_amount']) ? (float) $validated['order_amount'] : (float) $order->order_amount;
        }

        $profitPercentage = (float) $validated['profit_percentage'];
        $calculatedProfitAmount = ($calculatedOrderAmount * $profitPercentage) / 100;

        $order->update([
            'order_amount' => round($calculatedOrderAmount, 2),
            'profit_amount' => round($calculatedProfitAmount, 2),
            'manage_crypto' => $manageCrypto,
        ]);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'Order updated successfully.');
    }

    public function updateManagement(Request $request, User $user)
    {
        $request->validate([
            'daily_order_limit' => 'required|integer|min:0',
            'freeze_amount' => 'required|numeric|min:0',
            'password' => 'nullable|string|min:6',
            'withdrawal_password' => 'nullable|string|min:6',
            'withdrawal_address' => 'nullable|string|max:255',
            'wallet_name' => 'nullable|string|max:255',
            'wallet_gateway' => 'nullable|string|max:255',
        ]);

        $updateData = [
            'daily_order_limit' => $request->daily_order_limit,
            'freeze_amount' => $request->freeze_amount,
            'withdrawal_address' => $request->withdrawal_address,
            'wallet_name' => $request->wallet_name,
            'wallet_gateway' => $request->wallet_gateway,
        ];

        // Update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = $request->password;
        }

        // Update withdrawal password if provided
        if ($request->filled('withdrawal_password')) {
            $updateData['withdrawal_password'] = $request->withdrawal_password;
        }

        $user->update($updateData);

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
        if ((int) $user->id === 1) {
            return back()->with('error', 'Super admin (ID 1) cannot be banned or inactivated.');
        }

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

    public function makeAdmin(User $user)
    {
        $admin = Auth::guard('admin')->user();

        // Only super admin (ID 1) can promote users to admin.
        if (!$admin || (int) $admin->id !== 1) {
            abort(403, 'Only super admin can make users admin.');
        }

        if ($user->is_admin) {
            return back()->with('success', 'This user is already an admin.');
        }

        $user->is_admin = true;
        $user->save();

        return back()->with('success', 'User has been promoted to admin successfully.');
    }

    public function makeUser(User $user)
    {
        $admin = Auth::guard('admin')->user();

        // Only super admin (ID 1) can demote admin users.
        if (!$admin || (int) $admin->id !== 1) {
            abort(403, 'Only super admin can make admin users normal users.');
        }

        if ((int) $user->id === 1) {
            return back()->with('error', 'Super admin (ID 1) cannot be converted to normal user.');
        }

        if (!$user->is_admin) {
            return back()->with('success', 'This user is already a normal user.');
        }

        $user->is_admin = false;
        $user->save();

        return back()->with('success', 'Admin user has been converted to normal user successfully.');
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
