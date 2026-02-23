@extends('admin.layouts.master')

@section('title', __('messages.warehouse_list'))

@section('content')

    <div class="container-fluid py-4">
        <!-- Page title & breadcrumbs -->
        <div class="pagetitle mb-4">
            <h1 class="display-6 fw-bold">{{ $pageTitle }}</h1>
            <nav>
                <ol class="breadcrumb rounded-3 p-2">
                    @foreach ($breadcrumbs as $breadcrumb)
                        <li class="breadcrumb-item {{ $breadcrumb['active'] ? 'active text-muted' : '' }}">
                            @if (!$breadcrumb['active'])
                                <a href="{{ $breadcrumb['url'] }}" class="text-primary text-decoration-none">
                                    {{ $breadcrumb['label'] }}
                                </a>
                            @else
                                {{ $breadcrumb['label'] }}
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>
        </div>

        <!-- Main content section -->
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body p-4">
                            <!-- Actions -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="card-title mb-0 fw-semibold"></h5>
                                <div class="dropdown">
                                    <button class="btn btn-primary btn-sm dropdown-toggle rounded-3" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-gear-fill me-1"></i> Actions
                                    </button>
                                    <ul class="dropdown-menu shadow-sm rounded-3">
                                        <li><a class="dropdown-item" href="#"
                                                id="addWarehouseBtn">{{ __('messages.add') }}</a></li>
                                        <li><a class="dropdown-item" href="#" id="bulkDeleteBtn"
                                                disabled>{{ __('messages.delete') }}</a></li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Alerts -->
                            <div id="alertsContainer" class="mb-4">
                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show auto-hide">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif
                                @if (session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show auto-hide">
                                        {{ session('error') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif
                            </div>
                            <!-- Table -->
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered rounded-3 align-middle"
                                    id="warehousesTable">
                                    <thead class="table-primary">
                                        <tr>
                                            <th class="py-3 "><input type="checkbox" id="selectAll"></th>
                                            <th class="py-3">Name</th>
                                            <th class="py-3">Location</th>
                                            <th class="py-3">Note</th>
                                            <th class="py-3 text-center">Actions</th>
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

        <!-- Add Warehouse Modal -->
        <div class="modal fade" id="addWarehouseModal" tabindex="-1" aria-labelledby="addWarehouseModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-3 border-0 shadow">
                    <div class="modal-header border-0 rounded-top-3">
                        <h5 class="modal-title fw-semibold" id="addWarehouseModalLabel">Create Warehouse</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="createWarehouseForm">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name" class="form-label fw-medium">Warehouse Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control rounded-3" name="name" id="name" required>
                                <span id="name-error" class="invalid-feedback"></span>
                            </div>
                            <div class="mb-3">
                                <label for="location" class="form-label fw-medium">Location</label>
                                <input type="text" class="form-control rounded-3" name="location" id="location">
                                <span id="location-error" class="invalid-feedback"></span>
                            </div>
                            <div class="mb-3">
                                <label for="note" class="form-label fw-medium">Note</label>
                                <textarea class="form-control rounded-3" name="note" id="note" rows="3"></textarea>
                                <span id="note-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary btn-sm rounded-3"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary btn-sm rounded-3">Save Warehouse</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Warehouse Modal -->
        <div class="modal fade" id="editWarehouseModal" tabindex="-1" aria-labelledby="editWarehouseModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-3 border-0 shadow">
                    <div class="modal-header border-0 rounded-top-3">
                        <h5 class="modal-title fw-semibold" id="editWarehouseModalLabel">Edit Warehouse</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="editWarehouseForm">
                        @csrf
                        <input type="hidden" name="_method" id="method" value="PUT">
                        <input type="hidden" name="warehouse_id" id="warehouse_id">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="editName" class="form-label fw-medium">Warehouse Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control rounded-3" name="name" id="editName" required>
                                <span id="editName-error" class="invalid-feedback"></span>
                            </div>
                            <div class="mb-3">
                                <label for="editLocation" class="form-label fw-medium">Location</label>
                                <input type="text" class="form-control rounded-3" name="location" id="editLocation">
                                <span id="editLocation-error" class="invalid-feedback"></span>
                            </div>
                            <div class="mb-3">
                                <label for="editNote" class="form-label fw-medium">Note</label>
                                <textarea class="form-control rounded-3" name="note" id="editNote" rows="3"></textarea>
                                <span id="editNote-error" class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary btn-sm rounded-3"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary btn-sm rounded-3">Update Warehouse</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteWarehouseModal" tabindex="-1" aria-labelledby="deleteWarehouseModalLabel"
            aria-hidden="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-3 border-0 shadow">
                    <div class="modal-header border-0  rounded-top-3">
                        <h5 class="modal-title fw-semibold" id="deleteWarehouseModalLabel">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this Warehouse?
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary btn-sm rounded-3"
                            data-bs-dismiss="modal">Cancel</button>
                        <form id="deleteWarehouseForm" method="POST">
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
        $(document).ready(function () {
            const BaseUrl = "/admin/system_settings/warehouse/";
            var table = $('#warehousesTable').DataTable({
                pageLength: 10,
                lengthMenu: [
                    [10, 20, 30, 50, -1],
                    [10, 20, 30, 50, "{{ __('messages.all') }}"]
                ],
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('warehouse.index') }}",
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                        render: function (data) {
                            return `<input type="checkbox" class="checkbox" value="${data}">`;
                        },
                        orderable: false,
                        searchable: false
                        // className: 'text-center'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'location',
                        name: 'location'
                    },
                    {
                        data: 'note',
                        name: 'note'
                    },
                    {
                        data: 'action',
                        name: 'action',
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
                    lengthMenu: '{{ __('messages.show') }} _MENU_ {{ __('messages.entries') }}',
                    search: '{{ __('messages.search') }}',
                    emptyTable: "{{ __('messages.no_data_available') }}",
                    processing: "{{ __('messages.processing') }}",
                    zeroRecords: "{{ __('messages.no_matching_records') }}",
                    infoEmpty: "{{ __('messages.showing_0_to_0_of_0_entries') }}",
                    infoFiltered: "{{ __('messages.filtered_from_total_entries', ['total' => '_MAX_']) }}"
                }
            });
            function toggleBulkDeleteButton() {
                const anyChecked = $('.checkbox:checked').length > 0;
                $('#bulkDeleteBtn').prop('disabled', !anyChecked);
            }
            $('#addWarehouseBtn').click(function () {
                $('#createWarehouseForm')[0].reset();
                $('#addWarehouseModal').modal('show');
            });
            $('#createWarehouseForm').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('warehouse.store') }}",
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        $('#addWarehouseModal').modal('hide');
                        table.ajax.reload();
                        $('#alertsContainer').html(`
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                Warehouse added successfully!
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `);
                    },
                    error: function (response) {
                        alert('Error: ' + (response.responseJSON?.message || 'Unable to create'));
                    }
                });
            });
            $(document).on('click', '.editWarehouse', function () {
                const id = $(this).data('id');
                $.get(BaseUrl + id + "/edit", function (data) {

                    $('#editWarehouseModal').modal('show');
                    $('#editName').val(data.warehouse.name);
                    $('#editLocation').val(data.warehouse.location);
                    $('#editNote').val(data.warehouse.note);

                    $('#editWarehouseForm').attr('data-id', id);
                }).fail(function () {
                    alert('Unable to fetch warehouse details.');
                });
            });
            $('#editWarehouseForm').submit(function (e) {
                e.preventDefault();
                const id = $(this).attr('data-id');
                $.ajax({
                    url: BaseUrl + id,
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        $('#editWarehouseModal').modal('hide');
                        table.ajax.reload();
                        $('#alertsContainer').html(`
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    ${response.message}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            `);
                    },
                    error: function (response) {
                        alert('Error: ' + (response.responseJSON?.message || 'Unable to update'));
                    }
                });
            });
            $(document).on('click', '.deleteWarehouse', function() {
                var id = $(this).data('id');
                $('#deleteWarehouseForm').attr('action', BaseUrl + id);
                $('#deleteWarehouseModal').modal('show');
            });
            $('#deleteWarehouseForm').submit(function(e) {
                e.preventDefault();
                var id = $(this).attr('action').split('/').pop();
                $.ajax({
                    url: BaseUrl + id,
                    method: 'DELETE',
                    success: function(response) {
                        $('#deleteWarehouseModal').modal('hide');
                        table.ajax.reload();
                        const successAlert = `
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                ${response.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>`;
                        $('#alertsContainer').html(successAlert);
                    },
                    error: function(response) {
                        alert('Error: ' + response.responseJSON.message);
                    }
                });
            });



        });
    </script>
@endpush
