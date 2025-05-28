<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/black.css" rel="stylesheet">
</head>

<body class="antialiased text-gray-900">
    <!-- Header -->
    <header class="header d-flex align-items-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-2">
                    <a href="#" class="back-btn">
                        <i class="bi bi-arrow-left"></i>
                    </a>
                </div>
                <div class="col-8 text-center">
                    <h2><a href="/" class="text-white text-decoration-none">@ Your Company</a></h2>
                </div>
                <div class="col-2"></div>
            </div>
        </div>
    </header>

    @include('partials.menu-services');

    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <div class="mb-3">
                        <a href="#" class="text-decoration-none me-3" style="color: #aaa;">Privacy Policy</a>
                        <a href="#" class="text-decoration-none me-3" style="color: #aaa;">Terms of Service</a>
                        <a href="#" class="text-decoration-none me-3" style="color: #aaa;">GDPR Compliance</a>
                        <a href="#" class="text-decoration-none" style="color: #aaa;">Cookie Policy</a>
                    </div>
                    <p class="mb-0">© 2023 Company Name. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>