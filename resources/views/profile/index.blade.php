 @extends('layouts.app')

 @section('title', 'User Profile')

 @section('content')

 <h1 class="py-4 fs-2">{{ __('User Profile') }}</h1>

 <div class="row">
     <!-- Left Side Menu -->
     <div class="col-md-3 mb-4">
         <div class="card shadow-sm">
             <div class="card-header bg-primary text-white">
                 <h5 class="mb-0">{{ __('User Profile') }}</h5>
             </div>
             <div class="list-group list-group-flush">
                 <a href="#profile-details" class="list-group-item list-group-item-action active" data-bs-toggle="pill">
                     <i class="bi bi-person me-2"></i>{{ __('Profile details') }}
                 </a>
                 <a href="#password-reset" class="list-group-item list-group-item-action" data-bs-toggle="pill">
                     <i class="bi bi-shield-lock me-2"></i>{{ __('Password reset') }}
                 </a>
                 <a href="#my-orders" class="list-group-item list-group-item-action" data-bs-toggle="pill">
                     <i class="bi bi-bag me-2"></i>{{ __('My orders') }}
                 </a>
                 <a href="#wishlist" class="list-group-item list-group-item-action" data-bs-toggle="pill">
                     <i class="bi bi-heart me-2"></i>{{ __('Wishlist') }}
                 </a>
                 <a href="#newsletter" class="list-group-item list-group-item-action" data-bs-toggle="pill">
                     <i class="bi bi-envelope me-2"></i>{{ __('Newsletter') }}
                 </a>
                 <form method="POST" action="{{ route('logout') }}" class="list-group-item list-group-item-action">
                     @csrf
                     <button type="submit"
                         class="btn btn-link text-decoration-none p-0 border-0 bg-transparent w-100 text-start">
                         <i class="bi bi-box-arrow-right me-2"></i>{{ __('Logout') }}
                     </button>
                 </form>
             </div>
         </div>
     </div>

     <!-- Right Side Content -->
     <div class="col-lg-9">
    <div class="tab-content">
        <!-- Profile Details -->
        <div class="tab-pane fade show active" id="profile-details">
            <div class="card border-0 shadow-sm rounded-lg mb-4">
                <div class="card-header bg-white border-bottom-0 py-3 px-4">
                    <h5 class="mb-0 font-weight-600">{{ __('Profile Details') }}</h5>
                    <p class="text-muted small mb-0">{{ __('Update your personal information') }}</p>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @method('PUT')
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="form-label text-gray-600 small">{{ __('Name') }}</label>
                            <input type="text" class="form-control rounded-pill py-3 px-4 border-2" 
                                   id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label text-gray-600 small">{{ __('Email') }}</label>
                            <input type="email" class="form-control rounded-pill py-3 px-4 border-2" 
                                   id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                        </div>

                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 text-white fw-bold">
                            <i class="bi bi-check-circle me-2"></i>{{ __('Update Profile') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Password Reset -->
        <div class="tab-pane fade" id="password-reset">
            <div class="card border-0 shadow-sm rounded-lg mb-4">
                <div class="card-header bg-white border-bottom-0 py-3 px-4">
                    <h5 class="mb-0 font-weight-600">{{ __('Password Reset') }}</h5>
                    <p class="text-muted small mb-0">{{ __('Change your account password') }}</p>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('password.update') }}">
                        @method('PUT')
                        @csrf

                        <div class="mb-4">
                            <label for="current_password" class="form-label text-gray-600 small">{{ __('Current Password') }}</label>
                            <input type="password" class="form-control rounded-pill py-3 px-4 border-2" 
                                   id="current_password" name="current_password" required>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label text-gray-600 small">{{ __('New Password') }}</label>
                            <input type="password" class="form-control rounded-pill py-3 px-4 border-2" 
                                   id="password" name="password" required>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label text-gray-600 small">{{ __('Confirm New Password') }}</label>
                            <input type="password" class="form-control rounded-pill py-3 px-4 border-2" 
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 text-white fw-bold">
                            <i class="bi bi-shield-lock me-2"></i>{{ __('Update Password') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- My Orders -->
        <div class="tab-pane fade" id="my-orders">
            <div class="card border-0 shadow-sm rounded-lg mb-4">
                <div class="card-header bg-white border-bottom-0 py-3 px-4">
                    <h5 class="mb-0 font-weight-600">{{ __('My Orders') }}</h5>
                    <p class="text-muted small mb-0">{{ __('Your order history') }}</p>
                </div>
                <div class="card-body p-4">
                    @if($orders->isEmpty())
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-cart-x text-muted" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="text-gray-700">{{ __("You haven't placed any orders yet.") }}</h5>
                        <p class="text-muted small">{{ __('Your orders will appear here') }}</p>
                    </div>
                    @else
                    <div class="list-group list-group-flush">
                        @foreach($orders as $order)
                        <div class="list-group-item border-0 px-0 py-3">
                            <div class="d-flex flex-column flex-md-row justify-content-between mb-3">
                                <div class="mb-2 mb-md-0">
                                    <span class="fw-bold text-dark">{{ __('Order #:ref', ['ref' => $order->reference]) }}</span>
                                    <span class="text-muted ms-2">{{ $order->created_at->format('M d, Y') }}</span>
                                </div>
                                <div>
                                    <span class="badge rounded-pill py-2 px-3 
                                        {{ $order->status === 'payment-received' ? 'bg-success' : 
                                           ($order->status === 'cancelled' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                        {{ __(ucfirst($order->status)) }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-8">
                                    @foreach($order->lines as $line)
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="flex-shrink-0 me-3">
                                            @if($line->purchasable && $line->purchasable->product->getMedia('thumbnails')->isNotEmpty())
                                            <img src="{{ $line->purchasable->product->getMedia('thumbnails')->first()->getUrl() }}" 
                                                 alt="{{ $line->description }}" 
                                                 class="rounded-3" style="width: 64px; height: 64px; object-fit: cover;">
                                            @else
                                            <div class="rounded-3 bg-light d-flex align-items-center justify-content-center" 
                                                 style="width: 64px; height: 64px;">
                                                <i class="bi bi-box-seam text-muted"></i>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                {{ $line->purchasable ? ($line->purchasable->product->translateAttribute('name') ?? __('N/A')) : __('Item not available') }}
                                            </h6>
                                            <p class="mb-1 text-muted small">
                                                {{ __('Quantity: :count items', ['count' => $line->quantity]) }}
                                            </p>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex flex-column h-100 justify-content-between">
                                        <div class="text-md-end mb-3">
                                            <p class="text-muted small mb-1">{{ __('Total') }}</p>
                                            <h5 class="mb-0 text-dark">{{ $order->total->formatted }}</h5>
                                        </div>
                                        <div class="text-md-end">
                                            <button class="btn btn-outline-primary rounded-pill px-3 py-1">
                                                {{ __('View Details') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Wishlist -->
        <div class="tab-pane fade" id="wishlist">
            <div class="card border-0 shadow-sm rounded-lg mb-4">
                <div class="card-header bg-white border-bottom-0 py-3 px-4">
                    <h5 class="mb-0 font-weight-600">{{ __('Wishlist') }}</h5>
                    <p class="text-muted small mb-0">{{ __('Your saved items') }}</p>
                </div>
                <div class="card-body p-4">
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-heart text-muted" style="font-size: 3rem;"></i>
                        </div>
                        <h5 class="text-gray-700">{{ __('Your wishlist is empty') }}</h5>
                        <p class="text-muted small">{{ __('Your saved items will appear here') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Newsletter -->
        <div class="tab-pane fade" id="newsletter">
            <div class="card border-0 shadow-sm rounded-lg mb-4">
                <div class="card-header bg-white border-bottom-0 py-3 px-4">
                    <h5 class="mb-0 font-weight-600">{{ __('Newsletter Preferences') }}</h5>
                    <p class="text-muted small mb-0">{{ __('Manage your email subscriptions') }}</p>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('newsletter.update') }}">
                        @csrf

                        <div class="form-check form-switch ps-0 mb-4">
                            <div class="d-flex align-items-center">
                                <input class="form-check-input ms-0 me-3" type="checkbox" 
                                       id="newsletter_subscribe" name="newsletter_subscribe"
                                       {{ auth()->user()->newsletter_subscribed ? 'checked' : '' }}>
                                <label class="form-check-label text-gray-600" for="newsletter_subscribe">
                                    {{ __('Subscribe to newsletter') }}
                                </label>
                            </div>
                            <p class="text-muted small mt-1">{{ __('Receive updates, promotions and special offers') }}</p>
                        </div>

                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 text-white fw-bold">
                            <i class="bi bi-envelope-check me-2"></i>{{ __('Update Preferences') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
 </div>
 @endsection