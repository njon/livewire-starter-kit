@extends('admin.app')

@section('toolbar')
    @include('admin.partials.buttons', [
        'title' => __('Edit Store'),
        'asset' => __('Store'),
        'buttons' => [['save' => true]],
        'custom_button' => '<button type="button" class="btn btn-danger" onclick="confirmDelete(\'Are you sure you want to delete this store?\', function() { document.getElementById(\'delete-store-form\').submit(); })"><i class="bi bi-trash me-1"></i> ' . __('Delete Store') . '</button>'
    ])
@endsection

@section('content')
<div class="container-fluid p-0">
    <script>
    const exampleData = {!! !empty($store->working_hours) ? $store->working_hours : json_encode([
        "monday" => ["active" => true, "open" => "09:00", "close" => "21:00"],
        "tuesday" => ["active" => true, "open" => "09:00", "close" => "21:00"],
        "wednesday" => ["active" => true, "open" => "09:00", "close" => "21:00"],
        "thursday" => ["active" => true, "open" => "09:00", "close" => "21:00"],
        "friday" => ["active" => true, "open" => "09:00", "close" => "21:00"],
        "saturday" => ["active" => true, "open" => "09:00", "close" => "21:00"],
        "sunday" => ["active" => true, "open" => "10:00", "close" => "18:00"]
    ]) !!};
    </script>
    <link rel="stylesheet" href="/css/sldr.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.js"></script>
    <form method="POST" enctype="multipart/form-data" action="{{ route('stores.update', $store->id) }}" class="submit-form">
        @csrf
        @method('PUT')
        
        <div class="row g-4">
            <!-- Left Column - Store Details -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-lg mb-4">
                    @include('admin.partials.form-header', [
                        'title' => __('Store Information'),
                        'description' => __('Manage your store details and settings'),
                        'icon' => 'bi-shop'
                    ])

                    <div class="card-body p-4">
                        <!-- Language Tabs -->
                        <div class="mb-4">
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
                        </div>
                    

                        <!-- Tab Content -->
                        <div class="tab-content" id="languageTabsContent">
                            @foreach($languages as $language)
                            <div class="tab-pane fade @if($loop->first) show active @endif" 
                                 id="{{ $language->code }}-content" role="tabpanel">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <label for="channel_title_{{ $language->code }}" class="form-label">
                                            <i class="bi bi-shop me-2 text-primary"></i>
                                            {{ __('Store Name') }} {!! lang_icon($language->code) !!}
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('name.'.$language->code) is-invalid @enderror"
                                               id="channel_title_{{ $language->code }}" 
                                               name="attribute_data[name][{{ $language->code }}]"
                                               value="{{ $store->attribute_data['name'][$language->code] ?? '' }}"
                                               placeholder="{{ __('Store Name') }}"
                                               @if($language->default) required @endif>
                                        @error('name.'.$language->code)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Contact & Location Info -->
                <div class="card border-0 shadow-lg">
                    @include('admin.partials.form-header', [
                        'title' => __('Contact & Location'),
                        'description' => __('Store contact information and address'),
                        'icon' => 'bi-geo-alt'
                    ])

                    <div class="card-body p-3">
                        <div class="row g-4">
                            <div class="col-md-6 mt-0">
                                <label for="phone" class="form-label">
                                    <i class="bi bi-telephone me-2 text-primary"></i>
                                    {{ __('Phone Number') }}
                                </label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="{{ $store->phone }}" placeholder="{{ __('Phone Number') }}">
                            </div>
                            <div class="col-md-6  mt-0">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope me-2 text-primary"></i>
                                    {{ __('Email Address') }}
                                </label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="{{ $store->email }}" placeholder="{{ __('Email Address') }}">
                            </div>
                            <div class="col-12">
                                <label for="address" class="form-label">
                                    <i class="bi bi-geo-alt me-2 text-primary"></i>
                                    {{ __('Store Address') }}
                                </label>
                                <input type="text" class="form-control" id="address" name="address" 
                                       value="{{ $store->address }}" placeholder="{{ __('Store Address') }}">
                                <div class="form-text mt-2">
                                    <i class="bi bi-info-circle me-1"></i>
                                    {{ __('Start typing to search for address suggestions') }}
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="position-relative">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="bi bi-map text-primary me-2"></i>
                                        <h6 class="mb-0">{{ __('Store Location') }}</h6>
                                    </div>
                                    <div class="map-container border rounded-3 overflow-hidden shadow-sm">
                                        <div id="map" style="height: 350px; width: 100%;"></div>
                                    </div>
                                    <input type="hidden" id="map_location" name="map_location" value="{{ $store->map_location }}">
                                    <div class="mt-3 p-3 bg-light rounded-3">
                                        <div class="d-flex align-items-center text-muted small">
                                            <i class="bi bi-cursor me-2"></i>
                                            {{ __('Click on the map or drag the marker to set the exact store location') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Business Hours -->
            <div class="col-lg-5">
                <div class="sticky-top" style="top: 1rem;">
                    <div class="card border-0 shadow-lg">
                        @include('admin.partials.form-header', [
                            'title' => __('Business Hours'),
                            'description' => __('Set your store operating hours'),
                            'icon' => 'bi-clock'
                        ])

                        <div class="card-body p-4">
                            <div class="mb-3">
                                <div class="d-flex align-items-center text-muted small mb-3">
                                    <i class="bi bi-info-circle me-2"></i>
                                    {{ __('Configure when your store is open for business') }}
                                </div>
                                <div id="business-hours-container" class="business-hours-modern"></div>
                            </div>
                            <input type="hidden" name="working_hours" id="json-output" value="{{ $store->working_hours }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCgEzqtsDF9vccmavcM9nqftFqXSgASHGE&libraries=places&callback=initMap"  defer></script>
<script>
// Modern form enhancements
document.addEventListener('DOMContentLoaded', function() {
    // Add floating label animations
    const formControls = document.querySelectorAll('.form-floating .form-control');
    formControls.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        input.addEventListener('blur', function() {
            if (!this.value) {
                this.parentElement.classList.remove('focused');
            }
        });
    });

    // Smooth tab transitions
    const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');
    tabButtons.forEach(button => {
        button.addEventListener('shown.bs.tab', function(e) {
            // Add smooth transition effect
            const target = document.querySelector(e.target.getAttribute('data-bs-target'));
            target.style.opacity = '0';
            setTimeout(() => {
                target.style.opacity = '1';
            }, 50);
        });
    });
});

