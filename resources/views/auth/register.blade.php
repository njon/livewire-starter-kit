@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <!-- Card Header with Gradient Background -->
                <h2 class="h4 mb-0 fw-bold mt-5 ms-5">{{ __('Create Your Account') }}</h2>

                <!-- Card Body -->
                <div class="card-body p-5">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Name Field -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-medium">{{ __('Full Name') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fa fa-user text-primary"></i>
                                </span>
                                <input id="name" type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                       name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                                       placeholder="Enter your full name">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Email Field -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-medium">{{ __('Email Address') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fa fa-envelope text-primary"></i>
                                </span>
                                <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email') }}" required autocomplete="email"
                                       placeholder="your@email.com">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-medium">{{ __('Password') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fa fa-lock text-primary"></i>
                                </span>
                                <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                       name="password" required autocomplete="new-password"
                                       placeholder="At least 8 characters">
                                <button class="btn btn-light" type="button" id="togglePassword">
                                    <i class="fa fa-eye"></i>
                                </button>
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-text">Use 8 or more characters with a mix of letters, numbers & symbols</div>
                        </div>

                        <!-- Confirm Password Field -->
                        <div class="mb-5">
                            <label for="password-confirm" class="form-label fw-medium">{{ __('Confirm Password') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fa fa-lock text-primary"></i>
                                </span>
                                <input id="password-confirm" type="password" class="form-control form-control-lg" 
                                       name="password_confirmation" required autocomplete="new-password"
                                       placeholder="Confirm your password">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary py-2 fw-bold">
                                {{ __('Create Account') }}
                            </button>
                        </div>

                        <!-- Divider -->
                        <div class="position-relative my-4">
                            <hr class="border-1">
                            <div class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted">
                                OR CONTINUE WITH
                            </div>
                        </div>

                        <!-- Social Login Buttons -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <a href="{{ url('/auth/facebook') }}" class="btn btn-outline-primary w-100 py-2">
                                    <i class="fab fa-facebook-f me-2"></i> Facebook
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ url('/auth/google') }}" class="btn btn-outline-danger w-100 py-2">
                                    <i class="fab fa-google me-2"></i> Google
                                </a>
                            </div>
                        </div>

                        <!-- Login Link -->
                        <div class="text-center mt-4">
                            <p class="text-muted">Already have an account? 
                                <a href="{{ route('login') }}" class="text-primary fw-medium">Sign in</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const password = document.getElementById('password');
        const icon = this.querySelector('i');
        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            password.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    });
</script>
@endpush
@endsection