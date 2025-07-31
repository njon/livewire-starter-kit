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
        // $channels = \Lunar\Models\Channel::all();
        // dd($channels);

//          $activitiesWithIds = [
//         1 => [ // Adventure & Action
//             "Bungee Jumping",
//             "Climbing & Caving",
//             "Extreme Sports",
//             "Hiking & Trekking",
//             "Horseback Riding",
//             "Martial Arts",
//             "Obstacle Course Racing",
//             "Paintball & Airsoft",
//             "Shooting & Archery",
//             "Survival & Bushcraft",
//             "Zip-lining & Aerial Adventures",
//             "Other"
//         ],
//         2 => [ // Airborne Experiences
//             "Aerobatic Flights",
//             "Flight Simulator Experiences",
//             "Flying Lessons",
//             "Gliding Experiences",
//             "Helicopter Tours",
//             "Hot Air Balloon Rides",
//             "Indoor Skydiving",
//             "Paragliding & Hang Gliding",
//             "Scenic Flights",
//             "Skydiving",
//             "Vintage Aircraft Flights",
//             "Other"
//         ],
//         3 => [ // Automotive Sports
//             "Classic Car Driving",
//             "Drifting",
//             "Formula Racing Experiences",
//             "Go-Karting",
//             "Monster Truck Driving",
//             "Motorcycle Experiences",
//             "Off-Roading & 4x4",
//             "Rally Driving",
//             "Supercar & Racing Experiences",
//             "Tank Driving Experiences",
//             "Track Days",
//             "Other"
//         ],
//         4 => [ // Beauty & Wellness
//             "Facials & Skincare",
//             "Fitness & Yoga",
//             "Floatation Therapy",
//             "Hair & Makeup",
//             "Manicures & Pedicures",
//             "Meditation & Mindfulness",
//             "Nutrition & Health Coaching",
//             "Perfume Making",
//             "Personal Styling & Shopping",
//             "Spa Days & Massage",
//             "Wellness Retreats",
//             "Other"
//         ],
//         5 => [ // Creative & Learning
//             "Acting & Theatre",
//             "Dance Classes",
//             "DIY & Home Improvement",
//             "Gardening & Floristry",
//             "Glassblowing",
//             "Jewellery Making",
//             "Language & Culture",
//             "Music Lessons",
//             "Painting & Drawing",
//             "Photography Workshops",
//             "Pottery & Ceramics",
//             "Other"
//         ],
//         6 => [ // Cultural & Entertainment
//             "Circus & Spectacles",
//             "Comedy Clubs",
//             "Concerts & Live Music",
//             "Escape Rooms",
//             "Exhibitions & Museums",
//             "Murder Mystery Events",
//             "Performing Arts & Theatre",
//             "Sporting Events Tickets",
//             "Stadium & Venue Tours",
//             "Theme Parks & Attractions",
//             "Movie & Film Experiences",
//             "Other"
//         ],
//         7 => [ // Culinary & Dining
//             "Afternoon Tea",
//             "Baking & Patisserie",
//             "Bartending & Mixology",
//             "Brewery & Distillery Tours",
//             "Chocolate Making",
//             "Cooking Classes",
//             "Fine Dining",
//             "Food Tours & Tastings",
//             "Sushi Making",
//             "Vineyard & Wine Tasting",
//             "Barista & Coffee Making",
//             "Other"
//         ],
//         8 => [ // Nature & Wildlife
//             "Animal Encounters",
//             "Beekeeping",
//             "Bird Watching",
//             "Botanical & Garden Tours",
//             "Eco-Tours & Conservation",
//             "Falconry",
//             "Farm Stays & Experiences",
//             "Foraging Workshops",
//             "Safaris & Wildlife Viewing",
//             "Whale & Dolphin Watching",
//             "Marine Biology Experiences",
//             "Other"
//         ],
//         9 => [ // Photography Experiences
//             "Astrophotography",
//             "Drone Photography",
//             "Fashion & Portrait Photography",
//             "Food Photography",
//             "Landscape & Nature Photography",
//             "Photography Tours & Workshops",
//             "Smartphone Photography",
//             "Street & Urban Photography",
//             "Studio Photography",
//             "Travel Photography",
//             "Wildlife Photography",
//             "Other"
//         ],
//         10 => [ // Short Breaks & Getaways
//             "Adventure Holidays",
//             "Beach & Coastal Retreats",
//             "City Breaks",
//             "Countryside & Rural Stays",
//             "Foodie Getaways",
//             "Glamping & Camping",
//             "Historic Hotel Stays",
//             "Log Cabins & Lodges",
//             "Luxury & Boutique Stays",
//             "Themed Breaks",
//             "Unique & Quirky Stays",
//             "Other"
//         ],
//         11 => [ // Tours & Sightseeing
//             "Architectural Tours",
//             "Bus & Coach Tours",
//             "Cycling Tours",
//             "Food & Drink Tours",
//             "Ghost & Paranormal Tours",
//             "Historical & Heritage Tours",
//             "Literary & Film Location Tours",
//             "River & Canal Cruises",
//             "Scooter & E-Bike Tours",
//             "Segway Tours",
//             "Walking Tours",
//             "Other"
//         ],
//         12 => [ // Water Sports & Aquatic
//             "Boat & Catamaran Trips",
//             "Canoeing & Kayaking",
//             "Coasteering",
//             "Diving & Snorkeling",
//             "Flyboarding & Hoverboarding",
//             "Jet Skiing & Powerboating",
//             "Kitesurfing",
//             "Sailing & Yachting",
//             "Stand-Up Paddleboarding",
//             "Surfing & Windsurfing",
//             "Wakeboarding & Waterskiing",
//             "Other"
//         ]
//     ];

// foreach ($activitiesWithIds as $parentCollectionId => $subcategories) {
//  $parentCollection = Collection::find($parentCollectionId);
//  if ($parentCollection) {
//       foreach ($subcategories as $subcategoryName) {
//             $childCollection = Collection::create([
//                  'collection_group_id' => 1,
//                  'attribute_data' => [
//                             'name' => new TranslatedText([
//                                  'en' => new Text($subcategoryName),
//                             ])
//                       ],
//             ]);
//             $childCollection->parent_id = $parentCollection->id; // Set to 0 for top-level collection
//             $childCollection->save();
//       }
//  }
// }

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