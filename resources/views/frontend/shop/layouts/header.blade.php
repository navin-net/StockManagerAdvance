@php
    $cartCount = array_sum(array_column(session('cart', []), 'quantity'));
@endphp

{{-- =====================================================
     NAVBAR — matches your cream/ink/accent design system
     Requires: Clash Display + Satoshi already loaded
     CSS variables: --cream, --ink, --accent, --border, --muted
     ===================================================== --}}

<style>
    #site-header {
        position: sticky;
        top: 0;
        z-index: 200;
        background: rgba(247, 244, 239, 0.88);
        backdrop-filter: blur(18px) saturate(160%);
        -webkit-backdrop-filter: blur(18px) saturate(160%);
        border-bottom: 1.5px solid var(--border);
        transition: box-shadow 0.3s;
    }

    #site-header.scrolled {
        box-shadow: 0 4px 32px rgba(26, 26, 46, 0.07);
    }

    .nav-inner {
        max-width: 1440px;
        margin: 0 auto;
        padding: 0 2rem;
        height: 68px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 2rem;
    }

    /* Logo */
    .nav-logo {
        font-family: 'Clash Display', sans-serif;
        font-size: 1.45rem;
        font-weight: 700;
        letter-spacing: -0.03em;
        color: var(--ink);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 6px;
        flex-shrink: 0;
        transition: opacity 0.2s;
    }

    .nav-logo:hover { opacity: 0.7; }

    .nav-logo-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: var(--accent);
        flex-shrink: 0;
        margin-bottom: 1px;
    }

    /* Desktop links */
    .nav-links-desktop {
        display: none;
        align-items: center;
        gap: 2px;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    @media (min-width: 900px) {
        .nav-links-desktop  { display: flex; }
        .nav-mobile-toggle  { display: none !important; }
    }

    .nav-links-desktop a {
        position: relative;
        display: flex;
        align-items: center;
        gap: 7px;
        padding: 7px 15px;
        border-radius: 100px;
        font-family: 'Satoshi', sans-serif;
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--muted);
        text-decoration: none;
        transition: color 0.18s, background 0.18s;
        white-space: nowrap;
    }

    .nav-links-desktop a:hover {
        color: var(--ink);
        background: rgba(26, 26, 46, 0.05);
    }

    .nav-links-desktop a.nav-active {
        color: var(--ink);
        font-weight: 700;
        background: rgba(26, 26, 46, 0.07);
    }

    /* Red dot indicator under active item */
    .nav-links-desktop a.nav-active::after {
        content: '';
        position: absolute;
        bottom: 4px;
        left: 50%;
        transform: translateX(-50%);
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: var(--accent);
    }

    /* Cart badge */
    .nav-cart-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 19px;
        height: 19px;
        padding: 0 5px;
        border-radius: 100px;
        font-size: 10px;
        font-weight: 800;
        font-family: 'Satoshi', sans-serif;
        background: var(--accent);
        color: white;
        line-height: 1;
        transition: transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .nav-links-desktop a:hover .nav-cart-badge {
        transform: scale(1.2);
    }

    /* Login CTA */
    .nav-cta {
        padding: 8px 20px !important;
        background: var(--ink) !important;
        color: white !important;
        border-radius: 100px !important;
        font-weight: 700 !important;
        letter-spacing: -0.01em;
        transition: background 0.2s, transform 0.15s !important;
    }

    .nav-cta:hover {
        background: var(--accent) !important;
        color: white !important;
        transform: translateY(-1px) !important;
    }

    .nav-cta.nav-active {
        background: var(--accent) !important;
    }

    /* Hide the dot indicator on CTA */
    .nav-cta::after { display: none !important; }

    /* Mobile toggle button */
    .nav-mobile-toggle {
        background: none;
        border: 1.5px solid var(--border);
        color: var(--ink);
        cursor: pointer;
        padding: 8px 12px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
        font-family: 'Satoshi', sans-serif;
        font-size: 0.82rem;
        font-weight: 600;
        transition: border-color 0.2s, background 0.2s;
    }

    .nav-mobile-toggle:hover {
        border-color: var(--ink);
        background: rgba(26, 26, 46, 0.04);
    }

    /* Mobile menu panel */
    #nav-mobile-menu {
        display: none;
        flex-direction: column;
        gap: 3px;
        padding: 10px 1.5rem 16px;
        border-top: 1.5px solid var(--border);
        background: rgba(247, 244, 239, 0.97);
    }

    #nav-mobile-menu.open { display: flex; }

    #nav-mobile-menu a {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 11px 14px;
        border-radius: 14px;
        font-family: 'Satoshi', sans-serif;
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--muted);
        text-decoration: none;
        transition: color 0.18s, background 0.18s;
    }

    #nav-mobile-menu a:hover {
        color: var(--ink);
        background: rgba(26, 26, 46, 0.05);
    }

    #nav-mobile-menu a.nav-active {
        color: var(--ink);
        font-weight: 700;
        background: rgba(26, 26, 46, 0.06);
    }

    #nav-mobile-menu .mobile-nav-cta {
        margin-top: 6px;
        background: var(--ink) !important;
        color: white !important;
        border-radius: 100px !important;
        justify-content: center;
        font-weight: 700;
    }

    #nav-mobile-menu .mobile-nav-cta:hover {
        background: var(--accent) !important;
    }
