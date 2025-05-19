<div class="accordion-item">
    <h2 class="accordion-header accordion-button collapsed" id="reviews-container" type="button"
        data-bs-toggle="collapse" data-bs-target="#collapseReviews" aria-controls="collapseReviews">
        Customer Reviews
    </h2>

    <div id="collapseReviews" class="accordion-collapse collapse" aria-labelledby="reviews-container">
        <div class="accordion-product">
            <div class="product-reviews">

                <div class="average-rating mb-4">
                    <div class="d-flex align-items-center">
                        <div class="star-rating-display me-3">
                            @for($i = 1; $i <= 5; $i++) @if($i <=floor($product->average_rating)) ★ @elseif($i - 0.5
                                <=$product->average_rating) ½ @else ☆ @endif @endfor <span class="ms-2">
                                {{ number_format($product->average_rating, 1) }} out of 5</span>
                        </div>
                        <span class="text-muted">{{ $product->product_count }} {{ Str::plural('review', $product->product_count) }}</span>
                    </div>
                </div>

                @foreach($reviews as $review)
                <div class="review-card mb-4 p-3 border rounded">
                    <div class="d-flex justify-content-between">
                        <div class="reviewer-name fw-bold">
                            {{ $review->user ? $review->user->name : $review->name }}
                        </div>
                        <div class="review-date text-muted small">
                            {{ $review->created_at->format('M d, Y') }}
                        </div>
                    </div>

                    <div class="review-rating my-2 text-warning">
                        {{ $product->rating_stars }}
                    </div>

                    <div class="review-text my-2">
                        {{ $review->review }}
                    </div>

                    @if($review->verified_purchase)
                    <div class="verified-badge text-success small mb-2">
                        ✓ Verified Purchase
                    </div>
                    @endif

                    
                    <!-- 
                    @todo re-add at some point
                    <div class="helpful-section">
                        <button class="btn-helpful btn btn-sm btn-outline-secondary" data-review-id="{{ $review->id }}">
                            <span class="emoji">❤️</span> Helpful ({{ $review->helpful_count }})
                        </button>
                    </div> -->
                </div>
                @endforeach
                @auth
                @if(!$product->reviews()->where('user_id', auth()->id())->exists())
                <div class="mt-4">
                    <button class="btn btn-primary" data-bs-toggle="collapse" role="button" data-bs-target="#write-review"
                        aria-expanded="false" aria-controls="write-review">
                        Write a Review
                    </button>
                </div>
                @endif
                @else
                <div class="mt-4">
                    <!-- Button to trigger modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reviewRestrictionModal">
                        Leave a Review
                    </button>
                </div>
                @endauth
            </div>
        </div>
    </div>
</div>
@section('bottom-content')
    @include('products.reviews.create')
@endsection
    


<!-- // @todo fix this Include into section -->

<div class="modal fade" id="reviewRestrictionModal" tabindex="-1" aria-labelledby="reviewRestrictionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="reviewRestrictionModalLabel">
                    <i class="bi bi-star-fill text-warning me-2"></i>
                    How to Leave a Review
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                 @if(!Auth::check())
            <div class="mb-4 mt-4" id="write-review">
    <div>
        <h5 class="card-title mt-1 mb-4">Write a Review</h5>

        <!-- Assuming you have a route named 'product.reviews.store' -->
        <!-- <form action="" method="POST"> -->
        <form action="products/1/reviews" method="POST" id="review-form">

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
@else
                <div class="d-flex align-items-start mb-4">
                    <i class="bi bi-check2-circle text-success fs-5 mt-1 me-3"></i>
                    <div>
                        <h6 class="fw-bold mb-2">Review Eligibility</h6>
                        <p>You can review this item if you've purchased it. No registration required!</p>
                    </div>
                </div>

                <div class="d-flex align-items-start mb-3">
                    <i class="bi bi-envelope-open text-primary fs-5 mt-1 me-3"></i>
                    <div>
                        <h6 class="fw-bold mb-2">For Guest Buyers</h6>
                        <p>Use the review link from your order confirmation email.</p>
                    </div>
                </div>

                <div class="d-flex align-items-start">
                    <i class="bi bi-person-check text-info fs-5 mt-1 me-3"></i>
                    <div>
                        <h6 class="fw-bold mb-2">For Registered Users</h6>
                        <p>If you purchased while logged in, you can review directly from your account.</p>
                    </div>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>