<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use \Lunar\Models\Order;

class HomepageController extends Controller
{
    function index()
    {
        $products = Product::paginate(10);
        $categories = \Lunar\Models\Collection::with([
            'defaultUrl',
            'parent.defaultUrl'
        ])->get();

        return view('homepage.index', compact('products', 'categories'));
    }
}
