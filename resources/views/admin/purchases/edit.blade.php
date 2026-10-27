@extends('admin.layouts.master')
@section('title', __('Edit purchase'))
@section('content')
@include('admin.records.transaction-edit', ['record' => $purchase, 'kind' => 'purchase'])
@endsection
