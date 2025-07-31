@extends('admin.app')
@section('content')

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="product_type_id" value="1">
    <input type="hidden" name="status" value="draft">
    
<div class="dashboard-content">
    <div class="container-fluid px-4 py-3">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h4 fw-semibold text-dark mb-0">Product Management</h2>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary">Preview</button>
                <button type="submit" class="btn btn-primary">Save Product</button>
            </div>
        </div>

        <!-- Details Section -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-3">
                <h3 class="h5 mb-0 d-flex align-items-center">
                    <i class="bi bi-card-text me-2 text-primary"></i> Product Details
                </h3>
            </div>
            <div class="card-body">
                <!-- Language Tabs -->
                <ul class="nav nav-tabs mb-4" id="languageTabs" role="tablist">
                    @foreach($languages as $language)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if($loop->first) active @endif" id="{{ $language->code }}-tab" data-bs-toggle="tab" 
                                data-bs-target="#{{ $language->code }}-content" type="button" role="tab">
                            {{ $language->name }}
                            @if($language->default)
                            <span class="badge bg-primary ms-2">Default</span>
                            @endif
                        </button>
                    </li>
                    @endforeach
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="languageTabsContent">
                    @foreach($languages as $language)
                    <div class="tab-pane fade @if($loop->first) show active @endif" id="{{ $language->code }}-content" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-lg-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" 
                                           id="product_title_{{ $language->code }}" 
                                           name="name[{{ $language->code }}]"
                                           placeholder="Product Name"
                                           @if($language->default) required @endif>
                                    <label for="productName_{{ $language->code }}">Product Name ({{ $language->name }})</label>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <label class="form-label mb-1">Description ({{ $language->name }})</label>
                                <textarea id="productDescription_{{ $language->code }}" 
                                          name="description[{{ $language->code }}]"
                                          class="rich-text-editor border rounded bg-light"
                                          data-lang="{{ $language->code }}"></textarea>
                                <div class="form-text">Describe your product in detail (supports rich text formatting)</div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        

        <!-- Stores Section -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
                <h3 class="h5 mb-0 d-flex align-items-center">
                    <i class="bi bi-shop me-2 text-primary"></i> Store Availability
                </h3>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#storeModal" type="button">
                    <i class="bi bi-plus-circle me-1"></i> Add Store
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Store</th>
                                <th class="text-center">Status</th>
                                <th>Availability Period</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(1==2)
                            @foreach($channels as $channel)
                            <tr>
                                <td class="ps-4 fw-medium">{{ $channel->name }}</td>
                                <td class="text-center">
                                    <span class="badge bg-success bg-opacity-10 text-success">
                                        <i class="bi bi-check-circle-fill me-1"></i> Active
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">Jan 1, 2023 - Dec 31, 2023</small>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="bi bi-gear"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="#"><i class="bi bi-pencil me-2"></i> Edit</a></li>
                                            <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash me-2"></i> Remove</a></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <i class="bi bi-shop me-3"></i> No stores available.
                                </td>
                            </tr>
                            @endif

                        </tbody>
                    </table>
            <div id="selectedChannelsContainer"></div>

                </div>
            </div>
        </div>

        

        <!-- SEO & Categories Section -->
        <div class="row g-4 mb-4">
            <div class="col-lg-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-header bg-transparent border-bottom py-3">
                        <h3 class="h5 mb-0 d-flex align-items-center">
                            <i class="bi bi-link-45deg me-2 text-primary"></i> SEO URLs
                        </h3>
                    </div>
                    <div class="card-body">
                        @foreach($languages as $language)
                        <div class="mb-3">
                            <label class="form-label d-flex align-items-center gap-2">
                                <span class="fi fi-{{ $language->country_code }} fis rounded-circle"></span>
                                {{ $language->name }} URL
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <span class="fi fi-{{ $language->country_code }} fis me-1"></span>
                                </span>
                                <input type="text" class="form-control url-field" 
                                       name="urls[{{ $language->code }}]" 
                                       data-lang="{{ $language->code }}" 
                                       placeholder="product-name">
                                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="tooltip" title="Regenerate from name">
                                    <i class="bi bi-arrow-repeat"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-header bg-transparent border-bottom py-3">
                        <h3 class="h5 mb-0 d-flex align-items-center">
                            <i class="bi bi-diagram-3 me-2 text-primary"></i> Categories
                        </h3>
                    </div>
                    <div class="card-body">
                        <label class="form-label">Product Categories</label>
                        <select class="form-select" name="collections[]" multiple size="4">
                            @foreach($collections as $collection)
                            <option value="{{ $collection->id }}">{{ $collection->translateAttribute('name') }}</option>
                            @endforeach
                        </select>
                        <div class="form-text mt-1">Hold Ctrl/Cmd to select multiple categories</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Actions -->
        <div class="d-flex justify-content-between bg-light p-3 rounded">
            <button class="btn btn-outline-danger">
                <i class="bi bi-trash me-1"></i> Delete Product
            </button>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save me-1"></i> Save Changes
                </button>
            </div>
        </div>
    </div>
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
            <!-- Hidden container for selected channels -->
            
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
        <button type="submit" class="btn btn-success" form="mainProductForm">Save All</button>
    </div>
