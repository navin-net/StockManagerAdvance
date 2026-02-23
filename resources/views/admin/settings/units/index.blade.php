@extends('admin.layouts.master')

@section('title', __('messages.units_list'))

@section('content')
    <div class="container-fluid py-4">

        {{-- Breadcrumb --}}
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

        {{-- Card --}}
        <section class="section">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">

                    {{-- Actions --}}
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0 fw-semibold"></h5>
                        <div class="dropdown">
                            <button class="btn btn-primary btn-sm dropdown-toggle rounded-3" data-bs-toggle="dropdown">
                                <i class="bi bi-gear-fill me-1"></i> Actions
                            </button>
                            <ul class="dropdown-menu shadow-sm rounded-3">
                                <li><a class="dropdown-item" id="addUnitsBtn">{{ __('messages.add') }}</a></li>
                                <li>
                                    <a class="dropdown-item disabled" id="bulkDeleteBtn" style="pointer-events:none;">
                                        {{ __('messages.delete') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- Alerts --}}
                    <div id="alertsContainer" class="mb-4"></div>

                    {{-- Table --}}
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered align-middle" id="unitsTable">
                            <thead class="table-primary">
                                <tr>
                                    <th><input type="checkbox" id="selectAll"></th>
                                    <th>{{ __('messages.name') }}</th>
                                    <th>{{ __('messages.slug') }}</th>
                                    <th class="text-center">{{ __('messages.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                </div>
            </div>
        </section>
    </div>

    {{-- ADD MODAL --}}
    <div class="modal fade" id="addUnitsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-3 border-0 shadow">
                <form id="createUnitsForm">
                    @csrf
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-semibold">{{ __('messages.create') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.name') }}</label>
                            <input type="text" class="form-control" id="create_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.slug') }}</label>
                            <input type="text" class="form-control" id="create_slug" name="slug" required>
                        </div>
                    </div>

                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary btn-sm"
                            data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                        <button type="submit" class="btn btn-primary btn-sm">{{ __('messages.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div class="modal fade" id="editUnitsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-3 border-0 shadow">
                <form id="editUnitsForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_id">

                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-semibold">{{ __('messages.edit') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.name') }}</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.slug') }}</label>
                            <input type="text" class="form-control" id="edit_slug" name="slug" required>
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


    {{-- DELETE CONFIRM MODAL --}}
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-3 border-0 shadow">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold">{{ __('messages.confirm_delete') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="deleteModalMessage">{{ __('messages.are_you_sure_delete') }}</p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary btn-sm"
                        data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    <button type="button" id="confirmDeleteBtn"
                        class="btn btn-danger btn-sm">{{ __('messages.delete') }}</button>
                </div>
            </div>
        </div>
    </div>

<div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Unit Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modalBodyContent">
        </div>
    </div>
  </div>
</div>


@endsection
@push('scripts')
    <script>
        const BaseUrl = "/admin/system_settings/units/";
        let table;

        $(document).ready(function () {

            // ===== DataTable =====
            table = $('#unitsTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('units.index') }}",
                columns: [
                    {
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        render: data => `<input type="checkbox" class="Checkbox" value="${data}">`
                    },
                    { data: 'name',
                        className: 'clickable-col' // Add this class
                     },
                    { data: 'slug' },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                language: {
                    lengthMenu: '{{ __('messages.show') }} _MENU_ {{ __('messages.entries') }}',
                    search: '{{ __('messages.search') }}',
                    emptyTable: "{{ __('messages.no_data_available') }}",
                    processing: "{{ __('messages.processing') }}",
                    zeroRecords: "{{ __('messages.no_matching_records') }}"
                }
            });


$('#unitsTable tbody').on('click', '.clickable-col', function () {
    var data = table.row($(this).parents('tr')).data();

    $('#modalTitle').text('Details for: ' + data.name);
    $('#modalBodyContent').html(`
        <p><strong>ID:</strong> ${data.id}</p>
        <p><strong>Slug:</strong> ${data.slug}</p>
    `);
    var myModal = new bootstrap.Modal(document.getElementById('detailsModal'));
    myModal.show();
});


            // ===== Select All =====
            $('#selectAll').on('change', function () {
                $('.Checkbox').prop('checked', $(this).prop('checked'));
                toggleBulkDeleteButton();
            });
            $(document).on('change', '.Checkbox', toggleBulkDeleteButton);

            function toggleBulkDeleteButton() {
                const selected = $('.Checkbox:checked').length;
                if (selected > 0) {
                    $('#bulkDeleteBtn').removeClass('disabled').css('pointer-events', 'auto');
                } else {
                    $('#bulkDeleteBtn').addClass('disabled').css('pointer-events', 'none');
                }
            }

            // ===== ADD =====
            $('#addUnitsBtn').on('click', function () {
                $('#createUnitsForm')[0].reset();
                $('#addUnitsModal').modal('show');
            });

            $('#createUnitsForm').on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    url: BaseUrl,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (res) {
                        $('#addUnitsModal').modal('hide');
                        table.ajax.reload(null, false);
                        $('#alertsContainer').html(`
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    ${res.message}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>`);
                    },
                    error: function (xhr) {
                        alert('Create failed');
                    }
                });
            });

            // ===== EDIT OPEN =====
            $(document).on('click', '.editUnitsBtn', function () {
                let id = $(this).data('id');
                $.get(BaseUrl + id + "/edit", function (data) {
                    $('#edit_id').val(data.id);
                    $('#edit_name').val(data.name);
                    $('#edit_slug').val(data.slug);
                    $('#editUnitsModal').modal('show');
                });
            });

            // ===== UPDATE =====
            $('#editUnitsForm').on('submit', function (e) {
                e.preventDefault();
                let id = $('#edit_id').val();
                $.ajax({
                    url: BaseUrl + id,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (res) {
                        $('#editUnitsModal').modal('hide');
                        table.ajax.reload(null, false);
                        $('#alertsContainer').html(`
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    ${res.message}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>`);
                    },
                    error: function () { alert('Update failed'); }
                });
            });

            // SINGLE DELETE BUTTON CLICK
            $(document).on('click', '.deleteUnitsBtn', function () {
                deleteIds = [$(this).data('id')]; // single id
                isBulkDelete = false;
                $('#deleteModalMessage').text("{{ __('messages.are_you_sure_delete') }}");
                $('#deleteModal').modal('show');
            });

            // BULK DELETE BUTTON CLICK
            $('#bulkDeleteBtn').on('click', function () {
                deleteIds = $('.Checkbox:checked').map(function () { return $(this).val(); }).get();
                if (deleteIds.length === 0) return;
                isBulkDelete = true;
                $('#deleteModalMessage').text("{{ __('messages.delete_confirm') }}");
                $('#deleteModal').modal('show');
            });

            // CONFIRM DELETE
            $('#confirmDeleteBtn').on('click', function () {
                let url = isBulkDelete ? BaseUrl + 'bulk_delete' : BaseUrl + deleteIds[0];
                let type = isBulkDelete ? 'POST' : 'DELETE';
                let data = isBulkDelete ? { ids: deleteIds, _token: '{{ csrf_token() }}' }
                    : { _token: '{{ csrf_token() }}' };

                $.ajax({
                    url: url,
                    type: type,
                    data: data,
                    success: function (res) {
                        $('#deleteModal').modal('hide');
                        $('#selectAll').prop('checked', false);
                        $('.Checkbox').prop('checked', false);
                        toggleBulkDeleteButton();
                        table.ajax.reload(null, false);
                        $('#alertsContainer').html(`
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                ${res.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>`);
                    },
                    error: function () {
                        alert('Delete failed');
                    }
                });
            });

        });


    </script>
@endpush
