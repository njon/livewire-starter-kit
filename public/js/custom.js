const curCurrency = '€';
let $form;

$(document).ready(function() {
    // Initialize variables
    let currentImageIndex = 0;
    let images = [];
    
    // Collect all images from the gallery
    function initGallery() {
        images = [];
        
        // Add main image first
        images.push($('#mainProductImage').attr('src'));
        
        // Add thumbnail images
        $('.thumbnail-item').each(function() {
            images.push($(this).data('target'));
        });
    }
    
    // Initialize the gallery
    initGallery();
    
    // Click handler for thumbnails
    $('.thumbnail-item').click(function() {
        currentImageIndex = $(this).index() + 1; // +1 because main image is first
        $('#modalImage').attr('src', $(this).data('target'));
        $('#imageGalleryModal').modal('show');
    });
    
    // Click handler for main image
    $('#mainProductImage').click(function() {
        currentImageIndex = 0;
        $('#modalImage').attr('src', $(this).attr('src'));
        $('#imageGalleryModal').modal('show');
    });
    
    // Previous image button
    $('#prevImage').click(function() {
        currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
        $('#modalImage').attr('src', images[currentImageIndex]);
    });
    
    // Next image button
    $('#nextImage').click(function() {
        currentImageIndex = (currentImageIndex + 1) % images.length;
        $('#modalImage').attr('src', images[currentImageIndex]);
    });
    
    // Keyboard navigation
    $(document).keydown(function(e) {
        if ($('#imageGalleryModal').hasClass('show')) {
            if (e.keyCode == 37) { // Left arrow
                $('#prevImage').click();
            } else if (e.keyCode == 39) { // Right arrow
                $('#nextImage').click();
            }
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {


  // Parse initial values from the HTML
  const daysElement = document.getElementById('countdown-days');
  const timeElement = document.getElementById('countdown-time');
  
  if(!daysElement || !timeElement) {
    return false;
  }
  // Extract initial values (trim whitespace)
  let days = parseInt(daysElement.textContent.trim());
  let [hours, minutes, seconds] = timeElement.textContent.trim().split(':').map(Number);
  
  // Update the countdown every second
  const countdown = setInterval(function() {
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
            if(!cartElement) {
                return false;
            }

            if(showElement) {
                $('#shoppingCart').offcanvas('show');
            }
            // Generate the HTML structure
            cartElement.innerHTML = data.html; 

            const cartCounter = document.querySelector('.cart-count');
            if (cartCounter) cartCounter.textContent = Object.keys(data).length;
        })
        .catch(error => console.error('Error loading cart:', error));
}

document.addEventListener('DOMContentLoaded', function() {
    loadOffcanvasCartItems(false);
});

let isLoading = false;

$(document).ready(function() {


function loadBtn(btn) {
    const $button = btn;
      // Button Off
  if ($button.hasClass('is-active')) {
    $button
      .removeClass('is-active');
    return;
  }
  
  // Button On (with a loader)
  $button.addClass('is-loading');  
  setTimeout(function () {
    $button
      .removeClass('is-loading')
      .addClass('is-active');
  }, 500);
}


    const csrf_token = $('[name="_token"]').val();

    
    $('#review-form').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission
        
        var $form = $(this);
        var $submitBtn = $form.find('button[type="submit"]');
        var originalBtnText = $submitBtn.text();
        
        // Show loading state
        $submitBtn.prop('disabled', true).text('Submitting...');
        
        $.ajax({
        url: $form.attr('action'),
        type: $form.attr('method'),
        data: $form.serialize(),
        dataType: 'json',
        success: function(response) {
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
        error: function(xhr) {
            // Error handling
            var errors = xhr.responseJSON.errors;
            var errorHtml = '<div class="alert alert-danger"><ul>';
            
            $.each(errors, function(key, value) {
            errorHtml += '<li>' + value + '</li>';
            });
            
            errorHtml += '</ul></div>';
            
            $form.before(errorHtml);
            $submitBtn.prop('disabled', false).text(originalBtnText);
        },
        complete: function() {
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
        $.get('wishlist/ajax-items', function(response) {
            $('.wishlist-add').each(function() {
                const $btn = $(this);
                const productId = $btn.data('product-id');
                const isActive = response.hasOwnProperty(productId);
                $btn.toggleClass('is-active', isActive);
                $btn.closest('.btn-wishlist').toggleClass('is-active', isActive);
            });
        });
    }

    // Toggle wishlist item
    $(document).on('click', '.wishlist-add', function(e) {
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
            success: function() {
                $button.removeClass('is-loading');
                
                if(!isActive) {
                    $button.addClass('is-active');
                }

                updateWishlist();
            }
        });
    });

    // Initial update
    updateWishlist();

    $('.btn-helpful').click(function() {
        const button = $(this);
        const reviewId = button.data('review-id');
        
        $.ajax({
            url: `/reviews/${reviewId}/helpful`,
            method: 'POST',
            data: {
                _token: csrf_token
            },
            success: function(response) {
                button.find('.emoji').text('❤️');
                button.html(`<span class="emoji">❤️</span> Helpful (${response.count})`);
            },
            error: function() {
                alert('Error marking as helpful');
            }
        });
    });

    var $pageInput = $('[name="page"]');

    $('#add-to-cart').on('submit', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var $button = $form.find('.btn-add-to-cart');
        var originalText = $button.html();
        
        $button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...');
        
        $.ajax({
            url: $form.attr('action'),
            type: 'PUT',
            data: $form.serialize(),
            dataType: 'json',
            success: function(response) {
                loadOffcanvasCartItems();
            },
            error: function(xhr) {
                var errorMessage = xhr.responseJSON && xhr.responseJSON.message 
                    ? xhr.responseJSON.message 
                    : 'An error occurred while adding to cart';
                toastr.error(errorMessage);
            },
            complete: function() {
                $button.prop('disabled', false).html(originalText);
            }
        });
    });

    // $('form').on('#ask-question-form', function(e) {

    //     var notAjax = $(this).data('not-ajax');

    //     if (notAjax == true) {
    //         return false;
    //     }

    //     $form = $(this);

    //     e.preventDefault(); // Prevent default form submission
        
    //     // Get form data
    //     var formData = $(this).serialize();
    //     var formAction = $(this).attr('action');
    //     var submitButton = $(this).find('button[type="submit"]');
        
    //     // Disable submit button to prevent multiple submissions
    //     submitButton.prop('disabled', true).html(
    //         '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...'
    //     );
        
    //     // AJAX request
    //     $.ajax({
    //         url: formAction,
    //         type: 'POST',
    //         data: formData,
    //         headers: {
    //             'X-CSRF-TOKEN': csrf_token // Include CSRF token in headers
    //         },
    //         success: function(response) {
    //             if (response.success) {
    //                 toastr.success(response.message || 'Question submitted successfully!');
    //                 $('#askQuestionModal').modal('hide');
    //                 $('#question').val('');
    //             }
    //         },
    //         error: function(xhr) {
    //             // Handle errors
    //             var errorMessage = 'An error occurred. Please try again.';
                
    //             if (xhr.responseJSON && xhr.responseJSON.message) {
    //                 errorMessage = xhr.responseJSON.message;
    //             } else if (xhr.statusText) {
    //                 errorMessage = xhr.statusText;
    //             }
                
    //             toastr.error(errorMessage);
    //         },
    //         complete: function() {
    //             submitButton.prop('disabled', false).html('Submit Question');
    //         }
    //     });
    // });

    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 200 && !isLoading) {
            // loadMoreProducts();
        }
    });

    function loadMoreProducts() {
        isLoading = true;
        var nextPage = parseInt($pageInput.val()) + 1;
        $pageInput.val(nextPage);
        fetchResults(append = true);
    }

    function fetchResults(append = false) {
        const formData = $('#ajax-search-form').serialize();
        const URL = location.protocol + '//' + location.host + location.pathname + '?' + formData;
        window.history.pushState('page2', 'Title', URL);
        $('#search-results').append('<div class="col-6 col-lg-4 mb-4 placeholder-glow"> <div class="card h-100"> <!-- Image placeholder --> <div class="bg-image hover-zoom ripple ripple-surface ripple-surface-light placeholder" data-mdb-ripple-color="light" style="height: 415px;"> <div class="card-img-top w-100 placeholder" style="background-color: #eee;height: 415px;"></div> <a href="#!"> <div class="mask"> <div class="d-flex justify-content-start align-items-end h-100"></div> </div> <div class="hover-overlay"> <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div> </div> </a> </div> <div class="card-body"> <!-- Title placeholder --> <h3 class="card-title mb-3 placeholder-wave"> <span class="placeholder col-8"></span> </h3> <!-- City placeholder --> <div class="product-city mb-2 placeholder-wave"> <span class="placeholder col-6"></span> </div> <!-- Info placeholders --> <div class="bottom-info mb-3 placeholder-wave"> <span class="placeholder col-4 me-2"></span> <span class="placeholder col-4"></span> </div> <!-- Price placeholder --> <div class="product-listing-price placeholder-wave"> <span class="placeholder col-3"></span> </div> </div> </div> </div><div class="col-lg-4 col-md-4 mb-4 placeholder-glow"> <div class="card h-100"> <!-- Image placeholder --> <div class="bg-image hover-zoom ripple ripple-surface ripple-surface-light placeholder" data-mdb-ripple-color="light" style="height: 415px;"> <div class="card-img-top w-100 placeholder" style="background-color: #eee;height: 415px;"></div> <a href="#!"> <div class="mask"> <div class="d-flex justify-content-start align-items-end h-100"></div> </div> <div class="hover-overlay"> <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div> </div> </a> </div> <div class="card-body"> <!-- Title placeholder --> <h3 class="card-title mb-3 placeholder-wave"> <span class="placeholder col-8"></span> </h3> <!-- City placeholder --> <div class="product-city mb-2 placeholder-wave"> <span class="placeholder col-6"></span> </div> <!-- Info placeholders --> <div class="bottom-info mb-3 placeholder-wave"> <span class="placeholder col-4 me-2"></span> <span class="placeholder col-4"></span> </div> <!-- Price placeholder --> <div class="product-listing-price placeholder-wave"> <span class="placeholder col-3"></span> </div> </div> </div> </div><div class="col-lg-4 col-md-4 mb-4 placeholder-glow"> <div class="card h-100"> <!-- Image placeholder --> <div class="bg-image hover-zoom ripple ripple-surface ripple-surface-light placeholder" data-mdb-ripple-color="light" style="height: 415px;"> <div class="card-img-top w-100 placeholder" style="background-color: #eee;height: 415px;"></div> <a href="#!"> <div class="mask"> <div class="d-flex justify-content-start align-items-end h-100"></div> </div> <div class="hover-overlay"> <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div> </div> </a> </div> <div class="card-body"> <!-- Title placeholder --> <h3 class="card-title mb-3 placeholder-wave"> <span class="placeholder col-8"></span> </h3> <!-- City placeholder --> <div class="product-city mb-2 placeholder-wave"> <span class="placeholder col-6"></span> </div> <!-- Info placeholders --> <div class="bottom-info mb-3 placeholder-wave"> <span class="placeholder col-4 me-2"></span> <span class="placeholder col-4"></span> </div> <!-- Price placeholder --> <div class="product-listing-price placeholder-wave"> <span class="placeholder col-3"></span> </div> </div> </div> </div><div class="col-lg-4 col-md-4 mb-4 placeholder-glow"> <div class="card h-100"> <!-- Image placeholder --> <div class="bg-image hover-zoom ripple ripple-surface ripple-surface-light placeholder" data-mdb-ripple-color="light" style="height: 415px;"> <div class="card-img-top w-100 placeholder" style="background-color: #eee;height: 415px;"></div> <a href="#!"> <div class="mask"> <div class="d-flex justify-content-start align-items-end h-100"></div> </div> <div class="hover-overlay"> <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div> </div> </a> </div> <div class="card-body"> <!-- Title placeholder --> <h3 class="card-title mb-3 placeholder-wave"> <span class="placeholder col-8"></span> </h3> <!-- City placeholder --> <div class="product-city mb-2 placeholder-wave"> <span class="placeholder col-6"></span> </div> <!-- Info placeholders --> <div class="bottom-info mb-3 placeholder-wave"> <span class="placeholder col-4 me-2"></span> <span class="placeholder col-4"></span> </div> <!-- Price placeholder --> <div class="product-listing-price placeholder-wave"> <span class="placeholder col-3"></span> </div> </div> </div> </div>');

        $.ajax({
            url: "/ajax-search",
            type: "GET",
            data: formData,
            success: function(response) {
                if(append) {
                    $('#search-results').append(response);
                } else {
                    $('#search-results').html(response);
                }
                $('.placeholder-glow').remove();
                isLoading = false;
            },
            error: function(xhr) {
                $('#search-results').html(`
                    <div class="alert alert-danger">
                        An error occurred while searching. Please try again.
                    </div>
                `);
            }
        });
    }

    $('#ajax-search-form').on('submit', function(e) {
        e.preventDefault();
        fetchResults();
    });

    // Handle input changes for real-time search
    $('#search-query, #min-price, #max-price').on('input', function() {
        // Add slight delay to prevent too many requests
        clearTimeout($(this).data('timer'));
        $(this).data('timer', setTimeout(fetchResults, 500));
    });
    

    $('.variant-value-btn').on('click', function() {
        const formLink = $(this).data('form-link');
        $('#add-to-cart').attr('action', formLink);
    });


    // Remove item
    $(document).on('click', '.btn-remove', function(e) {
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
            success: function(response) {
                updateFields(response);
                $cartItem.fadeOut(300, function() {
                    $(this).remove();
                });
            },
            error: function(xhr) {
                var errorMessage = xhr.responseJSON && xhr.responseJSON.message 
                    ? xhr.responseJSON.message 
                    : 'An error occurred while removing item';
                toastr.error(errorMessage);
                $button.html('<i class="material-icons">delete</i>');
            }
        });
    });

    // Update quantity
    $(document).on('change', '.quantity-input', function() {
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
            success: function(response) {
                $cartItem.find('.item-total').text(response.total);
                updateFields(response);
            },
            error: function(xhr) {
                var errorMessage = xhr.responseJSON && xhr.responseJSON.message 
                    ? xhr.responseJSON.message 
                    : 'An error occurred while updating quantity';
                toastr.error(errorMessage);
                // Reset to previous value
                $input.val($input.data('previous-value'));
            }
        });
    });

    // Store previous value for quantity inputs
    $(document).on('focusin', '.quantity-input', function() {
        $(this).data('previous-value', $(this).val());
    });

    function updateFields(data) {
    // Update cart summary fields with new values
        $('#price-subtotal').html(data.sub_total);
        $('#price-discount').html(data.total_discount);
        $('#price-tax').html(data.tax);
        $('#price-total').html(data.price_total);
}

});