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

//    $activitiesWithIds = [
//     44 => [ // Adventure & Action
//         "Bungee Jumping",
//         "Climbing & Caving",
//         "Extreme Sports",
//         "Hiking & Trekking",
//         "Horseback Riding",
//         "Martial Arts",
//         "Obstacle Course Racing",
//         "Paintball & Airsoft",
//         "Shooting & Archery",
//         "Survival & Bushcraft",
//         "Zip-lining & Aerial Adventures",
//         "Other"
//     ],
// ];
// foreach ($activitiesWithIds as $parentCollectionId => $subcategories) { //     $parentCollection = Collection::find($parentCollectionId); //     if ($parentCollection) { //         foreach ($subcategories as $subcategoryName) { //             $childCollection = Collection::create([ //                 'collection_group_id' => 1, //                 'attribute_data' => [ //                         'name' => new TranslatedText([ //                             'en' => new Text($subcategoryName), //                         ]) //                     ], //             ]); //             $childCollection->parent_id = $parentCollection->id; // Set to 0 for top-level collection //             $childCollection->save(); //         } //     } // }



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

}