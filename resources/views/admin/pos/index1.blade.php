@extends('layouts.master')
@section('title', $pageTitle)

@section('content')
    @php
        $walkInCustomerId = 4;
        $selectedCustomerId = old('customer_id', $sale->customer_id ?? $walkInCustomerId);
    @endphp
    <div class="container-fluid">
        <div class="row">
            {{-- <h1>{{ $pageTitle }}</h1> --}}

            <div class="col-lg-1 col-md-1 border-end desktop-only">
                <div class="d-flex flex-column align-items-center no-scrollbar"
                    style="height: 760px; overflow-y: auto; overflow-x: hidden;">

                    <a href="#"
                        class="card  h-100 border-primary shadow-sm mb-3 text-decoration-none filter-brand active-brand"
                        data-brand-id="all">
                        <img src="{{ asset('tool.png') }}" alt="All"
                            style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                        <span class="text-center" style="font-size: 0.8rem;">All</span>
                    </a>
                    @foreach ($brands as $brand)
                        <a href="#" class="card  h-100 border shadow-sm mb-3 text-decoration-none filter-brand"
                            data-brand-id="{{ $brand->id }}">
                            <img src="{{ $brand->image ? asset('storage/images/' . $brand->image) : asset('noimage.png') }}"
                                style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                            <h6 class="card-title mb-1 text-truncate text-center" style="font-size: 0.75rem;">
                                {{ $brand->name }}
                            </h6>
                        </a>
                    @endforeach

                </div>
            </div>

            <div class="col-lg-7 col-md-6">
                <div class="p-3">
                    <!-- Search, Sort & Offcanvas Category Toggle -->
                    <div class="row mb-4 align-items-center">
                        <div class="col-md-12 mb-2 desktop-only">
                            <div class="input-group">
                                <input type="text" class="form-control search-bar" id="searchInput"
                                    placeholder="Search products...">
                                <button class="btn btn-dark" type="button" id="clearSearch">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-md-4 text-end mb-2 d-lg-none">
                            <button class="btn btn-outline-primary" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#filterOffcanvas" aria-controls="filterOffcanvas">
                                {{ __('messages.filters') }} <i class="bi bi-funnel"></i>
                            </button>
                        </div>

                    </div>

                    <!-- Products Grid -->
                    <div class="row" id="product-list">
                        @forelse ($products as $product)
                            <div class="col-6 col-md-3 col-lg-2 mb-3 product-item" data-brand="{{ $product['brand_id'] }}">
                                <div class="card product-card h-100" data-id="{{ $product['id'] }}"
                                    data-name="{{ $product['name'] }}" data-price="{{ $product['selling_price'] }}"
                                    data-stock="{{ $product['stock_quantity'] }}"
                                    style="background-color: var(--card-bg); border: 1px solid var(--border-color); color: var(--text-color); cursor: pointer; transition: transform var(--transition-speed), background var(--transition-speed);">

                                    <div class="d-flex justify-content-center p-2">
                                        <img src="{{ $product['image'] ? asset('storage/' . $product['image']) : asset('noimage.png') }}"
                                            alt="{{ $product['name'] }}"
                                            style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border-color);">
                                    </div>

                                    <div class="card-body p-2 text-center">
                                        <h6 class="card-title mb-1 text-truncate fw-bold" title="{{ $product['name'] }}">
                                            {{ $product['name'] }}
                                        </h6>
                                        <p class="mb-1 fw-bold" style="color: var(--primary-color);">
                                            ${{ number_format($product['selling_price'], 2) }}
                                        </p>
                                        <p class="mb-0" style="color: var(--text-muted); font-size: 0.8rem;">
                                            {{ __('messages.stock') }}:
                                            <span class="{{ $product['stock_quantity'] <= 0 ? 'text-danger' : '' }}">
                                                {{ $product['stock_quantity'] }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center mt-5">
                                <img src="https://cdn-icons-png.flaticon.com/512/6134/6134065.png" alt="Not Found"
                                    style="max-width: 150px; opacity: 0.5; filter: grayscale(1);">
                                <h3 class="fw-bold mt-3" style="color: var(--text-muted);">{{ __('messages.oops') }}</h3>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Cart Section (unchanged) -->
            <div class="col-lg-4 col-md-5">
                <div class="cart-sidebar p-3 card mb-2">
                    <div class="row">
                        <div class="col-sm-9 text-start">
                            <b>{{ __('messages.order_list') }}</b>
                        </div>
                        <div class="col-sm-3 text-end">
                            <InvoiceNo->#POS{{ $records->id }}</InvoiceNo->
                        </div>
                        <hr>
                        <div class="col-sm-9">
                            <select name="customer_id" class="form-select" aria-label="Select customer">
                                <option value="">-- {{ __('messages.select_customer') }} --</option>

                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ (string) $customer->id === (string) $selectedCustomerId ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-3 text-end d-flex gap-1 justify-content-end">
                            <a class="btn btn-outline-success d-flex align-items-center justify-content-center p-2"
                                data-bs-toggle="modal" data-bs-target="#eModal">
                                <i class="bi bi-person-add"></i>
                            </a>
                            <a class="btn btn-outline-primary d-flex align-items-center justify-content-center p-2"
                                data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <i class="bi bi-upc-scan"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="cart-sidebar p-3 card">
                    <div class="row">
                        <div class="col-sm-9 text-start">
                            <h4>
                                <i class="bi bi-cart3"></i>{{ __('messages.current_order') }}
                                <span class="badge bg-primary ms-2" id="cartCount">0</span>
                            </h4>
                        </div>
                        <div class="col-sm-3 text-end">
                            <button id="clearCartBtn" class="btn btn-outline-danger" data-bs-toggle="modal"
                                data-bs-target="#clearCartModal">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>

                    <hr>
                    <div id="cartItems">
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-cart-x display-4"></i>
                            <p class="mt-2">{{ __('messages.No_items_in_cart') }}</p>
                        </div>
                    </div>
                    <h5 class="mt-4">{{ __('messages.payment_summary') }}</h5>
                    <div class="total-section mt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ __('messages.subtotal') }}:</span>
                            <span id="subtotal">$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ __('messages.tax') }} (8%):</span>
                            <span id="tax">$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ __('messages.discount') }}:
                                <a type="button" data-bs-toggle="modal" data-bs-target="#discountModal">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </span>
                            <span id="discount">$0.00</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fs-4 fw-bold">
                            <span>{{ __('messages.total') }}:</span>
                            <span id="total">$0.00</span>
                        </div>
                    </div>

                </div>
                <div id="paymentBox" class="cart-sidebar p-3 card mt-2 d-none">
                    <div class="fw-semibold mb-3">Total Payment</div>
                    <input type="text" class="form-control search-bar" id="InputValue" placeholder="">
                </div>
                <div id="alertsContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 1080"></div>
                <div class="cart-sidebar p-3 card mt-2">

                    <div class="fw-semibold mb-3">Select Payment</div>

                    <!-- Payment Methods -->
                    <div class="row g-2 mb-3">

                        <div class="col-4">
                            <button id="btnCash"
                                class="btn btn-dark w-100 d-flex align-items-center justify-content-center gap-2">
                                💵 <span>Cash</span>
                            </button>
                        </div>

                        <div class="col-4">
                            <button class="btn btn-dark w-100 d-flex align-items-center justify-content-center gap-2">
                                💳 <span>Card</span>
                            </button>
                        </div>

                        <div class="col-4">
                            <button class="btn btn-dark w-100 d-flex align-items-center justify-content-center gap-2">
                                ⭐ <span>Points</span>
                            </button>
                        </div>

                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        <button class="btn btn-dark w-100">
                            <i class="bi bi-printer me-1"></i> Print Order
                        </button>
                        <button class="btn btn-dark w-100" data-bs-toggle="offcanvas" data-bs-target="#actionBar">
                            <i class="bi bi-cart me-1"></i> Place Order
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <!-- Clear Cart Confirmation Modal -->
    <div class="modal fade" id="clearCartModal" tabindex="-1" aria-labelledby="clearCartModalLabel">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="clearCartModalLabel">{{ __('messages.clear_cart') }}?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="bi bi-exclamation-triangle text-warning display-4 mb-3"></i>
                    <p class="fs-5">{{ __('messages.do_you_want_to_delete') }}
                        <strong>{{ __('messages.allitems') }}</strong> {{ __('messages.from_your_cart') }}
                    </p>
                    <small class="text-muted">{{ __('messages.action_warning') }}</small>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-secondary px-4"
                        data-bs-dismiss="modal">{{ __('messages.no') }}, {{ __('messages.cancel') }}</button>
                    <button type="button" class="btn btn-danger px-4" id="confirmClearCart">{{ __('messages.yes') }},
                        {{ __('messages.clear_cart') }}</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="discountModal" tabindex="-1" aria-labelledby="discountModalLabel">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="discountModalLabel">{{ __('messages.discount') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="mb-2">{{ __('messages.order_discount_type') }}</label>
                    <select class="form-select mb-2" id="discountType">
                        <option value="fixed" selected>Fixed Amount ($)</option>
                        <option value="percentage">Percentage (%)</option>
                    </select>

                    <label class="mb-2">{{ __('messages.value') }}</label>
                    <input type="number" class="form-control" id="discountValue" placeholder="0.00">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                    <button type="button" class="btn btn-primary"
                        onclick="applyDiscount()">{{ __('messages.submit') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="closePos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="closePosLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h1 class="modal-title fs-5 mb-0" id="closePosLabel">
                        {{ __('messages.close_register') }}
                        {{ now()->setTimezone('Asia/Phnom_Penh')->format('Y-m-d H:i:s') }}
                    </h1>

                    <div class="d-flex gap-2 align-items-center">
                        <!-- Print Button -->
                        <button type="button" class="btn btn-outline-success btn-sm"
                            onclick="printAnyModal('closePos')">
                            <i class="bi bi-printer me-1"></i> {{ __('messages.print') }}
                        </button>

                        <!-- Close Button (X) -->
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <form method="POST" action="{{ route('pos.close-register') }}" class="d-inline">
                    @csrf
                    <div class="modal-body">
                        <p>{{ __('messages.cpr') }}</p>
                        <div class="row">
                            <div class="col-md-6">
                                <p>Cash in hand:</p>
                                <p>Cash Payment:</p>
                            </div>
                            <div class="col-md-6 text-end">
                                <p>{{ $records->cash_in_hand }}</p>
                                <p>{{ $records->total_cash }}</p>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cash_in_hand"
                                        class="form-label">{{ __('messages.cash_in_hand') }}</label>
                                    <input min="0" id="cash_in_hand" name="cash_in_hand"
                                        class="form-control @error('cash_in_hand') is-invalid @enderror"
                                        value="{{ $records->cash_in_hand }}" required>
                                    @error('cash_in_hand')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="total_cash" class="form-label">{{ __('messages.total_cash') }}</label>
                                    <input type="text" class="form-control" value="{{ $records->total_cash ?? 0 }}"
                                        id="total_cash" name="total_cash">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="note" class="form-label">{{ __('messages.note') }}</label>
                                <input type="text" class="form-control" value="{{ $records->cash_in_hand }}"
                                    id="note">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-power me-1"></i> {{ __('messages.close') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('messages.barcode') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Scan or type barcode..."
                            aria-label="barcode">
                        <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                    </div>

                    <ul class="list-group list-group-flush" id="cartItems">
                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <div class="me-auto">
                                <div class="fw-bold">Product Name A</div>
                                <small class="text-muted">SKU: 123456</small>
                            </div>
                            <div class="cart-item-qty d-flex align-items-center">
                                <button class="btn btn-sm btn-outline-secondary qty-btn">-</button>
                                <input type="number" class="form-control form-control-sm text-center mx-2 qty-input"
                                    value="1" style="width: 50px;">
                                <button class="btn btn-sm btn-outline-secondary qty-btn">+</button>
                            </div>
                        </li>

                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <div class="me-auto">
                                <div class="fw-bold">Product Name B</div>
                                <small class="text-muted">SKU: 789012</small>
                            </div>
                            <div class="cart-item-qty d-flex align-items-center">
                                <button class="btn btn-sm btn-outline-secondary qty-btn">-</button>
                                <input type="number" class="form-control form-control-sm text-center mx-2 qty-input"
                                    value="1" style="width: 50px;">
                                <button class="btn btn-sm btn-outline-secondary qty-btn">+</button>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    <button type="button" class="btn btn-primary">{{ __('messages.add_item') }}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">{{ __('messages.barcode') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                    <button type="button" class="btn btn-primary">{{ __('messages.add_item') }}</button>
                </div>
            </div>
        </div>
    </div>



    <div class="offcanvas offcanvas-end" tabindex="-1" id="filterOffcanvas" aria-labelledby="filterOffcanvasLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="filterOffcanvasLabel">{{ __('messages.brands') }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-2">
            <div class="container-fluid">
                <div class="row g-2 no-scrollbar" style="height: 760px; overflow-y: auto; overflow-x: hidden;">

                    <div class="col-6">
                        <a href="#"
                            class="card h-100 border-primary shadow-sm text-decoration-none filter-brand active-brand"
                            data-brand-id="all">
                            <div class="card-body d-flex flex-column align-items-center p-2">
                                <img src="{{ asset('tool.png') }}"
                                    style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                                <span class="text-center mt-1" style="font-size: 0.8rem;">All</span>
                            </div>
                        </a>
                    </div>

                    @foreach ($brands as $brand)
                        <div class="col-6">
                            <a href="#" class="card h-100 border shadow-sm text-decoration-none filter-brand"
                                data-brand-id="{{ $brand->id }}">
                                <div class="card-body d-flex flex-column align-items-center p-2">
                                    <img src="{{ $brand->image ? asset('storage/images/' . $brand->image) : asset('noimage.png') }}"
                                        style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                                    <h6 class="card-title mb-0 mt-1 text-truncate text-center"
                                        style="font-size: 0.75rem;">
                                        {{ $brand->name }}
                                    </h6>
                                </div>
                            </a>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>

    </div>

    <!-- All your modals and offcanvas remain exactly the same -->

@endsection



@push('scripts')
    <script>
        let cart = [];
        const $paymentBox = $('#paymentBox');

        function applyDiscount() {
            const type = document.getElementById("discountType").value;
            const val = document.getElementById("discountValue").value;
            const displayElement = document.getElementById("discount");

            if (val === "" || isNaN(val)) {
                alert("Please enter a valid number");
                return;
            }
            let formattedText = type === "percentage" ? `${val}%` : `$${val}`;
            displayElement.innerText = formattedText;
            const modalElement = document.getElementById('discountModal');
            const modalInstance = bootstrap.Modal.getInstance(modalElement);
            modalInstance.hide();
        }


        function toggleClearCartButton() {
            const cartItems = document.querySelector('#cartItems');
            const clearBtn = document.querySelector('#clearCartBtn');

            const hasItems = cartItems.querySelectorAll('.cart-item').length > 0;

            clearBtn.disabled = !hasItems;
        }

        function filterProducts() {
            const searchKeyword = $('#searchInput').val().toLowerCase().trim();
            const selectedbrand_id = $('.filter-brand.active-brand').data('brand-id');

            let visibleCount = 0;

            $('.product-item').each(function() {
                const $item = $(this);
                const product_name = $item.find('.card-title').text().toLowerCase();
                const brand_id = $item.data('brand');

                const btnSearch = product_name.includes(searchKeyword);

                const btnBrand = (selectedbrand_id === 'all' || selectedbrand_id == brand_id);

                if (btnSearch && btnBrand) {
                    $item.removeClass('d-none');
                    visibleCount++;
                } else {
                    $item.addClass('d-none');
                }
            });

            // Show/hide "No products found" message
            if (visibleCount === 0) {
                $('#product-list .no-results-message').removeClass('d-none');
            } else {
                $('#product-list .no-results-message').addClass('d-none');
            }
        }

        $(document).ready(function() {
            // Brand filter click
            $('.filter-brand').on('click', function(e) {
                e.preventDefault();

                const brand_id = $(this).data('brand-id');

                // Update active state
                $('.filter-brand').removeClass('border-primary active-brand').addClass('border shadow-sm');
                $(this).addClass('border-primary active-brand').removeClass('border shadow-sm');

                // Trigger unified filter
                filterProducts();
            });

            // Search input
            $('#searchInput').on('keyup', function() {
                filterProducts();
            });

            // Clear search button
            $('#clearSearch').on('click', function() {
                $('#searchInput').val('');
                filterProducts();
            });

            // Initial filter on page load
            filterProducts();
        });

        $('#btnCash').on('click', function(e) {
            e.preventDefault();
            $paymentBox.removeClass('d-none').addClass('d-block');
            $('#InputValue').trigger('focus');
        });

        $(document).on('click', '.product-card', function() {
            const product_id = Number($(this).data('id'));
            const product_name = $(this).data('name');
            const productPrice = Number($(this).data('price'));
            const productStock = Number($(this).data('stock'));

            if (isNaN(productPrice)) return;

            if (productStock <= 0) {
                showAlert('{{ __('messages.This_product_is_out_of_stock') }}', 'danger');
                return;
            }


            let existingItem = cart.find(item => item.id === product_id);
            if (existingItem) {
                if (existingItem.quantity >= existingItem.stock) {
                    showAlert('{{ __('messages.no_more_stock_available') }}', 'danger');
                    return;
                }

                existingItem.quantity++;
            } else {
                cart.push({
                    id: product_id,
                    name: product_name,
                    price: productPrice,
                    quantity: 1,
                    stock: productStock

                });
            }

            updateCart();
        });

        function updateCart() {
            const cartItems = $('#cartItems');
            cartItems.empty();

            if (cart.length === 0) {
                cartItems.html(
                    '<div class="text-center text-muted py-5"><i class="bi bi-cart-x display-4"></i><p class="mt-2">No items in cart</p></div>'
                );
                $('#cartCount').text(0);
                $('#subtotal').text('$0.00');
                $('#tax').text('$0.00');
                $('#discount').text('$0.00');
                $('#total').text('$0.00');
            } else {
                let subtotal = 0;

                let val = 0;
                cart.forEach(item => {
                    const itemTotal = item.price * item.quantity;
                    subtotal += itemTotal;
                    cartItems.append(`
                <div class="cart-item d-flex align-items-center py-2 border-bottom">
                    <div class="cart-item-name d-flex align-items-center flex-grow-1">
                        <button class="btn btn-sm text-danger me-2" onclick="removeItem(${item.id})">
                            <i class="bi bi-trash"></i>
                        </button>
                        <span class="fw-medium">${item.name}</span>
                    </div>
                    <div class="cart-item-qty d-flex align-items-center justify-content-center">
                        <button class="qty-btn" onclick="updateQuantity(${item.id}, -1)">-</button>
                        <input type="number" class="qty-input" min="1" value="${item.quantity}" onchange="setQuantity(${item.id}, this.value)">
                        <button class="qty-btn" onclick="updateQuantity(${item.id}, 1)">+</button>
                    </div>
                    <div class="cart-item-price text-end fw-semibold">$${(item.price * item.quantity).toFixed(2)}</div>
                </div>`);
                });

                const tax = subtotal * 0.08;
                const discount = val;
                const total = subtotal + tax + discount;

                $('#cartCount').text(cart.length);
                $('#subtotal').text(`$${subtotal.toFixed(2)}`);
                $('#tax').text(`$${tax.toFixed(2)}`);
                $('#total').text(`$${total.toFixed(2)}`);
            }
            toggleClearCartButton();
        }

        function updateQuantity(product_id, change) {
            let item = cart.find(item => item.id === product_id);
            if (!item) return;

            const newQty = item.quantity + change;

            if (newQty < 1) return;
            if (newQty > item.stock) {
                showAlert('{{ __('messages.no_more_stock_available') }}', 'danger');
                return;
            }

            item.quantity = newQty;
            updateCart();
        }

        function removeItem(product_id) {
            cart = cart.filter(item => item.id !== product_id);
            updateCart();
        }

        function setQuantity(product_id, value) {
            let item = cart.find(i => i.id === product_id);
            if (!item) return;

            let qty = parseInt(value);

            if (isNaN(qty) || qty < 1) qty = 1;
            if (qty > item.stock) qty = item.stock;

            item.quantity = qty;
            updateCart();
        }

        $('#confirmClearCart').on('click', function() {
            cart = [];
            updateCart();
            $('#clearCartModal').modal('hide');
            $('#alertsContainer').html(`
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ __('messages.product_removed_from_cart') }}
                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close"></button>
                </div>
            `);

        });

        function printAnyModal(modalId) {
            const modal = document.getElementById(modalId);
            if (!modal) return;

            // Clone modal content
            const modalClone = modal.querySelector('.modal-content').cloneNode(true);

            // REMOVE all buttons from clone
            modalClone.querySelectorAll('button').forEach(btn => btn.remove());

            // OPTIONAL: remove inputs border (looks cleaner on print)
            modalClone.querySelectorAll('input').forEach(input => {
                input.setAttribute('readonly', true);
                input.classList.remove('form-control-sm');
            });

            const printWindow = window.open('', '', 'width=900,height=650');
            printWindow.document.write(`
        <html>
            <head>
                <title>Print</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body { padding: 20px; }
                </style>
            </head>
            <body>
                ${modalClone.outerHTML}
            </body>
        </html>
    `);

            printWindow.document.close();
            printWindow.focus();

            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 300);
        }

        function showAlert(message, type = 'danger') {
            $('#alertsContainer').html(`
                <div class="alert alert-${type} alert-dismissible fade show shadow" role="alert">
                    ${message}
                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close"></button>
                </div>
            `);
            setTimeout(() => {
                $('.alert').alert('close');
            }, 3000);
        }
    </script>
@endpush
