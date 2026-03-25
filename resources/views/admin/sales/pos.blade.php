@extends('admin.layouts.master')

@section('title', __('messages.pos_sales'))

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

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card border-0 shadow-sm rounded-3">

                        <div class="card-body p-4">
                            <div class="row align-items-center mb-4">
                                <div class="col-md-6">
                                    {{-- <h5 class="card-title mb-0 fw-semibold">{{ __('messages.sales_list') }}</h5> --}}
                                </div>
                                {{-- <div class="col-md-2 text-end"> --}}
                                    {{-- Location --}}

                                    {{-- </div> --}}
                                <div class="col-md-6 text-end d-flex justify-content-end gap-2">
                                    <div class="dropdown">
                                        <button class="btn btn-secondary btn-sm dropdown-toggle rounded-3" type="button"
                                            id="warehouseDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-buildings-fill"></i> {{ __('messages.warehouse') }}
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm rounded-3"
                                            aria-labelledby="warehouseDropdown">
                                            <li>
                                                <a class="dropdown-item active" href="#" data-warehouse="">
                                                    <i class="bi bi-grid me-2"></i>{{ __('messages.warehouse') }}
                                                </a>
                                            </li>
                                            @foreach($warehouses ?? [] as $warehouse)
                                                <li>
                                                    <a class="dropdown-item" href="#" data-warehouse="{{ $warehouse->id }}">
                                                        <i class="bi bi-building me-2"></i>{{ $warehouse->name }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    {{-- Second Dropdown (rename label/items as needed) --}}
                                    <div class="dropdown">
                                        <button class="btn btn-primary btn-sm dropdown-toggle rounded-3" type="button"
                                            id="actionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-gear-fill me-1"></i> {{ __('messages.actions') }}
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm rounded-3"
                                            aria-labelledby="actionDropdown">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('sales.create') }}">
                                                    <i class="bi bi-plus-circle me-2"></i>{{ __('messages.add') }}
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" id="exportSales">
                                                    <i
                                                        class="bi bi-file-excel me-2"></i>{{ __('messages.export_to_excel') }}
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" id="bulkDeleteBtn">
                                                    <i class="bi bi-trash me-2"></i>{{ __('messages.delete') }}
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div id="alertsContainer" class="mb-4">
                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif
                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ session('error') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif
                            </div>

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered rounded-3 align-middle" id="salesTable">
                                    <thead class="table-primary">
                                        <tr>
                                            <th><input type="checkbox" id="selectAll" class="form-check-input"></th>


                                            <th>{{ __('messages.date') }}</th>
                                            <th>{{ __('messages.reference') }}</th>
                                            <th>{{ __('messages.billers') }}</th>
                                            <th>{{ __('messages.customer') }}</th>
                                            <th>{{ __('messages.status') }}</th>
                                            <th>{{ __('messages.grand_total') }}</th>
                                            <th>{{ __('messages.paid') }}</th>
                                            <th>{{ __('messages.balance') }}</th>
                                            <th>{{ __('messages.payment_status') }}</th>
                                            <th scope="col" class="py-3 text-center" width="120">
                                                {{ __('messages.actions') }}
                                            </th>
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
@push('scripts')
    <script>
        const StatusMapper = {
            renderBadge: function (data) {
                // Define your mapping for cleaner logic
                const config = {
                    'completed': { class: 'bg-success', label: "{{ __('messages.completed') }}" },
                    'cancelled': { class: 'bg-danger', label: "{{ __('messages.cancelled') }}" },
                    'pending':   { class: 'bg-warning text-dark', label: "{{ __('messages.pending') }}" }
                };

                const status = config[data] || config['pending'];

                return `<span class="badge rounded-pill ${status.class}">${status.label}</span>`;
            }
        };
        /* =========================
         *  TABLE SALES
         * ========================= */
        $(document).ready(function () {
            const table = $('#salesTable').DataTable({
                pageLength: 10,
                lengthMenu: [
                    [10, 20, 30, 50, -1],
                    [10, 20, 30, 50, "{{ __('messages.all') }}"]
                ],
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('sales.getDataPos') }}",
                    data: function (d) {
                        d.warehouse_id = $('[data-warehouse].active').data('warehouse');
                    }
                },          // ← comma here
                columns: [{
                    data: 'id',
                    name: 'id',
                    orderable: false,
                    searchable: false,
                    render: data =>
                        `<input type="checkbox" class="saleCheckbox" value="${data}">`
                },
                {
                    data: 'date',
                    name: 'date',
                },
                {
                    data: 'reference',
                    name: 'reference',
                },
                {
                    data: 'customer',
                    name: 'customer',

                    defaultContent: 'N/A'
                },
                {
                    data: 'biller',
                    name: 'biller',
                    defaultContent: 'N/A'
                },
                {
                    data: 'status',
                    name: 'status',
                    className: 'text-center',
                    render: StatusMapper.renderBadge
                },
                {
                    data: 'grand_total',
                    name: 'grand_total',

                },
                {
                    data: 'paid',
                    name: 'paid',
                },
                {
                    data: 'balance',
                    name: 'balance',
                },
                {
                    data: 'payment_status',
                    name: 'payment_status',
                    className: 'text-center',
                    render: StatusMapper.renderBadge
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
                ],
                language: {
                    paginate: {
                        previous: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                            class="bi bi-arrow-left" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                                        </svg>`,
                        next: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                            class="bi bi-arrow-right" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                                        </svg>`
                    },
                    lengthMenu: '{{ __('messages.show') }} _MENU_ {{ __('messages.entries') }}',
                    search: '{{ __('messages.search') }}',
                    emptyTable: "{{ __('messages.no_data_available') }}",
                    processing: "{{ __('messages.processing') }}",
                    zeroRecords: "{{ __('messages.no_matching_records') }}",
                    infoEmpty: "{{ __('messages.showing_0_to_0_of_0_entries') }}",
                    infoFiltered: "{{ __('messages.filtered_from_total_entries', ['total' => '_MAX_']) }}"
                }
            });

            $(document).on('click', '[data-warehouse]', function (e) {
                e.preventDefault();
                $('[data-warehouse]').removeClass('active');
                $(this).addClass('active');
                const label = $(this).text().trim();
                $('#warehouseDropdown').html(`<i class="bi bi-building me-1"></i> ${label}`);
                table.ajax.reload(); // ✅ use the `table` variable directly
            });
        });
    </script>
@endpush
