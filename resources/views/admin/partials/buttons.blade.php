<nav class="fi-breadcrumbs">
    <ol class="fi-breadcrumbs-list ">
        <li class="fi-breadcrumbs-item">{{ $asset }}s</li>
        <li class="fi-breadcrumbs-item">
            <svg class="fi-breadcrumbs-item-separator" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon"> <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"></path> </svg>      
            <span class="fi-breadcrumbs-item-label">{{ __('List') }}</span>
        </li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
<h2 class="fw-bold text-dark mb-0">{{ $title }}</h2>

    <div class="d-flex gap-2">
        @foreach($buttons as $button)
            @if(isset($button['preview']))
                <button type="button" class="btn btn-outline-secondary">{{ __('Preview') }}</button>
            @endif

            @if(isset($button['save']))
                <button type="button" class="btn btn-primary" id="save-asset" onclick="document.querySelector('.submit-form').submit();">{{ __('Save changes') }}</button>
            @endif

            @if(isset($button['new']))
                <a class="btn btn-primary" type="button" href="{{ $button['link'] }}">
                    <i class="bi bi-plus-circle me-1"></i> {{ __('Create New') }}
                </a>
            @endif
            @if(isset($custom_button))
                {!! $custom_button !!}
            @endif
        @endforeach
    </div>
</div>

