<?php

// app/Scopes/PriceBetweenScope.php
namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class PriceBetweenScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        // Only apply if min_price or max_price exists in request
        if (request()->has('min_price') || request()->has('max_price')) {
            $minPrice = request('min_price') ? request('min_price') * 100 : null; // Convert to cents
            $maxPrice = request('max_price') ? request('max_price') * 100 : null; // Convert to cents

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
    }
}