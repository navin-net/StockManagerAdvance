@extends('admin.layouts.master')
@section('title', $pageTitle)

@section('content')
    @push('styles')
    @endpush

    {{-- ─────────────────────────────────────────────────────────
    HIDDEN DISCOUNT FIELDS (read by recalcTotals + submit)
    ──────────────────────────────────────────────────────────── --}}
    <input type="hidden" id="discountType" value="fixed">
    <input type="hidden" id="discountValue" value="0">

    <div class="pos-root">

        {{-- ══════════ COL 1 — BRAND SIDEBAR ══════════ --}}
        <aside class="pos-sidebar">
            <div class="pos-sidebar__head">
                <span class="pos-sidebar__label">Brands</span>
            </div>
            <div class="pos-sidebar__search">
                <i class="bi bi-search"></i>
                <input type="text" id="brandSearch" placeholder="Search…" oninput="filterBrands()">
            </div>
            <div class="pos-sidebar__list" id="brandList">

                <button class="brand-btn active" data-brand="all" onclick="selectBrand(this,'all')">
                    <span class="brand-btn__icon">🏪</span>
                    <span class="brand-btn__name">All</span>
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
                    <input type="text" id="searchInput" placeholder="Search products, code…" oninput="filterProducts()">
                    <button class="pos-search-clear" onclick="clearSearch()"></button>
                </div>
                <div class="pos-count"><span id="resCount">{{ $products->count() }}</span> items</div>
            </div>

            {{-- Category bar --}}
            <div class="pos-catbar">
                <div class="pos-catbar__label"><i class="bi bi-grid-3x3-gap"></i></div>
                <div class="pos-catbar__scroll" id="catScroller">
                    <button class="pos-cat-tab active" data-cat="all" onclick="selectCat(this,'all')">
                        All <span class="pos-cat-tab__cnt">{{ $products->count() }}</span>
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
                            data-name="{{ strtolower($product->name) }}"
                            data-code="{{ strtolower($product->code ?? '') }}" data-price="{{ $product->selling_price }}"
                            data-stock="{{ $product->stock_quantity }}"
                            data-image="{{ $product->image ? asset('storage/' . $product->image) : asset('noimage.png') }}"
                            onclick="addToCart(this)">

                            <div class="pos-pcard__img">
                                @if ($product->stock_quantity <= 0)
                                    <span class="pos-pcard__badge pos-pcard__badge--out">Out</span>
                                @elseif($product->stock_quantity <= 5)
                                    <span class="pos-pcard__badge pos-pcard__badge--low">Low</span>
                                @endif
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('noimage.png') }}"
                                    alt="{{ $product->name }}">
                            </div>

                            <div class="pos-pcard__body">
                                <div class="pos-pcard__name" title="{{ $product->name }}">{{ $product->name }}</div>
                                <div class="pos-pcard__price">${{ number_format($product->selling_price, 2) }}</div>
                                <div class="pos-pcard__stock">
                                    {{ $product->stock_quantity <= 0 ? 'Out of stock' : $product->stock_quantity . ' in stock' }}
                                </div>
                            </div>

                            {{-- <div class="pos-pcard__plus"><i class="bi bi-plus-lg"></i></div> --}}
                        </div>
                    @empty
                        <div class="pos-empty">
                            <i class="bi bi-box-seam"></i>
                            <p>No products</p>
                        </div>
                    @endforelse

                    {{-- No results placeholder --}}
                    <div class="pos-empty d-none" id="noResults">
                        <div class="search-icon-wrap">
                            <div class="search-circle"></div>
                            <div class="x-mark"></div>
                            <div class="search-handle"></div>
                        </div>
                        <p>No products match</p>
                        <small>Try a different keyword or filter</small>
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
                        ORDER <span class="pos-cart__badge" id="cartCount">0</span>
                    </div>
                    <div class="pos-cart__subtitle">{{ $sales->reference }}</div>
                </div>
                <div class="pos-cart__actions">
                    <button class="pos-ibtn" data-bs-toggle="modal" data-bs-target="#barcodeModal" title="Barcode scan">
                        <i class="bi bi-upc-scan"></i>
                    </button>
                    <button class="pos-ibtn pos-ibtn--danger" data-bs-toggle="modal" data-bs-target="#cancelModal"
                        title="Cancel order">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>

            {{-- Customer --}}
            <div class="pos-cart__section">
                <div class="pos-field-label"><i class="bi bi-person-circle"></i> Customer</div>
                <div class="d-flex gap-2 mb-2">
                    <select id="customerSelect" class="pos-select flex-fill">
                        <option value="">— Select Customer —</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}" {{ $customer->id == 4 ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                    {{-- <button class="pos-ibtn" title="Add new customer" onclick="alert('Open add-customer modal here')">
                        --}}
                    <button class="pos-ibtn" title="Add new customer" data-bs-toggle="modal"
                        data-bs-target="#addcustomerModal">
                        <i class="bi bi-person-plus"></i>
                    </button>
                </div>
                <div class="pos-field-label mb-1">
                    <i class="bi bi-building"></i> {{ __('messages.warehouse') }}
                </div>
                <div class="d-flex gap-2">
                    <select id="warehouseSelect" class="pos-select flex-fill locked" disabled>
                        <option value="">— Select Customer —</option>
                        @foreach ($warehouse as $customer)
                            <option value="{{ $customer->id }}" {{ $customer->id == 1 ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Discount inline --}}
            {{-- <div class="pos-discount-row">
                <div class="pos-field-label mb-0" style="white-space:nowrap;">
                    <i class="bi bi-tag"></i> Discount
                </div>
                <select class="pos-select" style="width:85px;" onchange="setDiscountType(this.value)">
                    <option value="fixed">$ Fixed</option>
                    <option value="percentage">% Pct</option>
                </select>
                <input type="number" class="pos-input flex-fill" id="discountInput" placeholder="0" min="0" step="0.01"
                    oninput="setDiscountValue(this.value)">
            </div> --}}

            {{-- Cart items --}}
            <div class="pos-cart__items" id="cartItems">
                <div class="pos-cart__empty" id="cartEmpty">
                    <i class="bi bi-bag-x"></i>
                    <p>Cart is empty</p>
                    <small>Tap a product to add</small>
                </div>
            </div>

            {{-- Totals --}}
            <div class="pos-cart__totals">
                <div class="pos-total-row">
                    <span>Subtotal</span>
                    <span id="totSubtotal">$0.00</span>
                </div>
                <div class="pos-total-row pos-total-row--disc">
                    <span>
                        Discount
                        <i class="bi bi-pencil-square ms-1 text-primary" style="cursor:pointer" data-bs-toggle="modal"
                            data-bs-target="#discountModal"></i>
                    </span>
                    <span id="totDiscount">−$0.00</span>
                </div>
                <div class="pos-total-row">
                    <span>Tax (8%)</span>
                    <span id="totTax">$0.00</span>
                </div>
                <hr class="pos-sep">
                <div class="pos-total-row pos-total-row--grand">
                    <span>Total Due</span>
                    <span id="totGrand">$0.00</span>
                </div>
            </div>

            {{-- Payment method --}}
            {{-- <div class="pos-pay-methods">
                <button class="pos-pay-btn active" data-method="cash" onclick="selectPayMethod(this,'cash')">
                    <i class="bi bi-cash-stack"></i> Cash
                </button>
                <button class="pos-pay-btn" data-method="card" onclick="selectPayMethod(this,'card')">
                    <i class="bi bi-credit-card-2-front"></i> Card
                </button>
                <button class="pos-pay-btn" data-method="qr" onclick="selectPayMethod(this,'qr')">
                    <i class="bi bi-qr-code-scan"></i> QR Pay
                </button>
            </div> --}}

            {{-- Charge button --}}
            <div class="pos-cart__footer">
                <button class="pos-charge-btn" id="chargeBtn" disabled data-bs-toggle="modal"
                    data-bs-target="#paymentModal">
                    <i class="bi bi-bag-check-fill"></i>
                    <span>CHARGE</span>
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
    <div class="modal fade" id="addcustomerModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content pos-modal">

                <!-- Header -->
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-person-plus me-2"></i>Add Customer
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="customerForm" enctype="multipart/form-data">
                    @csrf
                    <!-- Body -->
                    <div class="modal-body">
                        <div class="row g-2">

                            <!-- Name -->
                            <div class="col-6">
                                <label class="pos-field-label">Customer Name *</label>
                                <input type="text" name="name" id="name" class="pos-input w-100" placeholder="Enter name">
                            </div>

                            <!-- Phone -->
                            <div class="col-6">
                                <label class="pos-field-label">Phone *</label>
                                <input type="text" name="phone" id="phone" class="pos-input w-100" placeholder="Enter phone">
                            </div>

                            <!-- Email -->
                            <div class="col-12">
                                <label class="pos-field-label">Email *</label>
                                <input type="email" name="email" id="email" class="pos-input w-100" placeholder="Enter email">
                            </div>

                            <!-- Address -->
                            <div class="col-12">
                                <label class="pos-field-label">Address *</label>
                                <textarea name="address" id="address" class="pos-input w-100" rows="2" placeholder="Enter address"></textarea>
                            </div>

                            <!-- City -->
                            <div class="col-6">
                                <label class="pos-field-label">City</label>
                                <input type="text" name="city" id="city" class="pos-input w-100" placeholder="Enter city">
                            </div>

                            <!-- Street -->
                            <div class="col-6">
                                <label class="pos-field-label">Street</label>
                                <input type="text" name="street" id="street" class="pos-input w-100" placeholder="Enter street">
                            </div>

                            <!-- Number of Houses -->
                            <div class="col-6">
                                <label class="pos-field-label">No. Houses</label>
                                <input type="text" name="number_of_houses" id="number_of_houses" class="pos-input w-100" placeholder="Enter number">
                            </div>

                            <!-- Warehouse -->
                            <div class="col-6">
                                <label class="pos-field-label">Warehouse *</label>
                                <select name="warehouse_id" id="warehouse_id" class="pos-input w-100">
                                    <option value="">Select warehouse</option>
                                    @foreach($warehouse as $warehous)
                                        <option value="{{ $warehous->id }}">{{ $warehous->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Logo -->
                            <div class="col-12">
                                <label class="pos-field-label">Logo</label>
                                <input type="file" name="logo" id="logo" class="pos-input w-100">
                            </div>

                        </div>

                        <!-- Message -->
                        <div id="customerMsg" class="mt-2 small text-muted"></div>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer">
                        <button type="button" class="pos-btn pos-btn--ghost" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="pos-btn pos-btn--primary">
                            {{ __('messages.submit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="barcodeModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content pos-modal">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-upc-scan me-2"></i>Barcode / Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" id="barcodeInput" class="pos-input w-100"
                        placeholder="Scan or type product code…" autocomplete="off">
                    <div id="barcodeResult" class="mt-2" style="font-size:11px;color:var(--dim);"></div>
                </div>
                <div class="modal-footer">
                    <button class="pos-btn pos-btn--ghost" data-bs-dismiss="modal">Cancel</button>
                    <button class="pos-btn pos-btn--primary" onclick="addByBarcode()">Add Item</button>
                </div>
            </div>
        </div>
    </div>



    {{-- Cancel / Clear Modal --}}
    <div class="modal fade" id="cancelModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content pos-modal">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle text-warning me-2"></i>Clear Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-2">
                    <p style="font-size:13px;">Are you sure you want to clear all items from this order?</p>
                </div>
                <div class="modal-footer">
                    <button class="pos-btn pos-btn--ghost" data-bs-dismiss="modal">No, keep it</button>
                    <button class="pos-btn pos-btn--danger" onclick="clearOrder()">Yes, clear</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Discount Modal (for detailed edit via link) --}}
    <div class="modal fade" id="discountModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content pos-modal">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-tag me-2"></i>Apply Discount</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="pos-field-label mb-2">Discount Type</div>
                    <select class="pos-select w-100 mb-3" id="discountTypeModal">
                        <option value="fixed">Fixed Amount ($)</option>
                        <option value="percentage">Percentage (%)</option>
                    </select>
                    <div class="pos-field-label mb-2">Value</div>
                    <input type="number" class="pos-input w-100" id="discountValueModal" placeholder="0.00"
                        min="0" step="0.01">
                </div>
                <div class="modal-footer">
                    <button class="pos-btn pos-btn--ghost" data-bs-dismiss="modal">Close</button>
                    <button class="pos-btn pos-btn--primary" onclick="applyDiscountModal()">Apply</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Payment Modal --}}
    <div class="modal fade" id="paymentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content pos-modal">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-bag-check-fill me-2" style="color:var(--lime);"></i>Finalize Sale
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    {{-- Order summary --}}
                    <div id="paymentSummary" class="mb-3 p-3"
                        style="background:var(--ink3);border-radius:10px;border:1px solid var(--wire2);">
                        {{-- filled by JS --}}
                    </div>

                    <div class="row g-3">
                        <div class="col-md-12 col-12">
                            <div class="row g-3">
                                <div class="col-md-4 col-12">
                                    <div class="pos-field-label mb-2">Received Amount</div>
                                    <div class="pos-input-group">
                                        <span>$</span>
                                        <input type="number" class="pos-input" id="receivedAmt" placeholder="0.00"
                                            min="0" step="0.01" oninput="calcChange()" required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="pos-field-label mb-2">Total to Pay</div>
                                    <div class="pos-input-group">
                                        <span>$</span>
                                        <input type="number" class="pos-input" id="payingAmt" placeholder="0.00"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="pos-field-label mb-2">Change</div>
                                    <div class="pos-input-group">
                                        <span>$</span>
                                        <input type="text" class="pos-input" id="changeAmt" placeholder="0.00"
                                            readonly style="font-weight:700;color:var(--green);">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="pos-field-label mb-2">Payment Note</div>
                                    <textarea class="pos-input w-100" id="payNote" rows="2" placeholder="Optional note…" style="resize:none;"></textarea>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-md-3 col-12">
                            <div class="pos-field-label mb-2 text-center">Quick Cash</div>
                            <div class="d-flex flex-column gap-1">
                                <button class="pos-quick-cash" data-val="20">$20</button>
                                <button class="pos-quick-cash" data-val="50">$50</button>
                                <button class="pos-quick-cash" data-val="100">$100</button>
                                <button class="pos-quick-cash" data-val="500">$500</button>
                                <button class="pos-btn pos-btn--danger mt-1" id="clearQuick">Clear</button>
                            </div>
                        </div> --}}
                    </div>

                    <hr class="pos-sep mt-3">

                    <button class="pos-charge-btn w-100 mt-2" id="submitSaleBtn" type="button" onclick="submitSale()">
                        <i class="bi bi-check-circle-fill"></i>
                        <span>CONFIRM & CHARGE</span>
                    </button>

                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        /* ═══════════════════════════════════════════════════════
               STATE
            ═══════════════════════════════════════════════════════ */
        let cart = [];
        let activeBrand = 'all';
        let activeCat = 'all';
        let activeSub = 'all';
        let payMethod = 'cash';

        const STORE_URL = '{{ route('pos.store') }}';
        const CSRF_TOKEN = '{{ csrf_token() }}';

        function broadcastCart() {
            const subtotal = cart.reduce((s, i) => s + i.price * i.qty, 0);
            const discType = $('#discountType').val();
            const discVal = parseFloat($('#discountValue').val()) || 0;
            const discAmt = discType === 'percentage' ? subtotal * discVal / 100 : Math.min(discVal, subtotal);
            const afterDisc = subtotal - discAmt;
            const tax = afterDisc * 0.08;
            const grand = afterDisc + tax;

            localStorage.setItem('pos_cart', JSON.stringify({
                items: cart.map(i => ({
                    name: i.name,
                    price: i.price,
                    qty: i.qty
                })),
                subtotal: subtotal,
                discount: discAmt,
                tax: tax,
                grand: grand,
                pay_method: payMethod,
            }));
        }

        /* ═══════════════════════════════════════════════════════
           PAYMENT CHECK
        ═══════════════════════════════════════════════════════ */
        function checkPayment() {
            const received = parseFloat($('#receivedAmt').val()) || 0;
            const paying = parseFloat($('#payingAmt').val()) || 0;
            $('#submitSaleBtn').prop('disabled', received < paying);
        }

        $('#receivedAmt').on('input', checkPayment);

        /* ═══════════════════════════════════════════════════════
           BRAND SIDEBAR
        ═══════════════════════════════════════════════════════ */
        function selectBrand(el, brand) {
            activeBrand = brand;
            activeCat = 'all';
            activeSub = 'all';
            $('.brand-btn').removeClass('active');
            $(el).addClass('active');
            buildCatTabs();
            filterProducts();
        }

        function filterBrands() {
            const q = $('#brandSearch').val().toLowerCase();
            $('.brand-btn').each(function() {
                $(this).toggle($(this).find('.brand-btn__name').text().toLowerCase().includes(q));
            });
        }

        /* ═══════════════════════════════════════════════════════
           CATEGORY TABS
        ═══════════════════════════════════════════════════════ */
        function buildCatTabs() {
            const cards = $('.pos-pcard').toArray();
            const visible = activeBrand === 'all' ? cards : cards.filter(c => $(c).data('brand') == activeBrand);
            const cats = [...new Set(visible.map(c => $(c).data('cat')).filter(Boolean))].sort();

            $('#catScroller').html(
                `<button class="pos-cat-tab active" data-cat="all" onclick="selectCat(this,'all')">
                    All <span class="pos-cat-tab__cnt">${visible.length}</span>
                </button>` +
                cats.map(cat => {
                    const cnt = visible.filter(c => $(c).data('cat') === cat).length;
                    return `<button class="pos-cat-tab" data-cat="${cat}" onclick="selectCat(this,'${cat}')">
                        ${cat} <span class="pos-cat-tab__cnt">${cnt}</span>
                    </button>`;
                }).join('')
            );

            buildSubcatTabs([]);
        }

        function selectCat(el, cat) {
            activeCat = cat;
            activeSub = 'all';
            $('.pos-cat-tab').removeClass('active');
            $(el).addClass('active');

            if (cat !== 'all') {
                const subs = [...new Set(
                    $('.pos-pcard').toArray()
                    .filter(c => (activeBrand === 'all' || $(c).data('brand') == activeBrand) && $(c).data(
                        'cat') === cat)
                    .map(c => $(c).data('subcat'))
                    .filter(Boolean)
                )].sort();
                buildSubcatTabs(subs);
            } else {
                buildSubcatTabs([]);
            }
            filterProducts();
        }

        function buildSubcatTabs(subs) {
            const bar = $('#subcatBar');
            if (!subs.length) {
                bar.removeClass('show');
                return;
            }
            bar.addClass('show');
            $('#subcatScroller').html(
                `<button class="pos-subcat-tab active" onclick="selectSub(this,'all')">All</button>` +
                subs.map(s => `<button class="pos-subcat-tab" onclick="selectSub(this,'${s}')">${s}</button>`).join('')
            );
        }

        function selectSub(el, sub) {
            activeSub = sub;
            $('.pos-subcat-tab').removeClass('active');
            $(el).addClass('active');
            filterProducts();
        }

        /* ═══════════════════════════════════════════════════════
           PRODUCT FILTER
        ═══════════════════════════════════════════════════════ */
        function filterProducts() {
            const q = $('#searchInput').val().toLowerCase().trim();
            let visible = 0;

            $('.pos-pcard').each(function() {
                const ok =
                    (activeBrand === 'all' || $(this).data('brand') == activeBrand) &&
                    (activeCat === 'all' || $(this).data('cat') === activeCat) &&
                    (activeSub === 'all' || $(this).data('subcat') === activeSub) &&
                    (!q || $(this).data('name').includes(q) || $(this).data('code').includes(q));

                $(this).toggle(ok);
                if (ok) visible++;
            });

            $('#resCount').text(visible);
            $('#noResults').toggleClass('d-none', visible > 0);
        }

        /* ═══════════════════════════════════════════════════════
           BARCODE
        ═══════════════════════════════════════════════════════ */
        function addByBarcode() {
            const code = $('#barcodeInput').val().toLowerCase().trim();
            if (!code) return;

            const card = $('.pos-pcard').toArray().find(c => $(c).data('code') === code);

            if (card) {
                addToCart(card);
                $('#barcodeResult').html(
                    `<span style="color:var(--green)">✓ Added: <strong>${$(card).data('name')}</strong></span>`);
                $('#barcodeInput').val('');
            } else {
                $('#barcodeResult').html(
                    `<span style="color:var(--rose)">✗ No product found for code: <strong>${code}</strong></span>`);
            }
        }

        /* ═══════════════════════════════════════════════════════
           CART ACTIONS
        ═══════════════════════════════════════════════════════ */
        function addToCart(card) {
            const id = Number($(card).data('id'));
            const name = $(card).find('.pos-pcard__name').text().trim();
            const price = parseFloat($(card).data('price'));
            const stock = parseInt($(card).data('stock'));
            const image = $(card).data('image');

            if (stock <= 0) {
                showAlert('Out of stock', 'danger');
                return;
            }

            const existing = cart.find(i => i.id === id);
            if (existing) {
                if (existing.qty >= existing.stock) {
                    showAlert('No more stock available', 'danger');
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
            showAlert(`${name} {{ __('messages.added') }}`, 'success');
        }

        function changeQty(id, delta) {
            const item = cart.find(i => i.id === id);
            if (!item) return;
            item.qty += delta;
            if (item.qty > item.stock) {
                item.qty = item.stock;
                showAlert('Stock limit reached', 'warning');
            }
            if (item.qty < 1) cart = cart.filter(i => i.id !== id);
            renderCart();
        }

        function removeItem(id) {
            cart = cart.filter(i => i.id !== id);
            renderCart();
        }

        function clearOrder() {
            cart = [];
            $('#discountInput').val('');
            $('#discountType').val('fixed');
            $('#discountValue').val('0');
            renderCart();
            bootstrap.Modal.getInstance(document.getElementById('cancelModal'))?.hide();
            showAlert('Order cleared', 'warning');
        }

        /* ═══════════════════════════════════════════════════════
           RENDER CART
        ═══════════════════════════════════════════════════════ */
        function renderCart() {
            const container = $('#cartItems');
            const totalQty = cart.reduce((s, i) => s + i.qty, 0);

            $('#cartCount').text(totalQty);
            $('#fabCnt').text(totalQty);

            container.find('.pos-cart-item').remove();

            if (!cart.length) {
                $('#cartEmpty').css('display', 'flex');
            } else {
                $('#cartEmpty').hide();
                cart.forEach(item => {
                    container.append(`
                        <div class="pos-cart-item">
                            <div class="pos-cart-item__img">
                                <img src="${item.image}" alt="${item.name}" onerror="this.src='{{ asset('noimage.png') }}'">
                            </div>
                            <div class="pos-cart-item__info">
                                <div class="pos-cart-item__name">${item.name}</div>
                                <div class="pos-cart-item__meta">$${item.price.toFixed(2)} each</div>
                            </div>
                            <div class="pos-cart-item__qty">
                                <button class="pos-qb" onclick="changeQty(${item.id}, -1)">−</button>
                                <span class="pos-qv">${item.qty}</span>
                                <button class="pos-qb" onclick="changeQty(${item.id}, 1)">+</button>
                            </div>
                            <span class="pos-cart-item__total">$${(item.price * item.qty).toFixed(2)}</span>
                            <button class="pos-del-btn" onclick="removeItem(${item.id})"><i class="bi bi-x-lg"></i></button>
                        </div>
                    `);
                });
            }


            recalcTotals();
            broadcastCart();
        }

        /* ═══════════════════════════════════════════════════════
           TOTALS
        ═══════════════════════════════════════════════════════ */
        function recalcTotals() {
            const subtotal = cart.reduce((s, i) => s + i.price * i.qty, 0);
            const discType = $('#discountType').val();
            const discVal = parseFloat($('#discountValue').val()) || 0;
            const discAmt = discType === 'percentage' ? subtotal * discVal / 100 : Math.min(discVal, subtotal);
            const afterDisc = subtotal - discAmt;
            const tax = afterDisc * 0.08;
            const grand = afterDisc + tax;

            $('#totSubtotal').text(`$${subtotal.toFixed(2)}`);
            $('#totDiscount').text(`−$${discAmt.toFixed(2)}`);
            $('#totTax').text(`$${tax.toFixed(2)}`);
            $('#totGrand').text(`$${grand.toFixed(2)}`);
            $('#chargeAmt').text(`$${grand.toFixed(2)}`);
            $('#chargeBtn').prop('disabled', cart.length === 0);
            broadcastCart();
        }

        /* ═══════════════════════════════════════════════════════
           DISCOUNT
        ═══════════════════════════════════════════════════════ */
        function setDiscountType(val) {
            $('#discountType').val(val);
            recalcTotals();
        }

        function setDiscountValue(val) {
            $('#discountValue').val(val || 0);
            recalcTotals();
        }

        function applyDiscountModal() {
            const type = $('#discountTypeModal').val();
            const val = parseFloat($('#discountValueModal').val()) || 0;
            $('#discountType').val(type);
            $('#discountValue').val(val);
            $('.pos-discount-row select').val(type);
            $('#discountInput').val(val);
            recalcTotals();
            bootstrap.Modal.getInstance(document.getElementById('discountModal'))?.hide();
        }

        /* ═══════════════════════════════════════════════════════
           PAYMENT METHOD
        ═══════════════════════════════════════════════════════ */
        function selectPayMethod(el, method) {
            payMethod = method;
            $('.pos-pay-btn').removeClass('active');
            $(el).addClass('active');
            broadcastCart();
        }

        /* ═══════════════════════════════════════════════════════
           PAYMENT MODAL
        ═══════════════════════════════════════════════════════ */
        function buildPaymentSummary() {
            const subtotal = cart.reduce((s, i) => s + i.price * i.qty, 0);
            const discType = $('#discountType').val();
            const discVal = parseFloat($('#discountValue').val()) || 0;
            const discAmt = discType === 'percentage' ? subtotal * discVal / 100 : Math.min(discVal, subtotal);
            const afterDisc = subtotal - discAmt;
            const tax = afterDisc * 0.08;
            const grand = afterDisc + tax;

            const rows = cart.map(i =>
                `<div class="receipt-line">
                    <span>${i.name} × ${i.qty}</span>
                    <strong>$${(i.price * i.qty).toFixed(2)}</strong>
                 </div>`
            ).join('');

            $('#paymentSummary').html(`
                ${rows}
                <div class="receipt-line mt-2"><span>Subtotal</span><strong>$${subtotal.toFixed(2)}</strong></div>
                <div class="receipt-line"><span>Discount</span><strong style="color:var(--green);">−$${discAmt.toFixed(2)}</strong></div>
                <div class="receipt-line"><span>Tax (8%)</span><strong>$${tax.toFixed(2)}</strong></div>
                <div class="receipt-line total"><span>Total Due</span><span>$${grand.toFixed(2)}</span></div>
            `);

            $('#payingAmt').val(grand.toFixed(2));
            $('#receivedAmt').val('');
            $('#changeAmt').val('');
            resetQuickCash();
        }

        function calcChange() {
            const received = parseFloat($('#receivedAmt').val()) || 0;
            const paying = parseFloat($('#payingAmt').val()) || 0;
            const change = received - paying;
            $('#changeAmt')
                .val(change.toFixed(2))
                .css('color', change >= 0 ? 'var(--green)' : 'var(--rose)');
        }

        function resetQuickCash() {
            $('.pos-quick-cash').removeClass('active');
        }

        /* ═══════════════════════════════════════════════════════
           SUBMIT SALE
        ═══════════════════════════════════════════════════════ */
        function submitSale() {
            const received = parseFloat($('#receivedAmt').val()) || 0;
            const paying = parseFloat($('#payingAmt').val()) || 0;

            if (received < paying) {
                showAlert('Received amount must be greater than or equal to total.', 'danger');
                return;
            }

            const btn = $('#submitSaleBtn');
            btn.prop('disabled', true)
                .html('<span class="spinner-border spinner-border-sm me-2"></span> Processing…');

            const payload = {
                customer_id: $('#customerSelect').val() || null,
                warehouse_id: $('#warehouseSelect').val() || null,
                cart: cart.map(i => ({
                    id: i.id,
                    qty: i.qty
                })),
                payment_method: payMethod,
                amount_paid: received,
                discount_type: $('#discountType').val(),
                discount_value: parseFloat($('#discountValue').val()) || 0,
                note: $('#payNote').val(),
            };

            $.ajax({
                url: STORE_URL,
                method: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                data: JSON.stringify(payload),

                success: function(data) {
                    if (data.success) {

                        localStorage.setItem('pos_cart', JSON.stringify({
                            completed: true,
                            grand: parseFloat($('#payingAmt').val())
                        }));
                        setTimeout(() => localStorage.setItem('pos_cart', JSON.stringify({
                            items: []
                        })), 5000);

                        showAlert(`Sale ${data.reference} complete!`, 'success');
                        bootstrap.Modal.getInstance(document.getElementById('paymentModal'))?.hide();
                        setTimeout(() => window.location.href = data.receipt_url, 600);
                    } else {
                        showAlert(data.message ?? 'Sale failed. Check stock.', 'danger');
                        btn.prop('disabled', false)
                            .html('<i class="bi bi-check-circle-fill"></i> <span>CONFIRM & CHARGE</span>');
                    }
                },

                error: function(xhr) {
                    const msg = xhr.responseJSON?.message ?? 'Network error — please try again.';
                    showAlert(msg, 'danger');
                    btn.prop('disabled', false)
                        .html('<i class="bi bi-check-circle-fill"></i> <span>CONFIRM & CHARGE</span>');
                }
            });
        }

        /* ═══════════════════════════════════════════════════════
           MOBILE CART TOGGLE
        ═══════════════════════════════════════════════════════ */
        function toggleMobileCart() {
            const open = $('#posCart').hasClass('open');
            $('#posCart').toggleClass('open', !open);
            $('#posBackdrop').toggleClass('show', !open);
        }

        /* ═══════════════════════════════════════════════════════
           ALERTS
        ═══════════════════════════════════════════════════════ */
        function showAlert(msg, type = 'info') {
            $('#alertBox').html(`
                <div class="alert alert-${type} alert-dismissible fade show shadow" role="alert">
                    ${msg}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`);
            setTimeout(() => $('#alertBox .alert').remove(), 2800);
        }

        /* ═══════════════════════════════════════════════════════
           INIT
        ═══════════════════════════════════════════════════════ */
        $(document).ready(function() {

            buildCatTabs();
            filterProducts();

            $('#paymentModal').on('shown.bs.modal', buildPaymentSummary);

            $('#barcodeInput').on('keydown', function(e) {
                if (e.key === 'Enter') addByBarcode();
            });

            $('#barcodeModal').on('shown.bs.modal', function() {
                $('#barcodeInput').focus();
                $('#barcodeResult').html('');
            });

            $('.pos-quick-cash').on('click', function() {
                resetQuickCash();
                $(this).addClass('active');
                $('#receivedAmt').val(parseFloat($(this).data('val')).toFixed(2));
                calcChange();
            });

            $('#clearQuick').on('click', function() {
                resetQuickCash();
                $('#receivedAmt').val('');
                $('#changeAmt').val('');
            });


            $('#customerForm').on('submit', function (e) {
                e.preventDefault();

                let formData = new FormData(this);


                $('#customerMsg').html('').removeClass('text-danger text-success');

                $.ajax({
                    url: "{{ route('customers.store') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,

                    success: function (response) {
                        $('#customerMsg')
                            .addClass('text-success')
                            .html(response.success);

                        // Reset form
                        form.reset();

                        // Close modal after 1s
                        setTimeout(() => {
                            $('#addcustomerModal').modal('hide');
                        }, 1000);
                    },

                    error: function (xhr) {
                        let errors = xhr.responseJSON.errors;
                        let errorHtml = '';

                        $.each(errors, function (key, value) {
                            errorHtml += value[0] + '<br>';
                        });

                        $('#customerMsg')
                            .addClass('text-danger')
                            .html(errorHtml);
                    }
                });

            });



        });
    </script>

    <script id="8g3k2f">
        const select = document.getElementById('warehouseSelect');

        select.addEventListener('mousedown', function(e) {
            e.preventDefault(); // stop opening dropdown
        });
    </script>
@endpush
