<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Lunar\Facades\CartSession;
use App\Models\CartLine;
use Lunar\Base\StandardMedia;
use Lunar\Models\Cart;
use Lunar\Models\ProductVariant;

class CartService
{
    public function getCart(): Cart
    {
        return CartSession::current() ?: CartSession::create([
            'user_id' => auth()->id(),
        ]);
    }

    public function userHasCart()
    {
        if (!Auth::check()) {
            return false; // User is not logged in
        }

        return Cart::where('user_id', Auth::id())->exists();
    }

    public function calculateDiscountedPrices(Cart $cart): void
    {
        // Ensure the cart lines are loaded

        foreach ($cart->lines as $line) {
            
            // get discounted price
            $discountedPrice = format_price($line->subTotalDiscounted->value/$line->quantity);

            // Set the discounted price as the price attribute
            $line->price = $discountedPrice;

            // Optionally, save the line if you want to persist the discounted price
            // $line->save();
        }
    }

    /**
     * Delete the current cart
     */
    public function deleteCart(): bool
    {        
        if ($cart) {
            CartSession::forget();
            return true;
        }
        
        return false;
    }

    /**
     * Add item to cart using variant ID only
     */
    public function addToCart($purchasable, $quantity = 1): array
    {
            // Find the purchasable variant directly
            $cart = $this->getCart();

            if (!$purchasable) {
                throw new \RuntimeException("Variant not found");
            }

            // Check stock availability
            if ($purchasable->stock < $quantity) {
                throw new \RuntimeException("Insufficient stock available");
            }

            $variantName = $purchasable->values->first() ? $purchasable->values->first()->translate('name') : $purchasable->product->translate('name');

            // Find existing line for this variant
            $existingLine = $cart->lines->firstWhere('purchasable_id', $purchasable->id);

            if ($existingLine) {
                // Update existing line
                $existingLine->update([
                    'quantity' => $existingLine->quantity + $quantity
                ]);
            } else {
                // Create new cart line
                CartLine::create([
                    'cart_id' => $cart->id,
                    'purchasable_type' => get_class($purchasable),
                    'purchasable_id' => $purchasable->id,
                    'quantity' => $quantity,
                    'meta' => [
                        'product_name' => $purchasable->product->translateAttribute('name'),
                        'variant_name' => $variantName,
                    ]
                ]);
            }

            // Refresh cart in session
            CartSession::use($cart->refresh());

            return [
                'success' => true,
                'message' => 'Item added to cart',
                'cart' => $cart->toArray()
            ];

    }

    /**
     * Remove item from cart
     */
    public function removeFromCart($cart, int $product_id)
    {
        if ($cart) {
            $cart->lines()
                ->where('purchasable_id', $product_id)
                ->delete();

            // Refresh the cart in session
            CartSession::use($cart->refresh());
            return true;
        }

        return false;
    }

    /**
     * Update item quantity in cart
     */
    public function updateQuantity($ProductVariant, int $quantity)
    {
        $cart = $this->getCart();
        
        if (!$cart) {
            return false;
        }

        if ($quantity <= 0) {
            return $this->removeFromCart($ProductVariant->id);
        }

        // Find existing line for this variant
        $line = $cart->lines->firstWhere('purchasable_id', $ProductVariant->id);

        if ($line) {
            // Update existing line
            $line->update([
                'quantity' => $quantity
            ]);

            CartSession::use($cart->refresh());

            $cart->calculate();

            return $cart;
        } 

        return false;
    }

    /**
     * Generate cart items array for JSON response
     */
    public function canvasItems($cart): array
    {
        $html = view('partials.off-canvas-cart', ['products' => $products])->render();

        return [
            'success' => true,
            'html' => $html
        ];
    }

    /**
     * Get cart total
     */
    protected function getCartTotal(): string
    {
        if (!$cart) {
            return '0.00';
        }

        return $cart->total->formatted();
    }

    public function clearCartx($cart)
    {
        if ($cart) {
            $cart->lines()->delete();
            CartSession::use($cart->refresh());
        }
    }

    /**
     * Clear all items from cart
     */
    public function clearCart(): array
    {
        if ($cart) {
            $cart->lines()->delete();
            CartSession::use($cart->refresh());
        }
    }

    public function priceVariables($cart) 
    {
        return [
            'sub_total' => $cart->subTotal->formatted(),
            'price_total' => $cart->total->formatted(),
            'total_discount' => $cart->discountTotal->formatted(),
            'sub_total_discounted' => $cart->subTotalDiscounted->formatted(),
            'tax' => $cart->taxTotal->formatted(),
        ];
    }
}