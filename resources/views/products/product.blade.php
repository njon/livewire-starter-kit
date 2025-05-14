<div class="col-6  mb-4 col-lg-3">
    <div class="card">
        <div class="bg-image hover-zoom ripple ripple-surface ripple-surface-light" data-mdb-ripple-color="light">
            @if($product->thumbnail)
                @if ($product->hasDiscount)
                    <span class="discount-percentage badge text-bg-success">-{{ $product->discount_percentage }}%</span>
                @endif
                <img src="{{ $product->thumbnail->getUrl() }}" class="w-100" alt="{{ $product->translateAttribute('name') }}">
            @endif
            <a href="{{ $product->defaultUrl->slug }}">
                <div class="mask">
                    <div class="d-flex justify-content-start align-items-end h-100">
                        @if($product->isNew)
                            <h5><span class="badge bg-primary ms-2">New</span></h5>
                        @endif
                    </div>
                </div>
                <div class="hover-overlay">
                    <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
                </div>
            </a>
        </div>
        <div class="card-body">
            <h3 class="card-title mb-3">
                <a href="{{ $product->defaultUrl->slug }}">
                    {{ $product->translateAttribute('name') }}
                </a>
            </h3>
            <div class="product-city">
                @if($product->translateAttribute('city'))
                    {{ $product->translateAttribute('city') }}
                @else   
                    Athens, Thessaloniki
                @endif
            </div>

            <div class="bottom-info mb-3">
                <span class="material-symbols-outlined product-icon">person</span> {{ $product->translateAttribute('participants') }} participants
                <span class="material-symbols-outlined product-icon">schedule</span> {{ $product->translateAttribute('length') }} minutes
            </div>

            <div class="product-listing-price">
                <span class="product-price-original">{{ $product->price }}</span>
                @if ($product->hasDiscount)
                        <span class="original-price">{{ $product->price_without_discount }}</span>
                @endif
            </div>
        </div>
    </div>
</div>