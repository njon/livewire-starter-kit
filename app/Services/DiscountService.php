<?php

namespace App\Services;

// use Lunar\Models\Price;
use Lunar\Models\Discount;

class DiscountService
{
    protected Discount $discount;

    public function __construct($price, Discount $discount)
    {
        $this->price = $price;
        $this->discount = $discount;
    }

    public function calculate(): int
    {
        $value = $this->price->price->value ?? 0;
        $data = $this->discount->data ?? [];

        if (!empty($data['fixed_value'])) {
            $fixed = (int) ($data['fixed_values']['Eur'] ?? 0);
            return max(0, $value - $fixed);
        }

        if (isset($data['percentage'])) {
            $percent = (float) $data['percentage'];
            $discountAmount = (int) round($value * ($percent / 100));
            return max(0, $value - $discountAmount);
        }

        return $value;
    }
}
