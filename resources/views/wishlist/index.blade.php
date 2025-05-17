@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Your Wishlist</h1>
    
    @if($products->count() > 0)
            <div class="row">
            @foreach($products as $product)
                    <div class="col-md-4 col-lg-3 mb-4">
                        <div class="card h-100">
                            <div class="position-relative">
                                <img src="{{ $product->image }}" class="card-img-top" alt="{{ $product->name }}">
                                <span class="btn-wishlist active position-absolute top-0 end-0 m-2">
                                    <span class="wishlist-add material-symbols-outlined product-fav-icon active" 
                                          data-product-id="{{ $product->id }}">favorite</span>
                                </span>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->translateAttribute('name') }}</h5>
                                <p class="card-text">{{ $product->price }}</p>
                                <a href="{{ $product->slug }}" class="btn btn-primary">View Details</a>
                            </div>
                        </div>
                    </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-5">
            <span class="material-symbols-outlined text-muted" style="font-size: 60px">favorite</span>
            <h3 class="mt-3">Your wishlist is empty</h3>
            <p class="text-muted">Start adding items you love</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary mt-2">Browse Products</a>
        </div>
    @endif
</div>
@endsection
