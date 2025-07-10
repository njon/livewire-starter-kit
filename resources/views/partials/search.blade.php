<script>
  min = 0;
  max = 300;
  curMin = {{ request()->get('min_price', 0) }}
  curMax = {{ request()->get('max_price', 300) }}
</script>

<form action="https://crispy-rotary-phone-6rx99vvv952567j-80.app.github.dev/sale" method="GET" id="ajax-search-form" class="search-form pt-5">
  <div class="accordion mb-4" id="filterAccordion">
    <div class="">
      <h5 class="mb-3 fs-6 fw-600">{{ __('Price Selector') }}</h5>
      <h6 class="mb-5 fw-normal info-text text-muted">{{ __('Select Your Preferred Price Range') }}</h6>

      <!-- Price Display -->
      <div class="d-flex justify-content-between mb-4">
        <div class=" p-2 rounded filter-prices">€<input type="text" class="price-input" id="min-price" data-default="0"
            value="{{ request()->get('min_price') }}" name="min_price"></input></div>
        <div class=" p-2 rounded filter-prices">€<input type="text" class="price-input" id="max-price"
            data-default="300" value="{{ request()->get('max_price') }}" name="max_price"></input></div>
      </div>

      <!-- Combined Slider + Histogram Container -->
      <div class="price-filter-container position-relative mb-4" style="height: 80px;">

        <div class="d-flex h-100 align-items-end position-absolute w-100" style="bottom: 0;     left: 0;">
          <!-- $0-$300 range with $10 increments -->
          <div class="histogram-bar" style="height: 5%;" data-min="0" data-max="10"></div>
          <div class="histogram-bar" style="height: 8%;" data-min="10" data-max="20"></div>
          <div class="histogram-bar" style="height: 12%;" data-min="20" data-max="30"></div>
          <div class="histogram-bar" style="height: 15%;" data-min="30" data-max="40"></div>
          <div class="histogram-bar" style="height: 18%;" data-min="40" data-max="50"></div>
          <div class="histogram-bar" style="height: 25%;" data-min="50" data-max="60"></div>
          <div class="histogram-bar" style="height: 35%;" data-min="60" data-max="70"></div>
          <div class="histogram-bar" style="height: 45%;" data-min="70" data-max="80"></div>
          <div class="histogram-bar" style="height: 55%;" data-min="80" data-max="90"></div>
          <div class="histogram-bar" style="height: 65%;" data-min="90" data-max="100"></div>
          <div class="histogram-bar" style="height: 75%;" data-min="100" data-max="110"></div>
          <div class="histogram-bar" style="height: 85%;" data-min="110" data-max="120"></div>
          <div class="histogram-bar" style="height: 95%;" data-min="120" data-max="130"></div>
          <div class="histogram-bar" style="height: 100%;" data-min="130" data-max="140"></div>
          <div class="histogram-bar" style="height: 90%;" data-min="140" data-max="150"></div>
          <div class="histogram-bar" style="height: 80%;" data-min="150" data-max="160"></div>
          <div class="histogram-bar" style="height: 70%;" data-min="160" data-max="170"></div>
          <div class="histogram-bar" style="height: 60%;" data-min="170" data-max="180"></div>
          <div class="histogram-bar" style="height: 50%;" data-min="180" data-max="190"></div>
          <div class="histogram-bar" style="height: 40%;" data-min="190" data-max="200"></div>
          <div class="histogram-bar" style="height: 30%;" data-min="200" data-max="210"></div>
          <div class="histogram-bar" style="height: 20%;" data-min="210" data-max="220"></div>
          <div class="histogram-bar" style="height: 15%;" data-min="220" data-max="230"></div>
          <div class="histogram-bar" style="height: 10%;" data-min="230" data-max="240"></div>
          <div class="histogram-bar" style="height: 8%;" data-min="240" data-max="250"></div>
          <div class="histogram-bar" style="height: 12%;" data-min="250" data-max="260"></div>
          <div class="histogram-bar" style="height: 18%;" data-min="260" data-max="270"></div>
          <div class="histogram-bar" style="height: 22%;" data-min="270" data-max="280"></div>
          <div class="histogram-bar" style="height: 28%;" data-min="280" data-max="290"></div>
          <div class="histogram-bar" style="height: 35%;" data-min="290" data-max="300"></div>
          <!-- Slider Track -->
          <div id="price-slider" class="position-absolute w-100"></div>
        </div>
      </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.6.1/nouislider.min.js"></script>
    <link rel="stylesheet" href="https://crispy-rotary-phone-6rx99vvv952567j-80.app.github.dev/css/slider.css">

    <!-- Rating Accordion Item -->
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingRating">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
          data-bs-target="#collapseRating" aria-expanded="false" aria-controls="collapseRating">
          {{ __('Filter by Rating') }}
        </button>
      </h2>
      <div id="collapseRating" class="accordion-collapse collapse" aria-labelledby="headingRating">
        <div class="accordion-body">
            @include('products.filters.rating')
        </div>
      </div>
    </div>

    <!-- Categories Accordion Items -->
    @if(isset($filterCategories) && $filterCategories->count() > 0)
    @foreach($filterCategories as $category)

    <div class="accordion-item">
      <h2 class="accordion-header" id="heading{{ $category->slug }}">
        <button class="accordion-button @if(request()->has($category->slug)) x @else collapsed @endif" type="button"
          data-bs-toggle="collapse" data-bs-target="#collapse{{ $category->slug }}"
          aria-expanded="{{ request()->has($category->slug) }}" aria-controls="collapse{{ $category->slug }}">
          {{ __($category->name) }}
        </button>
      </h2>
      <div id="collapse{{ $category->slug }}"
        class="accordion-collapse @if(request()->has($category->slug)) accordion-collapse collapse show @else collapse @endif"
        aria-labelledby="heading{{ $category->slug }}">
        <div class="accordion-body">
          @if($category->options->count() > 0)

            @if($category->slug == 'occasion')
              @include('products.filters.box', [
              'options' => $category->options,
              'category' => $category,
              ])

            @else
              @include('products.filters.default', [
              'options' => $category->options,
              'category' => $category,
              ])

            @endif

          @else
          <p class="text-muted"> {{ __('No options available.') }}</p>
          @endif
        </div>
      </div>
    </div>
    @endforeach
    @endif
  </div>

  <input type="hidden" name="page" value="{{ request()->get('page', '1') }}">
  <input type="hidden" name="sort" id="sort-input" value="{{ request('sort') }}">
  
  <div class="d-grid gap-2 d-md-flex justify-content-md-start mt-4">
    <button type="submit" id="search-button" class="d-none">
      <i class="fas fa-filter me-1"></i>  {{ __('Apply Filters') }}
    </button>

    <a href="{{ url()->current() }}" class="btn btn-outline-secondary px-4">
      <i class="fa fa-undo" aria-hidden="true"></i>  {{ __('Reset Search') }}
    </a>
  </div>
</form>