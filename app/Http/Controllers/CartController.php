<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use App\Services\CartService;
use Lunar\Facades\CartSession;
use Lunar\Models\ProductVariant;
use Lunar\Models\Order;
use Lunar\Models\OrderAddress;
use Lunar\Models\BillingAddress;
use Lunar\Base\DataTransferObjects\PaymentAuthorize;
use Lunar\Models\Cart;
use Lunar\PaymentTypes\AbstractPayment;
use Illuminate\Support\Facades\App;
use Lunar\Facades\Payments;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\StripeClient;
use App\Events\OrderCompleted;


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
    public function order(Order $order)
    {
        return view('checkout.order-complete', compact('order'));
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

    public function xxx()
    {
        $stripe = new StripeClient(env('STRIPE_SECRET'));
        $YOUR_DOMAIN = 'https://crispy-rotary-phone-6rx99vvv952567j-80.app.github.dev';

        $checkout_session = $stripe->checkout->sessions->create([
            'ui_mode' => 'embedded',
            'customer_email' => 'customer@example.com',
            'billing_address_collection' => 'required',
            // 'shipping_address_collection' => ['allowed_countries' => ['US', 'CA']],
            'line_items' => [[
                    'price_data' => [  // No fixed Price ID needed
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Order #123', // Custom product name
                        ],
                        'unit_amount' =>31233, // Convert to cents
                    ],
                    'quantity' => 1,
                ]],
            'mode' => 'payment',
            'return_url' => $YOUR_DOMAIN . '/return.html?session_id={CHECKOUT_SESSION_ID}',
        ]);

        return response()->json(['clientSecret' => $checkout_session->client_secret]);
    }

    public function checkoutpage()
    {
        $hasCart = $this->cart->userHasCart();
        // $order = Order::find(240);
        // dd($order);

        // if(!$hasCart) {
        //     return view('partials.checkout.empty');
        // }
        
        $cart = $this->cart->getCart();

        return view('partials.checkout', compact('cart'));
    }

    protected function processStripePayment(Request $request)
    {
        // @todo Test in live
        
        try {
            // Validate the request
            $validated = $request->validate([
                'order_id' => 'required|exists:lunar_orders,id',
                'amount' => 'required|numeric|min:50', // Minimum 50 cents
                'currency' => 'sometimes|string|size:3'
            ]);

            // Get the order
            $order = Order::findOrFail($request->order_id);
            
            // Verify amount matches order total (in cents)
            $orderAmount = $order->total->value;
            if ($request->amount != $orderAmount) {
                throw new \Exception('Payment amount does not match order total');
            }
            
            Stripe::setApiKey(env('STRIPE_SECRET'));
            
            // Create payment intent
            $paymentIntent = PaymentIntent::create([
                'amount' => $orderAmount,
                'currency' => strtolower($order->currency_code),
                'metadata' => [
                    'order_id' => $order->id,
                    'cart_id' => CartSession::current()->id
                ],
                'description' => "Payment for Order #{$order->reference}"
            ]);

            // Update the order with payment information
            $order->update([
                'status' => 'payment-waiting', // Or your preferred status
                'meta' => [
                    'payment_intent_id' => $paymentIntent->id,
                    'payment_method' => 'stripe',
                    'payment_status' => 'requires_payment_method'
                ]
            ]);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id,
                'order_reference' => $order->reference,
                'amount' => $paymentIntent->amount,
                'currency' => $paymentIntent->currency
            ]);

        } catch (\Exception $e) {
            \Log::error('Stripe Payment Error: ' . $e->getMessage());
            
            return response()->json([
                'error' => $e->getMessage(),
                'order_id' => $request->order_id ?? null
            ], 500);
        }
    }

    public function completeOrder(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'payment_intent_id' => 'required|string',
                'order_id' => 'required|exists:lunar_orders,id'
            ]);

            // Retrieve the order
            $order = Order::findOrFail($request->order_id);
            
            // Verify the payment with Stripe
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);

            // Check payment status
            if ($paymentIntent->status !== 'succeeded') {
                throw new \Exception('Payment not completed. Status: ' . $paymentIntent->status);
            }

            // Verify payment amount matches order total (in cents)
            $orderAmountInCents = $order->total->value;
            if ($paymentIntent->amount !== $orderAmountInCents) {
                throw new \Exception('Payment amount mismatch');
            }

            // Update order status and payment info
            $order->update([
                'status' => 'payment-received', // Or your preferred status
                'meta' => [
                    'payment_status' => $paymentIntent->status,
                    'payment_received_at' => now(),
                    'payment_method' => $paymentIntent->payment_method_types[0] ?? 'card',
                    'payment_intent' => $paymentIntent->id,
                    'payment_details' => [
                        'amount_received' => $paymentIntent->amount_received,
                        'currency' => $paymentIntent->currency,
                        'charges' => $paymentIntent->charges->data[0] ?? null
                    ]
                ]
            ]);

            $transaction = $order->transactions()->create([
                'success' => true, // Marks payment as successful
                'type' => 'capture', // 'capture', 'refund', 'intent', etc.
                'driver' => 'stripe', // Payment gateway used (e.g., 'stripe', 'manual', etc.)
                'amount' => $paymentIntent->amount, // Amount in cents/pence (e.g., $50.00 = 5000)
                'reference' => $order->reference, // Transaction reference (e.g., Stripe charge ID)
                'status' => 'Payment sucessful', // 'succeeded', 'failed', 'pending'
                'notes' => 'Payment received via Stripe', // Optional notes
                'card_type' => 'card', // Card type (e.g., Visa, MasterCard)
            ]);

            // Clear the cart
            if ($cart = CartSession::current()) {
                $cart->delete();
            }

            event(new OrderCompleted($order));

            return response()->json([
                'success' => true,
                'order_reference' => $order->reference,
                'payment_status' => $paymentIntent->status
            ]);

        } catch (\Exception $e) {
            \Log::error('Order Completion Error: ' . $e->getMessage());
            
            return response()->json([
                'error' => $e->getMessage(),
                'order_id' => $request->order_id ?? null
            ], 500);
        }
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
            'customer_reference' => Auth::check() ? ('Customer ID: ' . Auth::user()->id) : 'Guest Checkout',
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

        // OrderAddress::create($addressData);

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
            'order_id' => $order->id,
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