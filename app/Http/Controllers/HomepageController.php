<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Collection;

class HomepageController extends Controller
{
    function index()
    {
        $products = Product::paginate(8);
        $categories = Collection::with([
            'defaultUrl',
            'parent.defaultUrl'
        ])->get();

        return view('homepage.index', compact('products', 'categories'));
    }
}
