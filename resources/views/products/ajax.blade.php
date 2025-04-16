@foreach($products as $product)
    @include('products.product', ['product' => $product])
    
@endforeach
