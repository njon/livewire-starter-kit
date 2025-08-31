<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('LunarPHP Admin Dashboard') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://animated-dollop-g5v44x4gg5fwq7-80.app.github.dev/css/admin.css"
        crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.css"
        integrity="sha512-7uSoC3grlnRktCWoO4LjHMjotq8gf9XDFQerPuaph+cqR7JC9XKGdvN+UwZMC14aAaBDItdRj3DcSDs4kMWUgg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icons/7.1.0/css/flag-icons.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.2/tinymce.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://animated-dollop-g5v44x4gg5fwq7-80.app.github.dev/js/admin/custom.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"
        integrity="sha512-U2WE1ktpMTuRBPoCFDzomoIorbOyUv0sP8B+INA3EzNAhehbzED1rOJg6bCqPf/Tuposxb5ja/MAUnC8THSbLQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
    const languages = {!! json_encode(\Lunar\Models\Language::all('code')->pluck('code')->toArray()) !!};
    </script>
</head>

<body>
    <nav class="topbar">
        <div class="sidebar-brand">
            <h2><a href="https://animated-dollop-g5v44x4gg5fwq7-80.app.github.dev/"><i class="bi bi-moon-stars"></i>
                    LunarPHP</a></h2>
        </div>
        <ul class="topbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="bi bi-bell"></i>
                    <span class="badge bg-danger">3</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-notifications p-0" style="">
                    <div class="dropdown-header d-flex justify-content-between align-items-center p-3">
                        <h6 class="m-0">{{ __('Notifications') }}</h6>
                        <a href="#" class="text-muted small">{{ __('Clear All') }}</a>
                    </div>
                    <div class="dropdown-notifications-container" style="max-height: 400px; overflow-y: auto;">
                        @include('admin.partials.notifications')
                    </div>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-envelope"></i>
                    <span class="badge bg-danger">7</span>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                    <span class="d-none d-lg-inline">{{ __('Admin User') }}</span>
                    <img src="https://ui-avatars.com/api/?name=Admin+User&amp;background=6366f1&amp;color=fff"
                        alt="User" class="user-avatar">
                </a>
            </li>
        </ul>
    </nav>

    <!-- Sidebar -->
    @include('admin.partials.sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <div class="row">
            <div class="col-9 offset-lg-1 mt-4">

                @yield('toolbar')

                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @yield('content')

            </div>
        </div>
    </div>


    <!-- Footer 
            @todo fix. add (C) date, LINKS
             -->
    <footer class="footer mt-4">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="text-center text-md-start">
                        &copy; 2023 LunarPHP. All rights reserved.
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="text-center text-md-end">
                        <a href="#" class="text-decoration-none me-3">{{ __('Privacy Policy') }}</a>
                        <a href="#" class="text-decoration-none">{{ __('Terms of Service') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    // Toggle sidebar on mobile
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggle = document.querySelector('.bi-moon-stars');
        const sidebar = document.querySelector('.sidebar');

        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('smalled');
        });
    });
    </script>
</body>
</html>