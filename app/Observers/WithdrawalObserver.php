<?php

namespace App\Observers;

use App\Models\Withdrawal;
use App\Models\Notification;

class WithdrawalObserver
{
    /**
     * Handle the Withdrawal "created" event.
     */
    public function created(Withdrawal $withdrawal): void
    {
        // Notify admin about new withdrawal request
        Notification::create([
            'user_id' => $withdrawal->user_id,
            'type' => 'withdrawal_submitted',
            'title' => 'New Withdrawal Request',
            'message' => 'User ' . ($withdrawal->user->name ?? 'Unknown') . ' submitted a withdrawal request of ' . number_format((float) $withdrawal->amount, 2) . ' ' . $withdrawal->currency . '.',
            'data' => json_encode(['withdrawal_id' => $withdrawal->id]),
            'is_for_admin' => true
        ]);
    }

    /**
     * Handle the Withdrawal "updated" event.
     */
    public function updated(Withdrawal $withdrawal): void
    {
        if ($withdrawal->isDirty('status')) {
            $newStatus = $withdrawal->status;

            if ($newStatus === 'approved') {
                // Notify user about approval
                Notification::create([
                    'user_id' => $withdrawal->user_id,
                    'type' => 'withdrawal_approved',
                    'title' => 'Withdrawal Approved',
                    'message' => 'Your withdrawal of ' . number_format((float) $withdrawal->amount, 2) . ' ' . $withdrawal->currency . ' has been approved.',
                    'data' => json_encode(['withdrawal_id' => $withdrawal->id]),
                    'is_for_admin' => false
                ]);
            } elseif ($newStatus === 'rejected') {
                // Notify user about rejection
                $reason = $withdrawal->admin_note ? ' Reason: ' . $withdrawal->admin_note : '';
                Notification::create([
                    'user_id' => $withdrawal->user_id,
                    'type' => 'withdrawal_rejected',
                    'title' => 'Withdrawal Rejected',
                    'message' => 'Your withdrawal request of ' . number_format((float) $withdrawal->amount, 2) . ' ' . $withdrawal->currency . ' has been rejected.' . $reason,
                    'data' => json_encode(['withdrawal_id' => $withdrawal->id]),
                    'is_for_admin' => false
                ]);
            }
        }
    }
}
