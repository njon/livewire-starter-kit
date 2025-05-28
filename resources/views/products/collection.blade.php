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
      <div class="d-flex align-items-center flex-row-reverse">
        <div class="sorting">
          <span class="me-2">Sort by:</span>
          <span class="dropdown">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown"
              data-bs-toggle="dropdown" aria-expanded="false">
              Featured
            </button>
            <ul class="dropdown-menu" aria-labelledby="sortDropdown">
              <li>
                <h6 class="dropdown-header">Sort options</h6>
              </li>
              <li><a class="dropdown-item active" href="#" data-sort="featured">Featured</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="#" data-sort="price-asc">Price: Low to High</a></li>
              <li><a class="dropdown-item" href="#" data-sort="price-desc">Price: High to Low</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="#" data-sort="rating">Customer Rating</a></li>
              <li><a class="dropdown-item" href="#" data-sort="newest">Newest Arrivals</a></li>
              <li><a class="dropdown-item" href="#" data-sort="bestsellers">Best Sellers</a></li>
            </ul>
</span>
        </div>
      </div>
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