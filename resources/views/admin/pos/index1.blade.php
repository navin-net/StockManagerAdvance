@extends('admin.layouts.master')
@section('title', $pageTitle)

@section('content')
    @php
        $walkInCustomerId = 4;
        $selectedCustomerId = old('customer_id', $sale->customer_id ?? $walkInCustomerId);
    @endphp
    {{-- @if (is_mobile())

    @else --}}

    <div class="container-fluid pos-wrapper h-100">
        <div class="row h-100">

            {{-- <h1>{{ $pageTitle }}</h1> --}}

            {{-- <div class="col-lg-1 col-md-1 border-end desktop-only" style="max-height:100%; overflow-y:auto;">
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
                </div> --}}

            <div class="col-lg-8 col-md-6">
                <div class="p-3">
                    <!-- Search, Sort & Offcanvas Category Toggle -->
                    <div class="row align-items-center">
                        <div class="col-md-12 mb-2 desktop-only">
                            <div class="input-group">
                                <input type="text" class="form-control search-bar" id="searchInput"
                                    placeholder="Search products...">
                                <button class="btn btn-dark" type="button" id="clearSearch">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </div>
                        <div class="cat-pills-wrapper" style="position: relative;">
                            <!-- Left arrow -->
                            <div class="scroll-arrow scroll-left" onclick="scrollPills(-150)">
                                <i class="bi bi-arrow-left"></i>
                            </div>

                            <!-- Pills container -->
                            <div class="cat-pills">
                                <!-- "All" button -->
                                <button class="pill active" data-brand-id="all" onclick="filterBrand(this,'all')">
                                    {{-- <i class="bi bi-three-dots"></i> --}}
                                    <span>{{ __('messages.all') }}</span>
                                </button>

                                <!-- Loop through brands -->
                                @foreach ($brands as $brand)
                                    <button class="pill" data-brand-id="{{ $brand->id }}"
                                        onclick="filterBrand(this,'{{ $brand->id }}')">
                                        <img src="{{ $brand->image ? asset('storage/images/' . $brand->image) : asset('noimage.png') }}"
                                            alt="{{ $brand->name }}"
                                            style="width:24px;height:24px;object-fit:cover;border-radius:4px;">
                                        <span>{{ $brand->name }}</span>
                                    </button>
                                @endforeach
                            </div>

                            <!-- Right arrow -->
                            <div class="scroll-arrow scroll-right" onclick="scrollPills(150)">
                                <i class="bi bi-arrow-right"></i>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="row mb-2">

                    </div>
                    {{-- <hr> --}}
                    <!-- Products Grid -->
                    <div class="row" id="product-list" style="max-height:500px; overflow-y:auto; desktop-only">
                        @forelse ($products as $product)
                            <div class="col-6 col-md-3 col-lg-2 mb-3 product-item" data-brand="{{ $product['brand_id'] }}">
                                <div class="card product-card h-100" data-id="{{ $product['id'] }}"
                                    data-name="{{ $product['name'] }}" data-price="{{ $product['selling_price'] }}"
                                    data-stock="{{ $product['stock_quantity'] }}"
                                    style="background-color: var(--card-bg); border: 1px solid var(--border-color); color: var(--text-color); cursor: pointer; transition: transform var(--transition-speed), background var(--transition-speed);">

                                    <div class="d-flex justify-content-center p-2">
                                        <div style="position: relative;">
                                            <img src="{{ $product['image'] ? asset('storage/' . $product['image']) : asset('noimage.png') }}"
                                                alt="{{ $product['name'] }}"
                                                style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border-color);">

                                            @if ($product['stock_quantity'] <= 0)
                                                <span
                                                    class="position-absolute top-0 end-0 translate-middle-y badge rounded-pill bg-danger text-white px-2 py-1 me-1"
                                                    style="font-size: 0.75rem; z-index: 2;">
                                                    {{ __('messages.sold_out') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="card-body p-2 text-center">
                                        <h6 class="card-title mb-1 text-truncate fw-bold {{ $product['stock_quantity'] <= 0 ? 'text-danger' : '' }}"
                                            title="{{ $product['name'] }}">
                                            {{ $product['name'] }}
                                        </h6>
                                        <p class="mb-1 fw-bold " style="color: var(--primary-color);">
                                            ${{ number_format($product['selling_price'], 2) }}
                                        </p>
                                        {{-- <p class="mb-0" style="color: var(--text-muted); font-size: 0.8rem;">
                                            {{ __('messages.stock') }}:
                                            <span class="{{ $product['stock_quantity'] <= 0 ? 'text-danger' : '' }}">
                                                {{ $product['stock_quantity'] }}
                                            </span>
                                        </p> --}}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center mt-5">
                                <img src="https://cdn-icons-png.flaticon.com/512/6134/6134065.png" alt="Not Found"
                                    style="max-width: 150px; opacity: 0.5; filter: grayscale(1);">
                                <h3 class="fw-bold mt-3" style="color: var(--text-muted);">{{ __('messages.oops') }}
                                </h3>
                            </div>
                        @endforelse
                        <div class="col-12 text-center mt-4 no-results-message d-none">
                            <img src="{{ asset('noimage.png') }}" style="max-width:120px;  opacity:.4;">
                            <h6 class="mt-3 text-muted">
                                No products found
                            </h6>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cart Section (unchanged) -->
            <div class="col-lg-4 col-md-6" style="max-height:100%; overflow-y:auto;">
                <div class="cart-sidebar p-3 card mb-2 desktop-only">
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
                                        {{ $customer->id === $selectedCustomerId ? 'selected' : '' }}>
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
                <div class="cart-sidebar p-3 card desktop-only">
                    <div class="row">
                        <div class="col-sm-9 text-start">
                            <h4>
                                <i class="bi bi-cart3"></i>{{ __('messages.current_order') }}
                                {{-- <span class="badge bg-primary ms-2" id="cartCount">0</span> --}}
                            </h4>
                        </div>
                        <div class="col-sm-3 text-end">
                            {{-- <button id="clearCartBtn" class="btn btn-outline-danger" data-bs-toggle="modal"
                                    data-bs-target="#clearCartModal">
                                    <i class="bi bi-trash"></i>
                                </button> --}}
                        </div>
                    </div>

                    <hr>
                    <div class="row">
                        <div class="col-sm-2 text-center">#</div>
                        <div class="col-sm-4 text-start">{{ __('messages.products') }}</div>
                        <div class="col-sm-3 text-end">{{ __('messages.quality') }}</div>
                        <div class="col-sm-3 text-center">{{ __('total') }}</div>
                    </div>
                    <div id="cartItems">
                        <div class="text-center text-muted py-20">
                            <i class="bi bi-cart-x display-4"></i>
                            <p class="mt-2">{{ __('messages.No_items_in_cart') }}</p>
                        </div>
                    </div>
                    {{-- <h5 class="mt-4">{{ __('messages.payment_summary') }}</h5> --}}
                    <div class="total-section mt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ __('messages.items') }}:</span>
                            <span class="badge bg-primary ms-2" id="cartCount">0</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ __('messages.shipping') }}
                                <a href="#" data-bs-toggle="modal" data-bs-target="#discountModal">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </span>
                            <span id="tax">$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ __('messages.tax') }}(8%)</span>
                            <span id="tax">$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>
                                {{ __('messages.discount') }}:
                                <a href="#" data-bs-toggle="modal" data-bs-target="#discountModal">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </span>
                            <span id="discount">$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ __('messages.subtotal') }}:</span>
                            <span id="subtotal">$0.00</span>
                        </div>

                        <hr>
                        <div class="d-flex justify-content-between fs-4 fw-bold">
                            <span>{{ __('messages.total_payable') }}:</span>
                            <span id="total"
                                oninput="document.getElementById('paying-amount').value = this.value">$0.00</span>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex gap-2">
                        <button class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#cancelModal">
                            <i class="bi bi-x-circle me-1"></i> {{ __('messages.cancel') }}
                        </button>

                        <button id="checkoutBtn" class="btn btn-primary w-100" data-bs-toggle="modal"
                            data-bs-target="#paymentModal">
                            <i class="bi bi-cart me-1"></i> {{ __('messages.payment') }}
                        </button>
                    </div>
                </div>

                <div id="alertsContainer" class="position-fixed top-0 end-0 p-3" style="z-index: 1080"></div>
                {{-- <div class="cart-sidebar p-3 card mt-2">

                    <div class="fw-semibold mb-3">Select Payment</div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-dark w-100">
                            <i class="bi bi-printer me-1"></i>{{ __('messages.cancel') }}
                        </button>
                        <button class="btn btn-dark w-100" data-bs-toggle="offcanvas" data-bs-target="#actionBar">
                            <i class="bi bi-cart me-1"></i> {{ __('messages.payment') }}
                        </button>
                    </div>

                </div> --}}
            </div>
        </div>
    </div>
    {{-- @endif --}}


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
                        data-bs-dismiss="modal">{{ __('messages.no') }},
                        {{ __('messages.cancel') }}</button>
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

    <div class="modal fade" id="cancelModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                        Cancel Order
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body text-center">
                    Are you sure you want to cancel this order?
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        No
                    </button>

                    <button type="button" class="btn btn-danger" onclick="confirmCancel()">
                        Yes, Cancel
                    </button>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="paymentModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="endScreenLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Finalize Sale</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-10">


                            <div class="row g-3">
                                <div class="col-md-4 col-12">
                                    <label class="form-label">Received Amount</label>
                                    <div class="input-group mb-2">
                                        <span class="input-group-text">$</span>
                                        <input type="text" class="form-control text-end" placeholder="0.00"
                                            inputmode="decimal" aria-label="Received amount" id="received-amount">
                                    </div>
                                </div>

                                <!-- Paying Amount -->
                                <div class="col-md-4 col-12">
                                    <label class="form-label">Paying Amount</label>
                                    <div class="input-group mb-2">
                                        <span class="input-group-text">$</span>
                                        <input type="text" class="form-control text-end" placeholder="0.00"
                                            id="paying-amount">
                                    </div>
                                </div>

                                <!-- Change (readonly or calculated) -->
                                <div class="col-md-4 col-12">
                                    <label class="form-label">Change</label>
                                    <div class="input-group mb-2">
                                        <span class="input-group-text">$</span>
                                        <input type="text" class="form-control text-end " placeholder="0.00" readonly
                                            aria-label="Change amount" id="change-amount">
                                    </div>
                                </div>

                                <div class="col-md-12 col-12">
                                    <label class="form-label fw-bold">{{ __('Payment Note') }}</label>
                                    <div class="input-group mb-2">
                                        <textarea class="form-control" id="transaction-description" rows="3"
                                            placeholder="Add payment notes or details here..." aria-label="Description">
                                        </textarea>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-2 text-end">
                            <div class="fw-bold pb-2 text-uppercase text-center small">Quick Cash</div>

                            <button class="btn btn-info w-100 mb-1 position-relative quick-cash" data-value="60">
                                60
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none cash-count">
                                    1
                                </span>
                            </button>

                            <button class="btn btn-info w-100 mb-1 position-relative quick-cash" data-value="120">
                                120
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none cash-count">
                                    0
                                </span>
                            </button>

                            <button class="btn btn-primary w-100 mb-1 position-relative quick-cash" data-value="500">
                                500
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none cash-count">
                                    0
                                </span>
                            </button>

                            <button class="btn btn-primary w-100 mb-1 position-relative quick-cash" data-value="1000">
                                1000
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none cash-count">
                                    0
                                </span>
                            </button>

                            <button id="clearCash" class="btn btn-danger w-100 mb-1">
                                {{ __('messages.clear') }}
                            </button>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100 flex-fill">
                            {{ __('messages.submit') }}
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>



