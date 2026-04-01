@extends('frontend.shop.layouts.app')

@section('title', __('messages.products_list'))

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
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
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

@section('content')

    {{-- ═══════════════════════════════════════════════
    QUICK VIEW MODAL
    ═══════════════════════════════════════════════ --}}
    <div class="modal-overlay" id="quickViewModal">
        <div class="modal-box">
            <button class="modal-close" onclick="closeQuickView()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M18 6L6 18M6 6l12 12" />
                </svg>
            </button>
            <div class="modal-img-wrap"><img id="qv-image" src="" alt=""></div>
            <div class="modal-body">
                <div class="modal-brand" id="qv-brand"></div>
                <div class="modal-name" id="qv-name"></div>
                <div class="card-rating" id="qv-rating"></div>
                <div class="modal-price" id="qv-price"></div>
                <p class="modal-desc" id="qv-desc"></p>
                <div id="qv-stock"></div>
                <div class="modal-actions">
                    <button class="modal-btn-cart" id="qv-cart-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0" />
                        </svg>
                        Add to Cart
                    </button>
                    <a class="modal-btn-detail" id="qv-detail-link" href="#">View Full Details</a>
                </div>
            </div>
        </div>
    </div>

    <div class="toast" id="toast"></div>

    {{-- ═══════════════════════════════════════════════
    MOBILE FILTER DRAWER
    ═══════════════════════════════════════════════ --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeMobileSidebar()"></div>
    <div class="sidebar-drawer" id="sidebarDrawer">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
            <h2 style="font-family:'Clash Display',sans-serif;font-size:1.3rem;font-weight:700;">Filters</h2>
            <button onclick="closeMobileSidebar()" style="background:none;border:none;cursor:pointer;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M18 6L6 18M6 6l12 12" />
                </svg>
            </button>
        </div>
        {{-- Mirror of desktop sidebar — reads from same hidden inputs via JS --}}
        <div id="mobile-filter-body"></div>
    </div>

    <div class="shop-wrapper">

        {{-- ── HEADER ── --}}
        <div class="shop-header">
            <div
                style="display:flex;flex-wrap:wrap;align-items:flex-end;justify-content:space-between;gap:1.5rem;position:relative;z-index:1;">
                <div>
                    <h1>Our <span>Collection</span></h1>
                    <p>{{ $products->total() }} products available for you</p>
                </div>
                <div class="search-bar-wrap">
                    <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <circle cx="11" cy="11" r="8" />
                        <path d="M21 21l-4.35-4.35" />
                    </svg>
                    <input type="text" id="searchInput" placeholder="Search products…" value="{{ request('search') }}"
                        onkeydown="if(event.key==='Enter') submitFilters()">
                    <button type="button" class="clear-search-btn" onclick="clearSearch()">✕</button>
                </div>
            </div>
        </div>

        <div class="shop-layout">

            {{-- ═══════════════════════════════════════════════
            DESKTOP SIDEBAR — single form, no include
            ═══════════════════════════════════════════════ --}}
            <aside class="sidebar desktop-sidebar">
                <form id="filterForm" method="GET" action="{{ route('shop.products') }}">

                    {{-- Preserve search & sort across filter submits --}}
                    <input type="hidden" name="search" id="hiddenSearch" value="{{ request('search') }}">
                    <input type="hidden" name="sort" id="hiddenSort" value="{{ request('sort') }}">

                    {{-- CATEGORIES --}}
                    <div class="filter-card">
                        <h3>Categories</h3>
                        <input type="hidden" name="category" id="categoryInput" value="{{ request('category') }}">
                        <div class="filter-pills" id="categoryPills">
                            <button type="button" class="filter-pill {{ !request('category') ? 'active' : '' }}"
                                onclick="selectPill(this, 'categoryInput', '')">All</button>
                            @foreach($categories as $cat)
                                <button type="button"
                                    class="filter-pill {{ request('category') == ($cat->slug ?? $cat->name) ? 'active' : '' }}"
                                    onclick="selectPill(this, 'categoryInput', '{{ $cat->slug ?? $cat->name }}')">
                                    {{ $cat->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- BRANDS --}}
                    <div class="filter-card">
                        <h3>Brands</h3>
                        <input type="hidden" name="brand" id="brandInput" value="{{ request('brand') }}">
                        <div class="filter-pills" id="brandPills">
                            <button type="button" class="filter-pill brand-pill {{ !request('brand') ? 'active' : '' }}"
                                onclick="selectPill(this, 'brandInput', '')">All</button>
                            @foreach($brands as $brand)
                                <button type="button"
                                    class="filter-pill brand-pill {{ request('brand') == ($brand->slug ?? $brand->name) ? 'active' : '' }}"
                                    onclick="selectPill(this, 'brandInput', '{{ $brand->slug ?? $brand->name }}')">
                                    {{ $brand->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- PRICE RANGE --}}
                    <div class="filter-card">
                        <h3>Price Range</h3>
                        <div class="price-range-wrap">
                            <input type="range" id="priceSlider" min="0" max="{{ (int) $maxProductPrice }}"
                                value="{{ request('max_price', (int) $maxProductPrice) }}"
                                oninput="syncPriceSlider(this.value)">
                            <div class="price-label-row">
                                <span>$0</span>
                                <span id="sliderLabel">${{ number_format(request('max_price', $maxProductPrice)) }}</span>
                            </div>
                        </div>
                        <div class="price-inputs">
                            <div>
                                <label>Min ($)</label>
                                <input type="number" name="min_price" id="minPriceInput" placeholder="0" min="0"
                                    value="{{ request('min_price') }}">
                            </div>
                            <div>
                                <label>Max ($)</label>
                                <input type="number" name="max_price" id="maxPriceInput"
                                    placeholder="{{ (int) $maxProductPrice }}" min="0" value="{{ request('max_price') }}"
                                    oninput="syncPriceSlider(this.value)">
                            </div>
                        </div>
                    </div>

                    {{-- RATING --}}
                    <div class="filter-card">
                        <h3>Minimum Rating</h3>
                        @foreach([5, 4, 3, 2, 1] as $r)
                            <label class="rating-row">
                                <input type="radio" name="rating" value="{{ $r }}" {{ request('rating') == $r ? 'checked' : '' }}>
                                <div class="stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="star {{ $i <= $r ? '' : 'empty' }}">★</span>
                                    @endfor
                                </div>
                                <span class="rating-label">& up</span>
                            </label>
                        @endforeach
                    </div>

                    {{-- ACTIONS --}}
                    <div style="padding:0 0 .5rem;">
                        <button type="submit" class="btn-apply">Apply Filters</button>
                        <a href="{{ route('shop.products') }}" class="btn-reset">Reset All</a>
                    </div>

                </form>{{-- #filterForm --}}
            </aside>

            {{-- ═══════════════════════════════════════════════
            MAIN CONTENT
            ═══════════════════════════════════════════════ --}}
            <main>

                {{-- Active filter tags --}}
                @if(request('search') || request('category') || request('brand') || request('min_price') || request('max_price') || request('rating'))
                    <div class="active-filters">
                        @if(request('search'))
                            <span class="active-filter-tag">
                                Search: {{ request('search') }}
                                <a href="{{ request()->fullUrlWithoutQuery(['search']) }}">✕</a>
                            </span>
                        @endif
                        @if(request('category'))
                            <span class="active-filter-tag">
                                Category: {{ request('category') }}
                                <a href="{{ request()->fullUrlWithoutQuery(['category']) }}">✕</a>
                            </span>
                        @endif
                        @if(request('brand'))
                            <span class="active-filter-tag">
                                Brand: {{ request('brand') }}
                                <a href="{{ request()->fullUrlWithoutQuery(['brand']) }}">✕</a>
                            </span>
                        @endif
                        @if(request('min_price') || request('max_price'))
                            <span class="active-filter-tag">
                                Price: ${{ request('min_price', 0) }} – ${{ request('max_price', $maxProductPrice) }}
                                <a href="{{ request()->fullUrlWithoutQuery(['min_price', 'max_price']) }}">✕</a>
                            </span>
                        @endif
                        @if(request('rating'))
                            <span class="active-filter-tag">
                                Rating: {{ request('rating') }}★+
                                <a href="{{ request()->fullUrlWithoutQuery(['rating']) }}">✕</a>
                            </span>
                        @endif
                        <a href="{{ route('shop.products') }}"
                            style="font-size:.8rem;color:var(--muted);text-decoration:underline;align-self:center;">Clear
                            all</a>
                    </div>
                @endif

                {{-- Toolbar --}}
                <div class="toolbar">
                    <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;">
                        <button type="button" class="mobile-filter-btn" onclick="openMobileSidebar()">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <line x1="4" y1="6" x2="20" y2="6" />
                                <line x1="8" y1="12" x2="20" y2="12" />
                                <line x1="12" y1="18" x2="20" y2="18" />
                            </svg>
                            Filters
                        </button>
                        <p class="result-count">
                            Showing <strong>{{ $products->count() }}</strong> of <strong>{{ $products->total() }}</strong>
                            products
                        </p>
                    </div>
                    <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;">
                        <div class="sort-wrap">
                            <select id="sortSelect" onchange="changeSort(this.value)">
                                <option value="" {{ !request('sort') ? 'selected' : '' }}>Sort: Featured</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low →
                                    High</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High →
                                    Low</option>
                                <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A → Z
                                </option>
                                <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name: Z → A
                                </option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Top Rated</option>
                            </select>
                        </div>
                        <div class="view-toggle">
                            <button type="button" class="view-btn active" id="gridViewBtn" onclick="setView('grid')"
                                title="Grid">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <rect x="3" y="3" width="7" height="7" />
                                    <rect x="14" y="3" width="7" height="7" />
                                    <rect x="3" y="14" width="7" height="7" />
                                    <rect x="14" y="14" width="7" height="7" />
                                </svg>
                            </button>
                            <button type="button" class="view-btn" id="listViewBtn" onclick="setView('list')" title="List">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <line x1="8" y1="6" x2="21" y2="6" />
                                    <line x1="8" y1="12" x2="21" y2="12" />
                                    <line x1="8" y1="18" x2="21" y2="18" />
                                    <line x1="3" y1="6" x2="3.01" y2="6" />
                                    <line x1="3" y1="12" x2="3.01" y2="12" />
                                    <line x1="3" y1="18" x2="3.01" y2="18" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Products Grid --}}
                <div class="products-grid" id="productsGrid">
                    @forelse($products as $product)
                        @php
                            $inStock = ($product->quantity ?? $product->stock ?? 1) > 0;
                            $rating = $product->rating ?? rand(3, 5);
                            $reviews = $product->reviews_count ?? rand(4, 120);
                            $brand = $product->brand->name ?? 'Generic';
                            $price = $product->selling_price ?? $product->price ?? 0;
                            $oldPrice = $product->old_price ?? $product->original_price ?? null;
                            $image = $product->image
                                ? asset('storage/' . $product->image)
                                : ($product->image ? asset('storage/' . $product->image) : asset('noimage.png'));
                        @endphp

                        <div class="product-card" data-id="{{ $product->id }}" data-name="{{ e($product->name) }}"
                            data-brand="{{ e($brand) }}" data-price="{{ $price }}" data-image="{{ $image }}"
                            data-desc="{{ e(Str::limit($product->description ?? '', 200)) }}" data-rating="{{ $rating }}"
                            data-reviews="{{ $reviews }}" data-stock="{{ $inStock ? 1 : 0 }}"
                            data-detail-url="{{ route('shop.product.show', $product->id) }}">

                            <div class="card-image-wrap">
                                <img src="{{ $image }}" alt="{{ e($product->name) }}" loading="lazy">

                                <div class="badge-group">
                                    <span class="badge {{ $inStock ? 'badge-stock-in' : 'badge-stock-out' }}">
                                        {{ $inStock ? 'In Stock' : 'Out of Stock' }}
                                    </span>
                                    @if($oldPrice && $oldPrice > $price)
                                        @php $disc = round((($oldPrice - $price) / $oldPrice) * 100) @endphp
                                        <span class="badge badge-sale">-{{ $disc }}%</span>
                                    @endif
                                </div>

                                <button type="button" class="btn-wishlist" onclick="toggleWishlist(this,{{ $product->id }})"
                                    title="Wishlist">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <path
                                            d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z" />
                                    </svg>
                                </button>

                                <button type="button" class="btn-quick-view"
                                    onclick="openQuickView(this.closest('.product-card'))">
                                    Quick View
                                </button>
                            </div>

                            <div class="card-body">
                                <div class="card-brand">{{ $brand }}</div>
                                <div class="card-name">{{ $product->name }}</div>
                                <div class="card-rating">
                                    <div class="stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            <span class="star {{ $i <= $rating ? '' : 'empty' }}">★</span>
                                        @endfor
                                    </div>
                                    <span class="rating-count">({{ $reviews }})</span>

                                </div>
                                                                    <div class="price-wrap">
                                        <span class="price-current">${{ number_format($price, 2) }}</span>
                                        @if($oldPrice && $oldPrice > $price)
                                            <span class="price-original">${{ number_format($oldPrice, 2) }}</span>
                                        @endif
                                    </div>
                                <div class="card-footer">

                                    <div style="display:flex;gap:.4rem;">
                                        @if($inStock)
                                            <button type="button" class="btn-cart"
                                                onclick="addToCart({{ $product->id }},'{{ e($product->name) }}',this)">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2.5">
                                                    <path
                                                        d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4zM3 6h18M16 10a4 4 0 01-8 0" />
                                                </svg>
                                                Add
                                            </button>
                                        @else
                                            <button type="button" class="btn-cart sold-out" disabled>Sold Out</button>
                                        @endif
                                        <a href="{{ route('shop.product.show', $product->id) }}" class="btn-detail">View</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                style="opacity:.15;display:block;margin:0 auto 1.5rem;">
                                <circle cx="11" cy="11" r="8" />
                                <path d="M21 21l-4.35-4.35" />
                            </svg>
                            <h3>No products found</h3>
                            <p>Try adjusting your filters or search terms.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($products->hasPages())
                    <div class="pagination-wrap">
                        @if($products->onFirstPage())
                            <span class="page-link disabled">← Prev</span>
                        @else
                            <a class="page-link" href="{{ $products->previousPageUrl() }}">← Prev</a>
                        @endif

                        @foreach($products->getUrlRange(max(1, $products->currentPage() - 2), min($products->lastPage(), $products->currentPage() + 2)) as $page => $url)
                            <a href="{{ $url }}"
                                class="page-link {{ $page == $products->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                        @endforeach

                        @if($products->hasMorePages())
                            <a class="page-link" href="{{ $products->nextPageUrl() }}">Next →</a>
                        @else
                            <span class="page-link disabled">Next →</span>
                        @endif
                    </div>
                @endif

            </main>
        </div>{{-- .shop-layout --}}
    </div>{{-- .shop-wrapper --}}

    <script>
        // ═══════════════════════════════════════════════════════
        //  SEARCH — Enter or debounce updates hidden input & submits
        // ═══════════════════════════════════════════════════════
        let searchTimer;
        document.getElementById('searchInput').addEventListener('input', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => submitFilters(), 600);
        });
        function clearSearch() {
            document.getElementById('searchInput').value = '';
            submitFilters();
        }

        // ═══════════════════════════════════════════════════════
        //  PILL SELECTION — updates hidden input in the form
        // ═══════════════════════════════════════════════════════
        function selectPill(btn, inputId, value) {
            // Deactivate siblings in same group
            btn.closest('.filter-pills').querySelectorAll('.filter-pill').forEach(p => p.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById(inputId).value = value;
        }

        // ═══════════════════════════════════════════════════════
        //  PRICE SLIDER — keeps slider & number input in sync
        // ═══════════════════════════════════════════════════════
        function syncPriceSlider(val) {
            const v = parseInt(val) || 0;
            document.getElementById('priceSlider').value = v;
            document.getElementById('maxPriceInput').value = v;
            document.getElementById('sliderLabel').textContent = '$' + v.toLocaleString();
        }

        // ═══════════════════════════════════════════════════════
        //  SORT — immediate redirect preserving other filters
        // ═══════════════════════════════════════════════════════
        function changeSort(val) {
            const params = new URLSearchParams(window.location.search);
            if (val) params.set('sort', val); else params.delete('sort');
            params.delete('page');
            window.location.href = '{{ route("shop.products") }}?' + params.toString();
        }

        // ═══════════════════════════════════════════════════════
        //  SUBMIT FILTERS — syncs search input then submits form
        // ═══════════════════════════════════════════════════════
        function submitFilters() {
            document.getElementById('hiddenSearch').value = document.getElementById('searchInput').value;
            document.getElementById('hiddenSort').value = document.getElementById('sortSelect').value;
            // Remove empty fields so URL stays clean
            document.getElementById('filterForm').querySelectorAll('input, select').forEach(el => {
                if (el.value === '' || el.value === null) el.disabled = true;
            });
            document.getElementById('filterForm').submit();
        }

        // Also wire Apply button directly (it's type=submit so form submits naturally,
        // but we still need to sync search/sort first)
        document.getElementById('filterForm').addEventListener('submit', function () {
            document.getElementById('hiddenSearch').value = document.getElementById('searchInput').value;
            document.getElementById('hiddenSort').value = document.getElementById('sortSelect').value;
        });

        // ═══════════════════════════════════════════════════════
        //  MOBILE SIDEBAR — clones the desktop form HTML
        // ═══════════════════════════════════════════════════════
        function openMobileSidebar() {
            // Clone desktop sidebar content into mobile drawer (excluding the form wrapper)
            const src = document.getElementById('filterForm').innerHTML;
            const body = document.getElementById('mobile-filter-body');
            body.innerHTML = '<form method="GET" action="{{ route("shop.products") }}" onsubmit="return syncMobileForm(this)">' + src + '</form>';
            document.getElementById('sidebarOverlay').classList.add('open');
            document.getElementById('sidebarDrawer').classList.add('open');
            document.body.style.overflow = 'hidden';
        }
        function closeMobileSidebar() {
            document.getElementById('sidebarOverlay').classList.remove('open');
            document.getElementById('sidebarDrawer').classList.remove('open');
            document.body.style.overflow = '';
        }
        function syncMobileForm(form) {
            // Before mobile form submits, remove empty fields
            form.querySelectorAll('input, select').forEach(el => {
                if (el.value === '' || el.value === null) el.disabled = true;
            });
            return true;
        }

        // ═══════════════════════════════════════════════════════
        //  VIEW TOGGLE
        // ═══════════════════════════════════════════════════════
        function setView(mode) {
            const grid = document.getElementById('productsGrid');
            document.getElementById('gridViewBtn').classList.toggle('active', mode === 'grid');
            document.getElementById('listViewBtn').classList.toggle('active', mode === 'list');
            grid.classList.toggle('list-view', mode === 'list');
            localStorage.setItem('shopView', mode);
        }
        if (localStorage.getItem('shopView') === 'list') setView('list');

        // ═══════════════════════════════════════════════════════
        //  QUICK VIEW MODAL
        // ═══════════════════════════════════════════════════════
        function openQuickView(card) {
            const d = card.dataset;
            document.getElementById('qv-image').src = d.image;
            document.getElementById('qv-image').alt = d.name;
            document.getElementById('qv-brand').textContent = d.brand;
            document.getElementById('qv-name').textContent = d.name;
            document.getElementById('qv-price').textContent = '$' + parseFloat(d.price).toFixed(2);
            document.getElementById('qv-desc').textContent = d.desc;
            document.getElementById('qv-detail-link').href = d.detailUrl;

            const r = parseInt(d.rating);
            let stars = '<div class="stars">';
            for (let i = 1; i <= 5; i++) stars += `<span class="star ${i <= r ? '' : 'empty'}">★</span>`;
            stars += `</div><span class="rating-count">(${d.reviews} reviews)</span>`;
            document.getElementById('qv-rating').innerHTML = stars;

            const inStock = d.stock === '1';
            document.getElementById('qv-stock').innerHTML = inStock
                ? '<span class="badge badge-stock-in" style="font-size:.8rem">✓ In Stock</span>'
                : '<span class="badge badge-stock-out" style="font-size:.8rem">✗ Out of Stock</span>';

            const btn = document.getElementById('qv-cart-btn');
            btn.disabled = !inStock;
            btn.style.opacity = inStock ? '1' : '.4';
            btn.onclick = inStock ? () => { addToCart(parseInt(d.id), d.name, btn); closeQuickView(); } : null;

            document.getElementById('quickViewModal').classList.add('open');
            document.body.style.overflow = 'hidden';
        }
        function closeQuickView() {
            document.getElementById('quickViewModal').classList.remove('open');
            document.body.style.overflow = '';
        }
        document.getElementById('quickViewModal').addEventListener('click', e => { if (e.target === e.currentTarget) closeQuickView(); });

        // ═══════════════════════════════════════════════════════
        //  WISHLIST — localStorage
        // ═══════════════════════════════════════════════════════
        let wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
        document.querySelectorAll('.btn-wishlist').forEach(btn => {
            const id = parseInt(btn.closest('.product-card').dataset.id);
            if (wishlist.includes(id)) btn.classList.add('wishlisted');
        });
        function toggleWishlist(btn, id) {
            const idx = wishlist.indexOf(id);
            if (idx === -1) { wishlist.push(id); btn.classList.add('wishlisted'); showToast('❤️ Added to wishlist'); }
            else { wishlist.splice(idx, 1); btn.classList.remove('wishlisted'); showToast('🤍 Removed from wishlist'); }
            localStorage.setItem('wishlist', JSON.stringify(wishlist));
        }

        // ═══════════════════════════════════════════════════════
        //  ADD TO CART — POST form to shop.cart.add
        // ═══════════════════════════════════════════════════════
        function addToCart(id, name, btn) {
            const orig = btn.innerHTML;
            btn.innerHTML = '✓ Added!';
            btn.style.background = 'var(--green)';
            showToast('🛒 ' + name + ' added to cart');
            setTimeout(() => { btn.innerHTML = orig; btn.style.background = ''; }, 1800);

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/shop/cart/add/' + id;
            form.style.display = 'none';
            form.innerHTML = `
            <input type="hidden" name="_token"   value="{{ csrf_token() }}">
            <input type="hidden" name="quantity" value="1">`;
            document.body.appendChild(form);
            form.submit();
        }

        // ═══════════════════════════════════════════════════════
        //  TOAST
        // ═══════════════════════════════════════════════════════
        let toastTimer;
        function showToast(msg) {
            const t = document.getElementById('toast');
            t.textContent = msg;
            t.classList.add('show');
            clearTimeout(toastTimer);
            toastTimer = setTimeout(() => t.classList.remove('show'), 2500);
        }
    </script>

@endsection
