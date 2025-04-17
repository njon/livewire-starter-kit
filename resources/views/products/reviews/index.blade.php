<div class="product-reviews mt-5">
    <h4>Customer Reviews</h4>
    
    <div class="average-rating mb-4">
        @php
            $averageRating = $product->reviews->avg('rating');
            $reviewCount = $product->reviews->count();
        @endphp
        <div class="d-flex align-items-center">
            <div class="star-rating-display me-3">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= floor($averageRating))
                        ★
                    @elseif($i - 0.5 <= $averageRating)
                        ½
                    @else
                        ☆
                    @endif
                @endfor
                <span class="ms-2">{{ number_format($averageRating, 1) }} out of 5</span>
            </div>
            <span class="text-muted">{{ $reviewCount }} {{ Str::plural('review', $reviewCount) }}</span>
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
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= $review->rating)
                        ★
                    @else
                        ☆
                    @endif
                @endfor
            </div>
            
            <div class="review-text my-2">
                {{ $review->review }}
            </div>
            
            @if($review->verified_purchase)
                <div class="verified-badge text-success small mb-2">
                    ✓ Verified Purchase
                </div>
            @endif
            
            <div class="helpful-section">
                <button class="btn-helpful btn btn-sm btn-outline-secondary" 
                        data-review-id="{{ $review->id }}">
                    <span class="emoji">❤️</span> Helpful ({{ $review->helpful_count }})
                </button>
            </div>
        </div>
    @endforeach

</div>

@auth
    @if(!$product->reviews()->where('user_id', auth()->id())->exists())
        <div class="mt-4" >
            <button class="btn btn-primary" data-bs-toggle="collapse" role="button" data-bs-target="#write-review" aria-expanded="false" aria-controls="write-review">
                Write a Review
            </button>
        </div>
    @endif
@else
    <div class="mt-4">
        <button class="btn btn-primary" data-bs-toggle="collapse" role="button" data-bs-target="#write-review"  aria-expanded="false" aria-controls="write-review">
            Write a Review
        </button>
    </div>
@endauth

@include('products.reviews.create')
