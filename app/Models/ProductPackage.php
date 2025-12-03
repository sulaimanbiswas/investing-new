<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_set_id',
        'type',
        'package_id',
        'platform_id',
        'profit_percentage',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'profit_percentage' => 'decimal:2',
    ];

    public function orderSet()
    {
        return $this->belongsTo(OrderSet::class);
    }

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_package_items')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    public function productPackageItems()
    {
        return $this->hasMany(ProductPackageItem::class);
    }

    // Alias for items relationship
    public function items()
    {
        return $this->hasMany(ProductPackageItem::class);
    }

    // Calculate subtotal (sum of all products price * quantity)
    public function getSubtotalAttribute()
    {
        return $this->productPackageItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }

    // Calculate profit amount
    public function getProfitAmountAttribute()
    {
        return ($this->subtotal * $this->profit_percentage) / 100;
    }

    // Calculate total with profit
    public function getTotalWithProfitAttribute()
    {
        return $this->subtotal + $this->profit_amount;
    }
}
