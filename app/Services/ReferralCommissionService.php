<?php

namespace App\Services;

use App\Models\{User, Deposit, ReferralCommission, Transaction, Notification};
use App\Helpers\SettingHelper;
use Illuminate\Support\Facades\DB;

class ReferralCommissionService
{
    /**
     * Calculate and distribute referral commissions for a deposit
     */
    public function distributeCommissions(Deposit $deposit): void
    {
        $user = $deposit->user;

        // Check if user was referred by someone
        if (!$user->referred_by) {
            return;
        }

        DB::transaction(function () use ($deposit, $user) {
            $this->processLevel1Commission($deposit, $user);
            $this->processLevel2Commission($deposit, $user);
            $this->processLevel3Commission($deposit, $user);
        });
    }

    /**
     * Process Level 1 commission (direct referral)
     */
    private function processLevel1Commission(Deposit $deposit, User $referredUser): void
    {
        $level1User = User::find($referredUser->referred_by);

        if (!$level1User) {
            return;
        }

        $commissionPercentage = $level1User->level1_commission > 0
            ? $level1User->level1_commission
            : $this->getDefaultCommission(1);

        if ($commissionPercentage > 0) {
            $this->createCommission($level1User, $referredUser, $deposit, 1, $commissionPercentage);
        }
    }

    /**
     * Process Level 2 commission (referral's referral)
     */
    private function processLevel2Commission(Deposit $deposit, User $referredUser): void
    {
        $level1User = User::find($referredUser->referred_by);

        if (!$level1User || !$level1User->referred_by) {
            return;
        }

        $level2User = User::find($level1User->referred_by);

        if (!$level2User) {
            return;
        }

        $commissionPercentage = $level2User->level2_commission > 0
            ? $level2User->level2_commission
            : $this->getDefaultCommission(2);

        if ($commissionPercentage > 0) {
            $this->createCommission($level2User, $referredUser, $deposit, 2, $commissionPercentage);
        }
    }

    /**
     * Process Level 3 commission (referral's referral's referral)
     */
    private function processLevel3Commission(Deposit $deposit, User $referredUser): void
    {
        $level1User = User::find($referredUser->referred_by);

        if (!$level1User || !$level1User->referred_by) {
            return;
        }

        $level2User = User::find($level1User->referred_by);

        if (!$level2User || !$level2User->referred_by) {
            return;
        }

        $level3User = User::find($level2User->referred_by);

        if (!$level3User) {
            return;
        }

        $commissionPercentage = $level3User->level3_commission > 0
            ? $level3User->level3_commission
            : $this->getDefaultCommission(3);

        if ($commissionPercentage > 0) {
            $this->createCommission($level3User, $referredUser, $deposit, 3, $commissionPercentage);
        }
    }

    /**
     * Create a commission record and update user balance
     */
    private function createCommission(
        User $earner,
        User $referredUser,
        Deposit $deposit,
        int $level,
        float $commissionPercentage
    ): void {
        $commissionAmount = ($deposit->approved_amount * $commissionPercentage) / 100;
        $balanceBefore = $earner->balance;
        $balanceAfter = $balanceBefore + $commissionAmount;

        // Create commission record
        ReferralCommission::create([
            'user_id' => $earner->id,
            'referred_user_id' => $referredUser->id,
            'deposit_id' => $deposit->id,
            'level' => $level,
            'deposit_amount' => $deposit->approved_amount,
            'commission_percentage' => $commissionPercentage,
            'commission_amount' => $commissionAmount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
        ]);

        // Update user balance
        $earner->update(['balance' => $balanceAfter]);

        // Create transaction record
        Transaction::create([
            'user_id' => $earner->id,
            'type' => 'referral_commission',
            'reference_id' => $deposit->id,
            'amount' => $commissionAmount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'remarks' => "Level {$level} referral commission from {$referredUser->name}'s deposit of \${$deposit->approved_amount}",
        ]);

        // Create notification for referral commission
        Notification::create([
            'user_id' => $earner->id,
            'type' => 'referral_commission',
            'title' => "Level {$level} Referral Commission Earned",
            'message' => "{$referredUser->name} deposited \${$deposit->approved_amount} and you earned \${$commissionAmount} commission.",
            'data' => [
                'deposit_id' => $deposit->id,
                'commission_amount' => $commissionAmount,
                'level' => $level,
                'referred_user_id' => $referredUser->id,
            ],
            'is_read' => false,
            'is_for_admin' => false,
        ]);
    }

    /**
     * Get default commission percentage for a level
     */
    protected function getDefaultCommission(int $level): float
    {
        return (float) setting("default_level{$level}_commission", 0);
    }

    /**
     * Get user's total referral commission earnings
     */
    public function getUserTotalCommissions(User $user): array
    {
        $commissions = ReferralCommission::where('user_id', $user->id)
            ->selectRaw('
                level,
                COUNT(*) as total_count,
                SUM(commission_amount) as total_amount
            ')
            ->groupBy('level')
            ->get();

        $level1 = $commissions->where('level', 1)->first();
        $level2 = $commissions->where('level', 2)->first();
        $level3 = $commissions->where('level', 3)->first();

        return [
            'level1' => (float) ($level1->total_amount ?? 0),
            'level2' => (float) ($level2->total_amount ?? 0),
            'level3' => (float) ($level3->total_amount ?? 0),
            'total' => (float) ($commissions->sum('total_amount') ?? 0),
        ];
    }

    /**
     * Get detailed commission history for a user
     */
    public function getUserCommissionHistory(User $user, int $perPage = 15)
    {
        return ReferralCommission::with(['referredUser', 'deposit'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
