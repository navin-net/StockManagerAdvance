@extends('admin.layouts.master')
@section('title', __('Edit sale'))
@section('content')
@include('admin.records.transaction-edit', ['record' => $sale, 'kind' => 'sale'])
@endsection