</style>

<header id="site-header">
    <div class="nav-inner">

        {{-- Logo --}}
        <a href="{{ url('/') }}" class="nav-logo">
            E-Shop<span class="nav-logo-dot"></span>
        </a>

        {{-- Desktop nav --}}
        <ul class="nav-links-desktop">
            <li>
                <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'nav-active' : '' }}">
                    Home
                </a>
            </li>
            <li>
                <a href="{{ route('shop.products') }}" class="{{ request()->routeIs('shop.products') ? 'nav-active' : '' }}">
                    Products
                </a>
            </li>
            <li>
                <a href="{{ route('shop.cart') }}" class="{{ request()->routeIs('shop.cart') ? 'nav-active' : '' }}">
                    Cart
                    @if ($cartCount > 0)
                        <span class="nav-cart-badge" id="cartCount">{{ $cartCount }}</span>
                    @endif
                </a>
            </li>
            <li>
                <a href="{{ route('shop.checkout') }}" class="{{ request()->routeIs('shop.checkout') ? 'nav-active' : '' }}">
                    Checkout
                </a>
            </li>
            <li>
                <a href="{{ route('login') }}" class="nav-cta {{ request()->routeIs('login') ? 'nav-active' : '' }}">
                    Login
                </a>
            </li>
        </ul>

        {{-- Mobile toggle --}}
        <button class="nav-mobile-toggle" id="nav-mobile-toggle" aria-label="Toggle menu" aria-expanded="false">
            <svg id="nav-icon-ham" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            <svg id="nav-icon-x" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="display:none">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Menu
        </button>

    </div>

    {{-- Mobile menu --}}
    <div id="nav-mobile-menu" role="navigation">
        <a href="{{ url('/') }}"               class="{{ request()->is('/')                    ? 'nav-active' : '' }}">Home</a>
        <a href="{{ route('shop.products') }}" class="{{ request()->routeIs('shop.products')   ? 'nav-active' : '' }}">Products</a>
        <a href="{{ route('shop.cart') }}"     class="{{ request()->routeIs('shop.cart')       ? 'nav-active' : '' }}">
            Cart
            @if ($cartCount > 0)
                <span class="nav-cart-badge">{{ $cartCount }}</span>
            @endif
        </a>
        <a href="{{ route('shop.checkout') }}" class="{{ request()->routeIs('shop.checkout')   ? 'nav-active' : '' }}">Checkout</a>
        <a href="{{ route('login') }}"         class="mobile-nav-cta {{ request()->routeIs('login') ? 'nav-active' : '' }}">Login</a>
    </div>
</header>

<script>
    // Mobile menu toggle
    const navToggle = document.getElementById('nav-mobile-toggle');
    const navMenu   = document.getElementById('nav-mobile-menu');
    const iconHam   = document.getElementById('nav-icon-ham');
    const iconX     = document.getElementById('nav-icon-x');

    navToggle.addEventListener('click', () => {
        const isOpen = navMenu.classList.toggle('open');
        navToggle.setAttribute('aria-expanded', String(isOpen));
        iconHam.style.display = isOpen ? 'none'  : 'block';
        iconX.style.display   = isOpen ? 'block' : 'none';
    });

    // Shadow on scroll
    window.addEventListener('scroll', () => {
        document.getElementById('site-header')
            .classList.toggle('scrolled', window.scrollY > 8);
    }, { passive: true });
</script>
