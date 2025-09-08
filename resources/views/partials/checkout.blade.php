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
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --success-color: #10b981;
            --border-radius: 12px;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        .checkout-card {
            border-radius: var(--border-radius);
            border: none;
            border: 1px solid #e2e8f0;
            transition: var(--transition);
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-weight: 600;
            padding: 1.25rem 1.5rem;
        }

        .product-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }

        #card-element {
            padding: 1rem;
            border: 1px solid #e2e8f0;
            border-radius: var(--border-radius);
            background: white;
        }

        .btn-checkout {
            background-color: var(--primary-color);
            border: none;
            padding: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: var(--transition);
        }

        .btn-checkout:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
        }

        .sticky-summary {
            position: sticky;
            top: 20px;
            box-shadow: var(--shadow-sm);
        }

        .payment-method {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 0.75rem;
            margin-bottom: 0.75rem;
            transition: var(--transition);
        }

        .payment-method:hover {
            border-color: var(--primary-color);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            border: 1px solid #e2e8f0;
        }

        .total-row {
            font-size: 1.1rem;
        }
    </style>
</head>
<form action="{{ route('paypal.create') }}" method="POST">
    <button type="submit" class="btn btn-paypal btn btn-outline-primary">
        <i class="fab fa-paypal"></i> Pay with PayPal
    </button>
</form>
<div class="container py-5">
    <div class="row g-4">
        <!-- Customer Information -->
        <div class="col-lg-8">
            <div class="checkout-card card mb-4">
                <div class="card-header d-flex align-items-center">
                    <i class="fa fa-user-circle me-2"></i>
                    <h5 class="mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <form action="/checkout" method="POST" id="checkout-form">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="firstName" class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="firstName" name="first_name" required>
                                <div class="invalid-feedback">Please enter your first name</div>
                            </div>
                            <div class="col-md-6">
                                <label for="lastName" class="form-label">Last Name *</label>
                                <input type="text" class="form-control" id="lastName" name="last_name" required>
                                <div class="invalid-feedback">Please enter your last name</div>
                            </div>
                            <div class="col-12">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <div class="invalid-feedback">Please enter a valid email</div>
                            </div>
                            <div class="col-12">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                            </div>
                            <input type="hidden" name="address" value="{{ \Illuminate\Support\Str::random(16) }}">
                            <input type="hidden" name="city" value="{{ \Illuminate\Support\Str::random(10) }}">
                            <input type="hidden" name="zip_code" value="{{ \Illuminate\Support\Str::random(6) }}">
                            <input type="hidden" name="country" value="{{ \Illuminate\Support\Str::random(8) }}">
                            <input type="hidden" name="order_notes" value="{{ \Illuminate\Support\Str::random(20) }}">
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Submit</button>
                    </form>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="checkout-card card mb-4">
                <div class="card-header d-flex align-items-center">
                    <i class="fa fa-shopping-bag me-2"></i>
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50%">Product</th>
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
                                            <img src="{{ $line->purchasable->product->getThumbImage() }}"
                                                class="product-img me-3"
                                                alt="{{ $line->purchasable->product->translateAttribute('name') }}">
                                            <div>
                                                <h6 class="mb-1">
                                                    {{ $line->purchasable->product->translateAttribute('name') }}</h6>
                                                @if($variantName)
                                                <small class="text-muted d-block">{{ $variantName }}</small>
                                                @endif
                                                @if($line->meta && isset($line->meta['variant_options']))
                                                @foreach($line->meta['variant_options'] as $option)
                                                <small class="text-muted d-block">{{ $option['name'] }}:
                                                    {{ $option['value'] }}</small>
                                                @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end align-middle">
                                        <div class="d-flex flex-column">
                                            @if(discount_value($line)->value > 0)
                                            <span
                                                class="text-success fw-bold">{{ discounted_item_price($line)->formatted() }}</span>
                                            <small
                                                class="text-muted text-decoration-line-through">{{ full_price($line)->formatted() }}</small>
                                            @else
                                            {{ full_price($line)->formatted() }}
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        {{ $line->quantity }}
                                    </td>
                                    <td class="text-end align-middle fw-bold">
                                        {{ $line->subTotalDiscounted->formatted() }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Payment Section -->
            <div class="checkout-card card">
                <div class="card-header d-flex align-items-center">
                    <i class="fa fa-credit-card me-2"></i>
                    <h5 class="mb-0">Payment Method</h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="form-check payment-method">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="creditCard" checked>
                            <label class="form-check-label w-100" for="creditCard">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="fa fa-cc-stripe me-2"></i>Credit/Debit Card
                                    </span>
                                    <div>
                                        <i class="fa fa-cc-visa me-1"></i>
                                        <i class="fa fa-cc-mastercard me-1"></i>
                                        <i class="fa fa-cc-amex"></i>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <div id="card-element" class="my-3">
                            <!-- Stripe Elements will be inserted here -->
                        </div>
                        <div id="card-errors" class="text-danger small" role="alert"></div>

                            <div class="form-check payment-method">
                                <input class="form-check-input" type="radio" name="paymentMethod" id="paypal">
                                <label class="form-check-label" for="paypal">
                                    <i class="fa fa-paypal me-2"></i>PayPal
                                </label>
                            </div>


                        <form id="payment-form" action="/checkout/pay" method="POST">
                        <button type="submit" class="btn btn-primary mt-3">Submit</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>



        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="checkout-card card sticky-summary">
                <div class="card-header d-flex align-items-center">
                    <i class="fa fa-receipt me-2"></i>
                    <h5 class="mb-0">Order Total</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>{{ $sub_total }}</span>
                    </div>

                    @if($total_discount > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span>Discount:</span>
                        <span class="text-success">-{{ $total_discount }}</span>
                    </div>
                    @endif

                    <div class="d-flex justify-content-between mb-2">
                        <span>VAT 24%</span>
                        <span>{{ $tax }}</span>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5 mb-4">
                        <span>Total:</span>
                        <span>{{ $total }}</span>
                    </div>

                    <!-- Coupon Code -->
                    <div class="py-3 border-top">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="{{ __('Coupon code') }}">
                            <button class="btn btn-outline-primary" type="button">{{ __('Apply') }}</button>
                        </div>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="termsCheck" required>
                        <label class="form-check-label small" for="termsCheck">
                            I agree to the <a href="#" class="text-primary">Terms and Conditions</a>
                        </label>
                    </div>

                    <button id="submit-button" class="btn btn-checkout w-100 py-3">
                        <span id="button-text">Pay {{ $total }}</span>
                        <span id="button-spinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>

                    <p class="text-muted small mt-3 mb-0">
                        <i class="fa fa-lock me-1"></i> Your payment is secured with 256-bit SSL encryption
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="order_id">
<input type="hidden" id="order_reference">

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
                amount: {{ $cart->total->value }}, // in cents, currency: 'EUR',
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border: none;
        }
        .product-img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
        }
        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        }
        .btn-primary {
            background-color: #0d6efd;
            border: none;
            padding: 10px 16px;
            font-weight: 600;
        }
        .btn-outline-primary {
            border-width: 2px;
            font-weight: 500;
        }
        .delivery-option {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .delivery-option.active {
            border-color: #0d6efd !important;
            background-color: rgba(13, 110, 253, 0.08);
        }
        .payment-method {
            border-radius: 8px;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .payment-method:hover {
            background-color: #f8f9fa;
        }
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
            margin: 20px 0;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #dee2e6;
        }
        .divider::before {
            margin-right: 10px;
        }
        .divider::after {
            margin-left: 10px;
        }
        .step-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            background-color: #0d6efd;
            color: white;
            border-radius: 50%;
            font-size: 12px;
            margin-right: 8px;
        }
    </style>
