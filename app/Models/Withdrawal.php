<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'gateway_id',
        'order_number',
        'amount',
        'wallet_address',
        'currency',
        'custom_data',
        'status',
        'admin_note',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'custom_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gateway()
    {
        return $this->belongsTo(Gateway::class);
    }
}
