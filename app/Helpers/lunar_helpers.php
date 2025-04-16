<?php

use Lunar\Models\Cart;
use Lunar\Facades\CartSession;
use Lunar\DataTypes\Price;
use Lunar\Models\Currency;

if (!function_exists('currency')) {
    /**
     * Format money with currency symbol
     */
    function currency($amount, $currency = null): string
    {
        $currency = $currency ?? config('lunar.pricing.default_currency');
        $formatter = new NumberFormatter(app()->getLocale(), NumberFormatter::CURRENCY);
        $after_discount = new Price($value, $currency);
        $discounted_price = $after_discount->formatted();

        return $formatter->formatCurrency($amount, $currency);
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