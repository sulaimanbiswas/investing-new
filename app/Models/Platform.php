<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'commission',
        'start_price',
        'end_price',
        'image',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
