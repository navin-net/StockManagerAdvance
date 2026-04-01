@extends('frontend-v2.app')
@section('title', 'Products')
@section('content')

    <!-- ── PAGE HERO ── -->
    <div class="page-hero">
        <div class="container-fluid px-4">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    {{-- <li class="breadcrumb-item"><a href="#">Women</a></li> --}}
                    <li class="breadcrumb-item active">All Products</li>
                </ol>
            </nav>
            <div class="page-hero-eyebrow">Curated Collection</div>
            <h1 class="page-hero-title">List <em>Products</em></h1>
            <p style="color:rgba(255,255,255,.5); font-size:.85rem; margin-top:.6rem;">
                Showing <span id="heroCount">0</span> products across all categories
            </p>
        </div>
    </div>

    <!-- ── MAIN SHOP LAYOUT ── -->
    <div class="shop-wrap">

        <!-- ── FILTER SIDEBAR ── -->
        <aside class="filter-sidebar" id="filterSidebar">

            <div class="filter-header">
                <h6>Filters</h6>
                <button class="filter-clear-all" onclick="clearAllFilters()">Clear All</button>
            </div>

            <!-- Active filter tags -->
            <div class="active-filters" id="activeFilters">
                <div class="active-filters-label">Active Filters</div>
                <div id="filterTags"></div>
            </div>

            <!-- CATEGORIES -->
            <div class="filter-section">
                <button class="filter-section-btn" onclick="toggleSection(this)">
                    Categories <i class="bi bi-chevron-down"></i>
                </button>
                <div class="filter-body" id="sec-categories">
                    {{-- Dynamically filled by buildSidebar() --}}
                </div>
            </div>

            <!-- BRANDS -->
            <div class="filter-section">
                <button class="filter-section-btn" onclick="toggleSection(this)">
                    Brand <i class="bi bi-chevron-down"></i>
                </button>
                <div class="filter-body" id="sec-brands">
                    <input type="text" class="brand-search" placeholder="Search brands…"
                        oninput="filterBrands(this.value)" />
                    <div id="brandList">
                        {{-- Dynamically filled by buildSidebar() --}}
                    </div>
                </div>
            </div>

            <!-- PRICE RANGE -->
            <div class="filter-section">
                <button class="filter-section-btn" onclick="toggleSection(this)">
                    Price Range <i class="bi bi-chevron-down"></i>
                </button>
                <div class="filter-body" id="sec-price">
                    <div class="price-range-wrap">
                        <div class="price-inputs">
                            <input type="number" class="price-input" id="priceMin" value="0" min="0" max="5000"
                                oninput="syncPrice('min')" />
                            <span class="price-sep">—</span>
                            <input type="number" class="price-input" id="priceMax" value="5000" min="0" max="5000"
                                oninput="syncPrice('max')" />
                        </div>
                        <div class="range-track">
                            <div class="range-fill" id="rangeFill"></div>
                            <div class="range-slider-wrap">
                                <input type="range" class="range-slider" id="sliderMin" min="0" max="5000" value="0"
                                    oninput="syncSlider('min')" />
                                <input type="range" class="range-slider" id="sliderMax" min="0" max="5000" value="5000"
                                    oninput="syncSlider('max')" />
                            </div>
                        </div>
                        <div style="display:flex;justify-content:space-between;margin-top:6px;">
                            <span style="font-size:.7rem;color:var(--muted);">$0</span>
                            <span style="font-size:.7rem;color:var(--muted);">$5,000</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MINIMUM RATING -->
            <div class="filter-section">
                <button class="filter-section-btn" onclick="toggleSection(this)">
                    Rating <i class="bi bi-chevron-down"></i>
                </button>
                <div class="filter-body" id="sec-rating">
                    <div id="starFilters">
                        <div class="star-filter-item" onclick="setMinRating(5)" data-min="5">
                            <div class="star-icons">
                                <i class="bi bi-star-fill on"></i><i class="bi bi-star-fill on"></i>
                                <i class="bi bi-star-fill on"></i><i class="bi bi-star-fill on"></i>
                                <i class="bi bi-star-fill on"></i>
                            </div>
                            <div class="star-bar">
                                <div class="star-bar-inner" style="width:35%"></div>
                            </div>
                            <span class="star-pct">35%</span>
                        </div>
                        <div class="star-filter-item" onclick="setMinRating(4)" data-min="4">
                            <div class="star-icons">
                                <i class="bi bi-star-fill on"></i><i class="bi bi-star-fill on"></i>
                                <i class="bi bi-star-fill on"></i><i class="bi bi-star-fill on"></i>
                                <i class="bi bi-star"></i>
                            </div>
                            <div class="star-bar">
                                <div class="star-bar-inner" style="width:62%"></div>
                            </div>
                            <span class="star-pct">62%</span>
                        </div>
                        <div class="star-filter-item" onclick="setMinRating(3)" data-min="3">
                            <div class="star-icons">
                                <i class="bi bi-star-fill on"></i><i class="bi bi-star-fill on"></i>
                                <i class="bi bi-star-fill on"></i><i class="bi bi-star"></i>
                                <i class="bi bi-star"></i>
                            </div>
                            <div class="star-bar">
                                <div class="star-bar-inner" style="width:80%"></div>
                            </div>
                            <span class="star-pct">80%</span>
                        </div>
                        <div class="star-filter-item" onclick="setMinRating(2)" data-min="2">
                            <div class="star-icons">
                                <i class="bi bi-star-fill on"></i><i class="bi bi-star-fill on"></i>
                                <i class="bi bi-star"></i><i class="bi bi-star"></i>
                                <i class="bi bi-star"></i>
                            </div>
                            <div class="star-bar">
                                <div class="star-bar-inner" style="width:90%"></div>
                            </div>
                            <span class="star-pct">90%</span>
                        </div>
                    </div>
                    <div class="rating-min-label" id="ratingLabel" style="display:none">
                        Showing <span id="ratingMinText"></span>★ & above
                    </div>
                </div>
            </div>

        </aside>

        <!-- ── PRODUCTS AREA ── -->
        <div class="products-area">

            <!-- Sort bar -->
            <div class="sort-bar">
                <div class="sort-bar-left">
                    <button class="mobile-filter-btn" onclick="openFilterModal()">
                        <i class="bi bi-sliders"></i> Filters
                        <span class="filter-pill" id="filterCount" style="display:none"></span>
                    </button>
                    <span class="result-count"><strong id="resultCount">0</strong> products</span>
                </div>
                <div class="d-flex align-items-center gap-3 sort-bar-right">
                    <select class="sort-select" onchange="sortProducts(this.value)">
                        <option value="featured">Featured</option>
                        <option value="newest">Newest First</option>
                        <option value="price-asc">Price: Low to High</option>
                        <option value="price-desc">Price: High to Low</option>
                        <option value="rating">Top Rated</option>
                        <option value="bestselling">Best Selling</option>
                    </select>
                    <div class="view-toggle">
                        <a href="#" class="active" id="gridViewBtn" onclick="setView('grid', event)" title="Grid view">
                            <i class="bi bi-grid-3x3-gap"></i>
                        </a>
                        <a href="#" id="listViewBtn" onclick="setView('list', event)" title="List view">
                            <i class="bi bi-list-ul"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="products-grid" id="productsGrid">
                <div class="row g-3" id="productRows"></div>
                <div class="no-results" id="noResults" style="display:none">
                    <i class="bi bi-search"></i>
                    <h5>No products match your filters</h5>
                    <p>Try adjusting or clearing your active filters</p>
                    <button onclick="clearAllFilters()"
                        style="margin-top:12px;padding:9px 24px;background:var(--dark);color:var(--white);border:none;font-size:.75rem;letter-spacing:.08em;text-transform:uppercase;cursor:pointer;border-radius:2px;">
                        Clear All Filters
                    </button>
                </div>
            </div>

            <!-- Pagination -->
            <div class="pagination-wrap" id="paginationWrap" style="display:none">
                <span class="page-info" id="pageInfo"></span>
                <div id="paginationLinks"></div>
            </div>

        </div>
    </div>

    <!-- ── FILTER MODAL (mobile) ── -->
    <div class="filter-modal-backdrop" id="filterModalBackdrop" onclick="closeFilterModal()"></div>
    <div class="filter-modal" id="filterModal">
        <div class="filter-modal-handle"></div>
        <div class="filter-modal-header">
            <h6><i class="bi bi-sliders me-2" style="color:var(--accent);font-size:1rem;"></i>Filter Products</h6>
            <button class="filter-modal-close" onclick="closeFilterModal()"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="filter-modal-body" id="filterModalBody">
            <!-- Content cloned from sidebar on open -->
        </div>
        <div class="filter-modal-footer">
            <button class="btn-modal-clear" onclick="clearAllFilters(); closeFilterModal()">Clear All</button>
            <button class="btn-modal-apply" onclick="closeFilterModal()">
                Show <span id="modalResultCount">0</span> Results
            </button>
        </div>
    </div>
    <!-- Quick View Modal -->
    <div class="modal fade" id="quickViewModal" tabindex="-1" aria-labelledby="quickViewLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="quickViewLabel">Quick View</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <!-- Image Column -->
                        <div class="col-md-6 text-center">
                            <img id="modal-image" src="" class="img-fluid rounded" alt="Product Image"
                                style="max-height: 400px; object-fit: contain;">
                        </div>

                        <!-- Details Column -->
                        <div class="col-md-6">
                            <h4 id="modal-name" class="mb-3"></h4>

                            <div id="modal-stars" class="product-stars mb-3"></div>

                            <div class="modal-price mb-4">
                                <span id="modal-price" class="h4 fw-bold text-primary"></span>
                                <span id="modal-oldprice" class="text-muted text-decoration-line-through ms-3"></span>
                            </div>

                            <div id="modal-desc" class="mb-4"></div>

                            <button onclick="addToCartFromModal()" class="btn btn-primary w-100 py-3">
                                <i class="bi bi-bag-plus"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection

