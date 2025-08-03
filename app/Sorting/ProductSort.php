<?php

namespace App\Sorting;

use App\Models\Product;
use Lunar\Models\ProductVariant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductSort
{
    public function apply(BelongsToMany $query, string $sortBy, string $direction = 'asc'): Builder
    {
        // Get the base query builder from the relationship
        $builder = $query->getQuery();
        
        return match ($sortBy) {
            'price' => $this->sortByPrice($builder, $direction),
            'name' => $this->sortByName($builder, $direction),
            'created_at' => $this->sortByCreatedAt($builder, $direction),
            'sku' => $this->sortBySku($builder, $direction),
            'stock' => $this->sortByStock($builder, $direction),
            default => $this->sortDefault($builder),
        };
    }

    protected function sortByPrice(Builder $query, string $direction): Builder
    {
        return $query->select('products.*')
            ->join('product_variants', 'products.id', '=', 'product_variants.product_id')
            ->join('prices', 'product_variants.id', '=', 'prices.priceable_id')
            ->where('prices.priceable_type', ProductVariant::class)
            ->orderBy('prices.price', $direction)
            ->groupBy('products.id');
    }

    protected function sortByName(Builder $query, string $direction): Builder
    {
        return $query->orderBy('attribute_data->name->'.app()->getLocale(), $direction);
    }

    protected function sortByCreatedAt(Builder $query, string $direction): Builder
    {
        return $query->orderBy('products.created_at', $direction);
    }

    protected function sortBySku(Builder $query, string $direction): Builder
    {
        return $query->select('products.*')
            ->join('product_variants', 'products.id', '=', 'product_variants.product_id')
            ->orderBy('product_variants.sku', $direction)
            ->groupBy('products.id');
    }

    protected function sortByStock(Builder $query, string $direction): Builder
    {
        return $query->select('products.*')
            ->join('product_variants', 'products.id', '=', 'product_variants.product_id')
            ->orderBy('product_variants.stock', $direction)
            ->groupBy('products.id');
    }

    protected function sortDefault(Builder $query): Builder
    {
        return $query->orderBy('products.created_at', 'desc');
    }
}