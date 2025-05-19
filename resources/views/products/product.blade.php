<div class="col-6 mb-4 col-lg-3 product">
    <div class="card h-100 shadow-sm border-0">
        <!-- Product Image with Hover Effects -->
        <div class="position-relative overflow-hidden">
            <!-- Wishlist Button -->
            <span class="btn-wishlist"> 
                <span class="wishlist-add material-symbols-outlined product-fav-icon hoverable-icon" data-product-id="{{ $product->id }}">favorite</span>
            </span>
            
            @if($product->thumbnail)
                <!-- Discount Badge -->
                @if($product->has_discount)
                    <span class="position-absolute top-0 start-0 m-2 badge bg-success">
                        -{{ $product->discount_percentage }}%
                    </span>
                @endif
                
                <!-- New Badge -->
                @if($product->isNew)
                    <span class="position-absolute start-0 bottom-0 m-2 badge bg-primary">
                        New
                    </span>
                @endif
                
                <!-- Product Image -->
                <img src="{{ $product->thumbnail->getUrl() }}" 
                     class="card-img-top object-fit-cover" 
                     alt="{{ $product->translateAttribute('name') }}"
                     style="height: 200px;">
                
                <!-- View Product Link -->
                <a href="{{ $product->defaultUrl->slug }}" class="stretched-link"></a>
            @endif
        </div>
        
        <!-- Card Body -->
        <div class="card-body d-flex flex-column">
            <!-- Product Title -->
            <h3 class="card-title fs-6 mb-2 mt-2">
                <a href="{{ $product->defaultUrl->slug }}" class="text-decoration-none text-dark">
                    {{ $product->translateAttribute('name') }}
                </a>
            </h3>
            
            <!-- Location -->
            <div class="product-city text-muted small mb-3">
                {{ $product->translateAttribute('city') ?? 'Athens, Thessaloniki' }}
            </div>
            

            <!-- Product Meta -->
            <div class="text-muted small mb-3 mt-1">
                <div class="attribute">
                    <span class="material-symbols-outlined product-icon">person</span>
                    2{{ $product->translateAttribute('participants') }} participants
                </div>
                <div class="attribute">
                    <span class="material-symbols-outlined product-icon">schedule</span>
                    45{{ $product->translateAttribute('length') }} mins
                </div>
            </div>
            
            <!-- Price -->
            <div class="mt-auto">
                <div class="product-price fw-bold fs-5 {{ $product->has_discount ? 'text-success' : 'text-dark' }}">
                    {{ $product->price }}
                    @if($product->has_discount)
                        <span class="text-muted small text-decoration-line-through text-small">
                            {{ $product->price_without_discount }}
                        </span>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .object-fit-cover {
        object-fit: cover;
        width: 100%;
    }
    .btn-wishlist {
        transition: all 0.2s;
        z-index: 2;
    }
    .btn-wishlist:hover {
        transform: scale(1.1);
    }
    .card {
        transition: transform 0.3s;
    }
    .card:hover {
        transform: translateY(-5px);
    }
    .product-fav-icon.active {
        font-variation-settings: 'FILL' 1;
    }
</style>
@endpush