<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Lunar\Models\ProductVariant as LunarProductVariant;
use Lunar\Models\Discount;
use App\Services\DiscountService;

class ProductVariant extends LunarProductVariant
{
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
            'price' => fn() => formatted_price($this->getDiscountedPrice())->formatted(),
            'price_without_discount' => fn() => formatted_price($this->getDefaultPrice())->formatted(),
            'has_discount' => fn() => $this->getDiscountedPrice() !== $this->getDefaultPrice(),
            'discount_value' => fn() => formatted_price($this->getDefaultPrice() - $this->getDiscountedPrice())->formatted(),
            'discount_percentage' => fn() => number_format(100 - ($this->getDiscountedPrice()/$this->getDefaultPrice() * 100), 0),
        ];
    }

    public function getDiscountedPrice()
    {
        $discount = $this->product->discounts->first() ?? new Discount();

        return (new DiscountService($this->basePrices->first(), $discount))->calculate();
    }

    public function getDefaultPrice()
    {        
        return $this->basePrices->first()->price->value;
    }

    /**
     * Get all active discounts for this product variant
     */
    public function discounts(): MorphToMany
    {
        return $this->morphToMany(
            Discount::class,
            'discountable',
            'lunar_discountables'
        )
        ->withPivot(['type'])
        ->where(function($query) {
            $query->whereNull('starts_at')
                ->orWhere('starts_at', '<=', now());
        })
        ->where(function($query) {
            $query->whereNull('ends_at')
                ->orWhere('ends_at', '>=', now());
        })
        ->orderBy('priority', 'desc');
    }
}