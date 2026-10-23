@extends('admin.layouts.master')

@section('title', __('messages.categories_list'))

@section('content')
    <div class="container-fluid ct">

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
                <div class="ct-chip">
                    <i class="bi bi-hdd-network"></i>
                    <span>{{ __('messages.ip_address') }}</span>
                    <strong>{{ auth()->user()->ip_address }}</strong>
                </div>

                <span class="ct-selected" id="selectedChip" hidden></span>

                <div class="dropdown">
                    <button class="btn btn-primary fw-semibold dropdown-toggle" type="button" id="actionDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-gear-fill me-1"></i>{{ __('messages.actions') }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="actionDropdown">
                        <li>
                            <a class="dropdown-item" href="#" id="addCategoriesBtn">
                                <i class="bi bi-plus-circle me-2"></i>{{ __('messages.add') }}
                            </a>
                        </li>
                        @if (Route::has('categories.export'))
                            <li>
                                <a class="dropdown-item" href="#" id="exportCategories">
                                    <i class="bi bi-file-earmark-excel me-2"></i>{{ __('messages.export_to_excel') }}
                                </a>
                            </li>
                        @endif
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

        <div id="alertsContainer"></div>

        {{-- TABLE --}}
        <section class="ct-card">
            <div class="table-responsive">
                <table id="categoriesTable" class="table table-hover align-middle w-100">
                    <thead>
                    <tr>
                        <th class="ct-check"><input type="checkbox" id="selectAll" class="form-check-input" aria-label="Select all"></th>
                        <th>{{ __('messages.name') }}</th>
                        <th>{{ __('messages.slug') }}</th>
                        <th class="text-center">{{ __('messages.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </section>

        {{-- ADD / EDIT MODAL (one form for both) --}}
        <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-semibold" id="categoryModalLabel">{{ __('messages.create') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form id="categoryForm" novalidate>
                        @csrf
                        <input type="hidden" name="category_id" id="category_id">
                        <input type="hidden" name="_method" id="formMethod" value="POST">

                        <div class="modal-body pt-0">
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('messages.name') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="name" maxlength="255" required>
                                <div class="invalid-feedback" data-error="name"></div>
                            </div>

                            <div>
                                <label for="slug" class="form-label d-flex justify-content-between">
                                    <span>{{ __('messages.slug') }} <span class="text-danger">*</span></span>
                                    <button type="button" class="ct-auto" id="slugAuto" title="Build the slug from the name">
                                        <i class="bi bi-magic me-1"></i><span id="slugAutoLabel">Auto</span>
                                    </button>
                                </label>
                                <input type="text" class="form-control ct-slug" name="slug" id="slug" maxlength="255" required>
                                <div class="invalid-feedback" data-error="slug"></div>
                                <div class="ct-hint" id="slugHint">The slug follows the name while you type.</div>
                            </div>
                        </div>

                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                            <button type="submit" id="saveBtn" class="btn btn-primary px-4">{{ __('messages.save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- DELETE MODAL --}}
        <div class="modal fade" id="deleteCategoriesModal" tabindex="-1" aria-labelledby="deleteCategoriesModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-semibold text-danger" id="deleteCategoriesModalLabel">{{ __('messages.confirm_delete') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0">{{ __('messages.delete_confirm') }}</div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                        <form id="deleteCategoriesForm" method="POST">
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
                        <p class="mb-1">You are about to delete <strong id="bulkDeleteCount">0</strong> selected categories.</p>
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
            .ct {
                --accent: var(--bs-primary, #4f46e5);
                --accent-soft: color-mix(in srgb, var(--accent) 14%, transparent);
                --muted: color-mix(in srgb, currentColor 62%, transparent);
                --line: color-mix(in srgb, currentColor 14%, transparent);
                --paper: color-mix(in srgb, currentColor 4%, transparent);
                --wash: color-mix(in srgb, currentColor 7%, transparent);
            }

            .ct-chip {
                display: inline-flex;
                align-items: center;
                gap: .5rem;
                padding: .4rem .8rem;
                border: 1px solid var(--line);
                border-radius: 999px;
                font-size: .85rem;
                color: var(--muted);
            }

            .ct-chip strong {
                color: inherit;
            }

            .ct-selected {
                padding: .35rem .75rem;
                border-radius: 999px;
                background: var(--accent-soft);
                color: var(--accent);
                font-size: .85rem;
                font-weight: 600;
            }

            .ct-selected[hidden] {
                display: none;
            }

            .ct-card {
                background: var(--paper);
                border: 1px solid var(--line);
                border-radius: 14px;
                padding: 1rem 1.25rem;
            }

            .ct .table {
                --bs-table-bg: transparent;
                --bs-table-color: inherit;
                --bs-table-hover-bg: var(--wash);
                --bs-table-hover-color: inherit;
                --bs-table-border-color: var(--line);
                margin-bottom: 0;
            }

            .ct .table thead th {
                font-size: .78rem;
                font-weight: 600;
                color: var(--muted);
                white-space: nowrap;
                border-bottom: 1px solid var(--line);
                background: transparent;
                padding: .75rem .6rem;
            }

            .ct .table tbody td {
                padding: .7rem .6rem;
                border-bottom: 1px solid var(--line);
            }

            .ct .table tbody tr:last-child td {
                border-bottom: 0;
            }

            .ct-check {
                width: 36px;
            }

            .ct-name {
                display: flex;
                align-items: center;
                gap: .75rem;
                font-weight: 600;
            }

            .ct-icon {
                display: grid;
                place-items: center;
                flex: none;
                width: 36px;
                height: 36px;
                border-radius: 10px;
                color: #fff;
                font-weight: 700;
            }

            .ct-slugtag {
                display: inline-block;
                padding: .15rem .6rem;
                border-radius: 6px;
                background: var(--wash);
                font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
                font-size: .82rem;
                color: var(--muted);
            }

            .ct-slug {
                font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
            }

            .ct-auto {
                border: 0;
                background: transparent;
                color: var(--accent);
                font-size: .8rem;
                font-weight: 600;
                padding: 0;
            }

            .ct-auto.is-off {
                color: var(--muted);
            }

            .ct-hint {
                margin-top: .4rem;
                font-size: .8rem;
                color: var(--muted);
            }

            .ct .form-label {
                font-size: .85rem;
                font-weight: 600;
            }

            .ct .form-control:focus {
                border-color: var(--accent);
                box-shadow: 0 0 0 .2rem var(--accent-soft);
            }

            /* DataTables controls */
            .ct .dataTables_wrapper .row:first-child,
            .ct .dt-container .row:first-child {
                margin-bottom: .75rem;
            }

            .ct .dataTables_filter input,
            .ct .dt-search input,
            .ct .dataTables_length select,
            .ct .dt-length select {
                background: transparent;
                color: inherit;
                border: 1px solid var(--line);
                border-radius: 8px;
                padding: .35rem .65rem;
            }

            .ct .dataTables_filter input:focus,
            .ct .dt-search input:focus {
                outline: none;
                border-color: var(--accent);
                box-shadow: 0 0 0 .2rem var(--accent-soft);
            }

            .ct .dataTables_info,
            .ct .dt-info {
                color: var(--muted);
                font-size: .85rem;
            }

            .ct .page-link {
                background: transparent;
                color: inherit;
                border-color: var(--line);
            }

            .ct .page-item.active .page-link {
                background: var(--accent);
                border-color: var(--accent);
                color: #fff;
            }

            .ct .form-check-input:checked {
                background-color: var(--accent);
                border-color: var(--accent);
            }
        </style>
    </div>
@endsection

@push('scripts')
    <script>
        const BaseUrl = "{{ url('admin/system_settings/categories') }}/";
        const txt = {
            create: @json(__('messages.create')),
            edit: @json(__('messages.edit')),
            save: @json(__('messages.save')),
            update: @json(__('messages.update'))
        };

        const esc = v => String(v ?? '').replace(/[&<>"']/g, c => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
        }[c]));

        const tint = name => {
            let h = 0;
            for (const c of String(name ?? '')) h = (h * 31 + c.charCodeAt(0)) % 360;
            return `hsl(${h} 45% 42%)`;
        };

        /*
         * Slug from a name. Works for any language: the old version used \w, which only knows
         * English letters, so a Khmer name became an empty slug and could not be saved.
         * \p{L} = letters, \p{M} = the marks and vowel signs that Khmer and other scripts use.
         */
        function slugify(value) {
            return String(value ?? '')
                .normalize('NFC')
                .toLowerCase()
                .trim()
                .replace(/[^\p{L}\p{M}\p{N}\s_-]/gu, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }

        function showAlert(type, message) {
            $('#alertsContainer').html(`
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${esc(message)}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`);
        }

        $(document).ready(function () {

            /* ===== TABLE ===== */
            const table = $('#categoriesTable').DataTable({
                pageLength: 10,
                lengthMenu: [
                    [10, 20, 30, 50, -1],
                    [10, 20, 30, 50, "{{ __('messages.all') }}"]
                ],
                responsive: true,
                processing: true,
                serverSide: true,
                order: [],
                ajax: "{{ route('categories.index') }}",
                columns: [
                    {
                        data: 'id', name: 'id', orderable: false, searchable: false,
                        render: data => `<input type="checkbox" class="form-check-input rowCheck" value="${esc(data)}">`
                    },
                    {
                        data: 'name', name: 'name',
                        render: function (data, type) {
                            if (type !== 'display') return data;
                            const letter = esc(String(data ?? '?').trim().charAt(0).toUpperCase());
                            return `<div class="ct-name"><span class="ct-icon" style="background:${tint(data)}">${letter}</span>${esc(data)}</div>`;
                        }
                    },
                    {
                        data: 'slug', name: 'slug',
                        render: (d, t) => t === 'display' ? `<span class="ct-slugtag">${esc(d)}</span>` : d
                    },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
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
                const n = $('.rowCheck:checked').length;
                $('#selectedChip').prop('hidden', n === 0).text(n + ' selected');
                $('#bulkDeleteBadge').prop('hidden', n === 0).text(n);
            }

            $('#selectAll').on('change', function () {
                $('.rowCheck').prop('checked', this.checked);
                updateSelected();
            });

            $('#categoriesTable').on('change', '.rowCheck', function () {
                const all = $('.rowCheck').length;
                $('#selectAll').prop('checked', all > 0 && $('.rowCheck:checked').length === all);
                updateSelected();
            });

            const selectedIds = () => $('.rowCheck:checked').map(function () { return $(this).val(); }).get();

            /* ===== ADD / EDIT MODAL ===== */
            const $form = $('#categoryForm');
            let autoSlug = true;   // the slug follows the name until the user edits it by hand

            function setAuto(on) {
                autoSlug = on;
                $('#slugAuto').toggleClass('is-off', !on);
                $('#slugAutoLabel').text(on ? 'Auto' : 'Manual');
                $('#slugHint').text(on
                    ? 'The slug follows the name while you type.'
                    : 'Manual. Click Auto to build it from the name again.');
            }

            function clearErrors() {
                $form.find('.is-invalid').removeClass('is-invalid');
                $form.find('[data-error]').text('');
            }

            function openModal(mode, data) {
                clearErrors();
                $form[0].reset();

                $('#category_id').val(data ? data.id : '');
                $('#formMethod').val(mode === 'edit' ? 'PUT' : 'POST');
                $('#categoryModalLabel').text(mode === 'edit' ? txt.edit : txt.create);
                $('#saveBtn').text(mode === 'edit' ? txt.update : txt.save);

                if (data) {
                    $('#name').val(data.name);
                    $('#slug').val(data.slug);
                    // Editing: do not change an existing slug just because the name changed,
                    // links to the category may already use it.
                    setAuto(false);
                } else {
                    setAuto(true);
                }

                const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('categoryModal'));
                modal.show();
                setTimeout(() => $('#name').trigger('focus'), 250);
            }

            $('#name').on('input', function () {
                if (autoSlug) $('#slug').val(slugify(this.value));
            });

            $('#slug').on('input', function () { setAuto(false); });

            $('#slugAuto').on('click', function () {
                setAuto(true);
                $('#slug').val(slugify($('#name').val()));
            });

            $('#addCategoriesBtn').on('click', function (e) {
                e.preventDefault();
                openModal('create');
            });

            $(document).on('click', '.editCategoryBtn', function (e) {
                e.preventDefault();
                $.get(BaseUrl + $(this).data('id') + '/edit')
                    .done(data => openModal('edit', data))
                    .fail(() => showAlert('danger', 'Could not load the category. Please try again.'));
            });

            $form.on('submit', function (e) {
                e.preventDefault();
                clearErrors();

                const editing = $('#formMethod').val() === 'PUT';
                const url = editing ? BaseUrl + $('#category_id').val() : "{{ route('categories.store') }}";
                const btn = $('#saveBtn');
                const label = btn.text();

                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

                $.ajax({ url: url, method: 'POST', data: $form.serialize() })
                    .done(function (response) {
                        bootstrap.Modal.getOrCreateInstance(document.getElementById('categoryModal')).hide();
                        table.ajax.reload(null, false);
                        showAlert('success', response.message);
                    })
                    .fail(function (xhr) {
                        const errors = xhr.responseJSON && xhr.responseJSON.errors;

                        if (errors) {
                            // Show each message under its own field
                            Object.entries(errors).forEach(([field, messages]) => {
                                $form.find('[name="' + field + '"]').addClass('is-invalid');
                                $form.find('[data-error="' + field + '"]').text(messages[0]);
                            });
                        } else {
                            showAlert('danger', (xhr.responseJSON && xhr.responseJSON.message) || 'Something went wrong.');
                            bootstrap.Modal.getOrCreateInstance(document.getElementById('categoryModal')).hide();
                        }
                    })
                    .always(function () { btn.prop('disabled', false).text(label); });
            });

            /* ===== DELETE ONE ===== */
            $(document).on('click', '.deleteCategories', function (e) {
                e.preventDefault();
                $('#deleteCategoriesForm').data('id', $(this).data('id'));
                bootstrap.Modal.getOrCreateInstance(document.getElementById('deleteCategoriesModal')).show();
            });

            $('#deleteCategoriesForm').on('submit', function (e) {
                e.preventDefault();
                const id = $(this).data('id');
                if (!id) return;

                $.ajax({
                    url: BaseUrl + id,
                    type: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function (response) {
                        $('#deleteCategoriesModal').modal('hide');
                        table.ajax.reload(null, false);
                        showAlert('success', response.message);
                    },
                    error: function (xhr) {
                        $('#deleteCategoriesModal').modal('hide');
                        table.ajax.reload(null, false);
                        showAlert('danger', (xhr.responseJSON && xhr.responseJSON.message) || 'Something went wrong');
                    }
                });
            });

            /* ===== BULK DELETE ===== */
            const bulkDeleteUrl = "{{ Route::has('categories.bulk-delete') ? route('categories.bulk-delete') : url('admin/system_settings/categories/bulk-delete') }}";

            $('#bulkDeleteBtn').on('click', function (e) {
                e.preventDefault();
                const n = selectedIds().length;
                if (!n) return showAlert('danger', 'Please select the categories you want to delete first.');

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

            /* ===== EXPORT (only when the categories.export route exists) ===== */
            @if (Route::has('categories.export'))
            $('#exportCategories').on('click', function (e) {
                e.preventDefault();
                const ids = selectedIds();
                const params = new URLSearchParams();

                if (ids.length) params.set('ids', ids.join(','));
                else if (table.search()) params.set('search', table.search());

                const query = params.toString();
                window.location.href = "{{ route('categories.export') }}" + (query ? '?' + query : '');
            });
            @endif
        });
    </script>
@endpush
