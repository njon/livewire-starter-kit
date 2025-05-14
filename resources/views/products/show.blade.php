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
                <span class="material-symbols-outlined product-fav-icon">favorite</span>
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
                        ★★★★★ ( 4.7 rating{{ $product->rating }})
                    </div>
                </div>

                <h4 class="mt-4 mb-4">{{ $product->translateAttribute('name') }}</h4>
                <p class="short-description">
                    {{ $product->translateAttribute('short_description') }}</p>

                <div class="pricing mb-3">
                    @if ($product->has_discount)
                        <span class="original-price">{{ $product->price_without_discount }}</span>
                    @endif
                    <span class="current-price">{{ $product->price }}</span>
                    @if ($product->has_discount)
                        <span class="discount-percentage">-{{ $product->discount_percentage }}%</span>
                    @endif
                </div>

                @if($product->has_discount)
                    <div class="d-flex align-items-center special-offer" role="alert">
                        <span class="material-symbols-outlined">schedule</span> Special offer: <span class="countdown"
                            data-end="{{ $product->price }}">04:25:15</span>
                    </div>
                @endif

                @if($product->variants->isNotEmpty())
                <div class="product-variants">
                    <h3 class="text-lg font-medium mb-4">Available Options</h3>
                    
                    @foreach($product->variants as $variant)
                        <div class="variant-option mb-6">
                            <h4 class="font-medium mb-2">{{ $variant->translate('name') }}</h4>
                            
                            @if($variant->values->isNotEmpty())
                                <div class="variant-values flex flex-wrap gap-2">
                                    @foreach($variant->values as $value)
                                        <button 
                                            type="button"
                                            class="variant-value-btn px-4 py-2 border rounded hover:bg-gray-100 transition"
                                            data-form-link="/cart/{{ $value->id }}"
                                            data-variant-id="{{ $variant->id }}"
                                            data-value-id="{{ $value->id }}"
                                            @if($value->disabled) disabled @endif
                                        >
                                            {{ $value->translate('name') }}
                                            {{ $variant->basePrices()->first()->price->formatted()}}
                                            {{ $variant->price }}

                                            @if($value->price)
                                                <span class="text-sm text-gray-600 ml-1">
                                                    (+{{ $value->price->formatted }})
                                                </span>
                                            @endif
                                        </button>
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
                        {{ $product->translateAttribute('participants') }} participants
                    </div>
                    <div class="attribute">
                        <span class="material-symbols-outlined product-icon">schedule</span>
                        {{ $product->translateAttribute('length') }} minutes
                    </div>
                    <div class="attribute">
                        <span class="material-symbols-outlined product-icon">person</span>
                        {{ $product->translateAttribute('participants') }} participants
                    </div>
                    <div class="attribute">
                        <span class="material-symbols-outlined product-icon">schedule</span>
                        {{ $product->translateAttribute('length') }} minutes
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
