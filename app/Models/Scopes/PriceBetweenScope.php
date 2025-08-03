<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Lunar\Models\Attribute;
use Lunar\Models\AttributeValues;

class PriceBetweenScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        // Price range filter (existing code)
        if (request()->has('min_price') || request()->has('max_price')) {
            $minPrice = request('min_price') ? request('min_price') * 100 : null;
            $maxPrice = request('max_price') ? request('max_price') * 100 : null;

            $builder->whereHas('variants', function($query) use ($minPrice, $maxPrice) {
                $query->whereHas('prices', function ($query) use ($minPrice, $maxPrice) {
                    if ($minPrice) {
                        $query->where('price', '>=', $minPrice);
                    }
                    if ($maxPrice) {
                        $query->where('price', '<=', $maxPrice);
                    }
                });
            });
        }

        // Apply owner_id filter to products
        $builder->where('owner_id', auth()->user()->owner_id);

        // @todo fix backend exclude
        // $request = request();
        // $filters = $request->except(['min_price', 'max_price']);


        // foreach ($filters as $filterSlug => $values) {
        //     if (empty($values)) continue;

        //     $builder->whereHas('filterOptions', function($q) use ($filterSlug, $values) {
        //         $q->whereHas('category', function($q) use ($filterSlug) {
        //             $q->where('slug', $filterSlug);
        //         });
                
        //         if (is_array($values)) {
        //             $q->whereIn('value', $values);
        //         } else {
        //             $q->where('value', $values);
        //         }
        //     });
        // }


    }
}