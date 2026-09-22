@extends('admin.layouts.master')

@section('title', __('messages.qualitys_list'))

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
                                    <!-- <h5 class="card-title mb-0 fw-semibold">{{ __('messages.qualitys_list') }}</h5> -->
                                </div>
                                <div class="col-md-6 text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-primary btn-sm dropdown-toggle rounded-3" type="button"
                                            id="actionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-gear-fill me-1"></i> {{ __('messages.actions') }}
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm rounded-3"
                                            aria-labelledby="actionDropdown">
                                            <li><a class="dropdown-item" href="#" id="addQualitysBtn">
                                                    <i class="bi bi-plus-circle me-2"></i>{{ __('messages.add') }}</a>
                                            </li>
                                            <li><a class="dropdown-item" href="#" id="exportqualitys">
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
                                <table id="qualitysTable" class="table table-striped table-bordered rounded-3 align-middle">
                                    <thead class="table-primary">
                                        <tr>
                                            <th><input type="checkbox" id="selectAll" class="form-check-input"></th>
                                            <th>{{ __('messages.name') }}</th>
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

        <!-- Edit Quality Modal -->
        <div class="modal fade" id="editQualityModal" tabindex="-1" aria-labelledby="editQualityModalLabel" aria-hidden="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-3 border-0 shadow">
                    <div class="modal-header border-0 rounded-top-3">
                        <h5 class="modal-title fw-semibold" id="editQualityModalLabel">Edit Quality</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="editQualityForm">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="editName" class="form-label fw-medium">Quality Name</label>
                                <input type="text" class="form-control rounded-3" name="name" id="editName" required>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary btn-sm rounded-3"
                                    data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary btn-sm rounded-3">Update Quality</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteQualityModal" tabindex="-1" aria-labelledby="deleteQualityModalLabel"
             aria-hidden="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-3 border-0 shadow">
                    <div class="modal-header border-0  rounded-top-3">
                        <h5 class="modal-title fw-semibold" id="deleteQualityModalLabel">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this Quality?
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary btn-sm rounded-3"
                                data-bs-dismiss="modal">Cancel</button>
                        <form id="deleteQualityForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm rounded-3">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="bulkDeleteModal" tabindex="-1" aria-labelledby="bulkDeleteModalLabel"
             aria-hidden="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-3 border-0 shadow">
                    <div class="modal-header border-0 rounded-top-3">
                        <h5 class="modal-title fw-semibold" id="bulkDeleteModalLabel">Confirm Bulk Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete the selected Qualitys?
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary btn-sm rounded-3"
                                data-bs-dismiss="modal">Cancel</button>
                        <button type="button" id="confirmBulkDeleteBtn"
                                class="btn btn-danger btn-sm rounded-3">Delete</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addQualityModal" tabindex="-1" aria-labelledby="addQualityModalLabel" aria-hidden="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-3 border-0 shadow">
                    <div class="modal-header border-0 rounded-top-3">
                        <h5 class="modal-title fw-semibold" id="addQualityModalLabel">Create Quality</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="createQualityForm">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name" class="form-label fw-medium">Quality Name</label>
                                <input type="text" class="form-control rounded-3" name="name" id="name" required>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary btn-sm rounded-3"
                                    data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary btn-sm rounded-3">Save Quality</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>



    </div>
@endsection
@push('scripts')
<script>
        $(document).ready(function() {
            var table = $('#qualitysTable').DataTable({
                pageLength: 10,
                lengthMenu: [
                    [10, 20, 30, 50, -1],
                    [10, 20, 30, 50, "{{ __('messages.all') }}"]
                ],
                buttons: [],
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('qualitys.index') }}",
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
                        data: 'name',
                        name: 'name'
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


            $('#addQualitysBtn').click(function() {
                $('#createQualityForm')[0].reset();
                $('#addQualityModal').modal('show');
            });
            // Create Quality
            $('#createQualityForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('qualitys.store') }}",
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#addQualityModal').modal('hide');
                        table.ajax.reload();
                        $('#alertsContainer').html(`
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                Quality added successfully!
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `);
                    },
                    error: function(response) {
                        alert('Error: ' + (response.responseJSON?.message || 'Unable to create'));
                    }
                });
            });

            const BaseUrl = "/admin/system_settings/qualitys/";

            // Edit Qualitys (open modal)
            $(document).on('click', '.editQuality', function() {
                const id = $(this).data('id');
                $.get(BaseUrl + id + "/edit", function(data) {

                    $('#editQualityModal').modal('show');
                    $('#editName').val(data.quality.name);
                    $('#editQualityForm').attr('data-id', id);
                }).fail(function() {
                    alert('Unable to fetch Quality details.');
                });
            });
            // Update Qualitys
            $('#editQualityForm').submit(function(e) {
                e.preventDefault();
                const id = $(this).attr('data-id');
                $.ajax({
                    url: BaseUrl + id,
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#editQualityModal').modal('hide');
                        table.ajax.reload();
                        $('#alertsContainer').html(`
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                ${response.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `);
                    },
                    error: function(response) {
                        alert('Error: ' + (response.responseJSON?.message || 'Unable to update'));
                    }
                });
            });


            $(document).on('click', '.deleteQuality', function() {
                var id = $(this).data('id');
                $('#deleteQualityForm').attr('action', BaseUrl + id);
                $('#deleteQualityModal').modal('show');
            });

            $('#deleteQualityForm').submit(function(e) {
                e.preventDefault();
                var id = $(this).attr('action').split('/').pop();
                $.ajax({
                    url: BaseUrl + id,
                    method: 'DELETE',
                    success: function(response) {
                        $('#deleteQualityModal').modal('hide');
                        table.ajax.reload();
                        const successAlert = `
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                Quality Delete successfully!
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>`;
                        $('#alertsContainer').html(successAlert);
                    },
                    error: function(response) {
                        alert('Error: ' + response.responseJSON.message);
                    }
                });
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
