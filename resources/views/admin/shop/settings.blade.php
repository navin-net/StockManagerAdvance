@extends('admin.layouts.master')

@section('title', __('messages.shop_settings'))

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
        <div class="card">
            <div class="card-body">

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
                    @csrf


                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.name_shop') }}</label>
                        <input type="text" name="name_shop" class="form-control"
                            value="{{ old('name_shop', $shop->name_shop ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.address') }}</label>
                        <input type="text" name="address" class="form-control"
                            value="{{ old('address', $shop->address ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.open_shop_time') }}</label>
                        <input type="text" name="open_shop_time" class="form-control"
                            value="{{ old('open_shop_time', $shop->open_shop_time ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.close_shop') }}</label>
                        <input type="text" name="close_shop" class="form-control"
                            value="{{ old('close_shop', $shop->close_shop ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.x') }}</label>
                        <input type="text" name="x" class="form-control" value="{{ old('x', $shop->x ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.description') }}</label>
                        <input type="text" name="description" class="form-control"
                            value="{{ old('description', $shop->description ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.phone') }}</label>
                        <input type="text" name="phone" class="form-control"
                            value="{{ old('phone', $shop->phone ?? '') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.email') }}</label>
                        <input type="email" name="email" class="form-control"
                            value="{{ old('email', $shop->email ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.logo_shop') }}</label>

                        @if (isset($shop) && $shop->logo_shop)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $shop->logo_shop) }}" alt="logo_shop"
                                    style="height: 50px;">
                            </div>
                        @endif

                        <input type="file" name="logo_shop" class="form-control @error('logo_shop') is-invalid @enderror"
                            accept="image/*">

                        @error('logo_shop')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">
                        {{ __('messages.save_changes') }}
                    </button>

                </form>
            </div>
        </div>
    </div>
@endsection
