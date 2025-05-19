<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Lunar\Models\Cart
{

    protected $appends = [
        'sub_total',
        'total',
        'total_discount',
        'sub_total_discounted',
        'tax',
    ];


    public function getAttribute($key)
    {
        if (array_key_exists($key, $this->computedAttributes())) {
            return $this->computedAttributes()[$key]();
        }

        return parent::getAttribute($key);
    }

    protected function computedAttributes(): array
    {
        
        return [
            'xxx' => fn() => 1,
            'sub_total' => fn() => $this->subTotal->formatted(),
            'total' => fn() => $this->total->formatted(),
            'total_discount' => fn() => $this->discountTotal->formatted(),
            'sub_total_discounted' => fn() => $this->subTotalDiscounted->formatted(),
            'tax' => fn() => $this->taxTotal->formatted(),
        ];
    }}


// Pridet appends naujus laukelius su price changes
// 
