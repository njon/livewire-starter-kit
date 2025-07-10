@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">{{ __('Your Wishlist') }}</h1>
    
    @if($products->count() > 0)
        <div class="row">
        @foreach($products as $product)
            <div class="col-md-4 col-lg-3 mb-4">
                <div class="h-100">
                    <div class="position-relative">
                        @if($product->thumbnail)
                        <!-- Product Image -->
                        <div class="img-holder overflow-hidden rounded-4">
                            <img src="{{ $product->thumbnail->getUrl() }}" class="product-image object-fit-cover" alt="{{ $product->translateAttribute('name') }}">
                        </div>

                        <!-- View Product Link -->
                        <a href="{{ $product->defaultUrl->slug }}" class="stretched-link"></a>
                        @endif
                        <span class="btn-wishlist active position-absolute top-0 end-0 m-2">
                            <span class="wishlist-add material-symbols-outlined product-fav-icon active" 
                                  data-product-id="{{ $product->id }}">favorite</span>
                        </span>
                    </div>
                    <div>
                        <h5 class="card-title pt-4">
                            <a href="{{ $product->defaultUrl->slug }}" class="text-decoration-none">{{ $product->translateAttribute('name') }}</a>
                        </h5>
                        <p class="card-text py-3">{{ $product->price }}</p>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <span class="material-symbols-outlined text-muted" style="font-size: 60px">favorite</span>
            <h3 class="mt-3">{{ __('Your wishlist is empty') }}</h3>
            <p class="text-muted">{{ __('Start adding items you love') }}</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary mt-2">{{ __('Browse Products') }}</a>
        </div>
    @endif
</div>
@endsection
