@extends('admin.layouts.master')
@section('title', $pageTitle)

@push('styles')
	<style>
		.ms-stat-card { padding: 1.1rem 1.25rem; }
		.ms-stat-card .label { color: var(--text-muted); font-size: .72rem; font-weight: 700; letter-spacing: .05em; text-transform: uppercase; }
		.ms-stat-card .value { font-size: 1.5rem; font-weight: 700; }
		.ms-chart-card canvas { max-height: 260px; }
	</style>
@endpush

@section('content')
	<div class="container-fluid py-4">
		<div class="pagetitle mb-4">
			<h1 class="display-6 fw-bold">{{ $pageTitle }}</h1>
			<nav>
				<ol class="breadcrumb rounded-3 p-2">
					@foreach ($breadcrumbs as $breadcrumb)
						<li class="breadcrumb-item {{ $breadcrumb['active'] ? 'active text-muted' : '' }}">
							@if (!$breadcrumb['active'])
								<a href="{{ $breadcrumb['url'] }}" class="text-primary text-decoration-none">{{ $breadcrumb['label'] }}</a>
							@else
								{{ $breadcrumb['label'] }}
							@endif
						</li>
					@endforeach
				</ol>
			</nav>
		</div>

		<form method="GET" action="{{ route('reports.monthly-sales') }}" class="mb-4">
			<div class="row g-2 align-items-center">
				<div class="col-12 col-sm-6 col-md-auto">
					<input type="month" name="month" class="form-control form-control-sm" value="{{ $month }}" aria-label="Report month">
				</div>
				<div class="col-12 col-sm-6 col-md-2">
					<select name="warehouse_id" class="form-select form-select-sm select2-basic">
						<option value="">All Warehouses</option>
						@foreach ($warehouses as $warehouse)
							<option value="{{ $warehouse->id }}" @selected(request('warehouse_id') == $warehouse->id)>{{ $warehouse->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-12 col-sm-6 col-md-2">
					<select name="customer_id" class="form-select form-select-sm select2-basic">
						<option value="">All Customers</option>
						@foreach ($billers as $biller)
							<option value="{{ $biller->id }}" @selected(request('customer_id') == $biller->id)>{{ $biller->name }}</option>
						@endforeach
					</select>
				</div>
				<div class="col-12 col-sm-6 col-md-2">
					<input type="search" name="search" class="form-control form-control-sm" placeholder="Reference" value="{{ request('search') }}">
				</div>
				<div class="col-12 col-sm-auto">
					<button type="submit" class="btn btn-primary btn-sm w-100"><i class="bi bi-search me-1"></i>Filter</button>
				</div>
				<div class="col-12 col-md-auto ms-md-auto">
					<button type="button" id="ms-export-excel" class="btn btn-outline-primary btn-sm w-100"><i class="bi bi-file-earmark-excel me-1"></i>Export Excel</button>
				</div>
			</div>
		</form>

		<div class="row g-3 mb-4">
			<div class="col-6 col-md-3"><div class="card ms-stat-card h-100"><div class="label"><i class="bi bi-cash-coin me-1"></i>Total Sales</div><div class="value">${{ number_format($summary['total_sales'], 2) }}</div></div></div>
			<div class="col-6 col-md-3"><div class="card ms-stat-card h-100"><div class="label"><i class="bi bi-check-circle me-1"></i>Total Paid</div><div class="value text-success">${{ number_format($summary['total_paid'], 2) }}</div></div></div>
			<div class="col-6 col-md-3"><div class="card ms-stat-card h-100"><div class="label"><i class="bi bi-receipt me-1"></i>Total Orders</div><div class="value">{{ number_format($summary['total_orders']) }}</div></div></div>
			<div class="col-6 col-md-3"><div class="card ms-stat-card h-100"><div class="label"><i class="bi bi-box-seam me-1"></i>Items Sold</div><div class="value">{{ number_format($summary['total_items']) }}</div></div></div>
		</div>

		<div class="row g-3 mb-4">
			<div class="col-lg-8">
				<div class="card ms-chart-card h-100 p-3"><div class="fw-bold mb-2">Daily Sales</div><canvas id="ms-daily-chart"></canvas></div>
			</div>
			<div class="col-lg-4">
				<div class="card ms-chart-card h-100 p-3"><div class="fw-bold mb-2">Payment Status</div><canvas id="ms-status-chart"></canvas>
					<div class="d-flex justify-content-center gap-3 mt-2 small"><span><i class="bi bi-circle-fill text-success me-1"></i>Paid {{ $paymentStatus['paid'] }}</span><span><i class="bi bi-circle-fill text-warning me-1"></i>Pending {{ $paymentStatus['pending'] }}</span></div>
				</div>
			</div>
		</div>

		<div class="card p-3">
			<div class="d-flex justify-content-between align-items-center mb-3"><div class="fw-bold">Transaction Details</div><span class="text-muted small">{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</span></div>
			<div class="table-responsive">
				<table id="ms-table" class="table table-hover align-middle w-100">
					<thead><tr><th>Date</th><th>Reference</th><th>Customer</th><th class="text-end">Items</th><th class="text-end">Grand Total</th><th class="text-end">Paid</th><th class="text-end">Balance</th><th>Payment</th></tr></thead>
					<tbody>
						@forelse ($sales as $sale)
							@php $paid = $sale->payments->sum('amount'); $balance = $sale->total_amount - $paid; @endphp
							<tr>
								<td>{{ $sale->date->format('Y-m-d') }}</td>
								<td>{{ $sale->reference }}</td>
								<td>{{ $sale->customer->name ?? 'N/A' }}</td>
								<td class="text-end">{{ number_format($sale->items->sum('quantity')) }}</td>
								<td class="text-end">${{ number_format($sale->total_amount, 2) }}</td>
								<td class="text-end">${{ number_format($paid, 2) }}</td>
								<td class="text-end {{ $balance > 0 ? 'text-warning' : ($balance < 0 ? 'text-danger' : '') }}">{{ $balance < 0 ? '-' : '' }}${{ number_format(abs($balance), 2) }}</td>
								<td><span class="badge {{ in_array($sale->payment_status, ['paid', 'completed']) ? 'text-bg-success' : 'text-bg-warning' }}">{{ ucfirst($sale->payment_status) }}</span></td>
							</tr>
						@empty
							<tr><td colspan="8" class="text-center text-muted py-4">No sales found for this month.</td></tr>
						@endforelse
					</tbody>
				</table>
			</div>
		</div>
	</div>
@endsection

@push('scripts')
	<script>
		$(function () {
			$('.select2-basic').select2({ theme: 'bootstrap-5', width: '100%' });
			$('#ms-table').DataTable({ pageLength: 10, order: [[0, 'asc']], columnDefs: [{ orderable: false, targets: [7] }] });

			new Chart(document.getElementById('ms-daily-chart'), {
				type: 'bar',
				data: { labels: @json($daily['labels']), datasets: [{ data: @json($daily['values']), backgroundColor: '#0ea5e9', borderRadius: 4, maxBarThickness: 34 }] },
				options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false } }, y: { ticks: { callback: value => '$' + value } } } }
			});

			new Chart(document.getElementById('ms-status-chart'), {
				type: 'doughnut',
				data: { labels: ['Paid', 'Pending'], datasets: [{ data: [{{ $paymentStatus['paid'] }}, {{ $paymentStatus['pending'] }}], backgroundColor: ['#22c55e', '#f59e0b'], borderWidth: 0 }] },
				options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
			});

			$('#ms-export-excel').on('click', function () {
				const workbook = XLSX.utils.table_to_book(document.getElementById('ms-table'), { sheet: 'Monthly Sales' });
				XLSX.writeFile(workbook, `monthly-sales-report-{{ $month }}.xlsx`);
			});
		});
	</script>
@endpush
