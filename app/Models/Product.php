<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection as BaseCollection;
use Lunar\Models\Product as LunarProduct;
use Lunar\Models\Collection;
use Lunar\Models\DiscountPurchasable;
use Lunar\DataTypes\Price;
use Lunar\Models\Currency;
use Lunar\Models\TaxClass;
use Lunar\Models\TaxRateAmount;
use Lunar\Models\TaxRate;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;
use Lunar\Models\Discount;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Product extends LunarProduct
{
  
    public static array $listingWith = [
        'variants.basePrices.currency',
        'defaultUrl',
        'thumbnail',
        'productType',
        'media',
    ];

    public static array $detailWith = [
        'variants.basePrices.currency',
        'collections.defaultUrl',
        'images',
        'defaultUrl',
        'productType.mappedAttributes',
        'associations',
        'questions',
    ];

    /**
     * Calculate discounted price based on discount data
     */
    protected function calculateDiscountedPrice(int $priceValue, array $discountData): array
    {
        $discountedValue = $priceValue;
        $discountAmount = 0;
        $discountPercentage = null;

        // Handle fixed value discount
        if ($discountData['fixed_value'] ?? false) {
            $fixedDiscount = $discountData['fixed_values']['Eur'] ?? 0;
            $discountAmount = $fixedDiscount;
            $discountedValue = max(0, $priceValue - $fixedDiscount);
        }
        // Handle percentage discount
        elseif (isset($discountData['percentage'])) {
            $percentage = (float)$discountData['percentage'];
            $discountAmount = (int)round($priceValue * ($percentage / 100));
            $discountedValue = max(0, $priceValue - $discountAmount);
            $discountPercentage = $percentage;
        }

        return [
            'original' => $priceValue,
            'discounted' => $discountedValue,
            'discount_amount' => $discountAmount,
            'discount_percentage' => $discountPercentage,
        ];
    }

    /**
     * Get discount information for the product
     */
    public function getDiscountInfoAttribute($price = ''): array
    {
        if(!$price) {
            $price = $this->prices->first();
        }

        $priceValue = $price->price->value;
        $discounts = $this->discounts;

        if ($discounts->isEmpty()) {
            return [
                'has_discount' => false,
                'discount_value' => null,
                'discount_percentage' => null,
                'price_without_discount' => $price->price->formatted,
                'current_price' => $price->price->formatted,
            ];
        }

        // Get the first active discount (you might want to prioritize certain discounts)
        $discountData = $discounts->first()->data;
        $calculated = $this->calculateDiscountedPrice($priceValue, $discountData);

        return [
            'has_discount' => true,
            'discount_value' => format_price($calculated['discount_amount'])->formatted(),
            'discount_percentage' => $calculated['discount_percentage'],
            'price_without_discount' => format_price($calculated['original'])->formatted(),
            'current_price' => format_price($calculated['discounted'])->formatted(),
        ];
    }

    /**
     * Format price in euros
     */
    protected function formatPrice(int $value): Price
    {
        $currency = Currency::where('code', 'EUR')->first();
        $price = new Price(
            $value, // value in smallest unit (cents/pence)
            $currency,
        );

        return $price;
    }

    public static function findBySlug(string $slug): ?self
    {
        return static::with(static::$detailWith)
            ->whereHas('defaultUrl', fn($q) => $q->where('slug', $slug))
            ->first();
    }

    public static function getForCollection(Collection $collection, int $perPage = 12)
    {
        return $collection->products()
            ->with(static::$listingWith)
            ->published()
            ->latestProducts()
            ->paginate($perPage);
    }

    public static function getAllPublished(int $perPage = 12)
    {
        return static::with(static::$listingWith)
            ->published()
            ->latestProducts()
            ->paginate($perPage);
    }

    public function getRelatedProducts(int $limit = 4): BaseCollection
    {
        return static::with(static::$listingWith)
            ->whereHas('collections', function($query) {
                $query->whereIn(
                    'lunar_collection_product.collection_id', 
                    $this->collections->pluck('id')
                );
            })
            ->where('lunar_products.id', '!=', $this->id)
            ->published()
            ->limit($limit)
            ->get();
    }

    public function tax()
    {
        // THIS WILL BE CORRECT-> ADD TAX CLASS ID
        $tax_class_id = $this->variants->first()->tax_class_id ?? null;
        $taxRateAmount = TaxRateAmount::whereHas('taxRate', function($query) use ($tax_class_id) {
            $query->where('id', $tax_class_id);
        })->get(); 

        return $taxRateAmount;
    }

    public function getHasDiscountAttribute(): bool
    {
        return $this->discount_info['has_discount'];
    }

    public function getDiscountValueAttribute(): ?string
    {
        return $this->discount_info['discount_value'];
    }

    public function getDiscountPercentageAttribute(): ?float
    {
        return $this->discount_info['discount_percentage'];
    }

    public function getPriceWithoutDiscountAttribute(): string
    {
        return $this->discount_info['price_without_discount'];
    }

    public function getPriceAttribute()
    {
        return $this->has_discount ? $this->discount_info['current_price'] : $this->prices->first()->price->formatted();
    }

    public function getOriginalAttribute()
    {
        return $this->variants->first()['formatted']['original'] ?? null;
    }


    public function averageRating(): float
    {
        return (float) $this->reviews()->avg('rating');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeLatestProducts(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
    }


    public function questions()
    {
        return $this->hasMany(ProductQuestion::class);
    }

    public function answeredQuestions()
    {
        return $this->questions()->whereNotNull('answered_at');
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    /**
     * Get all active discounts for this product
     * @todo Add multiple discount types
     * @todo Add discount per product collection, etc
     */
    public function discounts(): MorphToMany
    {
        return $this->morphToMany(
            Discount::class,
            'purchasable',
            'lunar_discount_purchasables' // Explicit table name
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

    public function updateVariants()
    {
        $this->variants->each(function ($variant) {

            $price = $variant->basePrices->first();
            $prices = $this->getDiscountInfoAttribute($price);
            $variant->setAttribute('price', $prices['current_price']);
            $variant->setAttribute('old_price', $prices['price_without_discount']);
            $variant->setAttribute('discount_value', $prices['discount_value']);
            $variant->setAttribute('discount_percentage', $prices['discount_percentage']);
            
            return $variant;
        });
    }
    
    protected static function booted()
    {
        static::retrieved(function ($product) {
            $product->getDiscountInfoAttribute();
        });

        static::addGlobalScope(new \App\Models\Scopes\PriceBetweenScope);
    }
}