@extends('admin.app')

@section('toolbar')
    @include('admin.partials.buttons', ['title' => 'Add Discount', 'asset' => 'Discount', 'buttons' => [
        ['save' => true]
    ]])
@endsection

@section('content')
<form method="POST" action="{{ route('admin.discounts.store') }}" class="submit-form">
    @csrf
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom py-3">
            <h3 class="h5 mb-0">{{ __('Add Discount') }}</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('Name') }}</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="coupon" class="form-label">{{ __('Coupon Code') }}</label>
                        <input type="text" class="form-control @error('coupon') is-invalid @enderror" id="coupon" name="coupon" value="{{ old('coupon') }}">
                        @error('coupon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Optional coupon code for this discount</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="discount_type" class="form-label">{{ __('Discount Percentage') }}</label>
                        <select class="form-select @error('discount_type') is-invalid @enderror" id="discount_type" name="discount_type" required>
                            <option value="">{{ __('Select discount percentage') }}</option>
                            <option value="5" {{ old('discount_type') == '5' ? 'selected' : '' }}>5%</option>
                            <option value="10" {{ old('discount_type') == '10' ? 'selected' : '' }}>10%</option>
                            <option value="15" {{ old('discount_type') == '15' ? 'selected' : '' }}>15%</option>
                            <option value="20" {{ old('discount_type') == '20' ? 'selected' : '' }}>20%</option>
                            <option value="25" {{ old('discount_type') == '25' ? 'selected' : '' }}>25%</option>
                            <option value="30" {{ old('discount_type') == '30' ? 'selected' : '' }}>30%</option>
                            <option value="35" {{ old('discount_type') == '35' ? 'selected' : '' }}>35%</option>
                            <option value="40" {{ old('discount_type') == '40' ? 'selected' : '' }}>40%</option>
                            <option value="50" {{ old('discount_type') == '50' ? 'selected' : '' }}>50%</option>
                            <option value="custom" {{ old('discount_type') == 'custom' ? 'selected' : '' }}>Custom</option>
                        </select>
                        @error('discount_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @error('percentage')
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
                                <option value="{{ $product->id }}" {{ in_array($product->id, old('product_ids', [])) ? 'selected' : '' }}>
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

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="starts_at" class="form-label">{{ __('Start Date') }}</label>
                        <input type="datetime-local" class="form-control @error('starts_at') is-invalid @enderror" id="starts_at" name="starts_at" value="{{ old('starts_at') }}" required>
                        @error('starts_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="ends_at" class="form-label">{{ __('End Date') }}</label>
                        <input type="datetime-local" class="form-control @error('ends_at') is-invalid @enderror" id="ends_at" name="ends_at" value="{{ old('ends_at') }}">
                        @error('ends_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Leave blank for no end date</div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="max_uses" class="form-label">{{ __('Maximum Uses') }}</label>
                        <input type="number" class="form-control @error('max_uses') is-invalid @enderror" id="max_uses" name="max_uses" value="{{ old('max_uses') }}" min="1">
                        @error('max_uses')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Leave blank for unlimited uses</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="priority" class="form-label">{{ __('Priority') }}</label>
                        <input type="number" class="form-control @error('priority') is-invalid @enderror" id="priority" name="priority" value="{{ old('priority', 0) }}" min="0" required>
                        @error('priority')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Higher numbers = higher priority</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" id="stop" name="stop" {{ old('stop') ? 'checked' : '' }}>
                            <label class="form-check-label" for="stop">
                                {{ __('Stop after this discount') }}
                            </label>
                            <div class="form-text">Prevents other discounts from applying after this one</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row" id="custom-percentage-field" style="display: none;">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="custom_percentage" class="form-label">{{ __('Custom Percentage') }}</label>
                        <div class="input-group">
                            <input type="number" class="form-control @error('custom_percentage') is-invalid @enderror" id="custom_percentage" name="custom_percentage" value="{{ old('custom_percentage') }}" min="0" max="100" step="0.01">
                            <span class="input-group-text">%</span>
                        </div>
                        @error('custom_percentage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">{{ __('Save Discount') }}</button>
</form>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    $('#product_ids').select2({
        width: '100%',
        placeholder: 'Select products',
        allowClear: true
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
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
    
    // Initial state
    toggleCustomPercentage();
    
    // Event listener
    discountTypeSelect.addEventListener('change', toggleCustomPercentage);
});
</script>
@endsection