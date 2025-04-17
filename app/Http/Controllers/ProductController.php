<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;
use Lunar\Models\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Models\Collection as Collect;
use App\Services\CartService;
use Illuminate\Support\Facades\Cookie;
use Lunar\Facades\CartSession;

class ProductController extends Controller
{

    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function updateCart()
    {
        CartSession::clear();

        $cart = $this->cartService->getCart();

        $cookieCart = Cookie::get('cart');

        $cartContent = $cookieCart ? Collect(json_decode($cookieCart)) : Collect();
        $cartContent->each(function($item, $key) {
            $product = Product::find($key);
            $purchasable = $product->variants()->first();
        
            $cart = $this->cartService->addToCart(
                purchasable: $purchasable,
                quantity: $item->quantity,
                meta: [
                    'product_name' => $product->translateAttribute('name'),
                ]
            );
        });



        $cart = $this->cartService->getCart();

        return [
            'cart' => $cart, 
            'products' => $cart->lines, 
            'sub_total' => $cart->subTotal->formatted(), 
            'total' => $cart->total->formatted(),
            'total_discount' => $cart->discountTotal->formatted(),
            'sub_total_discounted' => $cart->subTotalDiscounted->formatted(),
            'tax' => $cart->taxTotal->formatted(),
        ];
    }

    public function checkout()
    {
        $cart = $this->updateCart();

        return view('partials.checkout', $cart);
    }

    /**
     * Add product to cart
     */
    public function addToCart()
    {
        // Dummy data instead of request input
        $dummyData = [
            'id' => 12, // product_id = 1
            'quantity' => 2, // default quantity
        ];
    
        try {
            $product = Product::findOrFail($dummyData['id']);
            
            $purchasable = $product->variants()->first();
    
            if (!$purchasable) {
                return redirect()->back()->with('error', 'Selected variant not available');
            }
    
            // Check stock - using dummy quantity 2
            if ($purchasable->stock < $dummyData['quantity']) {
                return redirect()->back()->with('error', 'Not enough stock available');
            }
            // Check if the product is purchasable

            // Add to cart with dummy data
            $cart = $this->cartService->addToCart(
                purchasable: $purchasable,
                quantity: $dummyData['quantity'],
                meta: [
                    'product_name' => $product->translateAttribute('name'),
                ]
            );
            dd($cart);

            return redirect()->route('cart.view')
                ->with('success', __('Product added to cart successfully'));
    
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', __('Failed to add product to cart: ') . $e->getMessage());
        }
    }

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

        // $dd = \App\Models\ProductQuestion::all();;
        // dd($dd);

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
        // Check if the collection exists
        $id = Collection::with(['defaultUrl'])
            ->whereHas('defaultUrl', fn($q) => $q->where('slug', $collectionSlug))
            ->first()->id;

        $products = Product::paginate(3);

        $collection = Collection::with(['defaultUrl'])
            ->whereHas('defaultUrl', fn($q) => $q->where('slug', $collectionSlug))
            ->firstOrFail();

        return view('products.collection', [
            'products' => $products,
            'collection' => $collection,
            'seoTitle' => $collection->translateAttribute('name') . ' Collection | Your Store'
        ]);
    }



    public function ajaxResults()
    {
        // Get the current page from the request, default to 1
        $currentPage = request()->get('page', 2);
    
        // Paginate the products with 10 items per page
        $products = Product::paginate(3, ['*'], 'page', $currentPage);

        
    
        // Return a JSON response with the rendered view and pagination data
        return view('products.ajax', [
            'products' => $products,
        ])->render();
    }
}