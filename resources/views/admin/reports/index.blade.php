@extends('admin.layouts.master')
@section('title', __('messages.overview_chart'))

@php
    use Illuminate\Support\Facades\Lang;
    $t = fn($key, $fallback) => Lang::has('messages.' . $key) ? __('messages.' . $key) : $fallback;

    $stats = [
        ['icon' => 'box-seam', 'label' => __('messages.total_products'), 'value' => number_format($summary['products']), 'tone' => 'brand'],
        ['icon' => 'cash-coin', 'label' => __('messages.total_sales'), 'value' => '$' . number_format($summary['sales'], 2), 'tone' => 'green'],
        ['icon' => 'cart-check', 'label' => __('messages.product_sold'), 'value' => number_format($summary['items']), 'tone' => 'blue'],
        ['icon' => 'bag', 'label' => $t('purchases', 'Purchases'), 'value' => '$' . number_format($summary['purchases'], 2), 'tone' => 'amber'],
    ];
    $hasData = collect([$chart['sales'], $chart['purchases'], $chart['items']])->flatten()->sum() > 0;
@endphp

@push('styles')
    <style>
        .rp-stat { display: flex; align-items: center; gap: .9rem; padding: 1.1rem 1.25rem; height: 100%; }
        .rp-icon { width: 46px; height: 46px; flex: none; border-radius: 12px; display: grid; place-items: center; font-size: 1.25rem; }
        .rp-icon.brand { background: rgba(var(--brand-rgb), .14); color: var(--brand-text); }
        .rp-icon.green { background: rgba(34, 197, 94, .14); color: #22A55B; }
        .rp-icon.blue  { background: rgba(14, 165, 233, .14); color: #0B8DC9; }
        .rp-icon.amber { background: rgba(245, 158, 11, .16); color: #C47F06; }
        .rp-label { color: var(--text-muted); font-size: .8rem; font-weight: 600; }
        .rp-value { font-size: 1.55rem; font-weight: 800; letter-spacing: -.02em; line-height: 1.2; }

        .rp-legend { display: flex; flex-wrap: wrap; gap: .5rem; }
        .rp-chip {
            display: inline-flex; align-items: center; gap: .45rem; padding: .3rem .8rem; border-radius: 999px;
            border: 1px solid var(--border-color); background: transparent; color: var(--text-color); font-size: .82rem; font-weight: 600;
        }
        .rp-chip i { width: 10px; height: 10px; border-radius: 3px; background: var(--c); }
        .rp-chip.off { opacity: .45; text-decoration: line-through; }
        .rp-chart { position: relative; height: 340px; }
        .rp-empty { position: absolute; inset: 0; display: grid; place-items: center; text-align: center; color: var(--text-muted); }
        .rp-year { max-width: 140px; border-radius: 10px; font-weight: 600; }
    </style>
@endpush

@section('content')
    <div class="container-fluid">

        {{-- ============ Header ============ --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h1 class="h3 fw-bold mb-1">{{ __('messages.overview_chart') }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">{{ __('messages.dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('messages.reports') }}</li>
                    </ol>
                </nav>
            </div>

            <form method="GET" action="{{ route('reports') }}">
                <label for="overview-year" class="visually-hidden">Report year</label>
                <select id="overview-year" name="year" class="form-select rp-year" onchange="this.form.submit()">
                    @for ($optionYear = now()->year; $optionYear >= now()->year - 4; $optionYear--)
                        <option value="{{ $optionYear }}" @selected($year === $optionYear)>{{ $optionYear }}</option>
                    @endfor
                </select>
                <noscript><button type="submit" class="btn btn-primary mt-2">View</button></noscript>
            </form>
        </div>

        {{-- ============ Stats ============ --}}
        <div class="row g-3 mb-4">
            @foreach ($stats as $s)
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card rp-stat">
                        <span class="rp-icon {{ $s['tone'] }}"><i class="bi bi-{{ $s['icon'] }}"></i></span>
                        <div>
                            <div class="rp-label">{{ $s['label'] }}</div>
                            <div class="rp-value">{{ $s['value'] }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ============ Chart ============ --}}
        <div class="card p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                <div>
                    <h2 class="h5 fw-bold mb-1">{{ $year }} {{ $t('performance', 'performance') }}</h2>
                    <div class="text-muted small">{{ $t('performance_hint', 'Sales, purchases and units sold by month') }}</div>
                </div>
                <div class="rp-legend" id="rpLegend"></div>
            </div>

            <div class="rp-chart">
                <canvas id="overview-chart" aria-label="Monthly sales, purchases, and units sold chart"></canvas>
                @unless ($hasData)
                    <div class="rp-empty">
                        <div>
                            <i class="bi bi-bar-chart fs-1 d-block mb-2"></i>
                            <div class="fw-semibold">{{ $t('no_data', 'No data for') }} {{ $year }}</div>
                            <small>{{ $t('no_data_hint', 'Sales and purchases will show here once recorded.') }}</small>
                        </div>
                    </div>
                @endunless
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Chart.js is already loaded by the layout footer --}}
    <script>
        (function () {
            const css = name => getComputedStyle(document.documentElement).getPropertyValue(name).trim();
            const COLORS = { sales: '#5B5BD6', purchases: '#F59E0B', items: '#22C55E' };
            const LABELS = {
                sales: @json($t('sales', 'Sales')),
                purchases: @json($t('purchases', 'Purchases')),
                items: @json($t('units_sold', 'Units sold'))
            };
            const money = v => '$' + Number(v).toLocaleString();

            const chart = new Chart(document.getElementById('overview-chart'), {
                data: {
                    labels: @json($chart['labels']),
                    datasets: [
                        { type: 'bar', key: 'sales', label: LABELS.sales, data: @json($chart['sales']), backgroundColor: COLORS.sales, borderRadius: 6, maxBarThickness: 26, yAxisID: 'currency', order: 2 },
                        { type: 'bar', key: 'purchases', label: LABELS.purchases, data: @json($chart['purchases']), backgroundColor: COLORS.purchases, borderRadius: 6, maxBarThickness: 26, yAxisID: 'currency', order: 2 },
                        { type: 'line', key: 'items', label: LABELS.items, data: @json($chart['items']), borderColor: COLORS.items, backgroundColor: COLORS.items, borderWidth: 2.5, tension: .35, pointRadius: 3, pointHoverRadius: 5, yAxisID: 'units', order: 1 }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: c => ' ' + c.dataset.label + ': ' + (c.dataset.key === 'items' ? Number(c.parsed.y).toLocaleString() : money(c.parsed.y))
                            }
                        }
                    },
                    scales: {
                        x: { grid: { display: false } },
                        currency: { beginAtZero: true, border: { display: false }, ticks: { callback: money } },
                        units: { beginAtZero: true, position: 'right', border: { display: false }, grid: { display: false }, ticks: { callback: v => Number(v).toLocaleString() } }
                    }
                }
            });

            // Theme-aware axis / grid colours (re-applied when the admin switches theme)
            function paint() {
                Chart.defaults.font.family = css('--font') || 'inherit';
                const muted = css('--text-muted'), line = css('--border-color');
                ['x', 'currency', 'units'].forEach(id => {
                    chart.options.scales[id].ticks.color = muted;
                    if (id !== 'units' && id !== 'x') chart.options.scales[id].grid.color = line;
                });
                chart.update('none');
            }
            paint();
            new MutationObserver(paint).observe(document.documentElement, { attributes: true, attributeFilter: ['data-bs-theme'] });

            // Clickable legend: show / hide a series
            const legend = document.getElementById('rpLegend');
            Object.keys(COLORS).forEach((key, i) => {
                const b = document.createElement('button');
                b.type = 'button'; b.className = 'rp-chip'; b.style.setProperty('--c', COLORS[key]);
                b.innerHTML = '<i></i>' + LABELS[key];
                b.addEventListener('click', () => {
                    const on = chart.isDatasetVisible(i);
                    chart.setDatasetVisibility(i, !on);
                    b.classList.toggle('off', on);
                    chart.update();
                });
                legend.appendChild(b);
            });
        })();
    </script>
@endpush