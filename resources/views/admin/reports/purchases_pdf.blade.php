<!doctype html><html><head><meta charset="utf-8"><style>body{font-family:DejaVu Sans,sans-serif;font-size:11px}table{width:100%;border-collapse:collapse}th,td{padding:6px;border:1px solid #ddd}</style></head><body><h1>Purchases — {{ $period }}</h1>
<table class="table"><thead><tr><th>Reference</th><th>Supplier</th><th>Status</th><th>Total</th><th>Paid</th><th>Balance</th></tr></thead><tbody>
@foreach ($purchases as $purchase)
@php($paid = $purchase->payments->where('status', 'completed')->sum('amount'))
<tr><td>{{ $purchase->reference }}</td><td>{{ $purchase->supplier?->name }}</td><td>{{ $purchase->status }}</td><td>{{ number_format($purchase->total_amount, 2) }}</td><td>{{ number_format($paid, 2) }}</td><td>{{ number_format($purchase->total_amount - $paid, 2) }}</td></tr>
@endforeach
</tbody></table>
<p>Total: {{ number_format($purchases->sum('total_amount'), 2) }}</p>
</body></html>