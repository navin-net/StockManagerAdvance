@extends('admin.layouts.master')

@section('title', __('messages.sales_list'))

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
                                <div class="col-md-6 text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-primary btn-sm dropdown-toggle rounded-3" type="button"
                                            id="actionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-gear-fill me-1"></i> {{ __('messages.actions') }}
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm rounded-3"
                                            aria-labelledby="actionDropdown">
                                            <li><a class="dropdown-item" href="{{ route('sales.create') }}">
                                                    <i class="bi bi-plus-circle me-2"></i>{{ __('messages.add') }}</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#" id="exportSales">
                                                    <i
                                                        class="bi bi-file-excel me-2"></i>{{ __('messages.export_to_excel') }}</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#" id="bulkDeleteBtn" disabled>
                                                    <i class="bi bi-trash me-2"></i>{{ __('messages.delete') }}</a>
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
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-3 border-0 shadow">
                <div class="modal-header border-0 rounded-top-3">
                    <h5 class="modal-title fw-semibold" id="deleteModalLabel">{{ __('messages.confirm_delete') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ __('messages.delete_confirm') }}
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary btn-sm rounded-3"
                        data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm rounded-3">{{ __('messages.delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Delete Confirmation Modal -->
    <div class="modal fade" id="bulkDeleteModal" tabindex="-1" aria-labelledby="bulkDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-3 border-0 shadow">
                <div class="modal-header border-0 rounded-top-3">
                    <h5 class="modal-title fw-semibold" id="bulkDeleteModalLabel">
                        {{ __('messages.confirm_bulk_delete') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ __('messages.delete_confirm') }}
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary btn-sm rounded-3"
                        data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    <button type="button" class="btn btn-danger btn-sm rounded-3"
                        id="confirmBulkDelete">{{ __('messages.delete') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Add Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="paymentForm" enctype="multipart/form-data">
                        <input type="hidden" name="sale_id" value=""> <!-- fill via JS -->

                        <div class="mb-3">
                            <label>Amount <span class="text-danger">*</span></label>
                            <input type="number" name="amount" step="0.01" min="0.01" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Reference</label>
                            <input type="text" name="reference" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Payment Method <span class="text-danger">*</span></label>
                            <select name="methods" class="form-select" required>
                                <option value="">-- Select Method --</option>
                                <option value="cash">Cash</option>
                                <option value="card">Card</option>
                                <option value="bank">Bank Transfer</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Payment Date <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="payment_date" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Attachment</label>
                            <input type="file" name="attachment" class="form-control">
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary" id="btnSubmitPayment">Confirm Payment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="ListpaymentModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ListpaymentModalLabel">{{ __('messages.payment_list') }} - <span
                            id="sale-id"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="list-payment-sale">
                        <div class="text-center p-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


@endsection

@push('scripts')
    <script>
        function formatForDatetimeLocal(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);

            const offset = date.getTimezoneOffset();

            const localDate = new Date(date.getTime() - offset * 60 * 1000);


            return localDate.toISOString().slice(0, 16);
        }

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
                ajax: "{{ route('sales.getData') }}",
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



        });

        $(document).on('click', '.list-payment-sale', function (e) {
            e.preventDefault();
            const saleId = $(this).data('id');
            const modal = new bootstrap.Modal(document.getElementById('ListpaymentModal'));
            $('#sale-id').text('Loading...');
            $('#list-payment-sale').html(`
                <div class="text-center p-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading payment data...</p>
                </div>`);
            modal.show();
            // Load data via AJAX
            $.ajax({
                url: `{{ url('admin/sales/listPayments') }}/${saleId}`,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        const sale = response.data;
                        const payments = sale.payments;
                        $('#sale-id').text(sale.reference);
                        const tableHtml = `
                            <table id="payment-table" class="table table-striped table-bordered w-100">
                                <thead>
                                    <tr>
                                        <th>{{ __('messages.reference') }}</th>
                                        <th>{{ __('messages.date') }}</th>
                                        <th>{{ __('messages.amount') }}</th>
                                        <th>{{ __('messages.method') }}</th>
                                        <th>{{ __('messages.status') }}</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>`;

                        $('#list-payment-sale').html(tableHtml);
                        $('#payment-table').DataTable({
                            data: payments,
                            columns: [{
                                data: 'reference'
                            },
                            {
                                data: 'paid_at',
                                render: function (data) {
                                    if (!data) return '-';
                                    const date = new Date(data);
                                    return date.toLocaleString('en-GB', {
                                        day: '2-digit',
                                        month: '2-digit',
                                        year: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    });
                                }
                            },
                            {
                                data: 'amount',
                                render: function (data) {
                                    return '$' + parseFloat(data).toLocaleString(
                                        'en-US', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    });
                                }
                            },
                            {
                                data: 'method'
                            },
                            {
                                data: null,
                                render: () =>
                                    `<span class="badge bg-success">Completed</span>`
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
                    } else {
                        $('#list-payment-sale').html(
                            '<div class="alert alert-warning">No record found.</div>');
                    }
                }
            });
        });



    </script>
@endpush
