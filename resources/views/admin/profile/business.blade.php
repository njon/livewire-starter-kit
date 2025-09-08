@extends('admin.app')

@section('toolbar')
@include('admin.partials.buttons', ['title' => 'Business details', 'asset' => 'Profile', 'buttons' => [
['save' => true]
]])
@endsection

@section('content')

<form method="POST" action="{{ route('admin.profile.business.update') }}" class="submit-form">
    @csrf

    <!-- Business Information Section -->
    <div class="card mb-4 border-0 shadow-sm" id="business-card">
        <div class="card-header bg-transparent border-bottom py-3" role="button" data-bs-toggle="collapse"
            data-bs-target="#business-collapse" aria-expanded="true">
            <h3 class="h5 mb-0 d-flex align-items-center justify-content-between">
                <span><i class="bi bi-building me-2 text-primary"></i> {{ __('Business Information') }}</span>
                <i class="bi bi-chevron-down collapse-icon"></i>
            </h3>
        </div>
        <div class="collapse show" id="business-collapse">
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <label for="legal_business_name" class="form-label">
                            {{ __('Legal Business Name') }} <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('legal_business_name') is-invalid @enderror"
                            id="legal_business_name" name="legal_business_name"
                            value="{{ old('legal_business_name', $profile->legal_business_name ?? '') }}"
                            placeholder="As registered with Γ.Ε.Μ.Η. (GEMI)">
                        @error('legal_business_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">{{ __('Official company name as registered with GEMI') }}</div>
                    </div>

                    <div class="col-lg-6">
                        <label for="dba_trading_name" class="form-label">{{ __('Trading Name (DBA)') }}</label>
                        <input type="text" class="form-control @error('dba_trading_name') is-invalid @enderror"
                            id="dba_trading_name" name="dba_trading_name"
                            value="{{ old('dba_trading_name', $profile->dba_trading_name ?? '') }}"
                            placeholder="If different from legal name">
                        @error('dba_trading_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">{{ __('Commercial name used for business operations') }}</div>
                    </div>

                    <div class="col-lg-6">
                        <label for="business_registration_number" class="form-label">
                            {{ __('GEMI Registration Number') }} <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                            class="form-control @error('business_registration_number') is-invalid @enderror"
                            id="business_registration_number" name="business_registration_number"
                            value="{{ old('business_registration_number', $profile->business_registration_number ?? '') }}"
                            placeholder="123456789">
                        @error('business_registration_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">{{ __('9-digit business registration number') }}</div>
                    </div>

                    <div class="col-lg-6">
                        <label for="tax_identification_number" class="form-label">
                            {{ __('Tax ID Number (AFM)') }} <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('tax_identification_number') is-invalid @enderror"
                            id="tax_identification_number" name="tax_identification_number"
                            value="{{ old('tax_identification_number', $profile->tax_identification_number ?? '') }}"
                            placeholder="123456789">
                        @error('tax_identification_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">{{ __('Greek Tax Identification Number (Α.Φ.Μ.)') }}</div>
                    </div>

                    <div class="col-lg-6">
                        <label for="business_structure" class="form-label">
                            {{ __('Business Structure') }} <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('business_structure') is-invalid @enderror"
                            id="business_structure" name="business_structure">
                            <option value="">{{ __('Select business type') }}</option>
                            <option value="AE"
                                {{ old('business_structure', $profile->business_structure ?? '') == 'AE' ? 'selected' : '' }}>
                                Α.Ε. (Ανώνυμη Εταιρεία) - Corporation
                            </option>
                            <option value="EPE"
                                {{ old('business_structure', $profile->business_structure ?? '') == 'EPE' ? 'selected' : '' }}>
                                Ε.Π.Ε. (Εταιρεία Περιορισμένης Ευθύνης) - Limited Liability Company
                            </option>
                            <option value="IKE"
                                {{ old('business_structure', $profile->business_structure ?? '') == 'IKE' ? 'selected' : '' }}>
                                Ι.Κ.Ε. (Ιδιωτική Κεφαλαιουχική Εταιρεία) - Private Company
                            </option>
                            <option value="OE"
                                {{ old('business_structure', $profile->business_structure ?? '') == 'OE' ? 'selected' : '' }}>
                                Ο.Ε. (Ομόρρυθμη Εταιρεία) - General Partnership
                            </option>
                            <option value="EE"
                                {{ old('business_structure', $profile->business_structure ?? '') == 'EE' ? 'selected' : '' }}>
                                Ε.Ε. (Ετερόρρυθμη Εταιρεία) - Limited Partnership
                            </option>
                            <option value="Sole"
                                {{ old('business_structure', $profile->business_structure ?? '') == 'Sole' ? 'selected' : '' }}>
                                Ατομική Επιχείρηση - Sole Proprietorship
                            </option>
                        </select>
                        @error('business_structure')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-6">
                        <label for="business_address" class="form-label">
                            {{ __('Registered Business Address') }} <span class="text-danger">*</span>
                        </label>
                        <input class="form-control @error('business_address') is-invalid @enderror"
                            id="business_address" name="business_address" rows="3"
                            placeholder="Enter the complete registered address in Greece" value="{{ old('business_address', $profile->business_address ?? '') }}">
                        @error('business_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">{{ __('Physical address registered with GEMI - Greece') }}</div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="button" class="btn btn-primary business-next" id="business-next-btn"
                        onclick="openNextCard('contact')">
                        {{ __('Next: Contact Information') }} <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Information Section -->
    <div class="card mb-4 border-0 shadow-sm" id="contact-card">
        <div class="card-header bg-transparent border-bottom py-3" role="button" data-bs-toggle="collapse"
            data-bs-target="#contact-collapse" aria-expanded="false">
            <h3 class="h5 mb-0 d-flex align-items-center justify-content-between">
                <span><i class="bi bi-person-circle me-2 text-primary"></i>
                    {{ __('Primary Contact Information') }}</span>
                <i class="bi bi-chevron-down collapse-icon"></i>
            </h3>
        </div>
        <div class="collapse" id="contact-collapse">
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <label for="primary_contact_name" class="form-label">
                            {{ __('Primary Contact Name') }} <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('primary_contact_name') is-invalid @enderror"
                            id="primary_contact_name" name="primary_contact_name"
                            value="{{ old('primary_contact_name', $profile->primary_contact_name ?? '') }}"
                            placeholder="Full name of primary contact">
                        @error('primary_contact_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-6">
                        <label for="contact_title" class="form-label">
                            {{ __('Contact Title/Position') }} <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('contact_title') is-invalid @enderror" id="contact_title"
                            name="contact_title">
                            <option value="">{{ __('Select title') }}</option>
                            <option value="Owner"
                                {{ old('contact_title', $profile->contact_title ?? '') == 'Owner' ? 'selected' : '' }}>
                                {{ __('Owner / Ιδιοκτήτης') }}
                            </option>
                            <option value="CEO"
                                {{ old('contact_title', $profile->contact_title ?? '') == 'CEO' ? 'selected' : '' }}>
                                {{ __('CEO / Διευθύνων Σύμβουλος') }}
                            </option>
                            <option value="Managing Director"
                                {{ old('contact_title', $profile->contact_title ?? '') == 'Managing Director' ? 'selected' : '' }}>
                                {{ __('Managing Director / Διευθυντής') }}
                            </option>
                            <option value="Finance Manager"
                                {{ old('contact_title', $profile->contact_title ?? '') == 'Finance Manager' ? 'selected' : '' }}>
                                {{ __('Finance Manager / Υπεύθυνος Οικονομικών') }}
                            </option>
                            <option value="Other"
                                {{ old('contact_title', $profile->contact_title ?? '') == 'Other' ? 'selected' : '' }}>
                                {{ __('Other / Άλλο') }}
                            </option>
                        </select>
                        @error('contact_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-6">
                        <label for="business_phone_number" class="form-label">
                            {{ __('Business Phone') }} <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fi fi-gr"></i> +30
                            </span>
                            <input type="tel" class="form-control @error('business_phone_number') is-invalid @enderror"
                                id="business_phone_number" name="business_phone_number"
                                value="{{ old('business_phone_number', $profile->business_phone_number ?? '') }}"
                                placeholder="210 1234567">
                            @error('business_phone_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text">{{ __('Greek business phone number') }}</div>
                    </div>

                    <div class="col-lg-6">
                        <label for="business_email_address" class="form-label">
                            {{ __('Business Email') }} <span class="text-danger">*</span>
                        </label>
                        <input type="email" class="form-control @error('business_email_address') is-invalid @enderror"
                            id="business_email_address" name="business_email_address"
                            value="{{ old('business_email_address', $profile->business_email_address ?? '') }}"
                            placeholder="info@company.gr">
                        @error('business_email_address')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">{{ __('Professional email address for business correspondence') }}
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-secondary next-btn" onclick="openPreviousCard('business')">
                        <i class="bi bi-arrow-left me-2"></i> {{ __('Previous: Business Details') }}
                    </button>
                    <button type="button" class="btn btn-primary business-next" id="contact-next-btn"
                        onclick="openNextCard('banking')">
                        {{ __('Next: Banking Information') }} <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Banking Information Section -->
    <div class="card mb-4 border-0 shadow-sm" id="banking-card">
        <div class="card-header bg-transparent border-bottom py-3" role="button" data-bs-toggle="collapse"
            data-bs-target="#banking-collapse" aria-expanded="false">
            <h3 class="h5 mb-0 d-flex align-items-center justify-content-between">
                <span><i class="bi bi-bank2 me-2 text-primary"></i> {{ __('Banking Information') }}</span>
                <i class="bi bi-chevron-down collapse-icon"></i>
            </h3>
        </div>
        <div class="collapse" id="banking-collapse">
            <div class="card-body">
                <div class="alert alert-info d-flex align-items-start mb-4">
                    <i class="bi bi-info-circle me-2 mt-1"></i>
                    <div>
                        <strong>{{ __('Security Notice') }}</strong><br>
                        {{ __('Banking information is encrypted and handled according to PCI DSS standards. All payments will be processed within Greece.') }}
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-6">
                        <label for="beneficiary_name" class="form-label">
                            {{ __('Beneficiary Name') }} <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('beneficiary_name') is-invalid @enderror"
                            id="beneficiary_name" name="beneficiary_name"
                            value="{{ old('beneficiary_name', $profile->beneficiary_name ?? '') }}"
                            placeholder="Must match legal business name">
                        @error('beneficiary_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">{{ __('Must exactly match your legal business name') }}</div>
                    </div>

                    <div class="col-lg-6">
                        <label for="bank_name" class="form-label">
                            {{ __('Bank Name') }} <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('bank_name') is-invalid @enderror" id="bank_name"
                            name="bank_name" value="{{ old('bank_name', $profile->bank_name ?? '') }}"
                            placeholder="e.g., National Bank of Greece, Alpha Bank">
                        @error('bank_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">{{ __('Greek bank name') }}</div>
                    </div>

                    <div class="col-lg-12">
                        <label for="iban" class="form-label">
                            {{ __('IBAN (Greek)') }} <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fi fi-gr"></i> GR
                            </span>
                            <input type="text" class="form-control @error('iban') is-invalid @enderror" id="iban"
                                name="iban" value="{{ old('iban', $profile->iban ?? '') }}"
                                placeholder="XX XXXX XXXX XXXX XXXX XXXX XXXX" maxlength="27">
                            @error('iban')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-text">{{ __('27-character Greek IBAN number (without GR prefix)') }}
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning d-flex align-items-start mt-4">
                    <i class="bi bi-exclamation-triangle me-2 mt-1"></i>
                    <div>
                        <strong>{{ __('Verification Process') }}</strong><br>
                        {{ __('We will verify your bank details through a secure verification process. This may include small test deposits that you will need to confirm.') }}
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-secondary next-btn" onclick="openPreviousCard('contact')">
                        <i class="bi bi-arrow-left me-2"></i> {{ __('Previous: Contact Information') }}
                    </button>
                    <button type="submit" class="btn btn-success" id="submit-btn">
                        <i class="bi bi-check-circle me-2"></i> {{ __('Save Business Profile') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Delete Form (hidden) -->
<form method="POST" action="#" id="delete-profile-form" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize collapse icons and check if all fields are filled
        updateCollapseIcons();
        checkAllFieldsFilled();
        // Auto-format IBAN input
        const ibanInput = document.getElementById('iban');
        if (ibanInput) {
            ibanInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/[^\dA-Z]/g, '').toUpperCase();
                let formattedValue = value.replace(/(.{4})/g, '$1 ').trim();
                if (formattedValue.length > 34) {
                    formattedValue = formattedValue.substring(0, 34);
                }
                e.target.value = formattedValue;
                checkAllFieldsFilled();
            });
        }
        // Auto-format phone number
        const phoneInput = document.getElementById('business_phone_number');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length >= 10) {
                    value = value.substring(0, 10);
                    e.target.value = value.replace(/(\d{3})(\d{3})(\d{4})/, '$1 $2 $3');
                }
                checkAllFieldsFilled();
            });
        }
        // Monitor all form inputs for auto-expand functionality
        document.querySelectorAll('input, select, textarea').forEach(function(input) {
            input.addEventListener('input', checkAllFieldsFilled);
            input.addEventListener('change', checkAllFieldsFilled);
        });
        // Update icons when collapse state changes
        document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(function(element) {
            element.addEventListener('click', function() {
                setTimeout(updateCollapseIcons, 300);
            });
        });
    });
    // Auto-fill beneficiary name from legal business name
    document.getElementById('legal_business_name').addEventListener('input', function() {
        const beneficiaryField = document.getElementById('beneficiary_name');
        if (beneficiaryField && !beneficiaryField.value) {
            beneficiaryField.value = this.value;
        }
    });
    // Card navigation functions
    function openNextCard(cardName) {
        const cardElement = document.getElementById(cardName + '-card');
        const collapseElement = document.getElementById(cardName + '-collapse');
        if (cardElement && collapseElement) {
            // Expand the target card
            const bsCollapse = new bootstrap.Collapse(collapseElement, {
                show: true
            });
            // Scroll to the card smoothly
            setTimeout(() => {
                cardElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start',
                    inline: 'nearest'
                });
            }, 300);
        }
        updateCollapseIcons();
    }

    function openPreviousCard(cardName) {
        const cardElement = document.getElementById(cardName + '-card');
        const collapseElement = document.getElementById(cardName + '-collapse');
        if (cardElement && collapseElement) {
            // Expand the target card
            const bsCollapse = new bootstrap.Collapse(collapseElement, {
                show: true
            });
            // Scroll to the card smoothly
            setTimeout(() => {
                cardElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start',
                    inline: 'nearest'
                });
            }, 300);
        }
        updateCollapseIcons();
    }
    // Update collapse icons based on state
    function updateCollapseIcons() {
        document.querySelectorAll('.collapse-icon').forEach(function(icon) {
            const targetId = icon.closest('[data-bs-target]').getAttribute('data-bs-target');
            const targetElement = document.querySelector(targetId);
            if (targetElement && targetElement.classList.contains('show')) {
                icon.classList.remove('bi-chevron-down');
                icon.classList.add('bi-chevron-up');
            } else {
                icon.classList.remove('bi-chevron-up');
                icon.classList.add('bi-chevron-down');
            }
        });
    }
    // Check if all required fields are filled and expand all cards
    function checkAllFieldsFilled() {
        const requiredFields = [
            'legal_business_name',
            'business_registration_number',
            'tax_identification_number',
            'business_structure',
            'business_address',
            'primary_contact_name',
            'contact_title',
            'business_phone_number',
            'business_email_address',
            'beneficiary_name',
            'bank_name',
            'iban'
        ];
        let allFilled = true;
        requiredFields.forEach(function(fieldId) {
            const field = document.getElementById(fieldId);
            if (field && (!field.value || field.value.trim() === '')) {
                allFilled = false;
            }
        });
        // If all fields are filled, expand all cards
        if (allFilled) {
            expandAllCards();
            // Add completion styling
            document.querySelectorAll('.card').forEach(function(header) {
                header.classList.add('success-card');
            });
            
        } else {
            // Remove completion styling
            document.querySelectorAll('.card').forEach(function(header) {
                header.classList.remove('success-card');
            });
            // Remove completion alert if it exists
            const alert = document.getElementById('completion-alert');
            if (alert) {
                alert.remove();
            }
        }
    }
    // Expand all cards
    function expandAllCards() {
        const cardIds = ['business-collapse', 'contact-collapse', 'banking-collapse', 'location-collapse'];
        cardIds.forEach(function(cardId) {
            const element = document.getElementById(cardId);
            if (element && !element.classList.contains('show')) {
                const bsCollapse = new bootstrap.Collapse(element, {
                    show: true
                });
            }
        });
        setTimeout(updateCollapseIcons, 300);
    }
</script>

@endsection