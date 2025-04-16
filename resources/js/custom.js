document.addEventListener('DOMContentLoaded', function() {
    // Cookie Helper Functions
    function setCookie(name, value, days = 30) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        document.cookie = `${name}=${JSON.stringify(value)}; expires=${date.toUTCString()}; path=/`;
        console.log(document.cookie);
    }

    function getCookie(name) {
        const cookies = document.cookie.split('; ');
        const cookie = cookies.find(c => c.startsWith(`${name}=`));
        return cookie ? JSON.parse(cookie.split('=')[1]) : null;
    }

    // Initialize cart
    const cartCookie = getCookie('cart');
    const cart = cartCookie ? cartCookie : {};
    updateCartDisplay();

    // 1. Add to Cart Form Submission
    document.getElementById('add-to-cart').addEventListener('submit', function(event) {
        event.preventDefault();
        
        const form = this;
        const formData = new FormData(form);
        
        // Update local cart immediately for better UX
        const product = {
            id: formData.get('product_id'),
            name: formData.get('product_name'),
            price: formData.get('price'),
            quantity: parseInt(formData.get('quantity'))
        };
        
        addToCart(product);
        updateCartDisplay();
        
        // Then send to server
        // fetch(form.action, {
        //     method: 'POST',
        //     headers: {
        //         'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
        //         'Accept': 'application/json',
        //     },
        //     body: formData,
        // })
        // .then(response => {
        //     if (!response.ok) throw new Error('Network response was not ok');
        //     return response.json();
        // })
        // .then(data => {
        //     console.log('Server response:', data);
        //     // Optionally sync server response with local cart
        // })
        // .catch(error => {
        //     console.error('Error:', error);
        //     // Optionally revert local changes if server fails
        // });
    });

    // 2. Buy Now Button
    document.querySelector('.btn-add-to-cart').addEventListener('click', function() {
        // First ensure cart is updated
        const formData = new FormData(document.getElementById('add-to-cart'));
        const product = {
            id: formData.get('product_id'),
            name: formData.get('product_name'),
            price: formData.get('price'),
            quantity: parseInt(formData.get('quantity'))
        };
        
        addToCart(product);
        updateCartDisplay();
        
        // Then proceed to checkout
        window.location.href = '/checkout';
    });

    // 3. Thumbnail Click Handler
    document.querySelectorAll('.thumbnail-item').forEach(item => {
        item.addEventListener('click', function() {
            document.querySelectorAll('.thumbnail-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
            document.getElementById('mainProductImage').src = this.dataset.target;
        });
    });

    // Cart Management Functions
    function addToCart(product) {
        if (cart[product.id]) {
            cart[product.id].quantity += product.quantity;
        } else {
            cart[product.id] = product;
        }
        setCookie('cart', cart);
    }

    function removeFromCart(productId) {
        if (cart[productId]) {
            delete cart[productId];
            setCookie('cart', cart);
        }
    }

    function updateQuantity(productId, change) {
        if (cart[productId]) {
            cart[productId].quantity += change;
            if (cart[productId].quantity <= 0) {
                delete cart[productId];
            }
            setCookie('cart', cart);
            updateCartDisplay();
        }
    }

    function updateCartDisplay() {
        // Update cart count
        const count = Object.values(cart).reduce((sum, item) => sum + item.quantity, 0);
        document.querySelectorAll('.cart-count').forEach(el => {
            el.textContent = count;
        });
        
        // Update mini-cart if exists
        const miniCart = document.getElementById('mini-cart');
        if (miniCart) {
            miniCart.innerHTML = Object.values(cart).map(item => `
                <div class="cart-item">
                    <h4>${item.name}</h4>
                    <p>${item.price} × ${item.quantity}</p>
                    <span>${item.quantity}</span>
                    <button class="btn-remove" data-id="${item.id}">Remove</button>
                </div>
            `).join('');
            
            // Add event listeners to dynamically created buttons
            document.querySelectorAll('.btn-qty').forEach(btn => {
                btn.addEventListener('click', function() {
                    updateQuantity(this.dataset.id, parseInt(this.dataset.change));
                });
            });
            
            document.querySelectorAll('.btn-remove').forEach(btn => {
                btn.addEventListener('click', function() {
                    removeFromCart(this.dataset.id);
                    updateCartDisplay();
                });
            });
        }
    }
});