@push('scripts')
    <script>
        // ── GLOBALS ─────────────────────────────────────────────────────────────
        let PRODUCTS = [];
        let SERVER_CATEGORIES = [];
        let SERVER_BRANDS = [];

        // ── STATE ────────────────────────────────────────────────────────────────
        let state = {
            categories: new Set(), // stores category IDs
            subcategories: new Set(), // stores subcategory IDs
            brands: new Set(), // stores brand IDs
            priceMin: 0,
            priceMax: 5000,
            minRating: 0,
            sort: 'featured',
            view: 'grid',
            page: 1,
            perPage: 12,
        };

        // ── LOAD DATA ────────────────────────────────────────────────────────────
        async function loadAll() {

            const baseUrl = "{{ url('/api') }}";


            try {
                // Fire all three requests in parallel
                const [productsRes, categoriesRes, brandsRes] = await Promise.all([
                    fetch(`${baseUrl}/getProducts`),      // ✅ use backticks, not quotes
                    fetch(`${baseUrl}/getCategories`),
                    fetch(`${baseUrl}/getBrands`),
                ]);

                const productsData = await productsRes.json();
                const categoriesData = await categoriesRes.json();
                const brandsData = await brandsRes.json();

                // Map products
                PRODUCTS = productsData.map(p => ({
                    id: p.id,
                    code: p.code,
                    name: p.name,
                    brand: p.brand?.name ?? p.brand_id,
                    brand_id: p.brand_id,
                    cat: p.category_id,
                    sub: p.subcategory_id,
                    price: parseFloat(p.selling_price),
                    oldPrice: parseFloat(p.cost_price),
                    img: p.image ?
                        (p.image.startsWith('storage/') || p.image.startsWith('/storage/') ?
                            p.image : `/storage/${p.image.replace(/^\/+/, '')}`) : '/noimage.png',
                    desc: p.description ?? '',
                    stock: p.stock_quantity,
                    rating: parseFloat(p.rating ?? 0),
                    reviews: parseInt(p.reviews ?? 0),
                    badge: p.expiry_date && new Date(p.expiry_date) < new Date() ? 'expired' : 'new',
                }));

                // Map categories (expects subcategories relation)
                SERVER_CATEGORIES = categoriesData.map(c => ({
                    id: c.id,
                    name: c.name,
                    subcategories: (c.subcategories ?? []).map(s => ({
                        id: s.id,
                        name: s.name,
                    })),
                }));

                // Map brands
                SERVER_BRANDS = brandsData.map(b => ({
                    id: b.id,
                    name: b.name,
                }));

                buildSidebar();
                renderProducts();

            } catch (err) {
                console.error('Error loading data:', err);
            }
        }

        // ── BUILD SIDEBAR ────────────────────────────────────────────────────────
        function buildSidebar() {
            // Categories
            const catContainer = document.getElementById('sec-categories');
            catContainer.innerHTML = SERVER_CATEGORIES.map(cat => `
                            <div class="cat-tree-item mt-1">
                                <div class="cat-parent" onclick="toggleCategory(this, ${cat.id})" data-cat="${cat.id}">
                                    <div class="cat-checkbox"></div>
                                    <span>${cat.name}</span>
                                </div>
                                <div class="sub-tree">
                                    ${cat.subcategories.map(sub => `
                                            <div class="sub-item" onclick="toggleSub(this, ${cat.id}, ${sub.id})" data-sub="${sub.id}">
                                                <div class="sub-dot"></div>
                                                <span>${sub.name}</span>
                                            </div>
                                        `).join('')}
                                </div>
                            </div>
                        `).join('');

            // Brands
            const brandList = document.getElementById('brandList');
            brandList.innerHTML = SERVER_BRANDS.map(b => `
                            <div class="brand-check-item" onclick="toggleBrand(this, ${b.id})" data-brand="${b.name}">
                                <div class="brand-check-box"></div>
                                <span>${b.name}</span>
                            </div>
                        `).join('');
        }

        // ── RENDER PRODUCTS ──────────────────────────────────────────────────────
        function renderProducts() {
            let filtered = PRODUCTS.filter(p => {
                if (state.subcategories.size > 0 && !state.subcategories.has(p.sub)) return false;
                else if (state.subcategories.size === 0 && state.categories.size > 0 && !state.categories.has(p
                    .cat)) return false;
                if (state.brands.size > 0 && !state.brands.has(p.brand_id)) return false;
                if (p.price < state.priceMin || p.price > state.priceMax) return false;
                if (p.rating < state.minRating) return false;
                return true;
            });

            // Sort
            if (state.sort === 'price-asc') filtered.sort((a, b) => a.price - b.price);
            else if (state.sort === 'price-desc') filtered.sort((a, b) => b.price - a.price);
            else if (state.sort === 'rating') filtered.sort((a, b) => b.rating - a.rating);
            else if (state.sort === 'newest') filtered.sort((a, b) => b.id - a.id);
            else if (state.sort === 'bestselling') filtered.sort((a, b) => b.reviews - a.reviews);

            const total = filtered.length;
            const totalPages = Math.ceil(total / state.perPage);

            // Clamp page
            if (state.page > totalPages) state.page = 1;

            const start = (state.page - 1) * state.perPage;
            const end = start + state.perPage;
            const paginated = filtered.slice(start, end);

            const grid = document.getElementById('productRows');
            const noRes = document.getElementById('noResults');
            const colClass = state.view === 'grid' ? 'col-6 col-md-4 col-lg-3' : 'col-12';

            document.getElementById('resultCount').textContent = total;
            document.getElementById('heroCount').textContent = total;
            document.getElementById('pageInfo').textContent =
                total === 0 ? '' : `Showing ${start + 1}–${Math.min(end, total)} of ${total} products`;

            if (total === 0) {
                grid.innerHTML = '';
                noRes.style.display = 'block';
                document.getElementById('paginationWrap').style.display = 'none';
                return;
            }

            noRes.style.display = 'none';
            document.getElementById('paginationWrap').style.display = 'flex';

            grid.innerHTML = paginated.map((p, i) => {

                const stars = renderStars(p.rating);
                const badgeHtml = p.badge ?
                    `<span class="product-badge badge-${p.badge}">${p.badge}</span>` :
                    '';
                const priceHtml = p.oldPrice && p.oldPrice > p.price ?
                    `<del>$${p.oldPrice.toLocaleString()}</del> <span class="sale-price">$${p.price.toLocaleString()}</span>` :
                    `$${p.price.toLocaleString()}`;

                return `
                                <div class="${colClass} col-product" data-id="${p.id}"
                                     style="animation: fadeInUp .4s ease ${i * 0.04}s both">
                                    <div class="product-card">
                                        <div class="product-img-wrap">

                                        <img src="${p.img}" alt="${p.name}"
                                            loading="${i < 6 ? 'eager' : 'lazy'}"
                                            onerror="this.onerror=null;this.src='/noimage.png'" />
                                            ${badgeHtml}
                                            <div class="product-actions">
                                                <button class="action-btn" title="Wishlist" onclick="toggleWishlist(this)">
                                                    <i class="bi bi-heart"></i>
                                                </button>
                                                <button class="action-btn quick-view-btn" title="Quick View" data-id="${p.id}"
                                                    data-name="${p.name.replace(/"/g, '&quot;').replace(/'/g, '&#39;')}"
                                                    data-img="${p.img || '/noimage.png'}"
                                                    data-price="${p.price}"
                                                    data-oldprice="${p.oldPrice || ''}"
                                                    data-rating="${p.rating || 0}"
                                                    data-reviews="${p.reviews || 0}"
                                                    data-desc="${(p.desc || '').replace(/"/g, '&quot;').replace(/'/g, '&#39;')}">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                <button class="action-btn view-detail-btn" title="View Detail" data-code="${p.code}">
                                                    <i class="bi bi-box-arrow-up-right"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <div class="product-name">${p.name}</div>
                                            <div class="product-stars">
                                                <div class="stars">${stars}</div>
                                                <span class="rating-count">(${p.reviews})</span>
                                            </div>
                                            <div class="product-price">${priceHtml}</div>
                                            <button class="btn-cart" onclick="addToCart(this)">
                                                <i class="bi bi-bag-plus"></i> Add to Cart
                                            </button>
                                        </div>
                                    </div>
                                </div>`;
            }).join('');

            renderPagination(totalPages);
        }

        // ── PAGINATION ───────────────────────────────────────────────────────────
        function renderPagination(totalPages) {
            const wrap = document.getElementById('paginationLinks');
            if (totalPages <= 1) {
                wrap.innerHTML = '';
                return;
            }

            const current = state.page;
            const pages = new Set([1, totalPages]);
            for (let i = Math.max(2, current - 2); i <= Math.min(totalPages - 1, current + 2); i++) {
                pages.add(i);
            }
            const sorted = [...pages].sort((a, b) => a - b);

            let html = '';

            html += `<a href="#" class="page-link-custom ${current === 1 ? 'disabled' : ''}"
                                    onclick="goToPage(${current - 1}, event)">
                                    <i class="bi bi-chevron-left"></i>
                                 </a>`;

            let prev = 0;
            for (const p of sorted) {
                if (p - prev > 1) {
                    html += `<span class="page-link-custom disabled" style="pointer-events:none">…</span>`;
                }
                html += `<a href="#" class="page-link-custom ${p === current ? 'active' : ''}"
                                        onclick="goToPage(${p}, event)">${p}</a>`;
                prev = p;
            }

            html += `<a href="#" class="page-link-custom ${current === totalPages ? 'disabled' : ''}"
                                    onclick="goToPage(${current + 1}, event)">
                                    <i class="bi bi-chevron-right"></i>
                                 </a>`;

            wrap.innerHTML = html;
        }

        function goToPage(page, e) {
            e.preventDefault();
            const totalPages = Math.ceil(getFilteredCount() / state.perPage);
            if (page < 1 || page > totalPages) return;
            state.page = page;
            renderProducts();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // ── STARS ────────────────────────────────────────────────────────────────
        function renderStars(rating) {
            let html = '';
            for (let i = 1; i <= 5; i++) {
                if (rating >= i) html += '<i class="bi bi-star-fill on"></i>';
                else if (rating >= i - 0.5) html += '<i class="bi bi-star-half on"></i>';
                else html += '<i class="bi bi-star"></i>';
            }
            return html;
        }

        // ── FILTER TAGS ──────────────────────────────────────────────────────────
        function updateFilterTags() {
            const tags = [];

            // Use names for display — look up from SERVER data
            state.subcategories.forEach(id => {
                SERVER_CATEGORIES.forEach(cat => {
                    const sub = cat.subcategories.find(s => s.id === id);
                    if (sub) tags.push({
                        label: sub.name,
                        type: 'sub',
                        value: id
                    });
                });
            });
            if (state.subcategories.size === 0) {
                state.categories.forEach(id => {
                    const cat = SERVER_CATEGORIES.find(c => c.id === id);
                    if (cat) tags.push({
                        label: cat.name,
                        type: 'cat',
                        value: id
                    });
                });
            }
            state.brands.forEach(id => {
                const brand = SERVER_BRANDS.find(b => b.id === id);
                if (brand) tags.push({
                    label: brand.name,
                    type: 'brand',
                    value: id
                });
            });
            if (state.priceMin > 0 || state.priceMax < 5000)
                tags.push({
                    label: `$${state.priceMin}–$${state.priceMax}`,
                    type: 'price',
                    value: 'price'
                });
            if (state.minRating > 0)
                tags.push({
                    label: `${state.minRating}★+`,
                    type: 'rating',
                    value: 'rating'
                });

            const wrap = document.getElementById('activeFilters');
            const tagsEl = document.getElementById('filterTags');
            const fc = document.getElementById('filterCount');

            if (tags.length === 0) {
                wrap.classList.remove('has-filters');
                if (fc) fc.style.display = 'none';
                return;
            }
            wrap.classList.add('has-filters');
            if (fc) {
                fc.textContent = tags.length;
                fc.style.display = 'inline-flex';
            }
            tagsEl.innerHTML = tags.map(t =>
                `<span class="filter-tag" onclick="removeTag('${t.type}', ${JSON.stringify(t.value)})">
                                ${t.label} <i class="bi bi-x"></i>
                             </span>`
            ).join('');
        }

        function removeTag(type, value) {
            if (type === 'cat') {
                state.categories.delete(value);
                document.querySelectorAll(`.cat-parent[data-cat="${value}"]`).forEach(el => {
                    el.classList.remove('checked');
                    el.nextElementSibling.classList.remove('open');
                });
            } else if (type === 'sub') {
                state.subcategories.delete(value);
                document.querySelectorAll(`.sub-item[data-sub="${value}"]`).forEach(el => el.classList.remove('checked'));
            } else if (type === 'brand') {
                state.brands.delete(value);
                document.querySelectorAll(`.brand-check-item`).forEach(el => {
                    const brand = SERVER_BRANDS.find(b => b.id === value);
                    if (brand && el.dataset.brand === brand.name) el.classList.remove('checked');
                });
            } else if (type === 'price') {
                state.priceMin = 0;
                state.priceMax = 5000;
                document.getElementById('priceMin').value = 0;
                document.getElementById('priceMax').value = 5000;
                document.getElementById('sliderMin').value = 0;
                document.getElementById('sliderMax').value = 5000;
                updateRangeFill();
            } else if (type === 'rating') {
                state.minRating = 0;
                document.querySelectorAll('.star-filter-item').forEach(el => el.classList.remove('active'));
                document.getElementById('ratingLabel').style.display = 'none';
            }
            state.page = 1;
            updateFilterTags();
            renderProducts();
        }

        // ── CATEGORY TOGGLE ──────────────────────────────────────────────────────
        function toggleCategory(el, cat) {
            cat = parseInt(cat);
            el.classList.toggle('checked');
            const subTree = el.nextElementSibling;
            const isChecked = el.classList.contains('checked');
            if (isChecked) {
                state.categories.add(cat);
                subTree.classList.add('open');
            } else {
                state.categories.delete(cat);
                subTree.classList.remove('open');
                subTree.querySelectorAll('.sub-item').forEach(si => {
                    si.classList.remove('checked');
                    state.subcategories.delete(parseInt(si.dataset.sub));
                });
            }
            state.page = 1;
            updateFilterTags();
            renderProducts();
        }

        function toggleSub(el, cat, sub) {
            cat = parseInt(cat);
            sub = parseInt(sub);
            el.classList.toggle('checked');
            if (el.classList.contains('checked')) {
                state.subcategories.add(sub);
                state.categories.add(cat);
                const parentEl = el.closest('.sub-tree').previousElementSibling;
                parentEl.classList.add('checked');
            } else {
                state.subcategories.delete(sub);
            }
            state.page = 1;
            updateFilterTags();
            renderProducts();
        }

        // ── BRAND TOGGLE ─────────────────────────────────────────────────────────
        function toggleBrand(el, brand) {
            brand = parseInt(brand);
            el.classList.toggle('checked');
            if (el.classList.contains('checked')) state.brands.add(brand);
            else state.brands.delete(brand);
            state.page = 1;
            updateFilterTags();
            renderProducts();
        }

        function filterBrands(query) {
            document.querySelectorAll('#brandList .brand-check-item').forEach(el => {
                el.style.display = el.dataset.brand.toLowerCase().includes(query.toLowerCase()) ? '' : 'none';
            });
        }

        // ── PRICE RANGE ──────────────────────────────────────────────────────────
        function syncSlider(which) {
            const min = parseInt(document.getElementById('sliderMin').value);
            const max = parseInt(document.getElementById('sliderMax').value);
            if (which === 'min' && min > max - 50) {
                document.getElementById('sliderMin').value = max - 50;
                return;
            }
            if (which === 'max' && max < min + 50) {
                document.getElementById('sliderMax').value = min + 50;
                return;
            }
            document.getElementById('priceMin').value = min;
            document.getElementById('priceMax').value = max;
            state.priceMin = min;
            state.priceMax = max;
            state.page = 1;
            updateRangeFill();
            updateFilterTags();
            renderProducts();
        }

        function syncPrice(which) {
            let min = parseInt(document.getElementById('priceMin').value) || 0;
            let max = parseInt(document.getElementById('priceMax').value) || 5000;
            min = Math.max(0, Math.min(min, 4950));
            max = Math.max(50, Math.min(max, 5000));
            if (which === 'min' && min > max - 50) min = max - 50;
            if (which === 'max' && max < min + 50) max = min + 50;
            document.getElementById('sliderMin').value = min;
            document.getElementById('sliderMax').value = max;
            state.priceMin = min;
            state.priceMax = max;
            state.page = 1;
            updateRangeFill();
            updateFilterTags();
            renderProducts();
        }

        function updateRangeFill() {
            const min = parseInt(document.getElementById('sliderMin').value);
            const max = parseInt(document.getElementById('sliderMax').value);
            const fill = document.getElementById('rangeFill');
            if (!fill) return;
            fill.style.left = (min / 5000 * 100) + '%';
            fill.style.width = ((max - min) / 5000 * 100) + '%';
        }

        // ── RATING ───────────────────────────────────────────────────────────────
        function setMinRating(min) {
            if (state.minRating === min) {
                state.minRating = 0;
                document.querySelectorAll('.star-filter-item').forEach(el => el.classList.remove('active'));
                document.getElementById('ratingLabel').style.display = 'none';
            } else {
                state.minRating = min;
                document.querySelectorAll('.star-filter-item').forEach(el => {
                    el.classList.toggle('active', parseInt(el.dataset.min) <= min);
                });
                document.getElementById('ratingMinText').textContent = min;
                document.getElementById('ratingLabel').style.display = 'block';
            }
            state.page = 1;
            updateFilterTags();
            renderProducts();
        }

        // ── CLEAR ALL ────────────────────────────────────────────────────────────
        function clearAllFilters() {
            state.categories.clear();
            state.subcategories.clear();
            state.brands.clear();
            state.priceMin = 0;
            state.priceMax = 5000;
            state.minRating = 0;
            state.page = 1;
            document.querySelectorAll('.cat-parent').forEach(el => el.classList.remove('checked'));
            document.querySelectorAll('.sub-tree').forEach(el => el.classList.remove('open'));
            document.querySelectorAll('.sub-item').forEach(el => el.classList.remove('checked'));
            document.querySelectorAll('.brand-check-item').forEach(el => el.classList.remove('checked'));
            document.getElementById('priceMin').value = 0;
            document.getElementById('priceMax').value = 5000;
            document.getElementById('sliderMin').value = 0;
            document.getElementById('sliderMax').value = 5000;
            document.querySelectorAll('.star-filter-item').forEach(el => el.classList.remove('active'));
            document.getElementById('ratingLabel').style.display = 'none';
            updateRangeFill();
            updateFilterTags();
            renderProducts();
        }

        // ── SECTION TOGGLE ───────────────────────────────────────────────────────
        function toggleSection(btn) {
            const body = btn.nextElementSibling;
            btn.classList.toggle('collapsed');
            body.style.display = btn.classList.contains('collapsed') ? 'none' : 'block';
        }

        // ── SORT ─────────────────────────────────────────────────────────────────
        function sortProducts(val) {
            state.sort = val;
            state.page = 1;
            renderProducts();
        }

        // ── VIEW ─────────────────────────────────────────────────────────────────
        function setView(view, e) {
            e.preventDefault();
            state.view = view;
            const grid = document.getElementById('productsGrid');
            if (view === 'list') grid.classList.add('list-view');
            else grid.classList.remove('list-view');
            document.getElementById('gridViewBtn').classList.toggle('active', view === 'grid');
            document.getElementById('listViewBtn').classList.toggle('active', view === 'list');
            renderProducts();
        }

        // ── CART / WISHLIST ──────────────────────────────────────────────────────
        function addToCart(btn) {
            const orig = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check-circle"></i> Added!';
            btn.classList.add('added');
            setTimeout(() => {
                btn.innerHTML = orig;
                btn.classList.remove('added');
            }, 1800);
        }

        function toggleWishlist(btn) {
            btn.classList.toggle('active');
            btn.innerHTML = btn.classList.contains('active') ?
                '<i class="bi bi-heart-fill"></i>' :
                '<i class="bi bi-heart"></i>';
        }

        // ── FILTER COUNT (shared) ────────────────────────────────────────────────
        function getFilteredCount() {
            return PRODUCTS.filter(p => {
                if (state.subcategories.size > 0 && !state.subcategories.has(p.sub)) return false;
                else if (state.subcategories.size === 0 && state.categories.size > 0 && !state.categories.has(p
                    .cat)) return false;
                if (state.brands.size > 0 && !state.brands.has(p.brand_id)) return false;
                if (p.price < state.priceMin || p.price > state.priceMax) return false;
                if (p.rating < state.minRating) return false;
                return true;
            }).length;
        }

        // ── FILTER MODAL (mobile) ────────────────────────────────────────────────
        function openFilterModal() {
            const sidebar = document.querySelector('.filter-sidebar');
            const modalBody = document.getElementById('filterModalBody');
            modalBody.innerHTML = sidebar.innerHTML;
            syncModalState(modalBody);
            document.getElementById('filterModal').classList.add('open');
            document.getElementById('filterModalBackdrop').classList.add('show');
            document.body.style.overflow = 'hidden';
            updateModalCount();
        }

        function syncModalState(modalBody) {
            modalBody.querySelectorAll('.cat-parent').forEach(el => {
                const id = parseInt(el.dataset.cat);
                if (state.categories.has(id)) {
                    el.classList.add('checked');
                    const subTree = el.nextElementSibling;
                    if (subTree) subTree.classList.add('open');
                }
            });
            modalBody.querySelectorAll('.sub-item').forEach(el => {
                if (state.subcategories.has(parseInt(el.dataset.sub))) el.classList.add('checked');
            });
            modalBody.querySelectorAll('.brand-check-item').forEach(el => {
                const brand = SERVER_BRANDS.find(b => b.name === el.dataset.brand);
                if (brand && state.brands.has(brand.id)) el.classList.add('checked');
            });

            const pMin = modalBody.querySelector('#priceMin');
            const pMax = modalBody.querySelector('#priceMax');
            const sMin = modalBody.querySelector('#sliderMin');
            const sMax = modalBody.querySelector('#sliderMax');
            if (pMin) pMin.value = state.priceMin;
            if (pMax) pMax.value = state.priceMax;
            if (sMin) sMin.value = state.priceMin;
            if (sMax) sMax.value = state.priceMax;
            updateModalRangeFill(modalBody);

            modalBody.querySelectorAll('.star-filter-item').forEach(el => {
                if (state.minRating > 0 && parseInt(el.dataset.min) <= state.minRating)
                    el.classList.add('active');
            });
            if (state.minRating > 0) {
                const lbl = modalBody.querySelector('#ratingLabel');
                const txt = modalBody.querySelector('#ratingMinText');
                if (lbl) lbl.style.display = 'block';
                if (txt) txt.textContent = state.minRating;
            }

            // Wire up events inside modal
            modalBody.querySelectorAll('.cat-parent').forEach(el => {
                el.onclick = () => {
                    toggleCategory(el, el.dataset.cat);
                    syncModalState(modalBody);
                    updateModalCount();
                };
            });
            modalBody.querySelectorAll('.sub-item').forEach(el => {
                el.onclick = () => {
                    const catId = parseInt(el.closest('.sub-tree').previousElementSibling.dataset.cat);
                    toggleSub(el, catId, el.dataset.sub);
                    syncModalState(modalBody);
                    updateModalCount();
                };
            });
            modalBody.querySelectorAll('.brand-check-item').forEach(el => {
                const brand = SERVER_BRANDS.find(b => b.name === el.dataset.brand);
                if (brand) el.onclick = () => {
                    toggleBrand(el, brand.id);
                    syncModalState(modalBody);
                    updateModalCount();
                };
            });
            modalBody.querySelectorAll('.star-filter-item').forEach(el => {
                el.onclick = () => {
                    setMinRating(parseInt(el.dataset.min));
                    syncModalState(modalBody);
                    updateModalCount();
                };
            });
            if (sMin) sMin.oninput = () => {
                syncModalSlider('min', modalBody);
                updateModalCount();
            };
            if (sMax) sMax.oninput = () => {
                syncModalSlider('max', modalBody);
                updateModalCount();
            };
            if (pMin) pMin.oninput = () => {
                syncModalPrice('min', modalBody);
                updateModalCount();
            };
            if (pMax) pMax.oninput = () => {
                syncModalPrice('max', modalBody);
                updateModalCount();
            };

            const bSearch = modalBody.querySelector('.brand-search');
            if (bSearch) bSearch.oninput = (e) => {
                modalBody.querySelectorAll('.brand-check-item').forEach(el => {
                    el.style.display = el.dataset.brand.toLowerCase().includes(e.target.value.toLowerCase()) ?
                        '' : 'none';
                });
            };
            modalBody.querySelectorAll('.filter-section-btn').forEach(btn => {
                btn.onclick = () => toggleSection(btn);
            });
            const clearBtn = modalBody.querySelector('.filter-clear-all');
            if (clearBtn) clearBtn.onclick = () => {
                clearAllFilters();
                syncModalState(modalBody);
                updateModalCount();
            };
        }

        function syncModalSlider(which, modalBody) {
            const min = parseInt(modalBody.querySelector('#sliderMin').value);
            const max = parseInt(modalBody.querySelector('#sliderMax').value);
            if (which === 'min' && min > max - 50) {
                modalBody.querySelector('#sliderMin').value = max - 50;
                return;
            }
            if (which === 'max' && max < min + 50) {
                modalBody.querySelector('#sliderMax').value = min + 50;
                return;
            }
            if (which === 'min') modalBody.querySelector('#priceMin').value = min;
            if (which === 'max') modalBody.querySelector('#priceMax').value = max;
            state.priceMin = parseInt(modalBody.querySelector('#sliderMin').value);
            state.priceMax = parseInt(modalBody.querySelector('#sliderMax').value);
            state.page = 1;
            updateModalRangeFill(modalBody);
            updateFilterTags();
            renderProducts();
        }

        function syncModalPrice(which, modalBody) {
            let min = parseInt(modalBody.querySelector('#priceMin').value) || 0;
            let max = parseInt(modalBody.querySelector('#priceMax').value) || 5000;
            state.priceMin = Math.max(0, Math.min(min, 4950));
            state.priceMax = Math.max(50, Math.min(max, 5000));
            modalBody.querySelector('#sliderMin').value = state.priceMin;
            modalBody.querySelector('#sliderMax').value = state.priceMax;
            state.page = 1;
            updateModalRangeFill(modalBody);
            updateFilterTags();
            renderProducts();
        }

        function updateModalRangeFill(modalBody) {
            const fill = modalBody.querySelector('#rangeFill');
            if (!fill) return;
            fill.style.left = (state.priceMin / 5000 * 100) + '%';
            fill.style.width = ((state.priceMax - state.priceMin) / 5000 * 100) + '%';
        }

        function updateModalCount() {
            const el = document.getElementById('modalResultCount');
            if (el) el.textContent = getFilteredCount();
        }

        function closeFilterModal() {
            document.getElementById('filterModal').classList.remove('open');
            document.getElementById('filterModalBackdrop').classList.remove('show');
            document.body.style.overflow = '';
        }

        // ── INIT ─────────────────────────────────────────────────────────────────
        const style = document.createElement('style');
        style.textContent =
            '@keyframes fadeInUp { from { opacity:0; transform:translateY(16px);} to { opacity:1; transform:translateY(0);} }';
        document.head.appendChild(style);

        document.addEventListener('DOMContentLoaded', () => {
            updateRangeFill();
            loadAll().then(() => {
                const categoryId = sessionStorage.getItem('filter_category_id');
                if (categoryId) {
                    sessionStorage.removeItem('filter_category_id'); // clear immediately
                    const catId = parseInt(categoryId);
                    state.categories.add(catId);
                    const catEl = document.querySelector(`.cat-parent[data-cat="${catId}"]`);
                    if (catEl) {
                        catEl.classList.add('checked');
                        catEl.nextElementSibling.classList.add('open');
                    }
                    updateFilterTags();
                    renderProducts();
                }
            });
        });


        // Quick View Modal Handler - Use event delegation
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.quick-view-btn');
            if (!btn) return;

            // Get data safely
            const name = btn.dataset.name || 'No Name';
            const img = btn.dataset.img || '/noimage.png';
            const price = parseFloat(btn.dataset.price) || 0;
            const oldPrice = btn.dataset.oldprice ? parseFloat(btn.dataset.oldprice) : null;
            const rating = parseFloat(btn.dataset.rating) || 0;
            const reviews = btn.dataset.reviews || 0;
            const desc = btn.dataset.desc || 'No description available.';

            // Update modal elements
            document.getElementById('modal-image').src = img;
            document.getElementById('modal-image').alt = name;

            document.getElementById('modal-name').textContent = name;
            document.getElementById('modal-desc').textContent = desc;
            document.getElementById('modal-price').textContent = '$' + price.toFixed(2);

            // Handle old price
            const oldPriceEl = document.getElementById('modal-oldprice');
            if (oldPrice && oldPrice > price) {
                oldPriceEl.textContent = '$' + oldPrice.toFixed(2);
                oldPriceEl.style.display = 'inline';
            } else {
                oldPriceEl.style.display = 'none';
            }

            // Stars
            let starsHtml = '';
            const fullStars = Math.round(rating);
            for (let i = 1; i <= 5; i++) {
                starsHtml +=
                    `<i class="bi ${i <= fullStars ? 'bi-star-fill text-warning' : 'bi-star text-muted'}"></i>`;
            }
            document.getElementById('modal-stars').innerHTML = starsHtml +
                ` <span class="rating-count">(${reviews})</span>`;

            // Show modal
            const modalElement = document.getElementById('quickViewModal');
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        });

        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.view-detail-btn');
            if (!btn) return;

            // console.log(btn.dataset);

            const code = btn.dataset.code;
            // // console.log(code);

            window.open(`/shop/products/${code}`, '_blank');
        });
    </script>
@endpush
