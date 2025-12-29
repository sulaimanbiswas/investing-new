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

    /**
     * Show the wallet management form.
     */
    public function wallet(Request $request): View
    {
        $user = $request->user();
        $gateways = \App\Models\Gateway::where('type', 'withdrawal')
            ->where('is_active', 1)
            ->get();

        return view('user.wallet', [
            'user' => $user,
            'gateways' => $gateways,
        ]);
    }

    /**
     * Update the user's wallet details.
     */
    public function updateWallet(Request $request)
    {
        $user = $request->user();

        // Validate input
        $data = $request->validate([
            'wallet_name' => 'required|string|max:255',
            'wallet_gateway' => 'required|string|max:255',
            'withdrawal_address' => 'required|string|max:255',
            'wallet_password' => 'required|string',
        ]);

        // Verify wallet password
        if ($data['wallet_password'] !== $user->withdrawal_password) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Wallet password is incorrect. Please try again.'
                ], 422);
            }

            return back()->withErrors(['wallet_password' => 'Wallet password is incorrect.']);
        }

        // Update wallet details
        $user->wallet_name = $data['wallet_name'];
        $user->wallet_gateway = $data['wallet_gateway'];
        $user->withdrawal_address = $data['withdrawal_address'];
        $user->save();

        // Return response based on request type
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Wallet details saved successfully!'
            ]);
        }

        flash()->success('Wallet details saved successfully!');
        return Redirect::route('wallet.edit');
    }

    /**
     * Update the user's withdrawal password.
     */
    public function updateWithdrawalPassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updateWithdrawalPassword', [
            'current_withdrawal_password' => 'required|string',
            'withdrawal_password' => 'required|string|min:6|confirmed',
        ]);

        $user = $request->user();

        // Verify current withdrawal password
        if ($validated['current_withdrawal_password'] !== $user->withdrawal_password) {
            return Redirect::route('profile.edit')->withErrors([
                'current_withdrawal_password' => 'The current withdrawal password is incorrect.',
            ], 'updateWithdrawalPassword');
        }

        // Update withdrawal password
        $user->withdrawal_password = $validated['withdrawal_password'];
        $user->save();

        flash()->success('Withdrawal password updated successfully.');

        return Redirect::route('profile.edit')->with('status', 'withdrawal-password-updated');
    }
}
