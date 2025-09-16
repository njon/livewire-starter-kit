@extends('admin.app')

@section('toolbar')
    @include('admin.partials.buttons', ['title' => 'Edit Article', 'asset' => 'Article', 'buttons' => [
        ['save' => true]
    ]])
@endsection

@section('content')
<form method="POST" action="{{ route('admin.articles.update', $article) }}" class="submit-form">
    @csrf
    @method('PUT')
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-transparent border-bottom py-3">
            <h3 class="h5 mb-0">{{ __('Edit Article') }}</h3>
        </div>
        <div class="card-body">
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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="title_{{ $language->code }}" class="form-label">{{ __('Title') }} ({{ $language->name }})</label>
                                <input type="text" class="form-control @error('title.'.$language->code) is-invalid @enderror"
                                       id="title_{{ $language->code }}" name="title[{{ $language->code }}]"
                                       value="{{ old('title.'.$language->code, $article->translate('title', $language->code)) }}" required>
                                @error('title.'.$language->code)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="content_{{ $language->code }}" class="form-label">{{ __('Content') }} ({{ $language->name }})</label>
                                <textarea class="form-control tinymce @error('content.'.$language->code) is-invalid @enderror"
                                          id="content_{{ $language->code }}" name="content[{{ $language->code }}]"
                                          rows="15">{{ old('content.'.$language->code, $article->translate('content', $language->code)) }}</textarea>
                                @error('content.'.$language->code)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="meta_description_{{ $language->code }}" class="form-label">{{ __('Meta Description') }} ({{ $language->name }})</label>
                                <textarea class="form-control @error('meta_description.'.$language->code) is-invalid @enderror"
                                          id="meta_description_{{ $language->code }}" name="meta_description[{{ $language->code }}]"
                                          rows="3" maxlength="255">{{ old('meta_description.'.$language->code, $article->translate('meta_description', $language->code)) }}</textarea>
                                @error('meta_description.'.$language->code)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Optional. Used for SEO meta description tag.</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <hr class="my-4">

            <!-- Article Settings -->
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">{{ __('Current Slug') }}</label>
                        <div class="form-control-plaintext bg-light border rounded px-3 py-2">{{ $article->slug }}</div>
                        <div class="form-text">Auto-generated from title. Whitespaces removed, Greek letters converted to Latin.</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="status" class="form-label">{{ __('Status') }}</label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="draft" {{ old('status', $article->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $article->status) === 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row" id="published-date-field" style="{{ old('status', $article->status) === 'published' ? 'display: block;' : 'display: none;' }}">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="published_at" class="form-label">{{ __('Published Date') }}</label>
                        <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror"
                               id="published_at" name="published_at"
                               value="{{ old('published_at', $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '') }}">
                        @error('published_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Leave blank to use current date/time</div>
                    </div>
                </div>
            </div>

            <!-- Article Info -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">Article Information</h6>
                            <p class="card-text">
                                <strong>Created:</strong> {{ $article->created_at->format('M j, Y H:i') }}<br>
                                <strong>Last Updated:</strong> {{ $article->updated_at->format('M j, Y H:i') }}<br>
                                @if($article->published_at)
                                    <strong>Published:</strong> {{ $article->published_at->format('M j, Y H:i') }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">{{ __('Update Article') }}</button>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize TinyMCE
    tinymce.init({
        selector: '.tinymce',
        height: 400,
        menubar: false,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks | ' +
            'bold italic backcolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'removeformat | code | help',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
    });

    // Show/hide published date field based on status
    const statusSelect = document.getElementById('status');
    const publishedDateField = document.getElementById('published-date-field');

    function togglePublishedDate() {
        if (statusSelect.value === 'published') {
            publishedDateField.style.display = 'block';
        } else {
            publishedDateField.style.display = 'none';
        }
    }

    statusSelect.addEventListener('change', togglePublishedDate);
});
</script>
@endsection