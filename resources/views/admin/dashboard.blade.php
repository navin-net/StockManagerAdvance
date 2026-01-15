@extends('layouts.master')
@section('title', __('messages.dashboard'))

@section('content')
    <div class="container-fluid py-4">
        {{-- Header --}}
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h1 class="fw-bold">{{ __('messages.stock_management_system') }}</h1>
                <p class="text-muted">{{ __('messages.dashboard_welcome') }}</p>
            </div>
            <div class="col-md-6 text-end">
                <small>{{ __('messages.ip_address') }}: {{ auth()->user()->ip_address }}</small>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif



        {{-- Stats Cards --}}
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-box-seam text-primary fs-2 me-3"></i>
                        <div>
                            <small class="text-muted">{{ __('messages.total_products') }}</small>
                            <h4 class="mb-0">{{ $productCount }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-cash-coin text-success fs-2 me-3"></i>
                        <div>
                            <small class="text-muted">{{ __('messages.total_sales') }}</small>
                            <h4 class="mb-0">${{ $saleTotal }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-bag-check text-info fs-2 me-3"></i>
                        <div>
                            <small class="text-muted">{{ __('messages.product_sold') }}</small>
                            <h4 class="mb-0">{{ $salesCount }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body d-flex align-items-center">
                        <i class="bi bi-graph-up text-warning fs-2 me-3"></i>
                        <div>
                            <small class="text-muted">{{ __('messages.purchases') }}</small>
                            <h4 class="mb-0">{{ $avg_sales }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Sales Chart</h5>
                        <canvas id="salesChart" height="150"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Purchases Chart</h5>
                        <canvas id="purchasesChart" height="150"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Sales vs Purchases</h5>
                        <canvas id="saleBuyChart" height="150"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-12 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Products Chart</h5>
                        <canvas id="productsChart" height="150"></canvas>
                    </div>
                </div>
            </div>

        </div>






        {{-- Tabs --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <ul class="nav nav-tabs justify-content-center mb-3">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#billers">
                            {{ __('messages.billers') }}
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#sales">
                            {{ __('messages.sales') }}
                        </button>
                    </li>

                </ul>

                <div class="tab-content">

                    <div class="tab-pane fade show active" id="billers">
                        <div class="table-responsive">
                            <table id="billersTable" class="table table-striped table-bordered w-100">
                                <thead>
                                    <tr>
                                        <th>{{ __('messages.name') }}</th>
                                        <th>{{ __('messages.group') }}</th>
                                        <th>{{ __('messages.warehouse') }}</th>
                                        <th>{{ __('messages.email') }}</th>
                                        <th>{{ __('messages.phone') }}</th>
                                        <th>{{ __('messages.city') }}</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="sales">
                        <div class="table-responsive">
                            <table id="SalesTable" class="table table-striped table-bordered w-100">
                                <thead>
                                    <tr>
                                        <th>{{ __('messages.reference') }}</th>
                                        <th>{{ __('messages.customer') }}</th>
                                        <th>{{ __('messages.date') }}</th>
                                        <th>{{ __('messages.total_amount') }}</th>
                                        <th>{{ __('messages.status') }}</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>


                </div>
            </div>
        </div>






    </div>
@endsection

@push('scripts')
    <script>
        const datatableLang = {
            paginate: {
                previous: '<',
                next: '>'
            },
            emptyTable: "{{ __('messages.no_data_available') }}",
            processing: "{{ __('messages.processing') }}",
            lengthMenu: '{{ __('messages.show') }} _MENU_ {{ __('messages.entries') }}',
            search: '{{ __('messages.search') }}'
        };

        /* BILLERS */
        const Btable = $('#billersTable').DataTable({
            pageLength: 10,
            processing: true,
            serverSide: true,
            ajax: "{{ route('billers.index') }}",
            columns: [{
                    data: 'name'
                },
                {
                    data: 'group_name'
                },
                {
                    data: 'warehouse_name'
                },
                {
                    data: 'email'
                },
                {
                    data: 'phone'
                },
                {
                    data: 'city'
                }
            ],
            language: datatableLang,
            responsive: true
        });

        /* SALES */
        const Stable = $('#SalesTable').DataTable({
            pageLength: 10,
            processing: true,
            serverSide: true,
            ajax: "{{ route('sales.getData') }}",
            columns: [{
                    data: 'reference'
                },
                {
                    data: 'customer_id'
                },
                {
                    data: 'date'
                },
                {
                    data: 'total_amount'
                },
                {
                    data: 'status'
                }
            ],
            language: datatableLang,
            responsive: true
        });


        // SALES CHART (static)
        const salesData = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            datasets: [{
                label: 'Total Sales ($)',
                data: [500, 700, 400, 800, 600, 900, 750],
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        };

        new Chart(document.getElementById('salesChart'), {
            type: 'line',
            data: salesData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // PRODUCTS CHART (static)
        const productsData = {
            labels: ['Electronics', 'Clothes', 'Shoes', 'Accessories', 'Books'],
            datasets: [{
                label: 'Total Products',
                data: [120, 90, 60, 30, 50],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)'
                ],
                borderColor: 'rgba(255, 255, 255, 1)',
                borderWidth: 1
            }]
        };

        new Chart(document.getElementById('productsChart'), {
            type: 'bar',
            data: productsData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const purchasesData = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            datasets: [{
                label: 'Purchases ($)',
                data: [400, 600, 500, 700, 650, 800, 780], // static purchases/buy data
                backgroundColor: 'rgba(255, 206, 86, 0.2)',
                borderColor: 'rgba(255, 206, 86, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }]
        };

        new Chart(document.getElementById('purchasesChart'), {
            type: 'line',
            data: purchasesData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true
                    },
                    title: {
                        display: true,
                        text: 'Monthly Purchases'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        const ctx = document.getElementById('saleBuyChart').getContext('2d');

        const data = {
            labels: ['Sales', 'Purchases'],
            datasets: [{
                label: 'Amount ($)',
                data: [5500, 4300], // static data: sales and purchases
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)', // Sales color
                    'rgba(255, 206, 86, 0.7)' // Purchases color
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)'
                ],
                borderWidth: 2
            }]
        };

        new Chart(ctx, {
            type: 'doughnut', // circle chart
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    title: {
                        display: true,
                        text: 'Sales vs Purchases'
                    }
                }
            }
        });
    </script>
@endpush
