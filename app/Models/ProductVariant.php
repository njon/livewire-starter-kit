<?php
// app/Models/ProductVariant.php
namespace App\Models;

use App\Services\DiscountService;
use Lunar\Models\Discount;
use App\Models\Product;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends \Lunar\Models\ProductVariant
{
    public function getAttribute($key)
    {
        if (array_key_exists($key, $this->computedAttributes())) {
            return $this->computedAttributes()[$key]();
        }

        return parent::getAttribute($key);
    }

    public function getDiscountedPrice()
    {
        $discount = $this->product->discounts->first() ?? new Discount();

        return (new DiscountService($this->prices->sortBy('price')->first(), $discount))->calculate();
    }

    public function getDefaultPrice()
    {
        return $this->prices->sortBy('price')->first()->price->value;
    }

    protected function computedAttributes(): array
    {
        return [
            'price' => fn() => formatted_price($this->getDiscountedPrice())->formatted(),
            'old_price' => fn() => formatted_price($this->getDefaultPrice())->formatted(),
            'discount_value' => fn() => formatted_price($this->getDefaultPrice() - $this->getDiscountedPrice())->formatted(),
            'discount_percentage' => fn() => number_format(100 - ($this->getDiscountedPrice()/$this->getDefaultPrice() * 100), 0),
        ];
    }
}