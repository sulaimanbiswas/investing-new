<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SessionRevocationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function __construct(private SessionRevocationService $sessionRevocationService) {}

    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = $request->user();

        if ($validated['current_password'] !== $user->password && ! Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => __('auth.password'),
            ], 'updatePassword');
        }

        $user->update([
            'password' => $validated['password'],
        ]);

        $this->sessionRevocationService->revokeForUser($user);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Password updated successfully. Please log in again.');
    }
}
