@extends('admin.layouts.master')
@section('title', __('messages.dashboard'))

@php
    use Illuminate\Support\Facades\Lang;
    use Illuminate\Support\Facades\Route;

    // Translate with an English fallback when a key is not in lang/*/messages.php yet.
    $t = fn($key, $fallback) => Lang::has('messages.' . $key) ? __('messages.' . $key) : $fallback;
    // Use the named route when it exists, otherwise fall back to a path.
    $go = fn($name, $path) => Route::has($name) ? route($name) : url($path);

    $hour = now()->hour;
    $greeting = $hour < 12 ? $t('good_morning', 'Good morning') : ($hour < 18 ? $t('good_afternoon', 'Good afternoon') : $t('good_evening', 'Good evening'));
    $name = auth()->user()->first_name ?? auth()->user()->name;

    $stats = [
        ['icon' => 'box-seam', 'tone' => 'brand', 'label' => __('messages.total_products'), 'value' => number_format($productCount), 'url' => $go('products.index', 'admin/products')],
        ['icon' => 'cash-coin', 'tone' => 'green', 'label' => __('messages.total_sales'), 'value' => '$' . number_format($saleTotal ?? 0, 2), 'url' => $go('sales.index', 'admin/sales')],
        ['icon' => 'bag-check', 'tone' => 'blue', 'label' => __('messages.product_sold'), 'value' => number_format($salesCount), 'url' => $go('sales.index', 'admin/sales')],
        ['icon' => 'cart4', 'tone' => 'amber', 'label' => __('messages.purchases'), 'value' => number_format($avg_sales), 'url' => $go('purchases.index', 'admin/purchases')],
    ];

    $maxCat = max(1, (int) ($categories->max('products_count') ?? 1));
    $topCats = $categories->sortByDesc('products_count')->take(6);
    $ip = $ipFromDB ?? auth()->user()->ip_address;
@endphp

