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

<script src="https://js.stripe.com/v3/"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
<link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
<script>
    // Stripe initialization and payment handling
    const stripe = Stripe("{{ env('STRIPE_KEY') }}");
    const elements = stripe.elements();
    const cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#32325d',
                fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
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
        event.preventDefault();
        const submitButton = document.getElementById('submit-button');
        const buttonText = document.getElementById('button-text');
        const spinner = document.getElementById('button-spinner');
        const order_id = document.getElementById('order_id').value;
        const order_reference = document.getElementById('order_reference').value;
        submitButton.disabled = true;
        buttonText.textContent = 'Processing...';
        spinner.classList.remove('d-none');
        try {
            // 1. Create payment intent
            const response = await fetch('/checkout/create-payment-intent', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    amount: {
                        {
                            $cart - > total - > value
                        }
                    }, // in cents, currency: 'EUR',
                    currency: 'EUR',
                    order_id: order_id
                })
            });
            if (!response.ok) throw new Error('Failed to create payment intent');
            const {
                clientSecret
            } = await response.json();
            // 2. Confirm payment
            const {
                error,
                paymentIntent
            } = await stripe.confirmCardPayment(clientSecret, {
                payment_method: {
                    card: cardElement
                }
            });
            if (error) throw error;
            // 3. Complete order
            const completeResponse = await fetch('/checkout/complete-order', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    payment_intent_id: paymentIntent.id,
                    order_id: order_id
                })
            });
            if (!completeResponse.ok) throw new Error('Order completion failed');
            window.location.href = '/checkout/success/' + order_reference;
        } catch (error) {
            console.error('Payment error:', error);
            document.getElementById('card-errors').textContent = error.message ||
                'Payment failed. Please try again.';
            submitButton.disabled = false;
            buttonText.textContent = 'Pay {{ $total }}';
            spinner.classList.add('d-none');
        }
    });
