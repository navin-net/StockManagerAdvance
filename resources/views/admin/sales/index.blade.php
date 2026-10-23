@extends('admin.layouts.master')
@section('title', __('messages.sales_list'))

@php
    // Falls back to English if a translation key hasn't been added yet.
    $t = fn($key, $default) => \Illuminate\Support\Facades\Lang::has("messages.$key") ? __("messages.$key") : $default;

    // Strings used by the JavaScript below.
    $i18n = [
        'completed' => $t('completed', 'Completed'),
        'cancelled' => $t('cancelled', 'Cancelled'),
        'pending' => $t('pending', 'Pending'),
        'paid' => $t('paid', 'Paid'),
        'partial' => $t('partial', 'Partial'),
        'due' => $t('due', 'Due'),
        'unpaid' => $t('unpaid', 'Unpaid'),
        'loading' => $t('loading', 'Loading...'),
        'loadingPayments' => $t('loading_payments', 'Loading payment data...'),
        'noRecord' => $t('no_record_found', 'No record found.'),
        'deleting' => $t('deleting', 'Deleting...'),
        'deleted' => $t('sale_deleted', 'Sale deleted successfully.'),
        'wrong' => $t('something_went_wrong', 'Something went wrong.'),
        'delete' => __('messages.delete'),
        'emptyTitle' => $t('no_sales_found', 'No sales found'),
        'emptyHint' => $t('try_another_search', 'Try a different search term or filter.'),
        'show' => __('messages.show'),
        'entries' => __('messages.entries'),
        'search' => __('messages.search'),
        'noData' => __('messages.no_data_available'),
        'processing' => __('messages.processing'),
        'noMatch' => __('messages.no_matching_records'),
        'infoEmpty' => __('messages.showing_0_to_0_of_0_entries'),
        'infoFiltered' => __('messages.filtered_from_total_entries', ['total' => '_MAX_']),
    ];
@endphp

