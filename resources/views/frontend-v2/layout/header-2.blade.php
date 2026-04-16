{{-- ============================================================
     TOPBAR
     ============================================================ --}}
<div id="topbar">
    <div class="topbar-inner">
        <a href="#" class="topbar-link">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
            Find a Store
        </a>

        <span class="topbar-promo d-none d-md-block">
            {{-- Free shipping on orders over $99 --}}
        </span>

        <div class="topbar-right">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 010 20M12 2a15.3 15.3 0 000 20"/></svg>
            <select id="languageSelect" class="topbar-select" onchange="changeLanguage(this.value)">
                <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>EN 🇺🇸</option>
                <option value="km" {{ app()->getLocale() == 'km' ? 'selected' : '' }}>KH 🇰🇭</option>
            </select>
        </div>
    </div>
</div>
{{-- ============================================================
     NAVBAR — Desktop
     ============================================================ --}}
<header id="mainNav">
    <div class="nav-inner">

        {{-- Brand --}}
        <a class="nav-brand" href="#">{{ $shopDetail->name }}<span class="brand-accent">'s</span></a>

        {{-- Desktop Nav Links --}}
        <nav class="desktop-nav">
            <a class="nav-link {{ request()->is('/') ? 'is-active' : '' }}" href="{{ url('/') }}">
                {{ __('messages.home') }}
            </a>
            <a class="nav-link {{ request()->is('shop/products') ? 'is-active' : '' }}" href="{{ url('shop/products') }}">
                {{ __('messages.products') }}
            </a>

            {{-- Mega Category Dropdown --}}
            <div class="mega-wrap">
                <button class="nav-link mega-trigger" type="button">
                    {{ __('messages.categories') }}
                    <svg class="chevron" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="mega-panel">
                    <div class="mega-grid">
                        @foreach($categories as $category)
                            <div class="mega-col">
                                <p class="mega-heading">{{ $category->name }}</p>
                                @foreach($category->subcategories as $subcategory)
                                    <a class="mega-item" href="javascript:void(0)" onclick="goToSubCategory({{ $subcategory->id }})">
                                        {{ $subcategory->name }}
                                    </a>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <a class="nav-link {{ request()->is('shop/about-us') ? 'is-active' : '' }}" href="{{ url('shop/about-us') }}">
                {{ __('messages.about_us') }}
            </a>
            <a class="nav-link {{ request()->is('shop/contact-us') ? 'is-active' : '' }}" href="{{ url('shop/contact-us') }}">
                {{ __('messages.contact_us') }}
            </a>
        </nav>

        {{-- Nav Icons (Desktop) --}}
        <div class="nav-actions">

            {{-- Theme Toggle --}}
            <label class="theme-toggle" title="Toggle theme">
                <input type="checkbox" id="themeToggle">
                <span class="toggle-track">
                    <svg class="icon-sun" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                    <span class="toggle-thumb"></span>
                    <svg class="icon-moon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                </span>
            </label>

            {{-- Account Dropdown --}}
            <div class="account-wrap">
                <button class="icon-btn account-trigger" title="Account">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </button>
                <div class="account-panel">
                    <p class="acc-title">Welcome back</p>
                    <p class="acc-sub">Sign in to your account</p>
                    <input type="email" class="acc-input" placeholder="Email address" />
                    <input type="password" class="acc-input" placeholder="Password" />
                    <button class="btn-primary-full">Sign In</button>
                    <div class="divider"><span>or</span></div>
                    <div class="social-row">
                        <button class="btn-social">
                            <svg width="16" height="16" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                            Google
                        </button>
                        <button class="btn-social">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
                            Apple
                        </button>
                    </div>
                    <div class="divider"><span>new here?</span></div>
                    <button class="btn-outline-full">Create Account</button>
                </div>
            </div>

            {{-- Wishlist --}}
            @php $isWishlist = request()->is('shop/wishlist*'); @endphp
            <a href="{{ url('shop/wishlist') }}" class="icon-btn {{ $isWishlist ? 'is-active' : '' }}" title="Wishlist">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="{{ $isWishlist ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.8"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
            </a>

            {{-- Cart --}}
            <a href="#" id="cartToggle" class="icon-btn cart-btn" title="Cart" onclick="refreshCartCount()">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                <span class="cart-badge" id="cart-count">0</span>
            </a>

            {{-- Mobile Hamburger --}}
            <button class="icon-btn mobile-menu-btn d-lg-none" type="button"
                data-bs-toggle="offcanvas" data-bs-target="#mobileNav" aria-controls="mobileNav">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
            </button>
        </div>

    </div>
