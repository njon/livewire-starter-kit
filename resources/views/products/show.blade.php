@extends('layouts.app')

@section('title', $product->translateAttribute('name'))
<!-- @todo set meta description form product -->
@section('meta_description', 'This is the home page of our awesome Laravel site.')
@include('products.structure')

@section('content')

@php $first = true; @endphp
<div class="product-detail mt-3">
    @include('partials.breadcrumbs')

    <!-- Tide + Wishlist Row -->
    <div class="row mb-4">
        <div class="col-md-9">
            <h1 class="fs-3 fs">{{ $product->translateAttribute('name') }}</h2>
        </div>
        <div class="col-md-3 text-end">
            <div class="btn-wishlist">
                <button class="button-animated like wishlist-add" data-product-id="{{ $product->id }}">
                    <i class="fa fa-heart"></i>
                </button>
            </div>
        </div>
    </div>

    @include('products.gallery')

    <!-- Description + Sticky Box Row -->
    <div class="row">
        <div class="col-sm-12 col-md-7 col-lg-7 col-xl-8 product-description">
            <h2 class="mb-4 fs-4 sf">{{ __('Service description') }}</h2>
            <article>
                <p>{!! $product->translateAttribute('description') !!}</p>
            </article>

            @include('products.rules')

            <div class="accordion mb-4 mt-4">
                @include('products.additional')
                @include('products.questions.index', ['product' => $product, 'questions' =>
                $product->questions()->paginate(10)])
                @include('products.reviews.index', ['product' => $product, 'reviews' => $product->reviews])
            </div>
        </div>

        <div class="col-sm-12 col-md-5 col-lg-5 ps-lg-4 col-xl-4">
            <div class="sticky-box">
                <div class="card shadow-lg border-0 rounded-2 overflow-hidden">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="text-muted small">
                                {{ $product->city }} 
                                <div class="d-inline-flex flex-wrap gap-2">
                                  <span class="badge bg-light text-dark">{{ __('Acropolis') }}</span>
                                  <span class="badge bg-light text-dark">{{ __('Kolonaki') }}</span>
                                  <span class="badge bg-light text-dark">{{ __('Monastiraki') }}</span>
                                  <span class="badge bg-light text-dark">{{ __('Plaka') }}</span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                @if($product->average_rating == 0)
                                <span class="text-muted small">{{ __('Not rated yet') }}</span>
                                @else
                                <div class="text-warning h5 mb-0 me-2" role="img"
                                    aria-label="{{ number_format($product->average_rating, 1) }} {{ __('out of 5 stars') }}">
                                    {{ $product->rating_stars }}
                                </div>
                                <span class="text-muted small">{{ number_format($product->average_rating, 1) }}
                                    {{ __('rating') }}</span>
                                @endif
                            </div>
                        </div>

                        <h4 class="card-title text-dark lh-35">{{ $product->translateAttribute('name') }}</h4>
                        <p class="card-text text-muted mb-4 sf">
                            {{ $product->translateAttribute('short_description') }}
                        </p>

                        <div class="d-flex align-items-baseline my-3">
                            <span class="fs-3 fw-bolder me-2" id="cart-price">{{ $product->price }}</span>
                            @if ($product->has_discount)
                            <span
                                class="text-decoration-line-through text-muted me-2" id="cart-discount">{{ $product->price_without_discount }}</span>
                            <span
                                class="badge bg-success-subtle text-success fw-bold">-{{ $product->discount_percentage }}%</span>
                            @endif
                        </div>

                        @if($product->has_discount)
                        <div class="alert alert-success border-0 d-flex align-items-center p-2 px-3 rounded-4 my-3"
                            role="alert">
                            <i class="material-symbols-outlined me-2 fs-5">schedule</i>
                            <small class="fw-semibold text-info-emphasis">{{ __('Special Offer') }} </small>
                            @if(!$end['ended'])
                            <div class="countdown-timer d-flex align-items-center flex-grow-1 ms-1">
                                <span class="badge bg-warning text-dark me-1" id="countdown-days-container">
                                    <span id="countdown-days">{{ $end['days'] }}</span> days
                                </span>
                                <span class="text-info-emphasis fw-bold" id="countdown-time">
                                    {{ str_pad($end['hours'], 2, '0', STR_PAD_LEFT) }}:{{ str_pad($end['minutes'], 2, '0', STR_PAD_LEFT) }}:{{ str_pad($end['seconds'], 2, '0', STR_PAD_LEFT) }}
                                </span>
                            </div>
                            @endif
                        </div>
                        @endif

                        <!-- <div class="row text-center my-3">
                            <div class="col-4">
                                <div class="d-flex flex-column align-items-center justify-content-center h-100">
                                    <div class="mb-3 text-primary">
                                        <i class="fa fa-users fa-2x text-grey" aria-hidden="true"></i>
                                    </div>
                                    <p class="fw-semibold mb-0 fs-14 info-box-text">For 2<br>participants</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="d-flex flex-column align-items-center justify-content-center h-100">
                                    <div class="mb-3 text-primary">
                                        <i class="fa fa-calendar fa-2x text-grey" aria-hidden="true"></i>
                                    </div>
                                    <p class="fw-semibold mb-0 fs-14 info-box-text">12 Months<br>Validity</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="d-flex flex-column align-items-center justify-content-center h-100">
                                    <div class="mb-3 text-primary">
                                        <i class="fa fa-map-marker fa-2x text-grey" aria-hidden="true"></i>
                                    </div>
                                    <p class="fw-semibold mb-0 fs-14 info-box-text">{{ __('30 days') }}<br>{{ __('Refund') }}</p>
                                </div>
                            </div>
                        </div> -->

@if($product->variants->isNotEmpty() && $product->variants->count() > 1)
    <div class="product-variants mb-4">
        <h6 class="text-lg font-medium mb-3">{{ __('Select package') }}</h6>
        <div class="variant-options">
            @php $first = true; @endphp
            @foreach($product->variants as $variant)
                <div class="variant-option mb-3">
                    <input type="radio" class="btn-check" name="variant" id="variant-{{ $variant->id }}"
                           autocomplete="off" @if($first) checked @endif>
                    <label for="variant-{{ $variant->id }}"
                           class="variant-card d-flex flex-column p-md-3 rounded-3 border"
                           data-form-link="/cart/{{ $variant->id }}" 
                           data-variant-id="{{ $variant->id }}">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="variant-name h6 mb-0">
                                
                            </span>
                            <div class="text-truncate pe-2">
                                <div class="variant-title h6 mb-0 text-truncate" title="Adrenaline Rush: Extreme Paintball Combat Experience">
                                    {{ $product->translateAttribute('name') }}
                                </div>
                                <div class="variant-subtitle small text-muted text-truncate">
                                    {{ $variant->translateAttribute('name') }}
                                </div>
                            </div>
                            <span class="variant-price badge bg-primary rounded-pill">
                                {{ $variant->price }}
                            </span>
                            @if($variant->has_discount)
                                <span class="variant-discount badge bg-light rounded-pill text-decoration-line-through text-muted">{{ $variant->price_without_discount }}</span>
                            @endif
                        </div>
    
                        @php $first = false; @endphp
                    </label>
                </div>
            @endforeach
        </div>
    </div>
@endif

                        <div class="row">
                            <div class="col-12">
                                <form id="add-to-cart" action="/cart/{{ $product->variants->first()->id }}"
                                    method="PUT">
                                    <button type="submit"
                                        class="btn btn-success rounded-2 btn-add-to-cart w-100 p-2 button-animated"
                                        id="btn-add-to-cart">
                                        {{ __('Add to Cart') }}
                                    </button>
                                    <input type="hidden" name="to_cart" value="1">
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                </form>
                            </div>

                            <div class="col-12 mt-2">
                                <form id="add-to-cart" action="/cart/{{ $product->variants->first()->id }}" data-redirect="true" method="PUT">
                                    <button class="btn btn-dark btn btn-success rounded-2 animate-btn-hover w-100 p-2"
                                        id="btn-add-to-cart">
                                        <i class="fa fa-cart"></i> {{ __('Buy Now') }}
                                    </button>
                                    <input type="hidden" name="to_cart" value="1">
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                </form>
                            </div>
                        </div>

                        <div class="text-center mt-3" id="klarna">
                                {{ __('Make 3 payments of £26.33.') }}
                                <br>
                            <span class="fw-bold">Klarna</span> <a class="classic-color" href="#" aria-label="{{ __('Learn more - Klarna') }}">
                                {{ __('Learn more') }}
                            </a>
                        </div>

                        <div class="small text-muted right mt-5">
                            <a href="/terms-of-service" target="_blank"
                                class="text-decoration-none text-muted me-2">{{ __('Terms And Services') }}</a> |
                            <a href="/refund-policy" target="_blank" class="text-decoration-none text-muted mx-2">{{ __('Refund Policy') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="similar-products">
            <h3 class="py-4 fs-4 mb-3">{{ __('Similar Experiences you may like') }}</h3>
            <div class="row">
                @foreach ($relatedProducts as $product)
                    @include('products.product', ['col' => '3'])
                @endforeach
            </div>
        </div>
    </div>
</div>
</div>

<!-- Add this modal structure at the bottom of your body -->
<div class="modal" id="imageGalleryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="modalImage" class="img-fluid" alt="Gallery Image">
                <div class="position-absolute h-100">
                    <div
                        class="h-100 d-flex align-items-center justify-content-between position-absolute top-0 start-0 end-0 px-3">
                        <div>
                            <button type="button" class="gallery-control" id="prevImage">
                                <svg width="40" height="40" viewBox="0 0 60 60">
                                    <path d="M35 15 L15 30 L35 45 Z" fill="white" stroke="#333" stroke-width="2" />
                                </svg>
                            </button>
                        </div>
                        <div>
                            <button type="button" class="gallery-control" id="nextImage">
                                <svg width="40" height="40" viewBox="0 0 60 60">
                                    <path d="M25 15 L45 30 L25 45 Z" fill="white" stroke="#333" stroke-width="2" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection