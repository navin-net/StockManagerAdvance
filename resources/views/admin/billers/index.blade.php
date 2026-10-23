@extends('admin.layouts.master')

@section('title', __('messages.billers_list'))

@section('content')
    <div class="container-fluid bl">

        {{-- PAGE HEADER --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <div>
                <h1 class="h4 fw-bold mb-1">{{ $pageTitle }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        @foreach ($breadcrumbs as $breadcrumb)
                            @if (!$breadcrumb['active'])
                                <li class="breadcrumb-item">
                                    <a href="{{ $breadcrumb['url'] }}" class="text-decoration-none">{{ $breadcrumb['label'] }}</a>
                                </li>
                            @else
                                <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb['label'] }}</li>
                            @endif
                        @endforeach
                    </ol>
                </nav>
            </div>

            <div class="d-flex flex-wrap align-items-center gap-2">
                <div class="bl-chip">
                    <i class="bi bi-hdd-network"></i>
                    <span>{{ __('messages.ip_address') }}</span>
                    <strong>{{ auth()->user()->ip_address }}</strong>
                </div>

                <span class="bl-selected" id="selectedChip" hidden></span>

                <div class="dropdown">
                    <button class="btn btn-primary fw-semibold dropdown-toggle" type="button" id="actionDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-gear-fill me-1"></i>{{ __('messages.actions') }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="actionDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('billers.create') }}" id="addBillerBtn">
                                <i class="bi bi-plus-circle me-2"></i>{{ __('messages.add') }}
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="#" id="bulkDeleteBtn">
                                <i class="bi bi-trash me-2"></i>{{ __('messages.delete') }}
                                <span class="badge rounded-pill text-bg-danger ms-1" id="bulkDeleteBadge" hidden></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- ALERTS --}}
        <div id="alertsContainer">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show auto-hide" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show auto-hide" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        {{-- TABLE --}}
        <section class="bl-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle w-100" id="billersTable">
                    <thead>
                    <tr>
                        <th class="bl-check"><input type="checkbox" id="selectAll" class="form-check-input" aria-label="Select all"></th>
                        <th>{{ __('messages.name') }}</th>
                        <th>{{ __('messages.email') }}</th>
                        <th>{{ __('messages.group') }}</th>
                        <th>{{ __('messages.warehouse') }}</th>
                        <th>{{ __('messages.phone') }}</th>
                        <th>{{ __('messages.city') }}</th>
                        <th class="text-center">{{ __('messages.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </section>

        {{-- DETAILS MODAL --}}
        <div class="modal fade" id="companyModal" tabindex="-1" aria-labelledby="companyModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-semibold" id="companyModalLabel">{{ __('messages.billers_details') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0" id="companyDetailsContent">
                        <div class="bl-profile">
                            <span class="bl-avatar bl-avatar--lg" id="c_avatar">?</span>
                            <div>
                                <div class="bl-profile__name" id="c_name"></div>
                                <div class="bl-muted" id="c_email"></div>
                            </div>
                        </div>

                        <dl class="bl-facts">
                            <div><dt>{{ __('messages.phone') }}</dt><dd id="c_phone"></dd></div>
                            <div><dt>{{ __('messages.group') }}</dt><dd id="c_group"></dd></div>
                            <div><dt>{{ __('messages.warehouse') }}</dt><dd id="c_warehouse"></dd></div>
                            <div><dt>{{ __('messages.city') }}</dt><dd id="c_city"></dd></div>
                            <div><dt>{{ __('messages.street') }}</dt><dd id="c_street"></dd></div>
                            <div><dt>{{ __('messages.number_of_houses') }}</dt><dd id="c_houses"></dd></div>
                            <div class="bl-facts__wide"><dt>{{ __('messages.address') }}</dt><dd id="c_address"></dd></div>
                        </dl>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="printCompanyBtn">
                            <i class="bi bi-printer me-1"></i>Print
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- USER LIST MODAL --}}
        <div class="modal fade" id="userListModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content border-0">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-semibold">{{ __('messages.list_user') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0" id="userListContent">
                        <div class="text-center p-3">
                            <div class="spinner-border text-primary" role="status"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- DELETE MODAL --}}
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-semibold text-danger" id="deleteModalLabel">{{ __('messages.confirm_delete') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0">{{ __('messages.delete_confirm') }}</div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">{{ __('messages.delete') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- BULK DELETE MODAL --}}
        <div class="modal fade" id="bulkDeleteModal" tabindex="-1" aria-labelledby="bulkDeleteLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-semibold text-danger" id="bulkDeleteLabel">{{ __('messages.confirm_delete') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0">
                        <p class="mb-1">You are about to delete <strong id="bulkDeleteCount">0</strong> selected billers.</p>
                        <p class="mb-0">{{ __('messages.delete_confirm') }}</p>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                        <button type="button" class="btn btn-danger" id="confirmBulkDelete">{{ __('messages.delete') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .bl {
                --accent: var(--bs-primary, #4f46e5);
                --accent-soft: color-mix(in srgb, var(--accent) 14%, transparent);
                --muted: color-mix(in srgb, currentColor 62%, transparent);
                --line: color-mix(in srgb, currentColor 14%, transparent);
                --paper: color-mix(in srgb, currentColor 4%, transparent);
                --wash: color-mix(in srgb, currentColor 7%, transparent);
            }

            .bl-muted {
                color: var(--muted);
            }

            .bl-chip {
                display: inline-flex;
                align-items: center;
                gap: .5rem;
                padding: .4rem .8rem;
                border: 1px solid var(--line);
                border-radius: 999px;
                font-size: .85rem;
                color: var(--muted);
            }

            .bl-chip strong {
                color: inherit;
                font-variant-numeric: tabular-nums;
            }

            .bl-selected {
                padding: .35rem .75rem;
                border-radius: 999px;
                background: var(--accent-soft);
                color: var(--accent);
                font-size: .85rem;
                font-weight: 600;
            }

            .bl-selected[hidden] {
                display: none;
            }

            .bl-card {
                background: var(--paper);
                border: 1px solid var(--line);
                border-radius: 14px;
                padding: 1rem 1.25rem;
            }

            .bl .table {
                --bs-table-bg: transparent;
                --bs-table-color: inherit;
                --bs-table-hover-bg: var(--wash);
                --bs-table-hover-color: inherit;
                --bs-table-border-color: var(--line);
                margin-bottom: 0;
            }

            .bl .table thead th {
                font-size: .78rem;
                font-weight: 600;
                color: var(--muted);
                white-space: nowrap;
                border-bottom: 1px solid var(--line);
                background: transparent;
                padding: .75rem .6rem;
            }

            .bl .table tbody td {
                padding: .7rem .6rem;
                border-bottom: 1px solid var(--line);
            }

            .bl .table tbody tr {
                cursor: pointer;
            }

            .bl .table tbody tr:last-child td {
                border-bottom: 0;
            }

            .bl-check {
                width: 36px;
            }

            .bl-person {
                display: flex;
                align-items: center;
                gap: .75rem;
                min-width: 220px;
            }

            .bl-person strong,
            .bl-person small {
                display: block;
                line-height: 1.25;
            }

            .bl-person small {
                color: var(--muted);
            }

            .bl-avatar {
                display: grid;
                place-items: center;
                flex: none;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                color: #fff;
                font-weight: 700;
            }

            .bl-avatar--lg {
                width: 64px;
                height: 64px;
                font-size: 1.6rem;
            }

            .bl-tag {
                display: inline-block;
                padding: .2rem .65rem;
                border-radius: 999px;
                background: var(--accent-soft);
                color: var(--accent);
                font-size: .75rem;
                font-weight: 600;
                white-space: nowrap;
            }

            .bl-none {
                color: var(--muted);
            }

            /* Details modal */
            .bl-profile {
                display: flex;
                align-items: center;
                gap: 1rem;
                padding-bottom: 1.25rem;
                margin-bottom: 1.25rem;
                border-bottom: 1px solid var(--line);
            }

            .bl-profile__name {
                font-size: 1.25rem;
                font-weight: 700;
            }

            .bl-facts {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 1rem 1.5rem;
                margin: 0;
            }

            .bl-facts dt {
                font-size: .78rem;
                font-weight: 500;
                color: var(--muted);
                margin-bottom: .15rem;
            }

            .bl-facts dd {
                margin: 0;
                font-weight: 600;
                min-height: 1.4em;
                overflow-wrap: anywhere;
            }

            .bl-facts__wide {
                grid-column: 1 / -1;
            }

            @media (max-width: 575.98px) {
                .bl-facts {
                    grid-template-columns: 1fr 1fr;
                }
            }

            /* DataTables controls */
            .bl .dataTables_wrapper .row:first-child,
            .bl .dt-container .row:first-child {
                margin-bottom: .75rem;
            }

            .bl .dataTables_filter input,
            .bl .dt-search input,
            .bl .dataTables_length select,
            .bl .dt-length select {
                background: transparent;
                color: inherit;
                border: 1px solid var(--line);
                border-radius: 8px;
                padding: .35rem .65rem;
            }

            .bl .dataTables_filter input:focus,
            .bl .dt-search input:focus {
                outline: none;
                border-color: var(--accent);
                box-shadow: 0 0 0 .2rem var(--accent-soft);
            }

            .bl .dataTables_info,
            .bl .dt-info {
                color: var(--muted);
                font-size: .85rem;
            }

            .bl .page-link {
                background: transparent;
                color: inherit;
                border-color: var(--line);
            }

            .bl .page-item.active .page-link {
                background: var(--accent);
                border-color: var(--accent);
                color: #fff;
            }

            .bl .form-check-input:checked {
                background-color: var(--accent);
                border-color: var(--accent);
            }
        </style>
    </div>
@endsection

@push('scripts')
    <script>
        const esc = v => String(v ?? '').replace(/[&<>"']/g, c => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
        }[c]));

        const tint = name => {
            let h = 0;
            for (const c of String(name ?? '')) h = (h * 31 + c.charCodeAt(0)) % 360;
            return `hsl(${h} 45% 42%)`;
        };
        const initial = name => String(name ?? '?').trim().charAt(0).toUpperCase() || '?';

        function showAlert(type, message) {
            $('#alertsContainer').html(`
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${esc(message)}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`);
        }

        $(document).ready(function () {

            const plain = data => (data === null || data === undefined || data === '')
                ? '<span class="bl-none">N/A</span>' : esc(data);

            const table = $('#billersTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                order: [],
                ajax: "{{ route('billers.index') }}",
                columns: [
                    {
                        data: 'id', orderable: false, searchable: false,
                        render: data => `<input type="checkbox" class="form-check-input selectRow" value="${esc(data)}">`
                    },
                    {
                        data: 'name', name: 'name',
                        render: function (data, type, row) {
                            if (type !== 'display') return data;
                            return `
                                <div class="bl-person">
                                    <span class="bl-avatar" style="background:${tint(data)}">${esc(initial(data))}</span>
                                    <div><strong>${esc(data)}</strong><small>${esc(row.email)}</small></div>
                                </div>`;
                        }
                    },
                    /* the email is shown under the name, but stays searchable */
                    { data: 'email', name: 'email', visible: false },
                    {
                        data: 'group_name', name: 'group_name',
                        render: (d, t) => t === 'display' ? (d ? `<span class="bl-tag">${esc(d)}</span>` : plain(d)) : d
                    },
                    { data: 'warehouse_name', name: 'warehouse_name', render: plain },
                    { data: 'phone', name: 'phone', render: plain },
                    { data: 'city', name: 'city', render: plain },
                    { data: 'action', orderable: false, searchable: false, className: 'text-center' }
                ],
                drawCallback: function () {
                    $('#selectAll').prop('checked', false);
                    updateSelected();
                },
                language: {
                    paginate: {
                        previous: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/></svg>',
                        next: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/></svg>'
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

            /* ===== SELECTION ===== */
            function updateSelected() {
                const n = $('.selectRow:checked').length;
                $('#selectedChip').prop('hidden', n === 0).text(n + ' selected');
                $('#bulkDeleteBadge').prop('hidden', n === 0).text(n);
            }

            $('#selectAll').on('change', function () {
                $('.selectRow').prop('checked', this.checked);
                updateSelected();
            });

            $('#billersTable').on('change', '.selectRow', function () {
                const all = $('.selectRow').length;
                $('#selectAll').prop('checked', all > 0 && $('.selectRow:checked').length === all);
                updateSelected();
            });

            const selectedIds = () => $('.selectRow:checked').map(function () { return $(this).val(); }).get();

            /* ===== ROW CLICK -> DETAILS ===== */
            $('#billersTable tbody').on('click', 'tr', function (e) {
                // ignore clicks on the checkbox, links, buttons and the actions menu
                if ($(e.target).closest('input, a, button, .dropdown, label').length) return;

                const row = table.row(this).data();
                if (!row) return;

                $('#billersTable tbody tr').removeClass('table-active');
                $(this).addClass('table-active');

                $('#c_avatar').text(initial(row.name)).css('background', tint(row.name));
                $('#c_name').text(row.name || '');
                $('#c_email').text(row.email || '');
                $('#c_phone').text(row.phone || '-');
                $('#c_group').text(row.group_name || '-');
                $('#c_warehouse').text(row.warehouse_name || '-');
                $('#c_city').text(row.city || '-');
                $('#c_street').text(row.street || '-');
                $('#c_houses').text(row.number_of_houses || '-');
                $('#c_address').text(row.address || '-');

                bootstrap.Modal.getOrCreateInstance(document.getElementById('companyModal')).show();
            });

            /* ===== PRINT DETAILS ===== */
            $('#printCompanyBtn').on('click', function () {
                const fields = [
                    ['{{ __('messages.phone') }}', 'c_phone'], ['{{ __('messages.group') }}', 'c_group'],
                    ['{{ __('messages.warehouse') }}', 'c_warehouse'], ['{{ __('messages.city') }}', 'c_city'],
                    ['{{ __('messages.street') }}', 'c_street'], ['{{ __('messages.number_of_houses') }}', 'c_houses'],
                    ['{{ __('messages.address') }}', 'c_address']
                ];
                const rows = fields.map(([label, id]) =>
                    `<tr><th>${esc(label)}</th><td>${esc($('#' + id).text())}</td></tr>`).join('');

                const win = window.open('', '', 'width=900,height=700');
                win.document.write(`<html><head><title>Biller Details</title>
                    <style>
                        body{font-family:Arial,sans-serif;padding:32px;color:#111}
                        h1{margin:0 0 4px;font-size:22px} p{margin:0 0 24px;color:#555}
                        table{border-collapse:collapse;width:100%}
                        th,td{text-align:left;padding:10px;border-bottom:1px solid #ddd;font-size:14px}
                        th{width:30%;color:#555;font-weight:600}
                    </style></head><body>
                    <h1>${esc($('#c_name').text())}</h1><p>${esc($('#c_email').text())}</p>
                    <table>${rows}</table></body></html>`);
                win.document.close();
                win.print();
            });

            /* ===== USER LIST ===== */
            $(document).on('click', '.listUser', function (e) {
                e.preventDefault();
                const id = $(this).data('id');

                bootstrap.Modal.getOrCreateInstance(document.getElementById('userListModal')).show();
                $('#userListContent').html('<div class="text-center p-3"><div class="spinner-border text-primary" role="status"></div></div>');

                $.ajax({
                    url: '/admin/billers/' + id + '/users',
                    method: 'GET',
                    success: response => $('#userListContent').html(response),
                    error: () => $('#userListContent').html('<div class="alert alert-danger mb-0">{{ __('messages.load_failed') }}</div>')
                });
            });

            /* ===== DELETE ONE ===== */
            $(document).on('click', '.deleteBillerBtn', function (e) {
                e.preventDefault();
                $('#deleteForm').attr('action', "{{ url('admin/billers') }}/" + $(this).data('id'));
                bootstrap.Modal.getOrCreateInstance(document.getElementById('deleteModal')).show();
            });

            $(document).on('click', '.deleteUserBtn', function (e) {
                e.preventDefault();
                $('#deleteForm').attr('action', "{{ url('admin/billers/users') }}/" + $(this).data('id') + "/delete");   // match your route
                bootstrap.Modal.getOrCreateInstance(document.getElementById('deleteModal')).show();
            });

            $('#deleteForm').on('submit', function (e) {
                e.preventDefault();
                const form = $(this);

                $.ajax({
                    url: form.attr('action'),
                    type: 'DELETE',
                    data: form.serialize(),
                    success: function (response) {
                        $('#deleteModal').modal('hide');
                        $('#userListModal').modal('hide');
                        table.ajax.reload(null, false);
                        showAlert('success', (response && response.message) || @json(__('messages.biller_deleted_successfully')));
                    },
                    error: function (xhr) {
                        $('#deleteModal').modal('hide');
                        showAlert('danger', (xhr.responseJSON && xhr.responseJSON.message) || 'Failed to delete the biller. Please try again.');
                    }
                });
            });

            /* ===== BULK DELETE ===== */
            const bulkDeleteUrl = "{{ Route::has('billers.bulk-delete') ? route('billers.bulk-delete') : url('admin/billers/bulk-delete') }}";

            $('#bulkDeleteBtn').on('click', function (e) {
                e.preventDefault();
                const n = selectedIds().length;
                if (!n) return showAlert('danger', 'Please select the billers you want to delete first.');

                $('#bulkDeleteCount').text(n);
                bootstrap.Modal.getOrCreateInstance(document.getElementById('bulkDeleteModal')).show();
            });

            $('#confirmBulkDelete').on('click', function () {
                const btn = $(this);
                const ids = selectedIds();
                if (!ids.length) return;

                btn.prop('disabled', true);

                $.ajax({
                    url: bulkDeleteUrl,
                    type: 'DELETE',
                    data: { ids: ids, _token: "{{ csrf_token() }}" },
                    success: function (response) {
                        $('#bulkDeleteModal').modal('hide');
                        table.ajax.reload(null, false);
                        showAlert('success', response.message);
                    },
                    error: function (xhr) {
                        $('#bulkDeleteModal').modal('hide');
                        table.ajax.reload(null, false);
                        showAlert('danger', (xhr.responseJSON && xhr.responseJSON.message) || 'Something went wrong.');
                    },
                    complete: function () { btn.prop('disabled', false); }
                });
            });
        });
    </script>
@endpush
