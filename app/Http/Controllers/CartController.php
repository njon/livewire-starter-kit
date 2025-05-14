<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use App\Services\CartService;
use Lunar\Facades\CartSession;
use Lunar\Models\ProductVariant;
use Lunar\Models\Order;
use Lunar\Models\OrderAddress;
use Lunar\Models\BillingAddress;

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

        return response()->json($items);
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
        
        return response()->json([
            'success' => true,
            'message' => 'Quantity updated successfully',
            'total' => $cart->lines->firstWhere('purchasable_id', $ProductVariant->id)->total->formatted(),
        ], 200);
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

    /**
     * Helper function to calculate total items in the cart.
     */
    private function getTotalItems($cart)
    {
        return array_reduce($cart, function ($carry, $item) {
            return $carry + $item['quantity'];
        }, 0);
    }

    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'zip_code' => 'required|string|max:255',
            'country' => 'required|string|size:2',
            'order_notes' => 'nullable|string'
        ]);

        // Get the current cart
        $cart = $this->cart->getCart();

        // Create the order
        $order = Order::create([
            'user_id' => auth()->id(),
            'status' => 'awaiting-payment',
            'reference' => uniqid(),
            'sub_total' => $cart->subTotal->value,
            'total' => $cart->total->value,
            'notes' => $validated['order_notes'] ?? null,
            'currency_code' => $cart->currency->code,
            'channel_id' => 1, // @todo (???),
            'discount_total' => $cart->discountTotal->value,
            'tax_total' => $cart->taxTotal->value,
            'shipping_total' => 0,
            'tax_breakdown' => $cart->taxBreakdown,
        ]);

        // Create billing/shipping addresses
        $addressData = [
            'order_id' => $order->id,
            'title' => null,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'company_name' => null,
            'line_one' => $validated['address'],
            'line_two' => null,
            'line_three' => null,
            'city' => $validated['city'],
            'state' => null,
            'postcode' => $validated['zip_code'],
            'country_id' => 1, // $validated['country'],
            'contact_email' => $validated['email'],
            'contact_phone' => $validated['phone'],
            'type' => 'shipping', // or 'billing'
        ];

        OrderAddress::create($addressData);

        $addressData['type'] = 'billing';
        OrderAddress::create($addressData);

        // For separate billing address, duplicate with 'type' => 'billing'

        // Associate cart lines with order
        foreach ($cart->lines as $line) {
            $order->lines()->create([
                'purchasable_type' => $line->purchasable_type,
                'purchasable_id' => $line->purchasable_id,
                'type' => 'physical',
                'description' => 'no',//$line->purchasable->description,
                'option' => $line->purchasable->getOption(),
                'identifier' => $line->purchasable->getIdentifier(),
                'unit_price' => $line->unitPrice->value,
                'unit_quantity' => $line->purchasable->unit_quantity,
                'quantity' => $line->quantity,
                'sub_total' => $line->subTotal->value,
                'discount_total' => $line->discountTotal?->value ?? 0,
                'tax_breakdown' => $line->taxBreakdown,
                'tax_total' => $line->taxAmount->value,
                'total' => $line->total->value,
                'notes' => null,
                'meta' => $line->meta,
            ]);
        }

        // Clear the cart
        // CartSession::forget();

        return response()->json([
            'success' => true,
            'message' => 'Checkout done successfully',
        ]);
    }
}