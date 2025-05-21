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
            <!-- Add this right after your form opening tag -->


<!-- Add this CSS to your stylesheet -->
<style>
.filter-tags-container {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    align-items: center;
}

.filter-tag {
    display: inline-flex;
    align-items: center;
    background: #f3f4f6;
    border-radius: 9999px;
    padding: 0.25rem 0.75rem;
    font-size: 0.875rem;
    color: #4b5563;
}

.filter-tag-remove {
    margin-left: 0.5rem;
    background: none;
    border: none;
    color: #6b7280;
    cursor: pointer;
    font-size: 1rem;
    line-height: 1;
    padding: 0;
}

.filter-tag-remove:hover {
    color: #ef4444;
}
</style>
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