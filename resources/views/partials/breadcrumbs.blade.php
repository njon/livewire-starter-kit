<nav class="mt-5"
  style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);"
  aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="/">{{ __('Home') }}</a>
    </li>

    @if ($parent_category)
    <li class="breadcrumb-item">
      <a href="{{ $parent_category->defaultUrl->slug }}">{{ $parent_category->translateAttribute('name') }}</a>
    </li>
    @endif

    @if ($child_category)
    <li class="breadcrumb-item">
      <a href="{{ $child_category->defaultUrl->slug }}">{{ $child_category->translateAttribute('name') }}</a>
    </li>
    @endif

    <!-- Current page (product name) -->
    <li class="breadcrumb-item active" aria-current="page">
      {{ $product->translateAttribute('name') }}
    </li>
  </ol>
</nav>
<script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [{
        "@type": "ListItem",
        "position": 1,
        "name": "Home",
        "item": "{{ url('/') }}"
      },
      @if($parent_category) {
        "@type": "ListItem",
        "position": 2,
        "name": "{{ $parent_category->translateAttribute('name') }}",
        "item": "{{ url($parent_category->defaultUrl->slug) }}"
      },
      @endif
      @if($child_category) {
        "@type": "ListItem",
        "position": 3,
        "name": "{{ $child_category->translateAttribute('name') }}",
        "item": "{{ url($child_category->defaultUrl->slug) }}"
      },
      @endif {
        "@type": "ListItem",
        "position": 4,
        "name": "{{ $product->translateAttribute('name') }}",
        "item": "{{ url()->current() }}"
      }
    ]
  }
</script>