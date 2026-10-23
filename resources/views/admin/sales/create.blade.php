@extends('admin.layouts.master')
@section('title', __('messages.add_sale'))

@php
    // Falls back to English if a translation key hasn't been added yet.
    $t = fn($key, $default) => \Illuminate\Support\Facades\Lang::has("messages.$key") ? __("messages.$key") : $default;

    // Strings used by the JavaScript below.
    $i18n = [
        'noAvailable' => __('messages.no_products_available'),
        'noFound' => __('messages.no_products_found'),
        'outAddAnyway' => __('messages.is_out_of_stock_Add_anyway'),
        'addAtLeastOne' => __('messages.please_add_at_least_one_item'),
        'exceeds' => __('messages.exceeds_stock'),
        'stockIssues' => __('messages.stock_issues_detected'),
        'confirmSubmit' => $t('confirm_submit', 'Confirm submit?'),
        'stock' => $t('stock', 'Stock'),
        'inCart' => $t('in_cart', 'In cart'),
        'onlyInStock' => $t('only_in_stock', 'Only :n in stock'),
        'fixErrors' => $t('please_fix_errors', 'Please fix the errors below.'),
        'item' => $t('item', 'item'),
        'items' => __('messages.items'),
        'total' => __('messages.total_amount'),
        'remove' => $t('remove', 'Remove'),
        'qtyInvalid' => $t('quantity_invalid', 'Quantity must be at least 1.'),
    ];
@endphp

