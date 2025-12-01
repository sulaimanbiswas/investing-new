<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPackageItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_package_id',
        'product_id',
        'quantity',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function productPackage()
    {
        return $this->belongsTo(ProductPackage::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
