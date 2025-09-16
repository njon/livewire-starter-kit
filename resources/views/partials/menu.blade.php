<header id="header" class="position-relative">
  <div id="top-bar" class="classic-color-bg">
    <div class="container">
      <div class="d-flex flex-wrap justify-content-between align-items-center">
        <div class="mb-1 mb-md-0">
          {{ __('MORE THAN 400 EXPERIENCES TO CHOOSE FROM') }}
        </div>
        <div class="d-flex gap-3">
          <a href="#" class="text-white text-decoration-none hover-opacity">{{ __('F.A.Q') }}</a>
          <a href="#" class="text-white text-decoration-none hover-opacity">{{ __('Newsletter') }}</a>
          <a href="#" class="text-white text-decoration-none hover-opacity">{{ __('Contacts') }}</a>
        </div>
      </div>
    </div>
  </div>
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
                  <i class="d-block small text-dark mb-1 fa fa-mobile mt-1 fs-2"></i>
                  <div bis_skin_checked="1">
                    <span class="d-block fw-semibold small text-dark mb-1">{{ __('Phone Number') }}</span>
                    <a href="tel:+396973391189"
                      class="text-decoration-none fw-medium text-muted d-flex align-items-center gap-1 info-text">
                      +39 697 339 1189
                    </a>
                  </div>
                </div>

                <!-- Support Info Card -->
                <div class="text-nowrap d-flex align-items-start gap-3 rounded-3 hover-lift" bis_skin_checked="1">
                  <i class="d-block small text-dark mb-1 fa fa-comments-o mt-1 fs-2"></i>
                  <div bis_skin_checked="1">
                    <span class="d-block fw-semibold small text-dark mb-1">{{ __('Live Chat') }}</span>
                    <a href="#"
                      class="text-decoration-none fw-medium text-muted d-flex align-items-center gap-1 info-text">
                      {{ __('Click to start chat') }}
                    </a>
                  </div>
                </div>
                <div class="text-nowrap d-flex align-items-center gap-3 rounded-3 hover-lift" bis_skin_checked="1">
                  <div class="input-group">
                    <span class="input-group-text">
                      <i class="fa fa-search" aria-hidden="true"></i>
 
                    </span>
                    <input type="search" class="form-control border-start-0 min-width-400"
                      placeholder="{{ __('Search for experiences') }}" aria-label="{{ __('Search') }}">
                  </div>
                </div>
                <div class="text-nowrap d-flex align-items-center gap-3 rounded-3 hover-lift" bis_skin_checked="1">
                  <button class="btn btn-outline-dark fs-14">
                    {{ __('Claim Voucher') }}
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Search/Auth Column -->
          <div>
            <div class="d-flex flex-column flex-md-row justify-content-end align-items-center gap-3">
              <div class="text-nowrap">
                @if(auth()->check())
                <a href="/profile" class="text-decoration-none text-dark me-2">{{ __('Profile') }}</a>
                @else
                <a href="{{ route('register') }}" class="text-decoration-none text-dark me-2">{{ __('Register') }}</a>
                <span class="text-muted">/</span>
                <a href="{{ route('login') }}" class="text-decoration-none text-dark ms-2">{{ __('Login') }}</a>
                @endif
              </div>

              <a href="{{ route('wishlist.index') }}"
                class="material-symbols-outlined hoverable-icon text-dark text-decoration-none">
                <i class="fa fa-heart-o" aria-hidden="true"></i>
              </a>
              <i class="fa fa-shopping-basket material-symbols-outlined hoverable-icon" data-bs-toggle="offcanvas"
                data-bs-target="#shoppingCart" aria-controls="shoppingCart" aria-label="Toggle navigation"
                aria-hidden="true"></i>

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
      <div class="collapse navbar-collapse text-align-center align-center text-center" id="mainNavbar">
        <ul class="navbar-nav center align-items-center gap-lg-4">
          <li class="nav-item dropdown mega-menu">
            <a class="nav-link dropdown-toggle" href="#" id="dropdownExperiences" role="button"
              data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fa fa-magic text-danger me-1"></i> {{ __('Browse Experiences') }}
            </a>
            <div class="dropdown-menu shadow border menu-mega p-0" aria-labelledby="dropdownExperiences">
              <div class="row gx-0">
                <div class="col-lg-4 pe-0 py-2 category-list">
                  <ul class="list-unstyled">
                    @foreach(\Lunar\Models\Collection::with(['defaultUrl', 'children.defaultUrl'])->get() as $mainCategory)
                      @if($mainCategory->parent_id == null)
                      <li class="main-category @if($loop->first) active @endif" data-target="cat-{{ $loop->iteration }}" data-image="https://animated-dollop-g5v44x4gg5fwq7-80.app.github.dev/images/{{ get_category_image($mainCategory->translateAttribute('name')) }}">
                        <a href="{{ url($mainCategory->defaultUrl->slug) }}">{{ $mainCategory->translateAttribute('name') }}</a>
                      </li>
                      @endif
                    @endforeach
                  </ul>
                </div>

                <div class="col-lg-4 ps-0 py-2 subcategory-container">
                  @foreach(\Lunar\Models\Collection::with(['defaultUrl', 'children.defaultUrl'])->get() as $mainCategory)
                  @if($mainCategory->parent_id == null)
                  <div class="subcategory-group @if(!$loop->first) d-none @endif" id="cat-{{ $loop->iteration }}">
                    <ul class="list-unstyled">
                      @foreach($mainCategory->children as $subCategory)
                      <li>
                        <a href="{{ url($subCategory->defaultUrl->slug) }}"
                          class="d-block w-100 px-3 py-2 text-body text-decoration-none hover-bg @if(request()->url() == url($subCategory->defaultUrl->slug)) active @endif">
                          {{ $subCategory->translateAttribute('name') }}
                        </a>
                      </li>
                      @endforeach
                    </ul>
                  </div>
                  @endif
                  @endforeach
                </div>
                <div class="col-lg-4 p-3 description-container">
                  <div class="menu-image">
                    <img src="https://animated-dollop-g5v44x4gg5fwq7-80.app.github.dev/images/action1.png" alt="Menu Image" id="menu-description-image" class="img-fluid">
                  </div>
                  <div class="menu-description">
                    <h5 class="fw-bold mt-3 mb-3">{{ __('Explore Our Experiences') }}</h5>
                    <p class="text-muted">{{ __('Discover a wide range of unique experiences tailored to your interests. From thrilling adventures to relaxing getaways, we have something for everyone.') }}</p>
                  </div>
                </div>
              </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"><i class="fa fa-heart text-danger me-1"></i> {{ __('Father\'s Day') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"><i class="fa fa-birthday-cake text-warning me-1"></i> {{ __('Birthdays') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link fw-semibold" href="#"><i class="fa fa-tags me-1"></i> {{ __('Sale') }} <span
                class="badge bg-danger">{{ __('NOW ON!') }}</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"><i class="fa fa-search me-1"></i> {{ __('Gift Finder') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"><i class="fa fa-gift text-success me-1"></i> {{ __('Gift Cards') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"><i class="fa fa-briefcase me-1"></i> {{ __('For Business') }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('articles.index') }}"><i class="fa fa-newspaper-o me-1"></i> {{ __('Articles') }}</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>

@include('partials.cart-element')

<div class="absolute blur"> </div>