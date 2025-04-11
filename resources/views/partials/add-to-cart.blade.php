<div class="add-to-cart-container">
    <form action="{{ route('cart.add') }}" method="POST" class="add-to-cart-form">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">

        <!-- Quantity selector -->
        <div class="form-group mb-3">
            <label for="quantity">{{ __('Quantity') }}</label>
            <div class="input-group">
                <button type="button" class="btn btn-outline-secondary quantity-minus">-</button>
                <input type="number" 
                       name="quantity" 
                       id="quantity" 
                       value="1" 
                       min="1" 
                       max="{{ $product->stock ?? 10 }}" 
                       class="form-control text-center quantity-input">
                <button type="button" class="btn btn-outline-secondary quantity-plus">+</button>
            </div>
        </div>

        <!-- Price display -->
        <div class="price-display mb-3">
            <span class="current-price h4">
                {{ $product->price }}
            </span>
            @if($product->compare_price)
                <span class="compare-price text-muted text-decoration-line-through ms-2">
                    {{ $product->compare_price }}
                </span>
            @endif
        </div>

        <!-- Add to cart button -->
        <button type="submit" class="btn btn-primary btn-lg w-100 add-to-cart-btn">
        <i class="fas fa-shopping-cart me-2"></i> {{ __('Add to Cart') }}

        </button>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Quantity controls
        document.querySelectorAll('.quantity-plus').forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.parentNode.querySelector('.quantity-input');
                input.value = parseInt(input.value) + 1;
                updateMaxQuantity();
            });
        });

        document.querySelectorAll('.quantity-minus').forEach(btn => {
            btn.addEventListener('click', function() {
                const input = this.parentNode.querySelector('.quantity-input');
                if (parseInt(input.value) > 1) {
                    input.value = parseInt(input.value) - 1;
                    updateMaxQuantity();
                }
            });
        });

        function updateMaxQuantity() {
            const maxStock = {{ $product->stock ?? 10 }};
            const quantityInput = document.querySelector('.quantity-input');
            
            if (parseInt(quantityInput.value) > parseInt(maxStock)) {
                quantityInput.value = maxStock;
            }
        }
    });
</script>
@endpush