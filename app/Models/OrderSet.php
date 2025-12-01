<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderSet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'platform_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    public function productPackages()
    {
        return $this->hasMany(ProductPackage::class, 'order_set_id');
    }
}
