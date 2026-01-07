@extends('layouts.master')
@section('title', 'content')

@section('content')
    <div class="container-fluid">
        <div class="row">
            {{-- <h1>{{ $pageTitle }}</h1> --}}

            <div class="col-lg-1 col-md-1 border-end desktop-only">
                <div class="d-flex flex-column align-items-center no-scrollbar"
                    style="height: 760px; overflow-y: auto; overflow-x: hidden;">

                    <a href="#"
                        class="card product-card h-100 border-primary shadow-sm mb-3 text-decoration-none filter-brand active-brand"
                        data-brand-id="all">
                        <img src="{{ asset('tool.png') }}" alt="All"
                            style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                        <span class="text-center" style="font-size: 0.8rem;">All</span>
                    </a>
                    @foreach ($brands as $brand)
                        <a href="#"
                            class="card product-card h-100 border shadow-sm mb-3 text-decoration-none filter-brand"
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
                                Filters <i class="bi bi-funnel"></i>
                            </button>
                        </div>

                    </div>

                    <!-- Products Grid -->
                    <div class="row" id="product-list">
                        @forelse ($products as $product)
                            <div class="col-6 col-md-3 col-lg-2 mb-3 product-item" data-brand="{{ $product['brand_id'] }}">
                                <div class="card product-card h-100 border shadow-sm" data-id="{{ $product['id'] }}"
                                    data-name="{{ $product['name'] }}" data-price="{{ $product['selling_price'] }}">

                                    <img src="{{ $product['image'] ? asset('storage/' . $product['image']) : asset('noimage.png') }}"
                                        alt="{{ $product['name'] }}"
                                        style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">

                                    <div class="card-body p-2">
                                        <h6 class="card-title mb-1 text-truncate" title="{{ $product['name'] }}">
                                            {{ $product['name'] }}
                                        </h6>
                                        <p class="card-text mb-0 text-muted">
                                            ${{ number_format($product['selling_price'], 2) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center text-danger no-results-message">
                                <p>No products found.</p>
                            </div>
                        @endforelse

                        <!-- Dynamic "No products found" for client-side filtering -->
                        <div class="col-12 text-center text-danger no-results-message d-none">
                            <p>No products found.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cart Section (unchanged) -->
            <div class="col-lg-4 col-md-5">
                <div class="cart-sidebar p-3 card mb-2">
                    <div class="row">
                        <div class="col-sm-9 text-start">
                            <b>Order List</b>
                        </div>
                        <div class="col-sm-3 text-end">
                            <InvoiceNo->#ORD123</InvoiceNo->
                        </div>
                        <hr>

                        @php
                            $walkInCustomerId = 4;
                            $selectedCustomerId = old('customer_id', $sale->customer_id ?? $walkInCustomerId);
                        @endphp

                        <div class="col-sm-9">
                            <select name="customer_id" class="form-select" aria-label="Select customer">
                                <option value="">-- Select Customer --</option>

                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}"
                                        {{ (string) $customer->id === (string) $selectedCustomerId ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-3 text-end d-flex gap-1 justify-content-end">
                            <a class="btn btn-outline-success d-flex align-items-center justify-content-center p-2">
                                <i class="bi bi-person-add"></i>
                            </a>
                            <a class="btn btn-outline-primary d-flex align-items-center justify-content-center p-2">
                                <i class="bi bi-upc-scan"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="cart-sidebar p-3 card">
                    <div class="row">
                        <div class="col-sm-9 text-start">
                            <h4>
                                <i class="bi bi-cart3"></i> Current Order
                                <span class="badge bg-primary ms-2" id="cartCount">0</span>
                            </h4>
                        </div>
                        <div class="col-sm-3 text-end">
                            <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#clearCartModal">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>

                    <hr>
                    <div id="cartItems">
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-cart-x display-4"></i>
                            <p class="mt-2">No items in cart</p>
                        </div>
                    </div>
                    <h5>Payment Summary</h5>
                    <div class="total-section mt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span id="subtotal">$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax (8%):</span>
                            <span id="tax">$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Discount:
                                <a type="button" data-bs-toggle="modal" data-bs-target="#discountModal">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </span>
                            <span id="subtotal">$0.00</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fs-4 fw-bold">
                            <span>Total:</span>
                            <span id="total">$0.00</span>
                        </div>
                    </div>

                </div>
                <div id="paymentBox" class="cart-sidebar p-3 card mt-2 d-none">
                    <div class="fw-semibold mb-3">Total Payment</div>
                    <input type="text" class="form-control search-bar" id="InputValue" placeholder="">
                </div>
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
    <div class="modal fade" id="clearCartModal" tabindex="-1" aria-labelledby="clearCartModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="clearCartModalLabel">Clear Cart?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="bi bi-exclamation-triangle text-warning display-4 mb-3"></i>
                    <p class="fs-5">Do you want to delete <strong>all items</strong> from your cart?</p>
                    <small class="text-muted">This action cannot be undone.</small>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">No, Cancel</button>
                    <button type="button" class="btn btn-danger px-4" id="confirmClearCart">Yes, Clear Cart</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade " id="discountModal"  tabindex="-1"
        aria-labelledby="discountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="discountModalLabel">{{ __('messages.discount') }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="mb-2">Order Discount Type</label>
                    <select class="form-select mb-2" aria-label="Default select example">
                        <option selected>Open this select menu</option>
                        <option value="1">One</option>
                        <option value="2">Two</option>
                        <option value="3">Three</option>
                    </select>
                    <label class="mb-2">Value</label>
                    <input type="text" class="form-control" id="DiscountValue" placeholder="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                    <button type="button" class="btn btn-primary">{{ __('messages.submit') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- All your modals and offcanvas remain exactly the same -->
    <!-- (Bank Selection Modal, Filter Offcanvas, Category Offcanvas, Receipt Modal, etc.) -->
    <!-- Omitted here for brevity — keep them unchanged -->

@endsection

@push('style')
    <style>
        .no-scrollbar {
            -ms-overflow-style: none !important;
            scrollbar-width: none !important;
            overflow-y: scroll !important;
        }

        .no-scrollbar::-webkit-scrollbar {
            width: 0 !important;
            height: 0 !important;
            display: none !important;
        }

        .no-scrollbar::-webkit-scrollbar-track,
        .no-scrollbar::-webkit-scrollbar-thumb {
            background: transparent !important;
            display: none !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        let cart = [];
        const $paymentBox = $('#paymentBox');

        // Unified function to filter products and update "No results" message
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

            if (isNaN(productPrice)) return;

            let existingItem = cart.find(item => item.id === product_id);
            if (existingItem) {
                existingItem.quantity++;
            } else {
                cart.push({
                    id: product_id,
                    name: product_name,
                    price: productPrice,
                    quantity: 1
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
                $('#total').text('$0.00');
                return;
            }

            let subtotal = 0;
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
            const total = subtotal + tax;

            $('#cartCount').text(cart.length);
            $('#subtotal').text(`$${subtotal.toFixed(2)}`);
            $('#tax').text(`$${tax.toFixed(2)}`);
            $('#total').text(`$${total.toFixed(2)}`);
        }

        function updateQuantity(product_id, change) {
            let item = cart.find(item => item.id === product_id);
            if (item) {
                item.quantity += change;
                if (item.quantity <= 0) cart = cart.filter(i => i.id !== product_id);
                updateCart();
            }
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
            item.quantity = qty;
            updateCart();
        }

        $('#confirmClearCart').on('click', function() {
            cart = [];
            updateCart();
            $('#clearCartModal').modal('hide');
            $('#alertsContainer').html(`
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ __('messages.please_select_someone_columns_first_if_you_want_to_export') }}
                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close"></button>
                </div>
            `);

        });
    </script>
@endpush
