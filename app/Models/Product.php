<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection as BaseCollection;
use Lunar\Models\Product as LunarProduct;
use Lunar\Models\Collection;
use Lunar\Models\Discount;
use Lunar\Models\TaxRateAmount;
use App\Models\ProductVariant;
use App\Services\DiscountService;
use Lunar\Models\Currency;
use App\Models\ProductQuestion;
use App\Models\ProductReview;
use Lunar\Models\Price;
use App\Models\Scopes\PriceBetweenScope;
use App\Traits\OwnerScope;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends LunarProduct
{
    use SoftDeletes; 
    use OwnerScope;

    protected $fillable = [
        "attribute_data",
        "product_type_id",
        "status",
        "owner_id"
    ];

    public static array $listingWith = [];

    public static array $detailWith = [
        'variants.basePrices.currency',
        'reviews',
        'variants',
    ];

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
            'average_rating' => fn() => (float) $this->reviews()->avg('rating'),
            'rating_stars' => fn() => (
                $rating = $this->reviews()->avg('rating')
            ) !== null
                ? str_repeat('★', floor($rating)) .
                    (fmod($rating, 1) >= 0.5 && $rating < 5 ? '☆' : '') .
                    str_repeat('☆', 5 - ceil($rating))
                : '☆☆☆☆☆',
                         
            'product_count' => fn() => (float) $this->reviews()->count(),
        ];
    }

    public function getDiscountedPrice()
    {
        $discount = $this->discounts()->orderBy('priority', 'desc')->first() ?? new Discount();

        return (new DiscountService($this->prices->first(), $discount))->calculate();
    }

    public function getDefaultPrice()
    {        
        return $this->prices->first()->price->value;
    }

    public static function findBySlug(string $slug): ?self
    {
        return static::with(static::$detailWith)
            ->whereHas('defaultUrl', fn($q) => $q->where('slug', $slug))
            ->where('status', 'published')
            ->first();
    }

    public static function getForCollection(Collection $collection, int $perPage = 12)
    {
        return $collection->products()
            ->with(static::$listingWith)
            ->latestProducts()
            ->paginate($perPage);
    }

    public static function getAllPublished(int $perPage = 12)
    {
        return static::with(static::$listingWith)
            ->latestProducts()
            ->paginate($perPage);
    }

    public function getRelatedProducts(int $limit = 4): BaseCollection
    {
        return static::with(static::$listingWith)
        // @todo uncomment maybe later
            // ->whereHas('collections', function($query) {
            //     $query->whereIn(
            //         'lunar_collection_product.collection_id', 
            //         $this->collections->pluck('id')
            //     );
            // })
            ->where('lunar_products.id', '!=', $this->id)
            ->where('lunar_products.status', 'published')
            ->limit($limit)
            ->get();
    }

    public function answeredQuestions()
    {
        return $this->questions()->whereNotNull('answered_at');
    }

    public function questions()
    {
        return $this->hasMany(ProductQuestion::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
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
            'discountable',
            'lunar_discountables' // Explicit table name
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

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    
    public function filterOptions()
    {
        return $this->belongsToMany(FilterOption::class, 'product_filters');
    }

    public function scopeApplySorting($query, $sorting = null)
    {
        if (!$sorting) {
            // You might want to set a default sorting here
            // return $query->orderBy('created_at', 'desc');
            return $query;
        }

        switch ($sorting) {
            case 'rating_asc':
                return $query->orderByRating('asc');
            case 'rating_desc':
                return $query->orderByRating('desc');
            case 'price_asc':
                return $query->orderByLowestPrice('asc');
            case 'price_desc':
                return $query->orderByLowestPrice('desc');
            default:
                return $query;
        }
    }

    public function scopeOrderByLowestPrice(Builder $query, string $direction = 'asc')
    {
        return $query->orderBy(
            \Lunar\Models\Price::select('price')
                ->join('lunar_product_variants', 'lunar_product_variants.id', '=', 'lunar_prices.priceable_id')
                ->whereColumn('lunar_product_variants.product_id', 'lunar_products.id')
                ->orderBy('price', $direction)
                ->limit(1),
            $direction
        );
    }

    public function scopeOrderByRating(Builder $query, string $direction = 'desc'): Builder
    {
        return $query->orderBy(
            \DB::table('product_reviews')
                ->selectRaw('COALESCE(AVG(rating), 0)')
                ->whereColumn('product_id', 'lunar_products.id'),
            $direction
        );
    }

    public function getThumbImage(): ?string
    {
        // @todo add fallback.jpg to public images folder
        $thumbnail = $this->getMedia('thumbnails')->first();
        
        if (!$thumbnail) {
            return 'fallback.jpg';
        }
        
        try {
            return $thumbnail->getUrl('small') ?? $thumbnail->getUrl();
        } catch (\Exception $e) {
            // Log error if needed
            // logger()->error("Thumbnail URL error: " . $e->getMessage());
            return $thumbnail->getUrl(); // Fallback to original URL
        }
    }
    

}