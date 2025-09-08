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
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="handle" class="form-label">{{ __('Handle') }}</label>
                        <input type="text" class="form-control @error('handle') is-invalid @enderror" id="handle" name="handle" value="{{ old('handle') }}" required>
                        @error('handle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Unique identifier for this discount</div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="type" class="form-label">{{ __('Discount Type') }}</label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="">{{ __('Select discount type') }}</option>
                            @foreach($discountTypes as $value => $label)
                                <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
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

            <div class="row" id="amount-off-fields" style="display: none;">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="discount_type" class="form-label">{{ __('Discount Value Type') }}</label>
                        <select class="form-select @error('discount_type') is-invalid @enderror" id="discount_type" name="discount_type">
                            <option value="percentage" {{ old('discount_type', 'percentage') == 'percentage' ? 'selected' : '' }}>{{ __('Percentage') }}</option>
                            <option value="fixed_value" {{ old('discount_type') == 'fixed_value' ? 'selected' : '' }}>{{ __('Fixed Amount') }}</option>
                        </select>
                        @error('discount_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3" id="percentage-field">
                        <label for="percentage" class="form-label">{{ __('Percentage') }}</label>
                        <div class="input-group">
                            <input type="number" class="form-control @error('percentage') is-invalid @enderror" id="percentage" name="percentage" value="{{ old('percentage') }}" min="0" max="100" step="0.01">
                            <span class="input-group-text">%</span>
                        </div>
                        @error('percentage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3" id="fixed-amount-field" style="display: none;">
                        <label for="fixed_amount" class="form-label">{{ __('Fixed Amount') }}</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control @error('fixed_amount') is-invalid @enderror" id="fixed_amount" name="fixed_amount" value="{{ old('fixed_amount') }}" min="0" step="0.01">
                        </div>
                        @error('fixed_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">{{ __('Save Discount') }}</button>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('type');
    const amountOffFields = document.getElementById('amount-off-fields');
    const discountTypeSelect = document.getElementById('discount_type');
    const percentageField = document.getElementById('percentage-field');
    const fixedAmountField = document.getElementById('fixed-amount-field');
    
    function toggleDiscountFields() {
        const selectedType = typeSelect.value;
        if (selectedType === 'Lunar\\DiscountTypes\\AmountOff') {
            amountOffFields.style.display = 'block';
        } else {
            amountOffFields.style.display = 'none';
        }
    }
    
    function toggleValueFields() {
        const selectedDiscountType = discountTypeSelect.value;
        if (selectedDiscountType === 'percentage') {
            percentageField.style.display = 'block';
            fixedAmountField.style.display = 'none';
        } else {
            percentageField.style.display = 'none';
            fixedAmountField.style.display = 'block';
        }
    }
    
    // Initial state
    toggleDiscountFields();
    toggleValueFields();
    
    // Event listeners
    typeSelect.addEventListener('change', toggleDiscountFields);
    discountTypeSelect.addEventListener('change', toggleValueFields);
});
</script>
@endsection