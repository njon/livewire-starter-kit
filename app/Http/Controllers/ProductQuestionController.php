<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductQuestion extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'question',
        'answer',
        'answered_by',
        'answered_at'
    ];

    protected $casts = [
        'answered_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(\Lunar\Models\Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function answerer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'answered_by');
    }

    public function scopeAnswered($query)
    {
        return $query->whereNotNull('answer');
    }

    public function scopeUnanswered($query)
    {
        return $query->whereNull('answer');
    }
}