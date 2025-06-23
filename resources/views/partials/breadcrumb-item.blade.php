@if($crumb->defaultUrl)
  <li class="breadcrumb-item">
    <a href="{{ $crumb->defaultUrl->slug }}">{{ $crumb->translateAttribute('name') }}</a>
  </li>
  
  <!-- Recursively handle nested children -->
  @if(!empty($crumb->children))
    @foreach($crumb->children as $child)
      @include('partials.breadcrumb-item', ['crumb' => $child])
    @endforeach
  @endif
@endif