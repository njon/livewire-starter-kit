@extends('layouts.app')

@section('title', $collection->translateAttribute('name'))

@section('content')
@csrf

<section class="pb-4">
  <div class="row">
    <div class="col-md-3 col-lg-3">
      @include('partials.search')
    </div>

    <div class="col-md-9 col-lg-9" id="content">

      <h1 class="pt-5 fs-2">{{ $collection->translateAttribute('name') }}</h1>
      <div class="text-muted mb-5 mt-3">
        {!! $collection->translateAttribute('description') !!}
      </div>

      <div class="d-flex justify-content-between align-items-center mb-4">
        <div id="search-filters flex-grow-1">
          <div class="filter-tags-container">
            @include('products.search-tags', ['filterCategories' => $filterCategories])
          </div>
        </div>

        <div class="sorting flex-shrink-0">
          <span class="me-2 small">Sort by:</span>
          <span class="dropdown">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown"
              data-bs-toggle="dropdown" aria-expanded="false">
              @switch(request('sort'))
              @case('price_asc') Price: Low to High @break
              @case('price_desc') Price: High to Low @break
              @case('rating_desc') Best rated @break
              @case('rating_asc') Lowest rated @break
              @default Default
              @endswitch
            </button>
            <ul class="dropdown-menu" aria-labelledby="sortDropdown">
              <li>
                <h6 class="dropdown-header">Sort options</h6>
              </li>
              <li><a class="dropdown-item small" href="#" data-sort="price_asc">Price: Low to High</a></li>
              <li><a class="dropdown-item small" href="#" data-sort="price_desc">Price: High to Low</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item small" href="#" data-sort="rating_desc">Best rated</a></li>
              <li><a class="dropdown-item small" href="#" data-sort="rating_asc">Lowest rated</a></li>
            </ul>
          </span>
        </div>
      </div>

      <div class="row items" id="search-results">
        @foreach($products as $product)
          @include('products.product', ['product' => $product, 'col' => '4'])
        @endforeach
        {!! $pagination !!}
      </div>
    </div>
</section>

@endsection