<form action="" method="GET">
    <div class="accordion mb-4" id="filterAccordion">

        <!-- Price Range Accordion Item -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingPrice">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePrice" aria-controls="collapsePrice">
                    Price Range
                </button>
            </h2>

            <div id="collapsePrice" class="accordion-collapse @if(request()->filled('min_price') != '0' OR request()->filled('max_price') != '0') accordion-collapse collapse show @else collapse @endif" aria-labelledby="headingPrice">
                <div class="accordion-body">
                    <div class="row g-2 align-items-center">
                        <div class="col">
                            <label for="min_price" class="visually-hidden">Min Price</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" name="min_price" id="min_price" placeholder="Min" value="{{ request('min_price') }}">
                            </div>
                        </div>
                        <div class="col-auto">-</div>
                        <div class="col">
                            <label for="max_price" class="visually-hidden">Max Price</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" name="max_price" id="max_price" placeholder="Max" value="{{ request('max_price') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rating Accordion Item -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingRating">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRating" aria-expanded="false" aria-controls="collapseRating">
                    Minimum Rating
                </button>
            </h2>
            <div id="collapseRating" class="accordion-collapse collapse" aria-labelledby="headingRating">
                <div class="accordion-body">
                    <label for="ratingRange" class="form-label">
                        Selected: <span id="ratingValueDisplay" class="fw-bold">{{ request('rating', 0) }}</span> stars
                    </label>
                    <input type="range" class="form-range" min="0" max="5" step="0.5" id="ratingRange" name="rating" value="{{ request('rating', 0) }}" oninput="document.getElementById('ratingValueDisplay').textContent = this.value">
                    <div class="d-flex justify-content-between mt-1 text-muted small">
                        <span>0 stars</span>
                        <span>5 stars</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories Accordion Items -->
        @if(isset($filterCategories) && $filterCategories->count() > 0)
            @foreach($filterCategories as $category)

                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading{{ $category->slug }}">
                        <button class="accordion-button @if(request()->has($category->slug)) x @else collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $category->slug }}" aria-expanded="{{ request()->has($category->slug) }}" aria-controls="collapse{{ $category->slug }}">
                            {{ $category->name }}
                        </button>
                    </h2>
                    <div id="collapse{{ $category->slug }}" class="accordion-collapse @if(request()->has($category->slug)) accordion-collapse collapse show @else collapse @endif" aria-labelledby="heading{{ $category->slug }}">
                        <div class="accordion-body">
                            @if($category->options->count() > 0)
                                @foreach($category->options as $option)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox"
                                               name="{{ $category->slug }}[]"
                                               value="{{ $option->value }}"
                                               id="filter-{{ $category->slug }}-{{ $option->id }}"
                                               {{ in_array($option->value, (array)request($category->slug, [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="filter-{{ $category->slug }}-{{ $option->id }}">
                                            {{ $option->name }}
                                        </label>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted">No options available.</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <div class="d-grid gap-2 d-md-flex justify-content-md-start mt-4">
        <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-filter me-1"></i> Apply Filters
        </button>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary px-4">
            <i class="fas fa-undo me-1"></i> Reset
        </a>
    </div>
</form>
