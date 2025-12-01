<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController as AdminAuthController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $platformRules = \App\Models\PlatformRule::where('is_active', true)
        ->orderBy('sort_by')
        ->take(4)
        ->get();
    return view('user.dashboard.index', compact('platformRules'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/invitation', function () {
    return view('user.invitation.index');
})->middleware(['auth', 'verified'])->name('invitation');

Route::get('/teams', [\App\Http\Controllers\TeamsController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('teams');

Route::get('/platform-rules', [\App\Http\Controllers\PlatformRulesController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('platform-rules');

Route::get('/platform-rules/{id}', [\App\Http\Controllers\PlatformRulesController::class, 'show'])
    ->middleware(['auth', 'verified'])
    ->name('platform-rules.show');

Route::get('/menu', [\App\Http\Controllers\MenuController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('menu.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // User profile dashboard
    Route::view('/me', 'profile')->name('profile.home');

    // Deposit routes
    Route::get('/deposit', [\App\Http\Controllers\DepositController::class, 'index'])->name('deposit');
    Route::get('/deposit/confirm', [\App\Http\Controllers\DepositController::class, 'confirm'])->name('deposit.confirm');
    Route::post('/deposit', [\App\Http\Controllers\DepositController::class, 'store'])->name('deposit.store');
    Route::get('/deposit/records', [\App\Http\Controllers\DepositController::class, 'records'])->name('deposit.records');
});

// Admin auth routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware(['guest', 'guest:admin'])->group(function () {
        Route::get('/login', [AdminAuthController::class, 'create'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'store'])->name('login.store');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::view('/dashboard', 'admin.dashboard')->name('dashboard');
        Route::post('/logout', [AdminAuthController::class, 'destroy'])->name('logout');

        // CKEditor upload endpoint
        Route::post('/uploads/ckeditor', [\App\Http\Controllers\Admin\UploadController::class, 'ckeditor'])
            ->name('ckeditor.upload');

        // QR image upload endpoint (Dropzone)
        Route::post('/uploads/qrs', [\App\Http\Controllers\Admin\UploadController::class, 'qr'])
            ->name('uploads.qr');
        Route::post('/uploads/qrs/delete', [\App\Http\Controllers\Admin\UploadController::class, 'deleteQr'])->name('uploads.qr.delete');

        // Gateway logo upload endpoint
        Route::post('/uploads/gateways', [\App\Http\Controllers\Admin\UploadController::class, 'gateway'])
            ->name('uploads.gateway');
        Route::post('/uploads/gateways/delete', [\App\Http\Controllers\Admin\UploadController::class, 'deleteGateway'])->name('uploads.gateway.delete');

        // Users management pages (views first)
        Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');

        // Gateways CRUD
        Route::get('/gateways', [\App\Http\Controllers\Admin\GatewayController::class, 'index'])->name('gateways.index');
        Route::get('/gateways/create', [\App\Http\Controllers\Admin\GatewayController::class, 'create'])->name('gateways.create');
        Route::post('/gateways', [\App\Http\Controllers\Admin\GatewayController::class, 'store'])->name('gateways.store');
        Route::get('/gateways/{gateway}/edit', [\App\Http\Controllers\Admin\GatewayController::class, 'edit'])->name('gateways.edit');
        Route::put('/gateways/{gateway}', [\App\Http\Controllers\Admin\GatewayController::class, 'update'])->name('gateways.update');
        Route::delete('/gateways/{gateway}', [\App\Http\Controllers\Admin\GatewayController::class, 'destroy'])->name('gateways.destroy');
        Route::patch('/gateways/{gateway}/toggle', [\App\Http\Controllers\Admin\GatewayController::class, 'toggle'])->name('gateways.toggle');

        // Products
        Route::get('/products', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [\App\Http\Controllers\Admin\ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [\App\Http\Controllers\Admin\ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [\App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('products.destroy');
        // Allow both POST (form default) and PATCH (consistency with gateways) for toggle
        Route::match(['post', 'patch'], '/products/{product}/toggle', [\App\Http\Controllers\Admin\ProductController::class, 'toggle'])->name('products.toggle');

        // Legacy filtered routes (for compatibility)
        Route::get('/gateways/payment', [\App\Http\Controllers\Admin\GatewayController::class, 'payment'])->name('gateways.payment');
        Route::get('/gateways/withdrawal', [\App\Http\Controllers\Admin\GatewayController::class, 'withdrawal'])->name('gateways.withdrawal');

        // Platforms CRUD
        Route::get('/platforms', [\App\Http\Controllers\Admin\PlatformController::class, 'index'])->name('platforms.index');
        Route::get('/platforms/create', [\App\Http\Controllers\Admin\PlatformController::class, 'create'])->name('platforms.create');
        Route::post('/platforms', [\App\Http\Controllers\Admin\PlatformController::class, 'store'])->name('platforms.store');
        Route::get('/platforms/{platform}/edit', [\App\Http\Controllers\Admin\PlatformController::class, 'edit'])->name('platforms.edit');
        Route::put('/platforms/{platform}', [\App\Http\Controllers\Admin\PlatformController::class, 'update'])->name('platforms.update');
        Route::delete('/platforms/{platform}', [\App\Http\Controllers\Admin\PlatformController::class, 'destroy'])->name('platforms.destroy');

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
    });
});

require __DIR__ . '/auth.php';
