@extends('layouts.master')
@section('title', __('messages.dashboard'))

@section('content')
    {{-- Dashboard Header --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="section-title text-start">{{ __('messages.dashboard') }}</div>
            <h1 class="display-6 fw-bold mb-3 text-start">{{ __('messages.stock_management_system') }}</h1>
            <p class="text-muted mb-4 text-start">{{ __('messages.dashboard_welcome') }}</p>
        </div>
    </div>

    {{-- Success Alert --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('messages.close') }}"></button>
        </div>
    @endif

    {{-- E-Commerce Overview --}}
    <div class="row g-4">
        {{-- Total Sales --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Total Sales</h6>
                    <h3 class="fw-bold">$24,560</h3>
                    <small class="text-success">+12% from last month</small>
                </div>
            </div>
        </div>

        {{-- Orders --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Orders</h6>
                    <h3 class="fw-bold">1,245</h3>
                    <small class="text-info">+5% from last week</small>
                </div>
            </div>
        </div>

        {{-- Products in Stock --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Products in Stock</h6>
                    <h3 class="fw-bold">3,482</h3>
                    <small class="text-danger">-2% this month</small>
                </div>
            </div>
        </div>

        {{-- Active Customers --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="text-muted">Active Customers</h6>
                    <h3 class="fw-bold">856</h3>
                    <small class="text-success">+18% from last month</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Row --}}
    <div class="row mt-5">
        {{-- Recent Orders --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header ">
                    <h5 class="fw-bold mb-0">{{ __('messages.recent_orders') }}</h5>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle rounded-3 mb-0">
                            <thead class="table-light text-uppercase small">
                                <tr>
                                    <th class="fw-bold">{{ __('messages.ref') }}</th>
                                    <th class="fw-bold">{{ __('messages.customer') }}</th>
                                    <th class="fw-bold">{{ __('messages.status') }}</th>
                                    <th class="fw-bold">{{ __('messages.total') }}</th>
                                    <th class="fw-bold">{{ __('messages.date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#ORD-1024</td>
                                    <td>John Smith</td>
                                    <td><span class="badge bg-success">Completed</span></td>
                                    <td>$250.00</td>
                                    <td>2025-11-11</td>
                                </tr>
                                <tr>
                                    <td>#ORD-1025</td>
                                    <td>Sarah Lee</td>
                                    <td><span class="badge bg-warning text-dark">Pending</span></td>
                                    <td>$180.00</td>
                                    <td>2025-11-10</td>
                                </tr>
                                <tr>
                                    <td>#ORD-1026</td>
                                    <td>David Chen</td>
                                    <td><span class="badge bg-danger">Cancelled</span></td>
                                    <td>$99.00</td>
                                    <td>2025-11-09</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        {{-- Top Selling Products --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h5 class="fw-bold mb-0">Top Selling Products</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            iPhone 15 Pro
                            <span class="badge bg-primary rounded-pill">$12,500</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Samsung Galaxy S24
                            <span class="badge bg-primary rounded-pill">$9,300</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            MacBook Air M3
                            <span class="badge bg-primary rounded-pill">$8,100</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h5 class="fw-bold mb-0">{{ __('messages.recent_activity') }}</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            New Order #1027 from John Smith
                            <span class="text-muted small">2025-11-11 10:15 AM</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Product "MacBook Air M3" stock updated
                            <span class="text-muted small">2025-11-11 09:50 AM</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            New Order #1026 from Sarah Lee
                            <span class="text-muted small">2025-11-10 08:30 PM</span>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Sales vs Buying Chart --}}
    <div class="row mt-2">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h5 class="fw-bold mb-0">Sales vs Buying Products</h5>
                </div>
                <div class="card-body">
                    <canvas id="salesBuyingChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const canvas = document.getElementById('salesBuyingChart');
            if (canvas) {
                const ctx = canvas.getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                        datasets: [
                            {
                                label: @json(__('messages.sales')) + ' ($)',
                                data: [3500, 4200, 4800, 5100, 6200, 7000],
                                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            },
                            {
                                label: @json(__('messages.purchases')) + ' ($)',
                                data: [2500, 3000, 3600, 4000, 4500, 4800],
                                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: true, position: 'bottom' }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: { display: true, text: 'Amount ($)' }
                            },
                            x: {
                                title: { display: true, text: 'Month' }
                            }
                        }
                    }
                });
            }
        });
    </script>
@endsection
