@extends('admin.app')

@section('toolbar')
    @include('admin.partials.buttons', ['title' => 'Save Store', 'asset' => 'Store', 'buttons' => [
        ['save' => true]
    ]])
@endsection

@section('content')
<script>
const exampleData = {
    "monday": { "active": true, "open": "09:00", "close": "21:00" }, "tuesday": { "active": true, "open": "09:00", "close": "21:00" }, "wednesday": { "active": true, "open": "09:00", "close": "21:00" }, "thursday": { "active": true, "open": "09:00", "close": "21:00" }, "friday": { "active": true, "open": "09:00", "close": "21:00" }, "saturday": { "active": true, "open": "09:00", "close": "21:00" }, "sunday": { "active": true, "open": "10:00", "close": "18:00" }
};

</script>
<link rel="stylesheet" href="https://animated-dollop-g5v44x4gg5fwq7-80.app.github.dev/css/sldr.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.js"></script>
    <form method="POST" enctype="multipart/form-data" action="/admin/stores" class="submit-form">
                @csrf
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-3">
                <h3 class="h5 mb-0 d-flex align-items-center">
                    <i class="bi bi-card-text me-2 text-primary"></i> Store details
                </h3>
            </div>
            
            <div class="card-body">
                <ul id="languageTabs" role="tablist">
                    <p>Please select a language to edit the store details:</p>
                    @foreach($languages as $language)
                    <li>
                        <button class="m-0 nav-link @if($loop->first) active @endif" id="{{ $language->code }}-tab"
                            data-bs-toggle="tab" data-bs-target="#{{ $language->code }}-content" type="button"
                            role="tab">
                            {{ $language->name }}
                            {!! lang_icon($language->code) !!}
                        </button>
                    </li>
                    @endforeach
                </ul>

                <!-- Tab Content -->
                <div class="row">
                    <div class="tab-content col-6" id="languageTabsContent">
                        @foreach($languages as $language)
                        <div class="tab-pane fade @if($loop->first) show active @endif"
                            id="{{ $language->code }}-content" role="tabpanel">
                            <div class="row g-4">
                                <div class="col-lg-6">
                                    <label for="channel_title_{{ $language->code }}" class="form-label">Store title {!! lang_icon($language->code) !!}</label>
                                    <input type="text" data-slug="true"
                                        class="form-control @error('name.'.$language->code) is-invalid @enderror"
                                        id="channel_title_{{ $language->code }}" name="attribute_data[name][{{ $language->code }}]"
                                        value=""
                                        @if($language->default) required @endif>
                                    @error('name.'.$language->code)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-lg-6">
                                    <label class="form-label d-flex align-items-center gap-2">
                                        URL {!! lang_icon($language->code) !!}
                                    </label>
                                    <div class="input-group">
                                    
                                            <input type="text" id="channel_url_{{ $language->code }}" data-auto="true"
                                            class="form-control url-field @error('urls.'.$language->code) is-invalid @enderror"
                                            name="attribute_data[url][{{ $language->code }}]" data-lang="{{ $language->code }}"
                                            value="">
                                            <span class="input-group-text no-bg">
                                                <span id="slugCheckIcon"> </span> 
                                            </span>

                                        @error('urls.'.$language->code)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <label class="form-label mb-1">Store Description {!! lang_icon($language->code) !!}</label>
                                    <textarea id="channelDescription_{{ $language->code }}"
                                        name="attribute_data[description][{{ $language->code }}]"
                                        class="rich-text-editor border rounded bg-light @error('description.'.$language->code) is-invalid @enderror"
                                        data-lang="{{ $language->code }}"></textarea>
                                    @error('description.'.$language->code)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Describe your channel in detail (supports rich text
                                        formatting)</div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone">
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="website" class="form-label">Website</label>
                                <input type="text" class="form-control" id="website" name="website">
                            </div>
                            <div class="col-md-6 mt-3">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="address" name="address" placeholder="Start typing address...">
                                </div>
                            
                            <div class="col-md-12 mt-3">
                                
                                <div class="mb-3">
                                    <div id="map" style="height: 300px; width: 100%; background-color: #eee;"></div>
                                    <input type="hidden" id="map_location" name="map_location">
                                    <div class="mt-2 text-muted small">Drag the marker to adjust the exact location</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>







<div>
    <div class="card mb-4">
            <div class="card-header bg-transparent border-bottom py-3">
                <h3 class="h5 mb-0 d-flex align-items-center">
                    <i class="bi bi-card-text me-2 text-primary"></i> Business hours
                </h3>
            </div>
            <div class="card-body">
                <!-- Working Hours Section -->
                <div>
                    <div class="row g-3">
                        <div id="business-hours-container"> </div>
                    </div>
                </div>
                <input type="hidden" name="working_hours" id="json-output">
            </div>
        </div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCgEzqtsDF9vccmavcM9nqftFqXSgASHGE&libraries=places&callback=initMap"  defer></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize TinyMCE for all language descriptions
    @foreach($languages as $language)
    tinymce.init({
        selector: '#channelDescription_{{ $language->code }}',
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
});

    // Image Preview Functionality
    document.getElementById('images').addEventListener('change', function(e) {
        const previewContainer = document.querySelector('.image-preview');
        previewContainer.innerHTML = '';
        
        for (const file of e.target.files) {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        }
    });

    // Google Maps Functionality
    let map;
    let marker;
    let geocoder;
    let autocomplete;

    function initMap() {
        // Initialize map centered on a default location
        const defaultLocation = { lat: 37.9838, lng: 23.7275 }; 
        map = new google.maps.Map(document.getElementById("map"), {
            center: defaultLocation,
            zoom: 12,
        });

        // Initialize geocoder
        geocoder = new google.maps.Geocoder();

        // Add initial marker
        marker = new google.maps.Marker({
            position: defaultLocation,
            map: map,
            draggable: true
        });

        // Initialize autocomplete for address input
        autocomplete = new google.maps.places.Autocomplete(
            document.getElementById('address'),
            { types: ['geocode'] }
        );

        // When place is selected from autocomplete, update map
        autocomplete.addListener('place_changed', function() {
            const place = autocomplete.getPlace();
            if (!place.geometry) {
                return;
            }

            // Update map view
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }

            // Update marker position
            marker.setPosition(place.geometry.location);
            updateLocationField(place.geometry.location);
        });

        // When marker is dragged, update address field
        marker.addListener('dragend', function() {
            geocodePosition(marker.getPosition());
            updateLocationField(marker.getPosition());
        });

        // When map is clicked, move marker
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

    // Prevent form submit when hitting enter in address field
    document.getElementById('address').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
        }
    });
</script>
@endsection