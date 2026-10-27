@extends('admin.layouts.master')
@section('content')
<div class="container py-4">
    <h1 class="h3">Edit customer</h1>
    @if ($errors->any())
        <div class="alert alert-danger"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    <form method="POST" action="{{ route('customers.update', $customer->id) }}">
        @csrf @method('PUT')
        @foreach (['name', 'email', 'phone', 'address', 'city', 'street', 'number_of_houses'] as $field)
            <label class="form-label mt-3" for="{{ $field }}">{{ ucfirst(str_replace('_', ' ', $field)) }}</label>
            <input id="{{ $field }}" name="{{ $field }}" class="form-control" value="{{ old($field, $customer->$field) }}" {{ in_array($field, ['name', 'email', 'phone', 'address']) ? 'required' : '' }}>
        @endforeach
        <button class="btn btn-primary mt-3">Save customer</button>
    </form>
</div>
@endsection
