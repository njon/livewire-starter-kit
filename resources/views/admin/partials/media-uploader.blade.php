<div class="images-upload">
    <div>
        <div class="row g-3" id="image-preview">
            <div class="image-preview-container col-md-4 col-lg-3 relative">
                <div class="position-relative h-100 border-2 upload-placeholder rounded-2" onclick="document.getElementById('media-dropzone').click()">
                    <div class="text-center text-muted content mt-4">
                        <i class="bi bi-image" style="font-size: 2rem;"></i><br>

                        <span class="text-muted small d-block">
                            <a href="#">Click here</a> to
                            upload images
                        </span>
                    </div>
                </div>
            </div>
            @if($product->hasMedia('products'))
            @foreach($product->getMedia('products') as $media)
            <div class="image-preview-container col-md-4 col-lg-3">
                <div class="position-relative h-100  rounded-2">
                    <img src="{{ $media->getUrl() }}" class="img-fluid rounded-3 object-fit-cover w-100" alt="Product image">
                    <button type="button" onclick="deleteImage({{ $media->id }})" class="remove-button-d position-absolute top-0 right-0 bg-white-500 text-dark p-1 rounded-full">
                    Remove
                    </button>
                </div>
            </div>
            @endforeach
            @endif
        </div>
        <input type="file" id="image-upload-input" accept="image/*" class="d-none">
    </div>
</div>