@extends('layouts.app')
@section('content')

@include('partials.cart-element')
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


            <div class="d-flex justify-content-between mt-5">
                <div>
                    <h2>Questions and Answers</h2>
                </div>

                <div>
                    <button type="button" class="btn btn-outline-success p-3">ASK QUESTION</button>
                </div>
            </div>

            <div class="mb-4">Showing 2 questions</div>

            @include('products.questions.index', ['product' => $product, 'questions' => $product->answeredQuestions()->paginate(5)])

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
                    <span class="original-price">{{ $product->original_price }}</span>
                    <span class="current-price">{{ $product->price }}</span>
                    <span class="discount-percentage">-{{ $product->discount }}%</span>
                </div>

                @if($product->price)
                    <div class="d-flex align-items-center special-offer" role="alert">
                        <span class="material-symbols-outlined">schedule</span> Special offer: <span class="countdown"
                            data-end="{{ $product->price }}">04:25:15</span>
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
                    <form id="add-to-cart" action="/cart/add" method="POST">
                        <button class="btn btn-success btn-add-to-cart" id="btn-add-to-cart">
                            <span class="material-symbols-outlined icon-bottom">shopping_cart</span> Add to Cart
                        </button>
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="image" value="{{ $product->images->first()->getUrl() }}">
                        <input type="hidden" name="link" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <input type="hidden" name="price" value="{{ $product->price }}">
                        <input type="hidden" name="product_name" value="{{ $product->translateAttribute('name') }}">
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
