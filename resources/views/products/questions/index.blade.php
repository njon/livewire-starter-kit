@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between mt-5">
        <div>
            <h2>Questions and Answers</h2>
        </div>

        <div>
            <button type="button" class="btn btn-outline-success p-3" data-bs-toggle="modal" data-bs-target="#askQuestionModal">
                ASK QUESTION
            </button>
        </div>
    </div>

    <div class="mb-4">Showing {{ $questions->count() }} questions</div>

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
                                <span class="small">- {{ $question->created_at->diffForHumans() }}</span>
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
                                        <span class="small">- {{ $question->answered_at->diffForHumans() }}</span>
                                    </p>
                                    @can('delete', $question)
                                    <form action="{{ route('products.questions.destroy', [$product, $question]) }}" method="POST">
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
                    @elseif(auth()->user() && auth()->user()->isAdmin())
                    <div class="mt-3">
                        <form action="{{ route('products.questions.answer', [$product, $question]) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <textarea name="answer" class="form-control" rows="3" placeholder="Write your answer..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Submit Answer</button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach

    {{ $questions->links() }}
</div>

<!-- Ask Question Modal -->
<div class="modal fade" id="askQuestionModal" tabindex="-1" aria-labelledby="askQuestionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="askQuestionModalLabel">Ask a Question</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('products.questions.store', $product) }}" method="POST">
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
@endsection