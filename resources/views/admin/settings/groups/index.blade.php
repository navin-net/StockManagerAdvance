@extends('layouts.master')

@section('title', __('messages.groups_list'))

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
                                        <li><a class="dropdown-item" id="addGroupBtn">{{ __('messages.add') }}</a></li>
                                        <li><a class="dropdown-item" id="bulkDeleteBtn"
                                                disabled>{{ __('messages.delete') }}</a></li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Alerts -->
                            <div id="alertsContainer" class="mb-4"></div>

                            <!-- Table -->
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered rounded-3 align-middle" id="groupsTable">
                                    <thead class="table-primary">
                                        <tr>
                                            <!-- <th ><input type="checkbox" id="selectAll"></th> -->
                                            <th scope="col" ><input type="checkbox" id="selectAll"></th>
                                            <th >Name</th>
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

        <!-- Add Group Modal -->
        <div class="modal fade" id="addGroupModal" tabindex="-1" aria-labelledby="addGroupModalLabel" aria-hidden="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-3 border-0 shadow">
                    <div class="modal-header border-0 rounded-top-3">
                        <h5 class="modal-title fw-semibold" id="addGroupModalLabel">Create Group</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="createGroupForm">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name" class="form-label fw-medium">Group Name</label>
                                <input type="text" class="form-control rounded-3" name="name" id="name" required>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary btn-sm rounded-3"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary btn-sm rounded-3">Save Group</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- Edit Group Modal -->
        <div class="modal fade" id="editGroupModal" tabindex="-1" aria-labelledby="editGroupModalLabel" aria-hidden="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-3 border-0 shadow">
                    <div class="modal-header border-0 rounded-top-3">
                        <h5 class="modal-title fw-semibold" id="editGroupModalLabel">Edit Group</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="editGroupForm">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="editName" class="form-label fw-medium">Group Name</label>
                                <input type="text" class="form-control rounded-3" name="name" id="editName" required>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary btn-sm rounded-3"
                                data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary btn-sm rounded-3">Update Group</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="modal fade" id="deleteGroupModal" tabindex="-1" aria-labelledby="deleteGroupModalLabel"
            aria-hidden="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-3 border-0 shadow">
                    <div class="modal-header border-0  rounded-top-3">
                        <h5 class="modal-title fw-semibold" id="deleteGroupModalLabel">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this Group?
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary btn-sm rounded-3"
                            data-bs-dismiss="modal">Cancel</button>
                        <form id="deleteGroupForm" method="POST">
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
                        Are you sure you want to delete the selected groups?
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




    </div>
@endsection

@push('scripts')
    <script>
    const imageBaseUrl = "{{ asset('/storage/images/') }}";
    const noimage = "{{ asset('noimage.png') }}";
        $(document).ready(function() {
            var table = $('#groupsTable').DataTable({
                pageLength: 10,
                lengthMenu: [
                    [10, 20, 30, 50, -1],
                    [10, 20, 30, 50, "{{ __('messages.all') }}"]
                ],
                buttons: [],
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('groups.index') }}",
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

            $('#addGroupBtn').click(function() {
                $('#createGroupForm')[0].reset();
                $('#addGroupModal').modal('show');
            });

            // Create Group
            $('#createGroupForm').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('groups.store') }}",
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#addGroupModal').modal('hide');
                        table.ajax.reload();
                        $('#alertsContainer').html(`
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                Group added successfully!
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `);
                    },
                    error: function(response) {
                        alert('Error: ' + (response.responseJSON?.message || 'Unable to create'));
                    }
                });
            });

            const BaseUrl = "/admin/system_settings/groups/";

            // Edit groups (open modal)
            $(document).on('click', '.editGroup', function() {
                const id = $(this).data('id');
                $.get(BaseUrl + id + "/edit", function(data) {

                    $('#editGroupModal').modal('show');
                    $('#editName').val(data.group.name);
                    $('#editGroupForm').attr('data-id', id);
                }).fail(function() {
                    alert('Unable to fetch group details.');
                });
            });
            // Update groups
            $('#editGroupForm').submit(function(e) {
                e.preventDefault();
                const id = $(this).attr('data-id');
                $.ajax({
                    url: BaseUrl + id,
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#editGroupModal').modal('hide');
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

            $(document).on('click', '.deleteGroup', function() {
                var id = $(this).data('id');
                $('#deleteGroupForm').attr('action', BaseUrl + id);
                $('#deleteGroupModal').modal('show');
            });

            $('#deleteGroupForm').submit(function(e) {
                e.preventDefault();
                var id = $(this).attr('action').split('/').pop();
                $.ajax({
                    url: BaseUrl + id,
                    method: 'DELETE',
                    success: function(response) {
                        $('#deleteGroupModal').modal('hide');
                        table.ajax.reload();
                        const successAlert = `
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                Group Delete successfully!
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

            $(document).on('click','.payment-sale',function(){
                let id = $(this).data('id');
                $.get('/sales/' + id + '/payments', function(res){
                    $("#paymentsContent").html(res);
                    $("#paymentsModal").modal('show');
                });
            });



        });
    </script>
@endpush
