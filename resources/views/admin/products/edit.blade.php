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

<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#statusModal">
  Update Status
</button>




<!-- Button to open offcanvas -->
<button class="btn btn-outline-primary mb-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#shoppingCart" aria-controls="shoppingCart">
    Open Shopping Cart
</button>


<div class="offcanvas offcanvas-end" tabindex="-1" id="shoppingCart" aria-labelledby="shoppingCartLabel"
    aria-modal="true" role="dialog">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="shoppingCartLabel">Select categories</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-2">
        <div class="row">
            <div class="col-lg-12">
                <div class="row gx-0 category-list-container">
                    <div class="col-lg-12 pe-0 py-2 category-list">
                        <ul class="list-unstyled">
                            @foreach(\Lunar\Models\Collection::with(['defaultUrl', 'children.defaultUrl'])->get() as $mainCategory)
                            @if($mainCategory->parent_id == null)
                            <li class="main-category @if($loop->first) active @endif"
                                data-target="cat-{{ $loop->iteration }}"
                                data-image="https://animated-dollop-g5v44x4gg5fwq7-80.app.github.dev/images/{{ get_category_image($mainCategory->translateAttribute('name')) }}">
                                <a href="#">{{ $mainCategory->translateAttribute('name') }}</a>
                            </li>
                            @endif
                            @endforeach
                        </ul>
                    </div>

                    <div class="col-lg-12 ps-0 py-2 subcategory-container" style="display: none !important;">
                        @foreach(\Lunar\Models\Collection::with(['defaultUrl', 'children.defaultUrl'])->get() as $mainCategory)
                        @if($mainCategory->parent_id == null)
                        <div class="subcategory-group @if(!$loop->first) d-none @endif" id="cat-{{ $loop->iteration }}">
                            <ul class="list-unstyled">
                                @foreach($mainCategory->children as $subCategory)
                                <li>
                                    <a href="#"
                                        class="d-block w-100 px-3 py-2 text-body text-decoration-none hover-bg @if(request()->url() == url($subCategory->defaultUrl->slug)) active @endif">
                                        {{ $subCategory->translateAttribute('name') }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<div class="modal fade" tabindex="-1" id="statusModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title">Update Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
                <div class="mb-4">
                    <label class="form-label fw-bold mb-3">Status</label>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="status" id="status-published" value="published" wire:model.live="mountedActionsData.0.status">
                        <label class="form-check-label" for="status-published">
                            <span class="d-block fw-medium">Published</span>
                            <small class="text-muted d-block">This product will be available across all enabled customer groups and channels</small>
                        </label>
                    </div>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="status-draft" value="draft" wire:model.live="mountedActionsData.0.status">
                        <label class="form-check-label" for="status-draft">
                            <span class="d-block fw-medium">Draft</span>
                            <small class="text-muted d-block">This product will be hidden across all channels and customer groups</small>
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                    <span wire:loading.delay.default wire:target="callMountedAction" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                    <span wire:loading.remove wire:target="callMountedAction">Save changes</span>
                </button>
            </div>
        </div>
    </div>
</div>


<form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data"
    id="mainProductForm" class="submit-form">
    @csrf
    @method('PUT')
    <input type="hidden" name="product_type_id" value="{{ $product->product_type_id ?? 1 }}">
    <input type="hidden" name="status" value="{{ $product->status ?? 'draft' }}">


    <div class="card mb-4 border-0 shadow-sm">
        <div class="row">
            <div class="col-lg-8">
                <div class="col-lg-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom py-3">
                    <h3 class="h5 mb-0 d-flex align-items-center">
                        <i class="bi bi-tag me-2 text-primary"></i> Pricing
                    </h3>
                </div>
                <div class="card-body">
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
                                            value="{{ $option->id }}" @if($productHasFilterOption($option->id)) checked
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
    </div>


    <!-- Details Section -->
     <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom py-3">
                    <h3 class="h5 mb-0 d-flex align-items-center">
                        <i class="bi bi-card-text me-2 text-primary"></i> Service Details
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Language Tabs -->
                    <ul id="languageTabs" role="tablist">
                        Click to change language translations
                        @foreach($languages as $language)
                        <li><button class="nav-link @if($loop->first) active @endif" id="{{ $language->code }}-tab"
                                data-bs-toggle="tab" data-bs-target="#{{ $language->code }}-content" type="button" role="tab">
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
                        <div class="tab-pane fade @if($loop->first) show active @endif" id="{{ $language->code }}-content"
                            role="tabpanel">
                            <div class="row g-4">
                                <div class="col-lg-12">

                                    <label for="product_title_{{ $language->code }}" class="form-label">Service title</label>
                                    <input type="text" data-slug="true"
                                        class="form-control @error('name.'.$language->code) is-invalid @enderror"
                                        id="product_title_{{ $language->code }}" name="name[{{ $language->code }}]"
                                        value="{{ old('name.'.$language->code, $variant->translateAttribute('name', $language->code) ?? '') }}"
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
                                        <span class="input-group-text no-bg">
                                            <span id="slugCheckIcon"> </span>
                                        </span>

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
                                        data-lang="{{ $language->code }}">{{ old('description.'.$language->code, $variant->translateAttribute('description', $language->code) ?? '') }}</textarea>
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
</div>


            


            <div class="col-lg-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom py-3">
                    <h3 class="h5 mb-0 d-flex align-items-center">
                        <i class="bi bi-tag me-2 text-primary"></i> Pricing
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-12">
                            <label class="form-label">Base Price</label>
                            <div class="input-group">
                                <span class="input-group-text">Eur</span>
                                <input type="number" class="form-control @error('price') is-invalid @enderror"
                                    name="price" placeholder="0.00" step="0.01" value="{{ old('price',  $price) }}">
                                @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label" for="taxClass">Tax Class</label>
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
                        <!-- <div class="col-md-4">
                            <label class="form-label" for="productSku">SKU</label>
                            <input type="text" class="form-control @error('sku') is-invalid @enderror" id="productSku"
                                name="sku" placeholder="SKU"
                                value="{{ old('sku', $product->variants->first()->sku ?? '') }}">
                            @error('sku')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror -->
                            <!-- <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="track_inventory" id="trackInventory" 
                                {{ old('track_inventory', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="trackInventory">Track inventory</label>
                        </div> -->
                        <!-- </div> -->
                    </div>
                </div>
            </div>
        </div>
</div>

    <div class="row">
        <!-- Stores Section -->
        <div class="col-lg-6">
            <div class="card mb-4 border-0 shadow-sm">
                <div
                    class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h3 class="h5 mb-0 d-flex align-items-center">
                        <i class="bi bi-shop me-2 text-primary"></i> Store Availability
                    </h3>
                    <button class="btn btn-sm " data-bs-toggle="modal" data-bs-target="#storeModal" type="button">
                        <i class="bi bi-plus-circle me-1"></i> Add Store
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="storesTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Store</th>
                                    <th class="text-center">Status</th>
                                    <th>Availability Period</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($product->channels->count() > 0)
                                @foreach($product->channels as $channel)
                                @php
                                $pivot = $channel->pivot;
                                @endphp
                                <tr>
                                    <td class="ps-4 fw-medium">{{ $channel->name }}</td>
                                    <td class="text-center">
                                        <span
                                            class="badge {{ $pivot->enabled ? 'bg-success bg-opacity-10 text-success' : 'bg-secondary bg-opacity-10 text-secondary' }}">
                                            <i
                                                class="bi {{ $pivot->enabled ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }} me-1"></i>
                                            {{ $pivot->enabled ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            @if ($pivot->ends_at == '' && $pivot->starts_at == '')
                                            Always available
                                            @else
                                            {{ $pivot->starts_at ? $pivot->starts_at : 'Not set' }} -
                                            {{ $pivot->ends_at ? $pivot->ends_at : 'Not set' }}
                                            @endif
                                        </small>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a class="dropdown-item text-danger remove-channel" href="#"
                                            data-channel-id="{{ $channel->id }}">
                                            <i class="bi bi-trash me-2"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>

                        <div class="text-center text-muted py-5" id="no-stores"
                            style="display: {{ $product->channels->isEmpty() ? 'block' : 'none' }}">
                            <i class="bi bi-shop me-2"></i> Please choose stores where this product will be available.
                        </div>


                        <div id="selectedChannelsContainer">
                            @foreach($product->channels as $channel)
                            @php
                            $pivot = $channel->pivot;
                            @endphp
                            <input type="hidden" name="channels[{{ $channel->id }}][id]" value="{{ $channel->id }}">
                            <input type="hidden" name="channels[{{ $channel->id }}][name]" value="{{ $channel->name }}">
                            <input type="hidden" name="channels[{{ $channel->id }}][start_date]"
                                value="{{ $pivot->starts_at ? $pivot->starts_at : '' }}">
                            <input type="hidden" name="channels[{{ $channel->id }}][end_date]"
                                value="{{ $pivot->ends_at ? $pivot->ends_at : '' }}">
                            <input type="hidden" name="channels[{{ $channel->id }}][enabled]"
                                value="{{ $pivot->enabled ? 1 : 0 }}">
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Variants Section -->
        <div class="col-lg-6">
            <div class="card mb-4 border-0 shadow-sm">
                <div
                    class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h3 class="h5 mb-0 d-flex align-items-center">
                        <i class="bi bi-collection me-2 text-primary"></i> Product variants
                    </h3>
                    <button class="btn btn-sm btn-outline-primary" id="addVariantBtn" type="button">
                        <i class="bi bi-plus-circle me-1"></i> Add option
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0" id="variantsTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Title</th>
                                    <th>SKU</th>
                                    <th>Price</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $variants = $product->variants->skip(1); // Skip the default variant
                                @endphp


                                @foreach($variants as $index => $variant)
                                <tr>
                                    <td class="ps-4">
                                        <input type="hidden" name="variants[{{ $index }}][id]"
                                            value="{{ $variant->id }}">
                                        <input type="text" class="form-control form-control"
                                            name="variants[{{ $index }}][name][en]" placeholder="Product option"
                                            value="{{ old("variants.$index.name.en", $variant->translateAttribute('name', 'en')) }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control"
                                            name="variants[{{ $index }}][sku]" placeholder="SKU"
                                            value="{{ old("variants.$index.sku", $variant->sku) }}">
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">€</span>
                                            <input type="number" class="form-control"
                                                name="variants[{{ $index }}][price]" placeholder="0.00" step="0.01"
                                                value="{{ old("variants.$index.price", $variant->prices->first()->price ?? '') }}">
                                        </div>
                                    </td>

                                    <td class="text-end pe-4">
                                        <button class="btn btn-sm btn-outline-danger remove-variant" type="button">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="text-center text-muted py-5" id="no-variants"
                            style="display: {{ $variants->isEmpty() ? 'block' : 'none' }}">
                            <i class="bi bi-box-seam me-2"></i> No variants available
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="product_type_id" value="{{ $product->product_type_id ?? 1 }}">
    <input type="hidden" name="status" value="{{ $product->status ?? 'draft' }}">
</form>

<!-- Add Store Modal -->
<div class="modal fade" id="storeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Store Availability</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="channelForm">
                    <div class="mb-3">
                        <label class="form-label">Select Store</label>
                        <select class="form-select" id="channelSelect">
                            <option selected disabled>Choose a store</option>
                            @foreach($channels as $channel)
                            <option value="{{ $channel->id }}">{{ $channel->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Start Date</label>
                            <input type="datetime-local" class="form-control" id="startDate">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">End Date</label>
                            <input type="datetime-local" class="form-control" id="endDate">
                        </div>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="enabled" checked>
                        <label class="form-check-label" for="enableStore">Enable for this store</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="addChannelBtn">Add Store</button>
            </div>
        </div>
    </div>
</div>



<script>
function hideEmpty(rows, name) {
    if (rows == 0) {
        document.getElementById(name).style.display = '';
    } else {
        document.getElementById(name).style.display = 'none';
    }
}

function hideEmpty1(rows, name) {
    if (rows == 1) {
        document.getElementById(name).style.display = '';
    } else {
        document.getElementById(name).style.display = 'none';
    }
}
document.addEventListener('DOMContentLoaded', function() {
    const channelForm = document.getElementById('channelForm');
    const container = document.getElementById('selectedChannelsContainer');
    const channelsTable = document.querySelector('table.table tbody');

    document.getElementById('addChannelBtn').addEventListener('click', function() {
        const channelId = document.getElementById('channelSelect').value;
        const channelName = document.getElementById('channelSelect').options[document.getElementById(
            'channelSelect').selectedIndex].text;
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const enabled = document.getElementById('enabled').checked;

        if (!channelId) return;

        // Check if channel already exists
        const existingChannel = container.querySelector(`input[name="channels[${channelId}][id]"]`);
        if (existingChannel) {
            alert('This channel has already been added. Please delete first if you want to edit.');
            return;
        }

        // Format dates for display
        const startDateDisplay = startDate ? new Date(startDate).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        }) : 'Not set';

        const endDateDisplay = endDate ? new Date(endDate).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        }) : 'Not set';



        // Add hidden inputs to main form
        container.innerHTML += `
        <input type="hidden" name="channels[${channelId}][id]" value="${channelId}">
        <input type="hidden" name="channels[${channelId}][name]" value="${channelName}">
        <input type="hidden" name="channels[${channelId}][start_date]" value="${startDate}">
        <input type="hidden" name="channels[${channelId}][end_date]" value="${endDate}">
        <input type="hidden" name="channels[${channelId}][enabled]" value="${enabled ? 1 : 0}">
    `;

        // Add new row to the table
        const newRow = document.createElement('tr');
        newRow.setAttribute('data-channel-id',
            channelId); // Add data attribute for easier identification
        newRow.innerHTML = `
        <td class="ps-4 fw-medium">${channelName}</td>
        <td class="text-center">
            <span class="badge ${enabled ? 'bg-success bg-opacity-10 text-success' : 'bg-secondary bg-opacity-10 text-secondary'}">
                <i class="bi ${enabled ? 'bi-check-circle-fill' : 'bi-x-circle-fill'} me-1"></i>
                ${enabled ? 'Active' : 'Inactive'}
            </span>
        </td>
        <td><small class="text-muted">
            ${ (endDateDisplay == 'Not set' && startDateDisplay == 'Not set') ? 'Always available' : (startDateDisplay + ' - ' + endDateDisplay) }
        </small></td>
        <td class="text-end pe-4">
<a class="dropdown-item text-danger remove-channel" href="#" data-channel-id="${channelId}">
                        <i class="bi bi-trash me-2"></i>
                    </a>
        </td>
    `;

        channelsTable.appendChild(newRow);

        // Reset form and close modal
        document.getElementById('channelSelect').value = '';
        document.getElementById('startDate').value = '';
        document.getElementById('endDate').value = '';
        bootstrap.Modal.getInstance(document.getElementById('storeModal')).hide();
    });

    // Handle remove channel
    document.addEventListener('click', function(e) {
        const removeLink = e.target.closest('.remove-channel');

        if (removeLink) {
            e.preventDefault();
            const channelId = e.target.getAttribute('data-channel-id');
            const row = e.target.closest('tr');

            // Remove hidden input
            document.querySelector(`input[name="channels[${channelId}][id]"]`).remove();
            document.querySelector(`input[name="channels[${channelId}][name]"]`).remove();
            document.querySelector(`input[name="channels[${channelId}][start_date]"]`).remove();
            document.querySelector(`input[name="channels[${channelId}][end_date]"]`).remove();
            document.querySelector(`input[name="channels[${channelId}][enabled]"]`).remove();

            // Remove table row
            row.remove();
        }


        // Handle edit channel
        if (e.target.classList.contains('edit-channel')) {
            e.preventDefault();
            const channelId = e.target.getAttribute('data-channel-id');
            const row = e.target.closest('tr');

            // Get channel data from hidden inputs
            const channelName = document.querySelector(`input[name="channels[${channelId}][name]"]`)
                .value;
            const startDate = document.querySelector(`input[name="channels[${channelId}][start_date]"]`)
                .value;
            const endDate = document.querySelector(`input[name="channels[${channelId}][end_date]"]`)
                .value;
            const enabled = document.querySelector(`input[name="channels[${channelId}][enabled]"]`)
                .value === '1';

            // Populate modal form
            document.getElementById('channelSelect').value = channelId;
            document.getElementById('startDate').value = startDate;
            document.getElementById('endDate').value = endDate;
            document.getElementById('enabled').checked = enabled;

            // Remove the old entry
            document.querySelector(`input[name="channels[${channelId}][id]"]`).remove();
            row.remove();

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('storeModal'));
            modal.show();
        }


        storesTable = document.getElementById('storesTable');
        hideEmpty1(storesTable.rows.length, 'no-stores');
    });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {


    // Initialize TinyMCE for all language descriptions
    @foreach($languages as $language)
    tinymce.init({
        selector: '#productDescription_{{ $language->code }}',
        plugins: 'lists link image table help wordcount',
        toolbar: 'undo redo | formatselect | bold italic | \
                 alignleft aligncenter alignright alignjustify | \
                 bullist numlist outdent indent | link image | help',
        skin: 'oxide',
        height: 300,
        menubar: false,
        branding: false,
        statusbar: false,
        setup: function(editor) {
            editor.on('change', function() {
                editor.save();
            });
        }
    });
    @endforeach

    // Auto-generate URLs from default language product name
    document.getElementById('productName_{{ $defaultLanguage = $languages->firstWhere('default', true)->code }}')?.addEventListener('input', function(e) {
        const slug = e.target.value.toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/(^-|-$)/g, '');

        document.querySelectorAll('.url-field').forEach(field => {
            if (!field.value || field.dataset.autoGenerated === 'true') {
                field.value = slug;
                field.dataset.autoGenerated = 'true';
            }
        });
    });


    // Add remove functionality
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.remove-variant');
        if (btn) {
            const table = document.getElementById('variantsTable').getElementsByTagName('tbody')[0];
            const row = btn.closest('tr');
            row.parentNode.removeChild(row);
            hideEmpty(table.rows.length, 'no-variants');
        }
    });

    // Variant management
    document.getElementById('addVariantBtn').addEventListener('click', function() {
        const table = document.getElementById('variantsTable').getElementsByTagName('tbody')[0];
        const rowCount = table.rows.length;
        const newRow = table.insertRow();

        newRow.innerHTML = `
            <td class="ps-4">
                <input type="text" class="form-control form-control-sm" name="variants[${rowCount}][name][en]" placeholder="Product option">
            </td>
                        <td>
                <input type="text" class="form-control form-control-sm" name="variants[${rowCount}][sku]" placeholder="SKU">
            </td>
            <td>
                <div class="input-group input-group-sm">
                    <span class="input-group-text">€</span>
                    <input type="number" class="form-control" name="variants[${rowCount}][price]" placeholder="0.00" step="0.01">
                </div>
            </td>
            

            <td class="text-end pe-4">
                <button class="btn btn-sm btn-outline-danger remove-variant" type="button">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;

        hideEmpty(table.rows.length, 'no-variants');
    });
});
</script>
@endsection