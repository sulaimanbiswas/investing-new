<?php

namespace App\Observers;

use App\Models\Deposit;
use App\Models\Notification;
use App\Models\Transaction;
use App\Services\ReferralCommissionService;

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
                // Add balance to user account (use approved_amount if set, otherwise use amount)
                $user = $deposit->user;
                $approvedAmount = (float) ($deposit->approved_amount ?? $deposit->amount);
                $balanceBefore = (float) $user->balance;
                $balanceAfter = $balanceBefore + $approvedAmount;

                $user->increment('balance', $approvedAmount);

                // Create transaction record
                Transaction::create([
                    'user_id' => $deposit->user_id,
                    'type' => 'deposit',
                    'reference_id' => $deposit->id,
                    'amount' => $approvedAmount,
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balanceAfter,
                    'remarks' => 'Deposit of ' . number_format($approvedAmount, 2) . ' ' . $deposit->currency . ' approved',
                ]);

                // Distribute referral commissions
                try {
                    $commissionService = app(ReferralCommissionService::class);
                    $commissionService->distributeCommissions($deposit);
                } catch (\Exception $e) {
                    \Log::error('Failed to distribute referral commissions: ' . $e->getMessage());
                }

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
