@extends('layouts.app')

@section('title', 'Gift Experiences')

@section('top-content')


<!-- Hero Section: Gift-Focused -->
<section class="hero-section position-relative overflow-hidden">


    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mt-5 mb-lg-0" id="xxz">
                <!-- Badges for gifting occasions -->
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge bg-primary bg-opacity-10 text-primary py-2 px-3 rounded-pill">Birthdays</span>
                    <span
                        class="badge bg-success bg-opacity-10 text-success py-2 px-3 rounded-pill">Anniversaries</span>
                    <span class="badge bg-warning bg-opacity-10 text-warning py-2 px-3 rounded-pill">Corporate
                        Gifts</span>
                </div>

                <!-- Headline -->
                <h1 class="display-4 fw-bold mb-3">The Perfect <span class="text-primary">Gift</span> Isn’t a Thing—It’s
                    an <span class="text-primary">Experience</span></h1>
                <p class="lead mb-4">Surprise them with unforgettable moments—from adrenaline rushes to luxury escapes.
                    No wrapping paper needed.</p>

                <!-- Dual CTAs -->
                <div class="d-flex flex-wrap gap-3">
                    <a href="#gift-experiences" class="btn btn-primary btn-lg px-4">
                        <i class="bi bi-gift-fill me-2"></i> Gift an Experience
                    </a>
                    <a href="#categories" class="btn btn-outline-dark btn-lg px-4">
                        <i class="bi bi-search me-2"></i> Explore for Yourself
                    </a>
                </div>

                <!-- Trust signals -->
                <!-- Make sure Font Awesome 4.7 is loaded in your <head> -->

                <div class="mt-4 d-flex align-items-center gap-3">
                    <div class="d-flex gap-2 small text-muted align-items-center">
                        <i class="fa fa-lock text-success" aria-hidden="true"></i>
                        <span>Secure Booking</span>
                    </div>
                    <div class="d-flex gap-2 small text-muted align-items-center">
                        <i class="fa fa-bolt text-warning" aria-hidden="true"></i>
                        <span>Instant Delivery</span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection

@section('content')

@csrf
<div id="home-categories" class="py-5">
    <div class="d-flex align-items-center mb-5 gap-3">
        <i class="fa fa-tags fs-1 classic-color"></i>
        <div>
            <h2 class="fs-30 fw-600 ls-1">{{ __('Browse categories') }}</h2>
            <p class="text-muted mb-0 fs-18 ls-1">Lots of new products and product collections</p>
        </div>
    </div>

    <div class="row g-3">
       @foreach($categories->slice(5,5) as $collection)
        <div class="col mb-0 px-2">
            <div class="card border-0 rounded-1 overflow-hidden d-flex flex-column h-100  shadow-up">
                <a href="{{ $collection->defaultUrl->slug }}" class="category-link text-decoration-none">
                    <div class="position-relative flex-grow-1">
                        <div class="img-holder ratio ratio-1x1">
                            <img src="https://literate-spoon-j4pjjrrr74hq76-80.app.github.dev/images/{{ get_category_image($collection->translateAttribute('name')) }}" 
                                class="card-img-top object-fit-cover"
                                alt="{{ $collection->name }}"
                                loading="lazy">
                        </div>
                        <div class="position-absolute bottom-0 start-0 end-0 p-3">
                            <h5 class="category-explore">Explore</h5>
                            <div class="category-name">
                                {{ $collection->translateAttribute('name') }}
                            </div>
                        </div>
                    </div>
                </a>
                @if($collection->products_count)
                <div class="card-footer bg-transparent border-0 pt-0 pb-3 text-center">
                    <small class="text-muted">{{ $collection->products_count }} items</small>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <div class="row g-3 mt-1">
       @foreach($categories->slice(0,5) as $collection)
        <div class="col mb-0 px-2">
            <div class="card border-0 rounded-1 overflow-hidden d-flex flex-column h-100  shadow-up">
                <a href="{{ $collection->defaultUrl->slug }}" class="category-link text-decoration-none">
                    <div class="position-relative flex-grow-1">
                        <div class="img-holder ratio ratio-1x1">
                            <img src="https://literate-spoon-j4pjjrrr74hq76-80.app.github.dev/images/{{ get_category_image($collection->translateAttribute('name')) }}" 
                                class="card-img-top object-fit-cover"
                                alt="{{ $collection->name }}"
                                loading="lazy">
                        </div>
                        <div class="position-absolute bottom-0 start-0 end-0 p-3">
                            <h5 class="category-explore">Explore</h5>
                            <div class="category-name">
                                {{ $collection->translateAttribute('name') }}
                            </div>
                        </div>
                    </div>
                </a>
                @if($collection->products_count)
                <div class="card-footer bg-transparent border-0 pt-0 pb-3 text-center">
                    <small class="text-muted">{{ $collection->products_count }} items</small>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>


<div id="home-producs">
    <div class="d-flex align-items-center mb-5 mt-5 gap-3">
        <i class="fa fa-tags fs-1 classic-color"></i>
        <div>
            <h2 class="fs-30 fw-600 ls-1">Browse categories</h2>
            <p class="text-muted mb-0 fs-18 ls-1">Lots of new products and product collections</p>
        </div>
    </div>
    <div class="items">
        <div class="row">
            @foreach($products->slice(0, 4) as $product)
                @include('products.product', ['product' => $product])
            @endforeach
        </div>
    </div>
    <div class="items">
        <div class="row"> @foreach($products->slice(4, 4) as $product)
                @include('products.product', ['product' => $product])
            @endforeach
        </div>
    </div>
</div>


<div id="home-cards">
        <div class="d-flex align-items-center mb-5 mt-5 gap-3">
        <i class="fa fa-tags fs-1 classic-color"></i>
        <div>
            <h2 class="fs-30 fw-600 ls-1">Browse categories</h2>
            <p class="text-muted mb-0 fs-18 ls-1">Lots of new products and product collections</p>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex flex-column justify-content-between" style="
    color: #393939;
    background: #e5e5e547;
    border-radius: 5px;
">
                    <div class="row">
                        <div class="col-lg-6 mb-4 pe-0 p-5">
                            <h5 class="mb-3 fw-600">Special Picks</h5>
                            <h1 class="mb-3 fs-1 fw-900">Picks for date <br>Special Night </h1>
<div class="d-flex flex-wrap gap-2 mb-4">
                    <span class="badge bg-primary bg-opacity-10 text-primary py-2 px-3 rounded-pill">Birthdays</span>
                    <span class="badge bg-success bg-opacity-10 text-success py-2 px-3 rounded-pill">Anniversaries</span>
                    
                </div>
                            <p class="card-text fw-600 mb-4">Surprise your loved ones with unforgettable experiences. From adventure
                                getaways to relaxing spa days, we have the perfect gift for every occasion.</p>
                            <a href="https://literate-spoon-j4pjjrrr74hq76-80.app.github.dev/products" class="btn btn-dark rounded-5 fs-14 me-2"><i class="fa fa-magic me-1"></i>Explore More</a>
                            <a href="https://literate-spoon-j4pjjrrr74hq76-80.app.github.dev/products" class="btn btn-primary rounded-5  fs-14"><i class="fa fa-heart me-1"></i> Show Me </a>
                        </div>
                        <div class="col-lg-6">
                            <img src="https://cdn.midjourney.com/87c34077-4811-4787-83a7-44cbf263e192/0_1.png" class="img-fluid border-20" alt="Gift Experiences">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection