@extends('layouts.app')
@section('content')

@php
    $products = $cart->lines;
    $sub_total = $cart->subTotal->formatted();
    $total = $cart->total->formatted();
    $total_discount = $cart->discountTotal->formatted();
    $sub_total_discounted = $cart->subTotalDiscounted->formatted();
    $tax = $cart->taxTotal->formatted();
@endphp
<head>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<script>
const stripe = Stripe("pk_test_51ESlBjBdhnqCifPCEil0H2uW4cXFA0yHJ0oltIX62ILlfoJlpe7YaoZvghInuHotIYGOWB7pqhyKylJbyJa0EBqg00bQNx2Rme");

initialize();

// Create a Checkout Session
async function initialize() {
  const fetchClientSecret = async () => {
    const response = await fetch("/xxx", {
      method: "GET",
    });
    const { clientSecret } = await response.json();
    return clientSecret;
  };

  const checkout = await stripe.initEmbeddedCheckout({
    fetchClientSecret,
  });

  // Mount Checkout
  checkout.mount('#checkout');
}



document.addEventListener('DOMContentLoaded', function() {
    const stripe = Stripe('{{ env("STRIPE_KEY") }}');
    const elements = stripe.elements();
    const cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#32325d',
            }
        }
    });
    
    cardElement.mount('#card-element');
    
    // Handle real-time validation errors
    cardElement.on('change', function(event) {
        const displayError = document.getElementById('card-errors');
        displayError.textContent = event.error ? event.error.message : '';
    });
    
    // Handle form submission
    const form = document.getElementById('payment-form');
    form.addEventListener('submit', async function(event) {

        const orderId = document.getElementById('order_id').value;
        event.preventDefault();
        
        const submitButton = document.getElementById('submit-button');
        submitButton.disabled = true;
        submitButton.textContent = 'Processing...';
        
        try {
            // 1. Create payment intent
            const response = await fetch('/create-payment-intent', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ 
                    amount: Math.round({{ $cart->total->value }}), // in cents
                    currency: 'EUR',
                    order_id: orderId
                })
            });
            
            if (!response.ok) {
                throw new Error('Failed to create payment intent');
            }
            
            const { clientSecret } = await response.json();
            
            // 2. Confirm payment
            const { error, paymentIntent } = await stripe.confirmCardPayment(clientSecret, {
                payment_method: { card: cardElement }
            });
            
            if (error) {
                throw error;
            }
            
            // 3. Complete order
            const completeResponse = await fetch('/complete-order', {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ 
                    payment_intent_id: paymentIntent.id,
                    order_id: orderId
                })
            });
            
            if (!completeResponse.ok) {
                throw new Error('Order completion failed');
            }
            
            window.location.href = '/checkout/success/' + orderId; // Redirect to success page
            
        } catch (error) {
            console.error('Payment error:', error);
            document.getElementById('card-errors').textContent = 
                error.message || 'Payment failed. Please try again.';
            submitButton.disabled = false;
            submitButton.textContent = 'Pay Now';
        }
    });
});
</script>

      <div id="checkout">
        <!-- Checkout will insert the payment form here -->
      </div>





<div id="stripe-payment">
    <form id="payment-form">
        <div id="card-element" class="my-4 p-3 border rounded">
            <!-- Stripe Elements will be inserted here -->
        </div>
        <div id="card-errors" class="text-red-500" role="alert"></div>
        <input type="text" name="order_id" id="order_id" value="no"/>
        
        <button id="submit-button" class="btn btn-primary mt-4">
            Pay {{ $total }}
        </button>
    </form>
