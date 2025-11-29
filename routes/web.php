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

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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
    });
});

require __DIR__ . '/auth.php';
