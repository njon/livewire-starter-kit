@if($crumb->defaultUrl)
  <li class="breadcrumb-item">
    <a href="{{ $crumb->defaultUrl->slug }}">{{ $crumb->translateAttribute('name') }}</a>
  </li>
  
  <!-- Recursively handle nested children -->
  @if(!empty($crumb->parent))
    @foreach($crumb->parent as $child)
      @include('partials.breadcrumb-item', ['crumb' => $child])
    @endforeach
  @endif
@endif