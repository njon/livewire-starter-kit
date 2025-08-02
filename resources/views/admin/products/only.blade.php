<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Your Store | LunarPHP</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #6366f1;
            --secondary-color: #f8f9fa;
            --success-color: #28a745;
        }
        
        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', Roboto, sans-serif;
        }
        
        .wizard-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .wizard-sidebar {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            color: white;
            padding: 2.5rem 1.5rem;
            height: 100%;
        }
        
        .wizard-step {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            margin-bottom: 0.5rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .wizard-step.active {
            background-color: rgba(255,255,255,0.15);
        }
        
        .wizard-step.completed {
            background-color: rgba(255,255,255,0.1);
        }
        
        .wizard-step-number {
            width: 32px;
            height: 32px;
            background-color: white;
            color: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-weight: bold;
            flex-shrink: 0;
        }
        
        .wizard-step.active .wizard-step-number {
            background-color: var(--success-color);
            color: white;
        }
        
        .wizard-step.completed .wizard-step-number {
            background-color: var(--success-color);
            color: white;
        }
        
        .wizard-step-title {
            font-weight: 500;
            margin-bottom: 0;
        }
        
        .wizard-content {
            padding: 2.5rem;
        }
        
        .section-title {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }
        
        .section-title .icon {
            background-color: #edf2f7;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            color: var(--primary-color);
        }
        
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #4a5568;
        }
        
        .required-field::after {
            content: '*';
            color: #e53e3e;
            margin-left: 4px;
        }
        
        .form-control, .form-select {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        }
        
        .help-text {
            font-size: 0.875rem;
            color: #718096;
            margin-top: 0.5rem;
        }
        
        .image-upload-area {
            border: 2px dashed #cbd5e0;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            background-color: #f8fafc;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .image-upload-area:hover {
            border-color: var(--primary-color);
            background-color: #f0f4ff;
        }
        
        .image-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        
        .image-preview-item {
            position: relative;
            width: 120px;
            height: 120px;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .image-preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .remove-image {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background-color: rgba(0,0,0,0.5);
            color: white;
            border: none;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .business-hour-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        
        .day-toggle {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .form-switch .form-check-input {
            width: 3em;
            height: 1.5em;
            margin-right: 0.75rem;
        }
        
        .time-inputs {
            display: flex;
            gap: 1rem;
        }
        
        .time-input-group {
            flex: 1;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 0.875rem 1.75rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .btn-primary:hover {
            background-color: #4f46e5;
            transform: translateY(-1px);
        }
        
        .btn-outline-secondary {
            padding: 0.875rem 1.75rem;
            border-radius: 8px;
            font-weight: 500;
        }
        
        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e2e8f0;
        }
        
        @media (max-width: 992px) {
            .wizard-sidebar {
                padding: 1.5rem;
            }
            
            .wizard-content {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="text-center mb-5">
                    <h1 class="fw-bold mb-3">List Your Store With Us</h1>
                    <p class="lead text-muted">Complete this simple form to join our marketplace</p>
                </div>
                
                <div class="wizard-container">
                    <div class="row g-0">
                        <!-- Sidebar Navigation -->
                        <div class="col-lg-4">
                            <div class="wizard-sidebar">
                                <h3 class="h4 mb-4">Store Details</h3>
                                
                                <div class="wizard-step active">
                                    <div class="wizard-step-number">1</div>
                                    <div>
                                        <h4 class="wizard-step-title">Basic Information</h4>
                                        <p class="small mb-0 opacity-75">Name, description, and URL</p>
                                    </div>
                                </div>
                                
                                <div class="wizard-step">
                                    <div class="wizard-step-number">2</div>
                                    <div>
                                        <h4 class="wizard-step-title">Contact Details</h4>
                                        <p class="small mb-0 opacity-75">Phone, email, and location</p>
                                    </div>
                                </div>
                                
                                <div class="wizard-step">
                                    <div class="wizard-step-number">3</div>
                                    <div>
                                        <h4 class="wizard-step-title">Opening Hours</h4>
                                        <p class="small mb-0 opacity-75">When are you open?</p>
                                    </div>
                                </div>
                                
                                <div class="wizard-step">
                                    <div class="wizard-step-number">4</div>
                                    <div>
                                        <h4 class="wizard-step-title">Store Photos</h4>
                                        <p class="small mb-0 opacity-75">Show customers your space</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Content -->
                        <div class="col-lg-8">
                            <div class="wizard-content">
                                <form method="POST" enctype="multipart/form-data" action="/admin/stores">
                                    <input type="hidden" name="_token" value="xbAGmP2GIRVxObyBtn9rcwSWEgHun8aHQ3mnUdxz">
                                    
                                    <!-- Step 1: Basic Information -->
                                    <div class="step-content active" id="step1">
                                        <h2 class="section-title">
                                            <span class="icon"><i class="bi bi-shop"></i></span>
                                            About Your Store
                                        </h2>
                                        
                                        <div class="mb-4">
                                            <label for="store_name" class="form-label required-field">Store Name</label>
                                            <input type="text" class="form-control" id="store_name" name="attribute_data[name][en]" required>
                                            <div class="help-text">What do you call your business? This will be displayed to customers.</div>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label for="store_url" class="form-label">Custom Store URL</label>
                                            <div class="input-group">
                                                <span class="input-group-text">ourmarketplace.com/</span>
                                                <input type="text" class="form-control" id="store_url" name="attribute_data[url][en]" placeholder="your-store-name">
                                            </div>
                                            <div class="help-text">Optional web address for your store profile</div>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label for="store_description" class="form-label">Description</label>
                                            <textarea class="form-control" id="store_description" name="attribute_data[description][en]" rows="5" placeholder="Tell customers about your store..."></textarea>
                                            <div class="help-text">What makes your store special? What products or services do you offer?</div>
                                        </div>
                                    </div>
                                    
                                    <!-- Step 2: Contact Information -->
                                    <div class="step-content" id="step2" style="display: none;">
                                        <h2 class="section-title">
                                            <span class="icon"><i class="bi bi-telephone-outbound"></i></span>
                                            Contact Information
                                        </h2>
                                        
                                        <div class="row g-3 mb-4">
                                            <div class="col-md-6">
                                                <label for="phone" class="form-label required-field">Phone Number</label>
                                                <input type="tel" class="form-control" id="phone" name="phone" required>
                                                <div class="help-text">Where customers can call you</div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="email" class="form-label required-field">Email Address</label>
                                                <input type="email" class="form-control" id="email" name="email" required>
                                                <div class="help-text">We'll use this to contact you about your store</div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label for="website" class="form-label">Website (Optional)</label>
                                            <input type="url" class="form-control" id="website" name="website" placeholder="https://yourwebsite.com">
                                            <div class="help-text">Your business website if you have one</div>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label for="address" class="form-label required-field">Store Location</label>
                                            <input type="text" class="form-control mb-3" id="address" name="address" required placeholder="Start typing your address...">
                                            <div id="map" style="height: 250px; width: 100%; background-color: #eee; border-radius: 8px;"></div>
                                            <input type="hidden" id="map_location" name="map_location">
                                            <div class="help-text mt-2">Drag the pin to adjust your exact location</div>
                                        </div>
                                    </div>
                                    
                                    <!-- Step 3: Business Hours -->
                                    <div class="step-content" id="step3" style="display: none;">
                                        <h2 class="section-title">
                                            <span class="icon"><i class="bi bi-clock-history"></i></span>
                                            Business Hours
                                        </h2>
                                        
                                        <div class="mb-4">
                                            <p class="help-text">Set your regular opening hours. You can adjust these later or set special hours for holidays.</p>
                                        </div>
                                        
                                        <div class="business-hour-card">
                                            <div class="day-toggle">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="monday_active" checked>
                                                    <label class="form-check-label" for="monday_active"><strong>Monday</strong></label>
                                                </div>
                                                <div class="time-inputs">
                                                    <div class="time-input-group">
                                                        <label class="form-label">Opening Time</label>
                                                        <input type="time" class="form-control" value="09:00">
                                                    </div>
                                                    <div class="time-input-group">
                                                        <label class="form-label">Closing Time</label>
                                                        <input type="time" class="form-control" value="17:00">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Repeat for other days... -->
                                        
                                        <div class="form-check mt-3">
                                            <input class="form-check-input" type="checkbox" id="copy_hours">
                                            <label class="form-check-label" for="copy_hours">Use these same hours for all weekdays</label>
                                        </div>
                                        
                                        <input type="hidden" name="working_hours" id="json-output">
                                    </div>
                                    
                                    <!-- Step 4: Photos -->
                                    <div class="step-content" id="step4" style="display: none;">
                                        <h2 class="section-title">
                                            <span class="icon"><i class="bi bi-image"></i></span>
                                            Store Photos
                                        </h2>
                                        
                                        <div class="mb-4">
                                            <label class="form-label">Upload Store Images</label>
                                            <div class="image-upload-area" id="upload-area">
                                                <i class="bi bi-cloud-arrow-up" style="font-size: 2rem; color: #6366f1;"></i>
                                                <h5 class="mt-3">Drag & Drop Images Here</h5>
                                                <p class="text-muted">or click to browse files</p>
                                                <input type="file" id="store_images" name="images[]" multiple accept="image/*" style="display: none;">
                                            </div>
                                            <div class="help-text">Upload at least 3 photos showing your store (max 10 photos, 5MB each)</div>
                                        </div>
                                        
                                        <div class="image-preview" id="image-preview"></div>
                                    </div>
                                    
                                    <!-- Form Navigation -->
                                    <div class="form-actions">
                                        <button type="button" class="btn btn-outline-secondary" id="prev-btn" disabled>
                                            <i class="bi bi-chevron-left me-2"></i>Previous
                                        </button>
                                        <button type="button" class="btn btn-primary" id="next-btn">
                                            Next<i class="bi bi-chevron-right ms-2"></i>
                                        </button>
                                        <button type="submit" class="btn btn-success" id="submit-btn" style="display: none;">
                                            <i class="bi bi-check-circle me-2"></i>Submit Store
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5.3 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places&callback=initMap" defer></script>
    
    <script>
        // Form Step Navigation
        const steps = document.querySelectorAll('.wizard-step');
        const stepContents = document.querySelectorAll('.step-content');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const submitBtn = document.getElementById('submit-btn');
        let currentStep = 0;
        
        // Initialize steps
        function showStep(stepIndex) {
            stepContents.forEach((content, index) => {
                content.style.display = index === stepIndex ? 'block' : 'none';
            });
            
            steps.forEach((step, index) => {
                if (index === stepIndex) {
                    step.classList.add('active');
                } else if (index < stepIndex) {
                    step.classList.add('completed');
                    step.classList.remove('active');
                } else {
                    step.classList.remove('active', 'completed');
                }
            });
            
            prevBtn.disabled = stepIndex === 0;
            nextBtn.style.display = stepIndex === steps.length - 1 ? 'none' : 'block';
            submitBtn.style.display = stepIndex === steps.length - 1 ? 'block' : 'none';
            
            currentStep = stepIndex;
        }
        
        nextBtn.addEventListener('click', function() {
            if (validateStep(currentStep)) {
                showStep(currentStep + 1);
            }
        });
        
        prevBtn.addEventListener('click', function() {
            showStep(currentStep - 1);
        });
        
        // Step validation
        function validateStep(stepIndex) {
            let isValid = true;
            
            if (stepIndex === 0) {
                const storeName = document.getElementById('store_name');
                if (!storeName.value.trim()) {
                    storeName.classList.add('is-invalid');
                    isValid = false;
                } else {
                    storeName.classList.remove('is-invalid');
                }
            }
            
            if (stepIndex === 1) {
                const requiredFields = document.querySelectorAll('#step2 [required]');
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });
            }
            
            if (!isValid) {
                alert('Please complete all required fields before continuing.');
            }
            
            return isValid;
        }
        
        // Image Upload Handling
        const uploadArea = document.getElementById('upload-area');
        const fileInput = document.getElementById('store_images');
        const imagePreview = document.getElementById('image-preview');
        
        uploadArea.addEventListener('click', function() {
            fileInput.click();
        });
        
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadArea.style.borderColor = '#6366f1';
            uploadArea.style.backgroundColor = '#f0f4ff';
        });
        
        uploadArea.addEventListener('dragleave', function() {
            uploadArea.style.borderColor = '#cbd5e0';
            uploadArea.style.backgroundColor = '#f8fafc';
        });
        
        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadArea.style.borderColor = '#cbd5e0';
            uploadArea.style.backgroundColor = '#f8fafc';
            
            if (e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                updateImagePreview();
            }
        });
        
        fileInput.addEventListener('change', updateImagePreview);
        
        function updateImagePreview() {
            imagePreview.innerHTML = '';
            
            if (fileInput.files.length > 10) {
                alert('You can upload a maximum of 10 images');
                fileInput.value = '';
                return;
            }
            
            for (const file of fileInput.files) {
                if (file.size > 5 * 1024 * 1024) {
                    alert('One or more files exceed the 5MB limit');
                    fileInput.value = '';
                    imagePreview.innerHTML = '';
                    return;
                }
                
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const previewItem = document.createElement('div');
                        previewItem.className = 'image-preview-item';
                        
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        
                        const removeBtn = document.createElement('button');
                        removeBtn.className = 'remove-image';
                        removeBtn.innerHTML = '&times;';
                        removeBtn.addEventListener('click', function() {
                            previewItem.remove();
                        });
                        
                        previewItem.appendChild(img);
                        previewItem.appendChild(removeBtn);
                        imagePreview.appendChild(previewItem);
                    };
                    reader.readAsDataURL(file);
                }
            }
        }
        
        // Google Maps Initialization
        function initMap() {
            const map = new google.maps.Map(document.getElementById("map"), {
                center: { lat: 37.9838, lng: 23.7275 },
                zoom: 12
            });
            
            const marker = new google.maps.Marker({
                position: { lat: 37.9838, lng: 23.7275 },
                map: map,
                draggable: true
            });
            
            const autocomplete = new google.maps.places.Autocomplete(
                document.getElementById('address'),
                { types: ['geocode'] }
            );
            
            autocomplete.addListener('place_changed', function() {
                const place = autocomplete.getPlace();
                if (!place.geometry) return;
                
                map.setCenter(place.geometry.location);
                marker.setPosition(place.geometry.location);
                document.getElementById('map_location').value = 
                    `${place.geometry.location.lat()},${place.geometry.location.lng()}`;
            });
            
            marker.addListener('dragend', function() {
                document.getElementById('map_location').value = 
                    `${marker.getPosition().lat()},${marker.getPosition().lng()}`;
            });
        }
        
        // Initialize first step
        showStep(0);
    </script>
</body>
</html>