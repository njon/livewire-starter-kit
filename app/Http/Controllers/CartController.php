<?php

namespace App\Http\Controllers;

use Lunar\Facades\CartSession;
use Lunar\Models\Cart;
use Lunar\Models\ProductVariant;
use Illuminate\Http\Request;
use Lunar\Base\Purchasable;
use Lunar\Models\CartLine;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getOrCreateCart();

        return view('partials.cart', ['cart' => $cart]);
    }

    public function canvasItems()
    {
        $cart = $this->getOrCreateCart();

        $html = view('partials.off-canvas-cart', ['cart' => $cart])->render();

        return response()->json([
            'success' => true,
            'html' => $html
        ]);
    }

    public function update(ProductVariant $ProductVariant , Request $request)
    {
        $purchasable = $ProductVariant;

        $request->validate([
            'quantity' => 'required|numeric|min:1',
        ]);

        $quantity = $request['quantity'];

        $cart = $this->getOrCreateCart();

        $existingLine = $cart->lines()
            ->where('purchasable_type', get_class($purchasable))
            ->where('purchasable_id', $purchasable->id)
            ->first();

        if ($existingLine) {
            $existingLine->update([
                'quantity' => $existingLine->quantity + $quantity,
                'meta' => [
                    'product_name' => $purchasable->product->translateAttribute('name'),
                    'updated_at' => now(),
                ]
            ]);
        } else {
            CartLine::create([
                'cart_id' => $cart->id,
                'purchasable_type' => get_class($purchasable),
                'purchasable_id' => $purchasable->id,
                'quantity' => $quantity,
                'meta' => [
                    'product_name' => $purchasable->product->translateAttribute('name'),
                    'created_at' => now(),
                ]
            ]);
        }

        $cart->calculate();

        $response = array_merge([
            'success' => true,
            'message' => 'Quantity updated successfully',
        ]);

        return response()->json($response, 200);
    }

    public function showCart()
    {
        $cart = $this->getOrCreateCart();
        return view('cart.show', compact('cart'));
    }

    public function removeFromCart($cartLineId)
    {
        $cart = $this->getOrCreateCart();
        $cart->lines()->where('id', $cartLineId)->delete();
        $cart->calculate();

        return redirect()->back()->with('success', 'Item removed from cart');
    }

    protected function getOrCreateCart(): Cart
    {
        $cart = CartSession::current() ?? CartSession::createNew();



        // if(auth()->check()) {
        //         $cart = CartSession::create([
        //             'currency_id' => 1,
        //             'user_id' => auth()->id(),
        //             'channel_id' => 22,
        //             'meta' => ['created_at' => now()],
        //         ]);
        // }


        return $cart;
    }

    public function refreshLunarCache()
    {
        $cart = $this->getOrCreateCart();
        $cart->lines()->delete();
        $cart->calculate();
        CartSession::forget();

        return redirect()->back()->with('success', 'Cart refreshed');
    }
}