</header>
{{-- ============================================================
     OFFCANVAS — Mobile Navigation
     ============================================================ --}}
<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileNav" aria-labelledby="mobileNavLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="mobileNavLabel">{{ $shopDetail->name }}<span class="brand-accent">'s</span></h5>
        <button type="button" class="oc-close" data-bs-dismiss="offcanvas" aria-label="Close">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>

    <div class="offcanvas-body">

        {{-- Mobile Nav Links --}}
        <nav class="mobile-nav">
            <a class="mob-link {{ request()->is('/') ? 'is-active' : '' }}" href="{{ url('/') }}"
                data-bs-dismiss="offcanvas">{{ __('messages.home') }}</a>

            <a class="mob-link {{ request()->is('shop/products') ? 'is-active' : '' }}" href="{{ url('shop/products') }}"
                data-bs-dismiss="offcanvas">{{ __('messages.products') }}</a>

            {{-- Mobile Accordion Categories --}}
            <div class="mob-accordion">
                <button class="mob-link mob-accordion-trigger" type="button"
                    data-bs-toggle="collapse" data-bs-target="#mobileCats" aria-expanded="false">
                    {{ __('messages.categories') }}
                    <svg class="chevron" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M6 9l6 6 6-6"/></svg>
                </button>
                <div class="collapse" id="mobileCats">
                    @foreach($categories as $category)
                        <div class="mob-cat-group">
                            <p class="mob-cat-heading">{{ $category->name }}</p>
                            @foreach($category->subcategories as $subcategory)
                                <a class="mob-sublink" href="javascript:void(0)"
                                    onclick="goToSubCategory({{ $subcategory->id }})"
                                    data-bs-dismiss="offcanvas">
                                    {{ $subcategory->name }}
                                </a>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>

            <a class="mob-link {{ request()->is('shop/about-us') ? 'is-active' : '' }}" href="{{ url('shop/about-us') }}"
                data-bs-dismiss="offcanvas">{{ __('messages.about_us') }}</a>

            <a class="mob-link {{ request()->is('shop/contact-us') ? 'is-active' : '' }}" href="{{ url('shop/contact-us') }}"
                data-bs-dismiss="offcanvas">{{ __('messages.contact_us') }}</a>
        </nav>

        {{-- Mobile Bottom Actions --}}
        <div class="mob-footer">
            <div class="mob-footer-row">
                <label class="theme-toggle" title="Toggle theme">
                    <input type="checkbox" id="themeToggleMobile">
                    <span class="toggle-track">
                        <svg class="icon-sun" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                        <span class="toggle-thumb"></span>
                        <svg class="icon-moon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/></svg>
                    </span>
                </label>

                <select class="topbar-select" onchange="changeLanguage(this.value)">
                    <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>EN 🇺🇸</option>
                    <option value="km" {{ app()->getLocale() == 'km' ? 'selected' : '' }}>KH 🇰🇭</option>
                </select>
            </div>

            <a href="{{ url('shop/wishlist') }}" class="mob-action-link" data-bs-dismiss="offcanvas">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                Wishlist
            </a>

            <a href="#" class="mob-action-link" onclick="refreshCartCount()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                Cart <span id="cart-count-mobile" class="cart-badge-inline">0</span>
            </a>
        </div>

    </div>
</div>
