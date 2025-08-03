<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\OwnerScope;

class Order extends \Lunar\Models\Order
{
    use OwnerScope;
    
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function scopeForOwner($query)
    {
        return $query->where('owner_id', auth()->id());
    }

}