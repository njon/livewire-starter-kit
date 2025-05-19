<?php

namespace App\Services;

use App\Models\Product;
use App\Models\FilterOption;

class ProductSearchService
{
    public function search(array $filters = [])
    {


        dd($filters);

        $request = request();
    
        $query = $searchService->search($request->except(['min_price', 'max_price']));

        if ($request->has('occasion')) {
            $query->whereHas('filterOptions', function($q) use ($request) {
                $q->whereHas('category', function($q) {
                    $q->where('slug', 'occasion');
                })
                ->whereIn('value', (array)$request->occasion);
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