@extends('admin.layouts.master')
@section('content')
<div class="container py-4">
    <h1 class="h3">Customer account — {{ $customer->name }}</h1>
    @if ($errors->any())
        <div class="alert alert-danger"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    <form method="POST" action="{{ isset($user) ? route('customers.users.update', $user->id) : route('customers.users.store', $customer->id) }}">
        @csrf @isset($user) @method('PUT') @endisset
        <label for="name" class="form-label">Name</label><input id="name" class="form-control" name="name" value="{{ old('name', $user->name ?? '') }}" required>
        <label for="email" class="form-label mt-3">Email</label><input id="email" class="form-control" type="email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
        <label for="password" class="form-label mt-3">{{ isset($user) ? 'New password (optional)' : 'Password' }}</label>
        <input id="password" class="form-control" type="password" name="password" minlength="8" autocomplete="new-password" {{ isset($user) ? '' : 'required' }}>
        <label for="password_confirmation" class="form-label mt-3">Confirm password</label>
        <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" autocomplete="new-password">
        <button class="btn btn-primary mt-3">Save account</button>
    </form>
</div>
@endsection
