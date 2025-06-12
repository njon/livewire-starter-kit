<div id="top-bar" class="classic-color-bg">
  <div class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-center">
      <div class="mb-1 mb-md-0">
        MORE THAN 400 EXPERIENCES TO CHOOSE FROM
      </div>
      <div class="d-flex gap-3">
        <a href="#" class="text-white text-decoration-none hover-opacity">F.A.Q</a>
        <a href="#" class="text-white text-decoration-none hover-opacity">NEWSLETTER</a>
        <a href="#" class="text-white text-decoration-none hover-opacity">CONTACTS</a>
      </div>
    </div>
  </div>
</div>

<header>
  <div id="header-container">
    <div class="container">
      <div class="row align-items-center">
        <!-- Logo/Title Column -->
        <div class="justify-content-between d-flex py-3 align-items-center">
          <div>
            <h1 class="h3 mb-0">
              <a href="/" class="navbar-brand fw-bold text-danger fs-2" href="#">Giftify</a>
            </h1>
          </div>

          <!-- Contact/Live Chat Column -->
          <div>
            <div bis_skin_checked="1">
              <div class="d-flex flex-column flex-md-row justify-content-center gap-5 info-lines" bis_skin_checked="1">
                <!-- Company Info Card -->
                <div class="text-nowrap d-flex align-items-start gap-3 rounded-3 hover-lift" bis_skin_checked="1">
                  <span class="material-symbols-outlined mt-1 fs-2">phone</span>
                  <div bis_skin_checked="1">
                    <span class="d-block fw-semibold small text-dark mb-1">Phone Number</span>
                    <a href="tel:+396973391189"
                      class="text-decoration-none fw-medium text-muted d-flex align-items-center gap-1 info-text">
                      +39 697 339 1189
                    </a>
                  </div>
                </div>

                <!-- Support Info Card -->
                <div class="text-nowrap d-flex align-items-start gap-3 rounded-3 hover-lift" bis_skin_checked="1">
                  <span class="material-symbols-outlined mt-1 fs-2">support_agent</span>
                  <div bis_skin_checked="1">
                    <span class="d-block fw-semibold small text-dark mb-1">Live Chat</span>
                    <a href="#"
                      class="text-decoration-none fw-medium text-muted d-flex align-items-center gap-1 info-text">
                      Click to start chat
                    </a>
                  </div>
                </div>
                <div class="text-nowrap d-flex align-items-center gap-3 rounded-3 hover-lift" bis_skin_checked="1">
                  <div class="input-group">
                    <span class="input-group-text">
                      <span class="material-symbols-outlined hoverable-icon">search</span> </span>
                    <input type="search" class="form-control border-start-0" placeholder="Search for experiences"
                      aria-label="Search">
                  </div>
                </div>
              </div>

            </div>

          </div>

          <!-- Search/Auth Column -->
          <div>
            <div class="d-flex flex-column flex-md-row justify-content-end align-items-center gap-3">
              <div class="text-nowrap">
                <a href="#" class="text-decoration-none text-dark me-2">Register</a>
                <span class="text-muted">/</span>
                <a href="#" class="text-decoration-none text-dark ms-2">Login</a>
              </div>

              <span class="material-symbols-outlined hoverable-icon">favorite</span>
              <span class="material-symbols-outlined hoverable-icon" data-bs-toggle="offcanvas"
                data-bs-target="#shoppingCart" aria-controls="shoppingCart"
                aria-label="Toggle navigation">shopping_cart</span>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
  <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom border-top shadow-sm">
    <div class="container">
      <!-- Toggle for mobile -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
        aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Nav items -->
      <div class="collapse navbar-collapse text-aling-center align-center text-center " id="mainNavbar">
        <ul class="navbar-nav center align-items-center gap-lg-4">
          <li class="nav-item">
            <a class="nav-link" href="#"><i class="fa fa-heart text-danger me-1"></i> Father's Day</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"><i class="fa fa-birthday-cake text-warning me-1"></i> Birthdays</a>
          </li>
          <li class="nav-item">
            <a class="nav-link fw-semibold" href="#"><i class="fa fa-tags me-1"></i> Sale <span
                class="badge bg-danger">NOW ON!</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"><i class="fa fa-magic me-1"></i> Gift Finder</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"><i class="fa fa-gift text-success me-1"></i> Gift Cards</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"><i class="fa fa-briefcase me-1"></i> For Business</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- <div class="bottom-menu-container">
    <div class="container">
      <div class="row">
        <nav class="navbar navbar-expand-lg">
          <div class="container">
            <div class="collapse navbar-collapse" id="navbarNav">
              <ul class="navbar-nav mx-auto">
                @foreach(\Lunar\Models\Collection::with(['defaultUrl', 'children.defaultUrl'])->get() as $item)
                @if($item->parent_id == null)
                <li class="dropdown nav-item">
                  <a href="{{ $item->defaultUrl->slug }}" class="nav-link 
                  @if(!$item->children->isEmpty())
                    dropdown-toggle
                  @endif
                  @if(request()->url() == url($item->defaultUrl->slug))
                    active
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
                      <a href="{{ route('products.show', $child->defaultUrl->slug) }}"
                        class="@if(request()->url() == route('products.show', $child->defaultUrl->slug)) active @endif">
                        {{ $child->translateAttribute('name') }}
                      </a>
                    </li>
                    @endforeach
                  </ul>
                  @endif
                </li>
                @endif
                @endforeach
              </ul>
            </div>
          </div>
        </nav>
      </div>
    </div>
  </div> -->
</header>

@include('partials.cart-element')