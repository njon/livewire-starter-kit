<div class="accordion-item">
    <h2 class="accordion-header accordion-button collapsed sf" id="reviews-container" type="button"
        data-bs-toggle="collapse" data-bs-target="#collapseReviews" aria-controls="collapseReviews">
        {{ __('Customer Reviews') }}
    </h2>

    <section id="reviews">
        <div id="collapseReviews" class="accordion-collapse collapse" aria-labelledby="reviews-container">
            <div class="accordion-product">
                <div class="product-reviews">

                    <div class="average-rating mb-4">
                        <div class="d-flex align-items-center">
                            <div class="star-rating-display me-3">
                                @for($i = 1; $i <= 5; $i++) @if($i <=floor($product->average_rating)) ★ @elseif($i - 0.5
                                    <=$product->average_rating) ½ @else ☆ @endif @endfor <span class="ms-2">
                                            {{ number_format($product->average_rating, 1) }} {{ __('out of 5') }}</span>
                            </div>
                            <span class="text-muted">{{ $product->product_count }}
                                {{ Str::plural(__('review'), $product->product_count) }}</span>
                        </div>
                    </div>

                    @foreach($reviews as $review)
                    <div class="review-card mb-4 pb-4 border-bottom">
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
                            ✓ {{ __('Verified Purchase') }}
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
                        <button class="btn btn-primary" data-bs-toggle="collapse" role="button"
                            data-bs-target="#write-review" aria-expanded="false" aria-controls="write-review">
                            {{ __('Write a Review') }}
                        </button>
                    </div>
                    @endif
                    @else
                    <div class="mt-4">
                        <!-- Button to trigger modal -->
                        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal"
                            data-bs-target="#reviewRestrictionModal">
                            {{ __('Leave a Review') }}
                        </button>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </section>
</div>

@section('bottom-content')
@include('products.reviews.create')
@endsection