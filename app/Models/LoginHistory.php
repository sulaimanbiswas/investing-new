<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginHistory extends Model
{
    protected $fillable = [
        'user_id',
        'ip_address',
        'country',
        'country_code',
        'region',
        'region_code',
        'city',
        'zip_code',
        'latitude',
        'longitude',
        'timezone',
        'isp',
        'user_agent',
        'browser',
        'browser_version',
        'platform',
        'platform_version',
        'device',
        'device_model',
        'status',
        'failure_reason',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the login history.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the full location string.
     */
    public function getFullLocationAttribute(): string
    {
        $parts = array_filter([
            $this->city,
            $this->region,
            $this->country,
        ]);

        return implode(', ', $parts) ?: 'Unknown';
    }

    /**
     * Get the device info string.
     */
    public function getDeviceInfoAttribute(): string
    {
        $parts = array_filter([
            $this->device,
            $this->platform,
            $this->platform_version,
        ]);

        return implode(' - ', $parts) ?: 'Unknown';
    }

    /**
     * Get the browser info string.
     */
    public function getBrowserInfoAttribute(): string
    {
        if ($this->browser) {
            return $this->browser . ($this->browser_version ? ' ' . $this->browser_version : '');
        }

        return 'Unknown';
    }
}
