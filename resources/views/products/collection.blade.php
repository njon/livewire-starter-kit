@extends('layouts.app')

@section('content')
<h1>Collection: {{ $collection->translateAttribute('name') }}</h1>
<p>{{ $collection->translateAttribute('description') }}</p>

<section class="pb-4">
    <div class="row">
        @foreach($products as $product)
            @include('products.product', ['product' => $product])
        @endforeach
    </div>
</section>


{{ $products->links() }}
@endsection