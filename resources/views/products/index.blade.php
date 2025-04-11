@extends('layouts.app')

@section('content')
    <h1>All Products</h1>
    <div class="product-grid">
        @foreach($products as $product)
            <div class="product-card">
                    @if($product->thumbnail)
                        <img src="{{ $product->thumbnail->getUrl() }}" alt="{{ $product->translateAttribute('name') }}">
                    @endif
                    <h3><a href="{{ route('products.show', $product->defaultUrl->slug) }}">{{ $product->translateAttribute('name') }}</a></h3>
                    <p>{{ $product->price }}</p>
            </div>
        @endforeach
    </div>

    {{ $products->links() }}
@endsection