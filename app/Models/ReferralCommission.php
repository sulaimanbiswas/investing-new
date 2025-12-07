<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReferralCommission extends Model
{
    protected $fillable = [
        'user_id',
        'referred_user_id',
        'deposit_id',
        'level',
        'deposit_amount',
        'commission_percentage',
        'commission_amount',
        'balance_before',
        'balance_after',
    ];

    protected $casts = [
        'deposit_amount' => 'decimal:2',
        'commission_percentage' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    /**
     * Get the user who earned the commission
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the user who made the deposit
     */
    public function referredUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_user_id');
    }

    /**
     * Get the deposit that generated this commission
     */
    public function deposit(): BelongsTo
    {
        return $this->belongsTo(Deposit::class);
    }
}
