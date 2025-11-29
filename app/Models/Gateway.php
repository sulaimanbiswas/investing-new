<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gateway extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'currency',
        'country',
        'rate_usdt',
        'charge_type',
        'charge_value',
        'min_limit',
        'max_limit',
        'description',
        'address',
        'qr_path',
        'requires_txn_id',
        'requires_screenshot',
        'custom_fields',
        'is_active'
    ];

    protected $casts = [
        'rate_usdt' => 'decimal:8',
        'charge_value' => 'decimal:8',
        'min_limit' => 'decimal:8',
        'max_limit' => 'decimal:8',
        'requires_txn_id' => 'boolean',
        'requires_screenshot' => 'boolean',
        'is_active' => 'boolean',
        'custom_fields' => 'array',
    ];
}
