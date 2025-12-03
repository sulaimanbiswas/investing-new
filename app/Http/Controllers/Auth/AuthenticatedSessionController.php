<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\LoginTrackingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    protected LoginTrackingService $loginTracker;

    public function __construct(LoginTrackingService $loginTracker)
    {
        $this->loginTracker = $loginTracker;
    }

    /**
     * Display the login view.
     */
    public function create(): View|RedirectResponse
    {
        // If already logged in as user, redirect to dashboard
        if (Auth::guard('web')->check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Track successful login
        $this->loginTracker->trackLogin(Auth::user(), $request, 'success');

        flash()->success('Welcome back! You have been logged in successfully.');

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        flash()->info('You have been logged out successfully.');

        return redirect('/');
    }
}