@push('styles')
    <style>
        .db-stat { padding: 1.15rem 1.25rem; height: 100%; display: flex; flex-direction: column; gap: .9rem; }
        .db-stat-top { display: flex; align-items: center; gap: .9rem; }
        .db-icon { width: 46px; height: 46px; flex: none; border-radius: 12px; display: grid; place-items: center; font-size: 1.25rem; }
        .db-icon.brand { background: rgba(var(--brand-rgb), .14); color: var(--brand-text); }
        .db-icon.green { background: rgba(34, 197, 94, .14); color: #22A55B; }
        .db-icon.blue { background: rgba(14, 165, 233, .14); color: #0B8DC9; }
        .db-icon.amber { background: rgba(245, 158, 11, .16); color: #C47F06; }
        .db-label { color: var(--text-muted); font-size: .8rem; font-weight: 600; }
        .db-value { font-size: 1.6rem; font-weight: 800; letter-spacing: -.02em; line-height: 1.15; }
        .db-more {
            display: flex; justify-content: space-between; align-items: center; padding-top: .75rem; margin-top: auto;
            border-top: 1px solid var(--border-color); font-size: .82rem; font-weight: 600; color: var(--brand-text); text-decoration: none;
        }
        .db-more i { transition: transform .15s; }
        .db-more:hover i { transform: translateX(3px); }

        .db-chart { position: relative; height: 320px; }
        .db-brand { display: flex; align-items: center; gap: .8rem; padding: .6rem 0; }
        .db-brand + .db-brand { border-top: 1px solid var(--border-color); }
        .db-tile { width: 38px; height: 38px; border-radius: 10px; flex: none; display: grid; place-items: center; font-weight: 800; background: rgba(var(--brand-rgb), .14); color: var(--brand-text); }
        .db-brand .nm { font-weight: 600; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .db-bar { height: 6px; border-radius: 99px; background: var(--hover-bg); overflow: hidden; margin-top: .35rem; }
        .db-bar i { display: block; height: 100%; border-radius: 99px; background: var(--brand); }
        .db-empty { display: grid; place-items: center; text-align: center; color: var(--text-muted); padding: 2.5rem 1rem; }

        .db-tabs { display: inline-flex; padding: 4px; gap: 4px; border-radius: 12px; background: var(--bg-color); border: 1px solid var(--border-color); }
        .db-tabs .nav-link { border: 0; border-radius: 9px; padding: .45rem 1.1rem; font-weight: 600; color: var(--text-muted); }
        .db-tabs .nav-link.active { background: var(--card-bg); color: var(--text-color); box-shadow: 0 1px 2px rgba(16, 24, 40, .12); }
    </style>
@endpush

@section('content')
    <div class="container-fluid">

        {{-- ============ Header ============ --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h1 class="h3 fw-bold mb-1">{{ $greeting }}, {{ $name }}</h1>
                <p class="text-muted mb-0">{{ __('messages.dashboard_welcome') }}</p>
            </div>
            <span class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill border small">
                <i class="bi bi-hdd-network text-muted"></i>
                <span class="text-muted fw-semibold">{{ __('messages.ip_address') }}:</span>
                <span class="fw-semibold">{{ $ip }}</span>
            </span>
        </div>

        {{-- ============ Flash messages ============ --}}
        @foreach (['success' => 'check-circle-fill', 'error' => 'exclamation-triangle-fill'] as $type => $icon)
            @if (session($type))
                <div class="alert alert-{{ $type === 'error' ? 'danger' : 'success' }} alert-dismissible fade show d-flex align-items-center gap-2"
                     role="alert">
                    <i class="bi bi-{{ $icon }}"></i>
                    <div>{{ session($type) }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        @endforeach

        {{-- ============ Stat cards ============ --}}
        <div class="row g-3 mb-4">
            @foreach ($stats as $s)
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card db-stat">
                        <div class="db-stat-top">
                            <span class="db-icon {{ $s['tone'] }}"><i class="bi bi-{{ $s['icon'] }}"></i></span>
                            <div>
                                <div class="db-label">{{ $s['label'] }}</div>
                                <div class="db-value">{{ $s['value'] }}</div>
                            </div>
                        </div>
                        <a href="{{ $s['url'] }}" class="db-more">
                            <span>{{ __('messages.more_info') }}</span><i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- ============ Categories: chart + list ============ --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-8">
                <div class="card p-4 h-100">
                    <div class="mb-3">
                        <h2 class="h5 fw-bold mb-1">{{ $t('products_by_category', 'Products by category') }}</h2>
                        <div class="text-muted small">{{ $categories->count() }} {{ __('messages.categories') }} &middot; {{ number_format($productCount) }} {{ __('messages.total_products') }}</div>
                    </div>
                    @if ($categories->isEmpty())
                        <div class="db-empty">
                            <div><i class="bi bi-bar-chart fs-1 d-block mb-2"></i>{{ $t('no_category_data', 'No category data yet.') }}</div>
                        </div>
                    @else
                        <div class="db-chart"><canvas id="categoryChart" aria-label="Products per category"></canvas></div>
                    @endif
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card p-4 h-100">
                    <h2 class="h5 fw-bold mb-2">{{ $t('top_categories', 'Top categories') }}</h2>
                    @forelse ($topCats as $c)
                        <div class="db-brand">
                            <span class="db-tile">{{ mb_strtoupper(mb_substr($c->name, 0, 1)) }}</span>
                            <div class="flex-grow-1 min-w-0">
                                <div class="d-flex justify-content-between gap-2">
                                    <span class="nm">{{ $c->name }}</span>
                                    <span class="fw-bold">{{ number_format($c->products_count) }}</span>
                                </div>
                                <div class="text-muted" style="font-size:.74rem">
                                    {{ $c->sub_categories_count }} {{ $t('sub_categories', 'subcategories') }}
                                </div>
                                <div class="db-bar"><i style="width: {{ round($c->products_count / $maxCat * 100) }}%"></i></div>
                            </div>
                        </div>
                    @empty
                        <div class="db-empty">{{ $t('no_category_data', 'No category data yet.') }}</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ============ Tables ============ --}}
        <div class="card p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                <ul class="nav db-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#billers" type="button" role="tab">
                            <i class="bi bi-buildings me-1"></i>{{ __('messages.billers') }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#sales" type="button" role="tab">
                            <i class="bi bi-receipt me-1"></i>{{ __('messages.sales') }}
                        </button>
                    </li>
                </ul>
            </div>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="billers" role="tabpanel">
                    <div class="table-responsive">
                        <table id="billersTable" class="table table-hover align-middle w-100">
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

                <div class="tab-pane fade" id="sales" role="tabpanel">
                    <div class="table-responsive">
                        <table id="SalesTable" class="table table-hover align-middle w-100">
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
@endsection

@push('scripts')
    <script>
        /* ---------- Category chart (Chart.js is loaded by the layout footer) ---------- */
        const categoryCanvas = document.getElementById('categoryChart');
        if (categoryCanvas) {
            const css = n => getComputedStyle(document.documentElement).getPropertyValue(n).trim();
            const categoryChart = new Chart(categoryCanvas, {
                type: 'bar',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: @json(__('messages.total_products')),
                        data: @json($data),
                        backgroundColor: '#5B5BD6',
                        hoverBackgroundColor: '#7C7CFF',
                        borderRadius: 8,
                        maxBarThickness: 36
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false } },
                        y: { beginAtZero: true, border: { display: false }, ticks: { precision: 0 } }
                    }
                }
            });

            // Theme-aware axis colours; re-applied when the admin switches theme.
            const paint = () => {
                Chart.defaults.font.family = css('--font') || 'inherit';
                const muted = css('--text-muted'), line = css('--border-color');
                categoryChart.options.scales.x.ticks.color = muted;
                categoryChart.options.scales.y.ticks.color = muted;
                categoryChart.options.scales.y.grid.color = line;
                categoryChart.update('none');
            };
            paint();
            new MutationObserver(paint).observe(document.documentElement, { attributes: true, attributeFilter: ['data-bs-theme'] });
        }

        /* ---------- DataTables ---------- */
        const datatableLang = {
            paginate: { previous: '<', next: '>' },
            emptyTable: @json(__('messages.no_data_available')),
            processing: @json(__('messages.processing')),
            lengthMenu: @json(__('messages.show')) + ' _MENU_ ' + @json(__('messages.entries')),
            search: @json(__('messages.search'))
        };

        const Btable = $('#billersTable').DataTable({
            pageLength: 10,
            processing: true,
            serverSide: true,
            ajax: @json(route('billers.index')),
            columns: [
                { data: 'name' }, { data: 'group_name' }, { data: 'warehouse_name' },
                { data: 'email' }, { data: 'phone' }, { data: 'city' }
            ],
            language: datatableLang,
            responsive: true
        });

        const Stable = $('#SalesTable').DataTable({
            pageLength: 10,
            processing: true,
            serverSide: true,
            ajax: @json(route('sales.getData')),
            columns: [
                { data: 'reference' }, { data: 'customer' }, { data: 'created_at' },
                { data: 'grand_total' }, { data: 'status' }
            ],
            language: datatableLang,
            responsive: true
        });

        // The Sales table is built while hidden, so re-fit its columns when its tab opens.
        document.querySelector('[data-bs-target="#sales"]').addEventListener('shown.bs.tab', () => {
            Stable.columns.adjust().responsive.recalc();
        });
    </script>
@endpush
