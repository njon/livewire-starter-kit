<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Channel extends \Lunar\Models\Channel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'handle',
        'phone',
        'email',
        'website',
        'address',
        'map_location',
        'attribute_data',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    public $casts = [
        'attribute_data' => 'array', // <-- Change 'boolean' to 'array' for JSON attribute
    ];
}
