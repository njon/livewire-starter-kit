@extends('admin.app')

@section('toolbar')
    @include('admin.partials.buttons', ['title' => 'View Article', 'asset' => 'Article', 'buttons' => [
        ['edit' => true, 'link' => route('admin.articles.edit', $article)]
    ]])
@endsection

@section('content')
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-header bg-transparent border-bottom py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h3 class="h5 mb-0">{{ __('Article Details') }}</h3>
            <div>
                @php
                    $badgeClass = match($article->status) {
                        'published' => 'bg-success',
                        'draft' => 'bg-warning',
                        default => 'bg-secondary'
                    };
                @endphp
                <span class="badge {{ $badgeClass }}">
                    {{ ucfirst($article->status) }}
                </span>
            </div>
        </div>
    </div>
    <div class="card-body">
        <!-- Article Meta -->
        <div class="row mb-4">
            <div class="col-md-6">
                <strong>Slug:</strong> <code>{{ $article->slug }}</code>
            </div>
            <div class="col-md-6">
                <strong>Status:</strong> {{ ucfirst($article->status) }}
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <strong>Created:</strong> {{ $article->created_at->format('M j, Y H:i') }}
            </div>
            <div class="col-md-4">
                <strong>Updated:</strong> {{ $article->updated_at->format('M j, Y H:i') }}
            </div>
            <div class="col-md-4">
                @if($article->published_at)
                    <strong>Published:</strong> {{ $article->published_at->format('M j, Y H:i') }}
                @else
                    <strong>Published:</strong> Not published
                @endif
            </div>
        </div>

        <hr>

        <!-- Language Tabs -->
        <ul class="nav nav-tabs mb-4" id="languageTabs" role="tablist">
            @foreach($languages as $index => $language)
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $index === 0 ? 'active' : '' }}" id="tab-{{ $language->code }}" data-bs-toggle="tab" data-bs-target="#content-{{ $language->code }}" type="button" role="tab">
                    <span class="fi fi-{{ strtolower($language->code) === 'en' ? 'gb' : strtolower($language->code) }}"></span>
                    {{ $language->name }}
                    @if($language->default)
                        <span class="badge bg-primary ms-1">Default</span>
                    @endif
                </button>
            </li>
            @endforeach
        </ul>

        <!-- Language Content -->
        <div class="tab-content" id="languageTabContent">
            @foreach($languages as $index => $language)
            <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="content-{{ $language->code }}" role="tabpanel">
                @php
                    $title = $article->translate('title', $language->code);
                    $content = $article->translate('content', $language->code);
                    $metaDescription = $article->translate('meta_description', $language->code);
                @endphp

                <div class="mb-4">
                    <h4>{{ __('Title') }} ({{ $language->name }})</h4>
                    @if($title)
                        <p class="h5">{{ $title }}</p>
                    @else
                        <p class="text-muted">No title provided for this language</p>
                    @endif
                </div>

                @if($metaDescription)
                <div class="mb-4">
                    <h5>{{ __('Meta Description') }} ({{ $language->name }})</h5>
                    <p class="text-muted">{{ $metaDescription }}</p>
                </div>
                @endif

                <div class="mb-4">
                    <h5>{{ __('Content') }} ({{ $language->name }})</h5>
                    @if($content)
                        <div class="border rounded p-3 bg-light">
                            {!! $content !!}
                        </div>
                    @else
                        <p class="text-muted">No content provided for this language</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection