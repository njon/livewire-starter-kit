@extends('layouts.app')

@section('title', $title)
@section('meta_description', $meta_description)

@section('content')

<section class="pb-4">
    <div class="row">
        <div class="col-12">
            <h1 class="pt-5 fs-2">{{ $title }}</h1>
            <div class="text-muted mb-5 mt-3">
                Stay informed with our latest articles, insights, and updates.
            </div>

            @if($articles->count() > 0)
                <div class="row">
                    @foreach($articles as $article)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">
                                        <a href="{{ route('articles.show', $article->slug) }}" class="text-decoration-none text-dark">
                                            {{ $article->getTitle() }}
                                        </a>
                                    </h5>

                                    @if($article->getMetaDescription())
                                        <p class="card-text text-muted small">
                                            {{ Str::limit($article->getMetaDescription(), 120) }}
                                        </p>
                                    @else
                                        <p class="card-text text-muted small">
                                            {{ Str::limit(strip_tags($article->getContent()), 120) }}
                                        </p>
                                    @endif

                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                {{ $article->published_at->format('M j, Y') }}
                                            </small>
                                            <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-outline-primary btn-sm">
                                                {{ __('Read More') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($articles->hasPages())
                    <div class="d-flex justify-content-center mt-5">
                        {{ $articles->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fa fa-newspaper-o fa-3x text-muted"></i>
                    </div>
                    <h3 class="text-muted">{{ __('No Articles Found') }}</h3>
                    <p class="text-muted">{{ __('Check back later for new articles and updates.') }}</p>
                </div>
            @endif
        </div>
    </div>
</section>

@endsection