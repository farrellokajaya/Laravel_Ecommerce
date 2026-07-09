<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_title',
        'product_description',
        'product_quantity',
        'product_prices',
        'product_image',
        'product_category',
    ];

    protected function casts(): array
    {
        return [
            'product_quantity' => 'integer',
            'product_prices' => 'integer',
        ];
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(ProductCart::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}