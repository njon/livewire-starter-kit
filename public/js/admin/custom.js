$(document).ready(function () {

let debounceTimer;

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













    $('.main-category').on('mouseenter', function () {
        $('.main-category').removeClass('active');
        $(this).addClass('active');
        $('.subcategory-group').addClass('d-none');
        const targetId = $(this).data('target');
        $('#' + targetId).removeClass('d-none');
        var image = $(this).data('image');
        $('#menu-description-image').attr('src', image);
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






});