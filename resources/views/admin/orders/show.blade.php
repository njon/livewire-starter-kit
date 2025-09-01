@extends('admin.app')

@section('toolbar')
@include('admin.partials.buttons', [
'title' => 'Order #' . $order->reference,
'asset' => __('Order'),
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
                <h3 class="h5 mb-0">{{ __('Ordered Services') }}</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">{{ __('Product') }}</th>
                                <th>{{ __('SKU') }}</th>
                                <th class="text-end">{{ __('Price') }}</th>
                                <th class="text-end">{{ __('Qty') }}</th>
                                <th class="text-end pe-4">{{ __('Total') }}</th>
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
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-{{ $order->status_color }} bg-opacity-10 text-{{ $order->status_color }}">
                                        {{ $order->status }}
                                    </span>
                                    <button class="btn btn-sm btn-outline-secondary update-status-btn" data-order-id="{{ $order->id }}" data-current-status="{{ $order->status }}">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </button>
                                </div>
                </div>
                <div class="text-muted fs-14 mt-1">
                    {{ $order->created_at->format('F j, Y \a\t g:i A') }}
                </div>
                @if($order->notes)
                <div class="border-top mt-3">
                    <h6 class="fs-14 mt-3">{{ __('Notes') }}</h6>
                    <p class="mb-0">{{ $order->notes ?? __('No notes') }}</p>
                </div>
                @endif
            </div>


            <div class="card-body">
                <!-- Order items table would go here -->

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="border-bottom pb-3 mb-3">
                            <h6 class="fs-14 text-muted mb-2">{{ __('Sub Total') }}</h6>
                            <p class="mb-0">{{ format_price($order->owner_subtotal)->formatted() }}</p>
                        </div>

                        <div class="border-bottom pb-3 mb-3">
                            <h6 class="fs-14 text-muted mb-2">{{ __('Discount') }}</h6>
                            <p class="mb-0">{{ format_price($order->owner_discount)->formatted() }}</p>
                        </div>
                        
                          <div>
                            <h6 class="fs-14 text-muted mb-2">{{ __('Paid') }}</h6>
                            <p class="mb-0">
                                {{ ($order->captures && $order->captures->first()) ? $order->captures->first()->amount->formatted() : __('Not paid') }}
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border-bottom pb-3 mb-3">
                            <h6 class="fs-14 text-muted mb-2">{{ __('VAT') }}</h6>
                            <p class="mb-0">{{ format_price($order->owner_vat)->formatted() }}</p>
                        </div>

                        <div class="border-bottom pb-3 mb-3">
                            <h6 class="fs-14 text-muted mb-2">{{ __('Total') }}</h6>
                            <p class="mb-0">{{ format_price($order->owner_total)->formatted() }}</p>
                        </div>

                      

                        <div>
                            <h6 class="fs-14 text-muted mb-2">{{ __('Refund') }}</h6>
                            @if($order->refund_total)
                            <p class="mb-0">{{ $order->refund_total }}</p>
                            @else
                            <p class="mb-0">{{ __('No refund') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-bottom py-3">
                <h3 class="h5 mb-0">{{ __('Transactions') }}</h3>
            </div>
            <div class="card-body">
                @if($order->transactions->isEmpty())
                <p class="mb-0 text-muted">{{ __('No transactions found.') }}</p>
                @else
                @foreach($order->transactions as $transaction)
                <div class="border-bottom pb-3 mb-3 last:border-0 last:pb-0 last:mb-0">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">{{ __('Payment successful') }} - {{ $transaction->amount }}</h6>
                        <span class="badge bg-success bg-opacity-10 text-success">{{ __('Completed') }}</span>
                    </div>
                    <div class="text-muted fs-14 mb-1">
                        {{ $transaction->created_at->format('F j, Y \a\t g:i A') }}
                    </div>
                    <p class="mb-0 text-muted fs-14">
                        {{ __('Payment received via') }} {{ $transaction->provider }} | {{ __('Payment intent') }}:
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
                        <textarea class="form-control" name="comment" rows="3" placeholder="{{ __('Add a comment') }}"></textarea>
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
                <h3 class="h5 mb-0">{{ __('Customer') }}</h3>
            </div>
            <div class="card-body">
                <div class="border-bottom pb-3 mb-3">
                    <h6 class="fs-14 text-muted mb-2">{{ __('New / Returning') }}</h6>
                    <p class="mb-0">{{ $order->customer ? __('Returning') : __('New') }}</p>
                </div>

                <div class="border-bottom pb-3 mb-3">
                    <h6 class="fs-14 text-muted mb-2">{{ __('Reference') }}</h6>
                    <p class="mb-0">{{ $order->reference }}</p>
                </div>

                <div class="border-bottom pb-3 mb-3">
                    <h6 class="fs-14 text-muted mb-2">{{ __('Customer Reference') }}</h6>
                    <p class="mb-0">{{ $order->customer_reference ?? '-' }}</p>
                </div>

                @if($order->channel)
                    <div class="border-bottom pb-3 mb-3">
                        <h6 class="fs-14 text-muted mb-2">{{ __('Store') }}</h6>
                        <p class="mb-0">{{ $order->channel->name }}</p>
                        <p class="mb-0">{{ $order->channel->address }}</p>
                    </div>
                @endif

                <div>
                    <h6 class="fs-14 text-muted mb-2">{{ __('Date Placed') }}</h6>
                    <p class="mb-0">{{ $order->created_at->format('F j, Y \a\t g:i A') }}</p>
                </div>
            </div>
        </div>

        <!-- Shipping Address Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-bottom py-3">
                <h3 class="h5 mb-0">{{ __('Shipping Address') }}</h3>
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
                <p class="mb-0 text-muted fs-14">{{ __('No address set') }}</p>
                @endif
            </div>
        </div>

        <!-- Billing Address Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-bottom py-3">
                <h3 class="h5 mb-0">{{ __('Billing Address') }}</h3>
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
                <p class="mb-0 text-muted fs-14">{{ __('No address set') }}</p>
                @endif
            </div>
        </div>

        <!-- Additional Information Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom py-3">
                <h3 class="h5 mb-0">{{ __('Additional Information') }}</h3>
            </div>
            <div class="card-body">
                <p class="mb-0 text-muted fs-14">{{ $order->additional_information ?? __('No additional information') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Update Order Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="statusForm">
                <div class="modal-body">
                    <p>Current status: <span id="currentStatus" class="fw-bold"></span></p>
                    
                    <div class="mb-3">
                        <label for="newStatus" class="form-label">New Status</label>
                        <select class="form-select" id="newStatus" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="refunded">Refunded</option>
                            <option value="payment-received">Payment Received</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Order status update functionality
document.addEventListener('DOMContentLoaded', function() {
    const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
    let currentOrderId = null;
    let currentStatusElement = null;

    // Handle update status button clicks
    document.querySelectorAll('.update-status-btn').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            const currentStatus = this.dataset.currentStatus;
            
            currentOrderId = orderId;
            
            // Find the status badge element
            currentStatusElement = this.previousElementSibling;
            
            // Set current status in modal
            document.getElementById('currentStatus').textContent = 
                currentStatus.charAt(0).toUpperCase() + currentStatus.slice(1).replace('-', ' ');
            
            // Set selected option
            const select = document.getElementById('newStatus');
            select.value = currentStatus;
            
            statusModal.show();
        });
    });

    // Handle status form submission
    document.getElementById('statusForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const newStatus = document.getElementById('newStatus').value;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Show loading state
        const submitBtn = document.querySelector('#statusForm button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...';
        submitBtn.disabled = true;
        
        // Make AJAX request
        fetch(`/admin/order/${currentOrderId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ status: newStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the status badge
                const statusText = newStatus.charAt(0).toUpperCase() + newStatus.slice(1).replace('-', ' ');
                
                // Update badge classes based on status
                let bgClass = 'secondary';
                let textClass = 'secondary';
                
                if (newStatus === 'payment-received' || newStatus === 'delivered') {
                    bgClass = 'success';
                    textClass = 'success';
                } else if (newStatus === 'processing' || newStatus === 'shipped') {
                    bgClass = 'primary';
                    textClass = 'primary';
                } else if (newStatus === 'cancelled' || newStatus === 'refunded') {
                    bgClass = 'danger';
                    textClass = 'danger';
                }
                
                currentStatusElement.className = `badge bg-${bgClass} bg-opacity-10 text-${textClass}`;
                currentStatusElement.textContent = statusText;
                
                // Update button data attribute
                const updateBtn = document.querySelector(`.update-status-btn[data-order-id="${currentOrderId}"]`);
                updateBtn.dataset.currentStatus = newStatus;
                
                // Show success message
                showToast('Status updated successfully!', 'success');
            } else {
                showToast('Error updating status: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error updating status. Please try again.', 'error');
        })
        .finally(() => {
            // Restore button state
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            statusModal.hide();
        });
    });
});

// Toast notification function
function showToast(message, type = 'info') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} border-0`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    `;
    
    // Add to container
    const toastContainer = document.getElementById('toastContainer') || createToastContainer();
    toastContainer.appendChild(toast);
    
    // Show toast
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Remove toast after it hides
    toast.addEventListener('hidden.bs.toast', () => {
        toast.remove();
    });
}

function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toastContainer';
    container.className = 'toast-container position-fixed top-0 end-0 p-3';
    container.style.zIndex = '9999';
    document.body.appendChild(container);
    return container;
}
</script>
@endsection