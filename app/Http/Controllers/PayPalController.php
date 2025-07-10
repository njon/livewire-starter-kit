<?php

namespace App\Http\Controllers;

use App\Services\PayPalCheckoutService;
use Lunar\Facades\CartSession;
use Lunar\Facades\Orders;

class PayPalController extends Controller
{
    public $paypalService;
    public $provider;

    public function __construct(PayPalCheckoutService $paypalService)
    {
        $this->paypalService = $paypalService;
    }

    public function create()
    {
        try {
            $response = $this->paypalService->createOrder();
            $orderId = $response['id'];
            
            // Store order ID in session temporarily
            session()->put('paypal_order_id', $orderId);
            
            // Get approval URL
            $links = collect($response['links']);
            $approveUrl = $links->where('rel', 'approve')->first()['href'];
            
            return redirect()->away($approveUrl);
            
        } catch (\Exception $e) {
            return redirect()->route('checkout')->with('error', $e->getMessage());
        }
    }

    public function success()
    {
        try {
            $orderId = session()->get('paypal_order_id');
            $response = $this->paypalService->captureOrder($orderId);
            
            // Create Lunar order
            $cart = CartSession::current();
            $order = Orders::create($cart);
            
            // Store PayPal transaction details
            $order->update([
                'meta' => [
                    'paypal' => [
                        'order_id' => $orderId,
                        'status' => $response['status'],
                        'capture_id' => $response['purchase_units'][0]['payments']['captures'][0]['id'],
                    ]
                ]
            ]);
            
            // Clear cart
            CartSession::forget();
            
            return redirect()->route('checkout.success')->with('success', 'Payment successful!');
            
        } catch (\Exception $e) {
            return redirect()->route('checkout')->with('error', $e->getMessage());
        }
    }

    public function cancel()
    {
        return redirect()->route('checkout')->with('error', 'Payment was cancelled');
    }
}