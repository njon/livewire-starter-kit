@extends('layouts.app')

@section('title', __('Gift Experiences'))

@section('top-content')

<!-- Hero Section: Gift-Focused -->
<section class="hero-section position-relative overflow-hidden">
    <div class="container position-relative">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mt-5 mb-lg-0" id="xxz">
                <!-- Badges for gifting occasions -->
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span
                        class="badge bg-primary bg-opacity-10 text-primary py-2 px-3 rounded-pill">{{ __('Birthdays') }}</span>
                    <span
                        class="badge bg-success bg-opacity-10 text-success py-2 px-3 rounded-pill">{{ __('Anniversaries') }}</span>
                    <span class="badge bg-warning bg-opacity-10 text-warning py-2 px-3 rounded-pill">Corporate
                        Gifts</span>
                </div>

                <!-- Headline -->
                <h1 class="display-4 fw-bold mb-3">{{ __('The Perfect') }} <span
                        class="text-primary">{{ __('Gift') }}</span> {{ __('Isn\'t a Thing—It\'s an') }} <span
                        class="text-primary">{{ __('Experience') }}</span></h1>
                <p class="lead mb-4">
                    {{ __('Surprise them with unforgettable moments—from adrenaline rushes to luxury escapes. No wrapping paper needed.') }}
                </p>

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
                        <i class="bi bi-lock-fill text-success"></i>
                        <span>{{ __('Secure Booking') }}</span>
                    </div>
                    <div class="d-flex gap-2 small text-muted align-items-center">
                        <i class="bi bi-lightning-fill text-warning"></i>
                        <span>{{ __('Instant Delivery') }}</span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<div class="container">
    <div id="home-products">
        <div class="d-flex align-items-center mb-5 mt-5 gap-3">
            <i class="bi bi-fire fs-1 classic-color"></i>
            <div>
                <h2 class="fs-30 fw-600 ls-1 m-0">{{ __('Trending Experiences') }}</h2>
                <p class="text-muted mb-0 fs-18 ls-1">{{ __('See and try why it\'s so popular right now!') }}</p>
            </div>
        </div>
        <ul class="nav nav-tabs mb-4" id="productTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="autumn-tab" data-bs-toggle="tab" data-bs-target="#autumn" type="button"
                    role="tab" aria-controls="autumn" aria-selected="true">Autumn Savings</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="shortbreaks-tab" data-bs-toggle="tab" data-bs-target="#shortbreaks"
                    type="button" role="tab" aria-controls="shortbreaks" aria-selected="false">Short Breaks</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="food-tab" data-bs-toggle="tab" data-bs-target="#food" type="button" role="tab"
                    aria-controls="food" aria-selected="false">Food &amp; Drink</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="driving-tab" data-bs-toggle="tab" data-bs-target="#driving" type="button"
                    role="tab" aria-controls="driving" aria-selected="false">Driving</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="multi-tab" data-bs-toggle="tab" data-bs-target="#multi" type="button"
                    role="tab" aria-controls="multi" aria-selected="false">Multi-Choice Vouchers</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="tea-tab" data-bs-toggle="tab" data-bs-target="#tea" type="button" role="tab"
                    aria-controls="tea" aria-selected="false">Afternoon Tea</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="adventure-tab" data-bs-toggle="tab" data-bs-target="#adventure" type="button"
                    role="tab" aria-controls="adventure" aria-selected="false">Adventure</button>
            </li>
        </ul>
        <div class="tab-content mb-4" id="productTabsContent">
            <div class="tab-pane fade show active" id="autumn" role="tabpanel" aria-labelledby="autumn-tab">
                <div class="items">
                    <div class="row">
                        @foreach($products->slice(0, 5) as $product)
                        @include('products.product', ['product' => $product, 'col' => ''])
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="shortbreaks" role="tabpanel" aria-labelledby="shortbreaks-tab">
                <div class="items">
                    <div class="row">
                        @foreach($products->shuffle()->slice(0, 4) as $product)
                        @include('products.product', ['product' => $product, 'col' => 3])
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="food" role="tabpanel" aria-labelledby="food-tab">
                <!-- Food & Drink content here -->
            </div>
            <div class="tab-pane fade" id="driving" role="tabpanel" aria-labelledby="driving-tab">
                <!-- Driving content here -->
            </div>
            <div class="tab-pane fade" id="multi" role="tabpanel" aria-labelledby="multi-tab">
                <!-- Multi-Choice Vouchers content here -->
            </div>
            <div class="tab-pane fade" id="tea" role="tabpanel" aria-labelledby="tea-tab">
                <!-- Afternoon Tea content here -->
            </div>
            <div class="tab-pane fade" id="adventure" role="tabpanel" aria-labelledby="adventure-tab">
                <!-- Adventure content here -->
            </div>
        </div>
    </div>
