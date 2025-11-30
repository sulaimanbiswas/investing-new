<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController as AdminAuthController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/invitation', function () {
    return view('invitation');
})->middleware(['auth', 'verified'])->name('invitation');

Route::get('/teams', function () {
    $user = auth()->user();

    // Get Level 1: Direct referrals
    $level1 = $user->referrals()->with('referrals')->get();
    $level1Count = $level1->count();

    // Get Level 2: Referrals of referrals
    $level2 = \App\Models\User::whereIn('referred_by', $level1->pluck('id'))->with('referrer')->get();
    $level2Count = $level2->count();

    // Get Level 3: Referrals of level 2
    $level3 = \App\Models\User::whereIn('referred_by', $level2->pluck('id'))->with('referrer')->get();
    $level3Count = $level3->count();

    $totalTeamSize = $level1Count + $level2Count + $level3Count;

    return view('teams', compact('level1', 'level2', 'level3', 'level1Count', 'level2Count', 'level3Count', 'totalTeamSize'));
})->middleware(['auth', 'verified'])->name('teams');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // User profile dashboard
    Route::view('/me', 'profile')->name('profile.home');
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

        // Users management pages (views first)
        Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::get('/users/active', [\App\Http\Controllers\Admin\UserController::class, 'active'])->name('users.active');
        Route::get('/users/banned', [\App\Http\Controllers\Admin\UserController::class, 'banned'])->name('users.banned');

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
        Route::get('/order-sets/{order_set}/edit', [\App\Http\Controllers\Admin\OrderSetController::class, 'edit'])->name('order-sets.edit');
        Route::put('/order-sets/{order_set}', [\App\Http\Controllers\Admin\OrderSetController::class, 'update'])->name('order-sets.update');
        Route::delete('/order-sets/{order_set}', [\App\Http\Controllers\Admin\OrderSetController::class, 'destroy'])->name('order-sets.destroy');
        Route::match(['post', 'patch'], '/order-sets/{order_set}/toggle', [\App\Http\Controllers\Admin\OrderSetController::class, 'toggle'])->name('order-sets.toggle');

        // Orders (Under Order Sets)
        Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/create', [\App\Http\Controllers\Admin\OrderController::class, 'create'])->name('orders.create');
        Route::post('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'store'])->name('orders.store');
        Route::get('/orders/{order}/edit', [\App\Http\Controllers\Admin\OrderController::class, 'edit'])->name('orders.edit');
        Route::put('/orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'update'])->name('orders.update');
        Route::delete('/orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'destroy'])->name('orders.destroy');
        Route::match(['post', 'patch'], '/orders/{order}/toggle', [\App\Http\Controllers\Admin\OrderController::class, 'toggle'])->name('orders.toggle');
        Route::get('/orders/products', [\App\Http\Controllers\Admin\OrderController::class, 'getProducts'])->name('orders.products');

        // Platform Rule (separate module)
        Route::get('/platform-rule', [\App\Http\Controllers\Admin\PlatformRuleController::class, 'index'])->name('platform-rule.index');
        Route::get('/platform-rule/create', [\App\Http\Controllers\Admin\PlatformRuleController::class, 'create'])->name('platform-rule.create');
        Route::post('/platform-rule', [\App\Http\Controllers\Admin\PlatformRuleController::class, 'store'])->name('platform-rule.store');
        Route::get('/platform-rule/{platform_rule}/edit', [\App\Http\Controllers\Admin\PlatformRuleController::class, 'edit'])->name('platform-rule.edit');
        Route::put('/platform-rule/{platform_rule}', [\App\Http\Controllers\Admin\PlatformRuleController::class, 'update'])->name('platform-rule.update');
        Route::delete('/platform-rule/{platform_rule}', [\App\Http\Controllers\Admin\PlatformRuleController::class, 'destroy'])->name('platform-rule.destroy');
        Route::match(['post', 'patch'], '/platform-rule/{platform_rule}/toggle', [\App\Http\Controllers\Admin\PlatformRuleController::class, 'toggle'])->name('platform-rule.toggle');
    });
});

require __DIR__ . '/auth.php';
