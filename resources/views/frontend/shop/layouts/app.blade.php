<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce Template</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Clash+Display:wght@400;500;600;700&family=Satoshi:wght@300;400;500;700&display=swap');

    :root {
        --cream: #F7F4EF;
        --ink: #1A1A2E;
        --accent: #FF4D4D;
        --accent-2: #FFB347;
        --muted: #8A8A9A;
        --border: #E8E4DC;
        --green: #22C55E;
    }

    * {
        box-sizing: border-box;
    }

    body {
        background: var(--cream);
        font-family: 'Satoshi', sans-serif;
        color: var(--ink);
    }

    .shop-wrapper {
        max-width: 1440px;
        margin: 0 auto;
        padding: 0 2rem 4rem;
    }

    /* HEADER */
    .shop-header {
        position: relative;
        padding: 4rem 0 3rem;
        overflow: hidden;
    }

    .shop-header::before {
        content: 'SHOP';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-family: 'Clash Display', sans-serif;
        font-size: clamp(6rem, 18vw, 16rem);
        font-weight: 700;
        color: rgba(26, 26, 46, 0.04);
        white-space: nowrap;
        pointer-events: none;
        user-select: none;
        letter-spacing: -0.05em;
    }

    .shop-header h1 {
        font-family: 'Clash Display', sans-serif;
        font-size: clamp(2.5rem, 5vw, 4.5rem);
        font-weight: 700;
        letter-spacing: -0.03em;
        line-height: 1;
        color: var(--ink);
        position: relative;
    }

    .shop-header h1 span {
        color: var(--accent);
    }

    .shop-header p {
        font-size: 1.05rem;
        color: var(--muted);
        margin-top: .75rem;
    }

    /* SEARCH */
    .search-bar-wrap {
        position: relative;
        max-width: 540px;
    }

    .search-bar-wrap input {
        width: 100%;
        padding: 1rem 3rem 1rem 3.25rem;
        border: 2px solid var(--border);
        border-radius: 100px;
        background: white;
        font-family: 'Satoshi', sans-serif;
        font-size: .95rem;
        color: var(--ink);
        outline: none;
        transition: border-color .2s, box-shadow .2s;
    }

    .search-bar-wrap input:focus {
        border-color: var(--ink);
        box-shadow: 0 0 0 4px rgba(26, 26, 46, .06);
    }

    .search-icon {
        position: absolute;
        left: 1.1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--muted);
        pointer-events: none;
    }

    .clear-search-btn {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: var(--muted);
        font-size: 1rem;
        line-height: 1;
        padding: 0;
        display: none;
    }

    .search-bar-wrap input:not(:placeholder-shown)~.clear-search-btn {
        display: block;
    }

    /* LAYOUT */
    .shop-layout {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 2.5rem;
        align-items: start;
    }

    @media(max-width:1024px) {
        .shop-layout {
            grid-template-columns: 1fr;
        }

        .desktop-sidebar {
            display: none;
        }
    }

    /* SIDEBAR */
    .sidebar {
        position: sticky;
        top: 1.5rem;
    }

    .filter-card {
        background: white;
        border: 1.5px solid var(--border);
        border-radius: 1.5rem;
        padding: 1.75rem;
        margin-bottom: 1.25rem;
    }

    .filter-card h3 {
        font-family: 'Clash Display', sans-serif;
        font-size: .8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: var(--muted);
        margin-bottom: 1.25rem;
    }

    /* PILLS */
    .filter-pills {
        display: flex;
        flex-wrap: wrap;
        gap: .5rem;
    }

    .filter-pill {
        padding: .45rem 1rem;
        border: 1.5px solid var(--border);
        border-radius: 100px;
        font-size: .85rem;
        font-weight: 500;
        cursor: pointer;
        background: transparent;
        color: var(--ink);
        user-select: none;
        transition: all .15s;
    }

    .filter-pill:hover {
        border-color: var(--ink);
    }

    .filter-pill.active {
        background: var(--ink);
        color: white;
        border-color: var(--ink);
    }

    .filter-pill.brand-pill.active {
        background: var(--accent);
        border-color: var(--accent);
    }

    /* PRICE */
    .price-range-wrap {
        padding: .25rem 0;
    }

    input[type=range] {
        -webkit-appearance: none;
        width: 100%;
        height: 3px;
        background: var(--border);
        border-radius: 100px;
        outline: none;
        cursor: pointer;
    }

    input[type=range]::-webkit-slider-thumb {
        -webkit-appearance: none;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: var(--ink);
        cursor: pointer;
        border: 3px solid white;
        box-shadow: 0 0 0 2px var(--ink);
    }

    .price-inputs {
        display: flex;
        gap: .5rem;
        margin-top: 1rem;
    }

    .price-inputs>div {
        flex: 1;
    }

    .price-inputs label {
        font-size: .75rem;
        color: var(--muted);
        display: block;
        margin-bottom: .3rem;
    }

    .price-inputs input[type=number] {
        width: 100%;
        padding: .55rem .75rem;
        border: 1.5px solid var(--border);
        border-radius: .75rem;
        font-family: 'Satoshi', sans-serif;
        font-size: .85rem;
        outline: none;
        color: var(--ink);
        transition: border-color .2s;
    }

    .price-inputs input[type=number]:focus {
        border-color: var(--ink);
    }

    .price-label-row {
        display: flex;
        justify-content: space-between;
        font-size: .85rem;
        font-weight: 600;
        color: var(--ink);
        margin-top: .75rem;
    }

    .price-label-row span {
        color: var(--accent);
    }

    /* RATING */
    .rating-row {
        display: flex;
        align-items: center;
        gap: .6rem;
        padding: .45rem .5rem;
        cursor: pointer;
        border-radius: .5rem;
        transition: background .15s;
    }

    .rating-row:hover {
        background: var(--cream);
    }

    .rating-row input[type=radio] {
        accent-color: var(--accent);
        width: 16px;
        height: 16px;
        cursor: pointer;
    }

    .stars {
        display: flex;
        gap: 2px;
    }

    .star {
        color: var(--accent-2);
        font-size: .9rem;
    }

    .star.empty {
        color: var(--border);
    }

    .rating-label {
        font-size: .85rem;
        color: var(--muted);
    }

    /* BUTTONS */
    .btn-apply {
        width: 100%;
        padding: .85rem;
        background: var(--ink);
        color: white;
        border: none;
        border-radius: 100px;
        font-family: 'Satoshi', sans-serif;
        font-size: .9rem;
        font-weight: 600;
        cursor: pointer;
        transition: opacity .2s, transform .15s;
        margin-top: .5rem;
    }

    .btn-apply:hover {
        opacity: .85;
        transform: translateY(-1px);
    }

    .btn-reset {
        display: block;
        width: 100%;
        padding: .75rem;
        background: transparent;
        color: var(--muted);
        border: 1.5px solid var(--border);
        border-radius: 100px;
        font-family: 'Satoshi', sans-serif;
        font-size: .88rem;
        font-weight: 500;
        cursor: pointer;
        transition: all .15s;
        margin-top: .5rem;
        text-decoration: none;
        text-align: center;
    }

    .btn-reset:hover {
        border-color: var(--ink);
        color: var(--ink);
    }

    /* TOOLBAR */
    .toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.75rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .result-count {
        font-size: .95rem;
        color: var(--muted);
    }

    .result-count strong {
        color: var(--ink);
        font-weight: 700;
    }

    .sort-wrap select {
        padding: .6rem 2.5rem .6rem 1rem;
        border: 1.5px solid var(--border);
        border-radius: 100px;
        background: white url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238A8A9A' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E") no-repeat right 1rem center;
        -webkit-appearance: none;
        font-family: 'Satoshi', sans-serif;
        font-size: .88rem;
        font-weight: 500;
        color: var(--ink);
        outline: none;
        cursor: pointer;
        transition: border-color .2s;
    }

    .sort-wrap select:focus {
        border-color: var(--ink);
    }

    .view-toggle {
        display: flex;
        gap: .35rem;
    }

    .view-btn {
        padding: .55rem .65rem;
        border: 1.5px solid var(--border);
        border-radius: .75rem;
        background: white;
        cursor: pointer;
        color: var(--muted);
        transition: all .15s;
    }

    .view-btn.active {
        background: var(--ink);
        border-color: var(--ink);
        color: white;
    }

    .mobile-filter-btn {
        display: none;
        align-items: center;
        gap: .5rem;
        padding: .6rem 1.25rem;
        border: 1.5px solid var(--border);
        border-radius: 100px;
        background: white;
        font-family: 'Satoshi', sans-serif;
        font-size: .88rem;
        font-weight: 500;
        cursor: pointer;
        color: var(--ink);
    }

    @media(max-width:1024px) {
        .mobile-filter-btn {
            display: flex;
        }
    }

    /* ACTIVE FILTERS */
    .active-filters {
        display: flex;
        flex-wrap: wrap;
        gap: .5rem;
        margin-bottom: 1.25rem;
    }

    .active-filter-tag {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .35rem .85rem;
        background: var(--ink);
        color: white;
        border-radius: 100px;
        font-size: .8rem;
        font-weight: 500;
    }

    .active-filter-tag a {
        color: rgba(255, 255, 255, .6);
        text-decoration: none;
        font-size: .9rem;
    }

    .active-filter-tag a:hover {
        color: white;
    }

    /* GRID */
    .products-grid {
        display: grid;
        /* grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); */
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }

    .products-grid.list-view {
        grid-template-columns: 1fr;
    }

    /* CARD */
    .product-card {
        background: white;
        border: 1.5px solid var(--border);
        border-radius: 1.5rem;
        overflow: hidden;
        transition: transform .3s cubic-bezier(.34, 1.56, .64, 1), box-shadow .3s;
        position: relative;
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 24px 48px rgba(26, 26, 46, .12);
    }

    .products-grid.list-view .product-card {
        flex-direction: row;
        border-radius: 1.25rem;
    }

    .products-grid.list-view .card-image-wrap {
        width: 220px;
        flex-shrink: 0;
        aspect-ratio: auto;
    }

    .products-grid.list-view .card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .card-image-wrap {
        position: relative;
        overflow: hidden;
        background: var(--cream);
        aspect-ratio: 1/1;
    }

    .card-image-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .5s cubic-bezier(.25, .46, .45, .94);
        display: block;
    }

    .product-card:hover .card-image-wrap img {
        transform: scale(1.08);
    }

    .badge-group {
        position: absolute;
        top: .85rem;
        left: .85rem;
        display: flex;
        flex-direction: column;
        gap: .35rem;
        z-index: 2;
    }

    .badge {
        padding: .25rem .7rem;
        border-radius: 100px;
        font-size: .72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        display: inline-block;
    }

    .badge-stock-in {
        background: #22C55E;
        color: white;
    }

    .badge-stock-out {
        background: #EF4444;
        color: white;
    }

    .badge-sale {
        background: var(--accent);
        color: white;
    }

    .btn-wishlist {
        position: absolute;
        top: .85rem;
        right: .85rem;
        z-index: 2;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: white;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .1);
        transition: transform .2s;
        color: var(--muted);
    }

    .btn-wishlist:hover {
        transform: scale(1.15);
    }

    .btn-wishlist.wishlisted {
        color: var(--accent);
    }

    .btn-wishlist.wishlisted svg {
        fill: var(--accent);
    }

    .btn-quick-view {
        position: absolute;
        bottom: .85rem;
        left: 50%;
        transform: translateX(-50%) translateY(60px);
        opacity: 0;
        z-index: 2;
        white-space: nowrap;
        padding: .5rem 1.25rem;
        background: rgba(26, 26, 46, .92);
        color: white;
        border: none;
        border-radius: 100px;
        font-family: 'Satoshi', sans-serif;
        font-size: .82rem;
        font-weight: 600;
        cursor: pointer;
        transition: opacity .25s, transform .3s cubic-bezier(.34, 1.56, .64, 1);
        backdrop-filter: blur(8px);
    }

    .product-card:hover .btn-quick-view {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }

    .card-body {
        padding: 1.25rem 1.25rem 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: .5rem;
    }

    .card-brand {
        font-size: .75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: var(--muted);
    }

    .card-name {
        font-family: 'Clash Display', sans-serif;
        font-size: 1.05rem;
        font-weight: 600;
        color: var(--ink);
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .card-rating {
        display: flex;
        align-items: center;
        gap: .4rem;
    }

    .card-rating .star {
        font-size: .78rem;
    }

    .rating-count {
        font-size: .78rem;
        color: var(--muted);
    }

    .card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: auto;
        padding-top: .75rem;
        border-top: 1px solid var(--border);
        gap: .75rem;
    }

    .price-wrap {
        display: flex;
        flex-direction: column;
    }

    .price-current {
        font-family: 'Clash Display', sans-serif;
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--ink);
        line-height: 1;
    }

    .price-original {
        font-size: .8rem;
        color: var(--muted);
        text-decoration: line-through;
        margin-top: 1px;
    }

    .btn-cart {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        gap: .4rem;
        padding: .65rem 1.1rem;
        background: var(--ink);
        color: white;
        border: none;
        border-radius: 100px;
        font-family: 'Satoshi', sans-serif;
        font-size: .82rem;
        font-weight: 600;
        cursor: pointer;
        transition: background .2s, transform .15s;
    }

    .btn-cart:hover {
        background: var(--accent);
        transform: scale(1.04);
    }

    .btn-cart.sold-out {
        background: var(--border);
        color: var(--muted);
        cursor: not-allowed;
    }

    .btn-cart.sold-out:hover {
        background: var(--border);
        transform: none;
    }

    .btn-detail {
        flex-shrink: 0;
        padding: .65rem 1.1rem;
        background: transparent;
        color: var(--ink);
        border: 1.5px solid var(--border);
        border-radius: 100px;
        font-family: 'Satoshi', sans-serif;
        font-size: .82rem;
        font-weight: 600;
        text-decoration: none;
        transition: all .2s;
    }

    .btn-detail:hover {
        border-color: var(--ink);
        background: var(--ink);
        color: white;
    }

    /* EMPTY */
    .empty-state {
        grid-column: 1/-1;
        text-align: center;
        padding: 6rem 2rem;
    }

    .empty-state h3 {
        font-family: 'Clash Display', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        opacity: .4;
    }

    .empty-state p {
        color: var(--muted);
        margin-top: .5rem;
    }

    /* PAGINATION */
    .pagination-wrap {
        margin-top: 3rem;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: .4rem;
        flex-wrap: wrap;
    }

    .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 42px;
        height: 42px;
        border: 1.5px solid var(--border);
        border-radius: .85rem;
        font-family: 'Satoshi', sans-serif;
        font-size: .88rem;
        font-weight: 600;
        color: var(--ink);
        text-decoration: none;
        transition: all .15s;
        padding: 0 1rem;
        background: white;
    }

    .page-link:hover {
        border-color: var(--ink);
    }

    .page-link.active {
        background: var(--ink);
        color: white;
        border-color: var(--ink);
    }

    .page-link.disabled {
        color: var(--muted);
        pointer-events: none;
        background: transparent;
    }

    /* MODAL */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 15, 30, .65);
        backdrop-filter: blur(6px);
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        opacity: 0;
        pointer-events: none;
        transition: opacity .25s;
    }

    .modal-overlay.open {
        opacity: 1;
        pointer-events: all;
    }

    .modal-box {
        background: white;
        border-radius: 2rem;
        max-width: 860px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        display: grid;
        grid-template-columns: 1fr 1fr;
        transform: translateY(30px) scale(.97);
        transition: transform .3s cubic-bezier(.34, 1.56, .64, 1);
        position: relative;
    }

    .modal-overlay.open .modal-box {
        transform: translateY(0) scale(1);
    }

    @media(max-width:640px) {
        .modal-box {
            grid-template-columns: 1fr;
        }
    }

    .modal-img-wrap {
        aspect-ratio: 1;
        background: var(--cream);
        border-radius: 2rem 0 0 2rem;
        overflow: hidden;
    }

    .modal-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .modal-body {
        padding: 2.5rem 2rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .modal-brand {
        font-size: .75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: var(--muted);
    }

    .modal-name {
        font-family: 'Clash Display', sans-serif;
        font-size: 1.6rem;
        font-weight: 700;
        line-height: 1.2;
    }

    .modal-price {
        font-family: 'Clash Display', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        color: var(--accent);
    }

    .modal-desc {
        font-size: .9rem;
        color: var(--muted);
        line-height: 1.6;
        flex: 1;
    }

    .modal-actions {
        display: flex;
        gap: .75rem;
        flex-wrap: wrap;
    }

    .modal-btn-cart {
        flex: 1;
        padding: .9rem 1.5rem;
        background: var(--ink);
        color: white;
        border: none;
        border-radius: 100px;
        font-family: 'Satoshi', sans-serif;
        font-size: .9rem;
        font-weight: 700;
        cursor: pointer;
        transition: background .2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: .4rem;
    }

    .modal-btn-cart:hover {
        background: var(--accent);
    }

    .modal-btn-detail {
        padding: .9rem 1.5rem;
        background: transparent;
        color: var(--ink);
        border: 1.5px solid var(--border);
        border-radius: 100px;
        font-family: 'Satoshi', sans-serif;
        font-size: .88rem;
        font-weight: 600;
        cursor: pointer;
        transition: all .2s;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-btn-detail:hover {
        border-color: var(--ink);
    }

    .modal-close {
        position: absolute;
        top: 1.25rem;
        right: 1.25rem;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: 1.5px solid var(--border);
        background: white;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--ink);
        transition: all .15s;
        z-index: 10;
    }

    .modal-close:hover {
        background: var(--ink);
        color: white;
        border-color: var(--ink);
    }

    /* MOBILE DRAWER */
    .sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .4);
        z-index: 500;
    }

    .sidebar-drawer {
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        width: min(320px, 90vw);
        background: white;
        z-index: 501;
        overflow-y: auto;
        padding: 1.5rem;
        transform: translateX(-100%);
        transition: transform .3s cubic-bezier(.34, 1.56, .64, 1);
    }

    @media(max-width:1024px) {
        .sidebar-overlay.open {
            display: block;
        }

        .sidebar-drawer.open {
            transform: translateX(0);
        }
    }

    /* TOAST */
    .toast {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        background: var(--ink);
        color: white;
        padding: .85rem 1.5rem;
        border-radius: 100px;
        font-family: 'Satoshi', sans-serif;
        font-size: .9rem;
        font-weight: 600;
        z-index: 2000;
        transform: translateY(80px);
        opacity: 0;
        transition: all .35s cubic-bezier(.34, 1.56, .64, 1);
    }

    .toast.show {
        transform: translateY(0);
        opacity: 1;
    }
</style>
</head>
<body class="flex flex-col min-h-screen">


    <!-- Header Section -->
    @include('frontend.shop.layouts.header')

    <!-- Content (Body) -->
    <main class="flex-1">
            @yield('content')
    </main>

    <!-- Footer Section -->
    @include('frontend.shop.layouts.footer')

</body>
</html>
