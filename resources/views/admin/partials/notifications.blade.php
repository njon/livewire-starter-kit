@php
$notifications = \App\Services\NotificationService::getRecent(5);
@endphp

<div id="notifications-container">
    @forelse($notifications as $notification)
        <div class="dropdown-item {{ $notification->is_read ? '' : 'bg-light' }}" 
             data-notification-id="{{ $notification->id }}" 
             onclick="markAsRead({{ $notification->id }})">
            <div class="activity-item">
                <div class="activity-icon {{ $notification->type === 'new_order' ? 'bg-primary bg-opacity-10 text-primary' : ($notification->type === 'new_review' ? 'bg-success bg-opacity-10 text-success' : 'bg-info bg-opacity-10 text-info') }}">
                    @if($notification->type === 'new_order')
                        <i class="bi bi-cart-check text-white"></i>
                    @elseif($notification->type === 'new_review')
                        <i class="bi bi-star-fill"></i>
                    @elseif($notification->type === 'new_question')
                        <i class="bi bi-question-circle"></i>
                    @endif
                </div>
                <div class="activity-content">
                    <div class="activity-time">{{ $notification->created_at->diffForHumans() }}</div>
                    <p class="activity-text text-truncate">{{ $notification->translated_message }}</p>
                    @if(!$notification->is_read)
                        <span class="badge bg-primary badge-sm">{{ __('New') }}</span>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="dropdown-item text-center text-muted py-4">
            {{ __('No notifications') }}
        </div>
    @endforelse
</div>

<div class="dropdown-divider m-0"></div>
<div class="dropdown-footer text-center py-3 d-block">
    <a href="{{ route('admin.notifications.index') }}" class="text-primary small d-block">{{ __('View All Notifications') }}</a>
    @if($notifications->where('is_read', false)->count() > 0)
        <a href="#" onclick="markAllAsRead()" class="text-muted small d-block mt-2">{{ __('Mark All as Read') }}</a>
    @endif
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
            // Update the notification appearance
            const notificationElement = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (notificationElement) {
                notificationElement.classList.remove('bg-light');
                const badge = notificationElement.querySelector('.badge');
                if (badge) badge.remove();
            }
            updateNotificationBadge();
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
            // Update all notifications appearance
            document.querySelectorAll('#notifications-container .dropdown-item').forEach(item => {
                item.classList.remove('bg-light');
                const badge = item.querySelector('.badge');
                if (badge) badge.remove();
            });
            updateNotificationBadge();
            location.reload(); // Reload to update the dropdown footer
        }
    });
}

function updateNotificationBadge() {
    const badge = document.querySelector('.topbar-nav .bi-bell + .badge');
    if (badge) {
        fetch('/admin/notifications/recent')
            .then(response => response.json())
            .then(data => {
                if (data.unread_count === 0) {
                    badge.style.display = 'none';
                } else {
                    badge.textContent = data.unread_count;
                    badge.style.display = 'inline';
                }
            });
    }
}

// Update notification badge on page load
document.addEventListener('DOMContentLoaded', function() {
    updateNotificationBadge();
});
</script>