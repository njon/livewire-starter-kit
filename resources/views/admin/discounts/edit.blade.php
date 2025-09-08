@extends('admin.app')

@section('toolbar')
    @include('admin.partials.buttons', ['title' => 'Edit Discount', 'asset' => 'Discount', 'buttons' => [
        ['save' => true]
    ]])
@endsection

@section('content')
<form method="POST" action="{{ route('admin.discounts.update', $discount) }}" class="submit-form">
    @csrf
    @method('PUT')
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom py-3 d-flex justify-content-between align-items-center">
            <h3 class="h5 mb-0">{{ __('Edit Discount') }}</h3>
            <button type="button" id="advanced-settings-btn" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-cog me-1"></i> Advanced Settings
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('Name') }}</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $discount->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="discount_type" class="form-label">{{ __('Discount Percentage') }}</label>
                        @php
                            $currentPercentage = $discount->data['percentage'] ?? 0;
                            $isCustom = !in_array($currentPercentage, [5, 10, 15, 20, 25, 30, 35, 40, 50]);
                            $selectedType = $isCustom ? 'custom' : $currentPercentage;
                        @endphp
                        <select class="form-select @error('discount_type') is-invalid @enderror" id="discount_type" name="discount_type" required>
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
                            <option value="custom" {{ old('discount_type', $selectedType) == 'custom' ? 'selected' : '' }}>Custom</option>
                        </select>
                        @error('discount_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @error('percentage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3" id="custom-percentage-field" style="display: {{ old('discount_type', $selectedType) == 'custom' ? 'block' : 'none' }};">
                        <label for="custom_percentage" class="form-label">{{ __('Custom Percentage') }}</label>
                        <div class="input-group">
                            <input type="number" class="form-control @error('custom_percentage') is-invalid @enderror" id="custom_percentage" name="custom_percentage" value="{{ old('custom_percentage', $isCustom ? $currentPercentage : '') }}" min="5" max="100">
                            <span class="input-group-text">%</span>
                        </div>
                        @error('custom_percentage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Date Range Picker -->
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3 date-input-group">
                        <label for="starts_at" class="form-label">Select Date</label>
                        <button type="button" class="btn btn-light w-100 text-start" id="date_range_btn">
                            <i class="bi bi-calendar-range text-muted pe-1"></i>
                            <span id="date_range_text" class="small">Click to add date range</span>
                        </button>
                    </div>
                </div>
                <div class="col">
                        <label for="starts_at" class="form-label">Start Date</label>

                    <div class="mb-3 input-group">
                        <input type="text" class="form-control datetime-field @error('starts_at') is-invalid @enderror" id="starts_at" name="starts_at" value="{{ old('starts_at', $discount->starts_at ? $discount->starts_at->format('Y-m-d H:i') : '') }}" required>
                        @error('starts_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col">
                    <div class="mb-3 date-input-group">
                        <label for="ends_at" class="form-label">End Date</label>
                        <input type="text" class="form-control datetime-field @error('ends_at') is-invalid @enderror" id="ends_at" name="ends_at" value="{{ old('ends_at', $discount->ends_at ? $discount->ends_at->format('Y-m-d H:i') : '') }}">
                        @error('ends_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="product_ids" class="form-label">{{ __('Products') }}</label>
                        <select class="form-select @error('product_ids') is-invalid @enderror" id="product_ids" name="product_ids[]" multiple>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ in_array($product->id, old('product_ids', [])) ? 'selected' : '' }} {{ in_array($product->id, $selectedProducts) ? 'selected' : '' }}>
                                    {{ $product->translateAttribute('name') ?? "Product {$product->id}" }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_ids')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Select products for this discount. Leave empty for all products.</div>
                    </div>
                </div>
            </div>

            <!-- Advanced Settings Section (Initially Hidden) -->
            <div id="advanced-settings" style="display: none;">
                <div class="border-top pt-3 mt-3">
                    <h5 class="mb-3 text-muted">Advanced Settings</h5>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="coupon" class="form-label">{{ __('Coupon Code') }}</label>
                                <input type="text" class="form-control @error('coupon') is-invalid @enderror" id="coupon" name="coupon" value="{{ old('coupon', $discount->coupon) }}">
                                @error('coupon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Optional coupon code for this discount</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="uses" class="form-label">{{ __('Current Uses') }}</label>
                                <input type="number" class="form-control" id="uses" value="{{ $discount->uses }}" readonly>
                                <div class="form-text">Read-only field</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="max_uses" class="form-label">{{ __('Maximum Uses') }}</label>
                                <input type="number" class="form-control @error('max_uses') is-invalid @enderror" id="max_uses" name="max_uses" value="{{ old('max_uses', $discount->max_uses) }}" min="1">
                                @error('max_uses')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Leave blank for unlimited uses</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="priority" class="form-label">{{ __('Priority') }}</label>
                                <input type="number" class="form-control @error('priority') is-invalid @enderror" id="priority" name="priority" value="{{ old('priority', $discount->priority) }}" min="0" required>
                                @error('priority')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Higher numbers = higher priority</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" id="stop" name="stop" {{ old('stop', $discount->stop) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="stop">
                                        {{ __('Stop after this discount') }}
                                    </label>
                                    <div class="form-text">Prevents other discounts from applying after this one</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">{{ __('Update Discount') }}</button>
</form>


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
    const advancedSettings = document.getElementById('advanced-settings');
    let advancedSettingsVisible = false;
    
    function toggleAdvancedSettings() {
        advancedSettingsVisible = !advancedSettingsVisible;
        if (advancedSettingsVisible) {
            advancedSettings.style.display = 'block';
            advancedSettingsBtn.innerHTML = '<i class="fas fa-times me-1"></i> Hide Advanced Settings';
            advancedSettingsBtn.classList.add('btn-secondary');
            advancedSettingsBtn.classList.remove('btn-outline-secondary');
        } else {
            advancedSettings.style.display = 'none';
            advancedSettingsBtn.innerHTML = '<i class="fas fa-cog me-1"></i> Advanced Settings';
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
</script>
@endsection