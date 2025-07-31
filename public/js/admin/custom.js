$(document).ready(function () {
    
    let debounceTimer;

     const days = [
        { id: 'monday', name: 'Monday' },
        { id: 'tuesday', name: 'Tuesday' },
        { id: 'wednesday', name: 'Wednesday' },
        { id: 'thursday', name: 'Thursday' },
        { id: 'friday', name: 'Friday' },
        { id: 'saturday', name: 'Saturday' },
        { id: 'sunday', name: 'Sunday' }
    ];

    function checkSlugAvailability(slug, lang = 'en') {
        $.ajax({
            url: '/admin/check-slug', // 🔁 Replace with your actual endpoint
            method: 'GET',
            data: {
                slug: slug,
                lang: lang
            },
            success: function(response) {
                // Expected: response.exists === true or false
                const $icon = $('#slugCheckIcon');
                if (response.exists) {
                    $icon.removeClass('valid').addClass('invalid').html('<i class="bi bi-x-circle-fill text-danger"></i>');
                } else {
                    $icon.removeClass('invalid').addClass('valid').html('<i class="bi bi-check-circle-fill text-success"></i>');
                }
            },
            error: function() {
                console.error('Slug check failed');
            }
        });
    }

    function createSlug(title) {
        const accentMap = {
                'α': 'a', 'β': 'v', 'γ': 'g', 'δ': 'd', 'ε': 'e', 'ζ': 'z', 'η': 'i',
                'θ': 'th','ι': 'i', 'κ': 'k', 'λ': 'l', 'μ': 'm', 'ν': 'n', 'ξ': 'x',
                'ο': 'o', 'π': 'p', 'ρ': 'r', 'σ': 's', 'ς': 's', 'τ': 't', 'υ': 'y',
                'φ': 'f', 'χ': 'ch','ψ': 'ps','ω': 'o',

                'ά': 'a', 'έ': 'e', 'ή': 'i', 'ί': 'i', 'ό': 'o', 'ύ': 'y', 'ώ': 'o',
                'ϊ': 'i', 'ΐ': 'i', 'ϋ': 'y', 'ΰ': 'y',

                'Α': 'a', 'Β': 'v', 'Γ': 'g', 'Δ': 'd', 'Ε': 'e', 'Ζ': 'z', 'Η': 'i',
                'Θ': 'th','Ι': 'i', 'Κ': 'k', 'Λ': 'l', 'Μ': 'm', 'Ν': 'n', 'Ξ': 'x',
                'Ο': 'o', 'Π': 'p', 'Ρ': 'r', 'Σ': 's', 'Τ': 't', 'Υ': 'y',
                'Φ': 'f', 'Χ': 'ch','Ψ': 'ps','Ω': 'o',

                'Ά': 'a', 'Έ': 'e', 'Ή': 'i', 'Ί': 'i', 'Ό': 'o', 'Ύ': 'y', 'Ώ': 'o'
            };

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


    $('.main-category').on('mouseenter', function () {
        var image = $(this).data('image');
        const targetId = $(this).data('target');

        $('.main-category').removeClass('active');
        $('.subcategory-group').addClass('d-none');
        
        $(this).addClass('active');
        $('#' + targetId).removeClass('d-none');
        $('#menu-description-image').attr('src', image);
    });

    $('.main-category, .subcategory-group').on('click', function () {
        console.log($(this));
        $(this).addClass('actived');

    });

    $('#product_url_en').on('input', function() {
        clearTimeout(debounceTimer);
        const slug = $(this).val().trim();
        const lang = 'en'; //$(this).data('lang') || 'en';

        // Don't send request if input is empty
        if (!slug) {
            $('#slugCheckIcon').removeClass('valid invalid').html('');
            return;
        }

        debounceTimer = setTimeout(function() {
            checkSlugAvailability(slug, lang);
        }, 400); 
    });
    

    $('#product_title_en').on('input', function() {
        clearTimeout(debounceTimer);
        const lang = 'en';

        const slug = createSlug($(this).val());
        var slugEmpty = $('#product_url_en').val();
        $('#product_url_en').val(slug);

        debounceTimer = setTimeout(function() {
            checkSlugAvailability(slug, lang);
        }, 400); 
    });
    
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

    initBusinessHours();
    loadBusinessHours(exampleData);
});