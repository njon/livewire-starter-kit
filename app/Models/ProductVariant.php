<?php
// app/Models/ProductVariant.php
namespace App\Models;

use App\Services\DiscountService;
use Lunar\Models\Discount;
use App\Models\Product;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariant extends \Lunar\Models\ProductVariant
{
    
}