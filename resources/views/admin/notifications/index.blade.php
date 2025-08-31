@extends('admin.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('Notifications') }}</h5>
                    @if($notifications->where('is_read', false)->count() > 0)
                        <button class="btn btn-sm btn-outline-primary" onclick="markAllAsRead()">
                            {{ __('Mark All as Read') }}
                        </button>
                    @endif
                </div>
                <div class="card-body p-0">
                    @forelse($notifications as $notification)
                        <div class="border-bottom p-3 {{ $notification->is_read ? '' : 'bg-light' }}" 
                             data-notification-id="{{ $notification->id }}">
                            <div class="d-flex align-items-start">
                                <div class="me-3">
                                    <div class="notification-icon {{ $notification->type === 'new_order' ? 'bg-primary' : ($notification->type === 'new_review' ? 'bg-success' : 'bg-info') }} bg-opacity-10 text-{{ $notification->type === 'new_order' ? 'primary' : ($notification->type === 'new_review' ? 'success' : 'info') }} rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        @if($notification->type === 'new_order')
                                            <i class="bi bi-cart-check"></i>
                                        @elseif($notification->type === 'new_review')
                                            <i class="bi bi-star-fill"></i>
                                        @elseif($notification->type === 'new_question')
                                            <i class="bi bi-question-circle"></i>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $notification->title }}</h6>
                                            <p class="mb-1 text-muted">{{ $notification->translated_message }}</p>
                                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                            @if(!$notification->is_read)
                                                <span class="badge bg-primary ms-2">{{ __('New') }}</span>
                                            @endif
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                {{ __('Actions') }}
                                            </button>
                                            <ul class="dropdown-menu">
                                                @if(!$notification->is_read)
                                                    <li><a class="dropdown-item" href="#" onclick="markAsRead({{ $notification->id }})">{{ __('Mark as Read') }}</a></li>
                                                @endif
                                                @if($notification->type === 'new_order' && isset($notification->data['order_id']))
                                                    <li><a class="dropdown-item" href="{{ route('admin.orders.show', $notification->data['order_id']) }}">{{ __('View Order') }}</a></li>
                                                @elseif($notification->type === 'new_review' && isset($notification->data['product_id']))
                                                    <li><a class="dropdown-item" href="{{ route('admin.products.edit', $notification->data['product_id']) }}">{{ __('View Product') }}</a></li>
                                                @elseif($notification->type === 'new_question' && isset($notification->data['product_id']))
                                                    <li><a class="dropdown-item" href="{{ route('admin.products.edit', $notification->data['product_id']) }}">{{ __('View Product') }}</a></li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="bi bi-bell text-muted" style="font-size: 3rem;"></i>
                            <h4 class="text-muted mt-3">{{ __('No notifications') }}</h4>
                            <p class="text-muted">{{ __('You\'ll see notifications here when orders, reviews, or questions come in.') }}</p>
                        </div>
                    @endforelse
                </div>
  
            </div>
        </div>
    </div>
</div>

<script>
function markAsRead(notificationId) {
    fetch(`/admin/notifications/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const notificationElement = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (notificationElement) {
                notificationElement.classList.remove('bg-light');
                const badge = notificationElement.querySelector('.badge');
                if (badge) badge.remove();
            }
            location.reload(); // Reload to update the page
        }
    });
}

function markAllAsRead() {
    fetch('/admin/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload(); // Reload to update the page
        }
    });
}
</script>
@endsection