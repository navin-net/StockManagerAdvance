@extends('admin.layouts.master')
@section('title', __('messages.edit_user'))
@section('content')
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <div class="pagetitle">
                <h1 class="h3 fw-bold mb-2">{{ $pageTitle }} - {{  $user->name }}</h1>
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
                        <form method="POST" action="{{ route('billers.users.update', $user->id) }}"  enctype="multipart/form-data">
                        {{-- <form method="POST"  action="{{ route('billers.store') }}" enctype="multipart/form-data"> --}}
                            @csrf
                            @method('PUT')
                            <div class="row">
                                {{-- Name --}}
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">{{ __('messages.name') }}</label>
                                    <input type="text" name="name" id="name"
                                        class="form-control @error('name') is-invalid @enderror" value="{{$user->name}}"
                                        required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">{{ __('messages.email') }}</label>
                                    <input type="email" name="email" id="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{$user->email}}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Password --}}
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">{{ __('messages.password') }}</label>
                                    <div class="input-group">
                                        <input type="password" name="password" id="password"
                                            class="form-control @error('password') is-invalid @enderror" required>
                                        <button type="button" class="btn btn-outline-secondary toggle-password">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Buttons --}}
                                <div class="mb-3 mt-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save"></i> {{ __('messages.submit') }}
                                    </button>
                                <a href="{{ url()->previous() }}" class="btn btn-secondary">
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
