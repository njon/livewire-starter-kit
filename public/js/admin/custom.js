$(document).ready(function () {
    
    const isBussinessHoursPage = $('#business-hours-container').length > 0;
    const days = [
        { id: 'monday', name: 'Monday' },
        { id: 'tuesday', name: 'Tuesday' },
        { id: 'wednesday', name: 'Wednesday' },
        { id: 'thursday', name: 'Thursday' },
        { id: 'friday', name: 'Friday' },
        { id: 'saturday', name: 'Saturday' },
        { id: 'sunday', name: 'Sunday' }
    ];

    function createSlug(title) {
        const accentMap = { 'α': 'a', 'β': 'v', 'γ': 'g', 'δ': 'd', 'ε': 'e', 'ζ': 'z', 'η': 'i', 'θ': 'th','ι': 'i', 'κ': 'k', 'λ': 'l', 'μ': 'm', 'ν': 'n', 'ξ': 'x', 'ο': 'o', 'π': 'p', 'ρ': 'r', 'σ': 's', 'ς': 's', 'τ': 't', 'υ': 'y', 'φ': 'f', 'χ': 'ch','ψ': 'ps','ω': 'o', 'ά': 'a', 'έ': 'e', 'ή': 'i', 'ί': 'i', 'ό': 'o', 'ύ': 'y', 'ώ': 'o', 'ϊ': 'i', 'ΐ': 'i', 'ϋ': 'y', 'ΰ': 'y', 'Α': 'a', 'Β': 'v', 'Γ': 'g', 'Δ': 'd', 'Ε': 'e', 'Ζ': 'z', 'Η': 'i', 'Θ': 'th','Ι': 'i', 'Κ': 'k', 'Λ': 'l', 'Μ': 'm', 'Ν': 'n', 'Ξ': 'x', 'Ο': 'o', 'Π': 'p', 'Ρ': 'r', 'Σ': 's', 'Τ': 't', 'Υ': 'y', 'Φ': 'f', 'Χ': 'ch','Ψ': 'ps','Ω': 'o', 'Ά': 'a', 'Έ': 'e', 'Ή': 'i', 'Ί': 'i', 'Ό': 'o', 'Ύ': 'y', 'Ώ': 'o' };

        return title
            .toLowerCase()
            .split('').map(char => accentMap[char] || char).join('') // replace accented
            .replace(/[^a-z0-9\u0370-\u03FF]+/g, '-') // keep Latin and Greek chars
            .replace(/^-+|-+$/g, '') // trim dashes
            .replace(/-{2,}/g, '-'); // collapse multiple dashes
    }


    // Initialize the business hours selector
    function initBusinessHours() {
        const container = $('#business-hours-container');
        container.empty();

        days.forEach(day => {
            // Create day element
            const dayElement = $(`
                <div class="day-container" id="${day.id}-container">
                    <div class="day-checkbox">
                        <input type="checkbox" id="${day.id}-checkbox" checked>
                    </div>
                    <div class="day-name">${day.name}</div>
                    <div class="slider-container">
                        <div id="${day.id}-slider"></div>
                    </div>
                    <div class="time-display">
                        <span id="${day.id}-start">09:00</span> - 
                        <span id="${day.id}-end">17:00</span>
                    </div>
                </div>
            `);

            container.append(dayElement);

            // Initialize slider
            const slider = document.getElementById(`${day.id}-slider`);
            noUiSlider.create(slider, {
                start: [540, 1020], // 9:00 (540 minutes) to 17:00 (1020 minutes)
                connect: true,
                range: {
                    'min': 0,
                    'max': 1440 // 24 hours in minutes
                },
                step: 15, // 15 minute intervals
                behaviour: 'drag-tap',
                tooltips: [true, true],
                format: {
                    to: function(value) {
                        const hours = Math.floor(value / 60);
                        const minutes = Math.floor(value % 60);
                        return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
                    },
                    from: function(value) {
                        const parts = value.split(':');
                        return parseInt(parts[0]) * 60 + parseInt(parts[1]);
                    }
                }
            });

            // Update time display when slider changes
            slider.noUiSlider.on('update', function(values) {
                $(`#${day.id}-start`).text(values[0]);
                $(`#${day.id}-end`).text(values[1]);
            });

            // Toggle day active state
            $(`#${day.id}-checkbox`).on('change', function() {
                const isActive = $(this).is(':checked');
                $(`#${day.id}-container`).toggleClass('disabled-day', !isActive);
                // @todo check if needs to be changed
                // slider.setAttribute('disabled', !isActive);
            });
        });
    }

    // Get current business hours as JSON
    function getBusinessHours() {
        const businessHours = {};
        
        days.forEach(day => {
            const slider = document.getElementById(`${day.id}-slider`);
            const values = slider.noUiSlider.get();
            
            businessHours[day.id] = {
                active: $(`#${day.id}-checkbox`).is(':checked'),
                open: values[0],
                close: values[1]
            };
        });
        
        return businessHours;
    }

    function updateHours() {
        const businessHours = getBusinessHours();
        const jsonString = JSON.stringify(businessHours, null, 2);
        $('#json-output').val(jsonString);
        
        // In a real application, you would send this to your backend
        console.log('Business hours to save:', businessHours);
    }

    // Load business hours from JSON
    function loadBusinessHours(data) {
        days.forEach(day => {
            const dayData = data[day.id] || { 
                active: true, 
                open: '09:00', 
                close: '17:00' 
            };
            
            // Set checkbox state
            $(`#${day.id}-checkbox`).prop('checked', dayData.active);
            $(`#${day.id}-container`).toggleClass('disabled-day', !dayData.active);
            
            // Set slider values
            const slider = document.getElementById(`${day.id}-slider`);
            slider.noUiSlider.set([dayData.open, dayData.close]);
        });
    }
    
    $('#product_title_gr').on('input', function() {
        const slug = createSlug($(this).val());
        var slugEmpty = $('#product_url_gr').val();
        $('#product_url_gr').val(slug);
    });

    
    if($('#json-output').length != 0) {
        $('#save-asset').hover(function() {
            updateHours();
        });
    }

    $('#save-btn').on('click', function() {
        updateHours();
    });

    $('.subcategory-list').hide();
    var curId = $('select[name="category"]').val();
    if(curId) {
        $('.subcategory-list[data-target="' + curId + '"]').show();
    }
    
    $('select[name="category"]').change(function() {
        var selectedValue = $(this).val();
        $('.subcategory-list').hide();
        $('.subcategory-list[data-target="' + selectedValue + '"]').show();
    });

    $('select.form-select.subcategory-select').change(function() {
        var selectedValue = $(this).val();
        $('input[name="sub_category"]').val(selectedValue);
    });

    
    
    $('select[name="category"]').trigger('change');

    isBussinessHoursPage && initBusinessHours();
    isBussinessHoursPage && loadBusinessHours(exampleData);
});

