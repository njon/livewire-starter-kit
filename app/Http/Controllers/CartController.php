<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    /**
     * Display the cart.
     */
    public function index()
    {
        $cart = $this->cart->getCart();
        $this->cart->calculateDiscountedPrices($cart);

        return view('partials.cart', ['cart' => $cart]);
    }

    /**
     * Remove a product from the cart.
     */
    public function destroy(ProductVariant $ProductVariant)
    {
        $items = $this->cart->removeFromCart($this->cart->getCart(), $ProductVariant->id);

        $cart = $this->cart->getCart();

        $response = array_merge([
            'success' => true,
            'message' => 'Quantity updated successfully',
        ], $prices = $this->cart->priceVariables($cart));

        return response()->json($response, 200);
    }

    /**
     * Update the quantity of a product in the cart.
     */
    public function update(ProductVariant $ProductVariant, Request $request)
    {
        $quantity = $request->validate(['quantity' => 'required|integer|min:1'])['quantity'];

        $request->input('to_cart') 
            ? $this->cart->addToCart($ProductVariant) 
            : $this->cart->updateQuantity($ProductVariant, $quantity);

        $cart = $this->cart->getCart();

        $response = array_merge([
            'success' => true,
            'message' => 'Quantity updated successfully',
            'total' => $cart->lines->firstWhere('purchasable_id', $ProductVariant->id)->total->formatted(),
        ], $prices = $this->cart->priceVariables($cart));

        return response()->json($response, 200);
    }

    /**
     * Get the cart items for the off-canvas cart.
     */
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

    // @todo remove later
    public function deleteCollections()
    {
        $collections = \Lunar\Models\Collection::all();
        foreach ($collections as $collection) {
            $collection->products()->detach();
            $collection->delete();
        }
        
        dd($collections);
    }
}