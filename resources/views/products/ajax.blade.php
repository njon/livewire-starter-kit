
@foreach($products as $product)
    @include('products.product', ['product' => $product, 'col' => '4'])
@endforeach

{!! $pagination !!}