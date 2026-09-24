
@extends('admin.layouts.master')

@section('title', 'Daily Sales Report')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        .dsr-stat-card {
            padding: 1.1rem 1.25rem;
        }

        .dsr-stat-card .label {
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .05em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: .4rem;
        }

        .dsr-stat-card .value {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .dsr-chart-card canvas {
            max-height: 240px;
        }

        .dsr-legend-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">

        {{-- PAGE TITLE --}}
        <div class="pagetitle mb-4">
            <h1 class="display-6 fw-bold">{{ $pageTitle }}</h1>

            <nav>
                <ol class="breadcrumb rounded-3 p-2">
                    @foreach ($breadcrumbs as $breadcrumb)
                        <li class="breadcrumb-item {{ $breadcrumb['active'] ? 'active text-muted' : '' }}">
                            @if (!$breadcrumb['active'])
                                <a href="{{ $breadcrumb['url'] }}"
                                   class="text-primary text-decoration-none">
                                    {{ $breadcrumb['label'] }}
                                </a>
                            @else
                                {{ $breadcrumb['label'] }}
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>
        </div>

        {{-- FILTER BAR --}}
        <form id="dsr-filter-form"
              method="GET"
              action="{{ route('reports.daily-sales') }}"
              class="mb-4">

            <div class="row g-2 align-items-center">

                {{-- DATE PICKER: DATE ONLY --}}
                <div class="col-12 col-sm-6 col-md-auto">
                    <input
                        type="text"
                        id="dsr-date"
                        name="date"
                        class="form-control form-control-sm"
                        value="{{ $date ?? now()->format('Y-m-d') }}"
                        autocomplete="off"
                        placeholder="Select Date">
                </div>

                {{-- WAREHOUSE --}}
                <div class="col-12 col-sm-6 col-md-2">
                    <select name="warehouse_id"
                            class="form-select form-select-sm select2-basic">

                        <option value="">All Warehouses</option>

                        @foreach ($warehouses ?? [] as $wh)
                            <option value="{{ $wh->id }}"
                                @selected(request('warehouse_id') == $wh->id)>
                                {{ $wh->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- CUSTOMER --}}
                <div class="col-12 col-sm-6 col-md-2">
                    <select name="customer_id"
                            class="form-select form-select-sm select2-basic">

                        <option value="">All Customers</option>

                        @foreach ($billers ?? [] as $biller)
                            <option value="{{ $biller->id }}"
                                @selected(request('customer_id') == $biller->id)>
                                {{ $biller->name }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- SEARCH --}}
                <div class="col-12 col-sm-6 col-md-2">
                    <input
                        type="text"
                        name="search"
                        class="form-control form-control-sm"
                        placeholder="Search..."
                        value="{{ request('search') }}">
                </div>

                {{-- FILTER BUTTON --}}
                <div class="col-12 col-sm-auto">
                    <button type="submit"
                            class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-search me-1"></i>
                        Filter
                    </button>
                </div>

                {{-- EXPORT BUTTONS --}}
                <div class="col-12 col-md-auto ms-md-auto d-flex gap-2 mt-2 mt-md-0">

                    <button type="button"
                            id="dsr-export-pdf"
                            class="btn btn-outline-secondary btn-sm flex-fill flex-md-grow-0">
                        <i class="bi bi-file-earmark-pdf me-1"></i>
                        Export PDF
                    </button>

                    <button type="button"
                            id="dsr-export-excel"
                            class="btn btn-primary btn-sm flex-fill flex-md-grow-0">
                        <i class="bi bi-file-earmark-excel me-1"></i>
                        Export Excel
                    </button>

                </div>
            </div>
        </form>

        {{-- SUMMARY CARDS --}}
        <div class="row g-3 mb-4">

            {{-- TOTAL SALES --}}
            <div class="col-6 col-md-3">
                <div class="card dsr-stat-card h-100">
                    <div class="label">
                        <i class="bi bi-cash-coin me-1"></i>
                        Total Sales
                    </div>
                    <div class="value">
                        ${{ number_format($summary['total_sales'] ?? 0, 2) }}
                    </div>
                </div>
            </div>

            {{-- TOTAL PAID --}}
            <div class="col-6 col-md-3">
                <div class="card dsr-stat-card h-100">
                    <div class="label">
                        <i class="bi bi-check-circle me-1"></i>
                        Total Paid
                    </div>
                    <div class="value text-success">
                        ${{ number_format($summary['total_paid'] ?? 0, 2) }}
                    </div>
                </div>
            </div>

            {{-- TOTAL BALANCE --}}
            <div class="col-6 col-md-3">
                <div class="card dsr-stat-card h-100">
                    <div class="label">
                        <i class="bi bi-scale me-1"></i>
                        Total Balance
                    </div>

                    @php
                        $balance = $summary['total_balance'] ?? 0;
                    @endphp

                    <div class="value {{ $balance < 0 ? 'text-danger' : '' }}">
                        {{ $balance < 0 ? '-' : '' }}${{ number_format(abs($balance), 2) }}
                    </div>
                </div>
            </div>

            {{-- TOTAL ORDERS --}}
            <div class="col-6 col-md-3">
                <div class="card dsr-stat-card h-100">
                    <div class="label">
                        <i class="bi bi-receipt me-1"></i>
                        Total Orders
                    </div>
                    <div class="value">
                        {{ $summary['total_orders'] ?? 0 }}
                    </div>
                </div>
            </div>

        </div>

        {{-- CHARTS --}}
        <div class="row g-3 mb-4">

            {{-- HOURLY SALES --}}
            <div class="col-lg-8">
                <div class="card dsr-chart-card h-100 p-3">
                    <p class="label mb-2 text-muted small fw-bold text-uppercase">
                        Hourly Sales
                    </p>
                    <canvas id="dsr-hourly-chart"></canvas>
                </div>
            </div>

            {{-- PAYMENT STATUS --}}
            <div class="col-lg-4">
                <div class="card dsr-chart-card h-100 p-3">
                    <p class="label mb-2 text-muted small fw-bold text-uppercase">
                        Payment Status
                    </p>

                    <canvas id="dsr-status-chart"></canvas>

                    <div class="d-flex justify-content-center gap-3 mt-2 small">
                        <span>
                            <span class="dsr-legend-dot"
                                  style="background:#22c55e"></span>
                            Completed {{ $paymentStatus['completed'] ?? 0 }}
                        </span>

                        <span>
                            <span class="dsr-legend-dot"
                                  style="background:#f59e0b"></span>
                            Pending {{ $paymentStatus['pending'] ?? 0 }}
                        </span>
                    </div>
                </div>
            </div>

        </div>

        {{-- TRANSACTION TABLE --}}
        <div class="card p-3">
            <p class="fw-bold mb-3">Transaction Details</p>

            <div class="table-responsive">
                <table id="dsr-table"
                       class="table table-hover align-middle w-100">

                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Reference</th>
                            <th>Customer</th>
                            <th class="text-end">Grand Total</th>
                            <th class="text-end">Paid</th>
                            <th class="text-end">Balance</th>
                            <th>Payment</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($sales as $sale)

                            @php
                                $paid = $sale->payments->sum('amount');
                                $rowBalance = $sale->total_amount - $paid;
                            @endphp

                            <tr>
                                <td>
                                    {{ $sale->created_at->format('Y-m-d H:i') }}
                                </td>

                                <td>{{ $sale->reference }}</td>

                                <td>
                                    {{ $sale->customer->name ?? 'N/A' }}
                                </td>

                                <td class="text-end">
                                    ${{ number_format($sale->total_amount, 2) }}
                                </td>

                                <td class="text-end">
                                    ${{ number_format($paid, 2) }}
                                </td>

                                <td class="text-end {{ $rowBalance < 0 ? 'text-danger' : ($rowBalance > 0 ? 'text-warning' : '') }}">
                                    {{ $rowBalance < 0 ? '-' : '' }}${{ number_format(abs($rowBalance), 2) }}
                                </td>

                                <td>
                                    @if ($sale->payment_status === 'paid')
                                        <span class="badge"
                                              style="background:rgba(34,197,94,.15); color:#22c55e;">
                                            Completed
                                        </span>
                                    @else
                                        <span class="badge"
                                              style="background:rgba(245,158,11,.15); color:#f59e0b;">
                                            {{ ucfirst($sale->payment_status) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>

                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>

    </div>
@endsection

@push('scripts')

    {{-- FLATPICKR --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        $(function () {

            // =========================
            // DATE PICKER (DATE ONLY)
            // =========================
            flatpickr("#dsr-date", {
                enableTime: false,
                noCalendar: false,
                dateFormat: "Y-m-d",
                defaultDate: "{{ $date ?? now()->format('Y-m-d') }}",

                onChange: function () {
                    $('#dsr-filter-form').submit();
                }
            });


            // =========================
            // SELECT2
            // =========================
            $('.select2-basic').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });


            // =========================
            // DATATABLE
            // =========================
            $('#dsr-table').DataTable({
                paging: true,
                pageLength: 10,
                order: [[1, 'asc']],
                columnDefs: [
                    {
                        orderable: false,
                        targets: [0, 6]
                    }
                ]
            });


            // =========================
            // HOURLY SALES CHART
            // =========================
            new Chart(document.getElementById('dsr-hourly-chart'), {
                type: 'bar',

                data: {
                    labels: @json($hourly['labels'] ?? []),

                    datasets: [{
                        data: @json($hourly['values'] ?? []),
                        backgroundColor: '#0ea5e9',
                        borderRadius: 4,
                        maxBarThickness: 34
                    }]
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                    plugins: {
                        legend: {
                            display: false
                        }
                    },

                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },

                        y: {
                            grid: {
                                color: 'rgba(255,255,255,0.06)'
                            },

                            ticks: {
                                callback: v => '$' + v
                            }
                        }
                    }
                }
            });


            // =========================
            // PAYMENT STATUS PIE CHART
            // =========================
            new Chart(document.getElementById('dsr-status-chart'), {
                type: 'pie',

                data: {
                    labels: ['completed', 'pending'],

                    datasets: [{
                        data: [
                            {{ $paymentStatus['completed'] ?? 0 }},
                            {{ $paymentStatus['pending'] ?? 0 }}
                        ],

                        backgroundColor: ['#22c55e', '#f59e0b'],
                        borderWidth: 0
                    }]
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });


            // =========================
            // EXPORT EXCEL
            // =========================
            $('#dsr-export-excel').on('click', function () {

                const wb = XLSX.utils.table_to_book(
                    document.getElementById('dsr-table'),
                    {
                        sheet: "Daily Sales"
                    }
                );

                XLSX.writeFile(
                    wb,
                    `daily-sales-report-{{ $date ?? now()->format('Y-m-d') }}.xlsx`
                );
            });


            // =========================
            // EXPORT PDF
            // =========================
            $('#dsr-export-pdf').on('click', function () {

                const params = new URLSearchParams(
                    window.location.search
                );

                window.open(
                    `{{ route('reports.daily-sales.pdf') }}?${params.toString()}`,
                    '_blank'
                );
            });

        });
    </script>

@endpush