</div>

        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const channelForm = document.getElementById('channelForm');
    const container = document.getElementById('selectedChannelsContainer');
    const channelsTable = document.querySelector('table.table tbody');
    
    document.getElementById('addChannelBtn').addEventListener('click', function() {
        const channelId = document.getElementById('channelSelect').value;
        const channelName = document.getElementById('channelSelect').options[document.getElementById('channelSelect').selectedIndex].text;
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const enabled = document.getElementById('enabled').checked;
        
        if (!channelId) return;
        
        // Format dates for display
        const startDateDisplay = startDate ? new Date(startDate).toLocaleDateString('en-US', { 
            year: 'numeric', month: 'short', day: 'numeric' 
        }) : 'Not set';
        
        const endDateDisplay = endDate ? new Date(endDate).toLocaleDateString('en-US', { 
            year: 'numeric', month: 'short', day: 'numeric' 
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
        newRow.innerHTML = `
            <td class="ps-4 fw-medium">${channelName}</td>
            <td class="text-center">
                <span class="badge ${enabled ? 'bg-success bg-opacity-10 text-success' : 'bg-secondary bg-opacity-10 text-secondary'}">
                    <i class="bi ${enabled ? 'bi-check-circle-fill' : 'bi-x-circle-fill'} me-1"></i>
                    ${enabled ? 'Active' : 'Inactive'}
                </span>
            </td>
            <td>
                <small class="text-muted">${startDateDisplay} - ${endDateDisplay}</small>
            </td>
            <td class="text-end pe-4">
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-gear"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item edit-channel" href="#" data-channel-id="${channelId}">
                            <i class="bi bi-pencil me-2"></i> Edit
                        </a></li>
                        <li><a class="dropdown-item text-danger remove-channel" href="#" data-channel-id="${channelId}">
                            <i class="bi bi-trash me-2"></i> Remove
                        </a></li>
                    </ul>
                </div>
            </td>
        `;
        
        channelsTable.appendChild(newRow);
        
        // Reset form
        document.getElementById('channelSelect').value = '';
        document.getElementById('startDate').value = '';
        document.getElementById('endDate').value = '';
    });
    
    // Handle remove channel
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-channel')) {
            e.preventDefault();
            const channelId = e.target.getAttribute('data-channel-id');
            const row = e.target.closest('tr');
            
            // Remove hidden input
            document.querySelector(`input[name="channels[${channelId}][id]"]`).remove();
            
            // Remove table row
            row.remove();
        }
        
        // Handle edit channel
        if (e.target.classList.contains('edit-channel')) {
            e.preventDefault();
            const channelId = e.target.getAttribute('data-channel-id');
            const row = e.target.closest('tr');
            
            // Get channel data from hidden inputs
            const channelName = document.querySelector(`input[name="channels[${channelId}][name]"]`).value;
            const startDate = document.querySelector(`input[name="channels[${channelId}][start_date]"]`).value;
            const endDate = document.querySelector(`input[name="channels[${channelId}][end_date]"]`).value;
            const enabled = document.querySelector(`input[name="channels[${channelId}][enabled]"]`).value === '1';
            
            // Populate modal form
            document.getElementById('channelSelect').value = channelId;
            document.getElementById('startDate').value = startDate;
            document.getElementById('endDate').value = endDate;
            document.getElementById('enabled').checked = enabled;
            
            // Remove the old entry
            document.querySelector(`input[name="channels[${channelId}][id]"]`).remove();
            row.remove();
        }
    });
});
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icons/7.1.0/css/flag-icons.min.css">
<script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.2/tinymce.min.js"></script>

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
    document.getElementById('productName_en')?.addEventListener('input', function(e) {
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

    // Variant management
    document.getElementById('addVariantBtn').addEventListener('click', function() {
        const table = document.getElementById('variantsTable').getElementsByTagName('tbody')[0];
        const rowCount = table.rows.length;
        const newRow = table.insertRow();
        
        newRow.innerHTML = `
            <td class="ps-4">
                <input type="text" class="form-control form-control-sm" name="variants[${rowCount}][name]" placeholder="Variant name">
            </td>
            <td>
                <input type="text" class="form-control form-control-sm" name="variants[${rowCount}][sku]" placeholder="SKU">
            </td>
            <td>
                <div class="input-group input-group-sm">
                    <span class="input-group-text">$</span>
                    <input type="number" class="form-control" name="variants[${rowCount}][price]" placeholder="0.00" step="0.01">
                </div>
            </td>
            <td>
                <input type="number" class="form-control form-control-sm" name="variants[${rowCount}][stock]" placeholder="0">
            </td>
            <td class="text-end pe-4">
                <button class="btn btn-sm btn-outline-danger remove-variant" type="button">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;
        
        // Add remove functionality
        newRow.querySelector('.remove-variant').addEventListener('click', function() {
            table.deleteRow(newRow.rowIndex - 1);
        });
    });
});
</script>
@endsection