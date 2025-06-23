@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Success Card -->
        <div class="card border-0  rounded-4 overflow-hidden">
            <div class="card-body p-5 text-center">
                <!-- Animated Checkmark -->
                <div class="success-animation mb-4">
                    <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52" width="80"
                        height="80">
                        <circle class="checkmark__circle" cx="26" cy="26" r="25"></circle>
                        <path class="checkmark__check" fill="none" stroke="#28a745" stroke-width="4"
                            stroke-linecap="round" d="M14.1 27.2l7.1 7.2 16.7-16.8"></path>
                    </svg>
                </div>

                <!-- Main Heading -->
                <h1 class="display-5 fw-bold text-success mb-3">Order Confirmed!</h1>
                <p class="lead mb-4">Your experience is being prepared with care</p>

                <!-- Order Details -->
                <div class="bg-light p-4 rounded-3 mb-4">
                    <div class="row">
<div class="col-md-6 mb-3 mb-md-0">
                                <h3 class="h5 mb-2 text-start">Order Summary</h3>
                                <ul class="list-unstyled text-start">
                                    <li class="mb-2"><strong>Order #:</strong> {{ $order->id }}</li>
                                    <li class="mb-2"><strong>Date:</strong>
                                        {{ $order->created_at->format('F j, Y') }}</li>
                                    <li class="mb-2"><strong>Total:</strong> {{ $order->total->formatted }}</li>
                                </ul>
                            </div>
                            
                        <div class="col-md-6">
                            <h3 class="h5 mb-2 text-start">What's Next</h3>
                            <ul class="list-unstyled text-start">
                                <li class="mb-2"><span class="badge bg-primary me-2">1</span> Instant booking
                                    confirmation</li>
                                <li class="mb-2"><span class="badge bg-primary me-2">2</span> Details sent to your email
                                </li>
                                <li><span class="badge bg-primary me-2">3</span> Prepare for an unforgettable
                                    experience!</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Uplifting Message -->
                <div class="alert alert-success bg-opacity-10 border-0 mb-4 py-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <span class="material-symbols-outlined text-success fs-1">celebration</span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="alert-heading mb-3" style="margin-left: -50px;">Get Ready for Adventure!</h4>
                            <p class="mb-0">Your <strong>Air Jordan Winter Experience</strong> is now confirmed. Our
                                team is preparing everything to ensure you have an amazing time. Expect your detailed
                                instructions via email shortly.</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
                    <a href="/" class="btn btn-success px-4 py-2">
                        <span class="material-symbols-outlined align-middle me-2">home</span>
                        Back to Home
                    </a>
                    <a href="/profile/orders" class="btn btn-outline-secondary px-4 py-2">
                        <span class="material-symbols-outlined align-middle me-2">receipt</span>
                        View Order Details
                    </a>
                </div>

                <!-- Share Options -->
                <div class="mt-5 pt-3 border-top">
                    <p class="text-muted mb-3">Share your excitement!</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="#" class="btn btn-sm btn-outline-primary rounded-circle">
                            <i class="fa fa-facebook"></i>
                        </a>
                        <a href="#" class="btn btn-sm btn-outline-info rounded-circle">
                            <i class="fa fa-twitter"></i>
                        </a>
                        <a href="#" class="btn btn-sm btn-outline-danger rounded-circle">
                            <i class="fa fa-instagram"></i>
                        </a>
                        <a href="#" class="btn btn-sm btn-outline-dark rounded-circle">
                            <i class="fa fa-whatsapp"></i>
                        </a>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card border-0 shadow-sm mt-4 rounded-4 overflow-hidden text-start">
                        <div class="card-body p-4">
                            <h3 class="h5 mb-3 text-start mb-5">Your Upcoming Experience</h3>
                            @foreach($order->lines as $line)
                            <div class="d-flex flex-column flex-md-row gap-3">
                                <img src="https://images.unsplash.com/photo-1544161515-4ab6ce6db874?ixlib=rb-1.2.1&amp;auto=format&amp;fit=crop&amp;w=300&amp;q=80"
                                    alt="Air Jordan Winter Experience" class="rounded-3 object-fit-cover" width="120"
                                    height="120">
                                <div>
                                    <h4 class="h6 mb-1">
                                        {{ $line->purchasable->product->translateAttribute('name') }}</h4>
                                    <p class="small text-muted mb-2">2 participants • 45 minutes</p>
                                    <p class="mb-2"><span
                                            class="badge bg-success bg-opacity-10 text-success">Confirmed</span></p>
                                    <p class="small mb-0">Athens, Thessaloniki • June 25, 2024</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Billing Address @todo Remove or leave -->
                    <div class="alert alert-light border mb-4 text-start d-none">
                        <h3 class="h6 text-muted mb-3">Billing Information</h3>
                        <div class="vstack gap-1">
                            <div><strong>{{ $order->billingAddress->first_name }}
                                    {{ $order->billingAddress->last_name }}</strong></div>
                            <div>{{ $order->billingAddress->line_one }}</div>
                            @if($order->billingAddress->line_two)
                            <div>{{ $order->billingAddress->line_two }}</div>
                            @endif
                            <div>
                                {{ $order->billingAddress->city }},
                                {{ $order->billingAddress->state }}
                                {{ $order->billingAddress->postcode }}
                            </div>
                            <div>{{ $order->billingAddress->country->name }}</div>
                        </div>
                    </div>
@endsection