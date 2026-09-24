@extends('admin.layouts.master')

@section('title', 'Product Sales Report')

@section('content')
    <div class="psr container-fluid py-4">
        <style>
            .psr{
                color: var(--text-color);
            }
            .psr .psr-eyebrow-free h1{ font-weight:700; letter-spacing:-0.01em; margin-bottom:.15rem; color:var(--text-color); }
            .psr .breadcrumb{ margin-bottom:0; }
            .psr .breadcrumb a{ color:var(--text-muted); text-decoration:none; }
            .psr .breadcrumb a:hover{ color:var(--primary-color); text-decoration:underline; }

            /* Period switch */
            .psr-filterbar{
                background:var(--card-bg); border:1px solid var(--border-color); border-radius:8px;
                padding:.6rem .75rem; transition: background-color var(--transition-speed), border-color var(--transition-speed);
            }
            .psr-tabs{ display:inline-flex; background:var(--hover-bg); border-radius:999px; padding:3px; gap:2px; }
            .psr-tabs input{ display:none; }
            .psr-tabs label{
                font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.4px;
                color:var(--text-muted); padding:.4rem .85rem; border-radius:999px; cursor:pointer; margin:0;
                transition:background .15s ease, color .15s ease;
            }
            .psr-tabs input:checked + label{ background:var(--primary-color); color:#fff; }
            .psr-filterbar .form-control{
                background:var(--bg-color); color:var(--text-color); border:1px solid var(--editor-border, var(--border-color));
            }
            .psr-filterbar .form-control:focus{
                border-color:var(--primary-color); box-shadow:0 0 0 3px rgba(14,165,233,.15); outline:none;
            }
            .psr-view-btn{
                background:var(--primary-color); color:#fff; border:none; border-radius:6px;
                font-size:.8125rem; font-weight:600; padding:.45rem 1rem;
                transition: opacity .2s, transform .15s;
            }
            .psr-view-btn:hover{ opacity:.88; transform:translateY(-1px); color:#fff; }

            .psr-period-line{ color:var(--text-muted); font-size:.9rem; }

            /* Hero + supporting stats */
            .psr-summary{
                background:var(--card-bg); border:1px solid var(--border-color); border-radius:8px;
                display:flex; flex-wrap:wrap; align-items:stretch; overflow:hidden;
                box-shadow:0 4px 6px rgba(0,0,0,.1);
                transition: background-color var(--transition-speed), border-color var(--transition-speed);
            }
            .psr-hero{
                padding:1.5rem 1.75rem; flex:1 1 260px; border-right:1px solid var(--border-color);
                background:var(--hover-bg);
            }
            .psr-hero .label{ color:var(--text-muted); font-size:.85rem; margin-bottom:.35rem; }
            .psr-hero .value{
                font-size:2.35rem; font-weight:700; color:var(--primary-color); line-height:1;
                font-variant-numeric:tabular-nums;
            }
            .psr-stats{ display:flex; flex:2 1 360px; }
            .psr-stat{ flex:1; padding:1.5rem 1.75rem; border-right:1px solid var(--border-color); }
            .psr-stat:last-child{ border-right:none; }
            .psr-stat .label{ color:var(--text-muted); font-size:.85rem; margin-bottom:.35rem; }
            .psr-stat .value{ font-size:1.5rem; font-weight:700; font-variant-numeric:tabular-nums; color:var(--text-color); }

            /* Table */
            .psr-table-card{
                background:var(--card-bg); border:1px solid var(--border-color); border-radius:8px;
                overflow:hidden; margin-top:1.25rem; box-shadow:0 4px 6px rgba(0,0,0,.1);
                transition: background-color var(--transition-speed), border-color var(--transition-speed);
            }
            .psr-table-head{ padding:1rem 1.25rem; border-bottom:1px solid var(--border-color); }
            .psr-table-head h2{ font-size:1.05rem; font-weight:700; margin:0; color:var(--text-color); }
            .psr table{ margin-bottom:0; color:var(--text-color); }
            .psr thead th{
                background:var(--hover-bg); color:var(--text-muted); font-size:.75rem; font-weight:700;
                text-transform:uppercase; letter-spacing:.05em; border-bottom:1px solid var(--border-color);
                padding:.65rem 1.25rem;
            }
            .psr tbody td{ padding:.7rem 1.25rem; border-bottom:1px solid var(--border-color); vertical-align:middle; }
            .psr tbody tr:last-child td{ border-bottom:none; }
            .psr tbody tr{ transition: background-color .12s ease; }
            .psr tbody tr:hover{ background:var(--hover-bg); }
            .psr .rank{
                width:1.75rem; height:1.75rem; border-radius:50%; display:inline-flex; align-items:center;
                justify-content:center; font-size:.8rem; font-weight:700; color:var(--text-muted); background:var(--hover-bg);
            }
            .psr .rank.top{ background:rgba(245,158,11,.16); color:var(--gold); }
            .psr .product-name{ font-weight:600; color:var(--text-color); }
            .psr .product-code{ font-size:.8rem; color:var(--text-muted); font-family:"Courier New", monospace; }
            .psr .num{ font-variant-numeric:tabular-nums; text-align:right; }
            .psr .revenue-cell{ min-width:190px; }
            .psr .revenue-amount{ font-weight:700; font-variant-numeric:tabular-nums; color:var(--text-color); }
            .psr .share-track{ background:var(--hover-bg); border-radius:3px; height:5px; margin-top:.35rem; overflow:hidden; }
            .psr .share-fill{ background:var(--primary-color); height:100%; border-radius:3px; }
            .psr tfoot td{
                padding:.8rem 1.25rem; border-top:2px solid var(--text-color); font-weight:700;
                font-variant-numeric:tabular-nums; color:var(--text-color);
            }
            .psr .empty-state{ padding:3rem 1.25rem; text-align:center; color:var(--text-muted); }
            .psr .psr-slate{ color:var(--text-muted); font-size:.875rem; }
        </style>

        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
            <div class="psr-eyebrow-free">
                <h1 class="h2">Product Sales Report</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('messages.dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('reports') }}">{{ __('messages.reports') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Product Sales</li>
                    </ol>
                </nav>
            </div>

            <form method="GET" action="{{ route('reports.product-sales') }}" class="psr-filterbar">
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <div class="psr-tabs">
                        <input type="radio" id="period-newest" name="period" value="newest" @checked($period === 'newest') onchange="this.form.submit()">
                        <label for="period-newest">Newest</label>
                        <input type="radio" id="period-daily" name="period" value="daily" @checked($period === 'daily') onchange="this.form.submit()">
                        <label for="period-daily">Daily</label>
                        <input type="radio" id="period-monthly" name="period" value="monthly" @checked($period === 'monthly') onchange="this.form.submit()">
                        <label for="period-monthly">Monthly</label>
                        <input type="radio" id="period-yearly" name="period" value="yearly" @checked($period === 'yearly') onchange="this.form.submit()">
                        <label for="period-yearly">Yearly</label>
                    </div>

                    @if ($period === 'daily')
                        <input type="date" name="date" class="form-control form-control-sm" style="width:9.5rem" value="{{ $selectedDate->format('Y-m-d') }}">
                    @elseif ($period === 'monthly')
                        <input type="month" name="month" class="form-control form-control-sm" style="width:9.5rem" value="{{ $selectedDate->format('Y-m') }}">
                    @elseif ($period === 'yearly')
                        <input type="number" name="year" min="2000" max="2100" class="form-control form-control-sm" style="width:6.5rem" value="{{ $selectedDate->format('Y') }}">
                    @endif

                    <button type="submit" class="psr-view-btn">View report</button>
                </div>
            </form>
        </div>

        @php
            $periodLabel = match ($period) {
                'newest' => 'recent activity',
                'monthly' => $selectedDate->format('F Y'),
                'yearly' => $selectedDate->format('Y'),
                default => $selectedDate->format('F j, Y'),
            };
            $revenueTotal = max($summary['revenue'], 0.01);
        @endphp

        <p class="psr-period-line mb-3">
            @if ($period === 'newest')
                Showing the <strong style="color:var(--text-color)">most recent</strong> product sales
            @else
                Showing product sales for <strong style="color:var(--text-color)">{{ $periodLabel }}</strong>
            @endif
        </p>

        <div class="psr-summary mb-1">
            <div class="psr-hero">
                <div class="label">Sales revenue</div>
                <div class="value">${{ number_format($summary['revenue'], 2) }}</div>
            </div>
            <div class="psr-stats">
                <div class="psr-stat">
                    <div class="label">Products sold</div>
                    <div class="value">{{ number_format($summary['products']) }}</div>
                </div>
                <div class="psr-stat">
                    <div class="label">Units sold</div>
                    <div class="value">{{ number_format($summary['units']) }}</div>
                </div>
                <div class="psr-stat">
                    <div class="label">Orders</div>
                    <div class="value">{{ number_format($summary['orders']) }}</div>
                </div>
            </div>
        </div>

        <div class="psr-table-card">
            <div class="psr-table-head">
                <h2>Sales by product</h2>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                    <tr>
                        <th style="width:3rem">Rank</th>
                        <th>Product</th>
                        @if ($period === 'newest')
                            <th>Last sold</th>
                        @endif
                        <th class="text-end">Units</th>
                        <th class="text-end">Orders</th>
                        <th class="revenue-cell text-end">Revenue</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($products as $product)
                        @php $share = min(100, ($product->revenue / $revenueTotal) * 100); @endphp
                        <tr>
                            <td><span class="rank @if($loop->iteration <= 3) top @endif">{{ $loop->iteration }}</span></td>
                            <td>
                                <div class="product-name">{{ $product->name }}</div>
                                @if ($product->code)
                                    <div class="product-code">{{ $product->code }}</div>
                                @endif
                            </td>
                            @if ($period === 'newest')
                                <td class="psr-slate">{{ optional($product->last_sold_at)->diffForHumans() ?? '—' }}</td>
                            @endif
                            <td class="num">{{ number_format($product->units_sold) }}</td>
                            <td class="num">{{ number_format($product->order_count) }}</td>
                            <td class="revenue-cell">
                                <div class="d-flex justify-content-end revenue-amount">${{ number_format($product->revenue, 2) }}</div>
                                <div class="share-track"><div class="share-fill" style="width: {{ $share }}%"></div></div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $period === 'newest' ? 6 : 5 }}">
                                <div class="empty-state">
                                    No sales recorded for {{ $periodLabel }}. Try a different date range above.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                    @if ($products->isNotEmpty())
                        <tfoot>
                        <tr>
                            <td colspan="{{ $period === 'newest' ? 3 : 2 }}">Total</td>
                            <td class="num">{{ number_format($summary['units']) }}</td>
                            <td class="num">{{ number_format($summary['orders']) }}</td>
                            <td class="num">${{ number_format($summary['revenue'], 2) }}</td>
                        </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
@endsection
