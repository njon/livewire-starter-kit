@php
$colClass = (isset($col) && is_numeric($col)) ? 'col-lg-' . $col : 'col';
@endphp

<div class="mb-5 mt-0 {{ $colClass }} product  px-2">
    <div class="product-item-list">
        <!-- Product Image with Hover Effects -->
        <div class="position-relative overflow-hidden product-image-container">
            <div class="btn-wishlist position-absolute top-0 end-0 m-2">
                <button class="button-animated like wishlist-add p-2 py-1" data-product-id="{{ $product->id }}">
                    <i class="fa fa-heart"></i>
                </button>
            </div>
            @if($product->average_rating != 0)
            <!-- <div class="rating fs-15 m-3 px-2">
                <span class="small">{{ $product->average_rating }}</span> {{ $product->rating_stars }}
            </div> -->
            @endif
            @if($product->thumbnail)

            <!-- New Badge -->
            <!-- <span class="position-absolute start-0 bottom-0 m-2 badge bg-primary">
                New
            </span> -->

            <!-- Product Image -->
            <div class="img-holder overflow-hidden rounded-2">
                <img src="{{ $product->thumbnail->getUrl() }}" class="product-image object-fit-cover" alt="{{ $product->translateAttribute('name') }}">
            </div>

            <!-- View Product Link -->
            <a href="{{ $product->defaultUrl->slug }}" class="stretched-link"></a>
            @endif
        </div>

        <!-- Card Body -->
        <div class="d-flex flex-column mt-4">
            <!-- Product Title -->
            <div class="d-flex flex-row justify-content-between align-items-baseline">
                <div>
                    <h3 class="card-title">
                        <a href="{{ $product->defaultUrl->slug }}" class="text-decoration-none text-dark">
                            {{ $product->translateAttribute('name') }}
                        </a>
                    </h3>
                </div>
                <div class="w-25 text-end">
                    4.7 <i class="fa fa-star" aria-hidden="true"></i>

  
                </div>
            </div>

            <!-- Product Meta -->
            <!-- <div class="d-flex flex-row text-muted fs-13 gap-2 mt-3">
                <div class="attribute">
                    <span class="material-symbols-outlined product-icon">person</span>
                    2{{ $product->translateAttribute('participants') }} participants
                </div>
                <div class="attribute">
                    <span class="material-symbols-outlined product-icon">schedule</span>
                    45{{ $product->translateAttribute('length') }} mins
                </div>
            </div> 

           <div class="product-city text-muted fs-13 mb-3">
                <i class="fa fa-map-marker"></i> {{ $product->translateAttribute('city') ?? 'Athens, Thessaloniki' }}
            </div> -->

            <!-- Price -->
            <div class="d-flex flex-row justify-content-between align-items-baseline mt-3">
                <div class="product-price">
                    @if($product->variants->count() > 1)
                        <span class="from-text">
                            {{ __('From') }}
                        </span>
                    @else
                        <span class="from-text">
                            {{ __('Price') }}
                        </span>
                    @endif

                    <span class="sale-price">
                        {{ $product->price }}
                    </span>

                    @if($product->has_discount)
                        <span  class="small text-decoration-line-through text-shallow">{{ $product->price_without_discount }}</span>
                    @endif

                    <!-- Discount Badge -->
                    @if($product->has_discount)
                        <span class="badge bg-success fw-normal text-white ms-2 fs-13">-{{ $product->discount_percentage }}%</span>
                    @endif
                </div>
                <div class="text-end">
                    <a href="{{ $product->defaultUrl->slug }}" class="text-decoration-none text-dark fs-14">
                        <span class="material-symbols-outlined"> shopping_cart </span> <span class="add-tc">{{ __('Add to cart') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>