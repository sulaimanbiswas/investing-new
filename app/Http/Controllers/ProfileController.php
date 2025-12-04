<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Update basic fields
        $user->fill($request->validated());

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Store in public/uploads/avatar
            $destinationPath = public_path('uploads/avatar');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            // Delete old avatar if exists
            if ($user->avatar_path) {
                $oldFile = public_path('uploads/avatar/' . $user->avatar_path);
                if (file_exists($oldFile)) {
                    @unlink($oldFile);
                }
            }

            $file->move($destinationPath, $filename);
            $user->avatar_path = $filename;
        }

        $user->save();

        flash()->success('Profile updated successfully.');

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Account deletion disabled for users.
        flash()->warning('Account deletion is disabled. Contact support for assistance.');
        return Redirect::route('profile.edit');
    }

    /**
     * Display the user's profile home page.
     */
    public function home(Request $request): View
    {
        $user = $request->user();
        $balance = $user->balance ?? 0;

        // Get all platforms ordered by price range
        $platforms = \App\Models\Platform::orderBy('start_price')->get();

        // Determine user's VIP level based on balance
        $vipLevel = 0;
        $vipName = 'VIP 0';
        $currentPlatform = null;
        $nextPlatform = null;

        foreach ($platforms as $index => $platform) {
            if ($balance >= $platform->start_price && $balance <= $platform->end_price) {
                $vipLevel = $index + 1;
                $vipName = 'VIP ' . $vipLevel;
                $currentPlatform = $platform;

                // Get next platform if exists
                if (isset($platforms[$index + 1])) {
                    $nextPlatform = $platforms[$index + 1];
                }
                break;
            }
        }

        // If balance is higher than all platforms, set to highest VIP
        if (!$currentPlatform && $platforms->count() > 0) {
            $lastPlatform = $platforms->last();
            if ($balance > $lastPlatform->end_price) {
                $vipLevel = $platforms->count();
                $vipName = 'VIP ' . $vipLevel;
                $currentPlatform = $lastPlatform;
            }
        }

        return view('profile', [
            'user' => $user,
            'vipLevel' => $vipLevel,
            'vipName' => $vipName,
            'currentPlatform' => $currentPlatform,
            'nextPlatform' => $nextPlatform,
        ]);
    }
}
