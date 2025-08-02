@extends('admin.app')
@section('content')
 
 <div class="dashboard-content">
            <!-- Page Header -->
            <div class="page-header">
                <div class="page-title">
                    <h1>Dashboard</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
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
                    <div class="stat-title">Total Revenue</div>
                    <div class="stat-value">{{ $totalRevenue }}</div>
                    <div class="stat-change positive">
                        <i class="bi bi-arrow-up me-1"></i>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                </div>
                
                <div class="stat-card success">
                    <div class="stat-title">Total Orders</div>
                    <div class="stat-value">{{ $orders }}</div>
                    <div class="stat-change positive">
                        <i class="bi bi-arrow-up me-1"></i>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-cart-check"></i>
                    </div>
                </div>

                
                <div class="stat-card warning">
                    <div class="stat-title">Active Customers</div>
                    <div class="stat-value">{{ $customers }}</div>
                    <div class="stat-change negative">
                        <i class="bi bi-arrow-down me-1"></i>
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
                
                <div class="stat-card danger">
                    <div class="stat-title">Pending Orders</div>
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
                            <h3>Recent Activity</h3>
                            <a href="#" class="btn btn-sm btn-outline-secondary">View All</a>
                        </div>
                        
                        <div class="activity-item">
                            <div class="activity-icon order">
                                <i class="bi bi-cart-check"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-time">10 minutes ago</div>
                                <p class="activity-text">New order #12345 placed</p>
                            </div>
                        </div>
                        
                        <div class="activity-item">
                            <div class="activity-icon user">
                                <i class="bi bi-person-plus"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-time">1 hour ago</div>
                                <p class="activity-text">New customer registered</p>
                            </div>
                        </div>
                        
                        <div class="activity-item">
                            <div class="activity-icon store">
                                <i class="bi bi-shop"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-time">3 hours ago</div>
                                <p class="activity-text">New store location added</p>
                            </div>
                        </div>
                        
                        <div class="activity-item">
                            <div class="activity-icon message">
                                <i class="bi bi-chat-left-text"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-time">5 hours ago</div>
                                <p class="activity-text">New customer message received</p>
                            </div>
                        </div>
                        
                        <div class="activity-item">
                            <div class="activity-icon shipping">
                                <i class="bi bi-truck"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-time">Yesterday</div>
                                <p class="activity-text">Order #12340 shipped</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Best Sellers Table -->
            <div class="orders-container mt-4">
                <div class="orders-header">
                    <h3>Best Sellers </h3><span class="text-secondary">last 12 months</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <!-- <th>Identifier</th> -->
                                <th>Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bestSellers as $item)
                            @if($item)
                            <tr>
                                <td>
                                    <span class="fw-bold">{{ $item['product']->translateAttribute('name') ?? 'N/A' }}</span>
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
                    <h3>Recent Orders</h3>
                    <a href="#" class="btn btn-sm btn-primary">View All</a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
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
                label: 'Sales (€)',
                data: {!! json_encode($salesPerMonth) !!},
                fill: false,
                borderColor: 'rgba(75, 192, 192, 1)',
                tension: 0.1
            }]
        },
        options: {
            scales: {
                y: { 
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value + ' €';
                        }
                    }
                }
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
</script>
@endsection