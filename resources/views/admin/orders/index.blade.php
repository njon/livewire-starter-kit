@extends('admin.app')

@section('toolbar')
    @include('admin.partials.buttons', [
        'title' => __('Orders'), 
        'asset' => __('Order'), 
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
                <label for="selectAll" class="form-check-label ms-3 fs-14">{{ __('Select all items') }}</label>
            </h3>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="ordersTable">
                <thead class="table-light">
                    <tr>
                        <th width="40" class="ps-4"></th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Reference') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Total') }}</th>
                        @if(auth()->user()->super_admin)
                            <th>{{ __('Owner ID') }}</th>
                            <th>{{ __('Vouchers') }}</th>
                        @endif
                        <th>{{ __('Date') }}</th>
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
                            <span class="text-muted fs-14">
                                {{ $order->addresses->first()->contact_email ?? '-' }}
                            </span>
                        </td>
                        <!-- <td>
                            <span class="text-muted fs-14">
                                {{ $order->addresses->first()->contact_phone ?? '-' }}
                            </span>
                        </td> -->
                        <td>
                            {{ format_price($order->owner_total)->formatted() }}
                        </td>
                        @if(auth()->user()->super_admin)
                            <td>
                                @foreach($order->lines->unique('owner_id') as $line)
                                    <a href="{{ route('users.edit', $line->owner_id) }}" class="text-decoration-none">
                                        {{ $line->owner_id }}
                                    </a>
                                @endforeach
                            </td>
                            <td>
                                @foreach($order->vouchers as $voucher)
                                    <div class="text-muted text-small small">{{ $voucher->code }}</div>
                                @endforeach
                            </td>
                        @endif
                        <td>
                            <span class="text-muted fs-14">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </span>
                        </td>
                        
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ auth()->user()->super_admin ? '9' : '8' }}" class="text-center py-5 text-muted">
                            <i class="bi bi-cart me-2"></i> {{ __('No orders found') }}
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