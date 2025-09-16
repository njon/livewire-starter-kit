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
use Lunar\Models\CartLine;
use Lunar\Models\OrderLine;
use Lunar\Models\Transaction;
use Lunar\Models\Customer;
use Lunar\Models\Address;
use Lunar\Models\Channel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

function forceDeleteAllData()
{
    try {
        DB::beginTransaction();

        // ⚠️ DANGER: Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Delete all data from all tables (in safe order)
        $tables = [
            'lunar_transactions',
            'lunar_order_discount',
            'lunar_order_user', 
            'lunar_order_customer',
            'lunar_order_lines',
            'lunar_orders',
            'lunar_cart_line_discount',
            'lunar_cart_lines',
            'lunar_cart_discount',
            'lunar_carts',
            'lunar_addresses',
            'lunar_customer_user',
            'lunar_customers',
        ];

        foreach ($tables as $table) {
            if (DB::getSchemaBuilder()->hasTable($table)) {
                $deleted = DB::table($table)->delete();
                echo "Deleted {$deleted} records from {$table}\n";
            }
        }

        // ⚠️ Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        DB::commit();

        echo "Force deleted all data successfully!\n";

    } catch (\Exception $e) {
        // Ensure foreign key checks are re-enabled even on error
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        DB::rollBack();
        echo "Error: " . $e->getMessage() . "\n";
        throw $e;
    }
}



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

