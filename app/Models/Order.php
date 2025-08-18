<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\OwnerScope;

class Order extends \Lunar\Models\Order
{
    use OwnerScope;
    

    public function scopeOwnerx($query)
    {
        return $query->where('owner_id', auth()->user()->owner_id);
    }

}