<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'type', // order_payment, order_profit, deposit, withdrawal
        'reference_id', // order_id, deposit_id, withdrawal_id
        'amount',
        'balance_before',
        'balance_after',
        'remarks',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
