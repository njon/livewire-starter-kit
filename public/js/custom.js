let $form;

$(document).ready(function () {

    // Variables for control
    let searchTimeout;
    let isSubmitting = false;
    let isInitialLoad = true; // New flag for initial load detection

    // Elements
    const $searchButton = $('#search-button');
    const $minPriceInput = $('#min-price');
    const $maxPriceInput = $('#max-price');
    const $priceSlider = $('#price-slider');
    const $histogramBars = $('.histogram-bar');
    const $hasSlider = $priceSlider.length > 0;

    if ($hasSlider) {
        noUiSlider.create($priceSlider[0], {
            start: [curMin, curMax],
            connect: true,
            range: { 'min': min, 'max': max },
            margin: 10,
            step: 5
        });
    }


    $('#filterAccordion').on('change', 'input', function () {
        if (this.id !== 'max-price' && this.id !== 'min-price') {
            $('#search-button').trigger('click');
        }
    });

    // Modified handler with initial load check
    function handlePriceChange() {
        if (isInitialLoad) return; // Skip during initial load

        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(triggerSearch, 500);
    }

    function triggerSearch() {
        if (isSubmitting) return;
        isSubmitting = true;
        $searchButton.trigger('click');
        setTimeout(() => { isSubmitting = false; }, 1000);
    }


    if ($hasSlider) {
        // Set up event listeners
        $minPriceInput.add($maxPriceInput).on('input change', handlePriceChange);

        $priceSlider[0].noUiSlider.on('update', function (values) {
            const minVal = Math.round(values[0]);
            const maxVal = Math.round(values[1]);

            $minPriceInput.val(minVal);
            $maxPriceInput.val(maxVal);

            $histogramBars.each(function () {
                const barMin = parseInt($(this).data('min'));
                const barMax = parseInt($(this).data('max'));
                $(this).css('background-color',
                    (barMin >= minVal && barMax <= maxVal) ? 'rgb(238, 238, 238)' : '#f6f6f6'
                );
            });

            handlePriceChange();
        });
    }

    // Mark initial load as complete after short delay
    setTimeout(() => {
        isInitialLoad = false;
    }, 500);

    $('.main-category').on('mouseenter', function () {
        $('.main-category').removeClass('active');
        $(this).addClass('active');
        $('.subcategory-group').addClass('d-none');
        const targetId = $(this).data('target');
        $('#' + targetId).removeClass('d-none');
        var image = $(this).data('image');
        $('#menu-description-image').attr('src', image);
    });

    // Show/hide blur on mega-menu hover
    $('.mega-menu').hover(
        function () {
            $('.blur').show(); // Show on hover in
        },
        function () {
            $('.blur').hide(); // Hide on hover out
        }
    );

    function updBdgs() {
        $('.filter-occasion input[type="checkbox"]').each(function () {
            updateBadgeState($(this));
        });
    }


    $('.filter-occasion').on('change', 'input[type="checkbox"]', function () {
        updBdgs();
    });



    function updateBadgeState(checkbox) {
        const badge = checkbox.next('.search-selector');
        if (badge.length) {
            const isChecked = checkbox.prop('checked');

            // Toggle active state class
            badge.toggleClass('selection-active', isChecked);

            // Toggle icon color
            badge.find('i').toggleClass('text-white', isChecked);

            // Optional: Toggle text color if needed
            badge.find('span:not(.fa)').toggleClass('text-white', isChecked);
        }
    }

    $('.dropdown-item[data-sort]').on('click', function (e) {
        e.preventDefault();

        // Update the hidden input value
        $('#sort-input').val($(this).data('sort'));

        // Update the dropdown button text
        $('#sortDropdown').text($(this).text());

        // Submit the form
        $('#search-button').submit();
    });

    $(document).on('click', '.pagination .page-link', function (e) {
        e.preventDefault(); // Prevent default link behavior

        const href = $(this).attr('href');
        const urlParams = new URLSearchParams(href.split('?')[1]);
        const page = urlParams.get('page');

        if (page) {
            $('input[name="page"]').val(page);
            $('#search-button').click();
        }
    });

    $('#checkout-form').on('submit', function (e) {
        e.preventDefault();

        var $form = $(this);
        var $submitBtn = $form.find('button[type="submit"]');
        var originalBtnText = $submitBtn.text();

        // Show loading state
        $submitBtn.prop('disabled', true).text(processing);

        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            data: $form.serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    // Redirect or show success message
                    alert('sucess');
                } else if (response.message) {
                    // Show error message
                    alert(response.message);
                }
                $('#order_id').val(response.order_id);
                $('#order_reference').val(response.order_reference);
            },
            error: function (xhr) {
                var errorMsg = 'An error occurred during checkout.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                alert(errorMsg);
            },
            complete: function () {
                $submitBtn.prop('disabled', false).text(originalBtnText);
            }
        });
    });


    $(document).on('click', '.filter-tag-remove', function () {
        var $button = $(this);
        var form = $('#ajax-search-form');
        var keys = $button.data('keys').split(',');
        var value = $button.data('value');
        setTimeout(function () {
            updBdgs();
        }, 11);

        $.each(keys, function (index, key) {
            if (key.endsWith('[]')) {
                // Handle array parameters (like occasion[])
                var paramName = key.replace('[]', '');
                var $inputs = form.find('input[name="' + paramName + '[]"]');

                $inputs.each(function () {
                    if ($(this).val() === value) {
                        $(this).prop('checked', false);
                    }
                });
            } else {
                // Handle regular parameters
                var $input = form.find('[name="' + key + '"]');
                var default_value = $input.data('default');
                $input.val(default_value);

                // if ($input.length) {
                //     if ($input.attr('type') === 'range') {
                //         $input.val(min); // Reset to minimum
                //         $input.val(max); // Reset to minimum
                //         $('#ratingValueDisplay').text($input.attr('min'));
                //     } else {
                //         $input.val('');
                //     }
                // }
            }
        });

        // Trigger form submission
        $('#search-button').click();
        $button.closest('.filter-tag').remove();
    });



    // Initialize variables
    let currentImageIndex = 0;
    let images = [];

    // Collect all images from the gallery
    function initGallery() {
        images = [];

        // Add main image first
        images.push($('#mainProductImage').attr('src'));

        // Add thumbnail images
        $('.thumbnail-item').each(function () {
            images.push($(this).data('target'));
        });
    }

    // Initialize the gallery
    initGallery();

    // Click handler for thumbnails
    $('.thumbnail-item').click(function () {
        currentImageIndex = $(this).index() + 1; // +1 because main image is first
        $('#modalImage').attr('src', $(this).data('target'));
        $('#imageGalleryModal').modal('show');
    });

    // Click handler for main image
    $('#mainProductImage').click(function () {
        currentImageIndex = 0;
        $('#modalImage').attr('src', $(this).attr('src'));
        $('#imageGalleryModal').modal('show');
    });

    // Previous image button
    $('#prevImage').click(function () {
        currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
        $('#modalImage').attr('src', images[currentImageIndex]);
    });

    // Next image button
    $('#nextImage').click(function () {
        currentImageIndex = (currentImageIndex + 1) % images.length;
        $('#modalImage').attr('src', images[currentImageIndex]);
    });

    // Keyboard navigation
    $(document).keydown(function (e) {
        if ($('#imageGalleryModal').hasClass('show')) {
            if (e.keyCode == 37) { // Left arrow
                $('#prevImage').click();
            } else if (e.keyCode == 39) { // Right arrow
                $('#nextImage').click();
            }
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {


    // Parse initial values from the HTML
    const daysElement = document.getElementById('countdown-days');
    const timeElement = document.getElementById('countdown-time');

    if (!daysElement || !timeElement) {
        return false;
    }
    // Extract initial values (trim whitespace)
    let days = parseInt(daysElement.textContent.trim());
    let [hours, minutes, seconds] = timeElement.textContent.trim().split(':').map(Number);

    // Update the countdown every second
    const countdown = setInterval(function () {
        // Decrement seconds
        seconds--;

        // Handle time rollover
        if (seconds < 0) {
            seconds = 59;
            minutes--;

            if (minutes < 0) {
                minutes = 59;
                hours--;

                if (hours < 0) {
                    hours = 23;
                    days--;
                    daysElement.textContent = days >= 0 ? `\xa0${days}` : '0';
                }
            }
        }

        // Stop if time is up
        if (days <= 0 && hours <= 0 && minutes <= 0 && seconds <= 0) {
            clearInterval(countdown);
            timeElement.textContent = '00:00:00';
            return;
        }

        // Update display (with leading zeros)
        timeElement.textContent =
            `${hours.toString().padStart(2, '0')}:` +
            `${minutes.toString().padStart(2, '0')}:` +
            `${seconds.toString().padStart(2, '0')}`;
    }, 1000);
});

// @todo Probably remove this function. Loading items in HTML blade file
function loadCartItems() {
    fetch('/cartItems')
        .then(response => response.json())
        .then(data => {
            document.querySelector('.cart').innerHTML = data.html
        })
        .catch(error => console.error('Error fetching cart items:', error));
}

function loadOffcanvasCartItems(showElement = true) {
    fetch('/canvasItems')
        .then(response => response.json())
        .then(data => {
            cartElement = document.querySelector('#shoppingCart .offcanvas-body');
            if (!cartElement) {
                return false;
            }

            if (showElement) {
                $('#shoppingCart').offcanvas('show');
            }
            // Generate the HTML structure
            cartElement.innerHTML = data.html;

            const cartCounter = document.querySelector('.cart-count');
            if (cartCounter) cartCounter.textContent = Object.keys(data).length;
        })
        .catch(error => console.error('Error loading cart:', error));
}

document.addEventListener('DOMContentLoaded', function () {
    loadOffcanvasCartItems(false);
});


$(document).ready(function () {

    const csrf_token = $('[name="_token"]').val();


    $('#review-form').on('submit', function (e) {
        e.preventDefault(); // Prevent default form submission

        var $form = $(this);
        var $submitBtn = $form.find('button[type="submit"]');
        var originalBtnText = $submitBtn.text();

        // Show loading state
        $submitBtn.prop('disabled', true).text(submitting);

        $.ajax({
            url: $form.attr('action'),
            type: $form.attr('method'),
            data: $form.serialize(),
            dataType: 'json',
            success: function (response) {
                // Success feedback
                if (response.success) {
                    $form.replaceWith(
                        '<div class="alert alert-success">' +
                        response.message +
                        '</div>'
                    );

                    // Optional: Reload reviews if they're displayed separately
                    if (typeof loadReviews === 'function') {
                        loadReviews();
                    }
                }
            },
            error: function (xhr) {
                // Error handling
                var errors = xhr.responseJSON.errors;
                var errorHtml = '<div class="alert alert-danger"><ul>';

                $.each(errors, function (key, value) {
                    errorHtml += '<li>' + value + '</li>';
                });

                errorHtml += '</ul></div>';

                $form.before(errorHtml);
                $submitBtn.prop('disabled', false).text(originalBtnText);
            },
            complete: function () {
                // Scroll to form if error
                if ($('.alert-danger').length) {
                    $('html, body').animate({
                        scrollTop: $form.offset().top - 100
                    }, 300);
                }
            }
        });
    });
    function updateWishlist() {
        $.get('wishlist/ajax-items', function (response) {
            $('.wishlist-add').each(function () {
                const $btn = $(this);
                const productId = $btn.data('product-id');
                const isActive = response.hasOwnProperty(productId);
                $btn.toggleClass('is-active', isActive);
                $btn.closest('.btn-wishlist').toggleClass('is-active', isActive);
            });
        });
    }

    // Toggle wishlist item
    $(document).on('click', '.wishlist-add', function (e) {
        e.preventDefault();
        const $button = $(this);
        const productId = $button.data('product-id');
        const isActive = $button.hasClass('is-active');

        if (isActive) {
            $button.removeClass('is-active');
        }

        $button.addClass('is-loading');

        $.ajax({
            url: 'wishlist',
            method: 'POST',
            data: {
                product_id: productId,
                _token: csrf_token,
                destroy: isActive
            },
            success: function () {
                $button.removeClass('is-loading');

                if (!isActive) {
                    $button.addClass('is-active');
                }

                updateWishlist();
            }
        });
    });

    $('#add-to-cart').on('submit', function (e) {
        e.preventDefault();

        var $form = $(this);
        var $button = $form.find('.btn-add-to-cart');
        var originalText = $button.html();

        const isActive = $button.hasClass('is-active');

        if (isActive) {
            $button.removeClass('is-active');
        }


        $button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ' + adding);

        $.ajax({
            url: $form.attr('action'),
            type: 'PUT',
            data: $form.serialize(),
            dataType: 'json',
            success: function (response) {
                $button.removeClass('is-loading');

                if (!isActive) {
                    $button.addClass('is-active');
                }

                setTimeout(function () {
                    $button.removeClass('is-active');
                }, 300);

                loadOffcanvasCartItems();
            },
            error: function (xhr) {
                var errorMessage = xhr.responseJSON && xhr.responseJSON.message
                    ? xhr.responseJSON.message
                    : 'An error occurred while adding to cart';
                alert(errorMessage);
            },
            complete: function () {
                $button.prop('disabled', false).html(originalText);
            }
        });
    });

    $('#ask-question-form').on('submit', function(e) {
        $form = $(this);
        e.preventDefault(); // Prevent default form submission

        // Get form data
        var formData = $(this).serialize();
        var formAction = $(this).attr('action');
        var submitButton = $(this).find('button[type="submit"]');

        // Disable submit button to prevent multiple submissions
        submitButton.prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ' + submitting
        );

        // AJAX request
        $.ajax({
            url: formAction,
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': csrf_token // Include CSRF token in headers
            },
            success: function(response) {
                if (response.success) {
                    alert('Question submitted successfully! Page will reload now.');
                    $('#askQuestionModal').modal('hide');
                    $('#question').val('');
                }
            },
            error: function(xhr) {
                // Handle errors
                var errorMessage = 'An error occurred. Please try again.';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.statusText) {
                    errorMessage = xhr.statusText;
                }

                alert(errorMessage);
            },
            complete: function() {
                submitButton.prop('disabled', false).html('Submit Question');
            }
        });
    });

    $('#ajax-search-form').on('submit', function (e) {
        e.preventDefault();
        const formData = $('#ajax-search-form').serialize();
        const URL = location.protocol + '//' + location.host + location.pathname + '?' + formData;
        window.history.pushState('page2', 'Title', URL);
        $('.filter-tags-container').addClass('loading');

        $('#search-results').html('<div class="col-6 mb-5 col-lg-4 product" bis_skin_checked="1"> <div class="product-item-list" bis_skin_checked="1"> <!-- Product Image Placeholder --> <div class="position-relative overflow-hidden placeholder-glow" bis_skin_checked="1"> <div class="card-img-top placeholder rounded card-img-top object-fit-cover rounded" background-color: #e9ecef;" bis_skin_checked="1"></div> <a class="stretched-link placeholder"></a> </div> <!-- Card Body Placeholders --> <div class="d-flex flex-column mt-4" bis_skin_checked="1"> <!-- Title & Wishlist --> <div class="d-flex justify-content-between align-items-center mb-2" bis_skin_checked="1"> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-8" style="width: 250px;"></span> </div> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder" style="width: 24px; height: 24px;"></span> </div> </div> <!-- Location --> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-5" style="height: 14px;"></span> </div> <!-- Meta (Participants/Duration) --> <div class="placeholder-glow my-2" bis_skin_checked="1"> <span class="placeholder col-5" style="height: 14px;"></span> </div> <!-- Price Placeholder --> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-3" style="height: 24px;"></span> <span class="placeholder col-2 ms-2" style="height: 16px;"></span> <span class="placeholder col-1 ms-2" style="height: 16px;"></span> </div> </div> </div> </div><div class="col-6 mb-5 col-lg-4 product" bis_skin_checked="1"> <div class="product-item-list" bis_skin_checked="1"> <!-- Product Image Placeholder --> <div class="position-relative overflow-hidden placeholder-glow" bis_skin_checked="1"> <div class="card-img-top placeholder rounded card-img-top object-fit-cover rounded" background-color: #e9ecef;" bis_skin_checked="1"></div> <a class="stretched-link placeholder"></a> </div> <!-- Card Body Placeholders --> <div class="d-flex flex-column mt-4" bis_skin_checked="1"> <!-- Title & Wishlist --> <div class="d-flex justify-content-between align-items-center mb-2" bis_skin_checked="1"> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-8" style="width: 250px;"></span> </div> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder" style="width: 24px; height: 24px;"></span> </div> </div> <!-- Location --> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-5" style="height: 14px;"></span> </div> <!-- Meta (Participants/Duration) --> <div class="placeholder-glow my-2" bis_skin_checked="1"> <span class="placeholder col-5" style="height: 14px;"></span> </div> <!-- Price Placeholder --> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-3" style="height: 24px;"></span> <span class="placeholder col-2 ms-2" style="height: 16px;"></span> <span class="placeholder col-1 ms-2" style="height: 16px;"></span> </div> </div> </div> </div><div class="col-6 mb-5 col-lg-4 product" bis_skin_checked="1"> <div class="product-item-list" bis_skin_checked="1"> <!-- Product Image Placeholder --> <div class="position-relative overflow-hidden placeholder-glow" bis_skin_checked="1"> <div class="card-img-top placeholder rounded card-img-top object-fit-cover rounded" background-color: #e9ecef;" bis_skin_checked="1"></div> <a class="stretched-link placeholder"></a> </div> <!-- Card Body Placeholders --> <div class="d-flex flex-column mt-4" bis_skin_checked="1"> <!-- Title & Wishlist --> <div class="d-flex justify-content-between align-items-center mb-2" bis_skin_checked="1"> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-8" style="width: 250px;"></span> </div> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder" style="width: 24px; height: 24px;"></span> </div> </div> <!-- Location --> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-5" style="height: 14px;"></span> </div> <!-- Meta (Participants/Duration) --> <div class="placeholder-glow my-2" bis_skin_checked="1"> <span class="placeholder col-5" style="height: 14px;"></span> </div> <!-- Price Placeholder --> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-3" style="height: 24px;"></span> <span class="placeholder col-2 ms-2" style="height: 16px;"></span> <span class="placeholder col-1 ms-2" style="height: 16px;"></span> </div> </div> </div> </div><div class="col-6 mb-5 col-lg-4 product" bis_skin_checked="1"> <div class="product-item-list" bis_skin_checked="1"> <!-- Product Image Placeholder --> <div class="position-relative overflow-hidden placeholder-glow" bis_skin_checked="1"> <div class="card-img-top placeholder rounded card-img-top object-fit-cover rounded" background-color: #e9ecef;" bis_skin_checked="1"></div> <a class="stretched-link placeholder"></a> </div> <!-- Card Body Placeholders --> <div class="d-flex flex-column mt-4" bis_skin_checked="1"> <!-- Title & Wishlist --> <div class="d-flex justify-content-between align-items-center mb-2" bis_skin_checked="1"> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-8" style="width: 250px;"></span> </div> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder" style="width: 24px; height: 24px;"></span> </div> </div> <!-- Location --> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-5" style="height: 14px;"></span> </div> <!-- Meta (Participants/Duration) --> <div class="placeholder-glow my-2" bis_skin_checked="1"> <span class="placeholder col-5" style="height: 14px;"></span> </div> <!-- Price Placeholder --> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-3" style="height: 24px;"></span> <span class="placeholder col-2 ms-2" style="height: 16px;"></span> <span class="placeholder col-1 ms-2" style="height: 16px;"></span> </div> </div> </div> </div><div class="col-6 mb-5 col-lg-4 product" bis_skin_checked="1"> <div class="product-item-list" bis_skin_checked="1"> <!-- Product Image Placeholder --> <div class="position-relative overflow-hidden placeholder-glow" bis_skin_checked="1"> <div class="card-img-top placeholder rounded card-img-top object-fit-cover rounded" background-color: #e9ecef;" bis_skin_checked="1"></div> <a class="stretched-link placeholder"></a> </div> <!-- Card Body Placeholders --> <div class="d-flex flex-column mt-4" bis_skin_checked="1"> <!-- Title & Wishlist --> <div class="d-flex justify-content-between align-items-center mb-2" bis_skin_checked="1"> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-8" style="width: 250px;"></span> </div> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder" style="width: 24px; height: 24px;"></span> </div> </div> <!-- Location --> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-5" style="height: 14px;"></span> </div> <!-- Meta (Participants/Duration) --> <div class="placeholder-glow my-2" bis_skin_checked="1"> <span class="placeholder col-5" style="height: 14px;"></span> </div> <!-- Price Placeholder --> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-3" style="height: 24px;"></span> <span class="placeholder col-2 ms-2" style="height: 16px;"></span> <span class="placeholder col-1 ms-2" style="height: 16px;"></span> </div> </div> </div> </div><div class="col-6 mb-5 col-lg-4 product" bis_skin_checked="1"> <div class="product-item-list" bis_skin_checked="1"> <!-- Product Image Placeholder --> <div class="position-relative overflow-hidden placeholder-glow" bis_skin_checked="1"> <div class="card-img-top placeholder rounded card-img-top object-fit-cover rounded" background-color: #e9ecef;" bis_skin_checked="1"></div> <a class="stretched-link placeholder"></a> </div> <!-- Card Body Placeholders --> <div class="d-flex flex-column mt-4" bis_skin_checked="1"> <!-- Title & Wishlist --> <div class="d-flex justify-content-between align-items-center mb-2" bis_skin_checked="1"> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-8" style="width: 250px;"></span> </div> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder" style="width: 24px; height: 24px;"></span> </div> </div> <!-- Location --> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-5" style="height: 14px;"></span> </div> <!-- Meta (Participants/Duration) --> <div class="placeholder-glow my-2" bis_skin_checked="1"> <span class="placeholder col-5" style="height: 14px;"></span> </div> <!-- Price Placeholder --> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-3" style="height: 24px;"></span> <span class="placeholder col-2 ms-2" style="height: 16px;"></span> <span class="placeholder col-1 ms-2" style="height: 16px;"></span> </div> </div> </div> </div><div class="col-6 mb-5 col-lg-4 product" bis_skin_checked="1"> <div class="product-item-list" bis_skin_checked="1"> <!-- Product Image Placeholder --> <div class="position-relative overflow-hidden placeholder-glow" bis_skin_checked="1"> <div class="card-img-top placeholder rounded card-img-top object-fit-cover rounded" background-color: #e9ecef;" bis_skin_checked="1"></div> <a class="stretched-link placeholder"></a> </div> <!-- Card Body Placeholders --> <div class="d-flex flex-column mt-4" bis_skin_checked="1"> <!-- Title & Wishlist --> <div class="d-flex justify-content-between align-items-center mb-2" bis_skin_checked="1"> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-8" style="width: 250px;"></span> </div> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder" style="width: 24px; height: 24px;"></span> </div> </div> <!-- Location --> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-5" style="height: 14px;"></span> </div> <!-- Meta (Participants/Duration) --> <div class="placeholder-glow my-2" bis_skin_checked="1"> <span class="placeholder col-5" style="height: 14px;"></span> </div> <!-- Price Placeholder --> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-3" style="height: 24px;"></span> <span class="placeholder col-2 ms-2" style="height: 16px;"></span> <span class="placeholder col-1 ms-2" style="height: 16px;"></span> </div> </div> </div> </div><div class="col-6 mb-5 col-lg-4 product" bis_skin_checked="1"> <div class="product-item-list" bis_skin_checked="1"> <!-- Product Image Placeholder --> <div class="position-relative overflow-hidden placeholder-glow" bis_skin_checked="1"> <div class="card-img-top placeholder rounded card-img-top object-fit-cover rounded" background-color: #e9ecef;" bis_skin_checked="1"></div> <a class="stretched-link placeholder"></a> </div> <!-- Card Body Placeholders --> <div class="d-flex flex-column mt-4" bis_skin_checked="1"> <!-- Title & Wishlist --> <div class="d-flex justify-content-between align-items-center mb-2" bis_skin_checked="1"> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-8" style="width: 250px;"></span> </div> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder" style="width: 24px; height: 24px;"></span> </div> </div> <!-- Location --> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-5" style="height: 14px;"></span> </div> <!-- Meta (Participants/Duration) --> <div class="placeholder-glow my-2" bis_skin_checked="1"> <span class="placeholder col-5" style="height: 14px;"></span> </div> <!-- Price Placeholder --> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-3" style="height: 24px;"></span> <span class="placeholder col-2 ms-2" style="height: 16px;"></span> <span class="placeholder col-1 ms-2" style="height: 16px;"></span> </div> </div> </div> </div><div class="col-6 mb-5 col-lg-4 product" bis_skin_checked="1"> <div class="product-item-list" bis_skin_checked="1"> <!-- Product Image Placeholder --> <div class="position-relative overflow-hidden placeholder-glow" bis_skin_checked="1"> <div class="card-img-top placeholder rounded card-img-top object-fit-cover rounded" background-color: #e9ecef;" bis_skin_checked="1"></div> <a class="stretched-link placeholder"></a> </div> <!-- Card Body Placeholders --> <div class="d-flex flex-column mt-4" bis_skin_checked="1"> <!-- Title & Wishlist --> <div class="d-flex justify-content-between align-items-center mb-2" bis_skin_checked="1"> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-8" style="width: 250px;"></span> </div> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder" style="width: 24px; height: 24px;"></span> </div> </div> <!-- Location --> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-5" style="height: 14px;"></span> </div> <!-- Meta (Participants/Duration) --> <div class="placeholder-glow my-2" bis_skin_checked="1"> <span class="placeholder col-5" style="height: 14px;"></span> </div> <!-- Price Placeholder --> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-3" style="height: 24px;"></span> <span class="placeholder col-2 ms-2" style="height: 16px;"></span> <span class="placeholder col-1 ms-2" style="height: 16px;"></span> </div> </div> </div> </div><div class="col-6 mb-5 col-lg-4 product" bis_skin_checked="1"> <div class="product-item-list" bis_skin_checked="1"> <!-- Product Image Placeholder --> <div class="position-relative overflow-hidden placeholder-glow" bis_skin_checked="1"> <div class="card-img-top placeholder rounded card-img-top object-fit-cover rounded" background-color: #e9ecef;" bis_skin_checked="1"></div> <a class="stretched-link placeholder"></a> </div> <!-- Card Body Placeholders --> <div class="d-flex flex-column mt-4" bis_skin_checked="1"> <!-- Title & Wishlist --> <div class="d-flex justify-content-between align-items-center mb-2" bis_skin_checked="1"> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-8" style="width: 250px;"></span> </div> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder" style="width: 24px; height: 24px;"></span> </div> </div> <!-- Location --> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-5" style="height: 14px;"></span> </div> <!-- Meta (Participants/Duration) --> <div class="placeholder-glow my-2" bis_skin_checked="1"> <span class="placeholder col-5" style="height: 14px;"></span> </div> <!-- Price Placeholder --> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-3" style="height: 24px;"></span> <span class="placeholder col-2 ms-2" style="height: 16px;"></span> <span class="placeholder col-1 ms-2" style="height: 16px;"></span> </div> </div> </div> </div><div class="col-6 mb-5 col-lg-4 product" bis_skin_checked="1"> <div class="product-item-list" bis_skin_checked="1"> <!-- Product Image Placeholder --> <div class="position-relative overflow-hidden placeholder-glow" bis_skin_checked="1"> <div class="card-img-top placeholder rounded card-img-top object-fit-cover rounded" background-color: #e9ecef;" bis_skin_checked="1"></div> <a class="stretched-link placeholder"></a> </div> <!-- Card Body Placeholders --> <div class="d-flex flex-column mt-4" bis_skin_checked="1"> <!-- Title & Wishlist --> <div class="d-flex justify-content-between align-items-center mb-2" bis_skin_checked="1"> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-8" style="width: 250px;"></span> </div> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder" style="width: 24px; height: 24px;"></span> </div> </div> <!-- Location --> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-5" style="height: 14px;"></span> </div> <!-- Meta (Participants/Duration) --> <div class="placeholder-glow my-2" bis_skin_checked="1"> <span class="placeholder col-5" style="height: 14px;"></span> </div> <!-- Price Placeholder --> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-3" style="height: 24px;"></span> <span class="placeholder col-2 ms-2" style="height: 16px;"></span> <span class="placeholder col-1 ms-2" style="height: 16px;"></span> </div> </div> </div> </div><div class="col-6 mb-5 col-lg-4 product" bis_skin_checked="1"> <div class="product-item-list" bis_skin_checked="1"> <!-- Product Image Placeholder --> <div class="position-relative overflow-hidden placeholder-glow" bis_skin_checked="1"> <div class="card-img-top placeholder rounded card-img-top object-fit-cover rounded" background-color: #e9ecef;" bis_skin_checked="1"></div> <a class="stretched-link placeholder"></a> </div> <!-- Card Body Placeholders --> <div class="d-flex flex-column mt-4" bis_skin_checked="1"> <!-- Title & Wishlist --> <div class="d-flex justify-content-between align-items-center mb-2" bis_skin_checked="1"> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-8" style="width: 250px;"></span> </div> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder" style="width: 24px; height: 24px;"></span> </div> </div> <!-- Location --> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-5" style="height: 14px;"></span> </div> <!-- Meta (Participants/Duration) --> <div class="placeholder-glow my-2" bis_skin_checked="1"> <span class="placeholder col-5" style="height: 14px;"></span> </div> <!-- Price Placeholder --> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-3" style="height: 24px;"></span> <span class="placeholder col-2 ms-2" style="height: 16px;"></span> <span class="placeholder col-1 ms-2" style="height: 16px;"></span> </div> </div> </div> </div><div class="col-6 mb-5 col-lg-4 product" bis_skin_checked="1"> <div class="product-item-list" bis_skin_checked="1"> <!-- Product Image Placeholder --> <div class="position-relative overflow-hidden placeholder-glow" bis_skin_checked="1"> <div class="card-img-top placeholder rounded card-img-top object-fit-cover rounded" background-color: #e9ecef;" bis_skin_checked="1"></div> <a class="stretched-link placeholder"></a> </div> <!-- Card Body Placeholders --> <div class="d-flex flex-column mt-4" bis_skin_checked="1"> <!-- Title & Wishlist --> <div class="d-flex justify-content-between align-items-center mb-2" bis_skin_checked="1"> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-8" style="width: 250px;"></span> </div> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder" style="width: 24px; height: 24px;"></span> </div> </div> <!-- Location --> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-5" style="height: 14px;"></span> </div> <!-- Meta (Participants/Duration) --> <div class="placeholder-glow my-2" bis_skin_checked="1"> <span class="placeholder col-5" style="height: 14px;"></span> </div> <!-- Price Placeholder --> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-3" style="height: 24px;"></span> <span class="placeholder col-2 ms-2" style="height: 16px;"></span> <span class="placeholder col-1 ms-2" style="height: 16px;"></span> </div> </div> </div> </div><div class="col-6 mb-5 col-lg-4 product" bis_skin_checked="1"> <div class="product-item-list" bis_skin_checked="1"> <!-- Product Image Placeholder --> <div class="position-relative overflow-hidden placeholder-glow" bis_skin_checked="1"> <div class="card-img-top placeholder rounded card-img-top object-fit-cover rounded" background-color: #e9ecef;" bis_skin_checked="1"></div> <a class="stretched-link placeholder"></a> </div> <!-- Card Body Placeholders --> <div class="d-flex flex-column mt-4" bis_skin_checked="1"> <!-- Title & Wishlist --> <div class="d-flex justify-content-between align-items-center mb-2" bis_skin_checked="1"> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-8" style="width: 250px;"></span> </div> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder" style="width: 24px; height: 24px;"></span> </div> </div> <!-- Location --> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-5" style="height: 14px;"></span> </div> <!-- Meta (Participants/Duration) --> <div class="placeholder-glow my-2" bis_skin_checked="1"> <span class="placeholder col-5" style="height: 14px;"></span> </div> <!-- Price Placeholder --> <div class="placeholder-glow" bis_skin_checked="1"> <span class="placeholder col-3" style="height: 24px;"></span> <span class="placeholder col-2 ms-2" style="height: 16px;"></span> <span class="placeholder col-1 ms-2" style="height: 16px;"></span> </div> </div> </div> </div>');

        $.ajax({
            url: URL + '&ajax=true',
            type: "GET",
            data: formData,
            success: function (response) {
                $('#search-results').html(response.products);
                $('.filter-tags-container').html(response.filters);
                $('.placeholder-glow').remove();
                $('html, body').animate({ scrollTop: 0 }, 300);
                $('.filter-tags-container').removeClass('loading');
            },
            error: function (xhr) {
                alert('error');
            }

        });
    });

    $('.variant-value-btn').on('click', function () {
        const formLink = $(this).find('label').data('form-link');
        $('#add-to-cart').attr('action', formLink);
    });

    // Remove item
    $(document).on('click', '.btn-remove', function (e) {
        e.preventDefault();

        var $button = $(this);
        var productId = $button.data('variant-id');
        var $cartItem = $button.closest('.cart-item');

        // Show loading state

        $.ajax({
            url: '/cart/' + productId,
            type: 'DELETE',
            data: {
                _token: csrf_token,
            },
            dataType: 'json',
            success: function (response) {
                updateFields(response);
                $cartItem.fadeOut(300, function () {
                    $(this).remove();
                });
            },
            error: function (xhr) {
                var errorMessage = xhr.responseJSON && xhr.responseJSON.message
                    ? xhr.responseJSON.message
                    : 'An error occurred while removing item';
                alert(errorMessage);
                $button.html('<i class="material-icons">delete</i>');
            }
        });
    });

    // Update quantity
    $(document).on('change', '.quantity-input', function () {
        var $input = $(this);
        var productId = $input.data('id');
        var quantity = $input.val();
        var $cartItem = $input.closest('.cart-item');

        $.ajax({
            url: '/cart/' + productId,
            type: 'PUT',
            data: {
                _token: csrf_token,
                quantity: quantity
            },
            dataType: 'json',
            success: function (response) {
                $cartItem.find('.item-total').text(response.total);
                updateFields(response);
            },
            error: function (xhr) {
                var errorMessage = xhr.responseJSON && xhr.responseJSON.message
                    ? xhr.responseJSON.message
                    : 'An error occurred while updating quantity';
                alert(errorMessage);
                // Reset to previous value
                $input.val($input.data('previous-value'));
            }
        });
    });

    // Store previous value for quantity inputs
    $(document).on('focusin', '.quantity-input', function () {
        $(this).data('previous-value', $(this).val());
    });

    function updateFields(data) {
        $('#price-subtotal').html(data.sub_total);
        $('#price-discount').html(data.total_discount);
        $('#price-tax').html(data.tax);
        $('#price-total').html(data.price_total);
    }

});