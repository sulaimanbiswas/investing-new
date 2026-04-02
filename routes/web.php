<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController as AdminAuthController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\Admin\TransactionLogController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::post('/locale/{locale}', function (string $locale) {
    $supported = array_keys(config('localization.supported_locales', ['en' => 'English']));

    if ($locale === 'zh') {
        $locale = 'zh-CN';
    }

    if (strtolower($locale) === 'pt-br') {
        $locale = 'pt-BR';
    }

    abort_unless(in_array($locale, $supported, true), 404);

    session(['locale' => $locale]);

    return back();
})->name('locale.switch');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $platformRules = \App\Models\PlatformRule::where('is_active', true)
        ->orderBy('sort_by')
        ->take(4)
        ->get();
    return view('user.dashboard.index', compact('platformRules'));
})->middleware(['auth', 'verified', 'check.banned'])->name('dashboard');

Route::get('/invitation', function () {
    return view('user.invitation.index');
})->middleware(['auth', 'verified'])->name('invitation');

Route::get('/teams', [\App\Http\Controllers\TeamsController::class, 'index'])->middleware(['auth', 'verified'])->name('teams');

Route::get('/platform-rules', [\App\Http\Controllers\PlatformRulesController::class, 'index'])->middleware(['auth', 'verified'])->name('platform-rules');

