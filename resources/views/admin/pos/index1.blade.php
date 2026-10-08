@extends('admin.layouts.master')
@section('title', $pageTitle)

@section('content')

    {{-- HIDDEN DISCOUNT FIELDS (read by recalcTotals + submit) --}}
    <input type="hidden" id="discountType" value="fixed">
    <input type="hidden" id="discountValue" value="0">

    <div class="pos-root">

        {{-- ══════════ COL 1 — BRAND SIDEBAR ══════════ --}}
        <aside class="pos-sidebar">
            <div class="pos-sidebar__head">
                <span class="pos-sidebar__label">{{ __('messages.brands') }}</span>
            </div>
            <div class="pos-sidebar__search">
                <i class="bi bi-search"></i>
                <input type="text" id="brandSearch" placeholder="{{ __('messages.search') }}…" oninput="filterBrands()">
            </div>
            <div class="pos-sidebar__list" id="brandList">

                <button class="brand-btn active" data-brand="all" onclick="selectBrand(this,'all')">
                    <span class="brand-btn__icon">🏪</span>
                    <span class="brand-btn__name">{{ __('messages.all') }}</span>
                    <span class="brand-btn__count" id="brandCntAll">{{ $products->count() }}</span>
                </button>

                @foreach ($brands as $brand)
                    <button class="brand-btn" data-brand="{{ $brand->id }}"
                            onclick="selectBrand(this,'{{ $brand->id }}')">
                        <span class="brand-btn__icon">
                            <img src="{{ $brand->image ? asset('storage/images/' . $brand->image) : asset('noimage.png') }}"
                                 alt="{{ $brand->name }}">
                        </span>
                        <span class="brand-btn__name">{{ $brand->name }}</span>
                        <span class="brand-btn__count">
                            {{ $products->where('brand_id', $brand->id)->count() }}
                        </span>
                    </button>
                @endforeach

            </div>
        </aside>

        {{-- ══════════ COL 2 — CENTER ══════════ --}}
        <main class="pos-center">

            {{-- Search bar --}}
            <div class="pos-searchbar">
                <div class="pos-search-wrap">
                    <i class="bi bi-search"></i>
                    <input type="text" id="searchInput" placeholder="{{ __('messages.spc') }}" oninput="filterProducts()">
                    <button type="button" class="pos-search-clear" onclick="clearSearch()"></button>
                </div>
                <div class="pos-count"><span id="resCount">{{ $products->count() }}</span> {{ __('messages.items') }}</div>
            </div>

            {{-- Category bar --}}
            <div class="pos-catbar">
                <div class="pos-catbar__label"><i class="bi bi-grid-3x3-gap"></i></div>
                <div class="pos-catbar__scroll" id="catScroller">
                    <button class="pos-cat-tab active" data-cat="all">
                        {{ __('messages.all') }} <span class="pos-cat-tab__cnt">{{ $products->count() }}</span>
                    </button>
                    {{-- dynamically rebuilt by JS --}}
                </div>
            </div>

            {{-- Subcategory bar --}}
            <div class="pos-subcatbar" id="subcatBar">
                <div class="pos-catbar__label"><i class="bi bi-diagram-2"></i></div>
                <div class="pos-catbar__scroll" id="subcatScroller"></div>
            </div>

            {{-- Product grid --}}
            <div class="pos-pgrid-wrap">
                <div class="pos-pgrid" id="productGrid">

                    @forelse($products as $product)
                        <div class="pos-pcard {{ $product->stock_quantity <= 0 ? 'pos-pcard--out' : '' }}"
                             data-id="{{ $product->id }}" data-brand="{{ $product->brand_id }}"
                             data-cat="{{ $product->category ?? '' }}" data-subcat="{{ $product->subcategory ?? '' }}"
                             data-name="{{ mb_strtolower($product->name) }}"
                             data-code="{{ mb_strtolower($product->code ?? '') }}" data-price="{{ $product->selling_price }}"
                             data-stock="{{ $product->stock_quantity }}"
                             data-image="{{ $product->image ? asset('storage/' . $product->image) : asset('noimage.png') }}"
                             onclick="addToCart(this)">

                            <div class="pos-pcard__img">
                                @if ($product->stock_quantity <= 0)
                                    <span class="pos-pcard__badge pos-pcard__badge--out">{{ __('messages.out') }}</span>
                                @elseif($product->stock_quantity <= 5)
                                    <span class="pos-pcard__badge pos-pcard__badge--low">{{ __('messages.low') }}</span>
                                @endif
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('noimage.png') }}"
                                     alt="{{ $product->name }}">
                            </div>

                            <div class="pos-pcard__body">
                                <div class="pos-pcard__name" title="{{ $product->name }}">{{ $product->name }}</div>
                                <div class="pos-pcard__price">${{ number_format($product->selling_price, 2) }}</div>
                                <div class="pos-pcard__stock">
                                    {{ $product->stock_quantity <= 0 ? __('messages.out_of_stock') : $product->stock_quantity . ' ' . __('messages.in_stock') }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="pos-empty"></div>
                    @endforelse

                    {{-- No results placeholder --}}
                    <div class="pos-empty d-none" id="noResults">
                        <div class="search-icon-wrap">
                            <div class="search-circle"></div>
                            <div class="x-mark"></div>
                            <div class="search-handle"></div>
                        </div>
                        <p>{{ __('messages.no_products_match') }}</p>
                        <small>{{ __('messages.try_different') }}</small>
                        <div class="dots">
                            <div class="dot"></div>
                            <div class="dot"></div>
                            <div class="dot"></div>
                        </div>
                    </div>

                </div>
            </div>
        </main>

        {{-- ══════════ COL 3 — CART ══════════ --}}
        <aside class="pos-cart" id="posCart">

            {{-- Header --}}
            <div class="pos-cart__head">
                <div>
                    <div class="pos-cart__title">
                        {{ __('messages.order') }} <span class="pos-cart__badge" id="cartCount">0</span>
                    </div>
                    <div class="pos-cart__subtitle">{{ $records->reference }}</div>
                </div>
                <div class="pos-cart__actions">
                    <button class="pos-ibtn" data-bs-toggle="modal" data-bs-target="#barcodeModal"
                            title="{{ __('messages.barcode_scan') }}">
                        <i class="bi bi-upc-scan"></i>
                    </button>
                    <button class="pos-ibtn pos-ibtn--danger" data-bs-toggle="modal" data-bs-target="#cancelModal"
                            title="{{ __('messages.cancel_order') }}">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>

            {{-- Customer --}}
            <div class="pos-cart__section">
                <div class="pos-field-label"><i class="bi bi-person-circle"></i> {{ __('messages.customer') }}</div>
                <div class="d-flex gap-2 mb-2">
                    <select id="customerSelect" class="pos-select flex-fill">
                        <option value="">— {{ __('messages.select_customer') }} —</option>
                        @foreach ($customers as $customer)
                            {{-- Pass $defaultCustomerId from the controller; falls back to 6 --}}
                            <option value="{{ $customer->id }}"
                                {{ $customer->id == ($defaultCustomerId ?? 6) ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                    <button class="pos-ibtn" title="{{ __('messages.add_new_customer') }}" data-bs-toggle="modal"
                            data-bs-target="#addcustomerModal">
                        <i class="bi bi-person-plus"></i>
                    </button>
                </div>
            </div>

            {{-- Cart items --}}
            <div class="pos-cart__items" id="cartItems">
                <div class="pos-cart__empty" id="cartEmpty">
                    <i class="bi bi-bag-x"></i>
                    <p>{{ __('messages.cie') }}</p>
                    <small>{{ __('messages.tpa') }}</small>
                </div>
            </div>

            {{-- Totals --}}
            <div class="pos-cart__totals">
                <div class="pos-total-row">
                    <span>{{ __('messages.subtotal') }}</span>
                    <span id="totSubtotal">$0.00</span>
                </div>
                <div class="pos-total-row pos-total-row--disc">
                    <span>
                        {{ __('messages.discount') }}
                        <i class="bi bi-pencil-square ms-1 text-primary" style="cursor:pointer" data-bs-toggle="modal"
                           data-bs-target="#discountModal"></i>
                    </span>
                    <span id="totDiscount">−$0.00</span>
                </div>
                <hr class="pos-sep">
                <div class="pos-total-row pos-total-row--grand">
                    <span>{{ __('messages.total_due') }}</span>
                    <span id="totGrand">$0.00</span>
                </div>
            </div>

            {{-- Charge button --}}
            <div class="pos-cart__footer">
                <button class="pos-charge-btn" id="chargeBtn" disabled data-bs-toggle="modal"
                        data-bs-target="#paymentModal">
                    <i class="bi bi-bag-check-fill"></i>
                    <span>{{ __('messages.charge') }}</span>
                    <span id="chargeAmt">$0.00</span>
                </button>
            </div>

        </aside>

        {{-- Mobile FAB --}}
        <button class="pos-fab" id="posFab" onclick="toggleMobileCart()">
            <i class="bi bi-bag-fill"></i>
            <span class="pos-fab__badge" id="fabCnt">0</span>
        </button>
        <div class="pos-backdrop" id="posBackdrop" onclick="toggleMobileCart()"></div>

    </div>{{-- /.pos-root --}}

    {{-- Alerts --}}
    <div id="alertBox" class="position-fixed top-0 end-0 p-3" style="z-index:9999;"></div>

    {{-- ═══════════════════════════════════════
    MODALS
    ═══════════════════════════════════════ --}}

    {{-- Add Customer --}}
    <div class="modal fade" id="addcustomerModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content pos-modal">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-person-plus me-2"></i>{{ __('messages.add_customer') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="customerForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-2">

                            <div class="col-6">
                                <label class="pos-field-label">{{ __('messages.customer_name') }} *</label>
                                <input type="text" name="name" id="name" class="pos-input w-100"
                                       placeholder="{{ __('messages.enter_name') }}">
                            </div>

                            <div class="col-6">
                                <label class="pos-field-label">{{ __('messages.phone') }} *</label>
                                <input type="text" name="phone" id="phone" class="pos-input w-100"
                                       placeholder="{{ __('messages.enter_phone') }}">
                            </div>

                            <div class="col-12">
                                <label class="pos-field-label">{{ __('messages.email') }} *</label>
                                <input type="email" name="email" id="email" class="pos-input w-100"
                                       placeholder="{{ __('messages.enter_email') }}">
                            </div>

                            <div class="col-12">
                                <label class="pos-field-label">{{ __('messages.address') }} *</label>
                                <textarea name="address" id="address" class="pos-input w-100" rows="2"
                                          placeholder="{{ __('messages.enter_address') }}"></textarea>
                            </div>

                            <div class="col-6">
                                <label class="pos-field-label">{{ __('messages.city') }}</label>
                                <input type="text" name="city" id="city" class="pos-input w-100"
                                       placeholder="{{ __('messages.enter_city') }}">
                            </div>

                            <div class="col-6">
                                <label class="pos-field-label">{{ __('messages.street') }}</label>
                                <input type="text" name="street" id="street" class="pos-input w-100"
                                       placeholder="{{ __('messages.enter_street') }}">
                            </div>

                            <div class="col-6">
                                <label class="pos-field-label">{{ __('messages.no_houses') }}</label>
                                <input type="text" name="number_of_houses" id="number_of_houses" class="pos-input w-100"
                                       placeholder="{{ __('messages.enter_number') }}">
                            </div>

                            <div class="col-12">
                                <label class="pos-field-label">{{ __('messages.logo') }}</label>
                                <input type="file" name="logo" id="logo" class="pos-input w-100">
                            </div>

                        </div>

                        <div id="customerMsg" class="mt-2 small text-muted"></div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="pos-btn pos-btn--ghost" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                        <button type="submit" class="pos-btn pos-btn--primary">{{ __('messages.submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Barcode --}}
    <div class="modal fade" id="barcodeModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content pos-modal">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-upc-scan me-2"></i>{{ __('messages.barcode_code') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" id="barcodeInput" class="pos-input w-100"
                           placeholder="{{ __('messages.scan_or_type') }}" autocomplete="off">
                    <div id="barcodeResult" class="mt-2" style="font-size:11px;color:var(--dim);"></div>
                </div>
                <div class="modal-footer">
                    <button class="pos-btn pos-btn--ghost" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    <button class="pos-btn pos-btn--primary" onclick="addByBarcode()">{{ __('messages.add_item') }}</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Cancel / Clear --}}
    <div class="modal fade" id="cancelModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content pos-modal">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle text-warning me-2"></i>{{ __('messages.clear_order') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-2">
                    <p style="font-size:13px;">{{ __('messages.clear_order_confirm') }}</p>
                </div>
                <div class="modal-footer">
                    <button class="pos-btn pos-btn--ghost" data-bs-dismiss="modal">{{ __('messages.no_keep_it') }}</button>
                    <button class="pos-btn pos-btn--danger" onclick="clearOrder()">{{ __('messages.yes_clear') }}</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Discount --}}
    <div class="modal fade" id="discountModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content pos-modal">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-tag me-2"></i>{{ __('messages.apply_discount') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="pos-field-label mb-2">{{ __('messages.discount_type') }}</div>
                    <select class="pos-select w-100 mb-3" id="discountTypeModal">
                        <option value="fixed">{{ __('messages.fixed_amount') }} ($)</option>
                        <option value="percentage">{{ __('messages.percentage') }} (%)</option>
                    </select>
                    <div class="pos-field-label mb-2">{{ __('messages.value') }}</div>
                    <input type="number" class="pos-input w-100" id="discountValueModal" placeholder="0.00"
                           min="0" step="0.01">
                </div>
                <div class="modal-footer">
                    <button class="pos-btn pos-btn--ghost" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                    <button class="pos-btn pos-btn--primary" onclick="applyDiscountModal()">{{ __('messages.apply') }}</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Payment --}}
    <div class="modal fade" id="paymentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content pos-modal">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-bag-check-fill me-2" style="color:var(--lime);"></i>{{ __('messages.finalize_sale') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    {{-- Order summary (filled by JS) --}}
                    <div id="paymentSummary" class="mb-3 p-3"
                         style="background:var(--ink3);border-radius:10px;border:1px solid var(--wire2);"></div>

                    {{-- Payment Method --}}
                    <div class="mb-3">
                        <div class="pos-field-label mb-2">{{ __('messages.payment_method') }}</div>
                        <input type="hidden" id="paymentMethod" value="cash">
                        <div class="d-flex flex-wrap gap-2" id="paymentMethodGroup">
                            <button type="button" class="pos-method-btn active" data-method="cash"
                                    onclick="selectPaymentMethod('cash')">
                                <i class="bi bi-cash-stack me-1"></i>{{ __('messages.cash') }}
                            </button>
                            <button type="button" class="pos-method-btn" data-method="bank"
                                    onclick="selectPaymentMethod('bank')">
                                <i class="bi bi-bank me-1"></i>{{ __('messages.bank_transfer') }}
                            </button>
                        </div>
                    </div>

                    {{-- Bank selector (bank transfer only) --}}
                    <div class="mb-3" id="bankSelectorWrap" style="display:none;">
                        <div class="pos-field-label mb-2">{{ __('messages.select_bank') }}</div>
                        <input type="hidden" id="bankName" name="bank_name" value="">
                        <div class="d-flex flex-wrap gap-2" id="bankSelectorGroup">
                            <button type="button" class="pos-method-btn" data-bank="aba" onclick="selectBank('aba')">ABA Bank</button>
                            <button type="button" class="pos-method-btn" data-bank="acleda" onclick="selectBank('acleda')">ACLEDA Bank</button>
                            <button type="button" class="pos-method-btn" data-bank="canadia" onclick="selectBank('canadia')">Canadia Bank</button>
                            <button type="button" class="pos-method-btn" data-bank="wing" onclick="selectBank('wing')">Wing Bank</button>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-12 col-12">
                            <div class="row g-3">
                                <div class="col-md-4 col-12">
                                    <div class="pos-field-label mb-2">{{ __('messages.received_amount') }}</div>
                                    <div class="pos-input-group">
                                        <span>$</span>
                                        <input type="number" class="pos-input" id="receivedAmt" placeholder="0.00"
                                               min="0" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="pos-field-label mb-2">{{ __('messages.total_to_pay') }}</div>
                                    <div class="pos-input-group">
                                        <span>$</span>
                                        <input type="number" class="pos-input" id="payingAmt" placeholder="0.00" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="pos-field-label mb-2">{{ __('messages.change') }}</div>
                                    <div class="pos-input-group">
                                        <span>$</span>
                                        <input type="text" class="pos-input" id="changeAmt" placeholder="0.00" readonly
                                               style="font-weight:700;color:var(--green);">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="pos-field-label mb-2">{{ __('messages.payment_note') }}</div>
                                    <textarea class="pos-input w-100" id="payNote" rows="2"
                                              placeholder="{{ __('messages.optional_note') }}" style="resize:none;"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="pos-sep mt-3">

                    <button class="pos-charge-btn w-100 mt-2" id="submitSaleBtn" type="button" onclick="submitSale()">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>{{ __('messages.CONFIRM_CHARGE') }}</span>
                    </button>

                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    /* ═══════════════════════════════════════════════════════
       STATE / CONSTANTS
    ═══════════════════════════════════════════════════════ */

    const STORE_URL          = @json(route('pos.store'));
    const CUSTOMER_STORE_URL = @json(route('customers.store'));
    const CSRF_TOKEN         = @json(csrf_token());
    const NO_IMAGE           = @json(asset('noimage.png'));

    const T = {
        all:           @json(__('messages.all')),
        added:         @json(__('messages.added')),
        totalDue:      @json(__('messages.total_due')),
        subtotal:      @json(__('messages.subtotal')),
        discount:      @json(__('messages.discount')),
        confirm:       @json(__('messages.CONFIRM_CHARGE')),
        each:          @json(__('messages.each')),
        processing:    @json(__('messages.processing')),
        outOfStock:    @json(__('messages.out_of_stock')),
        noMoreStock:   @json(__('messages.no_more_stock')),
        stockLimit:    @json(__('messages.stock_limit')),
        orderCleared:  @json(__('messages.order_cleared')),
        saleComplete:  @json(__('messages.sale_complete')),
        saleFailed:    @json(__('messages.sale_failed')),
        networkError:  @json(__('messages.network_error')),
        invalidTotal:  @json(__('messages.invalid_total')),
        enterReceived: @json(__('messages.enter_received')),
        receivedLess:  @json(__('messages.received_less')),
        selectBank:    @json(__('messages.please_select_bank')),
        invalidMethod: @json(__('messages.invalid_method')),
        addedOk:       @json(__('messages.added_ok')),
        noProduct:     @json(__('messages.no_product_code')),
        saved:         @json(__('messages.saved')),
        wentWrong:     @json(__('messages.something_wrong')),
    };

    let cart = [];
    let activeBrand = 'all';
    let activeCat = 'all';
    let activeSub = 'all';


    /* ═══════════════════════════════════════════════════════
       HELPERS
    ═══════════════════════════════════════════════════════ */

    function esc(str) {
        return String(str ?? '').replace(/[&<>"']/g, ch => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        }[ch]));
    }

    function attr(el, name) {
        return String($(el).attr('data-' + name) ?? '');
    }

    const toCents = n =>
        Math.round((parseFloat(n) || 0) * 100);

    const BTN_CONFIRM_HTML =
        `<i class="bi bi-check-circle-fill"></i>
         <span>${esc(T.confirm)}</span>`;


    /* ═══════════════════════════════════════════════════════
       TOTAL CALCULATION
    ═══════════════════════════════════════════════════════ */

    function calcTotals() {

        const subtotal = cart.reduce(
            (s, i) => s + (i.price * i.qty),
            0
        );

        const discType = $('#discountType').val();

        const discVal =
            parseFloat($('#discountValue').val()) || 0;

        const discAmt =
            discType === 'percentage'
                ? subtotal * discVal / 100
                : Math.min(discVal, subtotal);

        const grand = Math.max(
            0,
            subtotal - discAmt
        );

        return {
            subtotal,
            discAmt,
            grand
        };
    }


    /* ═══════════════════════════════════════════════════════
       PAYMENT METHOD
       CASH / BANK
    ═══════════════════════════════════════════════════════ */

    function selectPaymentMethod(method) {

        $('#paymentMethod').val(method);

        $('#paymentMethodGroup .pos-method-btn')
            .each(function () {

                $(this).toggleClass(
                    'active',
                    $(this).data('method') === method
                );

            });

        const receivedField =
            $('#receivedAmt').closest('.col-md-4');

        const changeField =
            $('#changeAmt').closest('.col-md-4');

        const bankWrap =
            $('#bankSelectorWrap');


        /* ─────────────────────────────────────────────
           CASH
        ───────────────────────────────────────────── */

        if (method === 'cash') {

            receivedField.show();
            changeField.show();
            bankWrap.hide();

            $('#bankName').val('');

            $('#bankSelectorGroup .pos-method-btn')
                .removeClass('active');


            /*
             * Default received amount = total.
             *
             * IMPORTANT:
             * This only happens when selecting CASH.
             *
             * If cashier manually changes:
             *
             * total = $9
             * received = $10
             *
             * we DO NOT overwrite $10 during submit.
             */

            const total =
                parseFloat($('#payingAmt').val()) || 0;

            if (
                !$('#receivedAmt').val() ||
                parseFloat($('#receivedAmt').val()) <= 0
            ) {
                $('#receivedAmt')
                    .val(total.toFixed(2));
            }

            calcChange();

        }


        /* ─────────────────────────────────────────────
           BANK
        ───────────────────────────────────────────── */

        else {

            receivedField.hide();
            changeField.hide();
            bankWrap.show();

            $('#receivedAmt').val('');
            $('#changeAmt').val('');
        }
    }


    function selectBank(bank) {

        $('#bankName').val(bank);

        $('#bankSelectorGroup .pos-method-btn')
            .each(function () {

                $(this).toggleClass(
                    'active',
                    $(this).data('bank') === bank
                );

            });
    }


    /* ═══════════════════════════════════════════════════════
       CASH CHANGE
    ═══════════════════════════════════════════════════════ */

    function calcChange() {

        const received =
            parseFloat($('#receivedAmt').val()) || 0;

        const paying =
            parseFloat($('#payingAmt').val()) || 0;

        const change =
            received - paying;

        $('#changeAmt')
            .val(
                change >= 0
                    ? change.toFixed(2)
                    : '0.00'
            )
            .css(
                'color',
                change >= 0
                    ? 'var(--green)'
                    : 'var(--rose)'
            );
    }


    /* ═══════════════════════════════════════════════════════
       PAYMENT VALIDATION
    ═══════════════════════════════════════════════════════ */

    function validatePayment() {

        const method =
            $('#paymentMethod').val();

        const total =
            parseFloat($('#payingAmt').val()) || 0;


        if (total <= 0) {

            showAlert(
                esc(T.invalidTotal),
                'danger'
            );

            return false;
        }


        /* CASH */

        if (method === 'cash') {

            const received =
                parseFloat($('#receivedAmt').val()) || 0;


            if (received <= 0) {

                showAlert(
                    esc(T.enterReceived),
                    'danger'
                );

                $('#receivedAmt').focus();

                return false;
            }


            if (
                toCents(received) <
                toCents(total)
            ) {

                showAlert(
                    esc(T.receivedLess),
                    'danger'
                );

                $('#receivedAmt').focus();

                return false;
            }


            return true;
        }


        /* BANK */

        if (method === 'bank') {

            if (!$('#bankName').val()) {

                showAlert(
                    esc(T.selectBank),
                    'danger'
                );

                return false;
            }

            return true;
        }


        showAlert(
            esc(T.invalidMethod),
            'danger'
        );

        return false;
    }


    /* ═══════════════════════════════════════════════════════
       PREPARE PAYMENT DATA
       
       IMPORTANT FIX:
       
       CASH:
       total = $9
       received = $10
       change = $1
       
       received_amount stays $10.
    ═══════════════════════════════════════════════════════ */

    function preparePaymentData() {

        if (!validatePayment()) {
            return null;
        }


        const method =
            $('#paymentMethod').val();

        const total =
            parseFloat($('#payingAmt').val()) || 0;


        /* ─────────────────────────────────────────────
           CASH
        ───────────────────────────────────────────── */

        if (method === 'cash') {

            const received =
                parseFloat($('#receivedAmt').val()) || 0;

            const change =
                received - total;


            return {

                payment_method: 'cash',

                bank_name: null,

                // Actual sale total
                total_amount: total,

                // IMPORTANT:
                // Keep customer's actual cash
                received_amount: received,

                // Cash returned to customer
                change_amount:
                    change > 0
                        ? change
                        : 0,

                payment_note:
                    $('#payNote')
                        .val()
                        .trim()
            };
        }


        /* ─────────────────────────────────────────────
           BANK
        ───────────────────────────────────────────── */

        if (method === 'bank') {

            return {

                payment_method: 'bank',

                bank_name:
                    $('#bankName').val(),

                total_amount: total,

                // Bank payment = exact total
                received_amount: total,

                change_amount: 0,

                payment_note:
                    $('#payNote')
                        .val()
                        .trim()
            };
        }


        return null;
    }


    /* ═══════════════════════════════════════════════════════
       BRAND SIDEBAR
    ═══════════════════════════════════════════════════════ */

    function selectBrand(el, brand) {

        activeBrand = String(brand);

        activeCat = 'all';
        activeSub = 'all';

        $('.brand-btn')
            .removeClass('active');

        $(el)
            .addClass('active');

        buildCatTabs();
        filterProducts();
    }


    function filterBrands() {

        const q =
            $('#brandSearch')
                .val()
                .toLowerCase()
                .trim();

        $('.brand-btn')
            .each(function () {

                $(this).toggle(
                    $(this)
                        .find('.brand-btn__name')
                        .text()
                        .toLowerCase()
                        .includes(q)
                );

            });
    }


    /* ═══════════════════════════════════════════════════════
       CATEGORY / SUBCATEGORY
    ═══════════════════════════════════════════════════════ */

    function brandVisibleCards() {

        const cards =
            $('.pos-pcard').toArray();

        return activeBrand === 'all'
            ? cards
            : cards.filter(
                c =>
                    attr(c, 'brand') ===
                    activeBrand
            );
    }


    function buildCatTabs() {

        const visible =
            brandVisibleCards();

        const cats = [
            ...new Set(
                visible
                    .map(c => attr(c, 'cat'))
                    .filter(Boolean)
            )
        ].sort();


        $('#catScroller').html(

            `<button
                class="pos-cat-tab active"
                data-cat="all">

                ${esc(T.all)}

                <span class="pos-cat-tab__cnt">
                    ${visible.length}
                </span>

            </button>` +

            cats.map(cat => {

                const cnt =
                    visible.filter(
                        c =>
                            attr(c, 'cat') === cat
                    ).length;

                return `
                    <button
                        class="pos-cat-tab"
                        data-cat="${esc(cat)}">

                        ${esc(cat)}

                        <span class="pos-cat-tab__cnt">
                            ${cnt}
                        </span>

                    </button>
                `;

            }).join('')
        );


        buildSubcatTabs([]);
    }


    function selectCat(el, cat) {

        activeCat = String(cat);
        activeSub = 'all';

        $('.pos-cat-tab')
            .removeClass('active');

        $(el)
            .addClass('active');


        if (activeCat !== 'all') {

            const subs = [
                ...new Set(

                    brandVisibleCards()
                        .filter(
                            c =>
                                attr(c, 'cat') ===
                                activeCat
                        )
                        .map(
                            c =>
                                attr(c, 'subcat')
                        )
                        .filter(Boolean)

                )
            ].sort();

            buildSubcatTabs(subs);

        } else {

            buildSubcatTabs([]);
        }


        filterProducts();
    }


    function buildSubcatTabs(subs) {

        const bar =
            $('#subcatBar');


        if (!subs.length) {

            bar.removeClass('show');

            $('#subcatScroller')
                .empty();

            return;
        }


        bar.addClass('show');


        $('#subcatScroller').html(

            `<button
                class="pos-subcat-tab active"
                data-sub="all">

                ${esc(T.all)}

            </button>` +

            subs.map(s => `

                <button
                    class="pos-subcat-tab"
                    data-sub="${esc(s)}">

                    ${esc(s)}

                </button>

            `).join('')
        );
    }


    function selectSub(el, sub) {

        activeSub = String(sub);

        $('.pos-subcat-tab')
            .removeClass('active');

        $(el)
            .addClass('active');

        filterProducts();
    }


    /* ═══════════════════════════════════════════════════════
       PRODUCT FILTER
    ═══════════════════════════════════════════════════════ */

    function filterProducts() {

        const q =
            $('#searchInput')
                .val()
                .toLowerCase()
                .trim();

        let visible = 0;


        $('.pos-pcard')
            .each(function () {

                const ok =

                    (
                        activeBrand === 'all' ||
                        attr(this, 'brand') === activeBrand
                    )

                    &&

                    (
                        activeCat === 'all' ||
                        attr(this, 'cat') === activeCat
                    )

                    &&

                    (
                        activeSub === 'all' ||
                        attr(this, 'subcat') === activeSub
                    )

                    &&

                    (
                        !q ||
                        attr(this, 'name')
                            .toLowerCase()
                            .includes(q)

                        ||

                        attr(this, 'code')
                            .toLowerCase()
                            .includes(q)
                    );


                $(this).toggle(ok);

                if (ok) {
                    visible++;
                }
            });


        $('#resCount')
            .text(visible);

        $('#noResults')
            .toggleClass(
                'd-none',
                visible > 0
            );
    }


    function clearSearch() {

        $('#searchInput').val('');

        filterProducts();
    }


    /* ═══════════════════════════════════════════════════════
       BARCODE
    ═══════════════════════════════════════════════════════ */

    function addByBarcode() {

        const code =
            $('#barcodeInput')
                .val()
                .toLowerCase()
                .trim();

        if (!code) {
            return;
        }


        const card =
            $('.pos-pcard')
                .toArray()
                .find(
                    c =>
                        attr(c, 'code')
                            .toLowerCase() === code
                );


        if (card) {

            addToCart(card);

            $('#barcodeResult').html(`
                <span style="color:var(--green)">
                    ✓ ${esc(T.addedOk)}:
                    <strong>
                        ${esc(
                            $(card)
                                .find('.pos-pcard__name')
                                .text()
                                .trim()
                        )}
                    </strong>
                </span>
            `);

            $('#barcodeInput')
                .val('');

        } else {

            $('#barcodeResult').html(`
                <span style="color:var(--rose)">
                    ✗ ${esc(T.noProduct)}:
                    <strong>${esc(code)}</strong>
                </span>
            `);
        }
    }


    /* ═══════════════════════════════════════════════════════
       CART
    ═══════════════════════════════════════════════════════ */

    function addToCart(card) {

        const id =
            Number(attr(card, 'id'));

        const name =
            $(card)
                .find('.pos-pcard__name')
                .text()
                .trim();

        const price =
            parseFloat(
                attr(card, 'price')
            ) || 0;

        const stock =
            parseInt(
                attr(card, 'stock'),
                10
            ) || 0;

        const image =
            attr(card, 'image');


        if (stock <= 0) {

            showAlert(
                esc(T.outOfStock),
                'danger'
            );

            return;
        }


        const existing =
            cart.find(i => i.id === id);


        if (existing) {

            if (
                existing.qty >=
                existing.stock
            ) {

                showAlert(
                    esc(T.noMoreStock),
                    'danger'
                );

                return;
            }

            existing.qty++;

        } else {

            cart.push({
                id,
                name,
                price,
                qty: 1,
                stock,
                image
            });
        }


        renderCart();

        showAlert(
            `${esc(name)} ${esc(T.added)}`,
            'success'
        );
    }


    function changeQty(id, delta) {

        const item =
            cart.find(i => i.id === id);

        if (!item) {
            return;
        }


        item.qty += delta;


        if (item.qty > item.stock) {

            item.qty = item.stock;

            showAlert(
                esc(T.stockLimit),
                'warning'
            );
        }


        if (item.qty < 1) {

            cart =
                cart.filter(
                    i => i.id !== id
                );
        }


        renderCart();
    }


    function updateQty(id, newQty) {

        const item =
            cart.find(i => i.id === id);

        if (!item) {
            return;
        }


        let qty =
            parseInt(newQty, 10);


        if (
            isNaN(qty) ||
            qty < 1
        ) {

            cart =
                cart.filter(
                    i => i.id !== id
                );

        } else {

            if (qty > item.stock) {

                qty = item.stock;

                showAlert(
                    esc(T.stockLimit),
                    'warning'
                );
            }

            item.qty = qty;
        }


        renderCart();
    }


    function removeItem(id) {

        cart =
            cart.filter(
                i => i.id !== id
            );

        renderCart();
    }


    function clearOrder() {

        cart = [];

        $('#discountInput').val('');

        $('#discountType')
            .val('fixed');

        $('#discountValue')
            .val('0');

        renderCart();

        bootstrap.Modal
            .getInstance(
                document.getElementById(
                    'cancelModal'
                )
            )
            ?.hide();

        showAlert(
            esc(T.orderCleared),
            'warning'
        );
    }


    /* ═══════════════════════════════════════════════════════
       RENDER CART
    ═══════════════════════════════════════════════════════ */

    function renderCart() {

        const container =
            $('#cartItems');

        const totalQty =
            cart.reduce(
                (s, i) => s + i.qty,
                0
            );


        $('#cartCount')
            .text(totalQty);

        $('#fabCnt')
            .text(totalQty);


        container
            .find('.pos-cart-item')
            .remove();


        if (!cart.length) {

            $('#cartEmpty')
                .css('display', 'flex');

        } else {

            $('#cartEmpty')
                .hide();


            cart.forEach(item => {

                container.append(`

                    <div class="pos-cart-item">

                        <div class="pos-cart-item__img">

                            <img
                                src="${esc(item.image)}"
                                alt="${esc(item.name)}"
                                onerror="this.src='${NO_IMAGE}'"
                            >

                        </div>


                        <div class="pos-cart-item__info">

                            <div class="pos-cart-item__name">
                                ${esc(item.name)}
                            </div>

                            <div class="pos-cart-item__meta">
                                $${item.price.toFixed(2)}
                                ${esc(T.each)}
                            </div>

                        </div>


                        <div class="pos-cart-item__qty">

                            <button
                                class="pos-qb"
                                onclick="changeQty(${item.id}, -1)">
                                −
                            </button>


                            <input
                                type="number"
                                class="pos-qv"
                                id="qty_${item.id}"
                                value="${item.qty}"
                                min="1"
                                onchange="updateQty(${item.id}, this.value)"
                            >


                            <button
                                class="pos-qb"
                                onclick="changeQty(${item.id}, 1)">
                                +
                            </button>

                        </div>


                        <span class="pos-cart-item__total">
                            $${(
                                item.price *
                                item.qty
                            ).toFixed(2)}
                        </span>


                        <button
                            class="pos-del-btn"
                            onclick="removeItem(${item.id})">

                            <i class="bi bi-x-lg"></i>

                        </button>

                    </div>
                `);
            });
        }


        recalcTotals();
    }


    /* ═══════════════════════════════════════════════════════
       TOTALS
    ═══════════════════════════════════════════════════════ */

    function recalcTotals() {

        const {
            subtotal,
            discAmt,
            grand
        } = calcTotals();


        $('#totSubtotal')
            .text(`$${subtotal.toFixed(2)}`);


        $('#totDiscount')
            .text(`−$${discAmt.toFixed(2)}`);


        $('#totGrand')
            .text(`$${grand.toFixed(2)}`);


        $('#chargeAmt')
            .text(`$${grand.toFixed(2)}`);


        $('#chargeBtn')
            .prop(
                'disabled',
                cart.length === 0
            );
    }


    /* ═══════════════════════════════════════════════════════
       DISCOUNT
    ═══════════════════════════════════════════════════════ */

    function applyDiscountModal() {

        const type =
            $('#discountTypeModal').val();

        const val =
            parseFloat(
                $('#discountValueModal').val()
            ) || 0;


        $('#discountType')
            .val(type);

        $('#discountValue')
            .val(val);


        recalcTotals();


        bootstrap.Modal
            .getInstance(
                document.getElementById(
                    'discountModal'
                )
            )
            ?.hide();
    }


    /* ═══════════════════════════════════════════════════════
       PAYMENT SUMMARY
    ═══════════════════════════════════════════════════════ */

    function buildPaymentSummary() {

        const {
            subtotal,
            discAmt,
            grand
        } = calcTotals();


        const rows =
            cart.map(i => `

                <div class="receipt-line">

                    <span>
                        ${esc(i.name)} × ${i.qty}
                    </span>

                    <strong>
                        $${(
                            i.price *
                            i.qty
                        ).toFixed(2)}
                    </strong>

                </div>

            `).join('');


        $('#paymentSummary').html(`

            ${rows}

            <div class="receipt-line mt-2">

                <span>
                    ${esc(T.subtotal)}
                </span>

                <strong>
                    $${subtotal.toFixed(2)}
                </strong>

            </div>


            <div class="receipt-line">

                <span>
                    ${esc(T.discount)}
                </span>

                <strong style="color:var(--green);">
                    −$${discAmt.toFixed(2)}
                </strong>

            </div>


            <div class="receipt-line total">

                <span>
                    ${esc(T.totalDue)}
                </span>

                <span>
                    $${grand.toFixed(2)}
                </span>

            </div>

        `);


        $('#payingAmt')
            .val(grand.toFixed(2));

        $('#payNote')
            .val('');


        /*
         * Reset submit button
         */

        $('#submitSaleBtn')
            .prop('disabled', false)
            .html(BTN_CONFIRM_HTML);


        /*
         * Always start with CASH.
         *
         * This sets received = total only
         * when the payment modal opens.
         */

        $('#receivedAmt')
            .val(grand.toFixed(2));

        $('#changeAmt')
            .val('0.00');


        selectPaymentMethod('cash');
    }


    /* ═══════════════════════════════════════════════════════
       SUBMIT SALE
       
       IMPORTANT FIX
       
       Example:
       
       Total:    $9
       Received: $10
       Change:   $1
       
       Payload:
       
       amount_paid = 10
       change_amount = 1
    ═══════════════════════════════════════════════════════ */

    function submitSale() {

        const pay =
            preparePaymentData();


        if (!pay) {
            return;
        }


        const btn =
            $('#submitSaleBtn');


        btn
            .prop('disabled', true)
            .html(`

                <span
                    class="spinner-border spinner-border-sm me-2">
                </span>

                ${esc(T.processing)}…

            `);


        const payload = {

            customer_id:
                $('#customerSelect').val()
                    || null,


            cart:
                cart.map(i => ({
                    id: i.id,
                    qty: i.qty
                })),


            payment_method:
                pay.payment_method,


            bank_name:
                pay.bank_name,


            /*
             * IMPORTANT
             *
             * This is the actual amount
             * customer gave.
             *
             * Cash:
             *
             * total = 9
             * received = 10
             *
             * amount_paid = 10
             */

            amount_paid:
                pay.received_amount,


            /*
             * Cash change
             */

            change_amount:
                pay.change_amount,


            discount_type:
                $('#discountType').val(),


            discount_value:
                parseFloat(
                    $('#discountValue').val()
                ) || 0,


            note:
                pay.payment_note
        };


        /*
         * Useful for debugging.
         * Open browser console with F12.
         */

        console.log(
            'POS SALE PAYLOAD:',
            payload
        );


        $.ajax({

            url: STORE_URL,

            method: 'POST',

            contentType:
                'application/json',

            dataType:
                'json',

            headers: {

                'Accept':
                    'application/json',

                'X-CSRF-TOKEN':
                    CSRF_TOKEN

            },

            data:
                JSON.stringify(payload),


            /* ═══════════════════════════════════════
               SUCCESS
            ═══════════════════════════════════════ */

            success: function (data) {

                if (data.success) {

                    showAlert(

                        esc(
                            T.saleComplete.replace(
                                ':ref',
                                data.reference
                            )
                        ),

                        'success'
                    );


                    bootstrap.Modal
                        .getInstance(
                            document.getElementById(
                                'paymentModal'
                            )
                        )
                        ?.hide();


                    setTimeout(
                        () => {

                            window.location.href =
                                data.receipt_url;

                        },
                        600
                    );


                } else {

                    showAlert(

                        esc(
                            data.message ??
                            T.saleFailed
                        ),

                        'danger'
                    );


                    btn
                        .prop(
                            'disabled',
                            false
                        )
                        .html(
                            BTN_CONFIRM_HTML
                        );
                }
            },


            /* ═══════════════════════════════════════
               ERROR
            ═══════════════════════════════════════ */

            error: function (xhr) {

                console.error(
                    'POS SALE ERROR:',
                    xhr.responseJSON ||
                    xhr.responseText
                );


                showAlert(

                    esc(
                        xhr.responseJSON?.message ??
                        T.networkError
                    ),

                    'danger'
                );


                btn
                    .prop(
                        'disabled',
                        false
                    )
                    .html(
                        BTN_CONFIRM_HTML
                    );
            }

        });
    }


    /* ═══════════════════════════════════════════════════════
       MOBILE CART
    ═══════════════════════════════════════════════════════ */

    function toggleMobileCart() {

        const open =
            $('#posCart')
                .hasClass('open');


        $('#posCart')
            .toggleClass(
                'open',
                !open
            );


        $('#posBackdrop')
            .toggleClass(
                'show',
                !open
            );
    }


    /* ═══════════════════════════════════════════════════════
       ALERTS
    ═══════════════════════════════════════════════════════ */

    function showAlert(
        msg,
        type = 'info'
    ) {

        $('#alertBox').html(`

            <div
                class="alert alert-${type}
                       alert-dismissible
                       fade show shadow"
                role="alert">

                ${msg}

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert">
                </button>

            </div>

        `);


        setTimeout(
            () => {
                $('#alertBox .alert')
                    .remove();
            },
            2800
        );
    }


    /* ═══════════════════════════════════════════════════════
       INIT
    ═══════════════════════════════════════════════════════ */

    $(document).ready(function () {

        buildCatTabs();

        filterProducts();


        /* ─────────────────────────────────────────────
           CATEGORY
        ───────────────────────────────────────────── */

        $('#catScroller')
            .on(
                'click',
                '.pos-cat-tab',
                function () {

                    selectCat(
                        this,
                        attr(this, 'cat')
                    );

                }
            );


        /* ─────────────────────────────────────────────
           SUBCATEGORY
        ───────────────────────────────────────────── */

        $('#subcatScroller')
            .on(
                'click',
                '.pos-subcat-tab',
                function () {

                    selectSub(
                        this,
                        attr(this, 'sub')
                    );

                }
            );


        /* ─────────────────────────────────────────────
           PAYMENT MODAL
        ───────────────────────────────────────────── */

        $('#paymentModal')
            .on(
                'shown.bs.modal',
                buildPaymentSummary
            );


        /*
         * Recalculate change whenever
         * cashier changes received amount.
         */

        $('#receivedAmt')
            .on(
                'input',
                calcChange
            );


        /* ─────────────────────────────────────────────
           BARCODE
        ───────────────────────────────────────────── */

        $('#barcodeInput')
            .on(
                'keydown',
                function (e) {

                    if (e.key === 'Enter') {
                        addByBarcode();
                    }

                }
            );


        $('#barcodeModal')
            .on(
                'shown.bs.modal',
                function () {

                    $('#barcodeInput')
                        .focus();

                    $('#barcodeResult')
                        .html('');

                }
            );


        /* ─────────────────────────────────────────────
           ADD CUSTOMER
        ───────────────────────────────────────────── */

        $('#customerForm')
            .on(
                'submit',
                function (e) {

                    e.preventDefault();


                    const form = this;

                    const formData =
                        new FormData(form);


                    $('#customerMsg')
                        .html('')
                        .removeClass(
                            'text-danger text-success'
                        );


                    $.ajax({

                        url:
                            CUSTOMER_STORE_URL,

                        type:
                            'POST',

                        data:
                            formData,

                        processData:
                            false,

                        contentType:
                            false,


                        success:
                            function (response) {

                                $('#customerMsg')
                                    .addClass(
                                        'text-success'
                                    )
                                    .text(
                                        response.success ??
                                        T.saved
                                    );


                                form.reset();


                                /*
                                 * Add newly created
                                 * customer to dropdown.
                                 */

                                if (
                                    response.customer
                                ) {

                                    const opt =
                                        new Option(

                                            response.customer.name,

                                            response.customer.id,

                                            true,

                                            true

                                        );


                                    $('#customerSelect')
                                        .append(opt);
                                }


                                setTimeout(
                                    () => {

                                        bootstrap.Modal
                                            .getInstance(
                                                document.getElementById(
                                                    'addcustomerModal'
                                                )
                                            )
                                            ?.hide();

                                    },
                                    1000
                                );
                            },


                        error:
                            function (xhr) {

                                const errors =
                                    xhr.responseJSON
                                        ?.errors;

                                let errorHtml =
                                    '';


                                if (errors) {

                                    $.each(
                                        errors,
                                        function (
                                            key,
                                            value
                                        ) {

                                            errorHtml +=
                                                esc(
                                                    value[0]
                                                ) +
                                                '<br>';

                                        }
                                    );

                                } else {

                                    errorHtml =
                                        esc(
                                            xhr.responseJSON
                                                ?.message ??
                                            T.wentWrong
                                        );
                                }


                                $('#customerMsg')
                                    .addClass(
                                        'text-danger'
                                    )
                                    .html(
                                        errorHtml
                                    );
                            }

                    });

                }
            );
    });


    /* ═══════════════════════════════════════════════════════
       CUSTOMER DISPLAY SYNC
    ═══════════════════════════════════════════════════════ */

    (function () {

        const KEY =
            'pos-cd-state';

        const HELLO =
            'pos-cd-hello';


        const ch =
            'BroadcastChannel' in window
                ? new BroadcastChannel(
                    'pos-customer-display'
                )
                : null;


        let mode =
            'order';


        function snapshot(extra) {

            const t =
                calcTotals();


            const stage =
                mode === 'order'
                    ? (
                        cart.length
                            ? 'order'
                            : 'idle'
                    )
                    : mode;


            return Object.assign({

                type:
                    'state',

                ts:
                    Date.now(),

                stage:
                    stage,

                ref:
                    $('.pos-cart__subtitle')
                        .first()
                        .text()
                        .trim(),

                customer:
                    $('#customerSelect').val()
                        ? $('#customerSelect option:selected')
                            .text()
                            .trim()
                        : '',


                items:
                    cart.map(i => ({
                        id: i.id,
                        name: i.name,
                        qty: i.qty,
                        price: i.price,
                        image: i.image
                    })),


                subtotal:
                    t.subtotal,

                discount:
                    t.discAmt,

                grand:
                    t.grand,


                payment: {

                    method:
                        $('#paymentMethod')
                            .val(),

                    bank:
                        $('#bankName')
                            .val() || '',

                    received:
                        parseFloat(
                            $('#receivedAmt')
                                .val()
                        ) || 0,

                    change:
                        parseFloat(
                            $('#changeAmt')
                                .val()
                        ) || 0

                }

            }, extra || {});
        }


        function send(extra) {

            const s =
                snapshot(extra);


            try {

                localStorage.setItem(
                    KEY,
                    JSON.stringify(s)
                );

            } catch (e) {}


            if (ch) {
                ch.postMessage(s);
            }
        }


        /* ─────────────────────────────────────────────
           CUSTOMER DISPLAY WINDOW
        ───────────────────────────────────────────── */

        if (
            typeof window.openCustomerDisplay !==
            'function'
        ) {

            window.openCustomerDisplay =
                function () {

                    const w =
                        window.open(
                            @json(url('/customer-display')),
                            'posCustomerDisplay',
                            'width=1100,height=720'
                        );


                    if (w) {
                        w.focus();
                    }


                    setTimeout(
                        send,
                        800
                    );
                };
        }


        /* ─────────────────────────────────────────────
           CART SYNC
        ───────────────────────────────────────────── */

        const _renderCart =
            renderCart;


        renderCart =
            function () {

                _renderCart.apply(
                    this,
                    arguments
                );

                send();
            };


        /* ─────────────────────────────────────────────
           DISCOUNT SYNC
        ───────────────────────────────────────────── */

        const _applyDiscount =
            applyDiscountModal;


        applyDiscountModal =
            function () {

                _applyDiscount.apply(
                    this,
                    arguments
                );

                send();
            };


        /* ─────────────────────────────────────────────
           PAYMENT METHOD SYNC
        ───────────────────────────────────────────── */

        const _selectMethod =
            selectPaymentMethod;


        selectPaymentMethod =
            function () {

                _selectMethod.apply(
                    this,
                    arguments
                );

                send();
            };


        /* ─────────────────────────────────────────────
           BANK SYNC
        ───────────────────────────────────────────── */

        const _selectBank =
            selectBank;


        selectBank =
            function () {

                _selectBank.apply(
                    this,
                    arguments
                );

                send();
            };


        /* ─────────────────────────────────────────────
           EVENTS
        ───────────────────────────────────────────── */

        $(function () {

            $('#customerSelect')
                .on(
                    'change',
                    () => send()
                );


            $('#receivedAmt')
                .on(
                    'input',
                    () => send()
                );


            $('#paymentModal')

                .on(
                    'shown.bs.modal',
                    () => {

                        mode =
                            'payment';

                        send();
                    }
                )

                .on(
                    'hidden.bs.modal',
                    () => {

                        if (
                            mode === 'payment'
                        ) {

                            mode =
                                'order';

                            send();
                        }

                    }
                );


            send();


            /*
             * Customer display heartbeat
             */

            setInterval(
                () => send(),
                5000
            );
        });


        /* ─────────────────────────────────────────────
           SALE LIFECYCLE
        ───────────────────────────────────────────── */

        $(document)

            .on(
                'ajaxSend',
                function (
                    e,
                    xhr,
                    opts
                ) {

                    if (
                        opts.url === STORE_URL
                    ) {

                        mode =
                            'processing';

                        send();
                    }

                }
            )


            .on(
                'ajaxSuccess',
                function (
                    e,
                    xhr,
                    opts,
                    data
                ) {

                    if (
                        opts.url === STORE_URL &&
                        data &&
                        data.success
                    ) {

                        mode =
                            'thanks';

                        send({
                            ref:
                                data.reference
                        });
                    }

                }
            )


            .on(
                'ajaxError',
                function (
                    e,
                    xhr,
                    opts
                ) {

                    if (
                        opts.url === STORE_URL
                    ) {

                        mode =
                            'payment';

                        send();
                    }

                }
            );


        /* ─────────────────────────────────────────────
           CUSTOMER DISPLAY HELLO
        ───────────────────────────────────────────── */

        if (ch) {

            ch.onmessage =
                e => {

                    if (
                        e.data &&
                        e.data.type ===
                        'hello'
                    ) {

                        send();
                    }

                };
        }


        addEventListener(
            'storage',
            e => {

                if (
                    e.key === HELLO
                ) {

                    send();
                }

            }
        );

    })();

</script>
@endpush