

  <nav class="navbar navbar-expand-md bg-dark border-bottom" data-bs-theme="dark">
  <div class="container">
    <a class="navbar-brand d-md-none" href="#">
      <svg class="bi" width="24" height="24" aria-hidden="true"><use xlink:href="#aperture"></use></svg>
      Aperture
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" aria-controls="offcanvas" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvas" aria-labelledby="offcanvasLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasLabel">Aperture</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <ul class="navbar-nav flex-grow-1">
          
      @foreach(\Lunar\Models\Collection::with(['defaultUrl', 'children.defaultUrl'])->get() as $item)
                @if($item->parent_id == null)
                    <li class="dropdown nav-item">
                        <a href="{{ $item->defaultUrl->slug }}" class="nav-link 
                            @if(!$item->children->isEmpty())
                                dropdown-toggle
                            @endif
                        " data-toggle="dropdown">
                            {{ $item->translateAttribute('name') }}
                            @if(!$item->children->isEmpty())
                                <span class="caret"></span>
                            @endif
                        </a>
                        @if(!$item->children->isEmpty())
                            <ul class="dropdown-menu">
                                @foreach($item->children as $child)
                                    <li>
                                        <a href="{{ route('products.show', $child->defaultUrl->slug) }}">
                                            {{ $child->translateAttribute('name') }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endif
            @endforeach
            <svg class="bi" width="24" height="24" aria-hidden="true"><use xlink:href="#cart"></use></svg>
          </a></li>
        </ul>
      </div>
    </div>
  </div>
</nav>

