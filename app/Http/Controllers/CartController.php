<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getCart();
        return view('partials.cart', compact('cart'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'product_name' => 'required|string',
            'price' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'image' => 'required|string', // New field for product image
            'link' => 'required' // New field for product link
        ]);

        $cart = $this->getCart();
        $productId = $request->input('product_id');

        // Check if product already exists in cart
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $request->input('quantity');
        } else {
            $cart[$productId] = [
                'product_id' => $productId,
                'product_name' => $request->input('product_name'),
                'price' => $request->input('price'),
                'quantity' => $request->input('quantity'),
                'image' => $request->input('image'), // Save product image
                'link' => $request->input('link') // Save product link
            ];
        }

        $cookie = $this->updateCart($cart);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully',
            'cart' => $cart,
            'total_items' => $this->getTotalItems($cart)
        ])->withCookie($cookie);
    }

    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer'
        ]);

        $cart = $this->getCart();
        $productId = $request->input('product_id');

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            $cookie = $this->updateCart($cart);

            return response()->json([
                'success' => true,
                'message' => 'Product removed from cart',
                'cart' => $cart,
                'total_items' => $this->getTotalItems($cart)
            ])->withCookie($cookie);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product not found in cart'
        ], 404);
    }

    public function updateQuantity(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = $this->getCart();
        $productId = $request->input('product_id');

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $request->input('quantity');
            $cookie = $this->updateCart($cart);

            return response()->json([
                'success' => true,
                'message' => 'Quantity updated successfully',
                'cart' => $cart,
                'total_items' => $this->getTotalItems($cart)
            ])->withCookie($cookie);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product not found in cart'
        ], 404);
    }

    public function getCart()
    {
        $cart = Cookie::get('cart');
        return $cart ? json_decode($cart, true) : [];
    }

    private function updateCart($cart)
    {
        return Cookie::make('cart', json_encode($cart), 60 * 24 * 30); // 30 days
    }

    private function getTotalItems($cart)
    {
        return array_reduce($cart, function($carry, $item) {
            return $carry + $item['quantity'];
        }, 0);
    }
}