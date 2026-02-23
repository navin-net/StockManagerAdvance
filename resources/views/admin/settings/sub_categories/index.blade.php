@extends('admin.layouts.master')

@section('title', 'Subcategory List')

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
                                    <h5 class="card-title mb-0 fw-semibold"></h5>
                                </div>
                                <div class="col-md-6 text-end">
                                    @if (Auth::user()->group_id == 1)
                                        <div class="dropdown">
                                            <button class="btn btn-primary btn-sm dropdown-toggle rounded-3" type="button"
                                                id="actionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-gear-fill me-1"></i> {{ __('messages.actions') }}
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm rounded-3"
                                                aria-labelledby="actionDropdown">

                                                <li><a class="dropdown-item" href="#" id="addSubCategoryBtn">
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

                            <div id="alertsContainer" class="mb-4"></div>



                            <div class="table-responsive">
                                <table id="subCategoriesTable"
                                    class="table table-striped table-bordered rounded-3 align-middle">
                                    <thead class="table-primary">
                                        <tr>
                                            <th><input type="checkbox" id="selectAll" class="form-check-input"></th>
                                            <th>{{ __('messages.category') }}</th>
                                            <th>{{ __('messages.name') }}</th>
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
        {{-- CREATE MODAL --}}
        <div class="modal fade" id="addSubCategoryModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content border-0 shadow">
                    <form id="createSubCategoryForm">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('messages.create') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">{{ __('messages.name') }}</label>
                                <input type="text" class="form-control" name="name" id="name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ __('messages.category') }}</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">-- {{ __('messages.select_category') }} --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-sm">{{ __('messages.save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- EDIT MODAL --}}
        <div class="modal fade" id="editSubCategoryModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content border-0 shadow">
                    <form id="editSubCategoryForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_id">

                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('messages.edit') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">{{ __('messages.name') }}</label>
                                <input type="text" id="edit_name" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ __('messages.category') }}</label>
                                <select id="edit_category_id" name="category_id" class="form-select" required>
                                    <option value="">-- {{ __('messages.select_category') }} --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-sm">{{ __('messages.update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>



        <div class="modal fade" id="deleteSubCategoryModal" tabindex="-1" aria-labelledby="deleteSubCategoryModalLabel"
            aria-hidden="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-3 border-0 shadow">
                    <div class="modal-header border-0  rounded-top-3">
                        <h5 class="modal-title fw-semibold" id="deleteSubCategoryModalLabel">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this SubCategory
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary btn-sm rounded-3"
                            data-bs-dismiss="modal">Cancel</button>
                        <form id="deleteSubCategoryForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm rounded-3">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection

@push('scripts')
    <script>
        const BaseUrl = "/admin/system_settings/sub_category/";

        $(document).ready(function () {
            var table = $('#subCategoriesTable').DataTable({
                pageLength: 10,
                lengthMenu: [
                    [10, 20, 30, 50, -1],
                    [10, 20, 30, 50, "{{ __('messages.all') }}"]
                ],
                buttons: [],
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('sub_category.index') }}",

                columns: [{
                    data: 'id',
                    name: 'id',
                    render: function (data) {
                        return `<input type="checkbox" class="Checkbox" value="${data}">`;
                    },
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'category_name',
                    name: 'category_name'
                },
                {
                    data: 'sub_category_name',
                    name: 'sub_category_name'
                },
                {
                    data: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
                ],
                language: {
                    paginate: {
                        previous: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/></svg>',
                        next: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/></svg>'
                    },
                    // info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                    lengthMenu: '{{ __('messages.show') }} _MENU_{{ __('messages.entries') }}',
                    search: '{{ __('messages.search') }}',
                    emptyTable: "{{ __('messages.no_data_available') }}",
                    processing: "{{ __('messages.processing') }}",
                    zeroRecords: "{{ __('messages.no_matching_records') }}",
                    infoEmpty: "{{ __('messages.showing_0_to_0_of_0_entries') }}",
                    infoFiltered: "{{ __('messages.filtered_from_total_entries', ['total' => '_MAX_']) }}"
                }
            });


            $('#addSubCategoryBtn').click(function () {
                $('#addSubCategoryModal').modal('show');
            });

            $('#createSubCategoryForm').submit(function (e) {
                e.preventDefault();
                $.post(BaseUrl, $(this).serialize(), function (res) {
                    $('#addSubCategoryModal').modal('hide');
                    table.ajax.reload();
                });
            });

            // EDIT
            $(document).on('click', '.editSubCategorybtn', function () {
                let id = $(this).data('id');

                $.get(BaseUrl + id + '/edit', function (data) {
                    $('#edit_id').val(data.id);
                    $('#edit_name').val(data.name);
                    $('#edit_category_id').val(data.category_id);

                    $('#editSubCategoryModal').modal('show');
                });
            });

            $('#editSubCategoryForm').submit(function (e) {
                e.preventDefault();
                let id = $('#edit_id').val();

                $.ajax({
                    url: BaseUrl + id,
                    method: 'PUT',
                    data: $(this).serialize(),
                    success: function (res) {
                        $('#editSubCategoryModal').modal('hide');
                        table.ajax.reload();

                    },
                    error: function (xhr) {
                        console.log(xhr.responseJSON);

                    }
                });
            });


            $(document).on('click', '.deleteSubCategory', function () {
                var id = $(this).data('id');
                $('#deleteSubCategoryModal').modal('show');
                $('#deleteSubCategoryForm').data('id', id); // store id
            });
            $('#deleteSubCategoryForm').submit(function (e) {
                e.preventDefault();
                var id = $(this).data('id');

                $.ajax({
                    url: '/admin/system_settings/sub_category/' + id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        $('#deleteSubCategoryModal').modal('hide');
                        table.ajax.reload();
                        const successAlert = `
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            ${response.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`;
                        $('#alertsContainer').html(successAlert);
                    },
                    error: function (xhr) {
                        $('#deleteSubCategoryModal').modal('hide');
                        table.ajax.reload();
                        const message = xhr.responseJSON?.message || 'Something went wrong';
                        const errorAlert = `
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                ${message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>`;
                        $('#alertsContainer').html(errorAlert);
                    }
                });

            });




            $('#bulkDeleteBtn').on('click', function () {
                var selectedIds = $('.Checkbox:checked').map(function () { return $(this).val(); }).get();

                if (selectedIds.length > 0) {
                    $('#bulkDeleteModal .modal-body').text(
                        `Are you sure you want to delete ${selectedIds.length} selected group(s)?`
                    );
                    $('#bulkDeleteModal').modal('show');

                    $('#confirmBulkDeleteBtn').off('click').on('click', function () {
                        $.ajax({
                            url: "{{ route('groups.bulkDelete') }}",
                            method: 'POST',
                            data: { ids: selectedIds },
                            success: function (response) {
                                $('#bulkDeleteModal').modal('hide');
                                table.ajax.reload();
                                $('#alertsContainer').html(`
                                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                ${response.success || 'Selected group(s) deleted successfully!'}
                                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                            </div>
                                        `);
                            },
                        });
                    });
                } else {

                    $('#alertsContainer').html(`
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    Please select at least one group.
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            `);
                }
            });
        });
    </script>
@endpush
