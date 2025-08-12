
@foreach($products as $product)
    @include('products.product', ['product' => $product, 'col' => '3'])
@endforeach

{!! $pagination !!}