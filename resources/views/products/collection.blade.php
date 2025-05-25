@extends('layouts.app')

@section('content')
    @csrf
    <!-- Add your form fields here -->
<h1 class="pt-5 fs-2">Collection: {{ $collection->translateAttribute('name') }}</h1>
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

                @include('products.search-tags', ['filterCategories' => $filterCategories])
                @foreach($products as $product)
                    @include('products.product', ['product' => $product])
                @endforeach
                {!! $pagination !!}
            </div>
        </div>
</section>


@endsection