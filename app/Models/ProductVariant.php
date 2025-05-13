<?php
// app/Models/ProductVariant.php
namespace App\Models;

use Lunar\Models\ProductVariant as LunarProductVariant;

class ProductVariant extends LunarProductVariant
{
    protected $appends = [
        'price',
        'old_price',
        'discount_value',
        'discount_percentage',
        'display_sku'
    ];
    protected $casts = [
        'price' => 'integer',
        'old_price' => 'integer',
        'discount_value' => 'integer',
        'discount_percentage' => 'integer',
        'display_sku' => 'string'
    ];

    protected $fillable = [
        'price',
        'old_price',
        'discount_value',
        'discount_percentage',
        'display_sku'
    ];

    protected $attributes = [
        'price' => 0,
        'old_price' => 0,
        'discount_value' => 0,
        'discount_percentage' => 0,
        'display_sku' => ''
    ];


    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn () => 11111111
        );
    }


    protected function displaySku(): Attribute
    {
        return Attribute::make(
            get: fn () => 'XXXXXXXXXXX'
        );
    }

    protected function getPriceInfo()
    {
        // Your price calculation logic here
    }
}