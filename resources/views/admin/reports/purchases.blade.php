@extends('admin.layouts.master')
@section('content')
<div class="container py-4">
<h1>Purchases — {{ $period }}</h1>
<div class="dt-static-viewport"><table class="table dt-static-table"><thead><tr><th>Reference</th><th>Supplier</th><th>Status</th><th>Total</th><th>Paid</th><th>Balance</th></tr></thead><tbody>
@foreach ($purchases as $purchase)
@php($paid = $purchase->payments->where('status', 'completed')->sum('amount'))
<tr><td>{{ $purchase->reference }}</td><td>{{ $purchase->supplier?->name }}</td><td>{{ $purchase->status }}</td><td>{{ number_format($purchase->total_amount, 2) }}</td><td>{{ number_format($paid, 2) }}</td><td>{{ number_format($purchase->total_amount - $paid, 2) }}</td></tr>
@endforeach
</tbody></table></div>
<p>Total: {{ number_format($purchases->sum('total_amount'), 2) }}</p>
</div>
@endsection
