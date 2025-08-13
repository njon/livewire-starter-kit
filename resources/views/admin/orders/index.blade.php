@extends('admin.app')

@section('toolbar')
    @include('admin.partials.buttons', [
        'title' => 'Orders', 
        'asset' => 'Order', 
        'buttons' => [
            ['export' => true]
        ]
    ])
@endsection

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="card-header bg-transparent border-bottom py-3">
            <h3 class="h5 mb-0 d-flex align-items-center">
                <input type="checkbox" id="selectAll" class="form-check-input"> 
                <label for="selectAll" class="form-check-label ms-3 fs-14">Select all items</label>
            </h3>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="ordersTable">
                <thead class="table-light">
                    <tr>
                        <th width="40" class="ps-4"></th>
                        <th>Status</th>
                        <th>Reference</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Total</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td class="ps-4">
                            <input type="checkbox" name="selected_orders[]" value="{{ $order->id }}" class="form-check-input order-checkbox">
                        </td>
                        <td>
                            <span class="badge bg-{{ $order->status == 'payment-received' ? 'success' : 'secondary' }} bg-opacity-10 text-{{ $order->status == 'payment-received' ? 'success' : 'secondary' }}">
                                {{ ucfirst(str_replace('-', ' ', $order->status)) }}
                            </span>
                        </td>
                        <td>
                            <a class="text-muted text-decoration-none text-truncate d-block" href="{{ route('admin.orders.show', $order->id) }}">
                                {{ $order->reference }}
                            </a>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div>
                                    {{ $order->customer->name ?? 'Guest' }}
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-muted fs-14">
                                {{ $order->addresses->first()->contact_email ?? '-' }}
                            </span>
                        </td>
                        <td>
                            <span class="text-muted fs-14">
                                {{ $order->addresses->first()->contact_phone ?? '-' }}
                            </span>
                        </td>
                        <td>
                            {{ $order->price_array['total']->formatted() }}
                        </td>
                        <td>
                            <span class="text-muted fs-14">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </span>
                        </td>
                        
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center py-5 text-muted">
                            <i class="bi bi-cart me-2"></i> No orders found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($orders->hasPages())
        <div class="card-footer bg-transparent border-top py-3">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all checkboxes
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.order-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
    
    // Uncheck "select all" if any order checkbox is unchecked
    const orderCheckboxes = document.querySelectorAll('.order-checkbox');
    orderCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (!this.checked) {
                document.getElementById('selectAll').checked = false;
            }
        });
    });
});
</script>
@endsection