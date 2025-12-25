<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(rules: [
            'token' => ['required'],
            'identifier' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Find user by identifier
        $identifier = $request->identifier;
        $user = null;

        if (preg_match('/^[0-9+\-\s()]+$/', $identifier)) {
            $phone = preg_replace('/[^0-9+]/', '', $identifier);
            $user = User::where('phone', $phone)->first();
        } elseif (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $identifier)->first();
        } else {
            $user = User::where('username', $identifier)->first();
        }

        if (!$user || !$user->email) {
            return back()->withInput($request->only('identifier'))
                ->withErrors(['identifier' => 'Invalid user or user has no email for password reset.']);
        }

        // Verify the reset token using email
        $status = Password::reset(
            ['email' => $user->email, 'password' => $request->password, 'password_confirmation' => $request->password_confirmation, 'token' => $request->token],
            function (User $user) use ($request) {
                $user->forceFill([
                    'password' => $request->password,
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            flash()->success('Password reset successfully. You can now login with your new password.');
            return redirect()->route('login');
        }

        return back()->withInput($request->only('identifier'))
            ->withErrors(['identifier' => __($status)]);
    }
}
