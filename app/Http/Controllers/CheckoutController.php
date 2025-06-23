<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\CartService;
use Lunar\Facades\CartSession;
use Lunar\Models\Order;
use Lunar\Models\OrderAddress;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Events\OrderCompleted;

class CheckoutController extends Controller
{
    public $cart;

    public function __construct(CartService $cartService)
    {
        $this->cart = $cartService;
    }

    public function index()
    {
        $cart = $this->cart->getCart();

        return view('partials.checkout', compact('cart'));
    }

    /**
     * Display the order confirmation page.
     */
    public function order($reference_id)
    {
        $order = Order::where('reference', $reference_id)
            ->where('status', 'payment-received')
            ->firstOrFail();

        if (!$order) {
            abort(404, 'Order not found or not completed.');
        }

        return view('checkout.order-complete', compact('order'));
    }

    protected function processStripePayment(Request $request)
    {
        try {
            $validated = $request->validate([
                'order_id' => 'required|exists:lunar_orders,id',
                'amount' => 'required|numeric|min:50',
                'currency' => 'sometimes|string|size:3'
            ]);

            $order = Order::findOrFail($request->order_id);
            $orderAmount = $order->total->value;
            
            if ($request->amount != $orderAmount) {
                throw new \Exception('Payment amount does not match order total');
            }
            
            Stripe::setApiKey(env('STRIPE_SECRET'));
            
            $paymentIntent = PaymentIntent::create([
                'amount' => $orderAmount,
                'currency' => strtolower($order->currency_code),
                'metadata' => [
                    'order_id' => $order->id,
                    'cart_id' => CartSession::current()->id
                ],
                'description' => "Payment for Order #{$order->reference}"
            ]);

            $order->update([
                'status' => 'payment-waiting'
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
            $validated = $request->validate([
                'payment_intent_id' => 'required|string',
                'order_id' => 'required|exists:lunar_orders,id'
            ]);

            $order = Order::findOrFail($request->order_id);
            
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);

            if ($paymentIntent->status !== 'succeeded') {
                throw new \Exception('Payment not completed. Status: ' . $paymentIntent->status);
            }

            $orderAmountInCents = $order->total->value;
            if ($paymentIntent->amount !== $orderAmountInCents) {
                throw new \Exception('Payment amount mismatch');
            }

            $order->update([
                'status' => 'payment-received'
            ]);

            $order->transactions()->create([
                'success' => true,
                'type' => 'capture',
                'driver' => 'stripe',
                'amount' => $paymentIntent->amount,
                'reference' => $order->reference,
                'status' => 'Payment successful',
                'notes' => 'Payment received via Stripe | Payment intent: ' . $paymentIntent->id . ' | ' . $paymentIntent->payment_method_types[0] ?? 'card',
                'card_type' => 'card',
            ]);

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

        $cart = $this->cart->getCart();

        $order = Order::create([
            'user_id' => auth()->id(),
            'status' => 'awaiting-payment',
            'reference' => uniqid(),
            'sub_total' => $cart->subTotal->value,
            'total' => $cart->total->value,
            'notes' => $validated['order_notes'] ?? null,
            'currency_code' => $cart->currency->code,
            'channel_id' => 1,
            'discount_total' => $cart->discountTotal->value,
            'tax_total' => $cart->taxTotal->value,
            'shipping_total' => 0,
            'tax_breakdown' => $cart->taxBreakdown,
            'customer_reference' => Auth::check() ? ('Customer ID: ' . Auth::user()->id) : 'Guest Checkout',
        ]);

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
            'country_id' => 1,
            'contact_email' => $validated['email'],
            'contact_phone' => $validated['phone'],
            'type' => 'shipping',
        ];

        $addressData['type'] = 'billing';
        OrderAddress::create($addressData);

        foreach ($cart->lines as $line) {
            $order->lines()->create([
                'purchasable_type' => $line->purchasable_type,
                'purchasable_id' => $line->purchasable_id,
                'type' => 'physical',
                'description' => 'no',
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

        return response()->json([
            'success' => true,
            'message' => 'Checkout done successfully',
            'order_id' => $order->id,
        ]);
    }
}