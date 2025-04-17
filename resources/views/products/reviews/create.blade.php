<div class="card mb-4 mt-4 collapse" id="write-review">
    <div class="card-body">
        <h5 class="card-title">Write a Review</h5>

        @if(!auth()->check())
            <div class="alert alert-info">
                Only customers who purchased this product can leave reviews. 
                We'll send a verification link to your email.
            </div>
        @endif

        <!-- <form action="{{ route('products.reviews.store', $product) }}" method="POST"> -->
        <form action="products/1/reviews" method="POST">
        
            @csrf

            @guest
                <div class="mb-3">
                    <label for="name" class="form-label">Your Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
            @endguest

            <div class="mb-3">
                <label class="form-label">Rating</label>
                <div class="star-rating">
                    @for($i = 5; $i >= 1; $i--)
                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" required>
                        <label for="star{{ $i }}">★</label>
                    @endfor
                </div>
            </div>

            <div class="mb-3">
                <label for="review" class="form-label">Your Review</label>
                <textarea name="review" id="review" class="form-control" rows="5" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Submit Review</button>
        </form>
    </div>
</div>