document.addEventListener('DOMContentLoaded', function() {

    function toggleFirstThumbnailPreview() {
        const thumbnailPreview = document.getElementById('thumbnail-preview');
        if (!thumbnailPreview) return; // Exit if element doesn't exist
        
        const previews = thumbnailPreview.querySelectorAll('.image-preview-container');
        
        if (previews.length >= 2) {
            previews[0].style.display = 'none';
        } else if (previews.length === 1) {
            previews[0].style.display = '';
        }
    }

    toggleFirstThumbnailPreview();

    const sortable = new Sortable(document.getElementById('image-preview'), {
        animation: 150,
        ghostClass: 'sortable-ghost',
        onEnd: function() {
            // updateImageOrder();
        }
    });

    const dropzone = new Dropzone("#media-dropzone", { maxFiles: 4 });
    dropzone.on("success", function(file, response) {
        const previewContainer = document.createElement('div');
        previewContainer.className = 'image-preview-container col-md-4 col-lg-3';
        previewContainer.innerHTML = `
            <div class="position-relative h-100 rounded-2">
                <img src="${response.url}" class="img-fluid rounded-3 object-fit-cover w-100" alt="Product image" draggable="false">
                <button onclick="deleteImage(${response.id})" class="remove-button-d position-absolute top-0 right-0 bg-white-500 text-dark p-1 rounded-full">
                    Remove
                </button>
            </div>
        `;
        document.getElementById('image-preview').appendChild(previewContainer);
    });

    const thumbDropzone = new Dropzone("#thumbnail-dropzone");
    thumbDropzone.on("success", function(file, response) {
        const previewContainer = document.createElement('div');
        previewContainer.className = 'image-preview-container col-md-4 col-lg-3 relative';
        previewContainer.innerHTML = `
            <div class="position-relative h-100 rounded-2">
                <img src="${response.url}" class="img-fluid rounded-3 object-fit-cover w-100" alt="Product image" draggable="false">
                <button type="button" onclick="deleteImage(${response.id})" class="remove-button-d position-absolute top-0 right-0 bg-white-500 text-dark p-1 rounded-full">
                    Remove
                </button>
            </div>
        `;
        document.getElementById('thumbnail-preview').appendChild(previewContainer);
        setTimeout(toggleFirstThumbnailPreview, 100);
    });

    const languages = ["en", "gr"];

    languages.forEach(lang => {
        tinymce.init({
            selector: `#productDescription_${lang}`,
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
    });
});