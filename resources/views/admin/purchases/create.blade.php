@extends('admin.layouts.master')

@section('title', __('messages.add_purchase'))

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
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body p-4">

                <h5 class="mb-4 fw-bold">{{ __('messages.add_new_purchase') }}</h5>

                <form id="purchaseForm" action="{{ route('purchases.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Purchase Info --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">
                                {{ __('messages.total_amount') }}
                            </label>
                            <input type="number" step="0.01" id="total_amount" name="total_amount" class="form-control"
                                readonly>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">
                                {{ __('messages.status') }}
                            </label>
                            <select name="status" class="form-select">
                                <option value="pending">{{ __('messages.pending') }}</option>
                                <option value="completed">{{ __('messages.completed') }}</option>
                                <option value="cancelled">{{ __('messages.cancelled') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">

                        <div class="col-md-6">
                            <label class="form-label fw-medium">
                                {{ __('messages.supplier') }}
                            </label>
                            <select name="supplier_id" class="form-select" required>
                                <option value="">{{ __('messages.select_supplier') }}</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}">
                                        {{ $supplier->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">
                                {{ __('messages.payment_status') }}
                            </label>
                            <select name="payment_status" class="form-select" required>
                                <option value="paid">{{ __('messages.paid') }}</option>
                                <option value="unpaid">{{ __('messages.unpaid') }}</option>
                                <option value="partial">{{ __('messages.partial') }}</option>
                            </select>
                        </div>

                    </div>
                    <div class="row mb-3">

                        <div class="col-md-6">
                            <label class="form-label fw-medium">
                                {{ __('messages.note') }}
                            </label>
                            <textarea name="note" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium">
                                {{ __('messages.attachment') }}
                            </label>
                            <input type="file" name="attachments" class="form-control">
                        </div>

                    </div>



                    <div class="mb-4">
                        <label class="form-label fw-medium">
                            {{ __('messages.date') }}
                        </label>
                        <input type="datetime-local" name="date" id="date" class="form-control">
                    </div>

                    {{-- Product Search --}}
                    <div class="mb-3 position-relative">
                        <label class="form-label fw-medium">
                            {{ __('messages.product') }}
                        </label>
                        <input type="text" class="form-control product-search"
                            placeholder="{{ __('messages.search_product') }}" autocomplete="off">

                        <div class="suggestions border bg-white position-absolute w-100 shadow-sm"
                            style="display:none; z-index:1000; max-height:200px; overflow:auto;">
                        </div>
                    </div>

                    {{-- Items Table --}}
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>{{ __('messages.product') }}</th>
                                <th>{{ __('messages.quantity') }}</th>
                                <th>{{ __('messages.cost_price') }}</th>
                                <th>{{ __('messages.action') }}</th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody"></tbody>
                    </table>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">
                            {{ __('messages.submit') }}
                        </button>

                        <a href="{{ route('purchases.index') }}" class="btn btn-secondary">
                            {{ __('messages.cancel') }}
                        </a>
                    </div>

                </form>
            </div>
        </div>

    </div>
@endsection


@push('scripts')
    <script>
        $(function () {

            let itemIndex = 0;
            const products = @json($products);

            // Auto set current date
            $('#date').val(new Date().toISOString().slice(0, 16));

            function renderSuggestions(matches, container) {

                if (!matches.length) {
                    return container.html(
                        `<div class="p-2 text-muted">
                            {{ __('messages.no_products_found') }}
                        </div>`
                    ).show();
                }

                let html = '';

                matches.forEach(p => {

                    // Check if product already added
                    const exists = $(`#itemsBody input[name^="items["][name$="[product_id]"]`)
                        .filter(function () { return $(this).val() == p.id; }).length > 0;

                    html += `
    <div class="suggestion-item px-3 py-2 border-bottom d-flex justify-content-between align-items-center ${exists ? 'text-color' : ''}"
        data-id="${p.id}"
        data-name="${p.name}"
        data-price="${p.cost_price ?? p.selling_price}"
        style="cursor:pointer">

        <div>
                                <div class="fw-bold" style="color: var(--text-color);">${p.name}</div>
                                <small style="color: var(--text-muted);">
                                    Code: <span style="color: var(--primary-color);">${p.code}</span> |
                                    Stock: <span class="${p.stock_quantity <= 0 ? 'text-danger' : ''}">${p.stock_quantity}</span>
                                </small>
        </div>

        ${exists ? '<span class="badge bg-light text-primary">Added</span>' : ''}
    </div>`;
                });

                container.html(html).show();
            }

            $('.product-search').on('keyup focus', function () {
                const keyword = $(this).val().toLowerCase();
                const filtered = products.filter(p => p.name.toLowerCase().includes(keyword));
                renderSuggestions(filtered, $('.suggestions')); // fixed
            });

            $(document).on('click', '.suggestion-item', function () {
                addProduct(
                    $(this).data('id'),
                    $(this).data('name'),
                    $(this).data('price')
                );
                $('.product-search').val('');
                $('.suggestions').hide(); // this is correct
            });

            function addProduct(id, name, price, qty = 1) {

                const existingRow = $(`#itemsBody input[name^="items["][name$="[product_id]"]`)
                    .filter(function () { return $(this).val() == id; })
                    .closest('.item-row');

                if (existingRow.length) {
                    const quantityInput = existingRow.find('.quantity');
                    quantityInput.val(parseInt(quantityInput.val()) + qty);
                    updateTotal();
                    // Refresh suggestions highlight
                    renderSuggestions(products, $('.suggestions'));
                    return;
                }

                // New product row
                $('#itemsBody').append(`
                    <tr class="item-row">
                        <td>
                            ${name}
                            <input type="hidden" name="items[${itemIndex}][product_id]" value="${id}">
                        </td>
                        <td>
                            <input type="number" min="1"
                                name="items[${itemIndex}][quantity]"
                                class="form-control quantity"
                                value="${qty}">
                        </td>
                        <td>
                            <input type="number" step="0.01"
                                name="items[${itemIndex}][cost_price]"
                                class="form-control cost_price"
                                value="${price}">
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger remove-item">{{ __('messages.remove') }}</button>
                        </td>
                    </tr>
                `);

                itemIndex++;
                updateTotal();
                // Refresh suggestions highlight
                renderSuggestions(products, $('.suggestions'));
            }

            $(document).on('click', '.remove-item', function () {
                $(this).closest('.item-row').remove();
                updateTotal();
                renderSuggestions(products, $('.suggestions')); // refresh highlight
            });

            $(document).on('input', '.quantity,.cost_price', updateTotal);

            function updateTotal() {
                let total = 0;
                $('#itemsBody tr').each(function () {
                    const qty = parseFloat($(this).find('.quantity').val()) || 0;
                    const price = parseFloat($(this).find('.cost_price').val()) || 0;
                    total += qty * price;
                });
                $('#total_amount').val(total.toFixed(2));
            }

            // $('#purchaseForm').submit(function (e) {
            //     e.preventDefault();

            //     if (!$('#itemsBody tr').length) {
            //         alert('{{ __("messages.please_add_at_least_one_item") }}');
            //         return;
            //     }

            //     let formData = new FormData(this);

            //     $.ajax({
            //         url: '{{ route("purchases.store") }}',
            //         type: 'POST',
            //         data: formData,
            //         processData: false,
            //         contentType: false,
            //         success: function (res) {
            //             window.location = res.redirect;
            //         }
            //     });
            // });

        });
    </script>
@endpush
