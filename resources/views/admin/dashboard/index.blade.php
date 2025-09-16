@extends('admin.app')
@section('content')
 
 <div class="dashboard-content">
            <!-- Page Header -->
            <div class="page-header">
                <div class="page-title">
                    <h1>{{ __('Dashboard') }}</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">{{ __('Home') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Dashboard') }}</li>
                        </ol>
                    </nav>
                </div>
                <button class="btn btn-primary">
                    <i class="bi bi-download me-2"></i> Generate Report
                </button>
            </div>
            
            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card primary">
                    <div class="stat-title">{{ __('Total Revenue') }}</div>
                    <div class="stat-value">{{ $totalRevenue }}</div>
                    <div class="stat-change positive">
                        <i class="bi bi-arrow-up me-1"></i>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                </div>
                
                <div class="stat-card success">
                    <div class="stat-title">{{ __('Total Orders') }}</div>
                    <div class="stat-value">{{ $ordersCount }}</div>
                    <div class="stat-change positive">
                        <i class="bi bi-arrow-up me-1"></i>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-cart-check"></i>
                    </div>
                </div>

                
                <div class="stat-card warning">
                    <div class="stat-title">{{ __('Active Customers') }}</div>
                    <div class="stat-value">{{ $customers }}</div>
                    <div class="stat-change negative">
                        <i class="bi bi-arrow-down me-1"></i>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
                
                <div class="stat-card danger">
                    <div class="stat-title">{{ __('Pending Orders') }}</div>
                    <div class="stat-value">{{ $pendingOrders }}</div>
                    <div class="stat-change positive">
                        <i class="bi bi-arrow-up me-1"></i>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
            </div>
            
            <!-- Charts Row -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="chart-container">
                        <div class="chart-header">
                            <h3>Sales Overview (Yearly)</h3>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Year: {{ $salesYear ?? date('Y') }}
                                </button>
                            </div>
                        </div>
                        <div class="chart-placeholder">
                            <canvas id="salesYearChart" height="120"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="activity-container">
                        <div class="chart-header mb-3">
                            <h3>{{ __('Recent Activity') }}</h3>
                            <a href="{{ route('admin.notifications.index') }}" class="btn btn-sm btn-outline-secondary">{{ __('View All') }}</a>
                        </div>
                        @include('admin.partials.notifications')
                    </div>
                </div>
            </div>

            <!-- Best Sellers Table -->
            <div class="orders-container mt-4">
                <div class="orders-header">
                    <h3>{{ __('Best Sellers') }} </h3><span class="text-secondary">{{ __('last 12 months') }}</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('Service') }}</th>
                                <!-- <th>Identifier</th> -->
                                <th>{{ __('Sales') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bestSellers as $item)
                            @if($item)
                            <tr>
                                <td>
                                    <span class="fw-bold"><a href="{{ $item['product_link'] }}">{{ $item['product']->translateAttribute('name') ?? 'N/A' }}</a></span>
                                    <span class="text-muted">Variant:</span>
                                    <span class="fst-italic text-secondary small">{{ $item['variant']->translateAttribute('name') ?? 'N/A' }}</span>
                                </td>
                                <!-- <td>{{ $item['variant']->name ?? 'N/A' }}</td> -->
                                <td>{{ $item['quantity'] }}</td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="orders-container mt-4">
                <div class="orders-header">
                    <h3>{{ __('Recent Orders') }}</h3>
                    <a href="#" class="btn btn-sm btn-primary">{{ __('View All') }}</a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('Order ID') }}</th>
                                <th>{{ __('Customer') }}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($latestOrders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->customer->name ?? 'N/A' }}</td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>{{ $order->total->formatted() }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status == 'payment-received' ? 'success' : 'secondary' }} bg-opacity-10 text-{{ $order->status == 'payment-received' ? 'success' : 'secondary' }}">
                                        {{ ucfirst(str_replace('-', ' ', $order->status)) }}
                                    </span>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-secondary update-status-btn" data-order-id="{{ $order->id }}" data-current-status="{{ $order->status }}">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>


document.addEventListener('DOMContentLoaded', function() {
    // Example 1: Bar chart (default)
    var ctx = document.getElementById('salesYearChart').getContext('2d');
    
    // Example 2: Line chart
    // Uncomment below to use a line chart instead
var lineChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($salesMonths) !!},
        datasets: [{
            label: 'Monthly Sales',
            data: {!! json_encode($salesPerMonth) !!},
            backgroundColor: 'rgba(99, 102, 241, 0.1)',
            borderColor: 'rgba(99, 102, 241, 1)',
            borderWidth: 2,
            pointBackgroundColor: 'rgba(99, 102, 241, 1)',
            pointBorderColor: '#fff',
            // pointHoverRadius: 6,
            // pointHoverBorderWidth: 2,
            // pointRadius: 4,
            // tension: 0.3,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    color: '#6b7280',
                    font: {
                        size: 13,
                        family: "'Inter', sans-serif",
                        weight: 500
                    },
                    padding: 20,
                    usePointStyle: true,
                    pointStyle: 'circle'
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                titleFont: {
                    size: 14,
                    weight: 'bold',
                    family: "'Inter', sans-serif"
                },
                bodyFont: {
                    size: 13,
                    family: "'Inter', sans-serif"
                },
                padding: 12,
                usePointStyle: true,
                callbacks: {
                    label: function(context) {
                        return ' ' + context.parsed.y.toLocaleString() + ' €';
                    }
                }
            }
        },
        scales: {
            x: {
                grid: {
                    display: false,
                    drawBorder: false
                },
                ticks: {
                    color: '#9ca3af',
                    font: {
                        size: 12,
                        family: "'Inter', sans-serif"
                    }
                }
            },
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(229, 231, 235, 0.5)',
                    drawBorder: true
                },
                ticks: {
                    color: '#9ca3af',
                    font: {
                        size: 12,
                        family: "'Inter', sans-serif"
                    },
                    callback: function(value) {
                        return value.toLocaleString() + ' €';
                    },
                    padding: 0,
                    // stepSize: 1000, 
                    maxTicksLimit: 10, 
                }
            }
        },
        elements: {
            line: {
                borderJoinStyle: 'round'
            }
        },
        interaction: {
            intersect: false,
            mode: 'index'
        }
    }
});

    // Example 3: Pie chart
    // Uncomment below to use a pie chart (for proportions)
    /*
    var pieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($salesMonths) !!},
            datasets: [{
                label: 'Sales',
                data: {!! json_encode($salesPerMonth) !!},
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                    'rgba(255, 159, 64, 0.6)'
                ]
            }]
        }
    });
    */
});

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
            
            // Find the status badge element in the same row
            const row = this.closest('tr');
            currentStatusElement = row.querySelector('.badge');
            
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
        fetch(`/order/${currentOrderId}/status`, {
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
@endsection