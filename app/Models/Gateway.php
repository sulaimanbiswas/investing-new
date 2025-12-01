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
        'logo_path',
        'requires_txn_id',
        'requires_screenshot',
        'custom_fields',
        'is_active'
    ];

    protected static function booted()
    {
        static::deleting(function (Gateway $gateway) {
            if ($gateway->qr_path) {
                $path = public_path(ltrim($gateway->qr_path, '/'));
                if (str_starts_with($gateway->qr_path, '/uploads/qrs/') && file_exists($path)) {
                    @unlink($path);
                }
            }
            if ($gateway->logo_path) {
                $path = public_path(ltrim($gateway->logo_path, '/'));
                if (str_starts_with($gateway->logo_path, '/uploads/gateways/') && file_exists($path)) {
                    @unlink($path);
                }
            }
        });
    }

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
