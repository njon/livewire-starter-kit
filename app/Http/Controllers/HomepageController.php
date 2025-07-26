<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use \Lunar\Models\Order;
use \Lunar\Models\Collection;
use \Lunar\Models\CollectionGroup;
use Illuminate\Support\Str;
use Lunar\FieldTypes\Text;
use Lunar\FieldTypes\TranslatedText;
use Illuminate\Support\Facades\DB;

class HomepageController extends Controller
{
    function index()
    {
        $product = Product::find(12);
        $mainImage = $product->getFirstMedia('main_image');

        dd($product->getFirstMediaUrl('images'));

        $products = Product::paginate(12);
        $categories = \Lunar\Models\Collection::with([
            'defaultUrl',
            'parent.defaultUrl'
        ])->where('parent_id', null)->get();


        return view('homepage.index', compact('products', 'categories'));
    }

    public function deleteOrders()
    {
        // Disable model events completely
        \Lunar\Models\Order::flushEventListeners();
        
        try {
            // Delete related tables first using direct DB queries
            \DB::table('lunar_order_lines')->delete();
            \DB::table('lunar_order_addresses')->delete();
            \DB::table('lunar_transactions')->delete();
            
            // Then delete orders
            $count = \Lunar\Models\Order::count();
            \Lunar\Models\Order::query()->delete();
            
            return $count;
        } finally {
            // Restore event listeners
            \Lunar\Models\Order::boot();
        }
    }

    public function load($page) {

                return view('admin.' . $page);

    }

}