<?php

namespace App\Services;

use App\Models\Product;
use App\Models\FilterOption;

class ProductSearchService
{
    public function search(array $filters = [])
    {
        $query = Product::query()->with(['filterOptions.category']);

        foreach ($filters as $filterSlug => $values) {
            if (empty($values)) continue;

            $query->whereHas('filterOptions', function($q) use ($filterSlug, $values) {
                $q->whereHas('category', function($q) use ($filterSlug) {
                    $q->where('slug', $filterSlug);
                });
                
                if (is_array($values)) {
                    $q->whereIn('value', $values);
                } else {
                    $q->where('value', $values);
                }
            });
        }

        return $query;
    }

    // For price range filtering
    public function applyPriceRange($query, $minPrice, $maxPrice)
    {
        return $query->whereHas('variants', function($q) use ($minPrice, $maxPrice) {
            $q->whereHas('prices', function($q) use ($minPrice, $maxPrice) {
                if ($minPrice) $q->where('price', '>=', $minPrice * 100);
                if ($maxPrice) $q->where('price', '<=', $maxPrice * 100);
            });
        });
    }
}