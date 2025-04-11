document.getElementById('add-to-cart').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent the default form submission

    const form = this;
    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value, // CSRF token
            'Accept': 'application/json',
        },
        body: formData,
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        // Handle success response
        alert('Product added to cart successfully!');
        console.log(data);
    })
    .catch(error => {
        // Handle error response
        alert('Failed to add product to cart.');
        console.error('Error:', error);
    });
});

document.querySelector('.btn-buy-now').addEventListener('click', function () {
fetch('/get-cookie', {
    method: 'GET',
    headers: {
        'Accept': 'application/json',
    },
    credentials: 'same-origin', // Include cookies in the request
})
.then(response => {
    if (!response.ok) {
        throw new Error('Network response was not ok');
    }
    return response.json();
})
.then(data => {
    // Handle the response from the server
    console.log('User Preferences:', data.user_preferences);
    alert('User Preferences: ' + data.user_preferences);
})
.catch(error => {
    console.error('Error:', error);
    alert('Failed to retrieve user preferences.');
});
});



// Thumbnail click handler
document.querySelectorAll('.thumbnail-item').forEach(item => {
    item.addEventListener('click', function () {
        document.querySelectorAll('.thumbnail-item').forEach(i => i.classList.remove('active'));
        this.classList.add('active');
        document.getElementById('mainProductImage').src = this.dataset.target;
    });
});

document.addEventListener('DOMContentLoaded', function() {
const cart = JSON.parse(getCookie('cart') || {});
updateCartDisplay();

// Add to cart
document.getElementById('btn-add-to-cart').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const product = {
        id: formData.get('product_id'),
        name: formData.get('product_name'),
        price: formData.get('price'),
        quantity: parseInt(formData.get('quantity'))
    };
    
    addToCart(product);
    updateCartDisplay();
});

// Cart functions
function addToCart(product) {
    if (cart[product.id]) {
        cart[product.id].quantity += product.quantity;
    } else {
        cart[product.id] = product;
    }
    setCookie('cart', JSON.stringify(cart), 30);
}

function removeFromCart(productId) {
    if (cart[productId]) {
        delete cart[productId];
        setCookie('cart', JSON.stringify(cart), 30);
    }
}

function updateQuantity(productId, newQuantity) {
    if (cart[productId]) {
        cart[productId].quantity = newQuantity;
        setCookie('cart', JSON.stringify(cart), 30);
    }
}

function updateCartDisplay() {
    // Update count
    const count = Object.values(cart).reduce((sum, item) => sum + item.quantity, 0);
    document.querySelectorAll('.cart-count').forEach(el => {
        el.textContent = count;
    });
    
    // Optional: Update mini-cart display
    updateMiniCart();
}

function updateMiniCart() {
    const miniCart = document.getElementById('mini-cart');
    if (miniCart) {
        miniCart.innerHTML = Object.values(cart).map(item => `
            <div class="cart-item">
                <h4>${item.name}</h4>
                <p>${item.price} × ${item.quantity}</p>
                <button onclick="updateQuantity('${item.id}', ${item.quantity - 1})">-</button>
                <button onclick="updateQuantity('${item.id}', ${item.quantity + 1})">+</button>
                <button onclick="removeFromCart('${item.id}')">Remove</button>
            </div>
        `).join('');
    }
}

// Cookie helpers (same as above)
function setCookie(name, value, days) { /* ... */ }
function getCookie(name) { /* ... */ }
});
