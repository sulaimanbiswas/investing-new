<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Services\LoginTrackingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    protected LoginTrackingService $loginTracker;

    public function __construct(LoginTrackingService $loginTracker)
    {
        $this->loginTracker = $loginTracker;
    }

    /**
     * Show the admin login view.
     */
    public function create(): View|RedirectResponse
    {
        // If already logged in as admin, redirect to dashboard
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    /**
     * Handle an incoming admin authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        // If already logged in, redirect to dashboard
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        $data = $request->validate([
            'email' => ['required', 'string'], // email or username
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        $identifier = $data['email'];
        $field = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // First, ensure the user is an admin
        $user = \App\Models\User::where($field, $identifier)->first();

        if (! $user || ! $user->is_admin) {
            // Track failed login attempt
            if ($user) {
                $this->loginTracker->trackLogin($user, $request, 'failed', 'Not an admin user');
            }

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        $credentials = [$field => $identifier, 'password' => $data['password']];

        if (! Auth::guard('admin')->attempt($credentials, $remember)) {
            // Track failed login attempt
            $this->loginTracker->trackLogin($user, $request, 'failed', 'Invalid password');

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        // Track successful login
        $this->loginTracker->trackLogin(Auth::guard('admin')->user(), $request, 'success');

        flash()->success('Welcome Admin! You have been logged in successfully.');

        return redirect()->intended(route('admin.dashboard'));
    }

    /**
     * Logout admin session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        flash()->info('You have been logged out successfully.');

        return redirect()->route('admin.login');
    }
}
