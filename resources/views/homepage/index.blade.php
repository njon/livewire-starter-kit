@extends('layouts.app')

@section('title', 'Gift Experiences')

@section('top-content')


<!-- Hero Section: Gift-Focused -->
<section class="hero-section py-5 bg-light position-relative overflow-hidden">
    <!-- Decorative elements (optional) -->
    <div class="position-absolute top-0 end-0 bg-primary opacity-10 rounded-circle"
        style="width: 300px; height: 300px; transform: translate(50%, -50%);"></div>

    <div class="container py-lg-5 position-relative">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0">
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

            <!-- Hero image (gift-themed) -->
            <div class="col-lg-6">
                <div class="position-relative">
                    <img src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1200&q=80"
                        alt="Couple enjoying an experience gift" class="img-fluid rounded-3 shadow">
                    <div class="position-absolute bottom-0 start-0 bg-white p-3 rounded-end shadow-sm"
                        style="transform: translateY(50%);">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-gift-fill text-primary fs-4"></i>
                            <div>
                                <p class="mb-0 fw-bold">Last-minute gift?</p>
                                <p class="mb-0 small text-muted">Instant e-vouchers available!</p>
                            </div>
                        </div>
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
            <h2 class="fs-30 fw-600 ls-1">Browse categories</h2>
            <p class="text-muted mb-0 fs-18 ls-1">Lots of new products and product collections</p>
        </div>
    </div>
@php

function getCategoryImage($categoryName) {
    $categories = [
        "Adventure & Action" => "action1.png",
        "Airborne Experiences" => "airborne.png",
        "Automotive Sports" => "auto1.png",
        "Beauty & Wellness" => "spa1.png",
        "Creative & Learning" => "educational.png",
        "Cultural & Entertainment" => "restaurant1.png",
        "Culinary & Dining" => "restaurant2.png",
        "Nature & Wildlife" => "nature1.png",
        "Water Sports & Aquatic" => "water.png",
        "Short Breaks & Getaways" => "explore.png",
        "Tours & Sightseeing" => "tours.png",
        "Photography Experiences" => "xx.png",
    ];

    if (isset($categories[$categoryName])) {
        echo $categories[$categoryName];
    } else {
        echo "default.png"; // fallback image if category not found
    }
}
@endphp
    <div class="row g-3">
       @foreach(\Lunar\Models\Collection::with(['defaultUrl', 'children.defaultUrl'])->get() as $collection)
        <div class="col-2 mb-0 px-2">
            <div class="card border-0 rounded-1 overflow-hidden d-flex flex-column h-100  shadow-up">
                <a href="{{ $collection->defaultUrl->slug }}" class="category-link text-decoration-none">
                    <div class="position-relative flex-grow-1">
                        <div class="img-holder ratio ratio-1x1">
                            <img src="https://crispy-rotary-phone-6rx99vvv952567j-80.app.github.dev/images/{{ getCategoryImage($collection->translateAttribute('name')) }}" 
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
            @foreach($products->slice(0, 5) as $product)
                @include('products.product', ['product' => $product])
            @endforeach
        </div>
    </div>
    <div class="items">
        <div class="row"> @foreach($products->slice(5, 5) as $product)
                @include('products.product', ['product' => $product])
            @endforeach
        </div>
    </div>
</div>


@endsection