</head>
<body>
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
                                <div class="delivery-option card p-3 border active" id="emailOption" onclick="selectOption('email')">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="deliveryMethod" id="emailMethod" checked>
                                        <label class="form-check-label fw-bold" for="emailMethod">
                                            Receive by Email
                                        </label>
                                    </div>
                                    <p class="text-muted mb-0 mt-2">Free. We'll send the product to your email address.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="delivery-option card p-3 border" id="deliveryOption" onclick="selectOption('delivery')">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="deliveryMethod" id="deliveryMethod">
                                        <label class="form-check-label fw-bold" for="deliveryMethod">
                                            Physical Delivery
                                        </label>
                                    </div>
                                    <p class="text-muted mb-0 mt-2">We'll ship the product to your address.</p>
                                </div>
                            </div>
                        </div>

                        <div id="emailForm" class="mt-4">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" placeholder="your@email.com">
                            </div>
                        </div>

                        <div id="addressForm" class="mt-4" style="display: none;">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="firstName" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="firstName">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="lastName" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="lastName">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address">
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="city">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="state" class="form-label">State</label>
                                    <select class="form-select" id="state">
                                        <option selected>Choose...</option>
                                        <option>California</option>
                                        <option>New York</option>
                                        <option>Texas</option>
                                    </select>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label for="zip" class="form-label">Zip</label>
                                    <input type="text" class="form-control" id="zip">
                                </div>
                            </div>
                        </div>
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
                                        <input class="form-check-input" type="radio" name="paymentMethod" id="creditCard" checked>
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
                        <div class="d-flex align-items-center mb-3">
                            <img src="https://via.placeholder.com/70" alt="Product" class="product-img">
                            <div class="ms-3">
                                <h6 class="mb-0">Wireless Headphones</h6>
                                <small class="text-muted">Quantity: 1</small>
                            </div>
                            <div class="ms-auto">$129.99</div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <img src="https://via.placeholder.com/70" alt="Product" class="product-img">
                            <div class="ms-3">
                                <h6 class="mb-0">Phone Case</h6>
                                <small class="text-muted">Quantity: 2</small>
                            </div>
                            <div class="ms-auto">$35.98</div>
                        </div>

                        <div class="divider">or</div>

                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Coupon code">
                            <button class="btn btn-outline-primary" type="button">Apply</button>
                        </div>

                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Subtotal</span>
                            <span>$165.97</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Discount</span>
                            <span class="text-success">-$10.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">VAT (20%)</span>
                            <span>$31.19</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-muted">Shipping</span>
                            <span class="text-success">Free</span>
                        </div>

                        <div class="d-flex justify-content-between mt-3 mb-2">
                            <h5>Total</h5>
                            <h5>$187.16</h5>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="termsCheck">
                            <label class="form-check-label" for="termsCheck">
                                I agree to the <a href="#">Terms and Conditions</a>
                            </label>
                        </div>

                        <button class="btn btn-primary w-100 py-2" id="checkoutButton" disabled>Complete Purchase</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
</body>
</html>

@endsection