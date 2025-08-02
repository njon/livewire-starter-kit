<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Lunar\Models\CartLine as LunarCartLine;

class CartLine extends LunarCartLine
{
    protected $fillable = [
        'partner_id',
    ];
    
    public function partner()
    {
        return $this->belongsTo(User::class, 'partner_id');
    }
}