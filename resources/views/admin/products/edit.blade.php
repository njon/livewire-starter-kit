@extends('admin.app')

@section('toolbar')
@include('admin.partials.buttons', [
'title' => __('Edit service'),
'asset' => __('Service'),
'buttons' => [
['save' => true]
],
'custom_button' => '<button type="button" class="btn btn-danger" onclick="confirmDelete(\'Are you sure you want to delete this product?\', function() { document.getElementById(\'delete-product-form\').submit(); })"><i class="bi bi-trash me-1"></i> Delete Product</button>'
])
@endsection

@section('content')

<form action="{{ route('admin.products.media.store', $product) }}" method="POST" id="media-dropzone">
    <small class="text-black small">{{ __('Files being uploaded...') }}</small>
    @csrf
    @method('PUT')
</form>

<form action="{{ route('admin.products.media.store', $product) }}" method="POST" id="thumbnail-dropzone">
    <small class="text-black small">{{ __('Files being uploaded...') }}</small>
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
                        <i class="bi bi-card-text me-2 text-primary"></i> {{ __('Service Details') }}
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Language Tabs -->
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-translate text-muted me-2"></i>
                        <small class="text-muted">{{ __('Select language to edit store details') }}</small>
                    </div>
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
                                    <label for="product_title_{{ $language->code }}" class="form-label">
                                        <i class="bi bi-card-text me-2 text-primary"></i>
                                        {{ __('Service title') }}
                                    </label>
                                    <input type="text" data-slug="true"
                                        class="form-control @error('name.'.$language->code) is-invalid @enderror"
                                        id="product_title_{{ $language->code }}" name="name[{{ $language->code }}]"
                                        value="{{ old('name.'.$language->code, $product->translateAttribute('name', $language->code) ?? '') }}"
                                        placeholder="{{ __('Service title') }}"
                                        @if($language->default) required @endif>
                                    @error('name.'.$language->code)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="bi bi-link me-2 text-primary"></i>
                                        {{ __('URL') }}
                                    </label>
                                    <input type="text" id="product_url_{{ $language->code }}" data-auto="true"
                                        class="form-control url-field @error('urls.'.$language->code) is-invalid @enderror"
                                        name="urls[{{ $language->code }}]" data-lang="{{ $language->code }}"
                                        placeholder="{{ __('Product URL') }}"
                                        value="{{ old('urls.'.$language->code, $url->slug ?? '') }}">
                                    @error('urls.'.$language->code)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-lg-12">
                                    <label class="form-label mb-1">{{ __('Service Description') }}</label>
                                    <textarea id="productDescription_{{ $language->code }}"
                                        name="description[{{ $language->code }}]"
                                        class="rich-text-editor border rounded bg-light @error('description.'.$language->code) is-invalid @enderror"
                                        data-lang="{{ $language->code }}">{{ old('description.'.$language->code, $product->translateAttribute('description', $language->code) ?? '') }}</textarea>
                                    @error('description.'.$language->code)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">{{ __('Describe your product in detail (supports rich text formatting)') }}</div>
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
                        <i class="bi bi-tag me-2 text-primary"></i> {{ __('Thumbnail Image') }}
                    </h3>
                </div>
                <div class="card-body">
                    @include('admin.partials.thumbnail-uploader', ['product' => $product])
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent border-bottom py-3">
                    <h3 class="h5 mb-0 d-flex align-items-center">
                        <i class="bi bi-tag me-2 text-primary"></i> {{ __('Service Images') }}
                    </h3>
                </div>
                <div class="card-body">
                    @include('admin.partials.media-uploader', ['product' => $product])
                </div>
            </div>


           
        </div>

        <div class="col-lg-5">

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent border-bottom py-3">
                    <h3 class="h5 mb-0 d-flex align-items-center">
                        <i class="bi bi-tag me-2 text-primary"></i> {{ __('Service status') }}
                    </h3>
                </div>
                <div class="card-body">
                    <label for="status" class="form-label">
                        <i class="bi bi-toggle-on me-2 text-primary"></i>
                        {{ __('Service Status') }}
                    </label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                        <option value="draft" {{ $product->status == 'draft' ? 'selected' : '' }}>{{ __('Draft') }}</option>
                        <option value="published" {{ $product->status == 'published' ? 'selected' : '' }}>{{ __('Published') }}</option>
                    </select>
                    @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

             <!-- STORE -->

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="fs-5 mb-1">
                                <i class="bi bi-shop me-2 text-primary"></i> {{ __('Store Availability') }}
                            </h3>
                            <p class="text-muted small mb-0">{{ __('Stores where this service is available') }}</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($channels as $channel)
                        <div class="list-group-item p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        @if($channel->image_url)
                                        <img src="{{ $channel->image_url }}" alt="{{ __('Store Image') }}" class="rounded" width="40" height="40">
                                        @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="bi bi-shop text-muted"></i>
                                        </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ store_name($channel) }}</h6>
                                        <small class="text-muted">{{ $channel->address ?? '' }}</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="form-check form-switch me-3">
                                        <input class="form-check-input" name="channels[{{ $channel->id }}][enabled]" type="checkbox" value="1" {{ $product->channels->contains($channel->id) ? 'checked' : '' }} role="switch">
                                    </div>
                                    <a href="{{ route('stores.edit', $channel->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary">{{ __('View') }}</a>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="channels[{{ $channel->id }}][]" value="{{ $channel->id }}">
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Categories -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-transparent border-bottom py-3">
                    <h3 class="h5 mb-0 d-flex align-items-center">
                        <i class="bi bi-tag me-2 text-primary"></i> {{ __('Categories') }}
                    </h3>
                </div>
                <div class="card-body">
                    <div class="category-list-container mb-3">
                        <div class="category-list">
                            <label for="category" class="form-label">
                                <i class="bi bi-tag me-2 text-primary"></i>
                                {{ __('Category') }}
                            </label>
                            <select class="form-select subcategory mb-3" name="category" id="category">
                                <option>{{ __('Select Category') }}</option>
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

                                <label class="form-label">{{ __('Subcategory') }}</label>
                                <select class="form-select subcategory-select mb-3" name="collection_id"
                                    data-target="{{ $loop->iteration }}">
                                    <option value="0">{{ __('Select subcategory') }}</option>
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
                    <label for="price" class="form-label">
                        <i class="bi bi-currency-euro me-2 text-primary"></i>
                        {{ __('Base Price') }}
                    </label>
                    <input type="number" class="form-control @error('price') is-invalid @enderror" 
                           id="price" name="price" placeholder="{{ __('Base Price') }}"
                           value="{{ old('price',  $price) }}">
                    @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    
                    <label for="taxClass" class="form-label mt-3">
                        <i class="bi bi-percent me-2 text-primary"></i>
                        {{ __('Tax Class') }}
                    </label>
                    <select class="form-select @error('tax_class_id') is-invalid @enderror" id="taxClass"
                        name="tax_class_id">
                        @foreach($taxClasses as $taxClass)
                        <option value="{{ $taxClass->id }}"
                            {{ (old('tax_class_id', $product->variants->first()->tax_class_id ?? '') == $taxClass->id) ? 'selected' : '' }}>
                            {{ $taxClass->name }} ({{ $taxClass->rate }}%)
                        </option>
                        @endforeach
                    </select>
                    @error('tax_class_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>


            
            <!-- Add this to your product edit view -->
            <div class="card mb-4 border-primary">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="h6 mb-0 py-2">{{ __('Product variants') }}</h3>
                    <button type="button" class="btn btn-sm btn-light add-variant-btn m-0">
                        <i class="bi bi-plus me-1"></i> {{ __('Add variant') }}
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
                        <i class="bi bi-tag me-2 text-primary"></i> {{ __('Service Filters') }}
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
                                                value="{{ $option->id }}" @if(product_has_filter_options($product)['checker']($option->id)) checked @endif
                                                id="filter_{{ $filterCategory->id }}_{{ $option->id }}">
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

<!-- Hidden Delete Form -->
<form id="delete-product-form" action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection