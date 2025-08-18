<div class="mb-5 product px-2 {{ isset($col) ? 'col-lg-' . $col . ' col-md-6' : 'col col-md-6' }}">
    <div class="product-item-list card border-0 shadow h-100">
        <!-- Product Image -->
        <div class="position-relative overflow-hidden">
            @php
                $badges = [
                    '<span class="position-absolute top-0 start-0 bg-success text-white px-2 py-1 small m-2 rounded-2">New Tour</span>',
                    '<span class="position-absolute top-0 start-0 bg-primary text-white px-2 py-1 small m-2 rounded-2">Popular</span>',
                    '<span class="position-absolute top-0 start-0 bg-warning text-dark px-2 py-1 small m-2 rounded-2">Limited Offer</span>',
                    '<span class="position-absolute top-0 start-0 bg-info text-white px-2 py-1 small m-2 rounded-2">Featured</span>',
                    '', // Empty badge
                ];
                $randomBadge = $badges[array_rand($badges)];
            @endphp
            {!! $randomBadge !!}
            <div class="btn-wishlist position-absolute top-0 end-0 m-2">
                <button class="button-animated like wishlist-add p-2 py-1" data-product-id="{{ $product->id }}">
                    <i class="fa fa-heart"></i>
                </button>
            </div>
            
            <div class="img-holder overflow-hidden rounded-top">
                @php
                    $images = [
                        "https://animated-dollop-g5v44x4gg5fwq7-80.app.github.dev/images/restaurant2.png",
                        "https://animated-dollop-g5v44x4gg5fwq7-80.app.github.dev/images/nature1.png",
                        "https://animated-dollop-g5v44x4gg5fwq7-80.app.github.dev/images/xx.png",
                        "https://animated-dollop-g5v44x4gg5fwq7-80.app.github.dev/images/explore.png",
                        "https://animated-dollop-g5v44x4gg5fwq7-80.app.github.dev/images/tours.png",
                        "https://animated-dollop-g5v44x4gg5fwq7-80.app.github.dev/images/water.png",
                        "https://animated-dollop-g5v44x4gg5fwq7-80.app.github.dev/images/action1.png",
                        "https://animated-dollop-g5v44x4gg5fwq7-80.app.github.dev/images/airborne.png",
                        "https://animated-dollop-g5v44x4gg5fwq7-80.app.github.dev/images/auto1.png",
                        "https://animated-dollop-g5v44x4gg5fwq7-80.app.github.dev/images/spa1.png",
                        "https://animated-dollop-g5v44x4gg5fwq7-80.app.github.dev/images/educational.png",
                        "https://animated-dollop-g5v44x4gg5fwq7-80.app.github.dev/images/restaurant1.png"
                    ];
                    $images = array_values(array_unique($images));
                    $randomImage = $images[array_rand($images)];
                @endphp
                <img src="{{ $product->getThumbImage() }}" class="product-image w-100 object-fit-cover" alt="{{ $product->translateAttribute('name') }}">
            </div>
            <a href="{{ $product->defaultUrl->slug }}" class="stretched-link"></a>
        </div>

        <!-- Card Body -->
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h3 class="card-title h5 mb-0">
                    <a href="{{ $product->defaultUrl->slug }}" class="text-decoration-none text-dark">
                        {{ $product->translateAttribute('name') }}
                    </a>
                </h3>
                <div class="text-muted" style="width: 66px; text-align: right; margin-top: 5px; font-size: 14px; font-weight: 700; color: rgb(25 135 84) !important;">
                    @if($product->average_rating != 0)
                        {{ $product->average_rating }} <i class="fa fa-star"></i>
                    @else
                        4.7 <i class="fa fa-star"></i>
                    @endif
                </div>
            </div>
            
            <!-- Tour Details -->
            <div class="d-flex flex-wrap gap-3 my-3 text-muted small">
                <div class="d-flex align-items-center">
                    <i class="fa fa-clock-o me-2"></i> 
                    {{ $product->translateAttribute('length') ?? '3.5 hours' }}
                </div>
                <div class="d-flex align-items-center">
                    <i class="fa fa-users me-2"></i> 
                    {{ $product->translateAttribute('participants') ?? 'Small group' }}
                </div>
                <div class="d-flex align-items-center">
                    <i class="fa fa-map-marker me-2"></i> 
                    {{ $product->translateAttribute('city') ?? 'Athens' }}
                </div>
            </div>

            <!-- @todo variation -->
            <div class="d-flex flex-wrap gap-2 mb-3 small d-none">
                <div class="d-flex align-items-center bg-light px-2 py-1 rounded">
                    <i class="fa fa-clock-o text-muted me-1"></i> 3.5h
                </div>
                <div class="d-flex align-items-center bg-light px-2 py-1 rounded">
                    <i class="fa fa-walking text-muted me-1"></i> 2.5km
                </div>
                <div class="d-flex align-items-center bg-light px-2 py-1 rounded">
                    <i class="fa fa-utensils text-muted me-1"></i> 8 stops
                </div>
            </div>
            
            <!-- Highlights -->
            <ul class="list-unstyled small mb-3 d-none">
                <li class="mb-1"><i class="fa fa-check text-success me-2"></i> 8+ local tastings</li>
                <li class="mb-1"><i class="fa fa-check text-success me-2"></i> Expert food guide</li>
                <li><i class="fa fa-check text-success me-2"></i> Vegetarian options</li>
            </ul>
            
            <!-- Price & CTA -->
            <div class="d-flex justify-content-between align-items-center mt-4 pt-2">
                <div>
                    <span class="text-muted small">
                        @if($product->variants->count() > 1)
                            {{ __('From') }}
                        @else
                            {{ __('Price') }}
                        @endif
                    </span>
                    <span class="fs-5 ms-2">
                        {{ $product->price }}
                        @if($product->has_discount)
                            <span class="small text-decoration-line-through text-shallow">{{ $product->price_without_discount }}</span>
                        @endif
                    </span>
                </div>
                <a href="{{ $product->defaultUrl->slug }}" class="btn btn-sm rounded-pill px-3">
                    <i class="fa fa-calendar me-1"></i> Book Now
                </a>
            </div>
        </div>
    </div>
</div>