@section('content')
    <style>
        /* ==========================================================
           Sales list - built on layout-v2.css tokens
           (same look as the product list)
           ========================================================== */
        .pl-card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 14px; overflow: hidden; }
        .pl-card:hover { transform: none; box-shadow: 0 1px 2px rgba(16, 24, 40, .05); }

        /* page head */
        .pl-head { display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: .75rem 1rem; margin-bottom: 1.25rem; }
        .pl-head h1 { font-size: 1.5rem; font-weight: 800; letter-spacing: -.02em; margin: 0 0 .35rem; }
        .pl-head .breadcrumb { font-size: .8rem; }
        .pl-chip { display: inline-flex; align-items: center; gap: .45rem; padding: .35rem .75rem; border-radius: 999px; border: 1px solid var(--border-color); background: var(--card-bg); font-size: .78rem; color: var(--text-muted); }
        .pl-chip b { color: var(--text-color); font-weight: 600; }

        /* toolbar */
        .pl-toolbar { display: flex; flex-wrap: wrap; align-items: center; gap: .6rem; padding: 1rem 1.1rem; border-bottom: 1px solid var(--border-color); }
        .pl-search { position: relative; flex: 1 1 260px; max-width: 420px; }
        .pl-search i { position: absolute; left: .85rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); pointer-events: none; }
        .pl-search input { width: 100%; height: 40px; padding: 0 2.2rem 0 2.4rem; border-radius: 10px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-color); }
        .pl-search input::placeholder { color: var(--text-muted); }
        .pl-search input:focus { outline: none; border-color: var(--brand); background: var(--card-bg); box-shadow: 0 0 0 .2rem rgba(var(--brand-rgb), .18); }
        .pl-search button { position: absolute; right: .35rem; top: 50%; transform: translateY(-50%); border: 0; background: transparent; color: var(--text-muted); width: 28px; height: 28px; border-radius: 8px; display: none; }
        .pl-search button:hover { background: var(--hover-bg); color: var(--text-color); }
        .pl-search.has-value button { display: block; }
        .pl-len { height: 40px; width: auto; padding-right: 2rem; }
        .pl-count { color: var(--text-muted); font-size: .84rem; white-space: nowrap; }
        .pl-count b { color: var(--text-color); font-weight: 700; }
        .pl-actions { margin-left: auto; display: flex; gap: .5rem; flex-wrap: wrap; }
        .pl-actions .btn { height: 40px; display: inline-flex; align-items: center; gap: .45rem; }

        /* status filter */
        .pl-tabs { display: flex; gap: .35rem; padding: .7rem 1.1rem; border-bottom: 1px solid var(--border-color); overflow-x: auto; scrollbar-width: none; }
        .pl-tabs::-webkit-scrollbar { display: none; }
        .pl-tabs button { flex: none; border: 1px solid transparent; background: transparent; color: var(--text-muted); font-weight: 600; font-size: .84rem; padding: .35rem .9rem; border-radius: 999px; }
        .pl-tabs button:hover { background: var(--hover-bg); color: var(--text-color); }
        .pl-tabs button.on { background: var(--brand-soft); color: var(--brand-text); }

        /* selection strip */
        .pl-selbar { display: none; align-items: center; gap: .75rem; padding: .6rem 1.1rem; background: var(--brand-soft); border-bottom: 1px solid var(--border-color); font-size: .86rem; }
        .pl-selbar.show { display: flex; }
        .pl-selbar b { color: var(--brand-text); }
        .pl-selbar .sp { flex: 1; }
        .pl-selbar button { border: 0; background: transparent; color: var(--brand-text); font-weight: 700; font-size: .84rem; padding: .3rem .6rem; border-radius: 8px; }
        .pl-selbar button:hover { background: var(--brand-soft); text-decoration: underline; }

        /* table */
        .pl-table-wrap { padding: 0 .25rem; }
        table.pl-table { --bs-table-bg: transparent; --bs-table-striped-bg: transparent; width: 100% !important; margin: 0 !important; border-collapse: collapse; }
        table.pl-table thead th { background: transparent; color: var(--text-muted); font-size: .76rem; font-weight: 600; letter-spacing: .01em; white-space: nowrap; padding: .8rem .9rem; border: 0; border-bottom: 1px solid var(--border-color); }
        table.pl-table tbody td { padding: .75rem .9rem; border: 0; border-bottom: 1px solid var(--border-color); vertical-align: middle; background: transparent !important; box-shadow: none; }
        table.pl-table tbody tr:last-child td { border-bottom: 0; }
        table.pl-table tbody tr:hover td { background: var(--hover-bg) !important; }
        table.pl-table tbody tr.is-selected td { background: var(--brand-soft) !important; }
        table.pl-table .num { text-align: right; font-variant-numeric: tabular-nums; white-space: nowrap; }
        table.pl-table thead th.num { text-align: right; }
        table.pl-table .muted { color: var(--text-muted); }
        table.pl-table input[type=checkbox] { width: 1.05rem; height: 1.05rem; accent-color: var(--brand); cursor: pointer; }
        table.dataTable > thead > tr > th.sorting:before, table.dataTable > thead > tr > th.sorting:after { opacity: .25; }
        table.pl-table td:last-child .btn { border-radius: 8px; }

        /* cells */
        .pl-ref { font-weight: 700; letter-spacing: .01em; white-space: nowrap; }
        .pl-person { display: flex; align-items: center; gap: .65rem; min-width: 150px; }
        .pl-av { flex: none; width: 34px; height: 34px; border-radius: 50%; display: grid; place-items: center; background: var(--brand-soft); color: var(--brand-text); font-weight: 700; font-size: .78rem; }
        .pl-person span { font-weight: 600; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 200px; }
        .pl-total { font-weight: 700; }
        .pl-bal.due { color: #E5484D; font-weight: 700; }
        .pl-bal.zero { color: var(--text-muted); }

        /* status badges */
        .pl-badge { display: inline-flex; align-items: center; gap: .4rem; padding: .2rem .65rem .2rem .5rem; border-radius: 999px; background: var(--hover-bg); color: var(--text-muted); font-size: .76rem; font-weight: 600; white-space: nowrap; }
        .pl-badge::before { content: ""; width: 7px; height: 7px; border-radius: 50%; background: currentColor; }
        .pl-badge.ok { background: rgba(34, 197, 94, .14); color: #1E9A52; }
        .pl-badge.warn { background: rgba(245, 158, 11, .16); color: #B97405; }
        .pl-badge.bad { background: rgba(229, 72, 77, .14); color: #E5484D; }

        /* footer / pagination */
        .pl-foot { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: .75rem; padding: .9rem 1.1rem; border-top: 1px solid var(--border-color); }
        .pl-foot .dataTables_info { color: var(--text-muted); font-size: .82rem; padding: 0; }
        .pl-foot .dataTables_paginate { padding: 0; }
        .pl-foot .pagination { margin: 0; gap: .2rem; }
        .pl-foot .page-link { border: 0; border-radius: 8px !important; min-width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; background: transparent; color: var(--text-color) !important; }
        .pl-foot .page-link:hover { background: var(--hover-bg); }
        .pl-foot .page-item.active .page-link { background: var(--brand) !important; color: #fff !important; }
        .pl-foot .page-item.disabled .page-link { opacity: .35; background: transparent; }
        div.dataTables_processing { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 10px; color: var(--text-color); }

        /* empty */
        .pl-empty { display: flex; flex-direction: column; align-items: center; gap: .4rem; padding: 3rem 1rem; color: var(--text-muted); }
        .pl-empty i { font-size: 2rem; opacity: .6; }
        .pl-empty b { color: var(--text-color); }

        /* modals */
        .pl-del-ic { width: 52px; height: 52px; border-radius: 14px; display: grid; place-items: center; margin: 0 auto .9rem; background: rgba(229, 72, 77, .14); color: #E5484D; font-size: 1.4rem; }
        .pl-del-name { margin-top: .6rem; padding: .5rem .75rem; border-radius: 10px; background: var(--hover-bg); font-weight: 600; word-break: break-word; }
        .pl-modal-title { font-weight: 700; }
        #paymentModal .form-label { font-weight: 600; font-size: .84rem; }
        #list-payment-sale .dataTables_wrapper { padding: 0; }

        @media (max-width: 767.98px) {
            .pl-actions { margin-left: 0; width: 100%; }
            .pl-actions .btn { flex: 1; justify-content: center; }
            .pl-search { max-width: none; }
            .pl-foot { justify-content: center; }
        }
    </style>

    <div class="container-fluid p-0">

        {{-- page head --}}
        <div class="pl-head">
            <div>
                <h1>{{ $pageTitle }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        @foreach ($breadcrumbs as $breadcrumb)
                            @if (!$breadcrumb['active'])
                                <li class="breadcrumb-item">
                                    <a href="{{ $breadcrumb['url'] }}" class="text-decoration-none">{{ $breadcrumb['label'] }}</a>
                                </li>
                            @else
                                <li class="breadcrumb-item active text-muted" aria-current="page">{{ $breadcrumb['label'] }}</li>
                            @endif
                        @endforeach
                    </ol>
                </nav>
            </div>
            <span class="pl-chip">
                <i class="bi bi-hdd-network"></i>
                {{ __('messages.ip_address') }}: <b>{{ auth()->user()->ip_address }}</b>
            </span>
        </div>

        <div id="alertsContainer">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <div class="pl-card">

            {{-- toolbar --}}
            <div class="pl-toolbar">
                <div class="pl-search" id="plSearchWrap">
                    <i class="bi bi-search"></i>
                    <input type="search" id="plSearch" autocomplete="off"
                           placeholder="{{ __('messages.search') }}  ({{ $t('sales_search_hint', 'reference, customer, biller') }})">
                    <button type="button" id="plSearchClear" aria-label="Clear"><i class="bi bi-x-lg"></i></button>
                </div>

                <select id="plLen" class="form-select pl-len" aria-label="{{ __('messages.show') }}">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="50">50</option>
                    <option value="-1">{{ __('messages.all') }}</option>
                </select>

                <span class="pl-count"><b id="plCount">0</b> {{ $t('sales', 'sales') }}</span>

                <div class="pl-actions">
                    <a href="#" id="exportSales" class="btn btn-outline-custom">
                        <i class="bi bi-file-earmark-excel"></i>{{ __('messages.export_to_excel') }}
                    </a>
                    <a href="{{ route('sales.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i>{{ __('messages.add') }}
                    </a>
                </div>
            </div>

            {{-- status filter --}}
            <div class="pl-tabs" id="plTabs" role="group" aria-label="{{ __('messages.status') }}">
                <button type="button" class="on" data-status="">{{ $t('all_sales', 'All') }}</button>
                <button type="button" data-status="completed">{{ $i18n['completed'] }}</button>
                <button type="button" data-status="pending">{{ $i18n['pending'] }}</button>
                <button type="button" data-status="cancelled">{{ $i18n['cancelled'] }}</button>
            </div>

            {{-- selection strip --}}
            <div class="pl-selbar" id="plSelBar" role="status">
                <span><b id="plSelCount">0</b> {{ $t('selected', 'selected') }}</span>
                <span class="sp"></span>
                <button type="button" id="plClearSel">{{ $t('clear_selection', 'Clear selection') }}</button>
            </div>

            {{-- table --}}
            <div class="pl-table-wrap">
                <table id="salesTable" class="table pl-table align-middle">
                    <thead>
                    <tr>
                        <th style="width:36px"><input type="checkbox" id="selectAll" aria-label="Select all"></th>
                        <th>{{ __('messages.date') }}</th>
                        <th>{{ __('messages.reference') }}</th>
                        <th>{{ __('messages.customer') }}</th>
                        <th>{{ __('messages.billers') }}</th>
                        <th class="text-center">{{ __('messages.status') }}</th>
                        <th class="num">{{ __('messages.grand_total') }}</th>
                        <th class="num">{{ __('messages.paid') }}</th>
                        <th class="num">{{ __('messages.balance') }}</th>
                        <th class="text-center">{{ __('messages.payment_status') }}</th>
                        <th class="text-center" style="width:120px">{{ __('messages.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Add payment modal --}}
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title pl-modal-title" id="paymentModalLabel">{{ $t('add_payment', 'Add Payment') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="paymentForm" enctype="multipart/form-data">
                        <input type="hidden" name="sale_id" value=""> <!-- filled via JS -->

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ $t('amount', 'Amount') }} <span class="text-danger">*</span></label>
                                <input type="number" name="amount" step="0.01" min="0.01" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('messages.reference') }}</label>
                                <input type="text" name="reference" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ $t('payment_method', 'Payment Method') }} <span class="text-danger">*</span></label>
                                <select name="methods" class="form-select" required>
                                    <option value="">-- {{ $t('select_method', 'Select Method') }} --</option>
                                    <option value="cash">{{ $t('cash', 'Cash') }}</option>
                                    <option value="card">{{ $t('card', 'Card') }}</option>
                                    <option value="bank">{{ $t('bank_transfer', 'Bank Transfer') }}</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ $t('payment_date', 'Payment Date') }} <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="payment_date" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">{{ $t('attachment', 'Attachment') }}</label>
                                <input type="file" name="attachment" class="form-control">
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary" id="btnSubmitPayment">{{ $t('confirm_payment', 'Confirm Payment') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Payment list modal --}}
    <div class="modal fade" id="ListpaymentModal" tabindex="-1" aria-labelledby="ListpaymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pl-modal-title" id="ListpaymentModalLabel">
                        {{ __('messages.payment_list') }} - <span id="sale-id"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="list-payment-sale">
                        <div class="text-center p-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete confirmation modal --}}
    <div class="modal fade" id="deleteSale" tabindex="-1" aria-labelledby="deleteSaleLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow">
                <div class="modal-body text-center p-4">
                    <div class="pl-del-ic"><i class="bi bi-trash3"></i></div>
                    <h5 class="fw-bold mb-1" id="deleteSaleLabel">{{ __('messages.confirm_delete') }}</h5>
                    <div class="text-muted">{{ __('messages.delete_confirm') }}</div>
                    <div class="pl-del-name" id="deleteName"></div>
                </div>
                <div class="modal-footer border-0 justify-content-center pt-0 pb-4 gap-2">
                    <button type="button" class="btn btn-outline-custom btn-sm px-4" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    <form id="deleteForm" class="m-0">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm px-4">{{ __('messages.delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const I18N = @json($i18n);

        const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
        }[c]));

        function formatForDatetimeLocal(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            const offset = date.getTimezoneOffset();
            const localDate = new Date(date.getTime() - offset * 60 * 1000);
            return localDate.toISOString().slice(0, 16);
        }

        function showAlert(type, html) {
            const $a = $(`
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${html}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`);
            $('#alertsContainer').html($a);
            setTimeout(() => $a.alert('close'), 5000);
        }

        /* Sale status and payment status share one badge mapper.
           Unknown values are shown as-is instead of being labelled "pending". */
        const StatusMapper = {
            map: {
                completed: { c: 'ok',   l: I18N.completed },
                paid:      { c: 'ok',   l: I18N.paid },
                pending:   { c: 'warn', l: I18N.pending },
                partial:   { c: 'warn', l: I18N.partial },
                cancelled: { c: 'bad',  l: I18N.cancelled },
                due:       { c: 'bad',  l: I18N.due },
                unpaid:    { c: 'bad',  l: I18N.unpaid }
            },
            renderBadge: function (data, type) {
                if (type !== 'display') return data;
                const b = StatusMapper.map[String(data ?? '').toLowerCase()];
                return b
                    ? `<span class="pl-badge ${b.c}">${esc(b.l)}</span>`
                    : `<span class="pl-badge">${esc(data)}</span>`;
            }
        };

        const toNumber = v => parseFloat(String(v ?? '').replace(/[^0-9.\-]/g, '')) || 0;
        const initials = name => String(name ?? '').trim().split(/\s+/).slice(0, 2).map(w => w.charAt(0)).join('').toUpperCase() || '?';

        const paginate = {
            previous: `<i class="bi bi-chevron-left"></i>`,
            next: `<i class="bi bi-chevron-right"></i>`
        };

        $(document).ready(function () {

            /* =========================
             *  SALES TABLE
             * ========================= */
            const table = $('#salesTable').DataTable({
                pageLength: 10,
                lengthMenu: [[10, 20, 30, 50, -1], [10, 20, 30, 50, "{{ __('messages.all') }}"]],
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('sales.getData') }}",
                order: [[1, 'desc']],
                // r = processing, t = table, then info + pagination. Search and page length live in the toolbar.
                dom: 'rt<"pl-foot"ip>',

                columns: [
                    { // 0
                        data: 'id', name: 'id', orderable: false, searchable: false, responsivePriority: 1,
                        render: d => `<input type="checkbox" class="saleCheckbox" value="${esc(d)}">`
                    },
                    { // 1
                        data: 'created_at', name: 'created_at', responsivePriority: 8,
                        render: (d, t) => t === 'display' ? `<span class="muted">${esc(d)}</span>` : d
                    },
                    { // 2
                        data: 'reference', name: 'reference', responsivePriority: 1,
                        render: (d, t) => t === 'display' ? `<span class="pl-ref">${esc(d)}</span>` : d
                    },
                    { // 3
                        data: 'customer', name: 'customer', defaultContent: 'N/A', responsivePriority: 2,
                        render: function (d, t) {
                            if (t !== 'display') return d;
                            return `<div class="pl-person"><div class="pl-av">${esc(initials(d))}</div><span title="${esc(d)}">${esc(d)}</span></div>`;
                        }
                    },
                    { // 4
                        data: 'biller', name: 'biller', defaultContent: 'N/A', responsivePriority: 9,
                        render: (d, t) => t === 'display' ? `<span class="muted">${esc(d)}</span>` : d
                    },
                    { // 5
                        data: 'status', name: 'status', className: 'text-center', responsivePriority: 6,
                        render: StatusMapper.renderBadge
                    },
                    { // 6
                        data: 'grand_total', name: 'grand_total', className: 'num', responsivePriority: 3,
                        render: (d, t) => t === 'display' ? `<span class="pl-total">${esc(d)}</span>` : d
                    },
                    { // 7
                        data: 'paid', name: 'paid', className: 'num', responsivePriority: 7,
                        render: (d, t) => t === 'display' ? `<span class="muted">${esc(d)}</span>` : d
                    },
                    { // 8
                        data: 'balance', name: 'balance', className: 'num', responsivePriority: 4,
                        render: function (d, t) {
                            if (t !== 'display') return d;
                            return `<span class="pl-bal ${toNumber(d) > 0 ? 'due' : 'zero'}">${esc(d)}</span>`;
                        }
                    },
                    { // 9
                        data: 'payment_status', name: 'payment_status', className: 'text-center', responsivePriority: 5,
                        render: StatusMapper.renderBadge
                    },
                    { // 10
                        data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center', responsivePriority: 2
                    }
                ],

                language: {
                    paginate: paginate,
                    lengthMenu: `${I18N.show} _MENU_ ${I18N.entries}`,
                    search: I18N.search,
                    emptyTable: `<div class="pl-empty"><i class="bi bi-receipt"></i><b>${esc(I18N.noData)}</b></div>`,
                    zeroRecords: `<div class="pl-empty"><i class="bi bi-search"></i><b>${esc(I18N.emptyTitle)}</b><span>${esc(I18N.emptyHint)}</span></div>`,
                    processing: I18N.processing,
                    infoEmpty: I18N.infoEmpty,
                    infoFiltered: I18N.infoFiltered
                }
            });

            /* ---------- toolbar ---------- */
            table.on('xhr.dt', function (e, settings, json) {
                if (json) $('#plCount').text(Number(json.recordsFiltered ?? json.recordsTotal ?? 0).toLocaleString());
            });

            let searchTimer;
            $('#plSearch').on('input', function () {
                const v = this.value;
                $('#plSearchWrap').toggleClass('has-value', v.length > 0);
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => table.search(v).draw(), 300);
            });
            $('#plSearchClear').on('click', function () {
                $('#plSearch').val('').trigger('input').trigger('focus');
            });
            $('#plLen').on('change', function () { table.page.len(parseInt(this.value, 10)).draw(); });

            // Status filter (searches the "status" column, index 5)
            $('#plTabs').on('click', 'button', function () {
                $('#plTabs button').removeClass('on');
                $(this).addClass('on');
                table.column(5).search($(this).data('status') || '').draw();
            });

            /* ---------- selection ---------- */
            function updateSelection() {
                const $all = $('.saleCheckbox');
                const $on = $all.filter(':checked');
                $all.each(function () { $(this).closest('tr').toggleClass('is-selected', this.checked); });
                $('#plSelCount').text($on.length);
                $('#plSelBar').toggleClass('show', $on.length > 0);
                const sa = $('#selectAll')[0];
                sa.checked = $all.length > 0 && $on.length === $all.length;
                sa.indeterminate = $on.length > 0 && $on.length < $all.length;
            }
            $('#selectAll').on('click', function () {
                $('.saleCheckbox').prop('checked', this.checked);
                updateSelection();
            });
            $('#salesTable').on('change', '.saleCheckbox', updateSelection);
            $('#plClearSel').on('click', function () {
                $('.saleCheckbox').prop('checked', false);
                updateSelection();
            });
            table.on('draw', updateSelection);

            /* ===================
             * Payment list
             * =================== */
            $(document).on('click', '.list-payment-sale', function (e) {
                e.preventDefault();
                const saleId = $(this).data('id');
                const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('ListpaymentModal'));

                $('#sale-id').text(I18N.loading);
                $('#list-payment-sale').html(`
                    <div class="text-center p-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">${esc(I18N.loading)}</span>
                        </div>
                        <p class="mt-2 text-muted">${esc(I18N.loadingPayments)}</p>
                    </div>`);
                modal.show();

                $.ajax({
                    url: `{{ url('admin/sales/listPayments') }}/${saleId}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        if (!response.success) {
                            $('#list-payment-sale').html(`<div class="alert alert-warning mb-0">${esc(I18N.noRecord)}</div>`);
                            return;
                        }
                        const sale = response.data;
                        $('#sale-id').text(sale.reference);

                        $('#list-payment-sale').html(`
                            <table id="payment-table" class="table pl-table align-middle w-100">
                                <thead>
                                    <tr>
                                        <th>{{ __('messages.reference') }}</th>
                                        <th>{{ __('messages.date') }}</th>
                                        <th class="num">{{ __('messages.amount') }}</th>
                                        <th>{{ __('messages.method') }}</th>
                                        <th class="text-center">{{ __('messages.status') }}</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>`);

                        $('#payment-table').DataTable({
                            data: sale.payments,
                            columns: [
                                { data: 'reference', render: d => `<span class="pl-ref">${esc(d)}</span>` },
                                {
                                    data: 'paid_at',
                                    render: function (data) {
                                        if (!data) return '-';
                                        return new Date(data).toLocaleString('en-GB', {
                                            day: '2-digit', month: '2-digit', year: 'numeric',
                                            hour: '2-digit', minute: '2-digit'
                                        });
                                    }
                                },
                                {
                                    data: 'amount', className: 'num',
                                    render: d => '$' + parseFloat(d).toLocaleString('en-US', {
                                        minimumFractionDigits: 2, maximumFractionDigits: 2
                                    })
                                },
                                { data: 'method', render: d => esc(d) },
                                {
                                    data: null, className: 'text-center',
                                    render: () => `<span class="pl-badge ok">${esc(I18N.completed)}</span>`
                                }
                            ],
                            language: {
                                paginate: paginate,
                                lengthMenu: `${I18N.show} _MENU_ ${I18N.entries}`,
                                search: I18N.search,
                                emptyTable: I18N.noData,
                                zeroRecords: I18N.noMatch,
                                infoEmpty: I18N.infoEmpty,
                                infoFiltered: I18N.infoFiltered
                            }
                        });
                    },
                    error: function () {
                        $('#list-payment-sale').html(`<div class="alert alert-danger mb-0">${esc(I18N.wrong)}</div>`);
                    }
                });
            });

            /* ===================
             * Delete sale
             * =================== */
            $(document).on('click', '.delete-sale', function (e) {
                e.preventDefault();

                const id = $(this).data('id');
                if (!id) { console.error('Sale ID is missing.'); return; }

                let $tr = $(this).closest('tr');
                if ($tr.hasClass('child')) $tr = $tr.prev();
                const row = table.row($tr).data();

                $('#deleteName').text(row ? row.reference : '').toggle(!!row);
                $('#deleteForm').data('id', id);
                bootstrap.Modal.getOrCreateInstance(document.getElementById('deleteSale')).show();
            });

            $(document).on('submit', '#deleteForm', function (e) {
                e.preventDefault();

                const id = $(this).data('id');
                if (!id) { console.error('Sale ID is missing.'); return; }

                const $btn = $('#deleteForm button[type="submit"]');
                $btn.prop('disabled', true).html(
                    `<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>${esc(I18N.deleting)}`
                );

                const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('deleteSale'));

                $.ajax({
                    url: '/admin/sales/' + id,
                    type: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function (response) {
                        modal.hide();
                        table.ajax.reload(null, false);
                        showAlert('success', esc(response.message || I18N.deleted));
                    },
                    error: function (xhr) {
                        console.error('Delete error:', xhr);
                        modal.hide();
                        showAlert('danger', esc((xhr.responseJSON && xhr.responseJSON.message) || I18N.wrong));
                    },
                    complete: function () {
                        $btn.prop('disabled', false).text(I18N.delete);
                    }
                });
            });
        });
    </script>
@endpush
