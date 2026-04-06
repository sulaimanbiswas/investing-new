<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SessionRevocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function __construct(private SessionRevocationService $sessionRevocationService) {}

    public function index()
    {
        return view('admin.profile.index');
    }

    public function update(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,' . $admin->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $admin->id],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'current_password' => ['nullable', 'string'],
            'password' => ['nullable', 'string', Password::defaults(), 'confirmed'],
        ];

        // Add password validation only if current_password is provided
        if ($request->filled('password') || $request->filled('password_confirmation')) {
            $rules['current_password'] = ['required', 'string'];
            $rules['password'] = ['required', 'string', Password::defaults(), 'confirmed'];
        }

        $validated = $request->validate($rules);

        // Verify current password if provided
        if ($request->filled('password')) {
            if ($request->current_password !== $admin->password && ! Hash::check($request->current_password, $admin->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($admin->avatar && file_exists(public_path('uploads/avatar/' . $admin->avatar))) {
                unlink(public_path('uploads/avatar/' . $admin->avatar));
            }

            // Store new avatar
            $avatarName = time() . '_' . uniqid() . '.' . $request->file('avatar')->getClientOriginalExtension();
            $request->file('avatar')->move(public_path('uploads/avatar'), $avatarName);
            $admin->avatar = $avatarName;
        }

        // Update basic info
        $admin->name = $validated['name'];
        $admin->username = $validated['username'];
        $admin->email = $validated['email'];

        // Update password if provided
        if ($request->filled('password')) {
            $admin->password = $validated['password'];
        }

        $admin->save();

        if ($request->filled('password')) {
            $this->sessionRevocationService->revokeForUser($admin);

            Auth::guard('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('admin.login')
                ->with('success', 'Profile updated successfully. Please log in again.');
        }

        flash()->success('Profile updated successfully!');

        return redirect()->route('admin.profile.index');
    }
}
