@if($cart->lines->count() === 0)
    <div class="text-center py-4">
        <span class="material-symbols-outlined" style="font-size: 2rem;">shopping_cart</span>
        <p class="mt-2 mb-0">{{ __('Your cart is empty') }}</p>
    </div>
@else
<div>
    <div class="container">
    <div class="cart-items">
        @foreach($cart->lines as $line)
            @php
                $product = $line->purchasable->product;
                $variant = $line->purchasable;
                $thumbnail = $product->getThumbImage();
                $productName = $product->translateAttribute('name');
                $unitPrice = $line->price ? $line->price->formatted() : 'No price';
                $totalPrice = $line->total->formatted();
                $variantName = $line->meta->variant_name ?? null;
            @endphp

            <div class="list-group-item py-3 cart-item border-bottom" data-line-id="{{ $line->id }}">
                <div class="row align-items-center g-2">
                    <!-- Column 1: Image -->
                    <div class="col-3">
                        <img src="{{ $thumbnail }}" 
                             alt="{{ $productName }}" 
                             class="img-fluid rounded-2" 
                             style="width: 100%; aspect-ratio: 1/1; object-fit: cover;">
                    </div>
                    
                    <!-- Column 2: Title, Price & Info -->
                    <div class="col-4">
                        <h6 class="mb-1 fw-bold">
                            {{ $productName }}
                            @if($variantName)
                                <small class="text-muted d-block">• {{ $variantName }}</small>
                            @endif
                        </h6>
                        <div class="text-muted small">
                            {{ $unitPrice }} {{ __('each') }}
                        </div>
                        <div class="mt-1">
                            <span class="fw-bold item-total">{{ $totalPrice }}</span>
                        </div>
                    </div>

                    <!-- Column 3: Quantity Controls -->
                    <div class="col-3">
                        <div class="d-flex align-items-center">
                            <button class="btn btn-sm btn-outline-secondary quantity-change" 
                                    data-action="decrease" 
                                    data-line-id="{{ $line->id }}">
                                <span class="material-symbols-outlined" style="font-size: 1rem;">remove</span>
                            </button>
                            <div class="quantity-control">
                                <input type="number" 
                                    class="quantity-input" 
                                    value="{{ $line->quantity }}" 
                                    min="1" 
                                    data-id="{{ $line->purchasable_id }}" 
                                    data-previous-value="1"
                                    style="width: 50px; text-align: center;">
                            </div>
                            <button class="btn btn-sm btn-outline-secondary quantity-change" 
                                    data-action="increase" 
                                    data-line-id="{{ $line->id }}">
                                <span class="material-symbols-outlined" style="font-size: 1rem;">add</span>
                            </button>
                        </div>
                    </div>
              
                    <!-- Column 4: Remove Button -->
                    <div class="col-2 text-end">
                        <button class="btn btn-sm btn-remove text-danger" 
                                data-line-id="{{ $line->id }}"
                                data-variant-id="{{ $variant->id }}">
                            <span class="material-symbols-outlined" style="font-size: 1.2rem;">delete</span>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
</div>
@endif