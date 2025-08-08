<div class="row gallery-row mb-5">
    <div class="col-md-2 gallery-thumbnails">
        <div class="thumbnail-column gap-3">
            <!-- Thumbnail 1 - Spa -->
            @if($product->images->first())
            <div class="thumbnail-item active" 
                 data-target="{{ $product->images->first()->getUrl() }}">
                <img loading="lazy" src="{{ $product->images->first()->getUrl() }}"
                    alt="Luxury spa" class="img-fluid thumb-image">
            </div>
            @endif

            <!-- Thumbnail 3 - Rest -->
            @if($product->images->skip(1)->first())
            <div class="thumbnail-item" 
                 data-target="{{ $product->images->skip(1)->first()->getUrl() }}">
                <img loading="lazy" src="{{ $product->images->skip(1)->first()->getUrl() }}"
                    alt="Peaceful rest" class="img-fluid thumb-image">
            </div>
            @endif
        </div>
    </div>

    <div class="col-md-7 main-image">
        @if($product->images->skip(2)->first())
        <img loading="lazy" src="{{ $product->images->skip(2)->first()->getUrl() }}" 
             id="mainProductImage"
             alt="Main spa image" 
             class="img-fluid rounded ">
        @endif
    </div>

    <div class="col-md-3 secondary-image">
        @if($product->images->skip(3)->first())
        <img loading="lazy" src="{{ $product->images->skip(3)->first()->getUrl() }}" 
             alt="Secondary massage image" 
             class="img-fluid rounded mb-3">
        @endif
    </div>
</div>



 <!-- /* @foreach($product->images->take(6) as $image)
    <div class="thumbnail-item {{ $loop->first ? 'active' : '' }}"
        data-target="{{ $image->getUrl() }}">
    </div>
@endforeach  */ -->