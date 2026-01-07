@extends('layouts.master')

@section('title', 'Subcategory List')

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

        {{-- <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="fw-semibold"></h5>
                    <div class="dropdown">
                        <button class="btn btn-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-gear-fill me-1"></i> Actions
                        </button>
                        <ul class="dropdown-menu shadow-sm">
                            <li><a class="dropdown-item" id="addSubCategoryBtn">{{ __('messages.add') }}</a></li>
                            <li><a class="dropdown-item" id="bulkDeleteBtn" disabled>{{ __('messages.delete') }} </a></li>
                        </ul>
                    </div>
                </div>

                <div id="alertsContainer"></div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="subCategoriesTable">
                        <thead class="table-primary">
                            <tr>
                                <th scope="col" ><input type="checkbox" id="selectAll"></th>
                                <th>{{ __('messages.category') }}</th>
                                <th>{{ __('messages.name') }}</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div> --}}
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

                                                <li><a class="dropdown-item" href="#"
                                                        id="addSubCategoryBtn">
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
                                <table id="subCategoriesTable" class="table table-striped table-bordered rounded-3 align-middle">
                                    <thead class="table-primary">
                                        <tr>
                                            <th><input type="checkbox" id="selectAll"></th>
                                            <th>{{ __('messages.name') }}</th>
                                            <th>{{ __('messages.code') }}</th>
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
        <div class="modal fade" id="addSubCategoryModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content border-0 shadow">
                    <form id="createSubCategoryForm">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Create Subcategory</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Subcategory Name</label>
                                <input type="text" class="form-control" name="name" id="name" required>
                                <div class="invalid-feedback" id="name_error"></div>
                            </div>
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                <select class="form-select" name="category_id" id="category_id" required>
                                    <option value="">-- Select Category --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="category_id_error"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary btn-sm">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editSubCategoryModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content border-0 shadow">
                    <form id="editSubCategoryForm">
                        @csrf
                        <input type="hidden" id="edit_id" name="id">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Subcategory</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="edit_name" class="form-label">Subcategory Name</label>
                                <input type="text" class="form-control" name="name" id="edit_name" required>
                                <div class="invalid-feedback" id="edit_name_error"></div>
                            </div>
                            <div class="mb-3">
                                <label for="edit_category_id" class="form-label">Category</label>
                                <select class="form-select" name="category_id" id="edit_category_id" required>
                                    <option value="">-- Select Category --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="edit_category_id_error"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-sm"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const imageBaseUrl = "{{ asset('/storage/images/') }}";
        const noimage = "{{ asset('noimage.png') }}";
        $(document).ready(function() {
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
                        render: function(data) {
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







            $('#bulkDeleteBtn').on('click', function() {
                var selectedIds = $('.Checkbox:checked').map(function() { return $(this).val(); }).get();

                if (selectedIds.length > 0) {
                    $('#bulkDeleteModal .modal-body').text(
                        `Are you sure you want to delete ${selectedIds.length} selected group(s)?`
                    );
                    $('#bulkDeleteModal').modal('show');

                    $('#confirmBulkDeleteBtn').off('click').on('click', function() {
                        $.ajax({
                            url: "{{ route('groups.bulkDelete') }}",
                            method: 'POST',
                            data: { ids: selectedIds },
                            success: function(response) {
                                $('#bulkDeleteModal').modal('hide');
                                table.ajax.reload();
                                $('#alertsContainer').html(`
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        ${response.success || 'Selected group(s) deleted successfully!'}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                `);
                            },
                            error: function(response) {
                                alert('Error: ' + (response.responseJSON?.message || 'Unable to delete'));
                            }
                        });
                    });
                } else {
                    // alert('Please select at least one group.');
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
