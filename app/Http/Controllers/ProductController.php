<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Lunar\Models\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\CartService;
use App\Models\FilterCategory;
use App\Services\ProductSearchService;

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
        $counter = end_in_counter($product->discounts);


        return view('products.show', [
            'product' => $product,
            'relatedProducts' => $product->getRelatedProducts(),
            'title' => $product->translateAttribute('name') . ' | Your Store',
            'end' => $counter
        ]);
    }

    /**
     * Display products by collection.
     */
    public function category($collection)
    {
        $ajax = request()->get('ajax', false);
        $products = $collection->products()->paginate(4);
        $filterCategories = FilterCategory::with('options')->get();

        if($ajax == 'true') {
            // Get the current page from the request, default to 1
            $currentPage = request()->get('page', 1);
        
            // Return a JSON response with the rendered view and pagination data
            return view('products.ajax', [
                'products' => $products,
                'pagination' => $products->links(),
                'filterCategories' => $filterCategories,
            ])->render();
        }

        return view('products.collection', [
            'products' => $products,
            'collection' => $collection,
            'title' => $collection->translateAttribute('name') . ' Collection | Your Store',
            'filterCategories' => $filterCategories,
            'pagination' => $products->links()
        ]);
    }

    public function ajaxResults()
    {

    }

    public function page() 
    {
        return view('articles.index');
    }
}