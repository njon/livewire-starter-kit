<?php

namespace App\Services;

use Lunar\Facades\CartSession;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalCheckoutService
{
    protected $provider;

    public function __construct()
    {
        $this->provider = new PayPalClient;
        $this->provider->setApiCredentials(config('paypal'));
        $this->provider->getAccessToken();
    }
    public function getProvider()
{
    return $this->provider;
}

    protected function getCartItems($cart)
{
    return $cart->lines->map(function ($line) use ($cart) {
        return [
            'name' => $line->purchasable->product->translateAttribute('name'),
            'quantity' => $line->quantity,
            'unit_amount' => [
                'currency_code' => strtoupper($cart->currency->code),
                'value' => number_format($line->unitPrice->decimal(2), 2),
            ],
        ];
    })->toArray();
}

public function createOrder()
{
    $cart = CartSession::current();
    
    $itemTotal = $cart->subTotal->decimal(2);
    $taxTotal = $cart->taxTotal->decimal(2);
    $shippingTotal = $cart->shippingTotal ? $cart->shippingTotal->decimal(2) : '0.00';
    $discountTotal = $cart->discountTotal ? $cart->discountTotal->decimal(2) : '0.00';
    
    $orderData = [
        'intent' => 'CAPTURE',
        'purchase_units' => [
            [
                'reference_id' => 'cart_'.$cart->id,
                'amount' => [
                    'currency_code' => strtoupper($cart->currency->code),
                    'value' => number_format($cart->total->decimal(2), 2), // Must match sum below
                    'breakdown' => [
                        'item_total' => [
                            'currency_code' => strtoupper($cart->currency->code),
                            'value' => $itemTotal,
                        ],
                        'shipping' => [
                            'currency_code' => strtoupper($cart->currency->code),
                            'value' => $shippingTotal,
                        ],
                        'tax_total' => [
                            'currency_code' => strtoupper($cart->currency->code),
                            'value' => 0,
                        ],
                        'discount' => [
                            'currency_code' => strtoupper($cart->currency->code),
                            'value' => $discountTotal,
                        ]
                    ]
                ],
                'items' => $this->getCartItems($cart)
            ]
        ],
        // ... rest of your config
    ];

    // Debug the amounts before sending
    \Log::debug('PayPal Order Amounts Verification', [
        'calculated_total' => number_format(
            $itemTotal + $shippingTotal - $discountTotal,
            2
        ),
        'cart_total' => $cart->total->decimal(2)
    ]);

    return $this->provider->createOrder($orderData);
}

    public function getOrderDetails($orderId)
    {
        return $this->provider->showOrderDetails($orderId);
    }

    public function captureOrder($orderId)
    {
        $response = $this->provider->capturePaymentOrder($orderId);
        
        if (isset($response['status']) && $response['status'] === 'COMPLETED') {
            return $response;
        }

        throw new \Exception('Failed to capture PayPal order: '.json_encode($response));
    }
}