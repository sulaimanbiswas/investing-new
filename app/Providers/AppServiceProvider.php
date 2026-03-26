<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\UserOrderSet;
use App\Observers\UserObserver;
use App\Observers\DepositObserver;
use App\Observers\WithdrawalObserver;
use App\Models\Notification;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register plaintext user provider for authentication
        Auth::provider('plaintext', function ($app, array $config) {
            return new \App\Auth\PlaintextUserProvider(
                $app['hash'],
                $config['model']
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::share('supportedLocales', config('localization.supported_locales', ['en' => 'English']));
        View::share('currentLocale', app()->getLocale());

        // Configure route model bindings
        Route::bind('userOrderSet', function ($value) {
            return UserOrderSet::findOrFail($value);
        });

        // Dynamically set pagination view: Tailwind for user, Bootstrap for admin
        if (request()->is('admin*')) {
            Paginator::defaultView('vendor.pagination.bootstrap-5');
        } else {
            Paginator::defaultView('vendor.pagination.tailwind');
        }

        // Configure pagination to show links around current page
        // This will show ellipsis (...) when there are many pages
        Paginator::currentPathResolver(function () {
            return request()->url();
        });

        Paginator::currentPageResolver(function ($pageName = 'page') {
            return request()->query($pageName);
        });

        User::observe(UserObserver::class);
        Deposit::observe(DepositObserver::class);
        Withdrawal::observe(WithdrawalObserver::class);

        // Share notification counts in user layout
        View::composer('layouts.user.*', function ($view) {
            if (Auth::check()) {
                $userId = Auth::id();
                $unreadCount = Notification::where('user_id', $userId)
                    ->where('is_for_admin', false)
                    ->where('is_read', false)
                    ->count();
                $latestNotifications = Notification::where('user_id', $userId)
                    ->where('is_for_admin', false)
                    ->latest()
                    ->take(5)
                    ->get();
                $view->with('userNotificationUnread', $unreadCount)
                    ->with('userNotificationLatest', $latestNotifications);
            }
        });

        // Share notification counts in admin layout (latest 10 for admin only)
        View::composer('admin.layouts.app', function ($view) {
            $latestNotifications = Notification::with('user')
                ->where('is_for_admin', true)
                ->latest()
                ->take(10)
                ->get();
            $unreadCount = Notification::where('is_for_admin', true)
                ->where('is_read', false)
                ->count();
            $view->with('adminNotificationUnread', $unreadCount)
                ->with('adminNotificationLatest', $latestNotifications);
        });
    }
}
