<?php

namespace App\Observers;

use App\Models\Notification;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "creating" event.
     */
    public function creating(User $user): void
    {
        // Auto-generate referral code if not set
        if (empty($user->referral_code)) {
            $user->referral_code = User::generateReferralCode();
        }
    }

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        Notification::create([
            'user_id' => $user->id,
            'type' => 'user_registered',
            'title' => 'New User Registration',
            'message' => 'New user ' . ($user->name ?: $user->username ?: 'Unknown') . ' has created an account.',
            'data' => [
                'registered_user_id' => $user->id,
                'registered_user_name' => $user->name,
                'registered_username' => $user->username,
                'registered_email' => $user->email,
                'registered_phone' => $user->phone,
            ],
            'is_for_admin' => true,
        ]);
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
