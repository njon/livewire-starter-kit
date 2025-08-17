@extends('admin.app')

@section('toolbar')
@include('admin.partials.buttons', [
'title' => 'Save service',
'asset' => 'Service',
'buttons' => [
['save' => true]
]
])
@endsection

@php
$selectedFilterOptionIds = $product->filterOptions->pluck('id')->toArray();
$productHasFilterOption = function($id) use ($selectedFilterOptionIds) {
return in_array($id, $selectedFilterOptionIds);
};
$price = $product->variants->first()->prices->first()->price->value ?? 0;
@endphp

@section('content')

<form action="{{ route('admin.products.media.store', $product) }}" method="POST" id="media-dropzone">
    <small class="text-black small">Files being uploaded...</small>
    @csrf
    @method('PUT')
</form>

<form action="{{ route('admin.products.media.store', $product) }}" method="POST" id="thumbnail-dropzone">
    <small class="text-black small">Files being uploaded...</small>
    @csrf
    @method('PUT')
    <input type="hidden" name="thumbnail" value="1">
</form>

<form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data"
    id="mainProductForm" class="submit-form">
    @csrf
    @method('PUT')

    <!-- Details Section -->
    <div class="row">
        <div class="col-lg-7">

            <!-- SERVICE DETAILS -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom py-3">
                    <h3 class="h5 mb-0 d-flex align-items-center">
                        <i class="bi bi-card-text me-2 text-primary"></i> Service Details
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Language Tabs -->
                    <div class="mb-2 small">Click to change language translations</div>
                    <ul id="languageTabs" role="tablist">
                        
                        @foreach($languages as $language)
                        <li><button class="nav-link @if($loop->first) active @endif" id="{{ $language->code }}-tab"
                                data-bs-toggle="tab" data-bs-target="#{{ $language->code }}-content" type="button"
                                role="tab">
                                {{ $language->name }}
                                <span class="fi fi-{{ $language->code == 'gr' ? 'gr' : 'gb' }} fis"></span>
                            </button></li>
                        @endforeach
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="languageTabsContent">
                        @foreach($languages as $language)
                        @php
                        $url = $product->urls->firstWhere('language.code', $language->code);
                        @endphp
                        <div class="tab-pane fade @if($loop->first) show active @endif"
                            id="{{ $language->code }}-content" role="tabpanel">
                            <div class="row g-4">
                                <div class="col-lg-12">

                                    <label for="product_title_{{ $language->code }}" class="form-label">Service
                                        title</label>
                                    <input type="text" data-slug="true"
                                        class="form-control @error('name.'.$language->code) is-invalid @enderror"
                                        id="product_title_{{ $language->code }}" name="name[{{ $language->code }}]"
                                        value="{{ old('name.'.$language->code, $product->translateAttribute('name', $language->code) ?? '') }}"
                                        @if($language->default) required @endif>
                                    @error('name.'.$language->code)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label d-flex align-items-center gap-2">
                                        URL
                                    </label>
                                    <div class="input-group">

                                        <input type="text" id="product_url_{{ $language->code }}" data-auto="true"
                                            class="form-control url-field @error('urls.'.$language->code) is-invalid @enderror"
                                            name="urls[{{ $language->code }}]" data-lang="{{ $language->code }}"
                                            placeholder="product-name"
                                            value="{{ old('urls.'.$language->code, $url->slug ?? '') }}">

                                        @error('urls.'.$language->code)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <label class="form-label mb-1">Service Description</label>
                                    <textarea id="productDescription_{{ $language->code }}"
                                        name="description[{{ $language->code }}]"
                                        class="rich-text-editor border rounded bg-light @error('description.'.$language->code) is-invalid @enderror"
                                        data-lang="{{ $language->code }}">{{ old('description.'.$language->code, $product->translateAttribute('description', $language->code) ?? '') }}</textarea>
                                    @error('description.'.$language->code)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Describe your product in detail (supports rich text
                                        formatting)</div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent border-bottom py-3">
                    <h3 class="h5 mb-0 d-flex align-items-center">
                        <i class="bi bi-tag me-2 text-primary"></i> Thumbnail Image
                    </h3>
                </div>
                <div class="card-body">
                    @include('admin.partials.thumbnail-uploader', ['product' => $product])
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent border-bottom py-3">
                    <h3 class="h5 mb-0 d-flex align-items-center">
                        <i class="bi bi-tag me-2 text-primary"></i> Service Images
                    </h3>
                </div>
                <div class="card-body">
                    @include('admin.partials.media-uploader', ['product' => $product])
                </div>
            </div>


            <!-- STORE -->

            <div class="card border-0 mb-3">
                <div class="card-header bg-transparent py-3">
                    <h3 class="fs-5 d-flex align-items-center">
                        <i class="bi bi-shop me-2 text-primary"></i> Store Availability
                    </h3>
                    <small class="text-muted">Stores where this service is available</small>
                </div>
            </div>
            <div>
                <div class="row">
                    @foreach($channels as $channel)
                    <div class="col-md-6 col-lg-4 mt-3">
                        <div class="card text-center relative border-1">
                            <div class="form-check form-switch position-absolute">
                                <input class="form-check-input" name="channels[{{ $channel->id }}][enabled]"
                                    type="checkbox" value="1"
                                    {{ $product->channels->contains($channel->id) ? 'checked' : '' }} role="switch">
                            </div>

                            <div class="store-image-container">
                                @if($channel->image_url)
                                <img class="card-img-top object-fit-cover h-100" src="{{ $channel->image_url }}"
                                    alt="Cover Image">
                                @else
                                <div class="d-flex align-items-center justify-content-center text-muted bg-light h-100">
                                    <i class="bi bi-shop store-icon fs-3"></i>
                                </div>
                                @endif
                            </div>

                            <div class="card-body p-2 py-3">
                                <h3 class="m-0 mb-1 fs-6">
                                    <a class="card-btn text-decoration-none" target="_blank"
                                        href="{{ route('stores.edit', $channel->id) }}">{{ $channel->name }}</a>
                                </h3>
                                <div class="mt-2">
                                    <p class="text-secondary m-0 small">{{ $channel->address ?? '' }}</p>
                                </div>
                            </div>
                            <div class="p-2 border-top">
                                <a href="{{ route('stores.edit', $channel->id) }}" target="_blank"
                                    class="card-btn text-decoration-none fs-14">View store</a>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="channels[{{ $channel->id }}][]" value="{{ $channel->id }}">
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-5">

            <!-- Pricing -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent border-bottom py-3">
                    <h3 class="h5 mb-0 d-flex align-items-center">
                        <i class="bi bi-tag me-2 text-primary"></i> Service status
                    </h3>
                </div>
                <div class="card-body">
                    <div class="input-group">
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                            <option value="draft" {{ $product->status == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ $product->status == 'published' ? 'selected' : '' }}>Published
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Categories -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent border-bottom py-3">
                    <h3 class="h5 mb-0 d-flex align-items-center">
                        <i class="bi bi-tag me-2 text-primary"></i> Categories
                    </h3>
                </div>
                <div class="card-body">
                    <div class="category-list-container mb-3">
                        <div class="category-list">
                            <label class="form-label" for="category">Category</label>
                            <select class="form-select subcategory mb-3" name="category">
                                <option>Select Category</option>
                                @foreach($collections as $mainCategory)
                                @if($mainCategory->parent_id == null)
                                <option value="{{ $mainCategory->id }}"
                                    {{ $product->collections->contains('id', $mainCategory->id) ? 'selected' : '' }}>
                                    {{ $mainCategory->translateAttribute('name') }}
                                </option>
                                @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="subcategory-container">
                            @foreach($collections as $mainCategory)
                            @if($mainCategory->parent_id == null)
                            <div class="subcategory-list mb-3 no-d" data-target="{{ $loop->iteration }}">

                                <label class="form-label">Subcategory</label>
                                <select class="form-select subcategory-select mb-3" name="collection_id"
                                    data-target="{{ $loop->iteration }}">
                                    <option value="0">Select subcategory</option>
                                    @foreach($mainCategory->children as $subCategory)
                                    <option value="{{ $subCategory->id }}">
                                        {{ $subCategory->translateAttribute('name') }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent border-bottom py-3">
                    <h3 class="h5 mb-0 d-flex align-items-center">
                        <i class="bi bi-tag me-2 text-primary"></i> Pricing
                    </h3>
                </div>
                <div class="card-body">
                    <label class="form-label">Base Price</label>
                    <div class="input-group">
                        <span class="input-group-text">Eur</span>
                        <input type="number" class="form-control @error('price') is-invalid @enderror" name="price"
                            placeholder="0.00" step="0.01" value="{{ old('price',  $price) }}">
                        @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <label class="form-label mt-3" for="taxClass">Tax Class</label>
                    <div class="input-group">
                        <select class="form-select @error('tax_class_id') is-invalid @enderror" id="taxClass"
                            name="tax_class_id">
                            @foreach($taxClasses as $taxClass)
                            <option value="{{ $taxClass->id }}"
                                {{ (old('tax_class_id', $product->variants->first()->tax_class_id ?? '') == $taxClass->id) ? 'selected' : '' }}>
                                {{ $taxClass->name }} ({{ $taxClass->rate }}%)
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @error('tax_class_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <!-- Add this to your product edit view -->
            <div class="card mb-4 border-primary">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="h6 mb-0 py-2">Product variants</h3>
                    <button type="button" class="btn btn-sm btn-light add-variant-btn m-0">
                        <i class="bi bi-plus me-1"></i> Add variant
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="d-flex column flex-column" id="variantsContainer">
                        @foreach($variants as $variant)
                        @include('admin.products.variant', ['variant' => $variant, 'product' => $product])
                        @endforeach
                    </div>
                </div>
            </div>


            <!-- Add this to your layout file or this view -->
            @section('scripts')
            <script src="{{ asset('js/product-variants.js') }}"></script>
            @endsection
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent border-bottom py-3">
                    <h3 class="h5 mb-0 d-flex align-items-center">
                        <i class="bi bi-tag me-2 text-primary"></i> Service Filters
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="filter-card accordion" id="filterAccordion">
                        @foreach($filterCategories as $index => $filterCategory)
                        <div class="accordion-item border-0">
                            <h2 class="accordion-header" id="heading{{ $index }}">
                                <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}"
                                    aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                    aria-controls="collapse{{ $index }}">
                                    {{ $filterCategory->name }}
                                </button>
                            </h2>
                            <div id="collapse{{ $index }}"
                                class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                aria-labelledby="heading{{ $index }}" data-bs-parent="#filterAccordion">
                                <div class="accordion-body p-3">
                                    <div class="filter-grid">
                                        @foreach($filterCategory->options as $option)
                                        <label class="filter-option">
                                            <input type="checkbox" name="filters[{{ $filterCategory->id }}][]"
                                                value="{{ $option->id }}" @if($productHasFilterOption($option->id))
                                            checked
                                            @endif
                                            id="filter_{{ $filterCategory->id }}_{{ $option->id }}"
                                            >
                                            <span class="checkmark"></span>
                                            <span class="option-label">{{ $option->name }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="product_type_id" value="1">
    <input type="hidden" name="sub_category" value="{{ $sub_category }}">
</form>

@include('admin.products.variant-modal')


<script>
function deleteImage(mediaId) {
    if (confirm('Are you sure you want to delete this image?')) {
        fetch("{{ route('admin.products.media.destroy', $product) }}", {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                media_id: mediaId
            })
        }).then(() => {
            const buttons = document.querySelectorAll(`button[onclick="deleteImage(${mediaId})"]`);
            buttons.forEach(button => {
                button.closest('.image-preview-container').remove();
            });
            const thumbnailPreview = document.getElementById('thumbnail-preview');
            if (!thumbnailPreview) return; // Exit if element doesn't exist

            const previews = thumbnailPreview.querySelectorAll('.image-preview-container');

            if (previews.length >= 2) {
                previews[0].style.display = 'none';
            } else if (previews.length === 1) {
                previews[0].style.display = '';
            }

        });
    }
}
</script>
<script>
function deleteThumb(mediaId) {
    if (confirm('Are you sure you want to delete this image?')) {
        fetch("{{ route('admin.products.media.destroy', $product) }}", {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                media_id: mediaId,
                thumbnail: 1
            })
        }).then(() => {
            const buttons = document.querySelectorAll(`button[onclick="deleteImage(${mediaId})"]`);
            buttons.forEach(button => {
                button.closest('.image-preview-container').remove();
            });

        });
    }
}
document.addEventListener('DOMContentLoaded', function() {
    var subCategoryId = "{{ $sub_category }}";
    if (subCategoryId) {
        var subCategorySelects = document.querySelectorAll('select.subcategory-select');
        subCategorySelects.forEach(function(select) {
            for (var i = 0; i < select.options.length; i++) {
                if (select.options[i].value == subCategoryId) {
                    select.selectedIndex = i;
                    break;
                }
            }
        });
    }
});
</script>

@endsection