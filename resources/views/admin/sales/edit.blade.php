@extends('admin.layouts.master')

@section('title', __('messages.edit_sale'))

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
                            <h5 class="card-title mb-3">{{ __('messages.edit_sale') }}</h5>
                            <div id="alertsContainer" class="mb-4"></div>

                            <form id="saleForm" action="{{ route('sales.update', $sale->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="total_amount"
                                            class="form-label fw-medium">{{ __('messages.total_amount') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="number" step="0.01" name="total_amount" id="total_amount"
                                            class="form-control rounded-3" value="{{ $sale->total_amount }}" readonly
                                            required>
                                        <div class="invalid-feedback" id="total_amount-error"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="status" class="form-label fw-medium">{{ __('messages.status') }}
                                            <span class="text-danger">*</span></label>
                                        <select name="status" id="status" class="form-select rounded-3" required>
                                            <option value="pending" {{ $sale->status == 'pending' ? 'selected' : '' }}>
                                                {{ __('messages.pending') }}
                                            </option>
                                            <option value="completed" {{ $sale->status == 'completed' ? 'selected' : '' }}>
                                                {{ __('messages.completed') }}
                                            </option>
                                            <option value="cancelled" {{ $sale->status == 'cancelled' ? 'selected' : '' }}>
                                                {{ __('messages.cancelled') }}
                                            </option>
                                        </select>
                                        <div class="invalid-feedback" id="status-error"></div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="date" class="form-label fw-medium">{{ __('messages.date') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="date" name="date" id="date" class="form-control rounded-3"
                                            value="{{ $sale->date }}" required>
                                        <div class="invalid-feedback" id="date-error"></div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <h5>{{ __('messages.items') }}</h5>
                                    <div class="position-relative mb-3">
                                        <label class="form-label fw-medium">{{ __('messages.product') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control product-search rounded-3"
                                            placeholder="{{ __('messages.search_product') }}" autocomplete="off">
                                        <div class="suggestions border position-absolute bg-white w-100 rounded-3 shadow-sm"
                                            style="display: none; z-index: 1000; max-height: 200px; overflow-y: auto;">
                                        </div>
                                    </div>
                                    <table class="table table-bordered table-hover" id="itemsTable">
                                        <thead>
                                            <tr>
                                                <th>{{ __('messages.product') }}</th>
                                                <th>{{ __('messages.quantity') }}</th>
                                                <th>{{ __('messages.sale_price') }}</th>
                                                <th>{{ __('messages.action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody id="itemsBody">
                                            @foreach ($sale->items as $index => $item)
                                                <tr class="item-row" data-product-id="{{ $item->product_id }}">
                                                    <td>
                                                        {{ $item->product->name }}
                                                        <input type="hidden" name="items[{{ $index }}][product_id]"
                                                            value="{{ $item->product_id }}">
                                                        <input type="hidden" class="stock-quantity"
                                                            value="{{ $item->product->stock_quantity }}">
                                                    </td>
                                                    <td>
                                                        <input type="number" name="items[{{ $index }}][quantity]"
                                                            class="form-control quantity" value="{{ $item->quantity }}" min="1"
                                                            required>
                                                    </td>
                                                    <td>
                                                        <input type="number" step="0.01" name="items[{{ $index }}][sale_price]"
                                                            class="form-control sale_price" value="{{ $item->sale_price }}"
                                                            required>
                                                    </td>
                                                    <td>
                                                        <button type="button"
                                                            class="btn btn-danger btn-sm remove-item">{{ __('messages.remove') }}</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-3">
                                    <button type="submit"
                                        class="btn btn-primary btn-sm rounded-3">{{ __('messages.update') }}</button>
                                    <a href="{{ route('sales.index') }}"
                                        class="btn btn-secondary btn-sm rounded-3">{{ __('messages.cancel') }}</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Submission Confirmation Modal -->
        <div class="modal fade" id="confirmSubmitModal" tabindex="-1" aria-labelledby="confirmSubmitModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmSubmitModalLabel">{{ __('messages.confirm_submission') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="stockIssues"></div>
                        <p id="confirmMessage">{{ __('messages.confirm_sale_submission') }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm"
                            data-bs-dismiss="modal">{{ __('messages.no') }}</button>
                        <button type="button" class="btn btn-primary btn-sm"
                            id="confirmSubmitBtn">{{ __('messages.yes') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Product Confirmation Modal -->
        <div class="modal fade" id="confirmAddProductModal" tabindex="-1" aria-labelledby="confirmAddProductModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmAddProductModalLabel">{{ __('messages.confirm_add_product') }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p id="addProductMessage"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm"
                            data-bs-dismiss="modal">{{ __('messages.no') }}</button>
                        <button type="button" class="btn btn-primary btn-sm"
                            id="confirmAddProductBtn">{{ __('messages.yes') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            let itemIndex = {{ $sale->items->count() }};
            const products = @json($products);
            let pendingProduct = null;

            // Function to display no products available message
            function showNoProductsMessage(container, message = '{{ __('messages.no_products_available') }}') {
                container.html(`<div class="px-3 py-2 text-muted">${message}</div>`).show();
            }

            // Function to render product suggestions
            function renderSuggestions(matches, container) {
                if (matches.length === 0) return showNoProductsMessage(container, '{{ __('messages.no_products_found') }}');

                let html = '';
                matches.forEach(p => {
                    html += `<div class="suggestion-item px-3 py-2 border-bottom"
                                data-id="${p.id}"
                                data-name="${p.name}"
                                data-code="${p.code}"
                                data-price="${p.selling_price}"
                                data-stock="${p.stock_quantity}"
                                style="cursor:pointer; color: var(--text-color); border-color: var(--border-color) !important;">
                                <div class="fw-bold" style="color: var(--text-color);">${p.name}</div>
                                <small style="color: var(--text-muted);">
                                    Code: <span style="color: var(--primary-color);">${p.code}</span> |
                                    Stock: <span class="${p.stock_quantity <= 0 ? 'text-danger' : ''}">${p.stock_quantity}</span>
                                </small>
                            </div>`;
                });
                container.html(html).show();
            }

            // Product search logic
            function initProductSearch() {
                const input = $('.product-search');
                const suggestionsDiv = $('.suggestions');

                if (!products.length) {
                    input.on('focus', () => showNoProductsMessage(suggestionsDiv));
                    return;
                }

                input.on('focus', () => renderSuggestions(products, suggestionsDiv));

                input.on('keyup', function () {
                    const keyword = $(this).val().toLowerCase().trim();
                    if (!keyword) return renderSuggestions(products, suggestionsDiv);
                    if (keyword.length < 2) return;

                    // Search by Name OR Code
                    const filtered = products.filter(p =>
                        p.name.toLowerCase().includes(keyword) ||
                        p.code.toLowerCase().includes(keyword)
                    );
                    renderSuggestions(filtered, suggestionsDiv);
                });

                suggestionsDiv.on('click', '.suggestion-item', function () {
                    const id = $(this).data('id'),
                        name = $(this).data('name'),
                        price = $(this).data('price'),
                        code = $(this).data('code'),
                        stock = parseInt($(this).data('stock'));

                    // Check if already in table
                    const existingRow = $(`#itemsBody input[name^="items["][name$="][product_id]"][value="${id}"]`).closest('.item-row');

                    if (existingRow.length) {
                        let qty = existingRow.find('.quantity');
                        qty.val(parseInt(qty.val()) + 1).trigger('input');
                        input.val('');
                        suggestionsDiv.hide();
                        return;
                    }

                    if (stock <= 0) {
                        pendingProduct = { id, name, price, stock, code };
                        $('#addProductMessage').text(`"${name}" is out of stock. Add anyway?`);
                        new bootstrap.Modal('#confirmAddProductModal').show();
                        return;
                    }

                    // FIXED: Changed function name to match definition
                    addProductToTable(id, name, price, stock, code);
                    input.val('');
                    suggestionsDiv.hide();
                });

                $(document).on('click', e => {
                    if (!$(e.target).closest('.product-search,.suggestions').length) suggestionsDiv.hide();
                });
            }

            function addProductToTable(id, name, price, stock, code) {
                const newRow = `
                    <tr class="item-row" data-product-id="${id}">
                        <td>
                            <div class="fw-bold">${name}</div>
                            <small class="text-muted">${code}</small>
                            <input type="hidden" name="items[${itemIndex}][product_id]" value="${id}">
                            <input type="hidden" class="stock-quantity" value="${stock}">
                        </td>
                        <td>
                            <input type="number" name="items[${itemIndex}][quantity]" class="form-control quantity" value="1" min="1" required>
                        </td>
                        <td>
                            <input type="number" step="0.01" name="items[${itemIndex}][sale_price]" class="form-control sale_price" value="${price}" required>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
                        </td>
                    </tr>`;
                $('#itemsBody').append(newRow);
                itemIndex++;
                updateTotalAmount();
            }

            $('#confirmAddProductBtn').on('click', function () {
                const modal = bootstrap.Modal.getInstance(document.getElementById('confirmAddProductModal'));
                modal.hide();

                if (pendingProduct) {
                    // FIXED: Corrected parameter order (stock vs code)
                    addProductToTable(pendingProduct.id, pendingProduct.name, pendingProduct.price, pendingProduct.stock, pendingProduct.code);
                    $('.product-search').val('');
                    $('.suggestions').hide();
                    pendingProduct = null;
                }
            });

            $(document).on('click', '.remove-item', function () {
                $(this).closest('.item-row').remove();
                updateTotalAmount();
            });

            function updateTotalAmount() {
                let total = 0;
                $('#itemsBody .item-row').each(function () {
                    const quantity = parseFloat($(this).find('input.quantity').val()) || 0;
                    const price = parseFloat($(this).find('input.sale_price').val()) || 0;
                    total += quantity * price;
                });
                $('#total_amount').val(total.toFixed(2));
                $('#total_display').text(total.toLocaleString(undefined, {minimumFractionDigits: 2}));
            }

            $(document).on('input', '.quantity, .sale_price', function () {
                const row = $(this).closest('.item-row');
                const quantity = parseFloat(row.find('.quantity').val()) || 0;
                const stock = parseInt(row.find('.stock-quantity').val()) || 0;
                const productName = row.find('td:first div').text().trim();

                if (quantity > stock && stock > 0) {
                    row.find('.quantity').addClass('is-invalid');
                    $('#alertsContainer').html(`
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            The quantity for "${productName}" exceeds available stock (${stock}).
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                } else {
                    row.find('.quantity').removeClass('is-invalid');
                }
                updateTotalAmount();
            });

            initProductSearch();

            // Form Submit Logic
            $('#saleForm').on('submit', function (e) {
                e.preventDefault();
                if ($('#itemsBody .item-row').length === 0) {
                    $('#alertsContainer').html('<div class="alert alert-danger">{{ __("messages.please_add_at_least_one_item") }}</div>');
                    return;
                }

                // Show confirmation modal
                const modal = new bootstrap.Modal(document.getElementById('confirmSubmitModal'));
                modal.show();
            });

            $('#confirmSubmitBtn').on('click', function () {
                const modal = bootstrap.Modal.getInstance(document.getElementById('confirmSubmitModal'));
                modal.hide();

                $.ajax({
                    url: $('#saleForm').attr('action'),
                    type: 'POST',
                    data: $('#saleForm').serialize(),
                    success: function (response) {
                        window.location.href = response.redirect;
                    },
                    error: function (xhr) {
                        // Error handling code...
                        alert('Something went wrong. Please check your inputs.');
                    }
                });
            });
        });
    </script>
@endpush
