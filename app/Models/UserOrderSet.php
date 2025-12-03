<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserOrderSet extends Model
{
    protected $fillable = [
        'user_id',
        'order_set_id',
        'total_products',
        'completed_products',
        'total_amount',
        'profit_amount',
        'status',
        'assigned_at',
        'completed_at',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'completed_at' => 'datetime',
        'total_amount' => 'decimal:2',
        'profit_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderSet(): BelongsTo
    {
        return $this->belongsTo(OrderSet::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(UserOrder::class);
    }

    public function completionPercentage(): int
    {
        if ($this->total_products == 0) {
            return 0;
        }
        return (int) round(($this->completed_products / $this->total_products) * 100);
    }
}