@section('content')
    <style>
        /* ==========================================================
           Add sale - built on layout-v2.css tokens
           ========================================================== */
        .pl-card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 14px; }
        .pl-card:hover { transform: none; box-shadow: 0 1px 2px rgba(16, 24, 40, .05); }
        .pl-card-h { display: flex; align-items: center; justify-content: space-between; gap: .75rem; padding: 1rem 1.25rem; border-bottom: 1px solid var(--border-color); }
        .pl-card-h h2 { font-size: 1rem; font-weight: 700; margin: 0; }
        .pl-card-b { padding: 1.25rem; }

        .pl-head { display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: .75rem 1rem; margin-bottom: 1.25rem; }
        .pl-head h1 { font-size: 1.5rem; font-weight: 800; letter-spacing: -.02em; margin: 0 0 .35rem; }
        .pl-head .breadcrumb { font-size: .8rem; }
        .pl-chip { display: inline-flex; align-items: center; gap: .45rem; padding: .35rem .75rem; border-radius: 999px; border: 1px solid var(--border-color); background: var(--card-bg); font-size: .78rem; color: var(--text-muted); }
        .pl-chip b { color: var(--text-color); font-weight: 600; }

        /* product picker */
        .pl-picker { position: relative; }
        .pl-picker > i { position: absolute; left: .9rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); pointer-events: none; }
        .pl-picker input { width: 100%; height: 46px; padding: 0 1rem 0 2.6rem; border-radius: 12px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-color); }
        .pl-picker input::placeholder { color: var(--text-muted); }
        .pl-picker input:focus { outline: none; border-color: var(--brand); background: var(--card-bg); box-shadow: 0 0 0 .2rem rgba(var(--brand-rgb), .18); }
        .suggestions { display: none; position: absolute; left: 0; right: 0; top: calc(100% + .4rem); z-index: 1050; max-height: 320px; overflow-y: auto; padding: .35rem; background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 12px; box-shadow: 0 12px 32px rgba(16, 24, 40, .18); }
        .pl-sugg-item { display: flex; align-items: center; justify-content: space-between; gap: 1rem; padding: .55rem .7rem; border-radius: 8px; cursor: pointer; color: var(--text-color); }
        .pl-sugg-item:hover, .pl-sugg-item.on { background: var(--brand-soft); }
        .pl-sugg-name { font-weight: 600; line-height: 1.25; }
        .pl-sugg-meta { font-size: .76rem; color: var(--text-muted); }
        .pl-sugg-meta b { font-weight: 700; }
        .pl-sugg-meta b.out { color: #E5484D; }
        .pl-sugg-side { text-align: right; flex: none; display: flex; flex-direction: column; align-items: flex-end; gap: .2rem; font-variant-numeric: tabular-nums; }
        .pl-sugg-empty { padding: .8rem; color: var(--text-muted); }

        /* items table */
        table.pl-table { --bs-table-bg: transparent; --bs-table-striped-bg: transparent; width: 100%; margin: 0; border-collapse: collapse; }
        table.pl-table thead th { background: transparent; color: var(--text-muted); font-size: .76rem; font-weight: 600; white-space: nowrap; padding: .7rem .75rem; border: 0; border-bottom: 1px solid var(--border-color); }
        table.pl-table tbody td { padding: .75rem; border: 0; border-bottom: 1px solid var(--border-color); vertical-align: top; background: transparent; box-shadow: none; }
        table.pl-table tbody tr:last-child td { border-bottom: 0; }
        table.pl-table .num { text-align: right; font-variant-numeric: tabular-nums; white-space: nowrap; }
        .pl-iname { font-weight: 600; line-height: 1.25; }
        .pl-imeta { font-size: .76rem; color: var(--text-muted); margin-top: .15rem; }
        .pl-imeta b.out { color: #E5484D; }
        .line-total { font-weight: 700; display: inline-block; padding-top: .5rem; }
        .pl-warn { font-size: .74rem; color: #E5484D; margin-top: .25rem; }
        .btn-icon { width: 36px; height: 36px; border: 1px solid var(--border-color); border-radius: 10px; background: transparent; color: var(--text-muted); display: inline-grid; place-items: center; }
        .btn-icon:hover { color: #E5484D; background: rgba(229, 72, 77, .1); border-color: rgba(229, 72, 77, .3); }

        .pl-empty { display: flex; flex-direction: column; align-items: center; gap: .4rem; padding: 2.75rem 1rem; color: var(--text-muted); text-align: center; }
        .pl-empty i { font-size: 2rem; opacity: .6; }
        .pl-empty b { color: var(--text-color); }

        /* summary */
        .pl-sticky { position: sticky; top: calc(var(--header-height) + 1rem); }
        .pl-sum-row { display: flex; align-items: center; justify-content: space-between; padding: .35rem 0; color: var(--text-muted); font-size: .88rem; }
        .pl-sum-row b { color: var(--text-color); font-variant-numeric: tabular-nums; }
        .pl-total-box { margin-top: .75rem; padding: .9rem 1rem; border-radius: 12px; background: var(--brand-soft); }
        .pl-total-box label { font-size: .8rem; font-weight: 600; color: var(--brand-text); margin: 0; }
        .pl-total-box input { width: 100%; border: 0; background: transparent; color: var(--text-color); font-size: 1.9rem; font-weight: 800; letter-spacing: -.02em; padding: 0; font-variant-numeric: tabular-nums; outline: none; }
        .pl-total-box input:focus { box-shadow: none; }
        .pl-field { margin-bottom: 1rem; }
        .pl-field .form-label { font-weight: 600; font-size: .84rem; }

        .pl-badge { display: inline-flex; align-items: center; padding: .12rem .5rem; border-radius: 999px; background: rgba(34, 197, 94, .14); color: #1E9A52; font-size: .7rem; font-weight: 600; white-space: nowrap; }

        .pl-modal-ic { width: 52px; height: 52px; border-radius: 14px; display: grid; place-items: center; margin: 0 auto .9rem; background: var(--brand-soft); color: var(--brand-text); font-size: 1.4rem; }
        .pl-modal-ic.warn { background: rgba(245, 158, 11, .16); color: #B97405; }
        #stockIssues:not(:empty) { text-align: left; margin-bottom: .9rem; padding: .7rem .9rem; border-radius: 10px; background: rgba(245, 158, 11, .14); color: var(--text-color); font-size: .86rem; }
        #confirmSummary { margin-top: .6rem; padding: .5rem .75rem; border-radius: 10px; background: var(--hover-bg); font-weight: 600; }

        @media (max-width: 991.98px) { .pl-sticky { position: static; } }
        @media (max-width: 575.98px) { #itemsTable th:nth-child(4), #itemsTable td:nth-child(4) { display: none; } }
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

        <div id="alertsContainer"></div>

        <form id="saleForm" novalidate>
            @csrf
            <div class="row g-3">

                {{-- items --}}
                <div class="col-lg-8">
                    <div class="pl-card">
                        <div class="pl-card-h">
                            <h2>{{ __('messages.items') }}</h2>
                            <span class="text-muted small"><b id="itemCount">0</b> {{ __('messages.items') }}</span>
                        </div>
                        <div class="pl-card-b pb-2">
                            <div class="pl-picker">
                                <i class="bi bi-search"></i>
                                <input type="text" class="product-search" autocomplete="off" role="combobox"
                                       aria-label="{{ __('messages.product') }}"
                                       placeholder="{{ __('messages.search_product') }} ({{ $t('search_by_name_or_code', 'name or code') }})">
                                <div class="suggestions" role="listbox"></div>
                            </div>
                        </div>

                        <div id="emptyItems" class="pl-empty">
                            <i class="bi bi-cart3"></i>
                            <b>{{ $t('no_items_yet', 'No items yet') }}</b>
                            <span>{{ $t('search_to_add', 'Search for a product above to add it to this sale.') }}</span>
                        </div>

                        <div class="table-responsive" id="itemsWrap" style="display:none">
                            <table class="table pl-table align-middle" id="itemsTable">
                                <thead>
                                <tr>
                                    <th>{{ __('messages.product') }}</th>
                                    <th>{{ __('messages.quantity') }}</th>
                                    <th>{{ __('messages.sale_price') }}</th>
                                    <th class="num">{{ $t('line_total', 'Total') }}</th>
                                    <th style="width:52px"><span class="visually-hidden">{{ __('messages.action') }}</span></th>
                                </tr>
                                </thead>
                                <tbody id="itemsBody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- summary --}}
                <div class="col-lg-4">
                    <div class="pl-card pl-sticky">
                        <div class="pl-card-h"><h2>{{ __('messages.add_new_sale') }}</h2></div>
                        <div class="pl-card-b">

                            <div class="pl-field">
                                <label for="date" class="form-label">{{ __('messages.date') }} <span class="text-danger">*</span></label>
                                <input type="datetime-local" name="date" id="date" class="form-control" required>
                                <div class="invalid-feedback" id="date-error"></div>
                            </div>

                            <div class="pl-field">
                                <label for="status" class="form-label">{{ __('messages.status') }} <span class="text-danger">*</span></label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="pending">{{ __('messages.pending') }}</option>
                                    <option value="completed">{{ __('messages.completed') }}</option>
                                    <option value="cancelled">{{ __('messages.cancelled') }}</option>
                                </select>
                                <div class="invalid-feedback" id="status-error"></div>
                            </div>

                            <div class="pl-total-box">
                                <label for="total_amount">{{ __('messages.total_amount') }} <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="total_amount" id="total_amount" value="0.00" readonly required>
                                <div class="invalid-feedback d-block" id="total_amount-error"></div>
                            </div>

                            <div class="d-grid gap-2 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg fs-6">{{ __('messages.submit') }}</button>
                                <a href="{{ route('sales.index') }}" class="btn btn-outline-custom">{{ __('messages.cancel') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- Submission confirmation modal --}}
        <div class="modal fade" id="confirmSubmitModal" tabindex="-1" aria-labelledby="confirmSubmitModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content border-0 shadow">
                    <div class="modal-body text-center p-4">
                        <div class="pl-modal-ic" id="confirmIcon"><i class="bi bi-receipt"></i></div>
                        <h5 class="fw-bold mb-2" id="confirmSubmitModalLabel">{{ __('messages.confirm_submission') }}</h5>
                        <div id="stockIssues"></div>
                        <p class="text-muted mb-0" id="confirmMessage">{{ __('messages.confirm_sale_submission') }}</p>
                        <div id="confirmSummary"></div>
                    </div>
                    <div class="modal-footer border-0 justify-content-center pt-0 pb-4 gap-2">
                        <button type="button" class="btn btn-outline-custom btn-sm px-4" data-bs-dismiss="modal">{{ __('messages.no') }}</button>
                        <button type="button" class="btn btn-primary btn-sm px-4" id="confirmSubmitBtn">{{ __('messages.yes') }}</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Add out-of-stock product confirmation modal --}}
        <div class="modal fade" id="confirmAddProductModal" tabindex="-1" aria-labelledby="confirmAddProductModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content border-0 shadow">
                    <div class="modal-body text-center p-4">
                        <div class="pl-modal-ic warn"><i class="bi bi-exclamation-triangle"></i></div>
                        <h5 class="fw-bold mb-2" id="confirmAddProductModalLabel">{{ __('messages.confirm_add_product') }}</h5>
                        <p class="text-muted mb-0" id="addProductMessage"></p>
                    </div>
                    <div class="modal-footer border-0 justify-content-center pt-0 pb-4 gap-2">
                        <button type="button" class="btn btn-outline-custom btn-sm px-4" data-bs-dismiss="modal">{{ __('messages.no') }}</button>
                        <button type="button" class="btn btn-primary btn-sm px-4" id="confirmAddProductBtn">{{ __('messages.yes') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const I18N = @json($i18n);
        const products = @json($products);

        const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
        }[c]));
        const num = v => parseFloat(v) || 0;
        const money = v => num(v).toFixed(2);

        $(document).ready(function () {
            let itemIndex = 0;
            let pendingProduct = null;
            let activeIdx = -1;

            const $input = $('.product-search');
            const $sugg = $('.suggestions');

            // default the sale date to "now" (local time)
            if (!$('#date').val()) {
                $('#date').val(new Date(Date.now() - new Date().getTimezoneOffset() * 60000).toISOString().slice(0, 16));
            }

            function notify(msg, type = 'info', timeout = 3000) {
                const $a = $(`
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        ${msg}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>`);
                $('#alertsContainer').html($a);
                if (timeout) setTimeout(() => $a.alert('close'), timeout);
            }

            function cartRow(id) {
                return $('#itemsBody .item-row').filter(function () { return String($(this).attr('data-id')) === String(id); });
            }

            /* ---------- product picker ---------- */
            function renderSuggestions(list) {
                activeIdx = -1;
                if (!products.length) {
                    $sugg.html(`<div class="pl-sugg-empty">${esc(I18N.noAvailable)}</div>`).show();
                    return;
                }
                if (!list.length) {
                    $sugg.html(`<div class="pl-sugg-empty">${esc(I18N.noFound)}</div>`).show();
                    return;
                }
                $sugg.html(list.slice(0, 30).map(p => {
                    const row = cartRow(p.id);
                    const inCart = row.length ? row.find('.quantity').val() : 0;
                    const out = num(p.stock_quantity) <= 0;
                    return `
                        <div class="pl-sugg-item" role="option" data-id="${esc(p.id)}">
                            <div class="min-w-0">
                                <div class="pl-sugg-name">${esc(p.name)}</div>
                                <div class="pl-sugg-meta">${esc(p.code)} &nbsp;|&nbsp; ${esc(I18N.stock)}: <b class="${out ? 'out' : ''}">${esc(p.stock_quantity)}</b></div>
                            </div>
                            <div class="pl-sugg-side">
                                <b>${money(p.selling_price)}</b>
                                ${inCart ? `<span class="pl-badge">${esc(I18N.inCart)} &times;${esc(inCart)}</span>` : ''}
                            </div>
                        </div>`;
                }).join('')).show();
            }

            function filterProducts() {
                const kw = $input.val().toLowerCase().trim();
                if (!kw) return products;
                return products.filter(p =>
                    String(p.name ?? '').toLowerCase().includes(kw) ||
                    String(p.code ?? '').toLowerCase().includes(kw));
            }

            function closeSuggestions(clear = true) {
                $sugg.hide();
                if (clear) $input.val('');
            }

            function pickProduct(p) {
                const row = cartRow(p.id);
                if (row.length) {
                    const q = row.find('.quantity');
                    q.val((parseInt(q.val(), 10) || 0) + 1).trigger('input');
                    closeSuggestions();
                    $input.trigger('focus');
                    return;
                }
                if (num(p.stock_quantity) <= 0) {
                    pendingProduct = p;
                    $('#addProductMessage').text(`"${p.name}" ${I18N.outAddAnyway}`);
                    bootstrap.Modal.getOrCreateInstance('#confirmAddProductModal').show();
                    closeSuggestions(false);
                    return;
                }
                addProduct(p);
                closeSuggestions();
                $input.trigger('focus');
            }

            $input.on('focus input', function () { renderSuggestions(filterProducts()); });

            $input.on('keydown', function (e) {
                const $items = $sugg.find('.pl-sugg-item');
                if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (!$sugg.is(':visible')) renderSuggestions(filterProducts());
                    if (!$items.length) return;
                    activeIdx = e.key === 'ArrowDown' ? (activeIdx + 1) % $items.length : (activeIdx - 1 + $items.length) % $items.length;
                    $items.removeClass('on').eq(activeIdx).addClass('on')[0].scrollIntoView({ block: 'nearest' });
                } else if (e.key === 'Enter') {
                    e.preventDefault(); // don't submit the form from the search box
                    const $pick = $items.eq(activeIdx >= 0 ? activeIdx : 0);
                    if ($pick.length) pickProduct(products.find(p => String(p.id) === String($pick.attr('data-id'))));
                } else if (e.key === 'Escape') {
                    closeSuggestions(false);
                }
            });

            $sugg.on('click', '.pl-sugg-item', function () {
                const p = products.find(x => String(x.id) === String($(this).attr('data-id')));
                if (p) pickProduct(p);
            });

            $(document).on('click', e => {
                if (!$(e.target).closest('.pl-picker').length) $sugg.hide();
            });

            /* ---------- cart ---------- */
            function addProduct(p, qty = 1) {
                const stock = num(p.stock_quantity);
                $('#itemsBody').append(`
                    <tr class="item-row" data-id="${esc(p.id)}">
                        <td>
                            <div class="pl-iname fw-medium">${esc(p.name)}</div>
                            <div class="pl-imeta">${esc(p.code)} &nbsp;|&nbsp; ${esc(I18N.stock)}: <b class="${stock <= 0 ? 'out' : ''}">${esc(p.stock_quantity)}</b></div>
                            <input type="hidden" name="items[${itemIndex}][product_id]" value="${esc(p.id)}">
                            <input type="hidden" class="stock-quantity" value="${esc(p.stock_quantity)}">
                        </td>
                        <td style="min-width:110px;width:140px">
                            <input type="number" name="items[${itemIndex}][quantity]" class="form-control quantity" value="${qty}" min="1" aria-label="{{ __('messages.quantity') }}">
                            <div class="pl-warn" hidden></div>
                        </td>
                        <td style="min-width:120px;width:160px">
                            <input type="number" step="0.01" name="items[${itemIndex}][sale_price]" class="form-control sale_price" value="${esc(p.selling_price)}" aria-label="{{ __('messages.sale_price') }}">
                        </td>
                        <td class="num"><span class="line-total">0.00</span></td>
                        <td class="text-end">
                            <button type="button" class="btn-icon remove-item" title="${esc(I18N.remove)}" aria-label="${esc(I18N.remove)}"><i class="bi bi-trash3"></i></button>
                        </td>
                    </tr>`);
                itemIndex++;
                updateTotal();
            }

            $('#confirmAddProductBtn').on('click', function () {
                if (!pendingProduct) return;
                addProduct(pendingProduct);
                pendingProduct = null;
                $input.val('');
                bootstrap.Modal.getOrCreateInstance('#confirmAddProductModal').hide();
            });

            $(document).on('click', '.remove-item', function () {
                $(this).closest('.item-row').remove();
                updateTotal();
            });

            function updateTotal() {
                let total = 0;
                const $rows = $('#itemsBody .item-row');
                $rows.each(function () {
                    const line = num($(this).find('.quantity').val()) * num($(this).find('.sale_price').val());
                    $(this).find('.line-total').text(money(line));
                    total += line;
                });
                $('#total_amount').val(total.toFixed(2));
                $('#itemCount').text($rows.length);
                $('#emptyItems').toggle($rows.length === 0);
                $('#itemsWrap').toggle($rows.length > 0);
            }

            $(document).on('input', '.quantity, .sale_price', function () {
                const $row = $(this).closest('.item-row');
                const qty = num($row.find('.quantity').val());
                const stock = num($row.find('.stock-quantity').val());
                const over = qty > stock && stock > 0;

                $row.find('.quantity').toggleClass('is-invalid', over);
                $row.find('.pl-warn').prop('hidden', !over).text(over ? I18N.onlyInStock.replace(':n', stock) : '');
                updateTotal();
            });

            /* ---------- submit ---------- */
            $('#saleForm').on('submit', function (e) {
                e.preventDefault();

                const $rows = $('#itemsBody .item-row');
                if (!$rows.length) return notify(esc(I18N.addAtLeastOne), 'danger');

                let warn = '';
                let badQty = false;
                $rows.each(function () {
                    const qty = num($(this).find('.quantity').val());
                    const stock = num($(this).find('.stock-quantity').val());
                    const name = $(this).find('.fw-medium').text();
                    if (qty < 1) badQty = true;
                    if (qty > stock) warn += `<i class="bi bi-exclamation-triangle me-1"></i>"${esc(name)}" ${esc(I18N.exceeds)} (${stock})<br>`;
                });
                if (badQty) return notify(esc(I18N.qtyInvalid), 'danger');

                $('#stockIssues').html(warn);
                $('#confirmMessage').text(warn ? I18N.stockIssues : I18N.confirmSubmit);
                $('#confirmIcon').toggleClass('warn', !!warn);
                $('#confirmSummary').html(`${$rows.length} ${esc(I18N.items)} &nbsp;|&nbsp; ${esc(I18N.total)}: ${esc($('#total_amount').val())}`);

                bootstrap.Modal.getOrCreateInstance('#confirmSubmitModal').show();
            });

            $('#confirmSubmitBtn').on('click', function () {
                const $btn = $(this);
                const label = $btn.html();
                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

                $('#saleForm .is-invalid').removeClass('is-invalid');
                $('#saleForm .invalid-feedback').text('');

                $.ajax({
                    url: '{{ route('sales.store') }}',
                    type: 'POST',
                    data: $('#saleForm').serialize(),
                    success: function (res) {
                        notify(esc(res.message), 'success', 0);
                        window.location = res.redirect;
                    },
                    error: function (xhr) {
                        bootstrap.Modal.getOrCreateInstance('#confirmSubmitModal').hide();

                        const errs = xhr.responseJSON && xhr.responseJSON.errors;
                        const other = [];
                        if (errs) {
                            Object.entries(errs).forEach(([key, msgs]) => {
                                if (/^[a-z_]+$/i.test(key) && $('#' + key + '-error').length) {
                                    $('#' + key).addClass('is-invalid');
                                    $('#' + key + '-error').text(msgs[0]);
                                } else {
                                    other.push(esc(msgs[0]));
                                }
                            });
                        }
                        const serverMsg = !errs && xhr.responseJSON && xhr.responseJSON.message;
                        notify(esc(serverMsg || I18N.fixErrors) + (other.length ? '<br>' + other.join('<br>') : ''), 'danger', 0);
                    },
                    complete: function () {
                        $btn.prop('disabled', false).html(label);
                    }
                });
            });

            updateTotal();
        });
    </script>
@endpush