if (!function_exists('store_name')) {
    /**
     * Format discounted price
     */
    function store_name($store)
    {
        $locale = app()->getLocale();

        if(is_array($store->attribute_data)) {
            $arrayData = $store->attribute_data;
        } else {
            $arrayData = json_decode($store->attribute_data, true);
        }

        // Safely access the attribute_data array
        return $arrayData['name'][$locale] ?? $arrayData['name']['gr'];
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

if (!function_exists('delete_all_reviews_and_questions')) {
    /**
     * Delete all product reviews and questions
     *
     * @return array Status messages for both operations
     */
    function delete_all_reviews_and_questions(): array
    {
        $reviewCount = \App\Models\ProductReview::count();
        $questionCount = \App\Models\ProductQuestion::count();
        
        \App\Models\ProductReview::truncate();
        \App\Models\ProductQuestion::truncate();
        
        return [
            'reviews' => "Deleted {$reviewCount} product reviews.",
            'questions' => "Deleted {$questionCount} product questions.",
            'total' => "Total deleted: {$reviewCount} reviews and {$questionCount} questions."
        ];
    }
}

if (!function_exists('unattach_all_products_from_channels')) {
    /**
     * Unattach all products from all channels
     *
     * @return string
     */
    function unattach_all_products_from_channels(): string
    {
        $detachedCount = 0;
        
        Product::chunk(100, function ($products) use (&$detachedCount) {
            foreach ($products as $product) {
                $channelCount = $product->channels()->count();
                $product->channels()->detach();
                $detachedCount += $channelCount;
            }
        });
        
        return "Unattached {$detachedCount} product-channel relationships.";
    }
}

if (!function_exists('from_channels')) {
    /**
     * Unattach all products from all channels
     *
     * @return string
     */
    function from_channels()
    {
        $detachedCount = 0;
        
        Channel::chunk(100, function ($channels) {
            foreach ($channels as $channel) {
                $channel->products()->detach();
            }
        });
    }
}

if (!function_exists('send_test_email')) {
    /**
     * Send test email function
     *
     * @param string $subject
     * @param string $message
     * @return bool
     */
    function send_test_email($subject = 'Test Email', $message = 'This is a test email from Laravel.')
    {
        try {
            $recipient = 'jonasjov2@gmail.com';
            
            \Illuminate\Support\Facades\Mail::raw($message, function ($mail) use ($recipient, $subject) {
                $mail->to($recipient)
                     ->subject($subject);
            });
            return true;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Test email failed: ' . $e->getMessage());
            return false;
        }
    }
}

if (!function_exists('generate_test_invoice')) {
    /**
     * Generate test invoice for a random order
     *
     * @param int|null $orderId Optional specific order ID
     * @return array [
     *     'success' => bool,
     *     'message' => string,
     *     'invoice_path' => string|null,
     *     'order_id' => int|null
     * ]
     */
    function generate_test_invoice(int|string|null $orderId = null)
    {
        try {
            $invoiceService = new \App\Services\InvoiceService();

            if ($orderId) {
                // Try to find order by ID first, then by reference if not found
                $order = Order::find($orderId);
                if (!$order) {
                    $order = Order::where('reference', $orderId)->first();
                }

                if (!$order) {
                    return [
                        'success' => false,
                        'message' => 'Order not found for ID/reference: ' . $orderId,
                        'invoice_path' => null,
                        'order_id' => null
                    ];
                }
            } else {
                $order = Order::where('status', 'payment-received')->inRandomOrder()->first();
                if (!$order) {
                    return [
                        'success' => false,
                        'message' => 'No completed orders found to generate test invoice.',
                        'invoice_path' => null,
                        'order_id' => null
                    ];
                }
            }

            $invoicePath = $invoiceService->generateInvoice($order);

            // Store invoice record in database if it doesn't exist
            $existingInvoice = \App\Models\Invoice::where('order_id', $order->id)->first();
            if (!$existingInvoice) {
                \App\Models\Invoice::create([
                    'order_id' => $order->id,
                    'owner_id' => $order->user_id,
                    'invoice_number' => 'TEST-INV' . date('Y') . '-' . $order->id,
                    'invoice_path' => $invoicePath,
                    'amount' => $order->total->value / 100,
                    'currency_code' => $order->currency_code,
                    'generated_at' => now(),
                ]);
            }

            return [
                'success' => true,
                'message' => 'Test invoice generated successfully for order ' . $order->reference,
                'invoice_path' => $invoicePath,
                'order_id' => $order->id
            ];

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Test invoice generation failed: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to generate test invoice: ' . $e->getMessage(),
                'invoice_path' => null,
                'order_id' => null
            ];
        }
    }
}

if (!function_exists('check_voucher_status')) {
    /**
     * Check if voucher exists and return its status with expiration information
     *
     * @param string $code The voucher code to check
     * @return array [
     *     'exists' => bool,
     *     'status' => string,
     *     'message' => string,
     *     'expires_at' => string|null,
     *     'days_remaining' => int|null,
     *     'voucher' => object|null
     * ]
     */
    function check_voucher_status(string $code): array
    {
        try {
            // Find the voucher by code using Voucher model
            $voucher = \App\Models\Voucher::where('code', $code)->first();
            
            if (!$voucher) {
                return [
                    'exists' => false,
                    'status' => 'not_found',
                    'message' => __('Voucher code not found. Please check your code and try again.'),
                    'expires_at' => null,
                    'days_remaining' => null,
                    'voucher' => null
                ];
            }
            
            $now = Carbon::now();
            $expiresAt = $voucher->expires_at ? Carbon::parse($voucher->expires_at) : null;
            
            // Check if voucher is cancelled
            if ($voucher->isCancelled()) {
                return [
                    'exists' => true,
                    'status' => 'cancelled',
                    'message' => __('This voucher has been cancelled and cannot be used.'),
                    'expires_at' => $expiresAt ? $expiresAt->format('M d, Y H:i') : null,
                    'days_remaining' => null,
                    'voucher' => $voucher
                ];
            }
            
            // Check if voucher is already used
            if ($voucher->isUsed()) {
                return [
                    'exists' => true,
                    'status' => 'used',
                    'message' => __('This voucher has already been used and cannot be redeemed again.'),
                    'expires_at' => $expiresAt ? $expiresAt->format('M d, Y H:i') : null,
                    'days_remaining' => null,
                    'voucher' => $voucher
                ];
            }
            
            // Check if voucher has expired
            if ($voucher->isExpired()) {
                return [
                    'exists' => true,
                    'status' => 'expired',
                    'message' => __('This voucher has expired on :date.', [
                        'date' => $expiresAt->format('M d, Y')
                    ]),
                    'expires_at' => $expiresAt->format('M d, Y H:i'),
                    'days_remaining' => 0,
                    'voucher' => $voucher
                ];
            }
            
            // Voucher is valid and active
            $daysRemaining = $expiresAt ? $voucher->getRemainingValidityDays() : null;
            
            $message = __('Voucher is valid and ready to use!');
            if ($expiresAt) {
                if ($daysRemaining <= 0) {
                    $message .= ' ' . __('Expires today at :time.', ['time' => $expiresAt->format('H:i')]);
                } elseif ($daysRemaining == 1) {
                    $message .= ' ' . __('Expires tomorrow.');
                } elseif ($daysRemaining <= 7) {
                    $message .= ' ' . __('Expires in :days days.', ['days' => $daysRemaining]);
                } else {
                    $message .= ' ' . __('Valid until :date.', ['date' => $expiresAt->format('M d, Y')]);
                }
            } else {
                $message .= ' ' . __('No expiration date.');
            }
            
            return [
                'exists' => true,
                'status' => 'valid',
                'message' => $message,
                'expires_at' => $expiresAt ? $expiresAt->format('M d, Y H:i') : null,
                'days_remaining' => $daysRemaining,
                'voucher' => $voucher
            ];
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Voucher check failed: ' . $e->getMessage());
            
            return [
                'exists' => false,
                'status' => 'error',
                'message' => __('An error occurred while checking the voucher. Please try again.'),
                'expires_at' => null,
                'days_remaining' => null,
                'voucher' => null
            ];
        }
    }
}
            