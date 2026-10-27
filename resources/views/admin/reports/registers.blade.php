@extends('admin.layouts.master')
@section('content')
<div class="container py-4"><h1 class="h3">Cash registers</h1>
<div class="dt-static-viewport"><table class="table dt-static-table"><thead><tr><th>Reference</th><th>User</th><th>Opened</th><th>Closed</th><th>Status</th><th>Opening cash</th><th>Cash total</th></tr></thead><tbody>
@foreach ($registers as $register)
<tr><td>{{ $register->reference }}</td><td>{{ $register->user?->name }}</td><td>{{ $register->opened_at }}</td><td>{{ $register->closed_at }}</td><td>{{ $register->status }}</td><td>{{ $register->cash_in_hand }}</td><td>{{ $register->total_cash }}</td></tr>
@endforeach
</tbody></table></div>{{ $registers->links() }}</div>
@endsection
