@extends('layouts.app')

@section('content')
    @csrf

    <div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h1 class="mb-4">Your Shopping Cart</h1>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - Cart Items -->
        <div class="col-lg-8 col-md-7">
            <div class="mb-4">
                <div class="cart"></div>
            </div>
        </div>

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
                            <span class="fw-bold">$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span class="fw-bold">Free</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Discount:</span>
                            <span class="fw-bold">-$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mt-3 pt-3 border-top">
                            <span class="h5">Total:</span>
                            <span class="h5 fw-bold">$0.00</span>
                        </div>
                    </div>

                    <!-- Checkout Button -->
                    <div class="d-grid mt-4">
                        <button class="btn btn-primary btn-lg py-3">Proceed to Checkout</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection