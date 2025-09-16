@extends('layouts.app-services')

@section('title', $title)
@section('meta_description', $meta_description)

@section('content')

<article class="py-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">

            <header class="mb-5">
                <h1 class="display-5 fw-bold mb-3">{{ $title }}</h1>
            </header>

            <div class="article-content">
                {!! $content !!}
            </div>
        </div>
    </div>
</article>

<style>
.article-content {
    line-height: 1.7;
    font-size: 1.1rem;
}

.article-content h1,
.article-content h2,
.article-content h3,
.article-content h4,
.article-content h5,
.article-content h6 {
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.article-content p {
    margin-bottom: 1.5rem;
}

.article-content img {
    max-width: 100%;
    height: auto;
    border-radius: 0.375rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.article-content blockquote {
    border-left: 4px solid #e9ecef;
    padding-left: 1rem;
    margin: 1.5rem 0;
    font-style: italic;
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 0.375rem;
}

.article-content ul,
.article-content ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.article-content li {
    margin-bottom: 0.5rem;
}
</style>

@endsection