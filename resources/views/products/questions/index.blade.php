<!-- Price Range Accordion Item -->
<div class="accordion-item">
    <h2 class="accordion-header accordion-button collapsed" id="questions-container" type="button"
        data-bs-toggle="collapse" data-bs-target="#collapseQuestions" aria-controls="collapseQuestions">
        Questions and Answers
    </h2>

    <div id="collapseQuestions" class="accordion-collapse collapse" aria-labelledby="questions-container">
        <div class="accordion-product">
            <div class="mb-4">Showing {{ $questions->count() }} questions</div>

            @if($questions->isEmpty())
            <div class="alert alert-light" role="alert">
                No questions have been asked yet. Be the first to ask a question!
            </div>
            <div class="mt-3">
                <button type="button" class="btn btn-outline-success" data-bs-toggle="modal"
                    data-bs-target="#askQuestionModal">
                    Ask Question
                </button>
            </div>
            @endif

            @foreach($questions as $question)
            <div class="row mb-4">
                <div class="col">
                    <div class="d-flex flex-start">
                        <img class="review-avatar me-3"
                            src="{{ $question->user ? $question->user->avatar_url : 'https://mdbcdn.b-cdn.net/img/Photos/Avatars/img%20(10).webp' }}"
                            alt="avatar" width="50" height="50">
                        <div class="flex-grow-1 flex-shrink-1">
                            <div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="mb-1">
                                        {{ $question->user ? $question->user->name : 'Guest' }}
                                        <span class="small">-
                                            {{ $question->created_at->diffForHumans() }}</span>
                                    </p>
                                </div>
                                <p class="mb-2">
                                    {{ $question->question }}
                                </p>
                            </div>

                            @if($question->answer)
                            <div class="d-flex flex-start mt-4 bg-light p-3 rounded">
                                <a class="me-3" href="#">
                                    <img class="review-avatar"
                                        src="{{ $question->answerer->avatar_url ?? 'https://mdbcdn.b-cdn.net/img/Photos/Avatars/img%20(11).webp' }}"
                                        alt="avatar" width="50" height="50">
                                </a>
                                <div class="flex-grow-1 flex-shrink-1">
                                    <div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class="mb-1">
                                                {{ $question->answerer->name ?? 'Admin' }}
                                                <span class="small">-
                                                    {{ $question->answered_at->diffForHumans() }}</span>
                                            </p>
                                            @can('delete', $question)
                                            <form
                                                action="{{ route('products.questions.destroy', [$product, $question]) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endcan
                                        </div>
                                        <p class="mb-0">
                                            {{ $question->answer }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @elseif(1 == 1)
                            <div class="mt-3">
                                <form action="product/{{ $product->id }}/answer/{{ $question->id }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <textarea name="answer" class="form-control" rows="3"
                                            placeholder="Write your answer..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">Submit
                                        Answer</button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@section('bottom-content')
<!-- Ask Question Modal -->
<div class="modal fade" id="askQuestionModal" tabindex="-1" aria-labelledby="askQuestionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="askQuestionModalLabel">Ask a Question</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="products/1/questions" method="POST" id="ask-question-form">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="question" class="form-label">Your Question</label>
                        <textarea name="question" id="question" class="form-control" rows="5" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit Question</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="reviewRestrictionModal" tabindex="-1" aria-labelledby="reviewRestrictionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            @if(!Auth::check())
            <div class="card mb-4 mt-4 collapse" id="write-review">
                <div class="card-body">
                    <h5 class="card-title">Write a Review</h5>
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
@endsection