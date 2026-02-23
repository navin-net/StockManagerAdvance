@extends('admin.layouts.master')

@section('title', __('messages.categories_list'))

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
                                    <!-- <h5 class="card-title mb-0 fw-semibold">{{ __('messages.Categoriess_list') }}</h5> -->
                                </div>
                                <div class="col-md-6 text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-primary btn-sm dropdown-toggle rounded-3" type="button"
                                            id="actionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-gear-fill me-1"></i> {{ __('messages.actions') }}
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm rounded-3"
                                            aria-labelledby="actionDropdown">
                                            <li><a class="dropdown-item" href="#" id="addCategoriesBtn">
                                                    <i class="bi bi-plus-circle me-2"></i>{{ __('messages.add') }}</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#" id="exportCategories">
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

                            <div id="alertsContainer" class="mb-4"></div>


                            <div class="table-responsive">
                                <table id="categoriesTable"
                                    class="table table-striped table-bordered rounded-3 align-middle">
                                    <thead class="table-primary">
                                        <tr>
                                            <th><input type="checkbox" id="selectAll" class="form-check-input"></th>
                                            <th>{{ __('messages.name') }}</th>
                                            <th>{{ __('messages.slug') }}</th>
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

        <!-- Add Categories Modal -->
        <div class="modal fade" id="addCategoriesModal" tabindex="-1" aria-hidden="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-3 border-0 shadow">
                    <div class="modal-header border-0 rounded-top-3">
                        <h5 class="modal-title fw-semibold" id="addCategoriesModalLabel">{{ __('messages.create') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="createCategoriesForm">
                        @csrf
                        <input type="hidden" name="category_id" id="category_id">
                        <input type="hidden" name="_method" id="method" value="POST">

                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-medium">{{ __('messages.name') }}</label>
                                <input type="text" class="form-control rounded-3" name="name" id="name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">{{ __('messages.slug') }}</label>
                                <input type="text" class="form-control rounded-3" name="slug" id="slug" required>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary btn-sm"
                                data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                            <button type="submit" id="saveBtn"
                                class="btn btn-primary btn-sm rounded-3">{{ __('messages.save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Edit Categories Modal --}}
        <div class="modal fade" id="editCategoriesModal" tabindex="-1" aria-hidden="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-3 border-0 shadow">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-semibold">{{ __('messages.edit') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="editCategoriesForm">
                        @csrf
                        @method('PUT') <input type="hidden" name="category_id" id="category_id">

                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-medium">{{ __('messages.name') }}</label>
                                <input type="text" class="form-control" name="name" id="name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-medium">{{ __('messages.slug') }}</label>
                                <input type="text" class="form-control" name="slug" id="slug" required>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary btn-sm"
                                data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                            <button type="submit" class="btn btn-primary btn-sm">{{ __('messages.update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteCategoriesModal" tabindex="-1" aria-labelledby="deleteCategoriesModalLabel"
            aria-hidden="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-3 border-0 shadow">
                    <div class="modal-header border-0  rounded-top-3">
                        <h5 class="modal-title fw-semibold" id="deleteCategoriesModalLabel">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this Categories?
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary btn-sm rounded-3"
                            data-bs-dismiss="modal">Cancel</button>
                        <form id="deleteCategoriesForm" method="POST">
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
        const BaseUrl = "/admin/system_settings/categories/";

        document.getElementById('name').addEventListener('input', function () {
            let nameValue = this.value;

            let slug = nameValue.toLowerCase()
                .trim()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');

            document.getElementById('slug').value = slug;
        });
        $(document).ready(function () {
            var table = $('#categoriesTable').DataTable({
                pageLength: 10,
                lengthMenu: [
                    [10, 20, 30, 50, -1],
                    [10, 20, 30, 50, "{{ __('messages.all') }}"]
                ],
                buttons: [],
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('categories.index') }}",
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
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'slug',
                    name: 'slug'
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

            $('#addCategoriesBtn').click(function () {
                $('#createCategoriesForm')[0].reset();
                $('#addCategoriesModal').modal('show');
            });
            // Create Categories
            $('#createCategoriesForm').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('categories.store') }}",
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        $('#addCategoriesModal').modal('hide');
                        table.ajax.reload();
                        $('#alertsContainer').html(`
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                ${response.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>`);
                    },
                    error: function (response) {
                        alert('Error: ' + (response.responseJSON?.message || 'Unable to create'));
                    }
                });
            });

            $(document).on('click', '.editCategoryBtn', function () {
                let id = $(this).data('id');
                let editUrl = BaseUrl + id + "/edit";

                $.get(editUrl, function (data) {

                    $('#category_id').val(data.id);

                    $('#editCategoriesModal #name').val(data.name);
                    $('#editCategoriesModal #slug').val(data.slug);

                    $('#editCategoriesModal').modal('show');
                }).fail(function () {
                    alert("Could not fetch data. Please check the console.");
                });
            });

            $('#editCategoriesModal #name').on('input', function () {
                let slug = $(this).val().toLowerCase()
                    .trim()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/[\s_-]+/g, '-')
                    .replace(/^-+|-+$/g, '');
                $('#editCategoriesModal #slug').val(slug);
            });

            $('#editCategoriesForm').on('submit', function (e) {
                e.preventDefault();
                let id = $('#category_id').val();

                let updateUrl = BaseUrl + id;

                $.ajax({
                    url: updateUrl,
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        $('#editCategoriesModal').modal('hide');
                        table.ajax.reload();
                        $('#alertsContainer').html(`
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                ${response.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>`);
                    },
                    error: function (xhr) {
                        let errors = xhr.responseJSON.errors;
                        console.log(errors);
                        alert('Update failed. Check console for details.');
                    }
                });
            });

            $(document).on('click', '.deleteCategories', function () {
                var id = $(this).data('id');
                $('#deleteCategoriesModal').modal('show');
                $('#deleteCategoriesForm').data('id', id); // store id
            });

            $('#deleteCategoriesForm').submit(function (e) {
                e.preventDefault();
                var id = $(this).data('id');

                $.ajax({
                    url: '/admin/system_settings/categories/' + id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        $('#deleteCategoriesModal').modal('hide');
                        table.ajax.reload();
                        const successAlert = `
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            ${response.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`;
                        $('#alertsContainer').html(successAlert);
                    },
                    error: function (xhr) {
                        $('#deleteCategoriesModal').modal('hide');
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





        });
    </script>
@endpush
