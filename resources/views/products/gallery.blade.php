<!-- $product->getFirstMedia('thumbnails')->first()->getUrl('zoom') -->
<!-- <img src="{{ $product->getMedia('thumbnails')->first() }}"> -->

<!-- <img src="{{ $product->getMedia('thumbnails')->first()->getUrl('small') }}">
<img src="{{ $product->getMedia('thumbnails')->first()->getUrl('medium') }}">
<img src="{{ $product->getMedia('thumbnails')->first()->getUrl('large') }}">  -->

<div class="row gallery-row mb-5">
    <div class="col-md-4 col-sm-6 col-lg-3 col-xl-2 gallery-thumbnails">
        <div class="thumbnail-column gap-3">
            <!-- Thumbnail 1 - Spa -->
            @php $firstMedia = $product->getMedia('products')->first(); @endphp
            @if($firstMedia)
                <div class="thumbnail-item">
                         <img 
                            loading="lazy"
                            src="{{ $firstMedia->getUrl('medium') }}" 
                            srcset="
                                {{ $firstMedia->getUrl('small') }} 480w,
                                {{ $firstMedia->getUrl('small') }} 768w,
                                {{ $firstMedia->getUrl('small') }} 1200w
                            "
                            sizes="(max-width: 600px) 480px,
                                    (max-width: 1024px) 768px,
                                    1200px"
                            alt="{{ $product->translateAttribute('name') }}"
                            class="img-fluid thumb-image rounded activate-gallery"
                >
                </div>
            @endif

            <!-- Thumbnail 2 -->
            @php $secondImage = $product->getMedia('products')->skip(1)->first(); @endphp
            @if($secondImage)
                <div class="thumbnail-item">
                         <img 
                            loading="lazy"
                            src="{{ $secondImage->getUrl('medium') }}" 
                            srcset="
                                {{ $secondImage->getUrl('small') }} 480w,
                                {{ $secondImage->getUrl('small') }} 768w,
                                {{ $secondImage->getUrl('small') }} 1200w
                            "
                            sizes="(max-width: 600px) 480px,
                                    (max-width: 1024px) 768px,
                                    1200px"
                            alt="{{ $product->translateAttribute('name') }}"
                            class="img-fluid thumb-image rounded activate-gallery"
                >
                </div>
            @endif
        </div>
    </div> <!-- Closing col-md-2 div -->

    <div class="col-md-4 col-sm-6 col-lg-5 col-xl-7 main-image">
        @php $mainImage = $product->getMedia('products')->skip(2)->first(); @endphp
        @if($mainImage)
            <img 
                loading="lazy"
                src="{{ $mainImage->getUrl('medium') }}" 
                srcset="
                    {{ $mainImage->getUrl('small') }} 480w,
                    {{ $mainImage->getUrl('medium') }} 768w,
                    {{ $mainImage->getUrl('large') }} 1200w
                "
                sizes="(max-width: 600px) 480px,
                        (max-width: 1024px) 768px,
                        1200px"
                alt="{{ $product->translateAttribute('name') }}"
                class="img-fluid thumb-image rounded activate-gallery">
        @endif
    </div>

    <div class="col-md-4 col-sm-12 col-lg-4 col-xl-3 secondary-image d-sm-none d-md-block">
        @php $lastImage = $product->getMedia('products')->skip(3)->first(); @endphp
        @if($lastImage)
            <img 
                loading="lazy"
                src="{{ $lastImage->getUrl('medium') }}" 
                srcset="
                    {{ $lastImage->getUrl('small') }} 480w,
                    {{ $lastImage->getUrl('medium') }} 768w,
                    {{ $lastImage->getUrl('large') }} 1200w
                "
                sizes="(max-width: 600px) 480px,
                        (max-width: 1024px) 768px,
                        1200px"
                alt="{{ $product->translateAttribute('name') }}"
                class="img-fluid thumb-image rounded activate-gallery">
        @endif
    </div>
</div> <!-- Closing row div -->