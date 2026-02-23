@extends('admin.layouts.master')

@section('title', __('messages.list_users'))

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
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="card-title mb-0 fw-semibold"></h5>
                                <div class="dropdown">
                                    <button class="btn btn-primary btn-sm dropdown-toggle rounded-3" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-gear-fill me-1"></i> Actions
                                    </button>
                                    <ul class="dropdown-menu shadow-sm rounded-3">
                                        <li><a class="dropdown-item" href="{{ __('users/create') }}" id="addProductBtn">
                                                <i class="bi bi-plus-circle me-2"></i>{{ __('messages.add') }}</a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li><a class="dropdown-item text-danger" href="#" id="bulkDeleteBtn" disabled>
                                                <i class="bi bi-trash me-2"></i>{{ __('messages.delete') }}</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div id="alertsContainer" class="mb-4"></div>

                            <div class="table-responsive">
                                <table class="table table-striped table-bordered rounded-3 align-middle" id="usersTable">
                                    <thead class="table-primary">
                                        <tr>
                                            <th scope="col" class="py-3"><input type="checkbox" id="selectAll"></th>
                                            <th scope="col" class="py-3">{{ __('messages.name') }}</th>
                                            <th scope="col" class="py-3">{{ __('messages.email') }}</th>
                                            <th scope="col" class="py-3">{{ __('messages.group') }}</th>
                                            <th scope="col" class="py-3">{{ __('messages.status') }}</th>
                                            <th scope="col" class="py-3 text-center">{{ __('messages.actions') }}</th>
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


    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <form id="editUserForm">
                    @csrf
                    <input type="hidden" id="edit_id" name="id">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" id="edit_name" required>
                            <div class="invalid-feedback" id="edit_name_error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_group_id" class="form-label">Group</label>
                            <select class="form-select" name="group_id" id="edit_group_id" required>
                                <option value="">-- Select Group --</option>
                                @foreach ($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="edit_group_id_error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('messages.email') }}</label>
                            <input type="email" name="email" id="edit_email"
                                class="form-control @error('email') is-invalid @enderror" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 position-relative">
                            <label for="password" class="form-label">{{ __('messages.password') }}</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password"
                                    class="form-control @error('password') is-invalid @enderror" required>
                                <button type="button" class="btn btn-outline-secondary toggle-password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 position-relative">
                            <label for="password_confirmation"
                                class="form-label">{{ __('messages.confirm_password') }}</label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control @error('password_confirmation') is-invalid @enderror" required>
                                <button type="button" class="btn btn-outline-secondary toggle-password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
                        <button type="submit"
                            class="btn btn-danger btn-sm rounded-3">{{ __('messages.delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



@endsection
@push('scripts')
    <script>
        $(document).ready(function() {

            // TAble
            const table = $('#usersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('users.index') }}",
                columns: [{
                        data: 'id',
                        render: data => `<input type="checkbox" class="selectRow" value="${data}">`,
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'group_name',
                        name: 'group_name'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data) {
                            if (data == 1) {
                                return '<span class="badge bg-success">Active</span>';
                            } else {
                                return '<span class="badge bg-danger">Inactive</span>';
                            }
                        }
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





            // Edit
            $(document).on('click', '.editUser', function() {
                var id = $(this).data('id');
                $.get("{{ url('admin/users') }}/" + id + "/edit", function(data) {
                    $('#editUserModal').modal('show');
                    $('#edit_name').val(data.user.name);
                    $('#edit_email').val(data.user.email);
                    $('#edit_group_id').val(data.user.group_id);
                    $('#editUserForm').attr('action', "{{ url('admin/users') }}/" + id);
                });
            });

            // Update User
            $('#editUserForm').submit(function(e) {
                e.preventDefault();
                var id = $(this).attr('action').split('/').pop();
                $.ajax({
                    url: "{{ url('admin/users') }}/" + id,
                    method: 'PUT',
                    data: $(this).serialize(),
                    success: function(response) {
                        $('#editUserModal').modal('hide');
                        table.ajax.reload();
                        const successAlert = `
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                User updated successfully!
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>`;
                        $('#alertsContainer').html(successAlert);
                    },
                    error: function(response) {
                        alert('Error: ' + response.responseJSON.message);
                    }
                });
            });


            // Delete User
            $(document).on('click', '.deleteUser', function() {
                const id = $(this).data('id');
                const deleteUrl = "{{ url('admin/users') }}/" + id;
                $('#deleteForm').attr('action', deleteUrl);
                $('#deleteModal').modal('show');
            });

            $('#deleteForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const action = form.attr('action');

                $.ajax({
                    url: action,
                    type: 'DELETE',
                    data: form.serialize(),
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#deleteModal').modal('hide');
                            table.ajax.reload();
                            $('#alertsContainer').html(`
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            ${response.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Failed to delete the user. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        $('#deleteModal').modal('hide'); // close modal if needed
                        $('#alertsContainer').html(`
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        ${errorMessage}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
                    }
                });
            });





        });
    </script>
@endpush
