@extends('admin.layouts.master')
@section('title', __('messages.customers_list'))
@section('content')
<div class="container-fluid py-4">
<div class="row align-items-center mb-4">
    <div class="col-md-6">
        <div class="pagetitle">
            <h1 class="h3 fw-bold mb-2">{{ $pageTitle }}</h1>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    @foreach ($breadcrumbs as $breadcrumb)
                        @if (!$breadcrumb['active'])
                            <li class="breadcrumb-item">
                                <a href="{{ $breadcrumb['url'] }}" class="text-decoration-none">
                                    {{ $breadcrumb['label'] }}
                                </a>
                            </li>
                        @else
                            <li class="breadcrumb-item active text-muted" aria-current="page">
                                {{ $breadcrumb['label'] }}
                            </li>
                        @endif
                    @endforeach
                </ol>
            </nav>
        </div>
    </div>
    <div class="col-md-6 text-md-end mt-3 mt-md-0">
        <small class="text-muted fw-semibold">
            {{ __('messages.ip_address') }}:
        </small>
        <span class="fw-semibold text-primary">
            {{ auth()->user()->ip_address }}
        </span>
    </div>







</div>
@endsection
