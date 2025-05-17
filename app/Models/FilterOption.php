<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilterOption extends Model
{
    public function category()
    {
        return $this->belongsTo(FilterCategory::class, 'filter_category_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_filters');
    }
}