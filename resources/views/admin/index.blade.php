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
                    <div class="stat-value">$24,780</div>
                    <div class="stat-change positive">
                        <i class="bi bi-arrow-up me-1"></i> 12.5% from last month
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-currency-dollar"></i>
                    </div>
                </div>
                
                <div class="stat-card success">
                    <div class="stat-title">Total Orders</div>
                    <div class="stat-value">1,245</div>
                    <div class="stat-change positive">
                        <i class="bi bi-arrow-up me-1"></i> 8.2% from last month
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-cart-check"></i>
                    </div>
                </div>
                
                <div class="stat-card warning">
                    <div class="stat-title">Active Customers</div>
                    <div class="stat-value">856</div>
                    <div class="stat-change negative">
                        <i class="bi bi-arrow-down me-1"></i> 2.3% from last month
                    </div>
                    <div class="stat-icon">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
                
                <div class="stat-card danger">
                    <div class="stat-title">Pending Orders</div>
                    <div class="stat-value">32</div>
                    <div class="stat-change positive">
                        <i class="bi bi-arrow-up me-1"></i> 5.1% from last month
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
                            <h3>Sales Overview</h3>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    This Month
                                </button>
                            </div>
                        </div>
                        <div class="chart-placeholder">
                            <i class="bi bi-bar-chart"></i>
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
                            <tr>
                                <td>#12345</td>
                                <td>John Smith</td>
                                <td>Jun 15, 2023</td>
                                <td>$120.00</td>
                                <td><span class="status-badge completed">Completed</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>#12344</td>
                                <td>Sarah Johnson</td>
                                <td>Jun 14, 2023</td>
                                <td>$85.50</td>
                                <td><span class="status-badge processing">Processing</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>#12343</td>
                                <td>Michael Brown</td>
                                <td>Jun 14, 2023</td>
                                <td>$210.00</td>
                                <td><span class="status-badge shipped">Shipped</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>#12342</td>
                                <td>Emily Davis</td>
                                <td>Jun 13, 2023</td>
                                <td>$65.75</td>
                                <td><span class="status-badge cancelled">Cancelled</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>#12341</td>
                                <td>Robert Wilson</td>
                                <td>Jun 12, 2023</td>
                                <td>$149.99</td>
                                <td><span class="status-badge completed">Completed</span></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Footer -->
            <footer class="footer mt-4">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="text-center text-md-start">
                                &copy; 2023 LunarPHP. All rights reserved.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center text-md-end">
                                <a href="#" class="text-decoration-none me-3">Privacy Policy</a>
                                <a href="#" class="text-decoration-none">Terms of Service</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>

        @endsection