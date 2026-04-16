<!-- ============================================================
     TOPBAR
     ============================================================ -->
<div id="topbar">
    <div class="container-fluid px-4">
        <div class="row align-items-center">
            <div class="col-auto">
                <div class="d-flex gap-4"><a href="#"
                        style="color:var(--white);text-decoration:none;font-weight:500;"><i
                            class="bi bi-geo-alt me-1"></i>Find a Store</a></div>
            </div>
            <div class="col text-center promo d-none d-md-block">
                {{-- <span>15% Off $99+</span> when you buy online &amp; pick up in-store --}}
            </div>
            <div class="col-auto">
                <a class="currency-select" style="color:var(--white);text-decoration:none;font-weight:500;"><i
                        class="bi bi-globe me-1"></i></a>

                <select id="languageSelect" name="language" class="btn-select" onchange="changeLanguage(this.value)">
                    <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>EN 🇺🇸</option>
                    <option value="km" {{ app()->getLocale() == 'km' ? 'selected' : '' }}>KH 🇰🇭</option>
                </select>

            </div>
        </div>
    </div>
</div>

<!-- ============================================================
     NAVBAR
     ============================================================ -->
<nav id="mainNav" class="navbar navbar-expand-lg">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="#">{{ $shopDetail->name }}<span>'s</span></a>

        <a style="color:var(--muted);font-weight:400" class="navbar-toggler border-0" type="button"
            data-bs-toggle="collapse" data-bs-target="#navCollapse">
            <i class="bi bi-list"></i>
        </a>

        <div class="collapse navbar-collapse" id="navCollapse">
            <ul class="navbar-nav mx-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/', 'shop') ? 'active' : '' }}" href="{{ url('/') }}">
                            {{ __('messages.home') }}
                    </a>
                </li>



                <li class="nav-item">
                    <a class="nav-link {{ request()->is('shop/products') ? 'active' : '' }}"
                        href="{{ url('shop/products') }}">
                        {{ __('messages.products') }}
                    </a>
                </li>


                <li class="nav-item nav-dropdown mega-category-dropdown">
                    <a href="#" class="nav-link dropdown-toggle-custom" style="text-align: center">
                        {{ __('messages.categories') }}
                        <i class="bi bi-chevron-down dropdown-arrow"></i>
                    </a>

                    <div class="mega-dropdown-menu">
                        <div class="mega-dropdown-grid">
                            @foreach($categories as $category)
                                <div class="mega-column">

                                    {{-- Clickable title --}}
                                    <div class="mega-title" onclick="toggleSubcategories(this)">
{{ \Illuminate\Support\Str::limit($category->name, 10, '...') }}                                        <i class="bi bi-chevron-down sub-arrow"></i>
                                    </div>

                                    {{-- Subcategory list — NO inline style="display:none" here! --}}
                                    <div class="subcategory-list">
                                        @foreach($category->subcategories as $subcategory)
                                            <a href="javascript:void(0)" onclick="goToSubCategory({{ $subcategory->id }})">
                                                {{ $subcategory->name }}
                                            </a>
                                        @endforeach
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    </div>
                </li>


                {{-- <li class="nav-item">
                    <a class="nav-link {{ request()->is('shop/checkout') ? 'active' : '' }}"
                        href="{{ url('shop/checkout') }}">
                        checkout
                    </a>
                </li> --}}

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('shop/about-us') ? 'active' : '' }}"
                        href="{{ url('shop/about-us') }}">
                        {{ __('messages.about_us') }}
                    </a>
                </li>


                <li class="nav-item">
                    <a class="nav-link {{ request()->is('shop/contact-us') ? 'active' : '' }}"
                        href="{{ url('shop/contact-us') }}">
                        {{ __('messages.contact_us') }}
                    </a>
                </li>
            </ul>

            <!-- Icons -->
            <div class="nav-icons d-flex align-items-center">
                <div class="toggle-wrap me-1">
                    <input type="checkbox" id="themeToggle">
                    <label class="toggle-track" for="themeToggle">
                        <i class="bi bi-brightness-high-fill track-sun"></i>
                        <div class="toggle-thumb">
                            <i class="bi bi-brightness-high-fill" id="themeIcon"></i>
                        </div>
                        <i class="bi bi-moon-fill track-moon"></i>
                    </label>
                </div>


                <div class="account-dropdown">
                    <a href="#" title="Account"><i class="bi bi-person"></i></a>
                    <div class="account-panel">
                        <div class="acc-header">
                            <div class="acc-title">Welcome
                                Back</div>
                            <div class="acc-sub">Sign in to your Luxé
                                account</div>
                        </div>
                        <div class="acc-body">

                            <input type="email" id="email" name="email" class="acc-input" placeholder="Email address" />
                            <input type="password" id="password" name="password" class="acc-input"
                                placeholder="Password" />
                            <button class="btn-acc-login">Sign In <i class="bi bi-arrow-right ms-1"></i></button>

                            <div class="acc-divider"><span>or continue with</span></div>
                            <div class="acc-social"><button class="btn-social"><i class="bi bi-google"></i>
                                    Google</button><button class="btn-social"><i class="bi bi-apple"></i>
                                    Apple</button></div>
                            <div class="acc-divider" style="margin-top:8px"><span>new to
                                    luxé?</span></div>
                            <button class="btn-acc-register">Create Account</button>
                        </div>
                    </div>
                </div>


                @php
                    $isWishlist = request()->is('shop/wishlist*');
                @endphp

                <a href="{{ url('shop/wishlist') }}" class="{{ $isWishlist ? 'active' : '' }}">
                    <i class="bi {{ $isWishlist ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                </a>
                    <a href="#" id="cartToggle" title="Cart" style="position:relative" onclick="refreshCart()">
                        <i class="bi bi-bag"></i>
                    <span class="badge-dot" id="cart-count">0</span>
                </a>
            </div>
        </div>
    </div>
</nav>
