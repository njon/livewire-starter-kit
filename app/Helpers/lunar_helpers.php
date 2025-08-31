<?php

use Lunar\Models\Cart;
use Lunar\Facades\CartSession;
use Lunar\DataTypes\Price;
use Lunar\Models\Currency;
use Carbon\Carbon;
use Lunar\Models\Order;
use Lunar\Models\Product;
use Lunar\FieldTypes\TranslatedText;
use Lunar\FieldTypes\Text;
use Lunar\Models\OrderLine;
use Lunar\Models\Transaction;
use Lunar\Models\Customer;



if (!function_exists('remove_orders')) {
    /**
     * Format discounted price
     */
    function remove_orders()
    {
        Order::query()->each(function ($order) {
            // Delete transactions first
            $order->transactions()->delete();
            
            // Delete order lines
            $order->lines()->delete();
            
            // Delete addresses
            $order->billingAddress()->delete();
            $order->shippingAddress()->delete();
            
            // Finally delete the order
            $order->delete();
        });
    }
}

if (!function_exists('format_price')) {
    /**
     * Format discounted price
     */
    function format_price(int $value): Price
    {
        return new Price($value, Currency::where('code', 'EUR')->first());
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
    function generate_order_prices($orders)
    {
        $orders->each(function($order) {
            $order->owner_subtotal = $order->lines->sum('total.value') - $order->lines->sum('tax_total.value');
            $order->owner_total = $order->lines->sum('total.value');
            $order->owner_vat = $order->lines->sum('tax_total.value');
            $order->owner_discount = $order->lines->sum('discount_total.value'); // Fixed typo: discount → discount
        });

        return $orders;
    }
}
    
if (!function_exists('delete_products')) {

    function delete_products() {
        $products = App\Models\Product::all();
        
        foreach ($products as $product) {
            // Detach all relationships before deletion
            $product->collections()->detach();
            $product->tags()->detach();
            $product->associations()->delete(); // For product associations
            
            $product->variants()->each(function ($variant) {
                $variant->prices()->delete();
                $variant->delete();
            });
            
            // Finally delete the product
            $product->delete();
        }
        
        return "All products and their relationships have been deleted.";
    }
}

if (!function_exists('get_table_columns')) {
    /**
     * Get all column names from a database table
     *
     * @param string $table The table name
     * @return array
     * @throws \Exception
     */
    function get_table_columns(string $table): array
    {
        try {
            return \Illuminate\Support\Facades\Schema::getColumnListing($table);
        } catch (\Exception $e) {
            throw new \Exception("Failed to get columns for table {$table}: " . $e->getMessage());
        }
    }
}

if (!function_exists('attributes_data')) {
    function attributes_data(array $attributes, array $languages = ['en', 'gr']): array
    {
        $result = [];
        $fields = ['name', 'description', 'url'];
        
        foreach ($fields as $field) {
            if (!isset($attributes[$field])) {
                continue;
            }
            
            $translations = [];
            
            foreach ($languages as $lang) {
                if (empty($attributes[$field][$lang])) {
                    continue;
                }
                
                $translations[$lang] = new Text($attributes[$field][$lang]);
            }
            
            if (!empty($translations)) {
                $result[$field] = new TranslatedText($translations);
            }
        }
        
        return $result;
    }
}

if (!function_exists('attribute_data')) {

    function attribute_data($attributes): array
    {
        return [
            'name' => new TranslatedText([
                'en' => new Text($attributes['name']['en']),
                'gr' => new Text($attributes['name']['gr']),
            ]),
            'description' => new TranslatedText([
                'en' => new Text($attributes['description']['en']),
                'gr' => new Text($attributes['description']['gr']),
            ]),
            'url' => new TranslatedText([
                'en' => new Text($attributes['url']['en']),
                'gr' => new Text($attributes['url']['gr']),
            ]),
        ];
    }
}

if (!function_exists('common_attributes')) {

    function common_attributes($validated): array
    {
        return [
            'tax_class_id' => 1,
            'stock' => 5000,
            'owner_id' => auth()->user()->owner_id,
            'attribute_data' => attributes_data($validated)
        ];
    }
}


if (!function_exists('product_attribute_data')) {

    function product_attribute_data($attributes): array
    {
        return [
            'name' => new TranslatedText([
                'en' => new Text($attributes['name']['en']),
                'gr' => new Text($attributes['name']['gr']),
            ]),
            'description' => new TranslatedText([
                'en' => new Text($attributes['description']['en']),
                'gr' => new Text($attributes['description']['gr']),
            ])
        ];
    }
}

if (!function_exists('product_has_filter_options')) {
    /**
     * Check if a product has specific filter options
     *
     * @param \Lunar\Models\Product $product
     * @param array $filterOptionIds
     * @return array [
     *     'selected_ids' => array,
     *     'checker' => \Closure
     * ]
     */
    function product_has_filter_options(Product $product, array $filterOptionIds = []): array
    {
        $selectedIds = $product->filterOptions->pluck('id')->toArray();
        
        $checker = function(int $id) use ($selectedIds): bool {
            return in_array($id, $selectedIds, true);
        };
        
        return [
            'selected_ids' => $selectedIds,
            'checker' => $checker
        ];
    }
}

if (!function_exists('new_customer')) {
    /**
     * Check if a product has specific filter options
     *
     * @param \Lunar\Models\Product $product
     * @param array $filterOptionIds
     * @return array [
     *     'selected_ids' => array,
     *     'checker' => \Closure
     * ]
     */
    function new_customer()
    {
        $customers = Customer::all();

        $customer = Customer::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            
            // Optional standard fields
            'company_name' => 'ACME Corporation',
            'account_ref' => 'CUST-12345', // Your internal reference
            
            // Contact information
            
            // Meta data (store any custom fields as JSON)
            'meta' => [
                'newsletter_subscribed' => true,
                'preferred_contact_method' => 'email',
                'lead_source' => 'website',
                'customer_tier' => 'premium',
                'notes' => 'Important client with special pricing',
            ],
    
        ]);

        $customer->users()->attach(auth()->id());

        $defaultGroup = \Lunar\Models\CustomerGroup::whereDefault(true)->first();
        $customer->customerGroups()->attach($defaultGroup);
    }
}

use Illuminate\Support\Facades\Auth;

if (!function_exists('get_customer')) {
    /**
     * Get the current user's customer record
     *
     * @return \Lunar\Models\Customer|null
     */
    function get_customer()
    {
        // Get the authenticated user
        $user = Auth::user();
        
        if (!$user) {
            return null;
        }

        // Return the first customer associated with the user
        return $user->customers()->first();
    }
}


if (!function_exists('remove_deleted_services_from_carts')) {
    function remove_deleted_services_from_carts($deleted_ids)
    {
        // Get cart IDs that contain the deleted products
        $cart_ids = \Lunar\Models\CartLine::whereIn('purchasable_id', $deleted_ids)
            ->get()
            ->pluck('cart_id')
            ->unique()
            ->toArray();

        // Delete the lines from those carts
        if (!empty($cart_ids)) {
            \Lunar\Models\CartLine::whereIn('cart_id', $cart_ids)
                ->whereIn('purchasable_id', $deleted_ids)
                ->delete();
        }
    }
}
