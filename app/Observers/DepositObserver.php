<?php

namespace App\Observers;

use App\Models\Deposit;
use App\Models\Notification;
use App\Models\Transaction;

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
                // Notify admin about new deposit request
                Notification::create([
                    'user_id' => $deposit->user_id,
                    'type' => 'deposit_pending',
                    'title' => 'New Deposit Request',
                    'message' => 'User ' . ($deposit->user->name ?? 'Unknown') . ' submitted a deposit request of ' . number_format((float) $deposit->amount, 2) . ' ' . $deposit->currency . '.',
                    'data' => json_encode(['deposit_id' => $deposit->id]),
                    'is_for_admin' => true
                ]);
            } elseif ($newStatus === 'approved') {
                // Add balance to user account
                $user = $deposit->user;
                $balanceBefore = (float) $user->balance;
                $balanceAfter = $balanceBefore + (float) $deposit->amount;

                $user->increment('balance', (float) $deposit->amount);

                // Create transaction record
                Transaction::create([
                    'user_id' => $deposit->user_id,
                    'type' => 'deposit',
                    'reference_id' => $deposit->id,
                    'amount' => (float) $deposit->amount,
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balanceAfter,
                    'remarks' => 'Deposit of ' . number_format((float) $deposit->amount, 2) . ' ' . $deposit->currency . ' approved',
                ]);

                // Notify user about approval
                Notification::create([
                    'user_id' => $deposit->user_id,
                    'type' => 'deposit_completed',
                    'title' => 'Deposit Approved',
                    'message' => 'Your deposit of ' . number_format((float) $deposit->amount, 2) . ' ' . $deposit->currency . ' has been approved and credited to your account.',
                    'data' => json_encode(['deposit_id' => $deposit->id]),
                    'is_for_admin' => false
                ]);
            } elseif ($newStatus === 'rejected') {
                // Notify user about rejection
                $reason = $deposit->admin_note ? ' Reason: ' . $deposit->admin_note : '';
                Notification::create([
                    'user_id' => $deposit->user_id,
                    'type' => 'deposit_rejected',
                    'title' => 'Deposit Rejected',
                    'message' => 'Your deposit request of ' . number_format((float) $deposit->amount, 2) . ' ' . $deposit->currency . ' has been rejected.' . $reason,
                    'data' => json_encode(['deposit_id' => $deposit->id]),
                    'is_for_admin' => false
                ]);
            }
        }
    }
}
