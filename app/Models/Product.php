<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection as BaseCollection;
use Lunar\Models\Product as LunarProduct;
use Lunar\Models\Collection;
use Illuminate\Support\Facades\URL;

class Product extends LunarProduct
{
    /**
     * Eager loads for product listings
     */
    public static array $listingWith = [
        'variants.basePrices',
        'defaultUrl',
        'thumbnail',
        'productType',
        'media',
    ];

    /**
     * Eager loads for single product view
     */
    public static array $detailWith = [
        'variants.basePrices',
        'collections.defaultUrl',
        'images',
        'defaultUrl',
        'productType.mappedAttributes',
        'associations'
    ];

    /**
     * Scope for published products
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope for latest products
     */
    public function scopeLatestProducts(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Find a product by its slug
     */
    public static function findBySlug(string $slug): ?self
    {
        return static::with(static::$detailWith)
            ->whereHas('defaultUrl', fn($q) => $q->where('slug', $slug))
            ->first();
    }

    /**
     * Get products for a collection
     */
    public static function getForCollection(Collection $collection, int $perPage = 12)
    {
        return $collection->products()
            ->with(static::$listingWith)
            ->published()
            ->latestProducts()
            ->paginate($perPage);
    }

    /**
     * Get all published products
     */
    public static function getAllPublished(int $perPage = 12)
    {
        return static::with(static::$listingWith)
            ->published()
            ->latestProducts()
            ->paginate($perPage);
    }

    /**
     * Get price for display
     */
    public function getDisplayPrice(): string
    {
        return optional($this->variants->first())->price?->formatted() ?? 'Price unavailable';
    }

    /**
     * Get related products (from same collections)
     */
    public function getRelatedProducts(int $limit = 4): BaseCollection
    {
        return static::with(static::$listingWith)
            ->whereHas('collections', function($query) {
                $query->whereIn(
                    'lunar_collection_product.collection_id', 
                    $this->collections->pluck('id')->toArray()
                );
            })
            ->where('lunar_products.id', '!=', $this->id)
            ->published()
            ->limit($limit)
            ->get();
    }

    /**
     * Access product attributes through the product type
     */
    public function getAttributeData()
    {
        return $this->productType->mappedAttributes->mapWithKeys(function ($attribute) {
            return [
                $attribute->handle => $this->attr($attribute->handle)
            ];
        });
    }

    public function getBasePrices()
    {
        $prices = $this->variants
            ->flatMap(function ($variant) {
                return $variant->basePrices->map(function ($price) use ($variant) {
                    return (object) [
                        'variant_id' => $variant->id,
                        'variant_name' => $variant->name,
                        'price' => $price->price->decimal,
                        'formatted_price' => $price->price->formatted(),
                        'currency_code' => $price->currency->code,
                        'compare_price' => optional($price->compare_price)->decimal,
                        'formatted_compare_price' => optional($price->compare_price)->formatted(),
                    ];
                });
            });

        // Optionally set the first price as the default price
        if ($prices->isNotEmpty()) {
            $this->attributes['price'] = $prices->first()->price; // Set the raw price
            $this->attributes['formatted_price'] = $prices->first()->formatted_price; // Set the formatted price
        }

        return $prices;
    }

    // Add an accessor for the price
    public function getPriceAttribute()
    {
        return $this->attributes['formatted_price'] ?? 'Price unavailable';
    }

    protected static function booted()
    {
        static::retrieved(function ($product) {
            if (str_contains(URL::current(), 'update')) {
                return false;
            }


            $product->getBasePrices();      

        });
    }
}