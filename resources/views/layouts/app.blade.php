<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport"  content="width=device-width, initial-scale=1" >
    <title>Demo Storefront</title>
    <meta name="description" content="Example of an ecommerce storefront built with Lunar.">
    <link rel="stylesheet" href="https://effective-space-happiness-6rx99v96443rvrv-80.app.github.dev/css/custom.css" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">  
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@350" rel="stylesheet" />

    <!-- Latest compiled and minified JavaScript -->
    <script src="/js/custom.js" ></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
</head>

<body class="antialiased text-gray-900">

    @include('partials.menu')

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                @yield('content')
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>
</html>
