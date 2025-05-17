<?php

use Lunar\Models\Cart;
use Lunar\Facades\CartSession;
use Lunar\DataTypes\Price;
use Lunar\Models\Currency;
use Carbon\Carbon;


if (!function_exists('format_price')) {
    /**
     * Format discounted price
     */
    function format_price(int $value): Price
    {
        $currency = Currency::where('code', 'EUR')->first();
        $price = new Price(
            $value, // value in smallest unit (cents/pence)
            $currency,
        );

        return $price;
    }
}

if (!function_exists('discounted_single_product_price')) {
    /**
     * Format discounted price
     */
    function discounted_single_product_price($product): int
    {
        return (int) (($product->subTotal->value - $product->discountTotal->value)/$product->quantity);
    }
}

if (!function_exists('discount_value')) {
    /**
     * Format discounted price
     */
    function discount_value($product)
    {
        return formatted_price( (int) ($product->discountTotal->value/$product->quantity));
    }
}

if (!function_exists('full_price')) {
    /**
     * Format discounted price
     */
    function full_price($product)
    {
        return formatted_price( (int) ($product->subTotal->value/$product->quantity));
    }
}

if (!function_exists('end_in_counter')) {
    /**
     * Get discounted price for a purchasable item
     */
    function end_in_counter($discount)
    {
        if ($discount->isEmpty()) {
            return null;
        }

        $endDate = $discount->first();

        $endDate = Carbon::parse($endDate->ends_at); // Your end date
        $now = Carbon::now();
        
        return [
            'days' => floor($now->diffInDays($endDate)),
            'hours' => $now->diffInHours($endDate) % 24,
            'minutes' => $now->diffInMinutes($endDate) % 60,
            'seconds' => $now->diffInSeconds($endDate) % 60,
            'total_seconds' => $now->diffInSeconds($endDate),
            'ended' => $now->greaterThan($endDate)
        ];
    }
}

if (!function_exists('formatted_price')) {
    /**
     * Get discounted price for a purchasable item
     */
    function formatted_price($price): Object
    {
        return new Price($price, Currency::getDefault());
    }
}


if (!function_exists('discounted_item_price')) {
    /**
     * Get discounted price for a purchasable item
     */
    function discounted_item_price($product): Object
    {
        $unformatted_price = discounted_single_product_price($product);
        $price = formatted_price($unformatted_price);
        return $price;
    }
}