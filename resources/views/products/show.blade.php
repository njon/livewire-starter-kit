@extends('layouts.app')
@section('content')

@include('partials.breadcrumbs')

<div class="product-detail mt-3">
    <!-- <div class="row gallery-row mb-5">
        <div class="col-md-3 gallery-thumbnails">
            <div class="thumbnail-column">
                @foreach($product->images->take(6) as $image)
                    <div class="thumbnail-item {{ $loop->first ? 'active' : '' }}"
                        data-target="{{ $image->getUrl() }}">
                    </div>
                @endforeach
            </div>
        </div>
                
        <div class="col-md-9 main-image">
            <img src="{{ $product->images->first()->getUrl() }}" id="mainProductImage"
                alt="{{ $product->translateAttribute('name') }}" class="img-fluid">
        </div>
    </div> -->

    <!-- Tide + Wishlist Row -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>{{ $product->translateAttribute('name') }}</h2>
        </div>
        <div class="col-md-6 text-end">

            <span class="btn-wishlist"> 
                <span class="wishlist-add material-symbols-outlined product-fav-icon" data-product-id="{{ $product->id }}">favorite</span>
            </span>
        </div>
    </div>

    <!-- Description + Sticky Box Row -->
    <div class="row">
        <div class="col-md-8 product-description">
            {!! $product->translateAttribute('description') !!}



            @include('products.questions.index', ['product' => $product, 'questions' => $product->questions()->paginate(10)])
            @include('products.reviews.index', ['product' => $product, 'reviews' => $product->reviews])

        </div>

        <div class="col-md-4">
            <div class="sticky-box">
                <div class="availability-rating d-flex justify-content-between">
                    <div class="cities">
                        {{ $product->city }} Athens, Thessaloniki
                    </div>
                    <div class="rating">
                            <div class="average-rating mb-4">
        @php
            $averageRating = $product->reviews->avg('rating');
            $reviewCount = $product->reviews->count();
        @endphp
        <div class="d-flex align-items-center">
            <div class="star-rating-display me-3">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= floor($averageRating))
                        ★
                    @elseif($i - 0.5 <= $averageRating)
                        ½
                    @else
                        ☆
                    @endif
                @endfor
                <span class="ms-2">{{ number_format($averageRating, 1) }} rating</span>
            </div>
        </div>
    </div>
                    </div>
                </div>

                <h4 class="mt-4 mb-4">{{ $product->translateAttribute('name') }}</h4>
                <p class="short-description">
                    {{ $product->translateAttribute('short_description') }}</p>

                <div class="pricing mb-3">
                    <span class="current-price">{{ $product->price }}</span>
                    @if ($product->has_discount)
                        <span class="original-price">{{ $product->price_without_discount }}</span>
                    @endif
                    @if ($product->has_discount)
                        <span class="discount-percentage">-{{ $product->discount_percentage }}%</span>
                    @endif
                </div>

                @if($product->has_discount)
                <div class="d-flex align-items-center special-offer" role="alert">
                    <span class="material-symbols-outlined">schedule</span>&nbsp; Special offer:&nbsp;

                    @if(!$end['ended'])
                    <span class="countdown-timer">
                        <span id="countdown-days-container"><span id="countdown-days">{{ $end['days'] }}</span> days</span>
                        <span id="countdown-time">{{ str_pad($end['hours'], 2, '0', STR_PAD_LEFT) }}:{{ str_pad($end['minutes'], 2, '0', STR_PAD_LEFT) }}:{{ str_pad($end['seconds'], 2, '0', STR_PAD_LEFT) }}</span>
                    @endif
                </div>

                @endif

                @if($product->variants->isNotEmpty() && $product->variants->count() > 1)
                <div class="product-variants">
                    <h6 class="text-lg font-medium mb-4">Available Options</h6>
                    
                    @foreach($product->variants as $variant)
                        <div class="variant-option mb-6">
                            <h4 class="font-medium mb-2">{{ $variant->translate('name') }}</h4>
                            
                            @if($variant->values->isNotEmpty())
                                <div class="variant-values flex flex-wrap gap-2">
                                    @foreach($variant->values as $value)
                                        <input type="radio" name="variant" id="variant-{{ $value->id }}">
                                            <label for="variant-{{ $value->id }}"
                                                type="button"
                                                class="variant-value-btn px-4 py-2 border rounded hover:bg-gray-100 transition"
                                                data-form-link="/cart/{{ $value->id }}"
                                                data-variant-id="{{ $variant->id }}"
                                                data-value-id="{{ $value->id }}">
                                                {{ $value->translate('name') }}
                                                {{ $variant->price }}
                                            </label>
                                        </input>                            
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                @endif

                <div class="product-attributes d-flex justify-content-between mt-2">
                <div class="attribute">
                        <span class="material-symbols-outlined product-icon">person</span>
                        3{{ $product->translateAttribute('participants') }} participants
                    </div>
                    <div class="attribute">
                        <span class="material-symbols-outlined product-icon">schedule</span>
                        60{{ $product->translateAttribute('length') }} minutes
                    </div>
                </div>

                <div class="action-buttons">
                    <form id="add-to-cart" action="/cart/5" method="PUT">
                        <button class="btn btn-success btn-add-to-cart" id="btn-add-to-cart">
                            <span class="material-symbols-outlined icon-bottom">shopping_cart</span> Add to Cart
                        </button>
                        @csrf
                        <input type="hidden" name="to_cart" value="1">
                        <input type="hidden" name="product_id" value="{{ $product->variants()->first()->id }}">
                        <input type="hidden" name="quantity" value="1">
                    </form>
                    <button class="btn btn-dark btn-buy-now">
                        <span class="material-symbols-outlined icon-bottom">bolt</span> Buy Now
                    </button>
                </div>

                <div class="footer-links">
                    <a href="#">Terms And Services</a> |
                    <a href="#">Refund Policy</a>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="mini-cart"></div>
@endsection
