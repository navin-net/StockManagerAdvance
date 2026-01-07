@extends('layouts.master')

@section('title', __('messages.add_sale'))

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
                            <h5 class="card-title mb-3">{{ __('messages.add_new_sale') }}</h5>
                            <div id="alertsContainer" class="mb-4"></div>

                            <form id="saleForm">
                                @csrf
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="total_amount"
                                            class="form-label fw-medium">{{ __('messages.total_amount') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="number" step="0.01" name="total_amount" id="total_amount"
                                            class="form-control rounded-3" readonly required>
                                        <div class="invalid-feedback" id="total_amount-error"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="status" class="form-label fw-medium">{{ __('messages.status') }} <span
                                                class="text-danger">*</span></label>
                                        <select name="status" id="status" class="form-select rounded-3" required>
                                            <option value="pending">{{ __('messages.pending') }}</option>
                                            <option value="completed">{{ __('messages.completed') }}</option>
                                            <option value="cancelled">{{ __('messages.cancelled') }}</option>
                                        </select>
                                        <div class="invalid-feedback" id="status-error"></div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="col-md-12">
                                        <label for="date" class="form-label fw-medium">
                                            {{ __('messages.date') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="datetime-local" name="date" id="date"
                                            class="form-control rounded-3" required>
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
                                        <tbody id="itemsBody"></tbody>
                                    </table>
                                </div>

                                <div class="mt-3">
                                    <button type="submit"
                                        class="btn btn-primary btn-sm rounded-3">{{ __('messages.submit') }}</button>
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
        $(document).ready(function() {
            let itemIndex = 0;
            const products = @json($products);
            let pendingProduct = null;

            // Function to display no products available message
            function showNoProductsMessage(container, message = '{{ __('messages.no_products_available') }}') {
                container.html(`<div class="px-3 py-2 text-muted">${message}</div>`).show();
            }

            // Function to render product suggestions
            function renderSuggestions(matches, container) {
                if (matches.length === 0) return showNoProductsMessage(container,
                    '{{ __('messages.no_products_found') }}');

                let html = '';
                matches.forEach(p => {
                    const existingRow = $(`#itemsBody input[name^="items["][name$="][product_id]"]`)
                        .filter(function() {
                            return $(this).val() == p.id
                        }).closest('.item-row');
                    const isSelected = existingRow.length > 0;
                    const currentQty = isSelected ? existingRow.find('.quantity').val() : 0;

                    html += `
                <div class="card suggestion-item cursor-pointer ${isSelected ? 'border-primary border-2' : ''}"
                    data-id="${p.id}" data-name="${p.name}" data-price="${p.selling_price}" data-stock="${p.stock_quantity}">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="card-title mb-1 fw-semibold">${p.name}</h6>
                                <p class="text-muted small mb-0">code: ${p.code}</p>
                                <p class="text-muted small mb-0">Stock: ${p.stock_quantity}</p>
                            </div>
                            ${isSelected ? `
                                    <div class="text-end">
                                        <small class="text-primary fw-medium">In cart: ${currentQty}</small>
                                        <div><small class="text-muted">Click to add +1</small></div>
                                    </div>` : ''}
                        </div>
                    </div>
                </div>`;
                });
                container.html(html).show();
            }

            // Product search
            function initProductSearch() {
                const input = $('.product-search');
                const suggestionsDiv = $('.suggestions');

                if (!products.length) {
                    input.on('focus', () => showNoProductsMessage(suggestionsDiv));
                    return;
                }

                input.on('focus', () => renderSuggestions(products, suggestionsDiv));

                input.on('keyup', function() {
                    const keyword = $(this).val().toLowerCase().trim();
                    if (!keyword) return renderSuggestions(products, suggestionsDiv);
                    if (keyword.length < 2) return;
                    renderSuggestions(products.filter(p => p.name.toLowerCase().includes(keyword)),
                        suggestionsDiv);
                });

                suggestionsDiv.on('click', '.suggestion-item', function() {
                    const id = $(this).data('id'),
                        name = $(this).data('name'),
                        price = $(this).data('price'),
                        stock = parseInt($(this).data('stock'));

                    const row = $(`#itemsBody input[name^="items["][name$="][product_id]"]`)
                        .filter(function() {
                            return $(this).val() == id
                        }).closest('.item-row');

                    if (row.length) {
                        let qty = row.find('.quantity');
                        qty.val(parseInt(qty.val()) + 1).trigger('input');
                        input.val('');
                        suggestionsDiv.hide();

                        notify(`Quantity increased for "${name}"`, 'success');
                        return;
                    }

                    if (stock <= 0) {
                        pendingProduct = {
                            id,
                            name,
                            price,
                            stock
                        };
                        $('#addProductMessage').text(`"${name}" is out of stock. Add anyway?`);
                        new bootstrap.Modal('#confirmAddProductModal').show();
                        return;
                    }

                    addProduct(id, name, price, stock);
                    input.val('');
                    suggestionsDiv.hide();
                });

                $(document).on('click', e => {
                    if (!$(e.target).closest('.product-search,.suggestions').length) suggestionsDiv.hide();
                });
            }

            // Add product
            function addProduct(id, name, price, stock, qty = 1, salePrice = price) {
                $('#itemsBody').append(`
            <tr class="item-row">
                <td>
                    <div><div class="fw-medium">${name}</div><small class="text-muted">Stock: ${stock}</small></div>
                    <input type="hidden" name="items[${itemIndex}][product_id]" value="${id}">
                    <input type="hidden" class="stock-quantity" value="${stock}">
                </td>
                <td><input type="number" name="items[${itemIndex}][quantity]" class="form-control quantity" value="${qty}" min="1"></td>
                <td><input type="number" step="0.01" name="items[${itemIndex}][sale_price]" class="form-control sale_price" value="${salePrice}"></td>
                <td><button type="button" class="btn btn-danger btn-sm remove-item"><i class="bi bi-trash"></i> Remove</button></td>
            </tr>
        `);
                itemIndex++;
                updateTotal();
                notify(`"${name}" added to cart!`, 'success');
            }

            $('#confirmAddProductBtn').click(function() {
                if (!pendingProduct) return;
                addProduct(pendingProduct.id, pendingProduct.name, pendingProduct.price, pendingProduct
                    .stock);
                pendingProduct = null;
                bootstrap.Modal.getInstance('#confirmAddProductModal').hide();
            });

            // Remove product
            $(document).on('click', '.remove-item', function() {
                const name = $(this).closest('.item-row').find('.fw-medium').text();
                $(this).closest('.item-row').remove();
                updateTotal();
                notify(`"${name}" removed`, 'info');
            });

            // Update total
            function updateTotal() {
                let total = 0;
                $('#itemsBody .item-row').each(function() {
                    total += ($(this).find('.quantity').val() * $(this).find('.sale_price').val());
                });
                $('#total_amount').val(total.toFixed(2));
            }

            $(document).on('input', '.quantity,.sale_price', function() {
                const row = $(this).closest('.item-row'),
                    qty = parseFloat(row.find('.quantity').val()),
                    stock = parseInt(row.find('.stock-quantity').val());

                row.find('.quantity').toggleClass('is-invalid', qty > stock && stock > 0);
                updateTotal();
            });


            // notify helper
            function notify(msg, type = 'info') {
                $('#alertsContainer').html(`
            <div class="alert alert-${type} alert-dismissible fade show">
                ${msg}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);
                setTimeout(() => $('.alert').fadeOut(), 2500);
            }

            // Form submission
            $('#saleForm').submit(function(e) {
                e.preventDefault();

                if (!$('#itemsBody .item-row').length) {
                    return notify('{{ __('messages.please_add_at_least_one_item') }}', 'danger');
                }

                let warn = '';
                $('#itemsBody .item-row').each(function() {
                    const qty = $(this).find('.quantity').val(),
                        stock = $(this).find('.stock-quantity').val(),
                        name = $(this).find('.fw-medium').text();
                    if (qty > stock) {
                        warn += `⚠ "${name}" exceeds stock (${stock})<br>`;
                    }
                });

                $('#stockIssues').html(warn);
                $('#confirmMessage').text(warn ? 'Stock issues detected' : 'Confirm submit?');

                new bootstrap.Modal('#confirmSubmitModal').show();
            });

            $('#confirmSubmitBtn').click(function() {
                $(this).prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm"></span>');

                $.post('{{ route('sales.store') }}', $('#saleForm').serialize(), res => {
                    notify(res.message, 'success');
                    window.location = res.redirect;
                }).fail(() => {
                    notify("Please fix errors", 'danger');
                    $(this).prop('disabled', false).html('Yes');
                });
            });

            initProductSearch();
        });
    </script>
@endpush
