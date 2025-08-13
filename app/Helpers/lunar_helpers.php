<?php

use Lunar\Models\Cart;
use Lunar\Facades\CartSession;
use Lunar\DataTypes\Price;
use Lunar\Models\Currency;
use Carbon\Carbon;
use Lunar\Models\Order;

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


if (!function_exists('get_category_image')) {

    function get_category_image($categoryName) {
        $categories = [
            "Adventure & Action" => "action1.png",
            "Airborne Experiences" => "airborne.png",
            "Automotive Sports" => "auto1.png",
            "Beauty & Wellness" => "spa1.png",
            "Creative & Learning" => "educational.png",
            "Cultural & Entertainment" => "restaurant1.png",
            "Culinary & Dining" => "restaurant2.png",
            "Nature & Wildlife" => "nature1.png",
            "Water Sports & Aquatic" => "water.png",
            "Short Breaks & Getaways" => "explore.png",
            "Tours & Sightseeing" => "tours.png",
            "Photography Experiences" => "xx.png",
        ];

        if (isset($categories[$categoryName])) {
            echo $categories[$categoryName];
        } else {
            echo "default.png"; // fallback image if category not found
        }
    }
}


if (!function_exists('lang_icon')) {
    /**
     * Format discounted price
     */
    function lang_icon($code)
    {
        $lang = $code == 'gr' ? 'gr' : 'gb';
        return '<span class="fi fi-' . $lang . ' fis"></span>';
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


if (!function_exists('generate_order_prices')) {
    /**
     * Get discounted price for a purchasable item
     */
    function generate_order_prices($order)
    {
        $subTotal = 0;
        $vatTotal = 0;
        $totalTotal = 0;
        $total = 0;
        $paid = 0;
        $lines = $order->lines->where('owner_id', auth()->user()->id);

        // Calculate totals from order lines
        foreach ($lines as $line) {
            if (!$line->owner_id) {
                continue;  // Skip lines without owner if needed
            }
            $lineTotal = $line->unit_price->value * $line->quantity;
            $subTotal += $lineTotal;
            
            $vatAmount = $line->tax_total->value * $line->quantity;
            $vatTotal += $vatAmount;

            $total = $line->total->value * $line->quantity;
            $totalTotal += $total;
        }
        
        // Calculate grand total
        $total = $subTotal + $vatTotal;
        
        // Return all calculated values
        return [
            'sub_total' => formatted_price($subTotal),
            'vat_total' => formatted_price($vatTotal),
            'total' => formatted_price($totalTotal),
            'paid' => formatted_price($paid),
            'balance' => formatted_price($total - $paid),
        ];
    }
}
    