Route::get('/platform-rules/{id}', [\App\Http\Controllers\PlatformRulesController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('platform-rules.show');

Route::get('/menu', [\App\Http\Controllers\MenuController::class, 'index'])->middleware(['auth', 'verified'])->name('menu.index');
Route::get('/menu/platform/{platform}', [\App\Http\Controllers\MenuController::class, 'show'])->middleware(['auth', 'verified'])->name('menu.platform.show');
Route::post('/menu/platform/{platform}/grab-order', [\App\Http\Controllers\MenuController::class, 'grabOrder'])->middleware(['auth', 'verified'])->name('menu.platform.grab-order');
Route::post('/menu/order/{order}/submit', [\App\Http\Controllers\MenuController::class, 'submitOrder'])->middleware(['auth', 'verified'])->name('menu.order.submit');
Route::post('/menu/request-order', [\App\Http\Controllers\MenuController::class, 'requestOrder'])->middleware(['auth', 'verified'])->name('menu.request-order');

Route::get('/service', [\App\Http\Controllers\ServiceController::class, 'index'])->middleware(['auth', 'verified'])->name('service.index');
Route::get('/help', [\App\Http\Controllers\HelpController::class, 'index'])->middleware(['auth', 'verified'])->name('help.index');

Route::middleware(['auth', 'check.banned'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/profile/withdrawal-password', [ProfileController::class, 'updateWithdrawalPassword'])->name('profile.update-withdrawal-password');
    // Wallet management
    Route::get('/wallet', [ProfileController::class, 'wallet'])->name('wallet.edit');
    Route::post('/wallet', [ProfileController::class, 'updateWallet'])->name('wallet.update');
    // User profile dashboard
    Route::get('/me', [\App\Http\Controllers\ProfileController::class, 'home'])->name('profile.home');

    // Deposit routes
    Route::get('/deposit', [\App\Http\Controllers\DepositController::class, 'index'])->name('deposit');
    Route::get('/deposit/confirm', [\App\Http\Controllers\DepositController::class, 'confirm'])->name('deposit.confirm');
    Route::post('/deposit/create-initialed', [\App\Http\Controllers\DepositController::class, 'createInitialed'])->name('deposit.create-initialed');
    Route::post('/deposit', [\App\Http\Controllers\DepositController::class, 'store'])->name('deposit.store');
    Route::get('/deposit/records', [\App\Http\Controllers\DepositController::class, 'records'])->name('deposit.records');

    // Withdrawal routes
    Route::get('/withdrawal', [\App\Http\Controllers\WithdrawalController::class, 'index'])->name('withdrawal');
    Route::get('/withdrawal/check-pending', [\App\Http\Controllers\WithdrawalController::class, 'checkPendingOrders'])->name('withdrawal.check-pending-orders');
    Route::post('/withdrawal', [\App\Http\Controllers\WithdrawalController::class, 'store'])->name('withdrawal.store');
    Route::get('/withdrawal/records', [\App\Http\Controllers\WithdrawalController::class, 'records'])->name('withdrawal.records');

    Route::get('/withdrawal/check-pending-withdrawal', [\App\Http\Controllers\WithdrawalController::class, 'checkPendingWithdrawals'])->name('withdrawal.check-pending-withdrawal');


    // Referral Commission routes
    Route::get('/commissions', [\App\Http\Controllers\ReferralCommissionController::class, 'index'])->name('commissions.index');

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::post('/notifications/{notification}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::get('/notifications/{notification}/go', [\App\Http\Controllers\NotificationController::class, 'go'])->name('notifications.go');

    // Order routes
    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');

    // Records summary
    Route::get('/records', [RecordController::class, 'index'])->name('records.index');

    // Return to admin
    Route::post('/return-to-admin', [\App\Http\Controllers\Admin\UserController::class, 'returnToAdmin'])->name('return-to-admin');
});

// Admin auth routes
Route::prefix('admin')->name('admin.')->group(function () {
    // No guest middleware - controller handles already logged in users
    Route::get('/login', [AdminAuthController::class, 'create'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'store'])->name('login.store');

    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('logout');

        // Profile management
        Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('profile.index');
        Route::put('/profile/update', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');

        // CKEditor upload endpoint
        Route::post('/uploads/ckeditor', [\App\Http\Controllers\Admin\UploadController::class, 'ckeditor'])->name('ckeditor.upload');

        // QR image upload endpoint (Dropzone)
        Route::post('/uploads/qrs', [\App\Http\Controllers\Admin\UploadController::class, 'qr'])->name('uploads.qr');
        Route::post('/uploads/qrs/delete', [\App\Http\Controllers\Admin\UploadController::class, 'deleteQr'])->name('uploads.qr.delete');

        // Gateway logo upload endpoint
        Route::middleware('super.admin')->group(function () {
            Route::post('/uploads/gateways', [\App\Http\Controllers\Admin\UploadController::class, 'gateway'])->name('uploads.gateway');
            Route::post('/uploads/gateways/delete', [\App\Http\Controllers\Admin\UploadController::class, 'deleteGateway'])->name('uploads.gateway.delete');
        });

        // Logo and Favicon upload endpoints
        Route::post('/uploads/logo', [\App\Http\Controllers\Admin\UploadController::class, 'logo'])->name('uploads.logo');
        Route::post('/uploads/favicon', [\App\Http\Controllers\Admin\UploadController::class, 'favicon'])->name('uploads.favicon');
        Route::post('/uploads/og-image', [\App\Http\Controllers\Admin\UploadController::class, 'ogImage'])->name('uploads.og-image');

        // Users management pages (views first)
        Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::get('/users/admins', [\App\Http\Controllers\Admin\UserController::class, 'admins'])->name('users.admins');
        Route::get('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
        Route::post('/users/{user}/add-balance', [\App\Http\Controllers\Admin\UserController::class, 'addBalance'])->name('users.add-balance');
        Route::post('/users/{user}/subtract-balance', [\App\Http\Controllers\Admin\UserController::class, 'subtractBalance'])->name('users.subtract-balance');
        Route::post('/users/{user}/change-password', [\App\Http\Controllers\Admin\UserController::class, 'changePassword'])->name('users.change-password');
        // Lightweight ping to keep the admin session active while the page is open
        Route::get('/session/ping', function () {
            return response()->noContent();
        })->name('session.ping');
        Route::post('/users/{user}/assign-order-set', [\App\Http\Controllers\Admin\UserController::class, 'assignOrderSet'])->name('users.assign-order-set');
        Route::put('/users/{user}/update-referrer', [\App\Http\Controllers\Admin\UserController::class, 'updateReferrer'])->name('users.update-referrer');
        Route::put('/users/{user}/orders/{order}', [\App\Http\Controllers\Admin\UserController::class, 'updateOrder'])->name('users.update-order');
        Route::delete('/users/{user}/order-set/{userOrderSet}', [\App\Http\Controllers\Admin\UserController::class, 'deleteUserOrderSet'])->name('users.delete-order-set');
        Route::put('/users/{user}/update-management', [\App\Http\Controllers\Admin\UserController::class, 'updateManagement'])->name('users.update-management');
        Route::put('/users/{user}/update-commissions', [\App\Http\Controllers\Admin\UserController::class, 'updateCommissions'])->name('users.update-commissions');
        Route::post('/users/{user}/login-as-user', [\App\Http\Controllers\Admin\UserController::class, 'loginAsUser'])->name('users.login-as-user');
        Route::get('/impersonate/{token}', [\App\Http\Controllers\Admin\UserController::class, 'impersonate'])->name('users.impersonate');
        Route::post('/users/{user}/ban', [\App\Http\Controllers\Admin\UserController::class, 'banUser'])->name('users.ban');
        Route::post('/users/{user}/unban', [\App\Http\Controllers\Admin\UserController::class, 'unbanUser'])->name('users.unban');
        Route::post('/users/{user}/make-admin', [\App\Http\Controllers\Admin\UserController::class, 'makeAdmin'])->name('users.make-admin');
        Route::post('/users/{user}/make-user', [\App\Http\Controllers\Admin\UserController::class, 'makeUser'])->name('users.make-user');
        Route::get('/users/{user}/tree', [\App\Http\Controllers\Admin\UserController::class, 'userTree'])->name('users.tree');

        // Gateways CRUD
        Route::middleware('super.admin')->group(function () {
            Route::get('/gateways', [\App\Http\Controllers\Admin\GatewayController::class, 'index'])->name('gateways.index');
            Route::get('/gateways/create', [\App\Http\Controllers\Admin\GatewayController::class, 'create'])->name('gateways.create');
            Route::post('/gateways', [\App\Http\Controllers\Admin\GatewayController::class, 'store'])->name('gateways.store');
            Route::get('/gateways/{gateway}/edit', [\App\Http\Controllers\Admin\GatewayController::class, 'edit'])->name('gateways.edit');
            Route::put('/gateways/{gateway}', [\App\Http\Controllers\Admin\GatewayController::class, 'update'])->name('gateways.update');
            Route::delete('/gateways/{gateway}', [\App\Http\Controllers\Admin\GatewayController::class, 'destroy'])->name('gateways.destroy');
            Route::patch('/gateways/{gateway}/toggle', [\App\Http\Controllers\Admin\GatewayController::class, 'toggle'])->name('gateways.toggle');

            // Legacy filtered routes (for compatibility)
            Route::get('/gateways/payment', [\App\Http\Controllers\Admin\GatewayController::class, 'payment'])->name('gateways.payment');
            Route::get('/gateways/withdrawal', [\App\Http\Controllers\Admin\GatewayController::class, 'withdrawal'])->name('gateways.withdrawal');
        });

        // Products
        Route::middleware('super.admin')->group(function () {
            Route::get('/products', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('products.index');
            Route::get('/products/create', [\App\Http\Controllers\Admin\ProductController::class, 'create'])->name('products.create');
            Route::post('/products', [\App\Http\Controllers\Admin\ProductController::class, 'store'])->name('products.store');
            Route::get('/products/{product}/edit', [\App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('products.edit');
            Route::put('/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'update'])->name('products.update');
            Route::delete('/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('products.destroy');
            // Allow both POST (form default) and PATCH (consistency with gateways) for toggle
            Route::match(['post', 'patch'], '/products/{product}/toggle', [\App\Http\Controllers\Admin\ProductController::class, 'toggle'])->name('products.toggle');
        });

        // Platforms CRUD
        Route::get('/platforms', [\App\Http\Controllers\Admin\PlatformController::class, 'index'])->name('platforms.index');
        Route::get('/platforms/create', [\App\Http\Controllers\Admin\PlatformController::class, 'create'])->name('platforms.create');
        Route::post('/platforms', [\App\Http\Controllers\Admin\PlatformController::class, 'store'])->name('platforms.store');
        Route::get('/platforms/{platform}/edit', [\App\Http\Controllers\Admin\PlatformController::class, 'edit'])->name('platforms.edit');
        Route::put('/platforms/{platform}', [\App\Http\Controllers\Admin\PlatformController::class, 'update'])->name('platforms.update');
        Route::delete('/platforms/{platform}', [\App\Http\Controllers\Admin\PlatformController::class, 'destroy'])->name('platforms.destroy');

        // Orders module (Order Sets + Product Packages + Completed Orders)
        Route::middleware('super.admin')->group(function () {
            // Order Sets (Categories)
            Route::get('/order-sets', [\App\Http\Controllers\Admin\OrderSetController::class, 'index'])->name('order-sets.index');
            Route::get('/order-sets/create', [\App\Http\Controllers\Admin\OrderSetController::class, 'create'])->name('order-sets.create');
            Route::post('/order-sets', [\App\Http\Controllers\Admin\OrderSetController::class, 'store'])->name('order-sets.store');
            Route::get('/order-sets/{order_set}/manage', [\App\Http\Controllers\Admin\OrderSetController::class, 'manage'])->name('order-sets.manage');
            Route::get('/order-sets/{order_set}/edit', [\App\Http\Controllers\Admin\OrderSetController::class, 'edit'])->name('order-sets.edit');
            Route::put('/order-sets/{order_set}', [\App\Http\Controllers\Admin\OrderSetController::class, 'update'])->name('order-sets.update');
            Route::delete('/order-sets/{order_set}', [\App\Http\Controllers\Admin\OrderSetController::class, 'destroy'])->name('order-sets.destroy');
            Route::match(['post', 'patch'], '/order-sets/{order_set}/toggle', [\App\Http\Controllers\Admin\OrderSetController::class, 'toggle'])->name('order-sets.toggle');

            // Product Packages (Under Order Sets)
            Route::get('/product-packages', [\App\Http\Controllers\Admin\ProductPackageController::class, 'index'])->name('product-packages.index');
            Route::get('/product-packages/create', [\App\Http\Controllers\Admin\ProductPackageController::class, 'create'])->name('product-packages.create');
            Route::post('/product-packages', [\App\Http\Controllers\Admin\ProductPackageController::class, 'store'])->name('product-packages.store');
            Route::get('/product-packages/{product_package}/edit', [\App\Http\Controllers\Admin\ProductPackageController::class, 'edit'])->name('product-packages.edit');
            Route::put('/product-packages/{product_package}', [\App\Http\Controllers\Admin\ProductPackageController::class, 'update'])->name('product-packages.update');
            Route::delete('/product-packages/{product_package}', [\App\Http\Controllers\Admin\ProductPackageController::class, 'destroy'])->name('product-packages.destroy');
            Route::match(['post', 'patch'], '/product-packages/{product_package}/toggle', [\App\Http\Controllers\Admin\ProductPackageController::class, 'toggle'])->name('product-packages.toggle');
            Route::get('/product-packages/products', [\App\Http\Controllers\Admin\ProductPackageController::class, 'getProducts'])->name('product-packages.products');

            // Orders (Completed User Orders)
            Route::get('/orders', [\App\Http\Controllers\Admin\AdminOrderController::class, 'index'])->name('orders.index');
            Route::get('/orders/{user_order}', [\App\Http\Controllers\Admin\AdminOrderController::class, 'show'])->name('orders.show');
        });

        // Platform Rule (separate module)
        Route::get('/platform-rule', [\App\Http\Controllers\Admin\PlatformRuleController::class, 'index'])->name('platform-rule.index');
        Route::get('/platform-rule/create', [\App\Http\Controllers\Admin\PlatformRuleController::class, 'create'])->name('platform-rule.create');
        Route::post('/platform-rule', [\App\Http\Controllers\Admin\PlatformRuleController::class, 'store'])->name('platform-rule.store');
        Route::get('/platform-rule/{platform_rule}/edit', [\App\Http\Controllers\Admin\PlatformRuleController::class, 'edit'])->name('platform-rule.edit');
        Route::put('/platform-rule/{platform_rule}', [\App\Http\Controllers\Admin\PlatformRuleController::class, 'update'])->name('platform-rule.update');
        Route::delete('/platform-rule/{platform_rule}', [\App\Http\Controllers\Admin\PlatformRuleController::class, 'destroy'])->name('platform-rule.destroy');
        Route::match(['post', 'patch'], '/platform-rule/{platform_rule}/toggle', [\App\Http\Controllers\Admin\PlatformRuleController::class, 'toggle'])->name('platform-rule.toggle');

        // Deposits management
        Route::get('/deposits', [\App\Http\Controllers\Admin\DepositController::class, 'index'])->name('deposits.index');
        Route::get('/deposits/{deposit}', [\App\Http\Controllers\Admin\DepositController::class, 'show'])->name('deposits.show');
        Route::patch('/deposits/{deposit}/status', [\App\Http\Controllers\Admin\DepositController::class, 'updateStatus'])->name('deposits.update-status');

        // Withdrawals management
        Route::get('/withdrawals', [\App\Http\Controllers\Admin\WithdrawalController::class, 'index'])->name('withdrawals.index');
        Route::get('/withdrawals/{withdrawal}', [\App\Http\Controllers\Admin\WithdrawalController::class, 'show'])->name('withdrawals.show');
        Route::patch('/withdrawals/{withdrawal}/status', [\App\Http\Controllers\Admin\WithdrawalController::class, 'updateStatus'])->name('withdrawals.update-status');

        // Order requests management
        Route::get('/order-requests', [\App\Http\Controllers\Admin\OrderRequestController::class, 'index'])->name('order-requests.index');
        Route::patch('/order-requests/{orderRequest}/status', [\App\Http\Controllers\Admin\OrderRequestController::class, 'updateStatus'])->name('order-requests.update-status');

        // Notifications (admin)
        Route::get('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/notifications/check', [\App\Http\Controllers\Admin\NotificationController::class, 'checkUnread'])->name('notifications.check');
        Route::get('/notifications/{notification}/go', [\App\Http\Controllers\Admin\NotificationController::class, 'go'])->name('notifications.go');
        Route::post('/notifications/read-all', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllRead'])->name('notifications.read-all');
        Route::post('/notifications/{notification}/read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('notifications.read');

        // Reports
        Route::get('/reports/login-history', [\App\Http\Controllers\Admin\ReportController::class, 'loginHistory'])->name('reports.login-history');
        Route::get('/reports/referral-commissions', [\App\Http\Controllers\Admin\ReferralCommissionReportController::class, 'index'])->name('reports.referral-commissions');

        // Transaction Logs
        Route::get('/transactions', [TransactionLogController::class, 'index'])->name('transactions.index');

        // Settings
        Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
        Route::get('/settings/seo', [\App\Http\Controllers\Admin\SettingController::class, 'seo'])->name('settings.seo');
        Route::post('/settings/seo', [\App\Http\Controllers\Admin\SettingController::class, 'updateSeo'])->name('settings.update-seo');
    });
});

require __DIR__ . '/auth.php';
