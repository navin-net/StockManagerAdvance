@extends('admin.layouts.master')
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
                    <div class="card-footer text-center">
                        <a href="{{ route('products.index') }}" class="text-decoration-none small">
                            {{ __('messages.more_info') }} <i class="bi bi-arrow-right"></i>
                        </a>
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
                    <div class="card-footer text-center">
                        <a href="{{ route('products.index') }}" class="text-decoration-none small">
                            {{ __('messages.more_info') }} <i class="bi bi-arrow-right"></i>
                        </a>
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
                    <div class="card-footer text-center">
                        <a href="{{ route('products.index') }}" class="text-decoration-none small">
                            {{ __('messages.more_info') }} <i class="bi bi-arrow-right"></i>
                        </a>
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
                    <div class="card-footer text-center">
                        <a href="{{ route('products.index') }}" class="text-decoration-none small">
                            {{ __('messages.more_info') }} <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-12 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <canvas id="brandChart" height="120"></canvas>
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
                                        <th>{{ __('messages.grand_total') }}</th>
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
        const ctx = document.getElementById('brandChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Total Products',
                    data: @json($data),
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
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
        const Stable = $('#SalesTable').DataTable({
            pageLength: 10,
            processing: true,
            serverSide: true,
            ajax: "{{ route('sales.getData') }}",
            columns: [{
                data: 'reference'
            },
            {
                data: 'customer'
            },
            {
                data: 'date'
            },
            {
                data: 'grand_total'
            },
            {
                data: 'status'
            }
            ],
            language: datatableLang,
            responsive: true
        });
    </script>
@endpush
