<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Lunar\Models\Product;

class ProductReview extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'guest_email',
        'name',
        'review',
        'rating',
        'helpful_count',
        'verified_purchase',
        'token',
        'token_expires_at'
    ];

    protected $dates = ['token_expires_at'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeVerified($query)
    {
        return $query->where('verified_purchase', true);
    }

    public function scopeValidToken($query, $token)
    {
        return $query->where('token', $token)
                    ->where('token_expires_at', '>', now());
    }
}
