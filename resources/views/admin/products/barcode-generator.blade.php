@extends('admin.layouts.master')
@section('title', __('messages.barcode-generator'))
@section('content')
    <div class="container-fluid">
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
                <small class="text-muted fw-semibold  no-print">
                    {{ __('messages.ip_address') }}:
                </small>
                <span class="fw-semibold text-primary  no-print">
                    {{ auth()->user()->ip_address }}
                </span>
            </div>
        </div>

        <section class="section">
            <div class="row">
                <div id="alertsContainer"></div>

                <!-- Left Panel -->
                <div class="col-lg-4 no-print">
                    <!-- Search Section -->
                    <div class="bg-color p-3 border rounded mb-3">
                        <h5 class="mb-3"><i class="bi bi-search me-2"></i>Search Product</h5>
                        <div class="position-relative">
                            <input type="text" class="form-control border-dark" id="searchInput"
                                placeholder="Search by code or name..." autocomplete="off">
                            <div id="searchDropdown"
                                class="position-absolute w-100 bg-color border border-dark border-top-0 shadow"
                                style="display: none; max-height: 300px; overflow-y: auto; z-index: 1000; top: 100%;"></div>
                        </div>
                    </div>

                    <!-- Style Selection -->
                    <div class="bg-color p-3 border rounded mb-3">
                        <h5 class="mb-3"><i class="bi bi-palette me-2"></i>Label Style</h5>
                        <div class="row g-2">
                            <div class="col-4">
                                <div class="border border-2 border-secondary p-2 text-center rounded style-option parent-active"
                                    data-style="1" role="button">
                                    <i class="bi bi-card-image fs-3"></i>
                                    <div class="small mt-1">Style 1</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border border-2 border-secondary p-2 text-center rounded style-option"
                                    data-style="2" role="button">
                                    <i class="bi bi-upc fs-3"></i>
                                    <div class="small mt-1">Style 2</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border border-2 border-secondary p-2 text-center rounded style-option"
                                    data-style="3" role="button">
                                    <i class="bi bi-upc fs-3"></i>
                                    <div class="small mt-1">Style 3</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Site Name -->
                    <div class="bg-color p-3 border rounded mb-3 d-none">
                        {{-- <h5 class="mb-3"><i class="bi bi-shop me-2"></i>Store Information</h5>
                        <label class="form-label fw-bold">Site/Store Name</label> --}}
                        <input type="hidden" class="form-control border-dark" id="siteName" value="My Store"
                            placeholder="Enter store name">
                    </div>

                    <!-- Action Buttons -->
                    <div class="bg-color p-3 border rounded">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Selected: <span class="badge bg-dark" id="selectedCount">0</span></h5>
                        </div>
                        <button class="btn btn-primary w-100 mb-2 fw-semibold" id="generateLabelsBtn">
                            <i class="bi bi-arrow-repeat me-2"></i>Generate Labels
                        </button>
                        <button class="btn btn-warning w-100 fw-semibold" id="printBtn">
                            <i class="bi bi-printer me-2"></i>Print Selected
                        </button>
                    </div>
                </div>

                <!-- Right Panel -->
                <div class="col-lg-8">
                    <!-- Selected Products -->
                    <div class="bg-color p-3 border rounded mb-3 no-print">
                        <h5 class="mb-3"><i class="bi bi-check2-square me-2"></i>Selected Products</h5>
                        <div id="selectedProductList">
                            <p class="text-muted text-center">No product selected</p>
                        </div>
                    </div>

                    <!-- Preview Area -->
                    <div class="bg-color p-3 border border-2 border-dashed rounded print-area" id="previewArea">
                        <h5 class="mb-3 d-print-none">
                            <i class="bi bi-eye me-2"></i>Label Preview
                        </h5>

                        <div id="labelsContainer" class="row print-only"></div>
                    </div>


                </div>
            </div>
        </section>
    </div>
