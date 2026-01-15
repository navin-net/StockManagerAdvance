@extends('layouts.master')

@section('content')
<h2>POS Page</h2>

<p>Opened at: {{ session('opened_at') }}</p>

<form method="POST" action="{{ route('pos.close-register') }}">
    @csrf
    <button type="submit">Close Register</button>
</form>
@endsection
