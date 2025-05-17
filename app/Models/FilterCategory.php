<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilterCategory extends Model
{
    public function options()
    {
        return $this->hasMany(FilterOption::class);
    }
}
