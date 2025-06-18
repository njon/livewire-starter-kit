@extends('layouts.app')

@section('content')
    @csrf

    @php
        $products = $cart->lines;
        $sub_total = $cart->subTotal->formatted();
        $total = $cart->total->formatted();
        $total_discount = $cart->discountTotal->formatted();
        $sub_total_discounted = $cart->subTotalDiscounted->formatted();
        $tax = $cart->taxTotal->formatted();
    @endphp

    <div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h3 class="mb-4">Your Shopping Cart</h3>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Cart Items -->
        <div class="col-lg-8 col-md-7">
            <div class="mb-4">
                @include('partials.cart-items')
            </div>
        </div>

        @if($cart->lines->count() !== 0)
        <!-- Right Column - Summary -->
        <div class="col-lg-4 col-md-5">
            <div class="mb-4">
                <div class="">
                    <h5 class="card-title mb-4">Order Summary</h5>

                    <!-- Coupon Code Input -->
                    <div class="mb-4">
                        <label for="couponCode" class="form-label">Have a coupon?</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="couponCode" placeholder="Enter coupon code">
                            <button class="btn btn-outline-secondary" type="button">Apply</button>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="border-top pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span class="fw-bold" id="price-subtotal">{{ $sub_total }}</span>
                        </div>

                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Discount:</span>
                            <span class="fw-bold" id="price-discount">-{{ $total_discount }}</span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>VAT 24%:</span>
                            <span id="price-tax">{{ $tax }}</span>
                        </div>


                        <div class="d-flex justify-content-between mt-3 pt-3 border-top">
                            <span class="h5">Total:</span>
                            <span class="h5 fw-bold" id="price-total">{{ $sub_total_discounted }}</span>
                        </div>
                    </div>

                    <!-- Checkout Button -->
                    <div class="d-grid mt-4">
                        <a href="/checkout" class="btn btn-primary btn-lg py-3">Proceed to Checkout</a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection