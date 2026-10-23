@extends('admin.layouts.master')
@section('title', __('messages.products_list'))

@php
    $isAdmin       = Auth::user()->group_id == 1;
    $selectedLabel = trans()->has('messages.selected') ? __('messages.selected') : 'selected';
@endphp

@push('styles')
    <style>
        .pl { display: flex; flex-direction: column; gap: 1.25rem; }

        /* ---------- header ---------- */
        .pl-head { display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 1rem; }
        .pl-head h1 { font-size: 1.5rem; font-weight: 800; letter-spacing: -.02em; margin: 0 0 .35rem; color: var(--text-color); }
        .pl-head .breadcrumb { font-size: .8rem; margin: 0; }
        .pl-head .breadcrumb-item a { color: var(--text-muted); text-decoration: none; }
        .pl-head .breadcrumb-item a:hover { color: var(--brand-text); }
        .pl-head .breadcrumb-item.active { color: var(--text-muted); }
        .pl-actions { display: flex; flex-wrap: wrap; gap: .5rem; align-items: center; }
        .pl-actions .btn { height: 36px; display: inline-flex; align-items: center; border-radius: 10px; font-weight: 600; font-size: .85rem; }
        .pl-actions .btn-primary { background: var(--brand) !important; background-image: none !important; border-color: var(--brand) !important; color: #fff !important; box-shadow: none; }
        .pl-actions .btn-primary:hover { background: var(--brand-hover) !important; border-color: var(--brand-hover) !important; }
        .pl-btn-danger { border: 1px solid rgba(229,72,77,.4); color: #E5484D; background: transparent; }
        .pl-btn-danger:hover:not(:disabled) { background: rgba(229,72,77,.1); color: #E5484D; }
        .pl-btn-danger:disabled { opacity: .45; }
        .pl-ip {
            display: inline-flex; align-items: center; gap: .4rem; height: 36px; padding: 0 .75rem; border-radius: 10px;
            background: var(--card-bg); border: 1px solid var(--border-color); font-size: .8rem; color: var(--text-muted);
        }
        .pl-ip b { color: var(--brand-text); font-weight: 700; }

        /* ---------- card & toolbar ---------- */
        .pl-card {
            background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 14px;
            box-shadow: 0 1px 2px rgba(16,24,40,.05);
        }
        .pl-card:hover { transform: none !important; box-shadow: 0 1px 2px rgba(16,24,40,.05) !important; }
        .pl-toolbar { display: flex; flex-wrap: wrap; align-items: center; gap: .6rem; padding: .85rem 1rem; border-bottom: 1px solid var(--border-color); }
        .pl-search { position: relative; flex: 1 1 280px; max-width: 380px; }
        .pl-search i { position: absolute; left: .85rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); pointer-events: none; }
        .pl-search input { padding-left: 2.4rem; height: 38px; font-size: .88rem; background: var(--bg-color); }
        .pl-selection {
            display: none; align-items: center; gap: .4rem; padding: .3rem .3rem .3rem .75rem; border-radius: 10px;
            background: var(--brand-soft); color: var(--brand-text); font-size: .82rem; font-weight: 600;
        }
        .pl-selection.show { display: inline-flex; }
        .pl-selection button {
            width: 26px; height: 26px; border: 0; border-radius: 7px; background: transparent; color: inherit;
            display: grid; place-items: center;
        }
        .pl-selection button:hover { background: rgba(var(--brand-rgb), .18); }

        /* ---------- table ---------- */
        .pl-table-wrap .table-responsive { overflow: visible; }
        #productsTable { font-size: .86rem; margin: 0 !important; width: 100% !important; }
        #productsTable thead th {
            font-size: .68rem; text-transform: uppercase; letter-spacing: .06em; font-weight: 700; white-space: nowrap;
            color: var(--text-muted); background: var(--hover-bg); border: 0 !important;
            border-bottom: 1px solid var(--border-color) !important; padding: .65rem .75rem;
        }
        #productsTable td {
            padding: .55rem .75rem; border-color: var(--border-color) !important; color: var(--text-color);
            background: transparent !important; vertical-align: middle; height: 60px;
        }
        #productsTable tbody tr:hover td { background: var(--hover-bg) !important; }
        #productsTable tbody tr.selected td { background: var(--brand-soft) !important; }
        #productsTable .num { text-align: right; font-variant-numeric: tabular-nums; white-space: nowrap; }
        #productsTable th.num { text-align: right; }
        #productsTable .code { font-family: ui-monospace, SFMono-Regular, Menlo, monospace; font-size: .76rem; color: var(--text-muted); white-space: nowrap; }
        #productsTable .pl-name { display: block; max-width: 280px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        #productsTable .pl-meta { display: block; max-width: 280px; font-size: .72rem; color: var(--text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 1px; }
        #productsTable .img-thumbnail {
            width: 44px; height: 44px; object-fit: cover; border-radius: 10px; padding: 2px;
            border-color: var(--border-color); background: var(--card-bg);
        }
        #productsTable input[type=checkbox], #selectAll { width: 16px; height: 16px; cursor: pointer; accent-color: var(--brand); }
        #productsTable .pl-price { font-variant-numeric: tabular-nums; }
        #productsTable .pl-price-sell { font-weight: 700; }

        .pl-tag {
            display: inline-block; padding: .18rem .55rem; border-radius: 999px; font-size: .72rem; font-weight: 600;
            background: var(--hover-bg); color: var(--text-muted); border: 1px solid var(--border-color); white-space: nowrap;
        }
        .pl-stock { display: inline-flex; align-items: center; gap: .35rem; font-weight: 700; font-variant-numeric: tabular-nums; }
        .pl-stock::before { content: ""; width: 7px; height: 7px; border-radius: 50%; background: #22A55B; }
        .pl-stock.low::before { background: #F59E0B; }
        .pl-stock.low { color: #C47F06; }
        .pl-stock.out { color: #E5484D; }
        .pl-stock.out::before { background: #E5484D; }

        /* row action buttons (server markup) — neutral instead of gradient */
        #productsTable .btn-primary,
        #productsTable .dropdown-toggle,
        #productsTable .btn-info,
        #productsTable .btn-warning,
        #productsTable .btn-danger {
            background: var(--card-bg) !important; background-image: none !important;
            border: 1px solid var(--border-color) !important; color: var(--text-color) !important;
            box-shadow: none !important; font-size: .78rem; font-weight: 600; padding: .3rem .6rem; border-radius: 8px;
        }
        #productsTable .btn-primary:hover,
        #productsTable .dropdown-toggle:hover { background: var(--hover-bg) !important; border-color: var(--brand) !important; color: var(--brand-text) !important; }
        #productsTable .btn-danger:hover { color: #E5484D !important; border-color: rgba(229,72,77,.5) !important; }
        #productsTable .dropdown-menu { min-width: 170px; }

        /* ---------- footer (DataTables 2 layout) ---------- */
        .dt-container { padding: .85rem 1rem 1rem; }
        .dt-container .dt-info { color: var(--text-muted); font-size: .8rem; padding-top: .4rem; }
        .dt-container .dt-length { font-size: .8rem; color: var(--text-muted); margin: 0; }
        .dt-container .dt-length select { border-radius: 8px; font-size: .8rem; padding: .2rem 1.6rem .2rem .5rem; height: 32px; background-color: var(--bg-color); color: var(--text-color); border-color: var(--border-color); }
        .dt-container .dt-paging .dt-paging-button {
            border-radius: 8px !important; border: 1px solid var(--border-color) !important; margin-left: 3px;
            background: var(--card-bg) !important; color: var(--text-color) !important; padding: .3rem .65rem !important; font-size: .8rem;
        }
        .dt-container .dt-paging .dt-paging-button:hover { background: var(--hover-bg) !important; color: var(--brand-text) !important; }
        .dt-container .dt-paging .dt-paging-button.current,
        .dt-container .dt-paging .dt-paging-button.current:hover {
            background: var(--brand) !important; color: #fff !important; border-color: var(--brand) !important;
        }
        .dt-container .dt-paging .dt-paging-button.disabled { opacity: .4; }

        /* ---------- modals ---------- */
        .pl-modal { border-radius: 16px; border: 1px solid var(--border-color); background: var(--card-bg); }
        .pl-modal .modal-header { border-bottom: 1px solid var(--border-color); }
        .pl-modal .modal-footer { border-top: 1px solid var(--border-color); }
        .pl-danger-ic {
            width: 56px; height: 56px; margin: 0 auto .75rem; border-radius: 16px; display: grid; place-items: center;
            background: rgba(229,72,77,.14); color: #E5484D; font-size: 1.5rem;
        }

        /* ---------- responsive ---------- */
        @media (max-width: 1199.98px) {
            .pl-table-wrap .table-responsive { overflow-x: auto; }
            #productsTable { min-width: 920px; }
        }
        @media (max-width: 768px) {
            .pl-actions { width: 100%; }
            .pl-actions .btn { flex: 1; justify-content: center; }
            .pl-search { max-width: none; }
        }
        @media print { .pl-toolbar, .pl-actions, .dt-container .dt-paging, .dt-container .dt-length { display: none !important; } }
    </style>
@endpush

@section('content')
    <div class="pl">

        {{-- Header --}}
        <div class="pl-head">
            <div>
                <h1>{{ $pageTitle }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        @foreach ($breadcrumbs as $breadcrumb)
                            @if (!$breadcrumb['active'])
                                <li class="breadcrumb-item"><a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['label'] }}</a></li>
                            @else
                                <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb['label'] }}</li>
                            @endif
                        @endforeach
                    </ol>
                </nav>
            </div>

            <div class="pl-actions">
            <span class="pl-ip">
                {{ __('messages.ip_address') }}: <b>{{ auth()->user()->ip_address }}</b>
            </span>

                @if ($isAdmin)
                    <a href="{{ url('admin/products/create') }}" id="addProductBtn" class="btn btn-primary px-3">
                        <i class="bi bi-plus-lg me-1"></i>{{ __('messages.add') }}
                    </a>
                    <button type="button" id="exportProducts" class="btn btn-outline-custom px-3">
                        <i class="bi bi-file-earmark-spreadsheet me-1"></i>{{ __('messages.export_to_excel') }}
                    </button>
                    <button type="button" id="bulkDeleteBtn" class="btn pl-btn-danger px-3" disabled>
                        <i class="bi bi-trash me-1"></i>{{ __('messages.delete') }}
                        <span id="bulkCount"></span>
                    </button>
                @endif
            </div>
        </div>

        {{-- Alerts --}}
        <div id="alertsContainer">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show auto-hide mb-0">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show auto-hide mb-0">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>

        {{-- Table card --}}
        <div class="pl-card">
            <div class="pl-toolbar">
                <div class="pl-search">
                    <i class="bi bi-search"></i>
                    <input type="search" id="productSearch" class="form-control" placeholder="{{ __('messages.search') }}…" autocomplete="off">
                </div>

                <span class="pl-selection" id="selectionChip">
                <span id="selectionText"></span>
                <button type="button" id="clearSelection" aria-label="Clear selection">
                    <i class="bi bi-x-lg"></i>
                </button>
            </span>
            </div>

            <div class="pl-table-wrap">
                <div class="table-responsive">
                    <table id="productsTable" class="table align-middle">
                        <thead>
                        <tr>
                            <th style="width:36px"><input type="checkbox" id="selectAll" aria-label="Select all"></th>
                            <th style="width:64px">{{ __('messages.image') }}</th>
                            <th style="width:120px">{{ __('messages.code') }}</th>
                            <th>{{ __('messages.name') }}</th>
                            <th>{{ __('messages.brand') }}</th>
                            <th>{{ __('messages.category') }}</th>
                            <th>{{ __('messages.quality') }}</th>
                            <th>{{ __('messages.unit') }}</th>
                            <th class="num" style="width:110px">{{ __('messages.quantity') }}</th>
                            <th class="num" style="width:110px">{{ __('messages.cost_price') }}</th>
                            <th class="num" style="width:120px">{{ __('messages.selling_price') }}</th>
                            <th class="text-center" style="width:120px">{{ __('messages.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Image preview --}}
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content pl-modal">
                <div class="modal-body text-center p-3">
                    <img id="modalImage" src="{{ asset('noimage.png') }}" alt="{{ __('messages.image') }}" class="img-fluid rounded-3">
                </div>
            </div>
        </div>
    </div>

    {{-- Delete single --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content pl-modal text-center">
                <div class="modal-body p-4">
                    <div class="pl-danger-ic"><i class="bi bi-trash"></i></div>
                    <h5 class="fw-bold mb-1" id="deleteModalLabel">{{ __('messages.delete') }}</h5>
                    <p class="text-muted small mb-0">{{ __('messages.delete_confirm') }}</p>
                </div>
                <div class="modal-footer justify-content-center border-0 pt-0">
                    <button type="button" class="btn btn-outline-custom btn-sm px-3" data-bs-dismiss="modal">{{ __('messages.no') }}</button>
                    <form id="deleteForm" method="POST" class="m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm px-3">{{ __('messages.yes') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete bulk --}}
    <div class="modal fade" id="bulkDeleteModal" tabindex="-1" aria-labelledby="bulkDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content pl-modal text-center">
                <div class="modal-body p-4">
                    <div class="pl-danger-ic"><i class="bi bi-trash3"></i></div>
                    <h5 class="fw-bold mb-1" id="bulkDeleteModalLabel">{{ __('messages.delete') }}</h5>
                    <p class="text-muted small mb-0">
                        <span id="bulkDeleteText"></span> {{ __('messages.delete_confirm') }}
                    </p>
                </div>
                <div class="modal-footer justify-content-center border-0 pt-0">
                    <button type="button" class="btn btn-outline-custom btn-sm px-3" data-bs-dismiss="modal">{{ __('messages.no') }}</button>
                    <button type="button" id="bulkDeleteConfirm" class="btn btn-danger btn-sm px-3">{{ __('messages.yes') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const BaseUrl   = "{{ url('admin/products') }}/";
        const bulkUrl   = "{{ route('products.bulk-destroy') }}";
        const exportUrl = "{{ route('products.export') }}";
        const imageBase = "{{ asset('storage') }}";
        const noimage   = "{{ asset('noimage.png') }}";
        const CSRF      = "{{ csrf_token() }}";
        const SELECTED_LABEL = @json($selectedLabel);

        $(function () {
            const selected = new Map(); // id -> true, kept across pages

            const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
            }[c]));

            const isEmpty = v => v === null || v === undefined || v === '' || v === 'N/A';

            function refreshSelectionUi() {
                const n = selected.size;
                $('#selectionText').text(`${n} ${SELECTED_LABEL}`);
                $('#selectionChip').toggleClass('show', n > 0);
                $('#bulkDeleteBtn').prop('disabled', n === 0);
                $('#bulkCount').text(n ? `(${n})` : '');

                const $boxes = $('.ProductCheckbox');
                const checked = $boxes.filter(':checked').length;
                $('#selectAll')
                    .prop('checked', $boxes.length > 0 && checked === $boxes.length)
                    .prop('indeterminate', checked > 0 && checked < $boxes.length);
            }

            const table = $('#productsTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: false,
                autoWidth: false,
                ajax: "{{ route('products.index') }}",
                pageLength: 10,
                lengthMenu: [[10, 20, 30, 50, -1], [10, 20, 30, 50, "{{ __('messages.all') }}"]],
                order: [],
                layout: {
                    topStart: null,
                    topEnd: null,
                    bottomStart: ['pageLength', 'info'],
                    bottomEnd: 'paging'
                },
                columns: [
                    {
                        data: 'id', name: 'id', orderable: false, searchable: false, className: 'text-center',
                        render: id => `<input type="checkbox" class="ProductCheckbox" value="${id}" ${selected.has(String(id)) ? 'checked' : ''}>`
                    },
                    {
                        data: 'image', name: 'image', orderable: false, searchable: false,
                        render: function (data) {
                            const src = data ? `${imageBase}/${data}` : noimage;
                            return `<a href="#" class="image-popup" data-bs-toggle="modal" data-bs-target="#imageModal" data-image="${src}">
                                    <img src="${src}" class="img-thumbnail" alt="">
                                </a>`;
                        }
                    },
                    {
                        data: 'code', name: 'code',
                        render: c => `<span class="code">${esc(c)}</span>`
                    },
                    {
                        // Brand / quality / unit are shown here as a meta line.
                        data: 'name', name: 'name',
                        render: function (name, type, row) {
                            if (type !== 'display') return name ?? '';
                            const meta = [row.brand_name, row.quality_name, row.unit_name]
                                .filter(v => !isEmpty(v)).map(esc).join(' · ');
                            return `<span class="pl-name" title="${esc(name)}">${esc(name)}</span>`
                                + (meta ? `<span class="pl-meta">${meta}</span>` : '');
                        }
                    },
                    { data: 'brand_name', name: 'brand_name', visible: false, defaultContent: '' },
                    {
                        data: 'category_name', name: 'category_name',
                        render: c => isEmpty(c) ? '<span class="text-muted">—</span>' : `<span class="pl-tag">${esc(c)}</span>`
                    },
                    { data: 'quality_name', name: 'quality_name', visible: false, defaultContent: '' },
                    { data: 'unit_name', name: 'unit_name', visible: false, defaultContent: '' },
                    {
                        data: 'stock_quantity', name: 'stock_quantity', className: 'num',
                        render: function (q) {
                            const n = Number(q);
                            if (isNaN(n)) return esc(q);
                            const cls = n <= 0 ? 'out' : (n <= 5 ? 'low' : '');
                            return `<span class="pl-stock ${cls}">${n.toLocaleString()}</span>`;
                        }
                    },
                    {
                        data: 'cost_price', name: 'cost_price', className: 'num pl-price',
                        render: p => isNaN(Number(p)) ? esc(p) : Number(p).toFixed(2)
                    },
                    {
                        data: 'selling_price', name: 'selling_price', className: 'num pl-price pl-price-sell',
                        render: p => isNaN(Number(p)) ? esc(p) : Number(p).toFixed(2)
                    },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                ],
                drawCallback: function () {
                    $('#productsTable tbody tr').each(function () {
                        $(this).toggleClass('selected', $(this).find('.ProductCheckbox').is(':checked'));
                    });
                    refreshSelectionUi();
                },
                language: {
                    paginate: {
                        previous: '<i class="bi bi-chevron-left"></i>',
                        next: '<i class="bi bi-chevron-right"></i>'
                    },
                    lengthMenu: '{{ __('messages.show') }} _MENU_ {{ __('messages.entries') }}',
                    info: '{{ __('messages.showing') ?? 'Showing' }} _START_–_END_ / _TOTAL_',
                    emptyTable: "{{ __('messages.no_data_available') }}",
                    processing: "{{ __('messages.processing') }}"
                }
            });

            // Custom search box
            let searchTimer;
            $('#productSearch').on('input', function () {
                clearTimeout(searchTimer);
                const v = this.value;
                searchTimer = setTimeout(() => table.search(v).draw(), 300);
            });

            // Row checkboxes
            $('#productsTable').on('change', '.ProductCheckbox', function () {
                const id = String($(this).val());
                $(this).is(':checked') ? selected.set(id, true) : selected.delete(id);
                refreshSelectionUi();
            });

            // Select all on current page
            $('#selectAll').on('change', function () {
                const checked = $(this).is(':checked');
                $('.ProductCheckbox').each(function () {
                    $(this).prop('checked', checked);
                    const id = String($(this).val());
                    checked ? selected.set(id, true) : selected.delete(id);
                });
                refreshSelectionUi();
            });

            $('#clearSelection').on('click', function () {
                selected.clear();
                $('.ProductCheckbox').prop('checked', false);
                refreshSelectionUi();
            });

            // Image preview
            $('#productsTable').on('click', '.image-popup', function (e) {
                e.preventDefault();
                $('#modalImage').attr('src', $(this).data('image'));
            });

            // Single delete
            $(document).on('click', '.deleteBtn', function () {
                $('#deleteForm').attr('action', BaseUrl + $(this).data('id'));
                $('#deleteModal').modal('show');
            });

            function showAlert(type, message) {
                $('#alertsContainer').html(`
                <div class="alert alert-${type} alert-dismissible fade show mb-0" role="alert">
                    ${esc(message)}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`);
            }

            const errorMessage = xhr =>
                (xhr.responseJSON && xhr.responseJSON.message) || 'Something went wrong';

            $('#deleteForm').on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'DELETE',
                    data: $(this).serialize(),
                    success: function (res) {
                        $('#deleteModal').modal('hide');
                        selected.clear();
                        table.ajax.reload(null, false);
                        showAlert('success', res.message);
                    },
                    error: function (xhr) {
                        $('#deleteModal').modal('hide');
                        showAlert('danger', errorMessage(xhr));
                    }
                });
            });

            // Bulk delete
            $('#bulkDeleteBtn').on('click', function () {
                if (!selected.size) return;
                $('#bulkDeleteText').text(`${selected.size} ×`);
                $('#bulkDeleteModal').modal('show');
            });

            $('#bulkDeleteConfirm').on('click', function () {
                const $btn = $(this).prop('disabled', true);
                $.ajax({
                    url: bulkUrl,
                    type: 'POST',
                    data: { _token: CSRF, _method: 'DELETE', ids: Array.from(selected.keys()) },
                    success: function (res) {
                        $('#bulkDeleteModal').modal('hide');
                        selected.clear();
                        table.ajax.reload(null, false);
                        showAlert('success', res.message);
                    },
                    error: function (xhr) {
                        $('#bulkDeleteModal').modal('hide');
                        showAlert('danger', errorMessage(xhr));
                    },
                    complete: () => $btn.prop('disabled', false)
                });
            });

            // Export: selected IDs across pages, or everything if none selected
            $('#exportProducts').on('click', function () {
                const ids = Array.from(selected.keys());
                window.location.href = ids.length ? `${exportUrl}?ids=${ids.join(',')}` : exportUrl;
            });
        });
    </script>
@endpush