</div>
<div class="container py-5">
    <div class="row">
        <!-- Order Summary Column -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h4 class="mb-0">Order Summary</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                    <table class="table">
    <thead>
        <tr>
            <th style="width: 60%">Product</th>
            <th class="text-end">Price</th>
            <th class="text-center">Qty</th>
            <th class="text-end">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($cart->lines as $line)
        @php $variantName = $line->meta->variant_name ?? null; @endphp

        <tr>
            <td>
                <div class="d-flex align-items-center">
                    @if($line->purchasable->product->thumbnail)
                        <img src="{{ $line->purchasable->product->thumbnail->getUrl('small') }}" 
                             alt="{{ $line->purchasable->product->translateAttribute('name') }}"
                             class="img-thumbnail me-3" width="80">
                    @else
                        <img src="https://via.placeholder.com/80" alt="Product" class="img-thumbnail me-3" width="80">
                    @endif
                    <div>
                        <h6 class="mb-1">{{ $line->purchasable->product->translateAttribute('name') }}</h6>
                        {{ $variantName }}
                        
                        @if($line->purchasable->variant)
                            <small class="text-muted">
                                {{ $line->purchasable->variant->name }}
                            </small>
                        @endif
                                        

                        @if($line->meta && isset($line->meta['variant_options']))
                            @foreach($line->meta['variant_options'] as $option)
                                <small class="text-muted d-block">
                                    {{ $option['name'] }}: {{ $option['value'] }}
                                </small>
                            @endforeach
                        @endif
                    </div>
                </div>
            </td>
            <td class="text-end align-middle">
                @if(discount_value($line)->value > 0)
                    <span class="badge text-bg-warning">-{{ discount_value($line)->formatted() }}</span>
                @endif
                @if(discount_value($line)->value > 0)
                    <span class="badge text-bg-light text-decoration-line-through">{{ full_price($line)->formatted() }}</span>
                @endif
                {{ discounted_item_price($line)->formatted() }}
            </td>
            <td class="text-center align-middle">
                {{ $line->quantity }}
            </td>
            <td class="text-end align-middle">
                {{ $line->subTotalDiscounted->formatted() }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
                    </div>
                    
                    <!-- Coupon Code -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Coupon code">
                                <button class="btn btn-outline-secondary" type="button">Apply</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Checkout Summary Column -->
        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header bg-light">
                    <h4 class="mb-0">Order Total</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>{{ $total }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Total:</span>
                        <span>{{ $sub_total }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>Discount:</span>
                        <span>{{ $total_discount }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>VAT 24%:</span>
                        <span>{{ $tax }}</span>
                    </div>


                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5 mb-4">
                        <span>Total:</span>
                        <span>{{ $total }}</span>
                    </div>
                    
                    <!-- Payment Methods -->
                    <div class="mb-4">
                        <h5 class="h6 mb-3">Payment Method</h5>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="creditCard" checked>
                            <label class="form-check-label" for="creditCard">
                                <i class="fab fa-cc-visa me-2"></i>Credit Card
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="paypal">
                            <label class="form-check-label" for="paypal">
                                <i class="fab fa-paypal me-2"></i>PayPal
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="bankTransfer">
                            <label class="form-check-label" for="bankTransfer">
                                <i class="fas fa-university me-2"></i>Bank Transfer
                            </label>
                        </div>
                    </div>
                    
                    <!-- Terms and Checkout Button -->
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="termsCheck" required>
                        <label class="form-check-label small" for="termsCheck">
                            I agree to the <a href="#">Terms and Conditions</a>
                        </label>
                    </div>
                    <button class="btn btn-primary w-100 py-2" type="submit">Complete Order</button>
                </div>
            </div>
        </div>
    </div>
<div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h4 class="mb-0">Customer Information</h4>
                </div>
                <div class="card-body">
                <form action="/checkout" method="POST" id="checkout-form">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="firstName" class="form-label">First Name *</label>
                            <input type="text" class="form-control" id="firstName" name="first_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lastName" class="form-label">Last Name *</label>
                            <input type="text" class="form-control" id="lastName" name="last_name" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone Number *</label>
                        <input type="tel" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Street Address *</label>
                        <input type="text" class="form-control" id="address" name="address" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">City *</label>
                            <input type="text" class="form-control" id="city" name="city" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="zip" class="form-label">ZIP Code *</label>
                            <input type="text" class="form-control" id="zip" name="zip_code" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="country" class="form-label">Country *</label>
                            <select class="form-select" id="country" name="country" required>
                                <option value="">Select...</option>
                                <option value="US">United States</option>
                                <option value="UK">United Kingdom</option>
                                <option value="CA">Canada</option>
                                <option value="AU">Australia</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Order Notes (Optional)</label>
                        <textarea class="form-control" id="notes" name="order_notes" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Place Order</button>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection