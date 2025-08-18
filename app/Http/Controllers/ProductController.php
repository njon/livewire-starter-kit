<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\CartService;
use App\Models\FilterCategory;
use App\Services\ProductSearchService;
use Illuminate\Support\Facades\DB;
use App\Models\FilterOption;
use App\Models\User;

class ProductController extends Controller
{
    /**
     * Display a listing of all published products.
     */
    public function index(): View
    {
        $products = Product::all();
        
        return view('products.index', [
            'products' => Product::all()
        ]);
    }

    /**
     * Display the specified product.
     */
    public function show($product): View
    {
        // @todo remove later
        // $us = auth()->user();
        // $user = User::find($us->id);

        $counter = end_in_counter($product->discounts);
        $relatedProducts = $product->getRelatedProducts();
        $category = $product->collections->first()->id ?? null;

        $breadcrums = $product->collections->first();

        return view('products.show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'title' => $product->translateAttribute('name') . ' | Your Store',
            'end' => $counter,
            'breadcrums' => $breadcrums,
        ]);
    }

    /**
     * Display products by collection.
     */
    public function category($collection)
    {
        $ajax = request()->get('ajax', false);
        $sort = request()->input('sort');
        $products = $collection->products()->applySorting($sort)->paginate(6);
        $filterCategories = FilterCategory::with('options')->get();
        $links = $products->links();

        $collectionId = $collection->id;

        if (in_array($sort, ['price_asc', 'price_desc'])) {
            $sortMethod = ($sort === 'price_desc') ? 'sortByDesc' : 'sortBy';
            
            $products = $products->$sortMethod(function($item) {
                return (float) filter_var($item->price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            });
        }

        $viewData = [
            'products' => $products,
            'filterCategories' => $filterCategories,
            'pagination' => $links,
        ];

        if ($ajax === 'true') {
            $filters = view('products.search-tags', ['filterCategories' => $filterCategories])->render();
            $productsHtml = view('products.ajax', $viewData)->render();

            return response()->json([
                'filters' => $filters,
                'products' => $productsHtml,
            ]);
        }

        $viewData['collection'] = $collection;
        $viewData['title'] = $collection->translateAttribute('name');

        return view('products.collection', $viewData);
    }

    public function page($name) 
    {
        return view('articles.' . $name);
    }
}