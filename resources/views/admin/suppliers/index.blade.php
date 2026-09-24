@extends('admin.layouts.master')
@section('title', __('messages.suppliers_list'))
@section('content')
    <div class="container-fluid py-4">
        <div class="row align-items-center mb-4">

            {{-- LEFT: Page title + breadcrumb --}}
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

            {{-- RIGHT: IP address --}}
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <small class="text-muted fw-semibold">
                    {{ __('messages.ip_address') }}:
                </small>
                <span class="fw-semibold text-primary">
                    {{ auth()->user()->ip_address }}
                </span>
            </div>

        </div>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title mb-0 fw-semibold"></h5>
                            <div class="dropdown">
                                <button class="btn btn-primary btn-sm dropdown-toggle rounded-3" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-gear-fill me-1"></i> Actions
                                </button>
                                <ul class="dropdown-menu shadow-sm rounded-3">
                                    <li><a class="dropdown-item" href="{{ __('suppliers/create') }}" id="addProductBtn">
                                            <i class="bi bi-plus-circle me-2"></i>{{ __('messages.add') }}</a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#" id="bulkDeleteBtn" disabled>
                                            <i class="bi bi-trash me-2"></i>{{ __('messages.delete') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div id="alertsContainer" class="mb-4"></div>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered rounded-3 align-middle" id="suppliersTable">
                                <thead class="table-primary">
                                <tr>
                                    <th scope="col" class="py-3"><input type="checkbox" id="selectAll"></th>
                                    <th scope="col" class="py-3">Name</th>
                                    <th scope="col" class="py-3">Group</th>
                                    <th scope="col" class="py-3">Email</th>
                                    <th scope="col" class="py-3">Phone</th>
                                    <th scope="col" class="py-3">City</th>
                                    <th scope="col" class="py-3 text-center">Actions</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>



@endsection
