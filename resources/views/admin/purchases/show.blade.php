@extends('admin.layouts.master')
@section('content')
<div class="container py-4">
    <h1 class="h3">Purchase — {{ $purchase->reference }}</h1>
    @if ($errors->any())
        <div class="alert alert-danger"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    <p>Supplier: {{ $purchase->supplier?->name }} · Status: {{ $purchase->status }} · Total: {{ number_format($purchase->total_amount, 2) }}</p>
    <table class="table"><thead><tr><th>Product</th><th>Quantity</th><th>Cost price</th><th>Total</th></tr></thead><tbody>
        @foreach ($purchase->items as $item)<tr><td>{{ $item->product?->name }}</td><td>{{ $item->quantity }}</td><td>{{ number_format($item->cost_price, 2) }}</td><td>{{ number_format($item->quantity * $item->cost_price, 2) }}</td></tr>@endforeach
    </tbody></table>
    <a class="btn btn-primary" href="{{ route('purchases.edit', $purchase->id) }}">Edit purchase</a>
</div>
@endsection
