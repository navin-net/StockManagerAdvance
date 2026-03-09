@extends('admin.layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row align-items-center">
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

        <section class="section">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <h5 class="card-title mb-1 fw-bold">{{ __('messages.open_register') }}</h5>
                                <p class="text-muted small">Register is not open, Please enter the cash in hand amount and
                                    click open register.</p>
                            </div>

                            <form method="POST" action="{{ route('pos.open-register.store') }}">
                                @csrf

                                <div class="mb-4">
                                    <label for="cash_in_hand" class="form-label fw-semibold">
                                        {{ __('messages.cash_in_hand') }} <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="number" name="cash_in_hand" id="cash_in_hand"
                                            class="form-control border-start-0" required>
                                    </div>

                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary py-2 fw-semibold">
                                        <i class="bi bi-door-open me-2"></i>{{ __('messages.open_register') }}
                                    </button>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection
