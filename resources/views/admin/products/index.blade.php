@extends('layouts.master')
@section('title', __('messages.products_list'))
@section('content')
    <div class="container-fluid">
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
                                    <h5 class="card-title mb-0 fw-semibold"></h5>
                                </div>
                                <div class="col-md-6 text-end">
                                    @if (Auth::user()->group_id == 1)
                                        <div class="dropdown">
                                            <button class="btn btn-primary btn-sm dropdown-toggle rounded-3" type="button"
                                                id="actionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                {{ __('messages.actions') }}
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm rounded-3"
                                                aria-labelledby="actionDropdown">

                                                <li><a class="dropdown-item" href="{{ url('admin/products/create') }}"
                                                        id="addProductBtn">
                                                        <i class="bi bi-plus-circle me-2"></i>{{ __('messages.add') }}</a>
                                                </li>
                                                <li><a class="dropdown-item" href="#" id="exportProducts">
                                                        <i
                                                            class="bi bi-file-excel me-2"></i>{{ __('messages.export_to_excel') }}</a>
                                                </li>
                                                <li><a class="dropdown-item" href="#" id="bulkDeleteBtn" disabled>
                                                        <i class="bi bi-trash me-2"></i>{{ __('messages.delete') }}</a>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif

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
                                <table id="productsTable" class="table table-striped table-bordered rounded-3 align-middle">
                                    <thead class="table-primary">
                                        <tr>
                                            <th><input type="checkbox" id="selectAll"></th>
                                            <th>{{ __('messages.image') }}</th>
                                            <th>{{ __('messages.name') }}</th>
                                            <th>{{ __('messages.code') }}</th>
                                            <th>{{ __('messages.brand') }}</th>
                                            <th>{{ __('messages.category') }}</th>
                                            <th>{{ __('messages.subcategory') }}</th>
                                            <th>{{ __('messages.quality') }}</th>
                                            <th>{{ __('messages.unit') }}</th>
                                            <th>{{ __('messages.stock_quantity') }}</th>
                                            <th>{{ __('messages.cost_price') }}</th>
                                            <th>{{ __('messages.selling_price') }}</th>
                                            <th class="text-center">{{ __('messages.actions') }}</th>
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

        <!-- Image Modal -->
        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-3 border-0 shadow">
                    <div class="modal-body text-center">
                        <img id="modalImage" src="/placeholder.svg" alt="Product Image" class="img-fluid rounded-3">
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Product Modal -->
        <div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteProductModalLabel">{{ __('messages.delete_product') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        {{ __('messages.delete_confirm') }}
                    </div>
                    <div class="modal-footer">
                        <form id="deleteProductForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                            <button type="submit" class="btn btn-danger">{{ __('messages.delete') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let table = $('#productsTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,

                ajax: "{{ route('products.index') }}",

                pageLength: 10,
                lengthMenu: [
                    [10, 20, 30, 50, -1],
                    [10, 20, 30, 50, "{{ __('messages.all') }}"]
                ],

                columns: [{
                        data: 'id',
                        name: 'id',
                        orderable: false,
                        searchable: false,
                        render: data =>
                            `<input type="checkbox" class="ProductCheckbox" value="${data}">`
                    },
                    {
                        data: 'image',
                        name: 'image',
                        render: function(data) {

                            let imageUrl = data ?
                                `/storage/${data}` :
                                `/noimage.png`;

                            return `
                                <a href="#" class="image-popup" data-bs-toggle="modal" data-bs-target="#imageModal" data-image="${imageUrl}">
                                    <img src="${imageUrl}" width="50" class="img-thumbnail brand-image-thumbnail">
                                </a>`;
                        }
                    },

                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'brand_name',
                        name: 'brand_name',
                        defaultContent: 'N/A'
                    },
                    {
                        data: 'category_name',
                        name: 'category_name',
                        defaultContent: 'N/A'
                    },
                    {
                        data: 'subcategory_name',
                        name: 'subcategory_name',
                        defaultContent: 'N/A'
                    },
                    {
                        data: 'quality_name',
                        name: 'quality_name',
                        defaultContent: 'N/A'
                    },
                    {
                        data: 'unit_name',
                        name: 'unit_name'
                    },
                    {
                        data: 'stock_quantity',
                        name: 'stock_quantity'
                    },
                    {
                        data: 'cost_price',
                        name: 'cost_price'
                    },
                    {
                        data: 'selling_price',
                        name: 'selling_price'
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
                        previous:
                    `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646 a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6 a.5.5 0 0 1 .708 0z"/>
                    </svg>`,
                        next: `
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                     fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                    d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6
                       a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354
                       a.5.5 0 0 1 0-.708z"/>
                </svg>
            `
                    },
                    lengthMenu: '{{ __('messages.show') }} _MENU_ {{ __('messages.entries') }}',
                    search: '{{ __('messages.search') }}',
                    emptyTable: "{{ __('messages.no_data_available') }}",
                    processing: "{{ __('messages.processing') }}"
                }
            });

            $('#selectAll').on('click', function() {
                var isChecked = $(this).prop('checked');
                $('.ProductCheckbox').prop('checked', isChecked);
            });

            // Update modal image src
            $('#productsTable').on('click', '.image-popup', function(e) {
                e.preventDefault();
                const imageSrc = $(this).data('image');
                $('#modalImage').attr('src', imageSrc);
            });

            // Auto-dismiss alerts
            setTimeout(function() {
                $('#alertsContainer .alert').alert('close');
            }, 5000);

            // Delete product
            $(document).on('click', '.delete-product', function() {
                const productId = $(this).data('id');
                const deleteUrl = "{{ url('admin/products') }}/" + productId;
                $('#deleteProductForm').attr('action', deleteUrl);
                $('#deleteProductModal').modal('show');
            });

            $('#deleteProductForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const action = form.attr('action');

                $.ajax({
                    url: action,
                    type: 'DELETE',
                    data: form.serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#deleteProductModal').modal('hide');
                        table.ajax.reload();
                        const successAlert = `
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                ${response.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>`;
                        $('#alertsContainer').html(successAlert);
                        setTimeout(function() {
                            $('#alertsContainer .alert').alert('close');
                        }, 5000);
                    },
                    error: function(xhr) {
                        $('#deleteProductModal').modal('hide');

                        const errorMessage = xhr.responseJSON?.error || xhr.responseJSON
                            ?.message ||
                            'Failed to delete the product. Please try again.';

                        const errorAlert = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            ${errorMessage}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`;

                        $('#alertsContainer').html(errorAlert);

                        setTimeout(function() {
                            $('#alertsContainer .alert').alert('close');
                        }, 5000);
                    }

                });
            });

            $('#exportProducts').on('click', function() {
                var selectedIds = $('.ProductCheckbox:checked').map(function() {
                    return $(this).val();
                }).get();

                var url = "{{ route('products.export') }}";
                if (selectedIds.length > 0) {
                    url += '?ids=' + selectedIds.join(',');
                    window.location.href = url;
                } else {
                    const errorAlert = `
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ __('messages.please_select_someone_columns_first_if_you_want_to_export') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`;
                    $('#alertsContainer').html(errorAlert);
                }
            });
        });
    </script>
@endpush
