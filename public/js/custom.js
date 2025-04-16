const curCurrency = '€';

function loadCartItems() {
    fetch('/cartItems')
        .then(response => response.json())
        .then(data => {
            cartElement = document.querySelector('.cart');
            // Generate the HTML structure
            let cartHtml = `
                <div class="card-body p-0">
            `;
            
            if (data && Object.keys(data).length > 0) {
                cartHtml += `
                    <div class="list-group list-group-flush">
                `;
                
                // Loop through cart items
                for (const [productId, item] of Object.entries(data)) {
                    const itemTotal = (parseFloat(item.price.replace(curCurrency, '')) * item.quantity).toFixed(2);
                    
                    cartHtml += `
                        <div class="list-group-item py-3 cart-item">
                            <div class="row align-items-center">
                                <div class="col-md-1">
                                    <a href="${item.link}" class="flex-shrink-0 me-3">
                                        <img src="${item.image || 'placeholder.jpg'}" alt="${item.product_name}" class="rounded" width="80" height="auto">
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="mb-1 fw-bold">${item.product_name}</h6>
                                    <small class="text-muted">Price: ${item.price}</small>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control quantity-input" 
                                               value="${item.quantity}" 
                                               min="1" 
                                               data-id="${productId}">
                                    </div>
                                </div>
                                <div class="col-md-2 text-end">
                                    <span class="item-total">€${itemTotal}</span>
                                </div>
                                <div class="col-md-2 text-end">
                                    <button class="btn btn-sm btn-outline-danger btn-remove" data-id="${productId}">
                                        <span class="material-symbols-outlined product-icon">delete</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                }
                
                cartHtml += `
                    </div>
                `;
            } else {
                cartHtml += `
                    <div class="p-4 text-center">
                        <i class="material-icons display-4 text-muted">remove_shopping_cart</i>
                        <h5 class="mt-3">Your cart is empty</h5>
                        <a href="/" class="btn btn-primary mt-3">
                            <i class="material-icons">shopping_bag</i> Start Shopping
                        </a>
                    </div>
                `;
            }
            
            cartHtml += `
                </div>
            `;
            
            // Insert the generated HTML
            cartElement.innerHTML = cartHtml;
            
            // Update cart counter if exists
            const cartCounter = document.querySelector('.cart-count');
            if (cartCounter) cartCounter.textContent = Object.keys(data).length;
        })
        .catch(error => console.error('Error loading cart:', error));
}

function loadOffcanvasCartItems(showElement = true) {
    fetch('/cartItems')
        .then(response => response.json())
        .then(data => {
            cartElement = document.querySelector('#shoppingCart .offcanvas-body');
            if(showElement) {
                $('#shoppingCart').offcanvas('show');
            }
            // Generate the HTML structure
            let cartHtml = `
                <div class="d-flex flex-column h-100">
                    <div class="flex-grow-1 overflow-auto">
                        <ul class="list-group list-group-flush">
            `;

            if (data && Object.keys(data).length > 0) {
                // Loop through cart items
                for (const [productId, item] of Object.entries(data)) {
                    const itemTotal = (parseFloat(item.price.replace(curCurrency, '')) * item.quantity).toFixed(2);

                    cartHtml += `
                        <li class="cart-item list-group-item py-3">
                            <div class="d-flex align-items-start">
                                <a href="${item.link}" class="flex-shrink-0 me-3">
                                    <img src="${item.image || 'placeholder.jpg'}" alt="${item.product_name}" class="rounded" width="80" height="auto">
                                </a>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <h6 class="mb-1">${item.product_name}</h6>
                                        <button type="button" class="btn-close btn-sm btn-remove" data-id="${productId}" aria-label="Remove"></button>
                                    </div>
                                    <p class="mb-1">${item.quantity} × ${item.price}</p>
                                    <small class="text-muted">Total: €${itemTotal}</small>
                                </div>
                            </div>
                        </li>
                    `;
                }
            } else {
                cartHtml += `
                    <li class="list-group-item py-3 text-center">
                        <i class="material-icons display-4 text-muted">remove_shopping_cart</i>
                        <h5 class="mt-3">Your cart is empty</h5>
                        <a href="/" class="btn btn-primary mt-3">
                            <i class="material-icons">shopping_bag</i> Start Shopping
                        </a>
                    </li>
                `;
            }

            cartHtml += `
                        </ul>
                    </div>
                    <div class="border-top p-3">
                        <div class="d-flex justify-content-between mb-2">
                            <h6 class="mb-0">Subtotal:</h6>
                            <span class="fw-bold">€${calculateCartTotal(data)}</span>
                        </div>
                        <div class="d-grid gap-2">
                            <a href="/cart" class="btn btn-outline-dark">View cart</a>
                            <a href="/checkout" class="btn btn-dark">Checkout</a>
                        </div>
                    </div>
                </div>
            `;

            // Insert the generated HTML
            cartElement.innerHTML = cartHtml;

            // Update cart counter if exists
            const cartCounter = document.querySelector('.cart-count');
            if (cartCounter) cartCounter.textContent = Object.keys(data).length;
        })
        .catch(error => console.error('Error loading cart:', error));
}

document.addEventListener('DOMContentLoaded', function() {
    loadOffcanvasCartItems(false);
});

function calculateCartTotal(cartItems) {
    return Object.values(cartItems).reduce((total, item) => {
        const itemTotal = parseFloat(item.price.replace(curCurrency, '')) * item.quantity;
        return total + itemTotal;
    }, 0).toFixed(2);
}

let isLoading = false;

document.addEventListener('DOMContentLoaded', function() {
    loadCartItems();
});


$(document).ready(function() {

    var $pageInput = $('[name="page"]');

    $(window).scroll(function() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 200 && !isLoading) {
            loadMoreProducts();
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
    
    // Add to cart
    $('#add-to-cart').on('submit', function(e) {
        e.preventDefault();
        
        var $form = $(this);
        var $button = $form.find('.btn-add-to-cart');
        var originalText = $button.html();
        
        // Show loading state
        $button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Adding...');
        
        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            success: function(response) {
                updateCartUI(response);
                toastr.success(response.message);
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

    // Remove item
    $(document).on('click', '.btn-remove', function(e) {
        e.preventDefault();
        
        var $button = $(this);
        var productId = $button.data('id');
        var $cartItem = $button.closest('.cart-item');
        
        // Show loading state
        
        $.ajax({
            url: '/cart/remove',
            type: 'POST',
            data: {
                _token: $('[name="_token"]').val(),
                product_id: productId
            },
            dataType: 'json',
            success: function(response) {
                $cartItem.fadeOut(300, function() {
                    $(this).remove();
                    updateCartUI(response);
                });
                toastr.success(response.message);
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
            url: '/cart/update-quantity',
            type: 'POST',
            data: {
                _token: $('[name="_token"]').val(),
                product_id: productId,
                quantity: quantity
            },
            dataType: 'json',
            success: function(response) {
                $cartItem.find('.item-total').text(calculateItemTotal(response.cart[productId]));
                updateCartUI(response);
                toastr.success(response.message);
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

    function calculateItemTotal(item) {
        // Remove $ sign and calculate total
        const price = parseFloat(item.price.replace(curCurrency, ''));
        return curCurrency + (price * item.quantity).toFixed(2);
    }

    function updateCartUI(response) {
        if (response.total_items !== undefined) {
            $('.cart-counter').text(response.total_items);
        }
        if (response.cart_total !== undefined) {
            $('.cart-total').text(curCurrency + response.cart_total.toFixed(2));
        }
        loadOffcanvasCartItems();
        loadCartItems();
    }
});