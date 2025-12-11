@extends('layouts.master')

@section('title', __('messages.sales_list'))

@section('content')
    <div class="container-fluid py-4">
        <div class="pagetitle mb-4">
            <h1 class="display-6 fw-bold">{{ $pageTitle }}</h1>
            <nav>
                <ol class="breadcrumb rounded-3 p-2">
                    @foreach ($breadcrumbs as $breadcrumb)
                        <li class="breadcrumb-item {{ $breadcrumb['active'] ? 'active text-muted' : '' }}">
                            @if (!$breadcrumb['active'])
                                <a href="{{ $breadcrumb['url'] }}"
                                    class="text-primary text-decoration-none">{{ $breadcrumb['label'] }}</a>
                            @else
                                {{ $breadcrumb['label'] }}
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>
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
                                            <th>{{ __('messages.total_amount') }}</th>
                                            <th>{{ __('messages.customer') }}</th>
                                            <th>{{ __('messages.date') }}</th>
                                            <th>{{ __('messages.item_count') }}</th>
                                            <th>{{ __('messages.total_quantity') }}</th>
                                            <th scope="col" class="py-3 text-center" width="120">
                                                {{ __('messages.actions') }}</th>
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
                        {{ __('messages.confirm_bulk_delete') }}</h5>
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
    <!-- Show Sale Modal -->
    {{-- <div class="modal fade" id="paymentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body" id="payment-body">
                    <div class="text-center p-3">Loading...</div>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="modal fade" id="paymentModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-3 border-0 shadow">
                <div class="modal-header border-0 rounded-top-1">
                    <h5 class="modal-title fw-semibold" id="paymentModalLabel">Process Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" id="payment-body">
                    <div class="text-center p-3">Loading...</div>
                </div>
            </div>
        </div>
    </div>











@endsection

@push('scripts')
    <script>
        let storePaymentUrl = "{{ route('sales.payment.store') }}"; // POST

        $(document).ready(function() {
            var table = $('#salesTable').DataTable({
                pageLength: 10,
                lengthMenu: [
                    [10, 20, 30, 50, -1],
                    [10, 20, 30, 50, "{{ __('messages.all') }}"]
                ],
                buttons: [],
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('sales.getData') }}",
                columns: [{
                        data: 'id',
                        name: 'id',
                        render: function(data) {
                            return `<input type="checkbox" class="saleCheckbox" value="${data}">`;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount',
                        render: $.fn.dataTable.render.number(',', '.', 2)
                    },
                    {
                        data: 'customer_id',
                        name: 'customer_id',
                        defaultContent: 'N/A',

                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'items_count',
                        name: 'items_count',

                    },
                    {
                        data: 'items_sum_quantity',
                        name: 'items_sum_quantity',

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
                        previous: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/></svg>',
                        next: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/></svg>'
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

            $(document).on('click', '.show-sale', function() {
                const id = $(this).data('id');
                $.ajax({
                    url: '{{ route('sales.show', ['id' => ':id']) }}'.replace(':id', id),
                    method: 'GET',
                    success: function(response) {
                        if (response.error) {
                            $('#alertsContainer').html(
                                `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    ${response.error}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>`
                            );
                            return;
                        }
                        const sale = response.sale;
                        $('#show-id').text(sale.id || 'N/A');
                        $('#show-total_amount').text((sale.total_amount || 0).toFixed(2));
                        $('#show-status').text(sale.status || 'N/A');
                        $('#show-date').text(sale.date || 'N/A');
                        let itemsHtml = '';
                        (sale.items || []).forEach(item => {
                            itemsHtml += `
                                <tr>
                                    <td>${item.product?.name || 'N/A'} (code: ${item.product?.code || 'N/A'})</td>
                                    <td>${item.quantity || 0}</td>
                                    <td>${(item.sale_price || 0).toFixed(2)}</td>
                                </tr>
                            `;
                        });
                        $('#show-items').html(itemsHtml ||
                            '<tr><td colspan="3">No items</td></tr>');
                        $('#showSaleModal').modal('show');
                    },
                    error: function(xhr) {
                        let errorMsg = 'Failed to load sale details. Please try again.';
                        if (xhr.status === 404) errorMsg = 'Sale not found.';
                        else if (xhr.status === 500) errorMsg = 'Server error occurred.';
                        $('#alertsContainer').html(
                            `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                ${errorMsg}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>`
                        );
                    }
                });
            });



            // -----------------------
            // 1. Open Payment Modal
            // -----------------------
            $(document).on("click", ".payment-sale", function(e) {
                e.preventDefault();

                let id = $(this).data("id");

                // Reset modal content and Header
                $('#payment-body').html('<div class="text-center p-3">Loading...</div>');
                $('#paymentModalLabel').text('Loading...'); // Optional: reset header while loading

                $.ajax({
                    url: 'sales/' + 'payments/' + id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        let sale = res.sale;

                        $('#paymentModalLabel').text('Payment for ' + (sale.reference ||'Sale'));
                        let paymentFormHtml = `
                        <div style="padding: 20px;">
                            <form id="paymentForm">
                                <input type="hidden" name="sale_id" value="${sale.id}">
                                <div class="mb-2">
                                    <label>Amount</label>
                                    <input type="number" name="amount" value="${sale.total_amount}" class="form-control" required>
                                </div>
                                <div class="mb-2">
                                    <label>Payment Method</label>
                                    <select name="method" id="paymentMethod" class="form-select" required>
                                        <option value="">Select Method</option>
                                        <option value="Cash">Cash</option>
                                        <option value="Card">Card</option>
                                        <option value="Transfer">Bank Transfer</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label>Payment Date</label>
                                    <input type="date" name="date" class="form-control" value="${new Date().toISOString().split('T')[0]}" required>
                                </div>
                                <button class="btn btn-primary col-12">Save</button>
                            </form>
                        </div>
                        `;
                        $('#payment-body').html(paymentFormHtml);
                        $('#paymentModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                        $('#paymentModalLabel').text('Error'); // Set header to Error
                        $('#payment-body').html(
                            '<div class="text-danger p-3">Failed to load payment form. Please try again.</div>'
                        );
                    }
                });
            }); // -----------------------
            // 2. Submit Payment Form
            // -----------------------
            $(document).on('submit', '#paymentForm', function(e) {
                e.preventDefault(); // important! prevents the form from doing a normal GET submit

                let form = $(this);

                $.ajax({
                    url: storePaymentUrl, // match your POST route
                    type: 'POST', // must be POST
                    data: form.serialize(),
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(res) {
                        Swal.fire({
                            icon: 'success',
                            title: res.message,
                            timer: 1200,
                            showConfirmButton: false
                        });
                        $('#paymentModal').modal('hide');
                    },
                    error: function(xhr) {
                        console.log(xhr.responseJSON);
                    }
                });
            });






        });
    </script>
@endpush
