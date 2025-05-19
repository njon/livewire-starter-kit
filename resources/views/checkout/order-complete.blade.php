@extends('layouts.app')

@section('content')
<div class="py-5">
    <div class="container">
        <!-- Success Card -->
        <div class="card border-0 shadow overflow-hidden mb-4">
            <!-- Header -->
            <div class="card-header bg-success text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="h4 mb-0">Order Confirmed!</h1>
                    <i class="bi bi-check-circle-fill fs-3"></i>
                </div>
                <p class="mb-0 opacity-75">Thank you for your purchase</p>
            </div>

            <!-- Order Summary -->
            <div class="card-body border-bottom">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h2 class="h5 fw-semibold mb-1">Order #{{ $order->id }}</h2>
                        <p class="text-muted small mb-0">
                            Placed on {{ $order->created_at->format('F j, Y \a\t g:i A') }}
                        </p>
                    </div>
                    <span class="badge bg-success bg-opacity-10 text-success">
                        {{ $order->status }}
                    </span>
                </div>

                <div class="row g-4 mt-3">
                    <!-- Billing Address -->
                    <div class="col-md-6">
                        <h3 class="h6 text-muted mb-2">Billing Address</h3>
                        <div class="vstack gap-1">
                            <div>{{ $order->billingAddress->first_name }} {{ $order->billingAddress->last_name }}</div>
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
                </div>
            </div>

            <!-- Items List -->
            <div class="card-body">
                <h3 class="h5 fw-semibold mb-3">Your Items</h3>
                <ul class="list-group list-group-flush">
                    @foreach($order->lines as $line)
                    <li class="list-group-item py-3">
                        <div class="d-flex gap-3">
                            <div class="flex-shrink-0">
                                <img src="{{ $line->purchasable->product->thumbnail->getUrl('small') }}" 
                                     alt="{{ $line->description }}" 
                                     class="rounded border" 
                                     width="80" 
                                     height="80"
                                     style="object-fit: cover">
                            </div>
                            <div class="flex-grow-1">
                                <h4 class="h6 mb-1">{{ $line->description }}</h4>
                                <div class="d-flex justify-content-between align-items-end">
                                    <small class="text-muted">Qty: {{ $line->quantity }}</small>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>

              
            </div>

            <!-- Footer CTA -->
            <div class="card-footer bg-light">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <p class="small text-muted mb-3 mb-md-0">
                        We've sent your order confirmation to {{ $order->customer->email }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection