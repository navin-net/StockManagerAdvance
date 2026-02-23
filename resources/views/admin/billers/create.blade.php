@extends('admin.layouts.master')

@section('title', __('messages.add_billers'))

@section('content')
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

    <div class="row">
        <div class="col-12">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form method="POST" action="{{ route('billers.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            {{-- Name --}}
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">{{ __('messages.name') }}</label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            {{-- Email --}}
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">{{ __('messages.email') }}</label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Billers -->
                            <div class="col-md-6 mb-3">
                                <label for="warehouse_id" class="form-label">{{ __('messages.warehouse') }}</label>
                                <select name="warehouse_id" id="warehouse_id" class="form-select @error('warehouse_id') is-invalid @enderror" required>
                                    <option value="">{{ __('messages.select_warehouse') }}</option>
                                    @foreach ($warehouse as $warehouse)
                                        <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                            {{ $warehouse->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('warehouse_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">{{ __('messages.phone') }}</label>
                                <input type="phone" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="col-md-6 mb-3">
                                <label for="address" class="form-label">{{ __('messages.address') }}</label>
                                <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror"
                                       value="{{ old('address') }}" required>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">{{ __('messages.city') }}</label>
                                <input type="text" name="city" id="city" class="form-control @error('city') is-invalid @enderror"
                                       value="{{ old('city') }}" required>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="number_of_houses" class="form-label">{{ __('messages.number_of_houses') }}</label>
                                <input type="text" name="number_of_houses" id="number_of_houses" class="form-control @error('number_of_houses') is-invalid @enderror"
                                       value="{{ old('number_of_houses') }}" required>
                                @error('number_of_houses')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="street" class="form-label">{{ __('messages.street') }}</label>
                                <input type="text" name="street" id="street" class="form-control @error('street') is-invalid @enderror"
                                       value="{{ old('street') }}" required>
                                @error('street')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="logo" class="form-label">{{ __('messages.logo') }}</label>
                                <input type="file" name="logo" id="logo" class="form-control @error('logo') is-invalid @enderror"
                                       value="{{ old('logo') }}" >
                                @error('logo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="note" class="form-label">{{ __('messages.note') }}</label>
                                <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror"
                                          required>{{ old('note') }}</textarea>
                                @error('note')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            {{-- Buttons --}}
                            <div class="mb-3 mt-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> {{ __('messages.submit') }}
                                </button>
                                <a href="{{ route('billers.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-circle"></i> {{ __('messages.cancel') }}
                                </a>
                            </div>
                        </div> {{-- .row --}}
                    </form>
                </div>
            </div>
        </div>
    </div>



@endsection

