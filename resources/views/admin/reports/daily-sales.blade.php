{{--
    resources/views/admin/reports/daily_sales.blade.php

    Built against your real App\Models\Sale and App\Models\Payment.

    NOTES / remaining assumptions:
    - "Biller" column = $sale->customer (Sale::customer() belongsTo Companies, keyed by customer_id) —
      this is the relation that holds values like "Company Biller" / "Walk in Customer" in your screenshot.
      Assumed Companies has a `name` column.
    - "Paid" = sum of that sale's Payment rows (`amount`). "Balance" = total_amount - paid.
      (Payment also has pos_paid/pos_balance snapshot columns — if those should be used
      instead of a live sum, say so and I'll switch it.)
    - Warehouse filter assumes an App\Models\Warehouse with a `name` column (Sale has warehouse_id
      but no warehouse() relation yet — add one, or I can add it for you).
    - "Payment status" pie assumes Sale::payment_status uses values like 'paid' / 'pending'.
    - Controller sends: $sales, $summary (total_sales, total_paid, total_balance, total_orders),
      $hourly (labels[], values[]), $paymentStatus (completed, pending), $warehouses, $billers, $date
--}}
@extends('admin.layouts.master')

@section('title', 'Daily Sales Report')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .dsr-stat-card { padding: 1.1rem 1.25rem; }
        .dsr-stat-card .label { font-size: .72rem; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: var(--text-muted); margin-bottom: .4rem; }
        .dsr-stat-card .value { font-size: 1.5rem; font-weight: 700; }
        .dsr-chart-card canvas { max-height: 240px; }
        .dsr-legend-dot { width: 9px; height: 9px; border-radius: 50%; display: inline-block; margin-right: 6px; }
    </style>
@endpush

@section('content')
    <div class="container-fluid">

        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">Reports</a></li>
                <li class="breadcrumb-item active" aria-current="page">Daily Sales</li>
            </ol>
        </nav>

        <h4 class="fw-bold mb-3">Daily Sales Report</h4>

        {{-- ===================== FILTER BAR ===================== --}}
        <form id="dsr-filter-form" method="GET" action="{{ route('admin.reports.daily-sales') }}"
              class="d-flex flex-wrap align-items-center gap-2 mb-4">
            <input type="text" id="dsr-date" name="date" class="form-control" style="width:150px"
                   value="{{ $date ?? now()->format('Y-m-d') }}" autocomplete="off">

            <select name="warehouse_id" class="form-select select2-basic" style="width:160px">
                <option value="">All Warehouses</option>
                @foreach($warehouses ?? [] as $wh)
                    <option value="{{ $wh->id }}" @selected(request('warehouse_id') == $wh->id)>{{ $wh->name }}</option>
                @endforeach
            </select>

            <select name="customer_id" class="form-select select2-basic" style="width:160px">
                <option value="">All Billers</option>
                @foreach($billers ?? [] as $biller)
                    <option value="{{ $biller->id }}" @selected(request('customer_id') == $biller->id)>{{ $biller->name }}</option>
                @endforeach
            </select>

            <input type="text" name="search" class="form-control" placeholder="Search..." style="width:180px"
                   value="{{ request('search') }}">

            <button type="submit" class="btn btn-primary btn-sm">
                <i class="bi bi-search me-1"></i>Filter
            </button>

            <div class="ms-auto d-flex gap-2">
                <button type="button" id="dsr-export-pdf" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-file-earmark-pdf me-1"></i>Export PDF
                </button>
                <button type="button" id="dsr-export-excel" class="btn btn-primary btn-sm">
                    <i class="bi bi-file-earmark-excel me-1"></i>Export Excel
                </button>
            </div>
        </form>

        {{-- ===================== SUMMARY CARDS ===================== --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card dsr-stat-card h-100">
                    <div class="label"><i class="bi bi-cash-coin me-1"></i>Total Sales</div>
                    <div class="value">${{ number_format($summary['total_sales'] ?? 0, 2) }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card dsr-stat-card h-100">
                    <div class="label"><i class="bi bi-check-circle me-1"></i>Total Paid</div>
                    <div class="value text-success">${{ number_format($summary['total_paid'] ?? 0, 2) }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card dsr-stat-card h-100">
                    <div class="label"><i class="bi bi-scale me-1"></i>Total Balance</div>
                    @php $balance = $summary['total_balance'] ?? 0; @endphp
                    <div class="value {{ $balance < 0 ? 'text-danger' : '' }}">
                        {{ $balance < 0 ? '-' : '' }}${{ number_format(abs($balance), 2) }}
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card dsr-stat-card h-100">
                    <div class="label"><i class="bi bi-receipt me-1"></i>Total Orders</div>
                    <div class="value">{{ $summary['total_orders'] ?? 0 }}</div>
                </div>
            </div>
        </div>

        {{-- ===================== CHARTS ===================== --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-8">
                <div class="card dsr-chart-card h-100 p-3">
                    <p class="label mb-2 text-muted small fw-bold text-uppercase">Hourly Sales</p>
                    <canvas id="dsr-hourly-chart"></canvas>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card dsr-chart-card h-100 p-3">
                    <p class="label mb-2 text-muted small fw-bold text-uppercase">Payment Status</p>
                    <canvas id="dsr-status-chart"></canvas>
                    <div class="d-flex justify-content-center gap-3 mt-2 small">
                        <span><span class="dsr-legend-dot" style="background:#22c55e"></span>Completed {{ $paymentStatus['completed'] ?? 0 }}</span>
                        <span><span class="dsr-legend-dot" style="background:#f59e0b"></span>Pending {{ $paymentStatus['pending'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===================== TRANSACTION TABLE ===================== --}}
        <div class="card p-3">
            <p class="fw-bold mb-3">Transaction Details</p>
            <div class="table-responsive">
                <table id="dsr-table" class="table table-hover align-middle w-100">
                    <thead>
                    <tr>
                        <th><input type="checkbox" id="dsr-select-all"></th>
                        <th>Time</th>
                        <th>Reference</th>
                        <th>Biller</th>
                        <th class="text-end">Grand Total</th>
                        <th class="text-end">Paid</th>
                        <th class="text-end">Balance</th>
                        <th>Payment</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($sales as $sale)
                        @php
                            $paid = $sale->payments->sum('amount');
                            $rowBalance = $sale->total_amount - $paid;
                        @endphp
                        <tr>
                            <td><input type="checkbox" class="dsr-row-check"></td>
                            <td>    {{ $sale->created_at ? $sale->created_at->format('d/m/Y H:i:s') : '-' }}</td>
                            <td>{{ $sale->reference }}</td>
                            <td>{{ $sale->customer->name ?? 'N/A' }}</td>
                            <td class="text-end">${{ number_format($sale->total_amount, 2) }}</td>
                            <td class="text-end">${{ number_format($paid, 2) }}</td>
                            <td class="text-end {{ $rowBalance < 0 ? 'text-danger' : ($rowBalance > 0 ? 'text-warning' : '') }}">
                                {{ $rowBalance < 0 ? '-' : '' }}${{ number_format(abs($rowBalance), 2) }}
                            </td>
                            <td>
                                @if($sale->payment_status === 'paid')
                                    <span class="badge" style="background:rgba(34,197,94,.15); color:#22c55e;">Completed</span>
                                @else
                                    <span class="badge" style="background:rgba(245,158,11,.15); color:#f59e0b;">{{ ucfirst($sale->payment_status) }}</span>
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
    <script>
        $(function () {
            // ----- Date picker -----
            flatpickr("#dsr-date", {
                dateFormat: "Y-m-d",
                defaultDate: "{{ $date ?? now()->format('Y-m-d') }}",
                onChange: function () {
                    $('#dsr-filter-form').submit();
                }
            });

            // ----- Select2 -----
            $('.select2-basic').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });

            // ----- DataTable (paging/search only; totals come from the summary cards) -----
            $('#dsr-table').DataTable({
                paging: true,
                pageLength: 10,
                order: [[1, 'asc']],
                columnDefs: [{ orderable: false, targets: [0, 7] }]
            });

            $('#dsr-select-all').on('change', function () {
                $('.dsr-row-check').prop('checked', $(this).is(':checked'));
            });

            // ----- Hourly bar chart -----
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
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false } },
                        y: { grid: { color: 'rgba(255,255,255,0.06)' }, ticks: { callback: v => '$' + v } }
                    }
                }
            });

            // ----- Payment status pie chart -----
            new Chart(document.getElementById('dsr-status-chart'), {
                type: 'pie',
                data: {
                    labels: ['Completed', 'Pending'],
                    datasets: [{
                        data: [{{ $paymentStatus['completed'] ?? 0 }}, {{ $paymentStatus['pending'] ?? 0 }}],
                        backgroundColor: ['#22c55e', '#f59e0b'],
                        borderWidth: 0
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
            });

            // ----- Export to Excel (SheetJS, already loaded in master layout) -----
            $('#dsr-export-excel').on('click', function () {
                const wb = XLSX.utils.table_to_book(document.getElementById('dsr-table'), { sheet: "Daily Sales" });
                XLSX.writeFile(wb, `daily-sales-report-{{ $date ?? now()->format('Y-m-d') }}.xlsx`);
            });

            // ----- Export to PDF -----
            // Requires a PDF route (see controller snippet). Opens the printable PDF in a new tab.
            $('#dsr-export-pdf').on('click', function () {
                const params = new URLSearchParams(window.location.search);
                window.open(`{{ route('admin.reports.daily-sales.pdf') }}?${params.toString()}`, '_blank');
            });
        });
    </script>
@endpush
{{--
    resources/views/admin/reports/daily_sales.blade.php

    Built against your real App\Models\Sale and App\Models\Payment.

    NOTES / remaining assumptions:
    - "Biller" column = $sale->customer (Sale::customer() belongsTo Companies, keyed by customer_id) —
      this is the relation that holds values like "Company Biller" / "Walk in Customer" in your screenshot.
      Assumed Companies has a `name` column.
    - "Paid" = sum of that sale's Payment rows (`amount`). "Balance" = total_amount - paid.
      (Payment also has pos_paid/pos_balance snapshot columns — if those should be used
      instead of a live sum, say so and I'll switch it.)
    - Warehouse filter assumes an App\Models\Warehouse with a `name` column (Sale has warehouse_id
      but no warehouse() relation yet — add one, or I can add it for you).
    - "Payment status" pie assumes Sale::payment_status uses values like 'paid' / 'pending'.
    - Controller sends: $sales, $summary (total_sales, total_paid, total_balance, total_orders),
      $hourly (labels[], values[]), $paymentStatus (completed, pending), $warehouses, $billers, $date
--}}
@extends('admin.layouts.master')

@section('title', 'Daily Sales Report')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .dsr-stat-card { padding: 1.1rem 1.25rem; }
        .dsr-stat-card .label { font-size: .72rem; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: var(--text-muted); margin-bottom: .4rem; }
        .dsr-stat-card .value { font-size: 1.5rem; font-weight: 700; }
        .dsr-chart-card canvas { max-height: 240px; }
        .dsr-legend-dot { width: 9px; height: 9px; border-radius: 50%; display: inline-block; margin-right: 6px; }
    </style>
@endpush

@section('content')
    <div class="container-fluid">

        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">Reports</a></li>
                <li class="breadcrumb-item active" aria-current="page">Daily Sales</li>
            </ol>
        </nav>

        <h4 class="fw-bold mb-3">Daily Sales Report</h4>

        {{-- ===================== FILTER BAR ===================== --}}
        <form id="dsr-filter-form" method="GET" action="{{ route('admin.reports.daily-sales') }}"
              class="d-flex flex-wrap align-items-center gap-2 mb-4">
            <input type="text" id="dsr-date" name="date" class="form-control" style="width:150px"
                   value="{{ $date ?? now()->format('Y-m-d') }}" autocomplete="off">

            <select name="warehouse_id" class="form-select select2-basic" style="width:160px">
                <option value="">All Warehouses</option>
                @foreach($warehouses ?? [] as $wh)
                    <option value="{{ $wh->id }}" @selected(request('warehouse_id') == $wh->id)>{{ $wh->name }}</option>
                @endforeach
            </select>

            <select name="customer_id" class="form-select select2-basic" style="width:160px">
                <option value="">All Billers</option>
                @foreach($billers ?? [] as $biller)
                    <option value="{{ $biller->id }}" @selected(request('customer_id') == $biller->id)>{{ $biller->name }}</option>
                @endforeach
            </select>

            <input type="text" name="search" class="form-control" placeholder="Search..." style="width:180px"
                   value="{{ request('search') }}">

            <button type="submit" class="btn btn-primary btn-sm">
                <i class="bi bi-search me-1"></i>Filter
            </button>

            <div class="ms-auto d-flex gap-2">
                <button type="button" id="dsr-export-pdf" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-file-earmark-pdf me-1"></i>Export PDF
                </button>
                <button type="button" id="dsr-export-excel" class="btn btn-primary btn-sm">
                    <i class="bi bi-file-earmark-excel me-1"></i>Export Excel
                </button>
            </div>
        </form>

        {{-- ===================== SUMMARY CARDS ===================== --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card dsr-stat-card h-100">
                    <div class="label"><i class="bi bi-cash-coin me-1"></i>Total Sales</div>
                    <div class="value">${{ number_format($summary['total_sales'] ?? 0, 2) }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card dsr-stat-card h-100">
                    <div class="label"><i class="bi bi-check-circle me-1"></i>Total Paid</div>
                    <div class="value text-success">${{ number_format($summary['total_paid'] ?? 0, 2) }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card dsr-stat-card h-100">
                    <div class="label"><i class="bi bi-scale me-1"></i>Total Balance</div>
                    @php $balance = $summary['total_balance'] ?? 0; @endphp
                    <div class="value {{ $balance < 0 ? 'text-danger' : '' }}">
                        {{ $balance < 0 ? '-' : '' }}${{ number_format(abs($balance), 2) }}
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card dsr-stat-card h-100">
                    <div class="label"><i class="bi bi-receipt me-1"></i>Total Orders</div>
                    <div class="value">{{ $summary['total_orders'] ?? 0 }}</div>
                </div>
            </div>
        </div>

        {{-- ===================== CHARTS ===================== --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-8">
                <div class="card dsr-chart-card h-100 p-3">
                    <p class="label mb-2 text-muted small fw-bold text-uppercase">Hourly Sales</p>
                    <canvas id="dsr-hourly-chart"></canvas>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card dsr-chart-card h-100 p-3">
                    <p class="label mb-2 text-muted small fw-bold text-uppercase">Payment Status</p>
                    <canvas id="dsr-status-chart"></canvas>
                    <div class="d-flex justify-content-center gap-3 mt-2 small">
                        <span><span class="dsr-legend-dot" style="background:#22c55e"></span>Completed {{ $paymentStatus['completed'] ?? 0 }}</span>
                        <span><span class="dsr-legend-dot" style="background:#f59e0b"></span>Pending {{ $paymentStatus['pending'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===================== TRANSACTION TABLE ===================== --}}
        <div class="card p-3">
            <p class="fw-bold mb-3">Transaction Details</p>
            <div class="table-responsive">
                <table id="dsr-table" class="table table-hover align-middle w-100">
                    <thead>
                    <tr>
                        <th><input type="checkbox" id="dsr-select-all"></th>
                        <th>Time</th>
                        <th>Reference</th>
                        <th>Biller</th>
                        <th class="text-end">Grand Total</th>
                        <th class="text-end">Paid</th>
                        <th class="text-end">Balance</th>
                        <th>Payment</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($sales as $sale)
                        @php
                            $paid = $sale->payments->sum('amount');
                            $rowBalance = $sale->total_amount - $paid;
                        @endphp
                        <tr>
                            <td><input type="checkbox" class="dsr-row-check"></td>
                            <td>    {{ $sale->created_at ? $sale->created_at->format('d/m/Y H:i:s') : '-' }}</td>
                            <td>{{ $sale->reference }}</td>
                            <td>{{ $sale->customer->name ?? 'N/A' }}</td>
                            <td class="text-end">${{ number_format($sale->total_amount, 2) }}</td>
                            <td class="text-end">${{ number_format($paid, 2) }}</td>
                            <td class="text-end {{ $rowBalance < 0 ? 'text-danger' : ($rowBalance > 0 ? 'text-warning' : '') }}">
                                {{ $rowBalance < 0 ? '-' : '' }}${{ number_format(abs($rowBalance), 2) }}
                            </td>
                            <td>
                                @if($sale->payment_status === 'paid')
                                    <span class="badge" style="background:rgba(34,197,94,.15); color:#22c55e;">Completed</span>
                                @else
                                    <span class="badge" style="background:rgba(245,158,11,.15); color:#f59e0b;">{{ ucfirst($sale->payment_status) }}</span>
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
    <script>
        $(function () {
            // ----- Date picker -----
            flatpickr("#dsr-date", {
                dateFormat: "Y-m-d",
                defaultDate: "{{ $date ?? now()->format('Y-m-d') }}",
                onChange: function () {
                    $('#dsr-filter-form').submit();
                }
            });

            // ----- Select2 -----
            $('.select2-basic').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });

            // ----- DataTable (paging/search only; totals come from the summary cards) -----
            $('#dsr-table').DataTable({
                paging: true,
                pageLength: 10,
                order: [[1, 'asc']],
                columnDefs: [{ orderable: false, targets: [0, 7] }]
            });

            $('#dsr-select-all').on('change', function () {
                $('.dsr-row-check').prop('checked', $(this).is(':checked'));
            });

            // ----- Hourly bar chart -----
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
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false } },
                        y: { grid: { color: 'rgba(255,255,255,0.06)' }, ticks: { callback: v => '$' + v } }
                    }
                }
            });

            // ----- Payment status pie chart -----
            new Chart(document.getElementById('dsr-status-chart'), {
                type: 'pie',
                data: {
                    labels: ['Completed', 'Pending'],
                    datasets: [{
                        data: [{{ $paymentStatus['completed'] ?? 0 }}, {{ $paymentStatus['pending'] ?? 0 }}],
                        backgroundColor: ['#22c55e', '#f59e0b'],
                        borderWidth: 0
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
            });

            // ----- Export to Excel (SheetJS, already loaded in master layout) -----
            $('#dsr-export-excel').on('click', function () {
                const wb = XLSX.utils.table_to_book(document.getElementById('dsr-table'), { sheet: "Daily Sales" });
                XLSX.writeFile(wb, `daily-sales-report-{{ $date ?? now()->format('Y-m-d') }}.xlsx`);
            });

            // ----- Export to PDF -----
            // Requires a PDF route (see controller snippet). Opens the printable PDF in a new tab.
            $('#dsr-export-pdf').on('click', function () {
                const params = new URLSearchParams(window.location.search);
                window.open(`{{ route('admin.reports.daily-sales.pdf') }}?${params.toString()}`, '_blank');
            });
        });
    </script>
@endpush
{{--
    resources/views/admin/reports/daily_sales.blade.php

    Built against your real App\Models\Sale and App\Models\Payment.

    NOTES / remaining assumptions:
    - "Biller" column = $sale->customer (Sale::customer() belongsTo Companies, keyed by customer_id) —
      this is the relation that holds values like "Company Biller" / "Walk in Customer" in your screenshot.
      Assumed Companies has a `name` column.
    - "Paid" = sum of that sale's Payment rows (`amount`). "Balance" = total_amount - paid.
      (Payment also has pos_paid/pos_balance snapshot columns — if those should be used
      instead of a live sum, say so and I'll switch it.)
    - Warehouse filter assumes an App\Models\Warehouse with a `name` column (Sale has warehouse_id
      but no warehouse() relation yet — add one, or I can add it for you).
    - "Payment status" pie assumes Sale::payment_status uses values like 'paid' / 'pending'.
    - Controller sends: $sales, $summary (total_sales, total_paid, total_balance, total_orders),
      $hourly (labels[], values[]), $paymentStatus (completed, pending), $warehouses, $billers, $date
--}}
@extends('admin.layouts.master')

@section('title', 'Daily Sales Report')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .dsr-stat-card { padding: 1.1rem 1.25rem; }
        .dsr-stat-card .label { font-size: .72rem; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: var(--text-muted); margin-bottom: .4rem; }
        .dsr-stat-card .value { font-size: 1.5rem; font-weight: 700; }
        .dsr-chart-card canvas { max-height: 240px; }
        .dsr-legend-dot { width: 9px; height: 9px; border-radius: 50%; display: inline-block; margin-right: 6px; }
    </style>
@endpush

@section('content')
    <div class="container-fluid">

        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">Reports</a></li>
                <li class="breadcrumb-item active" aria-current="page">Daily Sales</li>
            </ol>
        </nav>

        <h4 class="fw-bold mb-3">Daily Sales Report</h4>

        {{-- ===================== FILTER BAR ===================== --}}
        <form id="dsr-filter-form" method="GET" action="{{ route('admin.reports.daily-sales') }}"
              class="d-flex flex-wrap align-items-center gap-2 mb-4">
            <input type="text" id="dsr-date" name="date" class="form-control" style="width:150px"
                   value="{{ $date ?? now()->format('Y-m-d') }}" autocomplete="off">

            <select name="warehouse_id" class="form-select select2-basic" style="width:160px">
                <option value="">All Warehouses</option>
                @foreach($warehouses ?? [] as $wh)
                    <option value="{{ $wh->id }}" @selected(request('warehouse_id') == $wh->id)>{{ $wh->name }}</option>
                @endforeach
            </select>

            <select name="customer_id" class="form-select select2-basic" style="width:160px">
                <option value="">All Billers</option>
                @foreach($billers ?? [] as $biller)
                    <option value="{{ $biller->id }}" @selected(request('customer_id') == $biller->id)>{{ $biller->name }}</option>
                @endforeach
            </select>

            <input type="text" name="search" class="form-control" placeholder="Search..." style="width:180px"
                   value="{{ request('search') }}">

            <button type="submit" class="btn btn-primary btn-sm">
                <i class="bi bi-search me-1"></i>Filter
            </button>

            <div class="ms-auto d-flex gap-2">
                <button type="button" id="dsr-export-pdf" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-file-earmark-pdf me-1"></i>Export PDF
                </button>
                <button type="button" id="dsr-export-excel" class="btn btn-primary btn-sm">
                    <i class="bi bi-file-earmark-excel me-1"></i>Export Excel
                </button>
            </div>
        </form>

        {{-- ===================== SUMMARY CARDS ===================== --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card dsr-stat-card h-100">
                    <div class="label"><i class="bi bi-cash-coin me-1"></i>Total Sales</div>
                    <div class="value">${{ number_format($summary['total_sales'] ?? 0, 2) }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card dsr-stat-card h-100">
                    <div class="label"><i class="bi bi-check-circle me-1"></i>Total Paid</div>
                    <div class="value text-success">${{ number_format($summary['total_paid'] ?? 0, 2) }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card dsr-stat-card h-100">
                    <div class="label"><i class="bi bi-scale me-1"></i>Total Balance</div>
                    @php $balance = $summary['total_balance'] ?? 0; @endphp
                    <div class="value {{ $balance < 0 ? 'text-danger' : '' }}">
                        {{ $balance < 0 ? '-' : '' }}${{ number_format(abs($balance), 2) }}
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card dsr-stat-card h-100">
                    <div class="label"><i class="bi bi-receipt me-1"></i>Total Orders</div>
                    <div class="value">{{ $summary['total_orders'] ?? 0 }}</div>
                </div>
            </div>
        </div>

        {{-- ===================== CHARTS ===================== --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-8">
                <div class="card dsr-chart-card h-100 p-3">
                    <p class="label mb-2 text-muted small fw-bold text-uppercase">Hourly Sales</p>
                    <canvas id="dsr-hourly-chart"></canvas>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card dsr-chart-card h-100 p-3">
                    <p class="label mb-2 text-muted small fw-bold text-uppercase">Payment Status</p>
                    <canvas id="dsr-status-chart"></canvas>
                    <div class="d-flex justify-content-center gap-3 mt-2 small">
                        <span><span class="dsr-legend-dot" style="background:#22c55e"></span>Completed {{ $paymentStatus['completed'] ?? 0 }}</span>
                        <span><span class="dsr-legend-dot" style="background:#f59e0b"></span>Pending {{ $paymentStatus['pending'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===================== TRANSACTION TABLE ===================== --}}
        <div class="card p-3">
            <p class="fw-bold mb-3">Transaction Details</p>
            <div class="table-responsive">
                <table id="dsr-table" class="table table-hover align-middle w-100">
                    <thead>
                    <tr>
                        <th><input type="checkbox" id="dsr-select-all"></th>
                        <th>Time</th>
                        <th>Reference</th>
                        <th>Biller</th>
                        <th class="text-end">Grand Total</th>
                        <th class="text-end">Paid</th>
                        <th class="text-end">Balance</th>
                        <th>Payment</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($sales as $sale)
                        @php
                            $paid = $sale->payments->sum('amount');
                            $rowBalance = $sale->total_amount - $paid;
                        @endphp
                        <tr>
                            <td><input type="checkbox" class="dsr-row-check"></td>
                            <td>    {{ $sale->created_at ? $sale->created_at->format('d/m/Y H:i:s') : '-' }}</td>
                            <td>{{ $sale->reference }}</td>
                            <td>{{ $sale->customer->name ?? 'N/A' }}</td>
                            <td class="text-end">${{ number_format($sale->total_amount, 2) }}</td>
                            <td class="text-end">${{ number_format($paid, 2) }}</td>
                            <td class="text-end {{ $rowBalance < 0 ? 'text-danger' : ($rowBalance > 0 ? 'text-warning' : '') }}">
                                {{ $rowBalance < 0 ? '-' : '' }}${{ number_format(abs($rowBalance), 2) }}
                            </td>
                            <td>
                                @if($sale->payment_status === 'paid')
                                    <span class="badge" style="background:rgba(34,197,94,.15); color:#22c55e;">Completed</span>
                                @else
                                    <span class="badge" style="background:rgba(245,158,11,.15); color:#f59e0b;">{{ ucfirst($sale->payment_status) }}</span>
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
    <script>
        $(function () {
            // ----- Date picker -----
            flatpickr("#dsr-date", {
                dateFormat: "Y-m-d",
                defaultDate: "{{ $date ?? now()->format('Y-m-d') }}",
                onChange: function () {
                    $('#dsr-filter-form').submit();
                }
            });

            // ----- Select2 -----
            $('.select2-basic').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });

            // ----- DataTable (paging/search only; totals come from the summary cards) -----
            $('#dsr-table').DataTable({
                paging: true,
                pageLength: 10,
                order: [[1, 'asc']],
                columnDefs: [{ orderable: false, targets: [0, 7] }]
            });

            $('#dsr-select-all').on('change', function () {
                $('.dsr-row-check').prop('checked', $(this).is(':checked'));
            });

            // ----- Hourly bar chart -----
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
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false } },
                        y: { grid: { color: 'rgba(255,255,255,0.06)' }, ticks: { callback: v => '$' + v } }
                    }
                }
            });

            // ----- Payment status pie chart -----
            new Chart(document.getElementById('dsr-status-chart'), {
                type: 'pie',
                data: {
                    labels: ['Completed', 'Pending'],
                    datasets: [{
                        data: [{{ $paymentStatus['completed'] ?? 0 }}, {{ $paymentStatus['pending'] ?? 0 }}],
                        backgroundColor: ['#22c55e', '#f59e0b'],
                        borderWidth: 0
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
            });

            // ----- Export to Excel (SheetJS, already loaded in master layout) -----
            $('#dsr-export-excel').on('click', function () {
                const wb = XLSX.utils.table_to_book(document.getElementById('dsr-table'), { sheet: "Daily Sales" });
                XLSX.writeFile(wb, `daily-sales-report-{{ $date ?? now()->format('Y-m-d') }}.xlsx`);
            });

            // ----- Export to PDF -----
            // Requires a PDF route (see controller snippet). Opens the printable PDF in a new tab.
            $('#dsr-export-pdf').on('click', function () {
                const params = new URLSearchParams(window.location.search);
                window.open(`{{ route('admin.reports.daily-sales.pdf') }}?${params.toString()}`, '_blank');
            });
        });
    </script>
@endpush
{{--
    resources/views/admin/reports/daily_sales.blade.php

    Built against your real App\Models\Sale and App\Models\Payment.

    NOTES / remaining assumptions:
    - "Biller" column = $sale->customer (Sale::customer() belongsTo Companies, keyed by customer_id) —
      this is the relation that holds values like "Company Biller" / "Walk in Customer" in your screenshot.
      Assumed Companies has a `name` column.
    - "Paid" = sum of that sale's Payment rows (`amount`). "Balance" = total_amount - paid.
      (Payment also has pos_paid/pos_balance snapshot columns — if those should be used
      instead of a live sum, say so and I'll switch it.)
    - Warehouse filter assumes an App\Models\Warehouse with a `name` column (Sale has warehouse_id
      but no warehouse() relation yet — add one, or I can add it for you).
    - "Payment status" pie assumes Sale::payment_status uses values like 'paid' / 'pending'.
    - Controller sends: $sales, $summary (total_sales, total_paid, total_balance, total_orders),
      $hourly (labels[], values[]), $paymentStatus (completed, pending), $warehouses, $billers, $date
--}}
@extends('admin.layouts.master')

@section('title', 'Daily Sales Report')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .dsr-stat-card { padding: 1.1rem 1.25rem; }
        .dsr-stat-card .label { font-size: .72rem; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: var(--text-muted); margin-bottom: .4rem; }
        .dsr-stat-card .value { font-size: 1.5rem; font-weight: 700; }
        .dsr-chart-card canvas { max-height: 240px; }
        .dsr-legend-dot { width: 9px; height: 9px; border-radius: 50%; display: inline-block; margin-right: 6px; }
    </style>
@endpush

@section('content')
    <div class="container-fluid">

        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">Reports</a></li>
                <li class="breadcrumb-item active" aria-current="page">Daily Sales</li>
            </ol>
        </nav>

        <h4 class="fw-bold mb-3">Daily Sales Report</h4>

        {{-- ===================== FILTER BAR ===================== --}}
        <form id="dsr-filter-form" method="GET" action="{{ route('admin.reports.daily-sales') }}"
              class="d-flex flex-wrap align-items-center gap-2 mb-4">
            <input type="text" id="dsr-date" name="date" class="form-control" style="width:150px"
                   value="{{ $date ?? now()->format('Y-m-d') }}" autocomplete="off">

            <select name="warehouse_id" class="form-select select2-basic" style="width:160px">
                <option value="">All Warehouses</option>
                @foreach($warehouses ?? [] as $wh)
                    <option value="{{ $wh->id }}" @selected(request('warehouse_id') == $wh->id)>{{ $wh->name }}</option>
                @endforeach
            </select>

            <select name="customer_id" class="form-select select2-basic" style="width:160px">
                <option value="">All Billers</option>
                @foreach($billers ?? [] as $biller)
                    <option value="{{ $biller->id }}" @selected(request('customer_id') == $biller->id)>{{ $biller->name }}</option>
                @endforeach
            </select>

            <input type="text" name="search" class="form-control" placeholder="Search..." style="width:180px"
                   value="{{ request('search') }}">

            <button type="submit" class="btn btn-primary btn-sm">
                <i class="bi bi-search me-1"></i>Filter
            </button>

            <div class="ms-auto d-flex gap-2">
                <button type="button" id="dsr-export-pdf" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-file-earmark-pdf me-1"></i>Export PDF
                </button>
                <button type="button" id="dsr-export-excel" class="btn btn-primary btn-sm">
                    <i class="bi bi-file-earmark-excel me-1"></i>Export Excel
                </button>
            </div>
        </form>

        {{-- ===================== SUMMARY CARDS ===================== --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card dsr-stat-card h-100">
                    <div class="label"><i class="bi bi-cash-coin me-1"></i>Total Sales</div>
                    <div class="value">${{ number_format($summary['total_sales'] ?? 0, 2) }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card dsr-stat-card h-100">
                    <div class="label"><i class="bi bi-check-circle me-1"></i>Total Paid</div>
                    <div class="value text-success">${{ number_format($summary['total_paid'] ?? 0, 2) }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card dsr-stat-card h-100">
                    <div class="label"><i class="bi bi-scale me-1"></i>Total Balance</div>
                    @php $balance = $summary['total_balance'] ?? 0; @endphp
                    <div class="value {{ $balance < 0 ? 'text-danger' : '' }}">
                        {{ $balance < 0 ? '-' : '' }}${{ number_format(abs($balance), 2) }}
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card dsr-stat-card h-100">
                    <div class="label"><i class="bi bi-receipt me-1"></i>Total Orders</div>
                    <div class="value">{{ $summary['total_orders'] ?? 0 }}</div>
                </div>
            </div>
        </div>

        {{-- ===================== CHARTS ===================== --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-8">
                <div class="card dsr-chart-card h-100 p-3">
                    <p class="label mb-2 text-muted small fw-bold text-uppercase">Hourly Sales</p>
                    <canvas id="dsr-hourly-chart"></canvas>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card dsr-chart-card h-100 p-3">
                    <p class="label mb-2 text-muted small fw-bold text-uppercase">Payment Status</p>
                    <canvas id="dsr-status-chart"></canvas>
                    <div class="d-flex justify-content-center gap-3 mt-2 small">
                        <span><span class="dsr-legend-dot" style="background:#22c55e"></span>Completed {{ $paymentStatus['completed'] ?? 0 }}</span>
                        <span><span class="dsr-legend-dot" style="background:#f59e0b"></span>Pending {{ $paymentStatus['pending'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===================== TRANSACTION TABLE ===================== --}}
        <div class="card p-3">
            <p class="fw-bold mb-3">Transaction Details</p>
            <div class="table-responsive">
                <table id="dsr-table" class="table table-hover align-middle w-100">
                    <thead>
                    <tr>
                        <th><input type="checkbox" id="dsr-select-all"></th>
                        <th>Time</th>
                        <th>Reference</th>
                        <th>Biller</th>
                        <th class="text-end">Grand Total</th>
                        <th class="text-end">Paid</th>
                        <th class="text-end">Balance</th>
                        <th>Payment</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($sales as $sale)
                        @php
                            $paid = $sale->payments->sum('amount');
                            $rowBalance = $sale->total_amount - $paid;
                        @endphp
                        <tr>
                            <td><input type="checkbox" class="dsr-row-check"></td>
                            <td>    {{ $sale->created_at ? $sale->created_at->format('d/m/Y H:i:s') : '-' }}</td>
                            <td>{{ $sale->reference }}</td>
                            <td>{{ $sale->customer->name ?? 'N/A' }}</td>
                            <td class="text-end">${{ number_format($sale->total_amount, 2) }}</td>
                            <td class="text-end">${{ number_format($paid, 2) }}</td>
                            <td class="text-end {{ $rowBalance < 0 ? 'text-danger' : ($rowBalance > 0 ? 'text-warning' : '') }}">
                                {{ $rowBalance < 0 ? '-' : '' }}${{ number_format(abs($rowBalance), 2) }}
                            </td>
                            <td>
                                @if($sale->payment_status === 'paid')
                                    <span class="badge" style="background:rgba(34,197,94,.15); color:#22c55e;">Completed</span>
                                @else
                                    <span class="badge" style="background:rgba(245,158,11,.15); color:#f59e0b;">{{ ucfirst($sale->payment_status) }}</span>
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
    <script>
        $(function () {
            // ----- Date picker -----
            flatpickr("#dsr-date", {
                dateFormat: "Y-m-d",
                defaultDate: "{{ $date ?? now()->format('Y-m-d') }}",
                onChange: function () {
                    $('#dsr-filter-form').submit();
                }
            });

            // ----- Select2 -----
            $('.select2-basic').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });

            // ----- DataTable (paging/search only; totals come from the summary cards) -----
            $('#dsr-table').DataTable({
                paging: true,
                pageLength: 10,
                order: [[1, 'asc']],
                columnDefs: [{ orderable: false, targets: [0, 7] }]
            });

            $('#dsr-select-all').on('change', function () {
                $('.dsr-row-check').prop('checked', $(this).is(':checked'));
            });

            // ----- Hourly bar chart -----
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
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false } },
                        y: { grid: { color: 'rgba(255,255,255,0.06)' }, ticks: { callback: v => '$' + v } }
                    }
                }
            });

            // ----- Payment status pie chart -----
            new Chart(document.getElementById('dsr-status-chart'), {
                type: 'pie',
                data: {
                    labels: ['Completed', 'Pending'],
                    datasets: [{
                        data: [{{ $paymentStatus['completed'] ?? 0 }}, {{ $paymentStatus['pending'] ?? 0 }}],
                        backgroundColor: ['#22c55e', '#f59e0b'],
                        borderWidth: 0
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
            });

            // ----- Export to Excel (SheetJS, already loaded in master layout) -----
            $('#dsr-export-excel').on('click', function () {
                const wb = XLSX.utils.table_to_book(document.getElementById('dsr-table'), { sheet: "Daily Sales" });
                XLSX.writeFile(wb, `daily-sales-report-{{ $date ?? now()->format('Y-m-d') }}.xlsx`);
            });

            // ----- Export to PDF -----
            // Requires a PDF route (see controller snippet). Opens the printable PDF in a new tab.
            $('#dsr-export-pdf').on('click', function () {
                const params = new URLSearchParams(window.location.search);
                window.open(`{{ route('admin.reports.daily-sales.pdf') }}?${params.toString()}`, '_blank');
            });
        });
    </script>
@endpush
{{--
    resources/views/admin/reports/daily_sales.blade.php

    Built against your real App\Models\Sale and App\Models\Payment.

    NOTES / remaining assumptions:
    - "Biller" column = $sale->customer (Sale::customer() belongsTo Companies, keyed by customer_id) —
      this is the relation that holds values like "Company Biller" / "Walk in Customer" in your screenshot.
      Assumed Companies has a `name` column.
    - "Paid" = sum of that sale's Payment rows (`amount`). "Balance" = total_amount - paid.
      (Payment also has pos_paid/pos_balance snapshot columns — if those should be used
      instead of a live sum, say so and I'll switch it.)
    - Warehouse filter assumes an App\Models\Warehouse with a `name` column (Sale has warehouse_id
      but no warehouse() relation yet — add one, or I can add it for you).
    - "Payment status" pie assumes Sale::payment_status uses values like 'paid' / 'pending'.
    - Controller sends: $sales, $summary (total_sales, total_paid, total_balance, total_orders),
      $hourly (labels[], values[]), $paymentStatus (completed, pending), $warehouses, $billers, $date
--}}
@extends('admin.layouts.master')

@section('title', 'Daily Sales Report')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .dsr-stat-card { padding: 1.1rem 1.25rem; }
        .dsr-stat-card .label { font-size: .72rem; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; color: var(--text-muted); margin-bottom: .4rem; }
        .dsr-stat-card .value { font-size: 1.5rem; font-weight: 700; }
        .dsr-chart-card canvas { max-height: 240px; }
        .dsr-legend-dot { width: 9px; height: 9px; border-radius: 50%; display: inline-block; margin-right: 6px; }
    </style>
@endpush

@section('content')
    <div class="container-fluid">

        <nav aria-label="breadcrumb" class="mb-2">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">Reports</a></li>
                <li class="breadcrumb-item active" aria-current="page">Daily Sales</li>
            </ol>
        </nav>

        <h4 class="fw-bold mb-3">Daily Sales Report</h4>

        {{-- ===================== FILTER BAR ===================== --}}
        <form id="dsr-filter-form" method="GET" action="{{ route('admin.reports.daily-sales') }}"
              class="d-flex flex-wrap align-items-center gap-2 mb-4">
            <input type="text" id="dsr-date" name="date" class="form-control" style="width:150px"
                   value="{{ $date ?? now()->format('Y-m-d') }}" autocomplete="off">

            <select name="warehouse_id" class="form-select select2-basic" style="width:160px">
                <option value="">All Warehouses</option>
                @foreach($warehouses ?? [] as $wh)
                    <option value="{{ $wh->id }}" @selected(request('warehouse_id') == $wh->id)>{{ $wh->name }}</option>
                @endforeach
            </select>

            <select name="customer_id" class="form-select select2-basic" style="width:160px">
                <option value="">All Billers</option>
                @foreach($billers ?? [] as $biller)
                    <option value="{{ $biller->id }}" @selected(request('customer_id') == $biller->id)>{{ $biller->name }}</option>
                @endforeach
            </select>

            <input type="text" name="search" class="form-control" placeholder="Search..." style="width:180px"
                   value="{{ request('search') }}">

            <button type="submit" class="btn btn-primary btn-sm">
                <i class="bi bi-search me-1"></i>Filter
            </button>

            <div class="ms-auto d-flex gap-2">
                <button type="button" id="dsr-export-pdf" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-file-earmark-pdf me-1"></i>Export PDF
                </button>
                <button type="button" id="dsr-export-excel" class="btn btn-primary btn-sm">
                    <i class="bi bi-file-earmark-excel me-1"></i>Export Excel
                </button>
            </div>
        </form>

        {{-- ===================== SUMMARY CARDS ===================== --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card dsr-stat-card h-100">
                    <div class="label"><i class="bi bi-cash-coin me-1"></i>Total Sales</div>
                    <div class="value">${{ number_format($summary['total_sales'] ?? 0, 2) }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card dsr-stat-card h-100">
                    <div class="label"><i class="bi bi-check-circle me-1"></i>Total Paid</div>
                    <div class="value text-success">${{ number_format($summary['total_paid'] ?? 0, 2) }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card dsr-stat-card h-100">
                    <div class="label"><i class="bi bi-scale me-1"></i>Total Balance</div>
                    @php $balance = $summary['total_balance'] ?? 0; @endphp
                    <div class="value {{ $balance < 0 ? 'text-danger' : '' }}">
                        {{ $balance < 0 ? '-' : '' }}${{ number_format(abs($balance), 2) }}
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card dsr-stat-card h-100">
                    <div class="label"><i class="bi bi-receipt me-1"></i>Total Orders</div>
                    <div class="value">{{ $summary['total_orders'] ?? 0 }}</div>
                </div>
            </div>
        </div>

        {{-- ===================== CHARTS ===================== --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-8">
                <div class="card dsr-chart-card h-100 p-3">
                    <p class="label mb-2 text-muted small fw-bold text-uppercase">Hourly Sales</p>
                    <canvas id="dsr-hourly-chart"></canvas>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card dsr-chart-card h-100 p-3">
                    <p class="label mb-2 text-muted small fw-bold text-uppercase">Payment Status</p>
                    <canvas id="dsr-status-chart"></canvas>
                    <div class="d-flex justify-content-center gap-3 mt-2 small">
                        <span><span class="dsr-legend-dot" style="background:#22c55e"></span>Completed {{ $paymentStatus['completed'] ?? 0 }}</span>
                        <span><span class="dsr-legend-dot" style="background:#f59e0b"></span>Pending {{ $paymentStatus['pending'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===================== TRANSACTION TABLE ===================== --}}
        <div class="card p-3">
            <p class="fw-bold mb-3">Transaction Details</p>
            <div class="table-responsive">
                <table id="dsr-table" class="table table-hover align-middle w-100">
                    <thead>
                    <tr>
                        <th><input type="checkbox" id="dsr-select-all"></th>
                        <th>Time</th>
                        <th>Reference</th>
                        <th>Biller</th>
                        <th class="text-end">Grand Total</th>
                        <th class="text-end">Paid</th>
                        <th class="text-end">Balance</th>
                        <th>Payment</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($sales as $sale)
                        @php
                            $paid = $sale->payments->sum('amount');
                            $rowBalance = $sale->total_amount - $paid;
                        @endphp
                        <tr>
                            <td><input type="checkbox" class="dsr-row-check"></td>
                            <td>    {{ $sale->created_at ? $sale->created_at->format('d/m/Y H:i:s') : '-' }}</td>
                            <td>{{ $sale->reference }}</td>
                            <td>{{ $sale->customer->name ?? 'N/A' }}</td>
                            <td class="text-end">${{ number_format($sale->total_amount, 2) }}</td>
                            <td class="text-end">${{ number_format($paid, 2) }}</td>
                            <td class="text-end {{ $rowBalance < 0 ? 'text-danger' : ($rowBalance > 0 ? 'text-warning' : '') }}">
                                {{ $rowBalance < 0 ? '-' : '' }}${{ number_format(abs($rowBalance), 2) }}
                            </td>
                            <td>
                                @if($sale->payment_status === 'paid')
                                    <span class="badge" style="background:rgba(34,197,94,.15); color:#22c55e;">Completed</span>
                                @else
                                    <span class="badge" style="background:rgba(245,158,11,.15); color:#f59e0b;">{{ ucfirst($sale->payment_status) }}</span>
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
    <script>
        $(function () {
            // ----- Date picker -----
            flatpickr("#dsr-date", {
                dateFormat: "Y-m-d",
                defaultDate: "{{ $date ?? now()->format('Y-m-d') }}",
                onChange: function () {
                    $('#dsr-filter-form').submit();
                }
            });

            // ----- Select2 -----
            $('.select2-basic').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });

            // ----- DataTable (paging/search only; totals come from the summary cards) -----
            $('#dsr-table').DataTable({
                paging: true,
                pageLength: 10,
                order: [[1, 'asc']],
                columnDefs: [{ orderable: false, targets: [0, 7] }]
            });

            $('#dsr-select-all').on('change', function () {
                $('.dsr-row-check').prop('checked', $(this).is(':checked'));
            });

            // ----- Hourly bar chart -----
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
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false } },
                        y: { grid: { color: 'rgba(255,255,255,0.06)' }, ticks: { callback: v => '$' + v } }
                    }
                }
            });

            // ----- Payment status pie chart -----
            new Chart(document.getElementById('dsr-status-chart'), {
                type: 'pie',
                data: {
                    labels: ['Completed', 'Pending'],
                    datasets: [{
                        data: [{{ $paymentStatus['completed'] ?? 0 }}, {{ $paymentStatus['pending'] ?? 0 }}],
                        backgroundColor: ['#22c55e', '#f59e0b'],
                        borderWidth: 0
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
            });

            // ----- Export to Excel (SheetJS, already loaded in master layout) -----
            $('#dsr-export-excel').on('click', function () {
                const wb = XLSX.utils.table_to_book(document.getElementById('dsr-table'), { sheet: "Daily Sales" });
                XLSX.writeFile(wb, `daily-sales-report-{{ $date ?? now()->format('Y-m-d') }}.xlsx`);
            });

            // ----- Export to PDF -----
            // Requires a PDF route (see controller snippet). Opens the printable PDF in a new tab.
            $('#dsr-export-pdf').on('click', function () {
                const params = new URLSearchParams(window.location.search);
                window.open(`{{ route('admin.reports.daily-sales.pdf') }}?${params.toString()}`, '_blank');
            });
        });
    </script>
@endpush
