const curCurrency = '€';
let $form;

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

    const csrf_token = $('[name="_token"]').val()

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

});