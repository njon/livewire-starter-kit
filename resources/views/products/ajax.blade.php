@include('products.search-tags', ['filterCategories' => $filterCategories])

@foreach($products as $product)
    @include('products.product', ['product' => $product])
@endforeach

{!! $pagination !!}