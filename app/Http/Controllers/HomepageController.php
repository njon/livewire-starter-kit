<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Collection;

class HomepageController extends Controller
{
    function index()
    {
        $products = Product::all();
        $categories = Collection::all();


        return view('homepage.index', compact('products', 'categories'));
    }
}
