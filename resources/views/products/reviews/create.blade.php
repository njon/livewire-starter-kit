
<div class="modal fade" id="reviewRestrictionModal" tabindex="-1" aria-labelledby="reviewRestrictionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            
            @if(!Auth::check())
            <div class="card mb-4 mt-4 collapse" id="write-review">
    <div class="card-body">
        <h5 class="card-title">Write a Review</h5>

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
@endif
        
        
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="reviewRestrictionModalLabel">
                    <i class="bi bi-star-fill text-warning me-2"></i>
                    How to Leave a Review
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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
            </div>

        </div>
    </div>
</div>