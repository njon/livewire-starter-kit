@extends('admin.app')

@section('toolbar')
@include('admin.partials.buttons', [
'title' => 'Order #' . $order->reference,
'asset' => 'Order',
'buttons' => [
['refund' => true, 'url' => route('admin.orders.refund', $order->id)],
['status' => true],
['download' => true, 'url' => route('admin.orders.download', $order->id)]
]
])
@endsection

@section('content')
<div class="row g-4" id="orderDetails">
    <div class="col-lg-8">

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-bottom py-3">
                <h3 class="h5 mb-0">Ordered Services</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Product</th>
                                <th>SKU</th>
                                <th class="text-end">Price</th>
                                <th class="text-end">Qty</th>
                                <th class="text-end pe-4">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lines as $line)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        @if($line->purchasable->product->getMedia('thumbnails')->empty())
                                        <img src="{{ $line->purchasable->product->getMedia('thumbnails')->first()->getUrl() }}"
                                            alt="{{ $line->description }}" class="rounded"
                                            style="width: 48px; height: 48px; object-fit: cover;">
                                        @else
                                        <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                            style="width: 48px; height: 48px;">
                                            <i class="bi bi-box-seam text-muted"></i>
                                        </div>
                                        @endif
                                        <div>
                                            @if($line->purchasable->product)
                                            <small class="text-muted">
                                                {{ $line->purchasable->product->translateAttribute('name') }}
                                            </small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted fs-14">{{ $line->purchasable->sku ?? '-' }}</span>
                                </td>
                                <td class="text-end">{{ $line->unit_price->formatted() }}</td>
                                <td class="text-end">{{ $line->quantity }}</td>
                                <td class="text-end pe-4 fw-medium">{{ $line->sub_total->formatted() }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Order Summary Card -->

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-bottom py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="h5 mb-0">Order #{{ $order->reference }}</h3>
                    <span class="badge bg-{{ $order->status_color }} bg-opacity-10 text-{{ $order->status_color }}">
                        {{ $order->status }}
                    </span>
                </div>
                <div class="text-muted fs-14 mt-1">
                    {{ $order->created_at->format('F j, Y \a\t g:i A') }}
                </div>
                @if($order->notes)
                <div class="border-top mt-3">
                    <h6 class="fs-14 mt-3">Notes</h6>
                    <p class="mb-0">{{ $order->notes ?? 'No notes' }}</p>
                </div>
                @endif
            </div>


            <div class="card-body">
                <!-- Order items table would go here -->

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="border-bottom pb-3 mb-3">
                            <h6 class="fs-14 text-muted mb-2">Sub Total</h6>
                            <p class="mb-0">{{ format_price($order->owner_subtotal)->formatted() }}</p>
                        </div>

                        <div class="border-bottom pb-3 mb-3">
                            <h6 class="fs-14 text-muted mb-2">Discount</h6>
                            <p class="mb-0">{{ format_price($order->owner_discount)->formatted() }}</p>
                        </div>
                        
                          <div>
                            <h6 class="fs-14 text-muted mb-2">Paid</h6>
                            <p class="mb-0">
                                {{ ($order->captures && $order->captures->first()) ? $order->captures->first()->amount->formatted() : 'Not paid' }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border-bottom pb-3 mb-3">
                            <h6 class="fs-14 text-muted mb-2">VAT</h6>
                            <p class="mb-0">{{ format_price($order->owner_vat)->formatted() }}</p>
                        </div>

                        <div class="border-bottom pb-3 mb-3">
                            <h6 class="fs-14 text-muted mb-2">Total</h6>
                            <p class="mb-0">{{ format_price($order->owner_total)->formatted() }}</p>
                        </div>

                      

                        <div>
                            <h6 class="fs-14 text-muted mb-2">Refund</h6>
                            @if($order->refund_total)
                            <p class="mb-0">{{ $order->refund_total }}</p>
                            @else
                            <p class="mb-0">No refund</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-bottom py-3">
                <h3 class="h5 mb-0">Transactions</h3>
            </div>
            <div class="card-body">
                @if($order->transactions->isEmpty())
                <p class="mb-0 text-muted">No transactions found.</p>
                @else
                @foreach($order->transactions as $transaction)
                <div class="border-bottom pb-3 mb-3 last:border-0 last:pb-0 last:mb-0">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Payment successful - {{ $transaction->amount }}</h6>
                        <span class="badge bg-success bg-opacity-10 text-success">Completed</span>
                    </div>
                    <div class="text-muted fs-14 mb-1">
                        {{ $transaction->created_at->format('F j, Y \a\t g:i A') }}
                    </div>
                    <p class="mb-0 text-muted fs-14">
                        Payment received via {{ $transaction->provider }} | Payment intent:
                        {{ $transaction->reference }}
                    </p>
                </div>
                @endforeach
                @endif
            </div>
        </div>

        <!-- Timeline Card -->
        <!-- <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-3">
                <h3 class="h5 mb-0">Timeline</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="#">
                    @csrf
                    <div class="mb-3">
                        <textarea class="form-control" name="comment" rows="3" placeholder="Add a comment"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Add Comment</button>
                </form>
                
                <div class="mt-4">
                </div>
            </div>
        </div> -->
    </div>

    <div class="col-lg-4">
        <!-- Customer Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-bottom py-3">
                <h3 class="h5 mb-0">Customer</h3>
            </div>
            <div class="card-body">
                <div class="border-bottom pb-3 mb-3">
                    <h6 class="fs-14 text-muted mb-2">New / Returning</h6>
                    <p class="mb-0">{{ $order->customer ? 'Returning' : 'New' }}</p>
                </div>

                <div class="border-bottom pb-3 mb-3">
                    <h6 class="fs-14 text-muted mb-2">Reference</h6>
                    <p class="mb-0">{{ $order->reference }}</p>
                </div>

                <div class="border-bottom pb-3 mb-3">
                    <h6 class="fs-14 text-muted mb-2">Customer Reference</h6>
                    <p class="mb-0">{{ $order->customer_reference ?? '-' }}</p>
                </div>

                @if($order->channel)
                    <div class="border-bottom pb-3 mb-3">
                        <h6 class="fs-14 text-muted mb-2">Store</h6>
                        <p class="mb-0">{{ $order->channel->name }}</p>
                        <p class="mb-0">{{ $order->channel->address }}</p>
                    </div>
                @endif

                <div>
                    <h6 class="fs-14 text-muted mb-2">Date Placed</h6>
                    <p class="mb-0">{{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
                </div>
            </div>
        </div>

        <!-- Shipping Address Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-bottom py-3">
                <h3 class="h5 mb-0">Shipping Address</h3>
            </div>
            <div class="card-body">
                @if($order->shippingAddress)
                <div class="fs-14">
                    <div class="mb-1">{{ $order->shippingAddress->line_one }}</div>
                    @if($order->shippingAddress->line_two)
                    <div class="mb-1">{{ $order->shippingAddress->line_two }}</div>
                    @endif
                    <div class="mb-1">{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }}</div>
                    <div class="mb-1">{{ $order->shippingAddress->postcode }}</div>
                    <div>{{ $order->shippingAddress->country->name }}</div>
                </div>
                @else
                <p class="mb-0 text-muted fs-14">No address set</p>
                @endif
            </div>
        </div>

        <!-- Billing Address Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-bottom py-3">
                <h3 class="h5 mb-0">Billing Address</h3>
            </div>
            <div class="card-body">
                @if($order->billingAddress)
                <div class="fs-14">
                    <div class="mb-1">{{ $order->billingAddress->line_one }}</div>
                    @if($order->billingAddress->line_two)
                    <div class="mb-1">{{ $order->billingAddress->line_two }}</div>
                    @endif
                    <div class="mb-1">{{ $order->billingAddress->city }}, {{ $order->billingAddress->state }}</div>
                    <div class="mb-1">{{ $order->billingAddress->postcode }}</div>
                    <div>{{ $order->billingAddress->country->name }}</div>
                </div>
                @else
                <p class="mb-0 text-muted fs-14">No address set</p>
                @endif
            </div>
        </div>

        <!-- Additional Information Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-3">
                <h3 class="h5 mb-0">Additional Information</h3>
            </div>
            <div class="card-body">
                <p class="mb-0 text-muted fs-14">{{ $order->additional_information ?? 'No additional information' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection