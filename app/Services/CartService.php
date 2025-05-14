<?php

namespace App\Services;

use Lunar\Facades\CartSession;
use App\Models\Product;
use Lunar\Models\CartLine;
use Lunar\Base\StandardMedia;
use Lunar\Models\Cart;
use Lunar\Models\ProductVariant;

class CartService
{
    public function getCart(): Cart
    {
        $cart = CartSession::current() ?: CartSession::create(['currency_id' => 1, 'channel_id' => 1]);

        return $cart;
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
        try {
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

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'cart' => $cart->toArray()
            ];
        }
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

    public function getCartProducts()
    {        
        $cart = $this->getCart();

        if (!$cart) {
            return collect(); // Return empty collection if no cart exists
        }

        // Eager load the product relationships to avoid N+1 queries
        $cart->load('lines.purchasable.product');
        
        // Extract products from cart lines
        $products = $cart->lines->map(function ($line) {
            // Check if this is a product variant line
            if ($line->purchasable && $line->purchasable->product) {
                return $line->purchasable->product;
            }
            return null;

        })->filter()->unique('id');
        
        return $products;
    }

}