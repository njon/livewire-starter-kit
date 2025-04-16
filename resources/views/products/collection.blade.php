@extends('layouts.app')

@section('content')
<h1>Collection: {{ $collection->translateAttribute('name') }}</h1>
<p>{{ $collection->translateAttribute('description') }}</p>



@include('partials.search')



<section class="pb-4">
    <div class="row items" id="search-results">
        @foreach($products as $product)
            @include('products.product', ['product' => $product])
        @endforeach
    </div>
</section>


@endsection