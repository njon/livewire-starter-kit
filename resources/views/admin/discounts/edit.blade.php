@extends('admin.app')

@section('toolbar')
    @include('admin.partials.buttons', ['title' => __('Edit Discount'), 'asset' => __('Discount'), 'buttons' => [
        ['save' => true]
    ], 'custom_button' => '<button type="button" class="btn btn-danger" onclick="confirmDelete(\'Are you sure you want to delete this discount?\', function() { document.getElementById(\'delete-discount-form\').submit(); })"><i class="bi bi-trash me-1"></i> ' . __('Delete Discount') . '</button>'])
@endsection

@section('content')
<div class="container-fluid p-0">
    <form method="POST" action="{{ route('admin.discounts.update', $discount) }}" class="submit-form">
        @csrf
        @method('PUT')
        
        <div class="row g-4">
            <!-- Left Column - Discount Details -->
            <div class="col-lg-12" id="left-column">
                <!-- Basic Discount Information -->
                <div class="card border-0 shadow-lg mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle p-2 me-3">
                                <i class="bi bi-percent fs-5"></i>
                            </div>
                            <div>
                                <h4 class="mb-0 fw-semibold fs-6">{{ __('Discount Information') }}</h4>
                                <small>{{ __('Configure your discount details and percentage') }}</small>
                            </div>
                        </div>
                        <button type="button" id="advanced-settings-btn" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-gear me-1"></i> {{ __('Advanced Settings') }}
                        </button>
                    </div>
                    
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-6 mt-0">
                                <label for="name" class="form-label">
                                    <i class="bi bi-tag me-2 text-primary"></i>
                                    {{ __('Discount Name') }}
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $discount->name) }}" 
                                       placeholder="{{ __('Enter discount name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6  mt-0">
                                <label for="discount_type" class="form-label">
                                    <i class="bi bi-percent me-2 text-primary"></i>
                                    {{ __('Discount Percentage') }}
                                </label>
                                @php
                                    $currentPercentage = $discount->data['percentage'] ?? 0;
                                    $isCustom = !in_array($currentPercentage, [5, 10, 15, 20, 25, 30, 35, 40, 50]);
                                    $selectedType = $isCustom ? 'custom' : $currentPercentage;
                                @endphp
                                <select class="form-select @error('discount_type') is-invalid @enderror" 
                                        id="discount_type" name="discount_type" required>
                                    <option value="">{{ __('Select discount percentage') }}</option>
                                    <option value="5" {{ old('discount_type', $selectedType) == '5' ? 'selected' : '' }}>5%</option>
                                    <option value="10" {{ old('discount_type', $selectedType) == '10' ? 'selected' : '' }}>10%</option>
                                    <option value="15" {{ old('discount_type', $selectedType) == '15' ? 'selected' : '' }}>15%</option>
                                    <option value="20" {{ old('discount_type', $selectedType) == '20' ? 'selected' : '' }}>20%</option>
                                    <option value="25" {{ old('discount_type', $selectedType) == '25' ? 'selected' : '' }}>25%</option>
                                    <option value="30" {{ old('discount_type', $selectedType) == '30' ? 'selected' : '' }}>30%</option>
                                    <option value="35" {{ old('discount_type', $selectedType) == '35' ? 'selected' : '' }}>35%</option>
                                    <option value="40" {{ old('discount_type', $selectedType) == '40' ? 'selected' : '' }}>40%</option>
                                    <option value="50" {{ old('discount_type', $selectedType) == '50' ? 'selected' : '' }}>50%</option>
                                    <option value="custom" {{ old('discount_type', $selectedType) == 'custom' ? 'selected' : '' }}>{{ __('Custom') }}</option>
                                </select>
                                @error('discount_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6" id="custom-percentage-field" style="display: {{ old('discount_type', $selectedType) == 'custom' ? 'block' : 'none' }};">
                                <label for="custom_percentage" class="form-label">
                                    <i class="bi bi-calculator me-2 text-primary"></i>
                                    {{ __('Custom Percentage') }}
                                </label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('custom_percentage') is-invalid @enderror" 
                                           id="custom_percentage" name="custom_percentage" 
                                           value="{{ old('custom_percentage', $isCustom ? $currentPercentage : '') }}" 
                                           min="5" max="100" placeholder="0">
                                    <span class="input-group-text">%</span>
                                </div>
                                @error('custom_percentage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Date Range Configuration -->
                <div class="card border-0 shadow-lg mb-4">
                    @include('admin.partials.form-header', [
                        'title' => __('Date Range'),
                        'description' => __('Set the validity period for this discount'),
                        'icon' => 'bi-calendar-range'
                    ])
                    
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-4 mt-0">
                                <label for="date_range_btn" class="form-label">
                                    <i class="bi bi-calendar-range me-2 text-primary"></i>
                                    {{ __('Quick Select') }}
                                </label>
                                <button type="button" class="btn btn-light w-100 text-start" id="date_range_btn">
                                    <i class="bi bi-calendar-range text-muted pe-1"></i>
                                    <span id="date_range_text" class="small">{{ __('Click to add date range') }}</span>
                                </button>
                            </div>
                            
                            <div class="col-md-4 mt-0">
                                <label for="starts_at" class="form-label">
                                    <i class="bi bi-calendar-check me-2 text-primary"></i>
                                    {{ __('Start Date') }}
                                </label>
                                <input type="text" class="form-control datetime-field @error('starts_at') is-invalid @enderror" 
                                       id="starts_at" name="starts_at" 
                                       value="{{ old('starts_at', $discount->starts_at ? $discount->starts_at->format('Y-m-d H:i') : '') }}" 
                                       placeholder="{{ __('Select start date') }}" required>
                                @error('starts_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mt-0">
                                <label for="ends_at" class="form-label">
                                    <i class="bi bi-calendar-x me-2 text-primary"></i>
                                    {{ __('End Date') }}
                                </label>
                                <input type="text" class="form-control datetime-field @error('ends_at') is-invalid @enderror" 
                                       id="ends_at" name="ends_at" 
                                       value="{{ old('ends_at', $discount->ends_at ? $discount->ends_at->format('Y-m-d H:i') : '') }}" 
                                       placeholder="{{ __('Select end date') }}">
                                @error('ends_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Selection -->
                <div class="card border-0 shadow-lg">
                    @include('admin.partials.form-header', [
                        'title' => __('Product Selection'),
                        'description' => __('Choose which products this discount applies to'),
                        'icon' => 'bi-box-seam'
                    ])
                    
                    <div class="card-body p-4">
                        <div class="product-grid mb-3">
                            @foreach($products as $product)
                            @php
                            $isSelected = in_array($product->id, old('product_ids', $selectedProducts ?? []));
                            @endphp

                            <div class="product-card {{ $isSelected ? 'selected' : 'disabled' }}" data-product-id="{{ $product->id }}">
                                <div class="toggle-container">
                                    <input type="checkbox" id="toggle-{{ $product->id }}" class="toggle-checkbox"
                                        {{ $isSelected ? 'checked' : '' }} name="product_ids[]" value="{{ $product->id }}">
                                    <label for="toggle-{{ $product->id }}" class="toggle-label"></label>
                                </div>
                                <span class="status-badge {{ $isSelected ? 'status-active' : 'status-inactive' }}">
                                    {{ $isSelected ? __('Active') : __('Inactive') }}
                                </span>

                                @if($product->category)
                                <span class="badge bg-success category-badge">{{ $product->category->name }}</span>
                                @endif

                                <img src="{{ $product->getThumbImage() ?? 'https://via.placeholder.com/500x500?text=No+Image' }}"
                                    class="card-img-top product-image p-3" alt="{{ $product->translateAttribute('name') ?? 'Product' }}">

                                <div class="card-body">
                                    <h5 class="card-title">{{ $product->translateAttribute('name') ?? "Product {$product->id}" }}</h5>
                                    <p class="store-name">{{ $product->channels->first()->name ?? '' }}</p>
                                    <span class="price">{{ $product->price }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Advanced Settings (Initially Hidden) -->
            <div class="col-lg-5 d-none" id="right-column">
                <div class="sticky-top" style="top: 1rem;">
                    <!-- Coupon Code -->
                    <div class="card border-0 shadow-lg mb-4 d-none">
                        @include('admin.partials.form-header', [
                            'title' => __('Coupon Code'),
                            'description' => __('Optional coupon code requirement'),
                            'icon' => 'bi-ticket-perforated'
                        ])
                        
                        <div class="card-body p-4">
                            <label for="coupon" class="form-label">
                                <i class="bi bi-ticket-perforated me-2 text-primary"></i>
                                {{ __('Coupon Code') }}
                            </label>
                            <input type="text" class="form-control @error('coupon') is-invalid @enderror" 
                                   id="coupon" name="coupon" value="{{ old('coupon', $discount->coupon) }}"
                                   placeholder="{{ __('Enter coupon code') }}">
                            @error('coupon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                {{ __('Leave empty for automatic discount') }}
                            </div>
                        </div>
                    </div>

                    <!-- Usage Limits -->
                    <div class="card border-0 shadow-lg mb-4">
                        @include('admin.partials.form-header', [
                            'title' => __('Usage Limits'),
                            'description' => __('Control how many times this discount can be used'),
                            'icon' => 'bi-graph-up'
                        ])
                        
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-6">
                                    <label for="uses" class="form-label">
                                        <i class="bi bi-eye me-2 text-primary"></i>
                                        {{ __('Current Uses') }}
                                    </label>
                                    <input type="number" class="form-control" id="uses" 
                                           value="{{ $discount->uses }}" readonly>
                                    <div class="form-text">{{ __('Read-only') }}</div>
                                </div>
                                <div class="col-6">
                                    <label for="max_uses" class="form-label">
                                        <i class="bi bi-hash me-2 text-primary"></i>
                                        {{ __('Maximum Uses') }}
                                    </label>
                                    <input type="number" class="form-control @error('max_uses') is-invalid @enderror" 
                                           id="max_uses" name="max_uses" 
                                           value="{{ old('max_uses', $discount->max_uses) }}" 
                                           min="1" placeholder="{{ __('Unlimited') }}">
                                    @error('max_uses')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Priority Settings -->
                    <div class="card border-0 shadow-lg">
                        @include('admin.partials.form-header', [
                            'title' => __('Priority & Behavior'),
                            'description' => __('Control discount priority and stacking behavior'),
                            'icon' => 'bi-arrow-up-circle'
                        ])
                        
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label for="priority" class="form-label">
                                    <i class="bi bi-arrow-up-circle me-2 text-primary"></i>
                                    {{ __('Priority') }}
                                </label>
                                <input type="number" class="form-control @error('priority') is-invalid @enderror" 
                                       id="priority" name="priority" 
                                       value="{{ old('priority', $discount->priority) }}" 
                                       min="0" required placeholder="0">
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">{{ __('Higher numbers = higher priority') }}</div>
                            </div>
                            
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="stop" name="stop" 
                                       {{ old('stop', $discount->stop) ? 'checked' : '' }}>
                                <label class="form-check-label" for="stop">
                                    <i class="bi bi-stop-circle me-2"></i>
                                    {{ __('Stop after this discount') }}
                                </label>
                                <div class="form-text">{{ __('Prevents other discounts from applying after this one') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Scripts and Styles -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {

    $('#product_ids').select2({
        width: '100%',
        placeholder: 'Select products',
        allowClear: true
    });

    // Initialize date range picker
    $('#date_range_btn').daterangepicker({
        timePicker: true,
        timePicker24Hour: true,
        timePickerIncrement: 5,
        singleDatePicker: false,
        autoUpdateInput: false,
        locale: {
            format: 'YYYY-MM-DD HH:mm',
            cancelLabel: 'Clear'
        }
    });

    // Set up event handlers for the date range picker
    $('#date_range_btn').on('apply.daterangepicker', function(ev, picker) {
        $('#starts_at').val(picker.startDate.format('YYYY-MM-DD HH:mm'));
        $('#ends_at').val(picker.endDate.format('YYYY-MM-DD HH:mm'));
    });

    $('#date_range_btn').on('cancel.daterangepicker', function(ev, picker) {
        $('#starts_at').val('');
        $('#ends_at').val('');
    });

    // Toggle custom percentage field
    const discountTypeSelect = document.getElementById('discount_type');
    const customPercentageField = document.getElementById('custom-percentage-field');
    
    function toggleCustomPercentage() {
        const selectedType = discountTypeSelect.value;
        if (selectedType === 'custom') {
            customPercentageField.style.display = 'block';
        } else {
            customPercentageField.style.display = 'none';
        }
    }
    
    // Toggle advanced settings
    const advancedSettingsBtn = document.getElementById('advanced-settings-btn');
    const leftColumn = document.getElementById('left-column');
    const rightColumn = document.getElementById('right-column');
    let advancedSettingsVisible = false;
    
    function toggleAdvancedSettings() {
        advancedSettingsVisible = !advancedSettingsVisible;
        if (advancedSettingsVisible) {
            leftColumn.classList.remove('col-lg-12');
            leftColumn.classList.add('col-lg-7');
            rightColumn.classList.remove('d-none');
            rightColumn.classList.add('col-lg-5');
            advancedSettingsBtn.innerHTML = '<i class="bi bi-x me-1"></i> {{ __('Hide Advanced Settings') }}';
            advancedSettingsBtn.classList.add('btn-secondary');
            advancedSettingsBtn.classList.remove('btn-outline-secondary');
        } else {
            leftColumn.classList.remove('col-lg-7');
            leftColumn.classList.add('col-lg-12');
            rightColumn.classList.add('d-none');
            rightColumn.classList.remove('col-lg-5');
            advancedSettingsBtn.innerHTML = '<i class="bi bi-gear me-1"></i> {{ __('Advanced Settings') }}';
            advancedSettingsBtn.classList.remove('btn-secondary');
            advancedSettingsBtn.classList.add('btn-outline-secondary');
        }
    }
    
    // Initial state
    toggleCustomPercentage();
    
    // Event listeners
    discountTypeSelect.addEventListener('change', toggleCustomPercentage);
    advancedSettingsBtn.addEventListener('click', toggleAdvancedSettings);

    // Open date picker when clicking on the icon
    $('.input-icon').click(function() {
        $(this).siblings('.datetime-field').focus();
    });

    // Initialize individual date fields
    $('#starts_at, #ends_at').daterangepicker({
        timePicker: true,
        timePicker24Hour: true,
        timePickerIncrement: 5,
        singleDatePicker: true,
        autoUpdateInput: false,
        locale: {
            format: 'YYYY-MM-DD HH:mm',
            cancelLabel: 'Clear'
        }
    });

    $('#starts_at, #ends_at').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY-MM-DD HH:mm'));
    });
});
document.addEventListener('DOMContentLoaded', function() {
            const toggleCheckboxes = document.querySelectorAll('.toggle-checkbox');
            
            toggleCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const productCard = this.closest('.product-card');
                    const statusBadge = productCard.querySelector('.status-badge');
                    const viewButton = productCard.querySelector('.view-product-btn');
                    
                    if (this.checked) {
                        productCard.classList.remove('disabled');
                        productCard.classList.add('selected');
                        statusBadge.classList.remove('status-inactive');
                        statusBadge.classList.add('status-active');
                        statusBadge.textContent = 'Active';
                        viewButton.style.backgroundColor = '#4a6bdf';
                        viewButton.style.pointerEvents = 'auto';
                    } else {
                        productCard.classList.add('disabled');
                        productCard.classList.remove('selected');
                        statusBadge.classList.remove('status-active');
                        statusBadge.classList.add('status-inactive');
                        statusBadge.textContent = 'Disabled';
                        viewButton.style.backgroundColor = '#a0a0a0';
                        viewButton.style.pointerEvents = 'none';
                    }
                });
            });
        });
</script>


<!-- Hidden Delete Form -->
<form id="delete-discount-form" action="{{ route('admin.discounts.destroy', $discount) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>


    <style>
        .header {
            margin-bottom: 20px;
            padding-bottom: 0;
            border-bottom: 1px solid #eaeaea;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 20px;
        }
        .product-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            position: relative;
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 1px solid #ddddddff;
        }
        .product-card:hover {
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        .product-card.disabled {
            opacity: 0.7;
            background-color: #f0f0f0;
        }
        .product-card.disabled .card-img-top {
            filter: grayscale(70%);
        }
        .product-image {
            height: 240px;
            object-fit: cover;
            width: 100%;
        }
        .card-body {
            padding: 15px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }
        .card-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
        }
        .store-name {
            font-size: 13px;
            color: #6c757d;
            margin-bottom: 10px;
        }
        .price {
            font-weight: 700;
            color: #2c3e50;
            margin-top: auto;
        }
        .toggle-container {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
        }
        .toggle-checkbox {
            display: none;
        }
        .toggle-label {
            width: 44px;
            height: 24px;
            background: #28a745;
            border-radius: 100px;
            cursor: pointer;
            position: relative;
            transition: background-color 0.3s;
            display: block;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .toggle-label:after {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            width: 20px;
            height: 20px;
            background: #fff;
            border-radius: 50%;
            transition: 0.3s;
        }
        .toggle-checkbox:checked + .toggle-label {
            background: #28a745;
        }
        .toggle-checkbox:not(:checked) + .toggle-label {
            background: #dc3545;
        }
        .toggle-checkbox:checked + .toggle-label:after {
            left: calc(100% - 2px);
            transform: translateX(-100%);
        }
        .toggle-checkbox:not(:checked) + .toggle-label:after {
            left: 2px;
        }
        .view-product-btn {
            background: #4a6bdf;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            font-size: 14px;
            margin-top: 12px;
            transition: all 0.3s;
            text-align: center;
            text-decoration: none;
            display: block;
        }
        .view-product-btn:hover {
            background: #3a56c7;
            color: white;
        }
        .disabled .view-product-btn {
            background: #a0a0a0;
            cursor: not-allowed;
        }
        .status-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-active {
            background: #d4edda;
            color: #155724;
        }
        .status-inactive { background: #f8d7da; color: #721c24; z-index: 1; }
        .card-footer {
            background: transparent;
            border-top: 1px solid #f1f1f1;
            padding: 12px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>

@endsection