</script>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><span class="step-number">1</span>Delivery Method</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="delivery-option card p-3 border active" id="emailOption"
                                onclick="selectOption('email')">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="deliveryMethod" id="emailMethod"
                                        checked>
                                    <label class="form-check-label fw-bold" for="emailMethod">
                                        Receive by Email
                                    </label>
                                </div>
                                <p class="text-muted mb-0 mt-2">Free. We'll send the product to your email address.
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="delivery-option card p-3 border" id="deliveryOption"
                                onclick="selectOption('delivery')">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="deliveryMethod"
                                        id="deliveryMethod">
                                    <label class="form-check-label fw-bold" for="deliveryMethod">
                                        Physical Delivery
                                    </label>
                                </div>
                                <p class="text-muted mb-0 mt-2">We'll ship the product to your address.</p>
                            </div>
                        </div>
                    </div>
                    <form action="/checkout" method="POST" id="checkout-form">

                        <div id="emailForm" class="mt-4">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" placeholder="your@email.com"
                                    name="email">
                            </div>
                        </div>

                        <div id="addressForm" class="mt-4" style="display: none;">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email_physical"
                                        placeholder="your@email.com" name="email_physical">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="firstName" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="firstName" name="first_name">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="lastName" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="lastName" name="last_name">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="address" name="address">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="city" name="city">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="zip" class="form-label">Zip</label>
                                    <input type="text" class="form-control" id="zip" name="zip_code">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="order_notes" class="form-label">Order Notes</label>
                                    <textarea class="form-control" id="order_notes" name="order_notes" rows="3"
                                        placeholder="Add any notes for your order"></textarea>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><span class="step-number">2</span>Payment Method</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="payment-method card p-3 border text-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="creditCard"
                                        checked>
                                    <label class="form-check-label" for="creditCard">
                                        <i class="bi bi-credit-card fs-4 d-block mb-2"></i>
                                        Credit Card
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="payment-method card p-3 border text-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="paypal">
                                    <label class="form-check-label" for="paypal">
                                        <i class="bi bi-paypal fs-4 d-block mb-2"></i>
                                        PayPal
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="payment-method card p-3 border text-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="applePay">
                                    <label class="form-check-label" for="applePay">
                                        <i class="bi bi-apple fs-4 d-block mb-2"></i>
                                        Apple Pay
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    @foreach($cart->lines as $line)
                    @php $variantName = $line->meta->variant_name ?? null; @endphp
                    <div class="d-flex align-items-center cart-item">
                        <img src="{{ $line->purchasable->product->getThumbImage() }}"
                            alt="{{ $line->purchasable->product->translateAttribute('name') }}" class="product-img">
                        <div class="ms-3 flex-grow-1">
                            <h6 class="mb-1">{{ $line->purchasable->product->translateAttribute('name') }}</h6>

                            @if($variantName)
                            <small class="text-muted d-block">{{ $variantName }}</small>
                            @endif

                            @if($line->meta && isset($line->meta['variant_options']))
                            @foreach($line->meta['variant_options'] as $option)
                            <small class="text-muted d-block">{{ $option['name'] }}: {{ $option['value'] }}</small>
                            @endforeach
                            @endif

                            <small class="text-muted">Quantity: {{ $line->quantity }}</small>
                        </div>
                        <div class="ms-auto price-container">
                            @if(discount_value($line)->value > 0)
                            <span
                                class="text-success fw-bold d-block">{{ discounted_item_price($line)->formatted() }}</span>
                            <small
                                class="text-muted text-decoration-line-through">{{ full_price($line)->formatted() }}</small>
                            @else
                            <span class="fw-bold">{{ full_price($line)->formatted() }}</span>
                            @endif
                        </div>
                    </div>
                    @endforeach

                    <div class="divider">or</div>

                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Coupon code">
                        <button class="btn btn-outline-primary" type="button">Apply</button>
                    </div>

                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Subtotal</span>
                        <span>{{ $sub_total }}</span>
                    </div>

                    @if($total_discount > 0)
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Discount</span>
                        <span class="text-success">-{{ $total_discount }}</span>
                    </div>
                    @endif
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">VAT (20%)</span>
                        <span>{{ $tax }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Shipping</span>
                        <span class="text-success">Free</span>
                    </div>

                    <div class="d-flex justify-content-between mt-3 mb-2">
                        <h5>Total</h5>
                        <h5>{{ $total }}</h5>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="termsCheck">
                        <label class="form-check-label" for="termsCheck">
                            I agree to the <a href="#">Terms and Conditions</a>
                        </label>
                    </div>

                    <button class="btn btn-primary w-100 py-2" id="checkoutButton" disabled>
                        Complete Purchase
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('paypal.create') }}" method="POST">
    <button type="submit" class="btn btn-paypal btn btn-outline-primary">
        <i class="fab fa-paypal"></i> Pay with PayPal
    </button>
</form>

<script>
    function selectOption(option) {
        if (option === 'email') {
            document.getElementById('emailOption').classList.add('active');
            document.getElementById('deliveryOption').classList.remove('active');
            document.getElementById('emailMethod').checked = true;
            document.getElementById('emailForm').style.display = 'block';
            document.getElementById('addressForm').style.display = 'none';
        } else {
            document.getElementById('deliveryOption').classList.add('active');
            document.getElementById('emailOption').classList.remove('active');
            document.getElementById('deliveryMethod').checked = true;
            document.getElementById('emailForm').style.display = 'none';
            document.getElementById('addressForm').style.display = 'block';
        }
    }
    // Enable checkout button only when terms are accepted
    document.getElementById('termsCheck').addEventListener('change', function() {
        document.getElementById('checkoutButton').disabled = !this.checked;
    });
    // Add interaction to payment methods
    document.querySelectorAll('.payment-method').forEach(method => {
        method.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
            document.querySelectorAll('.payment-method').forEach(m => {
                m.style.borderColor = '#dee2e6';
            });
            this.style.borderColor = '#0d6efd';
        });
    });
</script>
<input type="hidden" id="order_id">
<input type="hidden" id="order_reference">

@endsection