@endsection
@push('style')
    <style>
        #total {
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(13, 202, 240, 0.5);
            /* A cyan glow effect */
            border: 2px solid #000;
        }

        .pos-wrapper {
            height: 100%;
        }

        .scroll-y {
            overflow-y: auto;
        }
    </style>
@endpush
@push('scripts')
    <script>
        const totalEl = document.getElementById('total');
        const payingEl = document.getElementById('paying-amount');

        function syncPayingWithTotal() {
            const total = parseFloat(totalEl.textContent.replace('$', '')) || 0;
            payingEl.value = total.toFixed(2);
        }
        document.getElementById('paymentModal').addEventListener('shown.bs.modal', () => {
            syncPayingWithTotal();
        });

        // Quick-cash buttons
        document.querySelectorAll('.quick-cash').forEach(btn => {
            btn.addEventListener('click', () => {
                // Use the current total from the span
                const total = parseFloat(totalEl.textContent.replace('$', '')) || 0;

                // Set the paying amount input
                payingEl.value = total.toFixed(2);
            });
        });



        let discountType = "fixed";
        let discountValue = 0;

        let cart = [];
        const $paymentBox = $('#paymentBox');

        function applyDiscount() {
            const type = document.getElementById("discountType").value;
            const val = document.getElementById("discountValue").value.trim();

            if (val === "" || isNaN(val) || Number(val) < 0) {
                alert("Please enter a valid non-negative number");
                return;
            }
            discountType = type;
            discountValue = Number(val);

            let displayText = type === "percentage" ? `${val}%` : `$${val}`;
            document.getElementById("discount").innerText = displayText;
            const modalElement = document.getElementById('discountModal');
            const modalInstance = bootstrap.Modal.getInstance(modalElement);
            modalInstance.hide();
            updateCart();
        }


        function toggleClearCartButton() {
            const cartItems = document.querySelector('#cartItems');
            const hasItems = cartItems.querySelectorAll('.cart-item').length > 0;
        }

        function filterBrand(el, brandId) {
            // Remove 'active' class from all pills
            $('.cat-pills .pill').removeClass('active');

            // Add 'active' to clicked pill
            $(el).addClass('active');

            // Update brand filter on product items
            $('.filter-brand').removeClass('active-brand'); // optional if using on cards
            $(el).addClass('active-brand'); // store active-brand on pill

            // Set data attribute for filtering (optional)
            $(el).data('brand-id', brandId);

            // Trigger the product filter
            filterProducts();
        }

        // Optional: filter products as you type in search
        $('#searchInput').on('input', filterProducts);


        function filterProducts() {
            const searchKeyword = $('#searchInput').val().toLowerCase().trim();

            // Get the selected brand from the active pill
            const selectedbrand_id = $('.cat-pills .pill.active').data('brand-id');

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

            if (visibleCount === 0) {
                $('.no-results-message').removeClass('d-none');
            } else {
                $('.no-results-message').addClass('d-none');
            }
        }


        $(document).ready(function() {
            $('.filter-brand').on('click', function(e) {
                e.preventDefault();
                const brand_id = $(this).data('brand-id');
                $('.filter-brand').removeClass('border-primary active-brand').addClass('border shadow-sm');
                $(this).addClass('border-primary active-brand').removeClass('border shadow-sm');
                filterProducts();
            });
            $('#searchInput').on('keyup', function() {
                filterProducts();
            });
            $('#clearSearch').on('click', function() {
                $('#searchInput').val('');
                filterProducts();
            });
            filterProducts();
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
                    '<div class="text-center text-muted py-10"><i class="bi bi-cart-x display-4"></i><p class="mt-2">No items in cart</p></div>'
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
                    cartItems.append(`<div class="cart-item d-flex align-items-center py-10 border-bottom">
                            <div class="cart-item-name d-flex align-items-center flex-grow-1">
                                <button class="btn btn-sm text-danger me-2" onclick="removeItem(${item.id})">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <div>
                                    <div class="fw-medium">${item.name}</div>
                                    <small class="text-muted">
                                        $${item.price.toFixed(2)} each
                                    </small>
                                </div>
                            </div>
                            <div class="cart-item-qty d-flex align-items-center justify-content-center">
                                <button class="qty-btn" onclick="updateQuantity(${item.id}, -1)">-</button>
                                <input type="number" class="qty-input" min="1" value="${item.quantity}"
                                    onchange="setQuantity(${item.id}, this.value)">
                                <button class="qty-btn" onclick="updateQuantity(${item.id}, 1)">+</button>
                            </div>
                            <div class="cart-item-price text-start fw-semibold ms-3">
                                $${(item.price * item.quantity).toFixed(2)}
                            </div>
                        </div>`);
                });

                let discountAmount = 0;

                if (discountValue > 0) {
                    if (discountType === "percentage") {
                        discountAmount = subtotal * (discountValue / 100);
                    } else { // fixed
                        discountAmount = discountValue;
                    }
                    if (discountAmount > subtotal) {
                        showAlert('Discount cannot be greater than the subtotal', 'danger');
                        $('#discount').val(0);
                        discountAmount = 0;
                        $('#checkoutBtn').prop('disabled', true).addClass('opacity-50');
                        return;
                    } else {
                        $('#checkoutBtn').prop('disabled', false).removeClass('opacity-50');
                    }
                }
                const taxableAmount = subtotal - discountAmount;
                const tax = taxableAmount * 0.0;
                const total = taxableAmount + tax;

                $('#cartCount').text(cart.length);
                $('#subtotal').text(`$${subtotal.toFixed(2)}`);
                $('#tax').text(`$${tax.toFixed(2)}`);

                if (discountValue > 0) {
                    let displayText = discountType === "percentage" ?
                        `${discountValue}%` :
                        `$${discountValue.toFixed(2)}`;
                    $('#discount').text(displayText);
                } else {
                    $('#discount').text('$0.00');
                }
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

        function printAnyModal(modalId) {
            const modal = document.getElementById(modalId);
            if (!modal) return;
            const modalClone = modal.querySelector('.modal-content').cloneNode(true);
            modalClone.querySelectorAll('button').forEach(btn => btn.remove());
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

        function confirmCancel() {
            $('#searchInput').val('');
            $('.filter-brand').removeClass('active-brand border-primary').addClass('border shadow-sm');
            $('.filter-brand[data-brand-id="all"]').addClass('active-brand border-primary').removeClass('border shadow-sm');
            filterProducts();
            cart = [];
            updateCart();
            const modal = bootstrap.Modal.getInstance(
                document.getElementById('cancelModal')
            );
            modal.hide();

            showAlert('Order cancelled successfully', 'warning');

            // location.reload();
        }


        document.addEventListener("DOMContentLoaded", function() {

            const quickButtons = document.querySelectorAll('.quick-cash');
            const receivedInput = document.getElementById('received-amount');
            const payingInput = document.getElementById('paying-amount');
            const changeInput = document.getElementById('change-amount');
            const paymentModal = document.getElementById('paymentModal');

            function calculateChange() {
                let received = parseFloat(receivedInput.value) || 0;
                let paying = parseFloat(payingInput.value) || 0;
                changeInput.value = (received - paying).toFixed(2);
            }

            function resetButtons() {
                quickButtons.forEach(btn => {
                    let badge = btn.querySelector('.cash-count');
                    badge.textContent = 0;
                    badge.classList.add('d-none');
                    btn.classList.remove('btn-info');
                    btn.classList.add('btn-primary');
                });
            }

            quickButtons.forEach(button => {

                button.addEventListener('click', function() {

                    const value = parseFloat(this.dataset.value);
                    const badge = this.querySelector('.cash-count');

                    /* ✅ CLEAR OTHER BUTTONS FIRST */
                    resetButtons();

                    /* ✅ ACTIVATE ONLY THIS BUTTON */
                    badge.textContent = 1;
                    badge.classList.remove('d-none');

                    this.classList.remove('btn-primary');
                    this.classList.add('btn-info');

                    /* ✅ REPLACE RECEIVED VALUE (NOT ADD) */
                    receivedInput.value = value.toFixed(2);

                    calculateChange();
                });

            });


            payingInput.addEventListener('input', calculateChange);


            paymentModal.addEventListener('shown.bs.modal', function() {

                receivedInput.value = "0.00";
                resetButtons();

                const defaultBtn = document.querySelector('.quick-cash[data-value="60"]');

                if (defaultBtn) {
                    defaultBtn.click();
                }
            });

        });

        function scrollPills(amount) {
            const container = document.querySelector('.cat-pills');
            container.scrollBy({
                left: amount,
                behavior: 'smooth'
            });
        }
    </script>
@endpush
