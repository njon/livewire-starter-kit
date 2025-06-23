<nav class="mt-5" 
  style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);"
  aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="/">Home</a></li>
    
    @if ($breadcrums)
      <!-- Parent category -->
      <li class="breadcrumb-item">
        <a href="{{ $breadcrums->defaultUrl->slug }}">{{ $breadcrums->translateAttribute('name') }}</a>
      </li>
      
      <!-- Handle children recursively -->
      @foreach ($breadcrums->children as $crumb)
        @include('partials.breadcrumb-item', ['crumb' => $crumb])
      @endforeach
    @endif
    
    <!-- Current page (product name) -->
    <li class="breadcrumb-item active" aria-current="page">
      {{ $product->translateAttribute('name') }}
    </li>
  </ol>
</nav>

@php
// Flatten the breadcrumbs into a flat list with positions by traversing the tree recursively
function flattenBreadcrumbs($crumbs, &$result = [], &$pos = 2) {
    // Start from 2 because 1 will be reserved for homepage
    if (!$crumbs) return;
    $result[] = ['crumb' => $crumbs, 'position' => $pos++];
    if ($crumbs->children) {
        foreach ($crumbs->children as $child) {
            flattenBreadcrumbs($child, $result, $pos);
        }
    }
}
$flatBreadcrumbs = [];
flattenBreadcrumbs($breadcrums, $flatBreadcrumbs);
@endphp

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "Home",
      "item": "{{ url('/') }}"
    }
    @if (!empty($flatBreadcrumbs))
      ,@foreach ($flatBreadcrumbs as $index => $item)
      {
        "@type": "ListItem",
        "position": {{ $item['position'] }},
        "name": "{{ $item['crumb']->translateAttribute('name') }}",
        "item": "{{ url($item['crumb']->defaultUrl->slug) }}"
      }@if(!$loop->last),@endif
      @endforeach
    @endif
    ,{
      "@type": "ListItem",
      "position": {{ (count($flatBreadcrumbs) + 2) }},
      "name": "{{ $product->translateAttribute('name') }}",
      "item": "{{ url()->current() }}"
    }
  ]
}
</script>