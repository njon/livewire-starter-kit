<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Lunar\Models\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Models\Collection as Collect;


class ProductController extends Controller
{
    /**
     * Display a listing of all published products.
     */
    public function index(): View
    {
        $products = Product::getAllPublished();
        
        $product = $products->first();

        return view('products.index', [
            'products' => Product::getAllPublished()
        ]);
    }

    /**
     * Display the specified product.
     */
    public function show(string $slug): View
    {
        $product = Product::findBySlug($slug);

        if (!$product) {
            throw new NotFoundHttpException('Product not found');
        }

        return view('products.show', [
            'product' => $product,
            'relatedProducts' => $product->getRelatedProducts(),
            'seoTitle' => $product->translateAttribute('name') . ' | Your Store',
        ]);
    }

    /**
     * Display products by collection.
     */
    public function byCollection(string $collectionSlug): View
    {
        $collection = Collection::with(['defaultUrl'])
            ->whereHas('defaultUrl', fn($q) => $q->where('slug', $collectionSlug))
            ->firstOrFail();

        return view('products.collection', [
            'products' => Product::getForCollection($collection),
            'collection' => $collection,
            'seoTitle' => $collection->translateAttribute('name') . ' Collection | Your Store'
        ]);
    }
}