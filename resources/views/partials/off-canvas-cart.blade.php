@if($cart->lines->count() === 0)
    <div class="text-center py-4">
        <span class="material-symbols-outlined" style="font-size: 2rem;">shopping_cart</span>
        <p class="mt-2 mb-0">Your cart is empty</p>
    </div>
@else
<div class="p-0">
    <div class="container">
    <div class="cart-items">
        @foreach($cart->lines as $line)
            @php
                $product = $line->purchasable->product;
                $variant = $line->purchasable;
                $thumbnail = $product->thumbnail?->getUrl('small') ?? '/placeholder-product.jpg';
                $productName = $product->translateAttribute('name');
                $unitPrice = $line->price ? $line->price->formatted() : 'No price';
                $totalPrice = $line->total->formatted();
                $productUrl = $product->urls->first()?->slug ?? '#';
                $variantName = $line->meta->variant_name ?? null;
            @endphp

            <div class="list-group-item py-3 cart-item border-bottom" data-line-id="{{ $line->id }}">
                <div class="row align-items-center g-3">
                    <!-- Larger Image -->
                    <div class="col-4 col-md-4">
                        <a href="{{ $productUrl }}" class="d-block">
                            <img src="{{ $thumbnail }}" 
                                 alt="{{ $productName }}" 
                                 class="img-fluid rounded-2" 
                                 style="width: 100%; aspect-ratio: 1/1; object-fit: cover;">
                        </a>
                    </div>
                    
                    <!-- Product Info -->
                    <div class="col-5 col-md-5">
                        <h6 class="mb-1 fw-bold">
                            {{ $productName }}
                            @if($variantName)
                                <small class="text-muted">• {{ $variantName }}</small>
                            @endif
                        </h6>
                        <div class="text-muted small">
                            {{ $line->quantity }} × {{ $unitPrice }}
                        </div>
                        <div class="mt-1">
                            <span class="fw-bold">Total: {{ $totalPrice }}</span>
                        </div>
                    </div>
              
                    <!-- Remove Button -->
                    <div class="col-12 col-md-2">
                        <button class="btn btn-sm btn-remove" 
                                data-line-id="{{ $line->id }}"
                                data-variant-id="{{ $variant->id }}">
                            <span class="material-symbols-outlined" style="font-size: 1.2rem;">close</span>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
</div>

<!-- Cart Summary -->
<div class="card-footer border-top bg-white p-3 sticky-bottom">
    <div class="d-flex justify-content-between mb-3">
        <span class="fw-bold">Subtotal:</span>
        <span class="fw-bold">{{ $cart->subTotal->formatted() }}</span>
    </div>
    <div class="d-grid gap-2">
        <a href="/checkout" class="btn btn-dark btn-lg rounded-pill fw-bold">
            Checkout
        </a>
        <button class="btn btn-outline-dark btn-lg rounded-pill fw-bold" 
                data-bs-dismiss="offcanvas">
            Continue Shopping
        </button>
    </div>
</div>
@endif