</div>



<div class="container">
    <div id="home-categories" class="py-5">
        <div class="d-flex align-items-center mb-5 gap-3">
            <i class="bi bi-tags fs-1 classic-color"></i>
            <div>
                <h2 class="fs-30 fw-600 ls-1 m-0">{{ __('Browse categories') }}</h2>
                <p class="text-muted mb-0 fs-18 ls-1">{{ __('Lots of new products and product collections') }}</p>
            </div>
        </div>

        <div class="row g-3">
            @foreach($categories->slice(6,6) as $collection)
            <div class="col-sm-6 col-md col-lg mb-0 px-2">
                <div class="card border-0 rounded-1 overflow-hidden d-flex flex-column h-100  shadow-up">
                    <a href="{{ $collection->defaultUrl->slug }}" class="category-link text-decoration-none">
                        <div class="position-relative flex-grow-1">
                            <div class="img-holder ratio ratio-1x1">
                                <img src="https://animated-dollop-g5v44x4gg5fwq7-80.app.github.dev/images/{{ get_category_image($collection->translateAttribute('name')) }}"
                                    class="card-img-top object-fit-cover" alt="{{ $collection->name }}" loading="lazy">
                            </div>
                            <div class="position-absolute bottom-0 start-0 end-0 p-3">
                                <h5 class="category-explore">{{ __('Explore') }}</h5>
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
</div>


<div class="container">
    <div id="gift-experiences" class="mb-5">
        <div class="d-flex align-items-center mb-5 mt-5 gap-3">
            <i class="bi bi-stars fs-1 classic-color"></i>
            <div>
                <h2 class="fs-30 fw-600 ls-1 mb-0">{{ __('Find unique offers for everyone') }}</h2>
                <p class="text-muted mb-0 fs-18 ls-1">{{ __('Experiences for Every Moment') }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 mb-4 d-flex align-items-center">
                <div class="card shadow-sm border-0 h-100 w-100 rounded-2"
                    style="background: url('https://animated-dollop-g5v44x4gg5fwq7-80.app.github.dev/images/johnycash420_climbing_mountains_in_greece_with_climber_--ar_3_2cdb20ba-7cf8-4f57-a12d-4493e1fc3dfa_1.png') center center / cover no-repeat;">
                    <div class="card-body d-flex flex-column justify-content-center h-100 p-5 py-6 text-white" style="background: rgba(0,0,0,0.35);">
                        <h2 class="mb-3 fw-800">For Adventurers</h2>
                        <a href="https://animated-dollop-g5v44x4gg5fwq7-80.app.github.dev/products"
                            class="btn btn-light rounded-5 fs-14 px-4 py-2"
                            style="font-weight: 400; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12); border: 0; text-align: left; font-size: 17px !important; background: #ffffffba; border-radius: 10px !important; display: inline !important; width: fit-content;">
                            <i class="bi bi-fire fs-6"></i> Explore Experiences
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4 d-flex align-items-center">
                <div class="card shadow-sm border-0 h-100 w-100"
                    style="background: url('https://cdn.midjourney.com/570aee86-993a-4940-a933-0b34e39baf9f/0_1.png') center center / cover no-repeat;border-radius: 20px;color: #fff;">
                    <div class="card-body d-flex flex-column justify-content-center h-100 p-5 py-6"
                        style="background: rgba(0,0,0,0.35); border-radius: 20px;">
                        <h2 class="mb-3 fw-800">For Groups</h2>
                        <div>
                            <p class="card-text fw-400 mb-4">Push your limits and embrace the thrill of adventure. Discover
                                breathtaking experiences that will leave you with stories to tell and memories to cherish.
                            </p>
                        </div>
                        <a href="https://animated-dollop-g5v44x4gg5fwq7-80.app.github.dev/products"
                            class="btn btn-light rounded-5 fs-14 px-4 py-2"
                            style="font-weight: 400; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12); border: 0; text-align: left; font-size: 17px !important; background: #ffffffba; border-radius: 10px !important; display: inline !important; width: fit-content;">
                            <i class="bi bi-fire fs-6"></i> Explore Experiences
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4 d-flex align-items-center">
                <div class="card shadow-sm border-0 h-100 w-100"
                    style="background: url('https://animated-dollop-g5v44x4gg5fwq7-80.app.github.dev/images/johnycash420_climbing_mountains_in_greece_with_climber_--ar_3_2cdb20ba-7cf8-4f57-a12d-4493e1fc3dfa_1.png') center center / cover no-repeat;border-radius: 20px;color: #fff;background-image: url(https://cdn.midjourney.com/9d9e6659-d53d-4486-8315-858f1648de60/0_0.png);">
                    <div class="card-body d-flex flex-column justify-content-center h-100 p-5 py-6"
                        style="background: rgba(0,0,0,0.35); border-radius: 20px;">
                        <h2 class="mb-3 fw-900">For Relaxation</h2>
                        <div>
                            <p class="card-text fw-400 mb-4">Push your limits and embrace the thrill of adventure. Discover
                                breathtaking experiences that will leave you with stories to tell and memories to cherish.
                            </p>
                        </div>
                        <a href="https://animated-dollop-g5v44x4gg5fwq7-80.app.github.dev/products"
                            class="btn btn-light rounded-5 fs-14 px-4 py-2"
                            style="font-weight: 400; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12); border: 0; text-align: left; font-size: 17px !important; background: #ffffffba; border-radius: 10px !important; display: inline !important; width: fit-content;">
                            <i class="bi bi-fire fs-6"></i> Explore Experiences
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="home-cards">
    <div class="container">
        <div class="d-flex align-items-center mb-5 mt-5 gap-3">
            <i class="bi bi-tags fs-1 classic-color"></i>
            <div>
                <h2 class="fs-30 fw-600 ls-1 mb-0">{{ __('Browse categories') }}</h2>
                <p class="text-muted mb-0 fs-18 ls-1">{{ __('Lots of new products and product collections') }}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm border-0 h-100 rounded-4 overflow-hidden">
                    <div class="card-body d-flex flex-column justify-content-between p-0" style="
            background: #dcebed;
        ">
                        <div class="row">
                            <div class="col-lg-7 mb-4 pe-0 p-5 h-100">
                                <div class="d-flex flex-column h-100 justify-content-center">
                                    <div class="justify-content-center">
                                        <h5 class="mb-3 fw-600">Special Picks</h5>
                                        <h1 class="mb-3 fw-700  double-card-title">Picks for date <br>Special Night
                                        </h1>
                                        <div class="d-flex flex-wrap gap-2 mb-4">
                                            <span
                                                class="badge bg-success bg-opacity-10 text-primary py-2 px-3 rounded-pill">Birthdays</span>
                                            <span
                                                class="badge bg-success bg-opacity-10 text-success py-2 px-3 rounded-pill">Anniversaries</span>
                                        </div>
                                        <p class="card-text fw-400 mb-4 fs-14">Push your limits and embrace the thrill of
                                            adventure. Discover breathtaking experiences that will leave you with</p>
                                        <div>
                                            <a href="https://animated-dollop-g5v44x4gg5fwq7-80.app.github.dev/products"
                                                class="btn btn-dark rounded-5 fs-14 me-2"><i
                                                    class="bi bi-magic me-1"></i>Explore More</a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <img src="https://cdn.midjourney.com/87c34077-4811-4787-83a7-44cbf263e192/0_1.png"
                                    class="fit-cover" alt="Gift Experiences">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm border-0 h-100 rounded-4 overflow-hidden">
                    <div class="card-body d-flex flex-column justify-content-between p-0" style="
                        background: #ededdc;
                    ">
                        <div class="row">
                            <div class="col-lg-7 mb-4 pe-0 p-5 h-100">
                                <div class="d-flex flex-column h-100 justify-content-center">
                                    <div class="justify-content-center">
                                        <h5 class="mb-3 fw-600">Special Picks</h5>
                                        <h1 class="mb-3 fw-700 double-card-title">Love Extreme? <br>Special Night </h1>
                                        <div class="d-flex flex-wrap gap-2 mb-4">
                                            <span
                                                class="badge bg-success bg-opacity-10 text-primary py-2 px-3 rounded-pill">Birthdays</span>
                                            <span
                                                class="badge bg-success bg-opacity-10 text-success py-2 px-3 rounded-pill">Anniversaries</span>
                                        </div>
                                        <p class="card-text fw-400 mb-4 fs-14">Push your limits and embrace the thrill of
                                            adventure. Discover breathtaking experiences that will leave you with</p>
                                        <div>
                                            <a href="https://animated-dollop-g5v44x4gg5fwq7-80.app.github.dev/products"
                                                class="btn btn-dark rounded-5 fs-14 me-2"><i
                                                    class="bi bi-magic me-1"></i>Explore More</a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <img src="https://animated-dollop-g5v44x4gg5fwq7-80.app.github.dev/images/johnycash420_climbing_mountains_in_greece_with_climber_--ar_3_2cdb20ba-7cf8-4f57-a12d-4493e1fc3dfa_1.png"
                                    class="fit-cover" alt="Gift Experiences" style="
                                object-position: 72% 100%;
                            ">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm border-0 h-100 rounded-4 overflow-hidden">
                    <div class="card-body d-flex flex-column justify-content-between p-0" style="
            background: #dcdced;
        ">
                        <div class="row">
                            <div class="col-lg-7 mb-4 pe-0 p-5 h-100">
                                <div class="d-flex flex-column h-100 justify-content-center">
                                    <div class="justify-content-center">
                                        <h5 class="mb-3 fw-600">Special Picks</h5>
                                        <h1 class="mb-3 fw-700  double-card-title">Picks for date <br>Special Night
                                        </h1>
                                        <div class="d-flex flex-wrap gap-2 mb-4">
                                            <span class="badge bg-success bg-opacity-10 text-primary py-2 px-3 rounded-pill">Birthdays</span>
                                            <span class="badge bg-success bg-opacity-10 text-success py-2 px-3 rounded-pill">Anniversaries</span>
                                        </div>
                                        <p class="card-text fw-400 mb-4 fs-14">Push your limits and embrace the thrill of
                                            adventure. Discover breathtaking experiences that will leave you with</p>
                                        <div>
                                            <a href="https://animated-dollop-g5v44x4gg5fwq7-80.app.github.dev/products" class="btn btn-dark rounded-5 fs-14 me-2"><i class="bi bi-magic me-1"></i>Explore More</a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <img src="https://cdn.midjourney.com/570aee86-993a-4940-a933-0b34e39baf9f/0_0.png" class="fit-cover" alt="Gift Experiences" style="
    object-position: 32% 100%;
">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div id="gift-experiences" class="mb-5">
        <div class="d-flex align-items-center mb-5 mt-5 gap-3 section-title">
            <i class="bi bi-tags fs-1 classic-color"></i>
            <div>
                <h2 class="fs-30 fw-600 ls-1 mb-0">Browse categories</h2>
                <p class="text-muted mb-0 fs-18 ls-1">Lots of new products and product collections</p>
            </div>
        </div>

        <div class="row">
            <!-- Variation 1: Adventure Theme -->
            <div class="col-lg-4 col-md-6 mb-4 d-flex align-items-stretch">
                <div class="card shadow-sm border-0 card-category card-adventure">
                    <div class="card-content">
                        <h2 class="mb-3 fw-900 text-white">Love Adventure?</h2>
                        <div>
                            <p class="card-text fw-400 mb-4 text-white">Push your limits and embrace the thrill of
                                exploration. Discover breathtaking experiences that will leave you with stories to tell
                                and memories to cherish.</p>
                        </div>
                        <a href="#" class="btn btn-card btn-adventure">
                            <i class="bi bi-compass fs-6"></i> Explore Adventures
                        </a>
                    </div>
                </div>
            </div>

            <!-- Variation 2: Luxury Theme -->
            <div class="col-lg-4 col-md-6 mb-4 d-flex align-items-stretch">
                <div class="card shadow-sm border-0 card-category card-luxury">
                    <div class="card-content">
                        <h2 class="mb-3 fw-900 text-white">Seek Luxury?</h2>
                        <div>
                            <p class="card-text fw-400 mb-4 text-white">Indulge in the finest experiences crafted for
                                those who appreciate the extraordinary. Pamper yourself with premium offerings and
                                exclusive treatments.</p>
                        </div>
                        <a href="#" class="btn btn-card btn-luxury">
                            <i class="bi bi-gem fs-6"></i> Luxury Collection
                        </a>
                    </div>
                </div>
            </div>

            <!-- Variation 3: Culinary Theme -->
            <div class="col-lg-4 col-md-6 mb-4 d-flex align-items-stretch">
                <div class="card shadow-sm border-0 card-category card-culinary">
                    <div class="card-content">
                        <h2 class="mb-3 fw-900 text-white">Food Enthusiast?</h2>
                        <div>
                            <p class="card-text fw-400 mb-4 text-white">Savor exquisite flavors and culinary adventures.
                                From cooking classes to gourmet dining experiences, delight your taste buds with our
                                curated selections.</p>
                        </div>
                        <a href="#" class="btn btn-card btn-culinary">
                            <i class="bi bi-egg-fried fs-6"></i> Culinary Experiences
                        </a>
                    </div>
                </div>
            </div>

            <!-- Variation 4: Relaxation Theme -->
            <div class="col-lg-4 col-md-6 mb-4 d-flex align-items-stretch">
                <div class="card shadow-sm border-0 card-category card-relaxation">
                    <div class="card-content">
                        <h2 class="mb-3 fw-900 text-white">Need Relaxation?</h2>
                        <div>
                            <p class="card-text fw-400 mb-4 text-white">Unwind and rejuvenate with our peaceful retreats
                                and wellness experiences. Find your inner peace and restore balance to your busy life.
                            </p>
                        </div>
                        <a href="#" class="btn btn-card btn-relaxation">
                            <i class="bi bi-clouds fs-6"></i> Relaxation Getaways
                        </a>
                    </div>
                </div>
            </div>

            <!-- Variation 5: Classic Theme -->
            <div class="col-lg-4 col-md-6 mb-4 d-flex align-items-stretch">
                <div class="card shadow-sm border-0 card-category card-classic">
                    <div class="card-content">
                        <h2 class="mb-3 fw-900 text-white">Classic Tastes?</h2>
                        <div>
                            <p class="card-text fw-400 mb-4 text-white">Enjoy timeless experiences that never go out of
                                style. From traditional spa days to classic city tours, we have the perfect options for
                                you.</p>
                        </div>
                        <a href="#" class="btn btn-card btn-classic">
                            <i class="bi bi-star fs-6"></i> Classic Experiences
                        </a>
                    </div>
                </div>
            </div>

            <!-- Variation 6: Extreme Theme (Original) -->
            <div class="col-lg-4 col-md-6 mb-4 d-flex align-items-stretch">
                <div class="card shadow-sm border-0 card-category"
                    style="background: url('https://images.unsplash.com/photo-1459767129950-54dca0c5e34a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80') center center / cover no-repeat;">
                    <div class="card-content">
                        <h2 class="mb-3 fw-900 text-white">Love Extreme?</h2>
                        <div>
                            <p class="card-text fw-400 mb-4 text-white">Push your limits and embrace the thrill of
                                adventure. Discover breathtaking experiences that will leave you with stories to tell
                                and memories to cherish.</p>
                        </div>
                        <a href="#" class="btn btn-card" style="background: #ffffffba; color: #333;">
                            <i class="bi bi-fire fs-6"></i> Explore Experiences
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="container my-5 mb-3">
    <div class="row">
        <div class="col-lg-12">
            <h5 class="fw-bold mb-4 ">Welcome to Ellada Experiences, your gateway to the soul of Greece.</h5>
            <p class="fs-14 mb-3">
                The true magic of Greece isn't found in a souvenir shop; it's felt in the moments that take your breath away. That’s why we offer more than just tours—we offer connections. With hundreds of authentic experiences handpicked across this ancient land, from a private olive harvest with a local family to a sailing trip under the Aegean sunset and so much more in between.
            </p>
            <p class="fs-14 mb-3 ">
                We do things the Greek way—with <em>philoxenia</em> (hospitality) at our core. We're experts in the heart of Greece, and we'll guide you to your perfect experience, every time.
                Ready to create memories that last a lifetime? A deep cultural immersion, a last-minute island escape, or a milestone celebration: whatever your heart desires, you’ll find it here. <strong>Authentic Greek experiences, with memories that stay with you forever.</strong>
            </p>
        </div>
    </div>
</div>


<div class="container">
    <div class="row">
        <div class="col-lg-12 text-center">
            <!-- Variation 5 - Full Width with Illustration -->
        <div class="newsletter-section variation-5">
            <div class="container position-relative" style="z-index: 2;">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <h3 class="fw-bold">Your Greek Odyssey Awaits</h3>
                        <p>Sign up for personalized travel recommendations</p>
                    </div>
                    <div class="col-lg-6">
                        <form class="d-flex">
                            <input type="email" class="form-control me-2" placeholder="Enter your email">
                            <button type="submit" class="btn">Begin Journey</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>


@endsection

@section('content')

<style>
    :root {
        --classic-color: #6f42c1;
        --adventure-color: #fd7e14;
        --luxury-color: #d63384;
        --culinary-color: #20c997;
        --relaxation-color: #0dcaf0;
    }

    body {
        background-color: #f8f9fa;
    }

    .section-title {
        margin-bottom: 3rem;
    }

    .classic-color {
        color: var(--classic-color);
    }

    .adventure-color {
        color: var(--adventure-color);
    }

    .luxury-color {
        color: var(--luxury-color);
    }

    .culinary-color {
        color: var(--culinary-color);
    }

    .relaxation-color {
        color: var(--relaxation-color);
    }

    .fs-30 {
        font-size: 30px;
    }

    .fs-18 {
        font-size: 18px;
    }

    .fw-600 {
        font-weight: 600;
    }

    .fw-900 {
        font-weight: 900;
    }

    .fw-400 {
        font-weight: 400;
    }

    .ls-1 {
        letter-spacing: 1px;
    }

    .card-category {
        border-radius: 20px;
        overflow: hidden;
        border: none;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
    }

    .card-category:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15) !important;
    }

    .card-content {
        background: rgba(0, 0, 0, 0.35);
        border-radius: 20px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 2rem;
    }

    .btn-card {
        font-weight: 400;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
        border: 0;
        text-align: left;
        font-size: 17px !important;
        border-radius: 10px !important;
        display: inline !important;
        width: fit-content;
        padding: 0.5rem 1.5rem;
        transition: all 0.3s ease;
    }

    /* Variation 1: Adventure Theme */
    .card-adventure {
        background: url('https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80') center center / cover no-repeat;
    }

    .btn-adventure {
        background: rgba(253, 126, 20, 0.85);
        color: white;
    }

    .btn-adventure:hover {
        background: rgba(253, 126, 20, 1);
        color: white;
    }

    /* Variation 2: Luxury Theme */
    .card-luxury {
        background: url('https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80') center center / cover no-repeat;
    }

    .btn-luxury {
        background: rgba(214, 51, 132, 0.85);
        color: white;
    }

    .btn-luxury:hover {
        background: rgba(214, 51, 132, 1);
        color: white;
    }

    /* Variation 3: Culinary Theme */
    .card-culinary {
        background: url('https://images.unsplash.com/photo-1414235077428-338989a2e8c0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80') center center / cover no-repeat;
    }

    .btn-culinary {
        background: rgba(32, 201, 151, 0.85);
        color: white;
    }

    .btn-culinary:hover {
        background: rgba(32, 201, 151, 1);
        color: white;
    }

    /* Variation 4: Relaxation Theme */
    .card-relaxation {
        background: url('https://images.unsplash.com/photo-1565307528294-f70f3c7094e0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80') center center / cover no-repeat;
    }

    .btn-relaxation {
        background: rgba(13, 202, 240, 0.85);
        color: white;
    }

    .btn-relaxation:hover {
        background: rgba(13, 202, 240, 1);
        color: white;
    }

    /* Variation 5: Classic Theme */
    .card-classic {
        background: url('https://images.unsplash.com/photo-1501555088652-021faa106b9b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1473&q=80') center center / cover no-repeat;
    }

    .btn-classic {
        background: rgba(111, 66, 193, 0.85);
        color: white;
    }

    .btn-classic:hover {
        background: rgba(111, 66, 193, 1);
        color: white;
    }
</style>
<style>

    .newsletter-section {
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }
    
    .newsletter-section:hover {
        transform: translateY(-5px);
    }
    
    /* Variation 1 - Full Width Gradient */
    .variation-1 {
        background: linear-gradient(135deg, var(--primary) 0%, #3498db 100%);
        color: white;
        padding: 50px 30px;
    }
    
    .variation-1 .form-control {
        background: rgba(255, 255, 255, 0.15);
        border: none;
        color: white;
        padding: 15px 20px;
        height: 54px;
    }
    
    .variation-1 .form-control::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }
    
    .variation-1 .btn {
        background: white;
        color: var(--primary);
        font-weight: 600;
        padding: 15px 30px;
        height: 54px;
        transition: all 0.3s;
    }
    
    .variation-1 .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    /* Variation 2 - Full Width with Waves */
    .variation-2 {
        background: white;
        color: var(--dark);
        padding: 50px 30px;
        position: relative;
        overflow: hidden;
    }
    
    .wave-bg {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100px;
        background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1200 120' preserveAspectRatio='none'%3E%3Cpath d='M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z' fill='%232c6b97' fill-opacity='0.1'%3E%3C/path%3E%3C/svg%3E");
        background-size: 1200px 100px;
        z-index: 1;
    }
    
    .variation-2 .btn {
        background: var(--secondary);
        color: white;
        font-weight: 600;
        padding: 15px 30px;
        height: 54px;
        transition: all 0.3s;
    }
    
    /* Variation 3 - Full Width Split Background */
    .variation-3 {
        display: flex;
        padding: 0;
    }
    
    .split-left {
        background: linear-gradient(135deg, var(--primary) 0%, #3498db 100%);
        color: white;
        padding: 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .split-right {
        background: white;
        padding: 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .variation-3 .btn {
        background: var(--primary);
        color: white;
        font-weight: 600;
        padding: 15px 30px;
        height: 54px;
    }
    
    /* Variation 4 - Full Width with Pattern */
    .variation-4 {
        background-color: var(--dark);
        background-image: 
            radial-gradient(circle at 10% 20%, rgba(44, 107, 151, 0.3) 0%, transparent 20%),
            radial-gradient(circle at 90% 80%, rgba(230, 126, 34, 0.3) 0%, transparent 20%);
        color: white;
        padding: 50px 30px;
        position: relative;
    }
    
    .variation-4 .form-control {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: white;
        padding: 15px 20px;
        height: 54px;
    }
    
    .variation-4 .form-control::placeholder {
        color: rgba(255, 255, 255, 0.6);
    }
    
    .variation-4 .btn {
        background: var(--secondary);
        color: white;
        font-weight: 600;
        padding: 15px 30px;
        height: 54px;
    }
    
    /* Variation 5 - Full Width with Illustration */
    .variation-5 {
        background: linear-gradient(135deg, #3F51B5 0%, #8BC34A 100%);
        color: white;
        padding: 50px 30px;
        position: relative;
        overflow: hidden;
    }
    
    .variation-5::before {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        top: -150px;
        right: -150px;
    }
    
    .variation-5::after {
        content: '';
        position: absolute;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        bottom: -100px;
        left: -100px;
    }
    
    .variation-5 .form-control {
        background: rgba(255, 255, 255, 0.15);
        border: none;
        color: white;
        padding: 15px 20px;
        height: 54px;
        position: relative;
        z-index: 2;
    }
    
    .variation-5 .form-control::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }
    
    .variation-5 .btn {
        background: white;
        color: #834d9b;
        font-weight: 600;
        padding: 15px 30px;
        height: 54px;
        position: relative;
        z-index: 2;
        width: 250px;
    }
    
    /* Responsive adjustments */
    @media (max-width: 992px) {
        .split-left, .split-right {
            padding: 30px;
        }
    }
    
    @media (max-width: 768px) {
        .variation-3 {
            flex-direction: column;
        }
        
        .split-left, .split-right {
            width: 100%;
            text-align: center;
        }
    }
</style>
@endsection