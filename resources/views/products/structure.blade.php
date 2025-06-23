@php
    $structuredData = [
        "@context" => "https://schema.org",
        "@type" => "Product",
        "name" => $product->translateAttribute('name'),
        "image" => [$product->images->first() ? $product->images->first()->getUrl() : asset('images/default-product.png')],
        "description" => $product->translateAttribute('description'),
        "sku" => $product->sku,
        "offers" => [
            "@type" => "Offer",
            "url" => url()->current(),
            "priceCurrency" => "EUR",
            "price" => $product->getDefaultPrice()/100,
            "availability" => "https://schema.org/InStock",
        ],
    ];
@endphp
        <!-- @todo finish -->
        <!-- "brand" => [
            "@type" => "Brand",
            "name" => $product->brand->name,
        ], -->

@section('structured_data')
<script type="application/ld+json">
    {!! json_encode($structuredData, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT) !!}
</script>
@endsection