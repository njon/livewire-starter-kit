 @extends('layouts.app')

 @section('title', 'User Profile')

 @section('content')
 @csrf
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
                 <a href="#my-bookings" class="list-group-item list-group-item-action" data-bs-toggle="pill">
                     <i class="bi bi-calendar-check me-2"></i>{{ __('My bookings') }}
                 </a>
                 <a href="#invite-friends" class="list-group-item list-group-item-action" data-bs-toggle="pill">
                     <i class="bi bi-people me-2"></i>{{ __('Invite Friends') }}
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
     <div class="col-md-9">
         <div class="tab-content">
             <!-- Profile Details -->
             <div class="tab-pane fade show active" id="profile-details">
                 <div class="card shadow-sm">
                     <div class="card-header bg-light">
                         <h5 class="mb-0">{{ __('Profile Details') }}</h5>
                     </div>
                     <div class="card-body">
                         <form method="POST" action="{{ route('profile.update') }}">
                             @csrf
                             @method('PUT')

                             <div class="mb-3">
                                 <label for="name" class="form-label">{{ __('Name') }}</label>
                                 <input type="text" class="form-control" id="name" name="name"
                                     value="{{ old('name', auth()->user()->name) }}" required>
                             </div>

                             <div class="mb-3">
                                 <label for="email" class="form-label">{{ __('Email') }}</label>
                                 <input type="email" class="form-control" id="email" name="email"
                                     value="{{ old('email', auth()->user()->email) }}" required>
                             </div>

                             <button type="submit" class="btn btn-primary">{{ __('Update Profile') }}</button>
                         </form>
                     </div>
                 </div>
             </div>

             <!-- Password Reset -->
             <div class="tab-pane fade" id="password-reset">
                 <div class="card shadow-sm">
                     <div class="card-header bg-light">
                         <h5 class="mb-0">{{ __('Password Reset') }}</h5>
                     </div>
                     <div class="card-body">
                         <form method="POST" action="{{ route('password.update') }}">
                             @csrf
                             @method('PUT')

                             <div class="mb-3">
                                 <label for="current_password" class="form-label">{{ __('Current Password') }}</label>
                                 <input type="password" class="form-control" id="current_password"
                                     name="current_password" required>
                             </div>

                             <div class="mb-3">
                                 <label for="password" class="form-label">{{ __('New Password') }}</label>
                                 <input type="password" class="form-control" id="password" name="password" required>
                             </div>

                             <div class="mb-3">
                                 <label for="password_confirmation"
                                     class="form-label">{{ __('Confirm New Password') }}</label>
                                 <input type="password" class="form-control" id="password_confirmation"
                                     name="password_confirmation" required>
                             </div>

                             <button type="submit" class="btn btn-primary">{{ __('Update Password') }}</button>
                         </form>
                     </div>
                 </div>
             </div>

             <!-- My Orders -->
             <div class="tab-pane fade" id="my-orders">
                 <div class="container py-5">
                     <div class="row mb-4">
                         <div class="col-12">
                             <h1 class="h2">{{ __('My Orders') }}</h1>
                             <hr class="my-3">
                         </div>
                     </div>

                     @if($orders->isEmpty())
                     <div class="row">
                         <div class="col-12">
                             <div class="alert alert-info">
                                 {{ __("You haven't placed any orders yet.") }}
                             </div>
                         </div>
                     </div>
                     @else
                     <div class="row">
                         <div class="col-12">
                             @foreach($orders as $order)
                             <div class="card mb-4 shadow-sm">
                                 <div class="card-header bg-light">
                                     <div class="d-flex justify-content-between align-items-center">
                                         <div>
                                             <span
                                                 class="fw-bold">{{ __('Order #:ref', ['ref' => $order->reference]) }}</span>
                                             <span
                                                 class="ms-3 text-muted">{{ $order->created_at->format('M d, Y') }}</span>
                                         </div>
                                         <div>
                                             <span
                                                 class="badge rounded-pill 
                                                {{ $order->status === 'payment-received' ? 'bg-success' : 
                                                   ($order->status === 'cancelled' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                                 {{ __(ucfirst($order->status)) }}
                                             </span>
                                         </div>
                                     </div>
                                 </div>
                                 <div class="card-body">
                                     <div class="row">
                                         <div class="col-md-6">
                                             <div class="d-flex align-items-center mb-3">
                                                 <div class="order-items">
                                                     @foreach($order->lines as $line)
                                                     <div class="d-flex align-items-center mb-3">
                                                         <div class="flex-shrink-0 me-3">
                                                             @if($line->purchasable->product->thumbnail->getUrl('small'))
                                                             <img src="{{ $line->purchasable->product->thumbnail->getUrl('small') }}"
                                                                 alt="{{ __('Product image') }}"
                                                                 class="img-fluid rounded"
                                                                 style="width: 80px; height: 80px; object-fit: cover;">
                                                             @else
                                                             <div class="bg-secondary rounded"
                                                                 style="width: 80px; height: 80px;"></div>
                                                             @endif
                                                         </div>
                                                         <div class="flex-grow-1">
                                                             <h5 class="mb-1">
                                                                 {{ $line->purchasable->product->translateAttribute('name') ?? __('N/A') }}
                                                             </h5>
                                                             <p class="mb-1 text-muted ">
                                                                 {{ __('Quantity: :count items', ['count' => $line->quantity]) }}
                                                             </p>
                                                         </div>
                                                     </div>
                                                     @endforeach
                                                 </div>
                                             </div>
                                         </div>
                                         <div class="col-md-3 d-flex align-items-start">
                                             <div>
                                                 <h5 class="mb-0 text-small fw-500">{{ __('Total') }}</h5>
                                                 <p class="mb-0 fs-5 fw-600">{{ $order->total->formatted }}</p>
                                             </div>
                                         </div>
                                         <div class="col-md-3 d-flex align-items-start justify-content-end">
                                             {{ __('View Details') }}
                                         </div>
                                     </div>
                                 </div>
                             </div>
                             @endforeach
                         </div>
                     </div>
                     @endif
                 </div>
             </div>

             <!-- Wishlist -->
             <div class="tab-pane fade" id="wishlist">
                 <div class="card shadow-sm">
                     <div class="card-header bg-light">
                         <h5 class="mb-0">{{ __('Wishlist') }}</h5>
                     </div>
                     <div class="card-body">
                         <p class="text-muted">{{ __('Your saved items will appear here') }}</p>
                     </div>
                 </div>
             </div>

             <!-- Newsletter -->
             <div class="tab-pane fade" id="newsletter">
                 <div class="card shadow-sm">
                     <div class="card-header bg-light">
                         <h5 class="mb-0">{{ __('Newsletter Preferences') }}</h5>
                     </div>
                     <div class="card-body">
                         <form method="POST" action="{{ route('newsletter.update') }}">
                             @csrf
                             <div class="form-check form-switch mb-3">
                                 <input class="form-check-input" type="checkbox" id="newsletter_subscribe"
                                     name="newsletter_subscribe"
                                     {{ auth()->user()->newsletter_subscribed ? 'checked' : '' }}>
                                 <label class="form-check-label" for="newsletter_subscribe">
                                     {{ __('Subscribe to newsletter') }}
                                 </label>
                             </div>

                             <button type="submit" class="btn btn-primary">{{ __('Update Preferences') }}</button>
                         </form>
                     </div>
                 </div>
             </div>

             <!-- My Bookings -->
             <div class="tab-pane fade" id="my-bookings">
                 <div class="card shadow-sm">
                     <div class="card-header bg-light">
                         <h5 class="mb-0">{{ __('My Bookings') }}</h5>
                     </div>
                     <div class="card-body">
                         <p class="text-muted">{{ __('Your booking history will appear here') }}</p>
                     </div>
                 </div>
             </div>

             <!-- Invite Friends -->
             <div class="tab-pane fade" id="invite-friends">
                 <div class="card shadow-sm">
                     <div class="card-header bg-light">
                         <h5 class="mb-0">{{ __('Invite Friends') }}</h5>
                     </div>
                     <div class="card-body">
                         <form method="POST" action="{{ route('invite.send') }}">
                             @csrf

                             <div class="mb-3">
                                 <label for="emails"
                                     class="form-label">{{ __('Email Addresses (comma separated)') }}</label>
                                 <textarea class="form-control" id="emails" name="emails" rows="3"></textarea>
                             </div>

                             <div class="mb-3">
                                 <label for="message" class="form-label">{{ __('Personal Message (optional)') }}</label>
                                 <textarea class="form-control" id="message" name="message" rows="3"></textarea>
                             </div>

                             <button type="submit" class="btn btn-primary">{{ __('Send Invitations') }}</button>
                         </form>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>
 @endsection