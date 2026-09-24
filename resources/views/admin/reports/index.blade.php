@extends('admin.layouts.master')
@section('title', __('messages.overview_chart'))

@push('styles')
    <style>
        .overview-stat { padding: 1.1rem 1.25rem; }
        .overview-stat .label { color: var(--text-muted); font-size: .72rem; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; }
        .overview-stat .value { font-size: 1.5rem; font-weight: 700; }
        .overview-chart { height: 320px; }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        <div class="pagetitle mb-4 d-flex flex-wrap justify-content-between align-items-start gap-3">
            <div>
                <h1 class="display-6 fw-bold mb-2">{{ __('messages.overview_chart') }}</h1>
                <nav>
                    <ol class="breadcrumb rounded-3 p-2 mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-primary text-decoration-none">{{ __('messages.dashboard') }}</a></li>
                        <li class="breadcrumb-item active text-muted">{{ __('messages.reports') }}</li>
                    </ol>
                </nav>
            </div>
            <form method="GET" action="{{ route('reports') }}" class="d-flex gap-2">
                <label for="overview-year" class="visually-hidden">Report year</label>
                <select id="overview-year" name="year" class="form-select form-select-sm" onchange="this.form.submit()">
                    @for ($optionYear = now()->year; $optionYear >= now()->year - 4; $optionYear--)
                        <option value="{{ $optionYear }}" @selected($year === $optionYear)>{{ $optionYear }}</option>
                    @endfor
                </select>
                <noscript><button type="submit" class="btn btn-primary btn-sm">View</button></noscript>
            </form>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3"><div class="card overview-stat h-100"><div class="label"><i class="bi bi-box-seam me-1"></i>{{ __('messages.total_products') }}</div><div class="value">{{ number_format($summary['products']) }}</div></div></div>
            <div class="col-6 col-md-3"><div class="card overview-stat h-100"><div class="label"><i class="bi bi-cash-coin me-1"></i>{{ __('messages.total_sales') }}</div><div class="value text-success">${{ number_format($summary['sales'], 2) }}</div></div></div>
            <div class="col-6 col-md-3"><div class="card overview-stat h-100"><div class="label"><i class="bi bi-cart-check me-1"></i>{{ __('messages.product_sold') }}</div><div class="value">{{ number_format($summary['items']) }}</div></div></div>
            <div class="col-6 col-md-3"><div class="card overview-stat h-100"><div class="label"><i class="bi bi-bag me-1"></i>Purchases</div><div class="value text-warning">${{ number_format($summary['purchases'], 2) }}</div></div></div>
        </div>

        <div class="card p-3">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div><h2 class="h5 mb-1">{{ $year }} performance</h2><div class="text-muted small">Sales, purchases, and units sold by month</div></div>
                <div class="d-flex flex-wrap gap-3 small text-muted"><span><i class="bi bi-square-fill text-success me-1"></i>Sales</span><span><i class="bi bi-square-fill text-warning me-1"></i>Purchases</span><span><i class="bi bi-square-fill text-primary me-1"></i>Units sold</span></div>
            </div>
            <div class="overview-chart"><canvas id="overview-chart" aria-label="Monthly sales, purchases, and units sold chart"></canvas></div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.js"></script>
    <script>
        new Chart(document.getElementById('overview-chart'), {
            type: 'bar',
            data: {
                labels: @json($chart['labels']),
                datasets: [
                    { label: 'Sales', data: @json($chart['sales']), backgroundColor: 'rgba(34, 197, 94, .75)', borderRadius: 4, yAxisID: 'currency' },
                    { label: 'Purchases', data: @json($chart['purchases']), backgroundColor: 'rgba(245, 158, 11, .75)', borderRadius: 4, yAxisID: 'currency' },
                    { label: 'Units sold', data: @json($chart['items']), backgroundColor: 'rgba(14, 165, 233, .75)', borderRadius: 4, yAxisID: 'units' }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false } },
                    currency: { beginAtZero: true, ticks: { callback: value => '$' + Number(value).toLocaleString() } },
                    units: { beginAtZero: true, position: 'right', grid: { display: false }, ticks: { callback: value => Number(value).toLocaleString() } }
                }
            }
        });
    </script>
@endpush