let map;
let marker;
let geocoder;
let autocomplete;
function initMap() {
    const defaultLocation = { lat: 37.9838, lng: 23.7275 };
    let initialLocation = defaultLocation;
    @if($store->map_location)
        let coords = "{{ $store->map_location }}".split(',');
        if (coords.length === 2) {
            initialLocation = { lat: parseFloat(coords[0]), lng: parseFloat(coords[1]) };
        }
    @endif
    map = new google.maps.Map(document.getElementById("map"), {
        center: initialLocation,
        zoom: 12,
    });
    geocoder = new google.maps.Geocoder();
    marker = new google.maps.Marker({
        position: initialLocation,
        map: map,
        draggable: true
    });
    autocomplete = new google.maps.places.Autocomplete(
        document.getElementById('address'),
        { types: ['geocode'] }
    );
    autocomplete.addListener('place_changed', function() {
        const place = autocomplete.getPlace();
        if (!place.geometry) {
            return;
        }
        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);
        }
        marker.setPosition(place.geometry.location);
        updateLocationField(place.geometry.location);
    });
    marker.addListener('dragend', function() {
        geocodePosition(marker.getPosition());
        updateLocationField(marker.getPosition());
    });
    map.addListener('click', function(e) {
        marker.setPosition(e.latLng);
        geocodePosition(e.latLng);
        updateLocationField(e.latLng);
    });
}
function geocodePosition(pos) {
    geocoder.geocode({
        location: pos
    }, function(results, status) {
        if (status === 'OK') {
            if (results[0]) {
                document.getElementById('address').value = results[0].formatted_address;
            }
        }
    });
}
function updateLocationField(latLng) {
    document.getElementById('map_location').value = `${latLng.lat()},${latLng.lng()}`;
}
document.getElementById('address').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
    }
});
</script>

<!-- Hidden Delete Form -->
<form id="delete-store-form" action="{{ route('stores.destroy', $store->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function updateLocationField(latLng) {
    document.getElementById('map_location').value = `${latLng.lat()},${latLng.lng()}`;
}
document.getElementById('address').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
    }
});
</script>

<!-- Hidden Delete Form -->
<form id="delete-store-form" action="{{ route('stores.destroy', $store->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection
