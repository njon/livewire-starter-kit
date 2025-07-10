<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- @todo change ellada experiences to your app name -->
    <title>@yield('title', 'Ellada Experiences') | Ellada Experiences</title>
    <!-- @todo change meta description for homepage and other pages -->
    <meta name="description" content="@yield('meta_description', 'Discover unique experiences in Greece with Ellada Experiences. From cultural tours to adventure activities, find the perfect gift or personal adventure.')">
    <script>
        fetch('wishlist/ajax-items') .then(response => { if (!response.ok) { throw new Error('Network response was not ok'); } return response.json(); }) .then(data => { document.querySelectorAll('.wishlist-add').forEach(btn => { const productId = btn.dataset.productId; const isActive = data.hasOwnProperty(productId); btn.classList.toggle('is-active', isActive); let parent = btn.closest('.btn-wishlist'); if (parent) { parent.classList.toggle('is-active', isActive); } }); }) .catch(error => { console.error('Error fetching wishlist items:', error); });
const processing = '{{ __("Processing...") }}';
const submitting = '{{ __("Submitting...") }}';
const adding = '{{ __("Adding") }}';
const submitQuestion = '{{ __("Submit Question") }}';
const questionSubmittedSuccess = '{{ __("Question submitted successfully!") }}';
const checkoutError = '{{ __("An error occurred during checkout.") }}';
const genericError = '{{ __("An error occurred. Please try again.") }}';
const helpfulError = '{{ __("Error marking as helpful") }}';
const addToCartError = '{{ __("An error occurred while adding to cart") }}';
const removeItemError = '{{ __("An error occurred while removing item") }}';
const updateQuantityError = '{{ __("An error occurred while updating quantity") }}';
const helpful = '{{ __("Helpful") }}';
        </script>
    <link rel="stylesheet" href="https://crispy-rotary-phone-6rx99vvv952567j-80.app.github.dev/css/custom.css" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <!-- @todo add favicon -->
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Bayon&family=Figtree:ital,wght@0,300..900;1,300..900&family=Fraunces:ital,opsz,wght@0,9..144,100..900;1,9..144,100..900&family=Manrope:wght@200..800&family=Rakkas&display=swap" rel="stylesheet">
    <link rel="canonical" href="{{ url()->current() }}" />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bayon&family=Plus Jakarta Sans:wght@200..800&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@350" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous">
    </script>
    <script src="https://crispy-rotary-phone-6rx99vvv952567j-80.app.github.dev/js/custom.js"></script>
    <style>
        /* @todo Remove */
.text-primary {
    --bs-text-opacity: 1;
    color: rgb(96 139 75) !important;
}
    </style>
    @cookieconsentscripts
</head>

<body class="antialiased text-gray-900">

    @include('partials.menu')

    @yield('top-content')

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                @yield('content')
            </div>
        </div>
    </div>

    @include('partials.footer')
    @yield('bottom-content')

    @if(session('success'))
    <div role="alert" aria-live="assertive" aria-atomic="true"
        class="toast position-fixed top-0 end-0 bg-white show border-0 m-3 alert-li" style="z-index:99;"
        data-bs-autohide="false">
        <div class="toast-header">
            <strong class="me-auto">Notification</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            {{ session('success') }}
        </div>
    </div>
    @endif

    @if(session('error'))
    <div role="alert" aria-live="assertive" aria-atomic="true"
        class="toast position-fixed top-0 end-0 bg-white show border-0 m-3 alert-li" style="z-index:99;"
        data-bs-autohide="false">
        <div class="toast-header">
            <strong class="me-auto">Notification</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            {{ session('error') }}
        </div>
    </div>
    @endif


    @cookieconsentview
</body>
    @yield('structured_data')
</html>