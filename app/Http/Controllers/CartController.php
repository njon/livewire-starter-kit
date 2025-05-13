<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Services\CartService;
use Lunar\Facades\CartSession;
use Lunar\Models\ProductVariant;

class CartController extends Controller
{
    public $cart;

    public function __construct(CartService $cartService)
    {
        $this->cart = $cartService;
    }

    // public function getCart()
    // {
    //     $cart = $this->cart->getCart();

    //     $data = $this->cart->canvasItems($cart);

    //     return response()->json($data);
    // }

    public function index()
    {
        $cart = $this->cart->getCart();
        $this->cart->calculateDiscountedPrices($cart);

        return view('partials.cart', ['cart' => $cart]);
    }


    public function addToCart(ProductVariant $ProductVariant, Request $request)
    {
        $this->cart->addToCart($ProductVariant);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully',
            'cart' => $this->cart->getCart()
        ]);
    }


    // @todo ProductVariant $ProductVariant injection
    public function removeFromCart(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer'
        ]);

        $items = $this->cart->removeFromCart($this->cart->getCart(), $data['product_id']);

        return response()->json($items);
    }

    // @todo ProductVariant $ProductVariant injection
    public function updateQuantity(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = $this->cart->getCart();

        $this->cart->updateQuantity($data['product_id'], $data['quantity']);

        return response()->json([
            'success' => true,
            'message' => 'Quanity updated successfully',
            'total' => $cart->lines->firstWhere('purchasable_id', $data['product_id'])->total->formatted(),
            'cart' => $cart,
        ], 200);
    }

    public function canvasItems()
    {
        $cart = $this->cart->getCart();
        $this->cart->calculateDiscountedPrices($cart);

        $html = view('partials.off-canvas-cart', ['cart' => $cart])->render();

        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }

    private function getTotalItems($cart)
    {
        return array_reduce($cart, function($carry, $item) {
            return $carry + $item['quantity'];
        }, 0);
    }
}