@endsection
@push('scripts')
    <script>
        const products = @json($products);
        let selectedProducts = [];
        let currentStyle = '1';
        const msgSelectProduct = @json(__('messages.please_select_product_you_want_print'));
        const msgSelectProductFrist = @json(__('messages.select_product_frist'));

        /* ===== STYLE SELECTION ===== */
        document.querySelectorAll('.style-option').forEach(option => {
            option.addEventListener('click', function () {
                document.querySelectorAll('.style-option').forEach(o => {
                    o.classList.remove('parent-active');
                    o.classList.add('border-secondary');
                });
                this.classList.add('parent-active');
                this.classList.remove('border-secondary');
                currentStyle = this.dataset.style;
            });
        });


        /* ===== SEARCH DROPDOWN ===== */
        document.getElementById('searchInput').addEventListener('input', e => {
            const term = e.target.value.trim().toLowerCase();
            const dropdown = document.getElementById('searchDropdown');

            if (!term) { dropdown.style.display = 'none'; return; }

            const results = products.filter(p =>
                p.code.toLowerCase().includes(term) ||
                p.name.toLowerCase().includes(term)
            );

            if (!results.length) {
                dropdown.innerHTML = `<div class="p-3 text-center text-muted">No products</div>`;
                dropdown.style.display = 'block';
                return;
            }

            dropdown.innerHTML = results.map(p => `
                            <div class="p-2 border-bottom suggestion-item" data-id="${p.id}" role="button">
                                <div class="fw-bold small">${p.name}</div>
                                <div class="text-muted" style="font-size:12px;">${p.code} · $${p.selling_price}</div>
                            </div>
                        `).join('');

            dropdown.style.display = 'block';

            dropdown.querySelectorAll('[data-id]').forEach(item => {
                item.addEventListener('click', function () {
                    const id = parseInt(this.dataset.id);
                    if (!selectedProducts.includes(id)) selectedProducts.push(id);
                    renderSelectedProducts();
                    document.getElementById('searchInput').value = '';
                    dropdown.style.display = 'none';
                });
            });
        });

        document.addEventListener('click', e => {
            if (!e.target.closest('#searchInput') &&
                !e.target.closest('#searchDropdown')) {
                document.getElementById('searchDropdown').style.display = 'none';
            }
        });

        /* ===== RENDER SELECTED PRODUCTS ===== */
        function renderSelectedProducts() {
            const container = document.getElementById('selectedProductList');
            const selected = products.filter(p => selectedProducts.includes(p.id));
            document.getElementById('selectedCount').innerText = selected.length;

            if (!selected.length) {
                container.innerHTML = `<p class="text-muted text-center">No product selected</p>`;
                return;
            }

            container.innerHTML = selected.map(p => `
                            <div class="border p-2 mb-2 rounded d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="fw-bold">${p.name}</div>
                                    <small class="text-muted">${p.code}</small>
                                </div>

                                <button class="btn btn-sm btn-outline-danger" onclick="removeProduct(${p.id})">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        `).join('');
        }

        function removeProduct(id) {
            selectedProducts = selectedProducts.filter(pid => pid !== id);

            renderSelectedProducts();
        }

        /* ================= GENERATE LABELS ================= */
        document.getElementById('generateLabelsBtn').addEventListener('click', () => {
            if (!selectedProducts.length) {
                const errorAlert = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    ${msgSelectProductFrist}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;
                $('#alertsContainer').html(errorAlert);
                return;
            }

            const container = document.getElementById('labelsContainer');
            container.innerHTML = '';

            const selected = products.filter(p => selectedProducts.includes(p.id));
            const siteName = document.getElementById('siteName').value || 'My Store';

            selected.forEach(p => {
                const barcodeId = `barcode-${p.id}-${Date.now()}`;
                let colClass = 'col-6 mb-3';
                let innerHtml = '';

if (currentStyle === '1') {
    colClass = 'col-12 mb-3';
    innerHtml = `
        <div class="label-box label-box-s1">
            <div class="s1-left">
                <div class="s1-name">${p.name}</div>
                <div class="s1-price">$${parseFloat(p.selling_price || 0).toFixed(2)}</div>
                <div class="s1-store">${siteName}</div>
            </div>
            <div class="s1-right">
                <svg id="${barcodeId}"></svg>
                <div class="s1-code">${p.code || ''}</div>
            </div>
        </div>`;
}

if (currentStyle === '2') {
    colClass = 'col-6 mb-3';
    innerHtml = `
        <div class="label-box label-box-s2">
            <div class="s2-top">
                <span class="s2-name">${p.name}</span>
                <span class="s2-price">$${parseFloat(p.selling_price || 0).toFixed(2)}</span>
            </div>
            <div class="s2-barcode">
                <svg id="${barcodeId}"></svg>
            </div>
            <div class="s2-bottom">
                <span class="s2-store">${siteName}</span>
                <span class="s2-code">${p.code || ''}</span>
            </div>
        </div>`;
}

if (currentStyle === '3') {
    colClass = 'col-4 mb-3';
    innerHtml = `
        <div class="label-box label-box-s3">
            <div class="s3-header">${siteName}</div>
            <div class="s3-barcode">
                <svg id="${barcodeId}"></svg>
            </div>
            <div class="s3-name">${p.name}</div>
            <div class="s3-price">$${parseFloat(p.selling_price || 0).toFixed(2)}</div>
        </div>`;
}
                const colDiv = document.createElement('div');
                colDiv.className = colClass;
                colDiv.innerHTML = innerHtml;
                container.appendChild(colDiv);

                JsBarcode(`#${barcodeId}`, p.code, {
                    format: 'CODE128',
                    width: currentStyle === '3' ? 1.4 : 2,
                    height: currentStyle === '3' ? 35 : 50,
                    displayValue: currentStyle !== '1',
                    fontSize: 11,
                    margin: 4
                });
            });
        });


        /* ===== PRINT ===== */
        document.getElementById('printBtn').addEventListener('click', () => {
            if (!selectedProducts.length) {
                const errorAlert = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    ${msgSelectProduct}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;
                $('#alertsContainer').html(errorAlert);
                return;
            }
            window.print();
        });

    </script>
@endpush
