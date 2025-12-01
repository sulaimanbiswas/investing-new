<?php

namespace App\Observers;

use App\Models\Deposit;
use App\Models\Notification;

class DepositObserver
{
    /**
     * Handle the Deposit "updated" event.
     */
    public function updated(Deposit $deposit): void
    {
        // Check if status was changed
        if ($deposit->isDirty('status')) {
            $oldStatus = $deposit->getOriginal('status');
            $newStatus = $deposit->status;

            // Send notification based on status change
            if ($newStatus === 'pending' && $oldStatus === 'initialed') {
                Notification::create([
                    'user_id' => $deposit->user_id,
                    'type' => 'deposit_pending',
                    'title' => 'Deposit Request Submitted',
                    'message' => 'Your deposit request of ' . number_format((float) $deposit->amount, 2) . ' ' . $deposit->currency . ' has been submitted successfully. Please wait for admin approval.',
                    'data' => json_encode(['deposit_id' => $deposit->id])
                ]);
            } elseif ($newStatus === 'completed') {
                Notification::create([
                    'user_id' => $deposit->user_id,
                    'type' => 'deposit_completed',
                    'title' => 'Deposit Approved',
                    'message' => 'Your deposit of ' . number_format((float) $deposit->amount, 2) . ' ' . $deposit->currency . ' has been approved and credited to your account.',
                    'data' => json_encode(['deposit_id' => $deposit->id])
                ]);
            } elseif ($newStatus === 'rejected') {
                $reason = $deposit->admin_note ? ' Reason: ' . $deposit->admin_note : '';
                Notification::create([
                    'user_id' => $deposit->user_id,
                    'type' => 'deposit_rejected',
                    'title' => 'Deposit Rejected',
                    'message' => 'Your deposit request of ' . number_format((float) $deposit->amount, 2) . ' ' . $deposit->currency . ' has been rejected.' . $reason,
                    'data' => json_encode(['deposit_id' => $deposit->id])
                ]);
            }
        }
    }
}
