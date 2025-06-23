<div class="row gallery-row mb-5">
    <div class="col-md-2 gallery-thumbnails">
        <div class="thumbnail-column gap-3">
            <!-- Thumbnail 1 - Spa -->
            <div class="thumbnail-item active" 
                 data-target="https://images.unsplash.com/photo-1544161515-4ab6ce6db874?ixlib=rb-1.2.1&auto=format&fit=crop&w=1600&q=80">
                <img loading="lazy" src="https://plus.unsplash.com/premium_photo-1679430672295-3846f0cf0503?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                    alt="Luxury spa" class="img-fluid thumb-image">
            </div>
          
            
            <!-- Thumbnail 3 - Rest -->
            <div class="thumbnail-item" 
                 data-target="https://images.unsplash.com/photo-1609342122563-a43ac8917a3a?ixlib=rb-1.2.1&auto=format&fit=crop&w=1600&q=80">
                <img loading="lazy" src="https://images.unsplash.com/photo-1540202403-b7abd6747a18"
                    alt="Peaceful rest" class="img-fluid thumb-image">
            </div>

        </div>
    </div>

    <div class="col-md-7 main-image">
        <img loading="lazy" src="https://images.unsplash.com/photo-1544161515-4ab6ce6db874?ixlib=rb-1.2.1&auto=format&fit=crop&w=1600&q=80" 
             id="mainProductImage"
             alt="Main spa image" 
             class="img-fluid rounded ">
    </div>

    <div class="col-md-3 secondary-image">
        <img loading="lazy" src="https://plus.unsplash.com/premium_photo-1679430887921-31e1047e5b55?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" 
             alt="Secondary massage image" 
             class="img-fluid rounded mb-3">
    </div>
</div>



 <!-- /* @foreach($product->images->take(6) as $image)
    <div class="thumbnail-item {{ $loop->first ? 'active' : '' }}"
        data-target="{{ $image->getUrl() }}">
    </div>
@endforeach  */ -->