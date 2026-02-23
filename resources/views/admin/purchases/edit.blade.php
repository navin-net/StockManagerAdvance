@extends('admin.layouts.master')

@section('title', __('messages.purchases_list'))

@section('content')
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <div class="pagetitle">
                <h1 class="h3 fw-bold mb-2">{{ $pageTitle . ' - ' . 'Testing'}} </h1>
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
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        {{-- <h5 class="card-title mb-3">{{ __('messages.add_new_sale') }}</h5> --}}
                        <div id="alertsContainer" class="mb-4"></div>
                        {{-- <div id="alertsContainer" class="alert alert-danger d-none"></div> --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
