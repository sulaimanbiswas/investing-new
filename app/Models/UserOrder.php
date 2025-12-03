<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class UserOrder extends Model
{
    protected $fillable = [
        'user_order_set_id',
        'product_package_item_id',
        'order_number',
        'type',
        'product_name',
        'quantity',
        'price',
        'order_amount',
        'profit_amount',
        'balance_after',
        'status',
        'manage_crypto',
        'paid_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'order_amount' => 'decimal:2',
        'profit_amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'manage_crypto' => 'array',
        'paid_at' => 'datetime',
    ];

    public function userOrderSet(): BelongsTo
    {
        return $this->belongsTo(UserOrderSet::class);
    }

    public function productPackageItem(): BelongsTo
    {
        return $this->belongsTo(ProductPackageItem::class);
    }

    public static function generateOrderNumber(): string
    {
        return strtoupper(Str::random(2)) . rand(100000, 999999);
    }
}
