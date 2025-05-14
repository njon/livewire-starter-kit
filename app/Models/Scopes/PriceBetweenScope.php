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


// if (request()->has('test')) {
//     $testValue = strtolower(request('test'));
//     $builder->whereHas('variants', function ($query) use ($testValue) {
//         $query->where('attribute_data->test->value', $testValue);
//     });
// }

    }
}