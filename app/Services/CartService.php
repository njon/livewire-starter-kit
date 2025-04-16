<?php

// app/Services/CartService.php
namespace App\Services;

use Lunar\Facades\CartSession;
use Lunar\Models\Cart;
use Lunar\Models\CartLine;
use Illuminate\Support\Facades\Auth;

class CartService
{
    /**
     * Get the current cart (creates one if doesn't exist)
     */
    public function getCart(): Cart
    {
        return CartSession::current() ?: CartSession::create();
    }

    /**
     * Add an item to cart
     */
    public function addToCart(
        $purchasable,
        int $quantity = 1,
        array $meta = []
    ): Cart {
        $cart = $this->getCart();

        // Check if this item already exists in cart
        $existingLine = $cart->lines->first(function ($line) use ($purchasable) {
            return $line->purchasable_type == get_class($purchasable) &&
                   $line->purchasable_id == $purchasable->id;
        });

        if ($existingLine) {
            // Update quantity if already exists
            $existingLine->update([
                'quantity' => $existingLine->quantity + $quantity
            ]);
        } else {
            // Add new line
            CartLine::create([
                'cart_id' => $cart->id,
                'purchasable_type' => get_class($purchasable),
                'purchasable_id' => $purchasable->id,
                'quantity' => $quantity,
                'meta' => $meta
            ]);
        }

        return $cart->refresh()->load('lines');
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart($lineId): Cart
    {
        $cart = $this->getCart();
        $cart->lines()->where('id', $lineId)->delete();
        return $cart->refresh();
    }

    /**
     * Update cart line quantity
     */
    public function updateQuantity($lineId, $quantity): Cart
    {
        $cart = $this->getCart();
        
        $cart->lines()->where('id', $lineId)->update([
            'quantity' => $quantity
        ]);

        return $cart->refresh();
    }

    /**
     * Clear the cart
     */
    public function clearCart(): Cart
    {
        $cart = $this->getCart();
        $cart->lines()->delete();
        return $cart->refresh();
    }
}