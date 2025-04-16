@extends('layouts.app')
@section('content')


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
                    <span class="badge text-bg-light text-decoration-line-through">{{ discounted_item_price($line)->formatted() }}</span>
                @endif
                {{ full_price($line)->formatted() }}
                @if(discount_value($line)->value > 0)
                    <!-- <span class="badge text-bg-warning">-{{ discount_value($line)->formatted() }}</span> -->
                @endif
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
                        <span>Sub Total:</span>
                        <span>{{ $sub_total_discounted }}</span>
                    </div>

                    <div class="d-flex justify-content-between mb-2">
                        <span>VAT 24%:</span>
                        <span>{{ $tax }}</span>
                    </div>


                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5 mb-4">
                        <span>Total:</span>
                        <span>{{ $sub_total }}</span>
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
    
    <!-- Customer Information Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h4 class="mb-0">Customer Information</h4>
                </div>
                <div class="card-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName" class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="firstName" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lastName" class="form-label">Last Name *</label>
                                <input type="text" class="form-control" id="lastName" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number *</label>
                            <input type="tel" class="form-control" id="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Street Address *</label>
                            <input type="text" class="form-control" id="address" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">City *</label>
                                <input type="text" class="form-control" id="city" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="zip" class="form-label">ZIP Code *</label>
                                <input type="text" class="form-control" id="zip" required>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="country" class="form-label">Country *</label>
                                <select class="form-select" id="country" required>
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
                            <textarea class="form-control" id="notes" rows="3"></textarea>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection