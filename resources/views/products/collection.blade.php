@extends('layouts.app')

@section('content')
    @csrf
    <!-- Add your form fields here -->
<h1 class="pt-5">Collection: {{ $collection->translateAttribute('name') }}</h1>
<div class="text-muted mb-5 mt-3">
    {!! $collection->translateAttribute('description') !!}
</div>

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