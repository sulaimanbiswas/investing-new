<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'identifier' => ['required', 'string'],
        ]);

        $identifier = $request->identifier;

        // Find user by phone, email, or username
        $user = null;
        if (preg_match('/^[0-9+\-\s()]+$/', $identifier)) {
            // Phone number
            $phone = preg_replace('/[^0-9+]/', '', $identifier);
            $user = \App\Models\User::where('phone', $phone)->first();
        } elseif (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            // Email
            $user = \App\Models\User::where('email', $identifier)->first();
        } else {
            // Username
            $user = \App\Models\User::where('username', $identifier)->first();
        }

        if (!$user) {
            return back()->withInput($request->only('identifier'))
                ->withErrors(['identifier' => 'We could not find a user with that phone/username/email.']);
        }

        // For now, we'll use email-based password reset if user has email
        // Otherwise, show an identifier-specific error
        if (!$user->email) {
            return back()->withInput($request->only('identifier'))
                ->withErrors(['identifier' => 'This user does not have an email address on file.']);
        }

        $status = Password::sendResetLink(['email' => $user->email]);

        if ($status == Password::RESET_LINK_SENT) {
            flash()->success('Password reset link has been sent to your email address.');
            return back();
        }

        return back()->withInput($request->only('identifier'))
            ->withErrors(['identifier' => __($status)]);
    }
}
