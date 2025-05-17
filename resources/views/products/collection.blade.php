@extends('layouts.app')

@section('content')
    @csrf
    <!-- Add your form fields here -->
<h1>Collection: {{ $collection->translateAttribute('name') }}</h1>
<p>{{ $collection->translateAttribute('description') }}</p>

<section class="pb-4">
    <div class="row">
        <div class="col-md-3 col-lg-3">
            @include('partials.search')
        </div>

        <div class="col-md-9 col-lg-9">
            <div class="row items" id="search-results">
                @foreach($products as $product)
                    @include('products.product', ['product' => $product])
                @endforeach
            </div>
        </div>
</section>


@endsection