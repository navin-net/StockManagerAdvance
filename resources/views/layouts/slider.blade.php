@php
    $userId = Auth::user()->id;
    $prefix = 'admin';
@endphp

<aside class="sidebar">
    <div class="sidebar-user">
        <div class="d-flex align-items-center mb-3">
            <img src="{{ Auth::user()->profile && Auth::user()->profile->image
                ? asset('storage/' . Auth::user()->profile->image)
                : asset('assets/img/profile-img.jpg') }}"
                alt="Profile"
                class="rounded-circle me-2"
                width="40" height="40">
            <div>
                <h6 class="mb-0">{{ Auth::user()->name }}</h6>
            </div>
        </div>

        {{-- Language switcher --}}
        <div class="dropdown mb-3 w-100">
            <button class="btn btn-outline-custom dropdown-toggle d-flex align-items-center justify-content-between w-100"
                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <div>
                    {{-- <i class="bi bi-flag me-2"></i> --}}

                    <span>{{ app()->getLocale() == 'en' ? __('messages.english') : __('messages.khmer') }}</span>
                </div>
            </button>
            <ul class="dropdown-menu w-100">
                <li>
                    <a class="dropdown-item d-flex align-items-center {{ app()->getLocale() == 'en' ? 'active' : '' }}"
                        href="{{ route('language.switch', ['language' => 'en']) }}">
                        <i class="bi bi-flag me-2"></i>{{ __('messages.english') }}
                    </a>
                </li>
                <li>
                    <a class="dropdown-item d-flex align-items-center {{ app()->getLocale() == 'km' ? 'active' : '' }}"
                        href="{{ route('language.switch', ['language' => 'km']) }}">
                        <i class="bi bi-flag me-2"></i>{{ __('messages.khmer') }}
                    </a>
                </li>
            </ul>
        </div>

        <!-- Theme Dropdown -->
        <div class="dropdown mb-3 w-100">
            <button class="btn btn-outline-custom dropdown-toggle d-flex align-items-center justify-content-between w-100"
                type="button" id="themeDropdownButton" data-bs-toggle="dropdown" aria-expanded="false">
                <div>
                    <i class="bi bi-moon-stars me-2"></i>
                    <span id="currentThemeLabel">Dark</span>
                </div>
            </button>
            <ul class="dropdown-menu w-100">
                <li>
                    <a class="dropdown-item d-flex align-items-center" href="#" data-theme="dark">
                        <i class="bi bi-moon-stars me-2"></i> {{ __('messages.dark') }}
                    </a>
                </li>
                <li>
                    <a class="dropdown-item d-flex align-items-center" href="#" data-theme="light">
                        <i class="bi bi-sun me-2"></i> {{ __('messages.light') }}
                    </a>
                </li>
                {{-- <li>
                    <a class="dropdown-item d-flex align-items-center" href="#" data-theme="system">
                        <i class="bi bi-circle-half me-2"></i> System
                    </a>
                </li> --}}
            </ul>
        </div>



        {{-- Search --}}
        {{-- <div class="input-group mb-3">
            <span class="input-group-text bg-transparent border-end-0">
                <i class="bi bi-search"></i>
            </span>
            <input type="text" class="form-control bg-transparent border-start-0" placeholder="Search...">
        </div> --}}
    </div>

    <div class="p-3">
        {{-- Dashboard --}}
        <a href="{{ url($prefix) }}"
            class="nav-link d-flex align-items-center {{ request()->is($prefix) ? 'active' : '' }}">
            <i class="bi bi-speedometer"></i>
            <span>{{ __('messages.dashboard') }}</span>
        </a>

        {{-- Products --}}
        <a class="nav-link d-flex align-items-center justify-content-between {{ request()->is($prefix.'/products*') ? 'active' : '' }}"
            data-bs-toggle="collapse" href="#shop-nav-products"
            aria-expanded="{{ request()->is($prefix.'/products*') ? 'true' : 'false' }}" aria-controls="shop-nav-products">
            <div>
                <i class="bi bi-box-seam-fill"></i>
                <span>{{ __('messages.product') }}</span>
            </div>
            <i class="bi bi-chevron-down"></i>
        </a>
        <div id="shop-nav-products" class="collapse {{ request()->is($prefix.'/products*') ? 'show' : '' }} ps-4 mt-1">
            <a href="{{ url($prefix.'/products') }}"
                class="nav-link d-flex align-items-center {{ request()->is($prefix.'/products') ? 'active' : '' }}">
                <i class="bi bi-list"></i>
                <span>{{ __('messages.list_products') }}</span>
            </a>
            <a href="{{ url($prefix.'/products/create') }}"
                class="nav-link d-flex align-items-center {{ request()->is($prefix.'/products/create') ? 'active' : '' }}">
                <i class="bi bi-plus-circle"></i>
                <span>{{ __('messages.create') }}</span>
            </a>
        </div>

        {{-- Sales --}}
        <a class="nav-link d-flex align-items-center justify-content-between {{ request()->is($prefix.'/sales*') ? 'active' : '' }}"
            data-bs-toggle="collapse" href="#shop-nav-sales"
            aria-expanded="{{ request()->is($prefix.'/sales*') ? 'true' : 'false' }}" aria-controls="shop-nav-sales">
            <div>
                <i class="bi bi-shop"></i>
                <span>{{ __('messages.sales') }}</span>
            </div>
            <i class="bi bi-chevron-down"></i>
        </a>
        <div id="shop-nav-sales" class="collapse {{ request()->is($prefix.'/sales*') ? 'show' : '' }} ps-4 mt-1">
            <a href="{{ url($prefix.'/sales') }}"
                class="nav-link d-flex align-items-center {{ request()->is($prefix.'/sales') ? 'active' : '' }}">
                <i class="bi bi-list"></i>
                <span>{{ __('messages.list_sales') }}</span>
            </a>
            <a href="{{ url($prefix.'/sales/create') }}"
                class="nav-link d-flex align-items-center {{ request()->is($prefix.'/sales/create') ? 'active' : '' }}">
                <i class="bi bi-plus-circle"></i>
                <span>{{ __('messages.create') }}</span>
            </a>
        </div>

        {{-- Purchases --}}
        <a href="{{ url($prefix.'/purchases') }}"
            class="nav-link d-flex align-items-center {{ request()->is($prefix.'/purchases') ? 'active' : '' }}">
            <i class="bi bi-cart4"></i>
            <span>{{ __('messages.purchases') }}</span>
        </a>

        {{-- Users / Admin Only --}}
        @if(Auth::user()->group_id == 1)
            @php
                $isUserNavActive = request()->is($prefix.'/users*') || request()->is($prefix.'/billers*') || request()->is($prefix.'/customers*');
            @endphp
            <div class="mb-2">
                <a class="nav-link d-flex align-items-center justify-content-between {{ $isUserNavActive ? 'active' : '' }}"
                    data-bs-toggle="collapse" href="#user-nav"
                    aria-expanded="{{ $isUserNavActive ? 'true' : 'false' }}"
                    aria-controls="user-nav">
                    <div>
                        <i class="bi bi-people"></i>
                        <span>{{ __('messages.users') }}</span>
                    </div>
                    <i class="bi bi-chevron-down"></i>
                </a>
                <div id="user-nav" class="collapse {{ $isUserNavActive ? 'show' : '' }} ps-4 mt-1">
                    <a href="{{ url($prefix.'/users') }}"
                        class="nav-link d-flex align-items-center {{ request()->is($prefix.'/users') ? 'active' : '' }}">
                        <i class="bi bi-person"></i>
                        <span>{{ __('messages.list_users') }}</span>
                    </a>
                    <a href="{{ url($prefix.'/users/create') }}"
                        class="nav-link d-flex align-items-center {{ request()->is($prefix.'/users/create') ? 'active' : '' }}">
                        <i class="bi bi-person-plus"></i>
                        <span>{{ __('messages.add_user') }}</span>
                    </a>
                    <a href="{{ url($prefix.'/billers') }}"
                        class="nav-link d-flex align-items-center {{ request()->is($prefix.'/billers') ? 'active' : '' }}">
                        <i class="bi bi-buildings"></i>
                        <span>{{ __('messages.list_billers') }}</span>
                    </a>
                    <a href="{{ url($prefix.'/billers/create') }}"
                        class="nav-link d-flex align-items-center {{ request()->is($prefix.'/billers/create') ? 'active' : '' }}">
                        <i class="bi bi-building-fill-add"></i>
                        <span>{{ __('messages.add_billers') }}</span>
                    </a>
                    <a href="{{ url($prefix.'/customers') }}"
                        class="nav-link d-flex align-items-center {{ request()->is($prefix.'/customers') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i>
                        <span>{{ __('messages.customers') }}</span>
                    </a>
                </div>
            </div>

            {{-- Settings --}}
            @php
                $activeSettings = request()->is($prefix.'/system_settings*');
            @endphp

            <div class="mb-2">
                <a class="nav-link d-flex align-items-center justify-content-between {{ $activeSettings ? 'active' : '' }}"
                    data-bs-toggle="collapse" href="#settings-nav"
                    aria-expanded="{{ $activeSettings ? 'true' : 'false' }}"
                    aria-controls="settings-nav">
                    <div>
                        <i class="bi bi-gear"></i>
                        <span>{{ __('messages.system_settings') }}</span>
                    </div>
                    <i class="bi bi-chevron-down"></i>
                </a>

                <div id="settings-nav" class="collapse {{ $activeSettings ? 'show' : '' }} ps-4 mt-1">

                    <a href="{{ url($prefix.'/system_settings/groups') }}"
                        class="nav-link d-flex align-items-center {{ request()->is($prefix.'/system_settings/groups') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i>
                        <span>{{ __('messages.groups') }}</span>
                    </a>

                    <a href="{{ url($prefix.'/system_settings/brands') }}"
                        class="nav-link d-flex align-items-center {{ request()->is($prefix.'/system_settings/brands') ? 'active' : '' }}">
                        <i class="bi bi-tags"></i>
                        <span>{{ __('messages.brands') }}</span>
                    </a>

                    <a href="{{ url($prefix.'/system_settings/warehouse') }}"
                        class="nav-link d-flex align-items-center {{ request()->is($prefix.'/system_settings/warehouse') ? 'active' : '' }}">
                        <i class="bi bi-house"></i>
                        <span>{{ __('messages.warehouse') }}</span>
                    </a>

                    <a href="{{ url($prefix.'/system_settings/qualitys') }}"
                        class="nav-link d-flex align-items-center {{ request()->is($prefix.'/system_settings/qualitys') ? 'active' : '' }}">
                        <i class="bi bi-sliders"></i>
                        <span>{{ __('messages.qualitys_list') }}</span>
                    </a>

                    <a href="{{ url($prefix.'/system_settings/categories') }}"
                        class="nav-link d-flex align-items-center {{ request()->is($prefix.'/system_settings/categories') ? 'active' : '' }}">
                        <i class="bi bi-bookmark-fill"></i>
                        <span>{{ __('messages.categories') }}</span>
                    </a>

                    <a href="{{ url($prefix.'/system_settings/sub_category') }}"
                        class="nav-link d-flex align-items-center {{ request()->is($prefix.'/system_settings/sub_category') ? 'active' : '' }}">
                        <i class="bi bi-bookmarks-fill"></i>
                        <span>{{ __('messages.sub_categories') }}</span>
                    </a>

                    <a href="{{ url($prefix.'/system_settings/units') }}"
                        class="nav-link d-flex align-items-center {{ request()->is($prefix.'/system_settings/units') ? 'active' : '' }}">
                        <i class="bi bi-unity"></i>
                        <span>{{ __('messages.units') }}</span>
                    </a>

                </div>
            </div>

        @endif

        {{-- Reports --}}
        <div class="mb-2">
            <a class="nav-link d-flex align-items-center justify-content-between {{ request()->is($prefix.'/reports*') ? 'active' : '' }}"
                data-bs-toggle="collapse" href="#report-nav-slider"
                aria-expanded="{{ request()->is($prefix.'/reports*') ? 'true' : 'false' }}" aria-controls="report-nav-slider">
                <div>
                    <i class="bi bi-peace-fill"></i>
                    <span>{{ __('messages.report_list') }}</span>
                </div>
                <i class="bi bi-chevron-down"></i>
            </a>
            <div id="report-nav-slider" class="collapse {{ request()->is($prefix.'/reports*') ? 'show' : '' }} ps-4 mt-1">
                <a href="{{ url($prefix.'/reports') }}"
                    class="nav-link d-flex align-items-center {{ request()->is($prefix.'/reports') ? 'active' : '' }}">
                    <i class="bi bi-bag-dash-fill"></i>
                    <span>{{ __('messages.report_sales') }}</span>
                </a>
            </div>
        </div>

        {{-- Shop Settings --}}
        <div class="settings-heading">{{ __('messages.shop') }}</div>
        <div class="mb-2">
            <a class="nav-link d-flex align-items-center justify-content-between {{ request()->is('shop/settings*') ? 'active' : '' }}"
                data-bs-toggle="collapse" href="#shop-nav-slider"
                aria-expanded="{{ request()->is('shop/settings*') ? 'true' : 'false' }}" aria-controls="shop-nav-slider">
                <div>
                    <i class="bi bi-shop"></i>
                    <span>{{ __('messages.shop_settings') }}</span>
                </div>
                <i class="bi bi-chevron-down"></i>
            </a>

            <div id="shop-nav-slider" class="collapse {{ request()->is('shop/settings*') ? 'show' : '' }} ps-4 mt-1">
                <a href="{{ url('shop/settings') }}"
                    class="nav-link d-flex align-items-center {{ request()->is('shop/settings') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i>
                    <span>{{ __('messages.shop_settings') }}</span>
                </a>
                <a href="{{ url('shop/banners') }}"
                    class="nav-link d-flex align-items-center {{ request()->is('shop/banners') ? 'active' : '' }}">
                    <i class="bi bi-tags"></i>
                    <span>{{ __('messages.banner') }}</span>
                </a>
            </div>
        </div>



        {{-- Documentation --}}
        <a href="{{ url($prefix.'/documentation') }}"
            class="nav-link d-flex align-items-center {{ request()->is($prefix.'/documentation') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text"></i>
            <span>{{ __('messages.documentation') }}</span>
        </a>

        {{-- Help --}}
        <a href="{{ url($prefix.'/help') }}"
            class="nav-link d-flex align-items-center {{ request()->is($prefix.'/help') ? 'active' : '' }}">
            <i class="bi bi-question-circle"></i>
            <span>{{ __('messages.help_support') }}</span>
        </a>

        {{-- Mobile-only Account Links --}}
        <div class="d-block d-lg-none mt-3">
            <div class="settings-heading">{{ __('messages.account') }}</div>

            {{-- Profile --}}
            <a href="{{ route('profile.edit', Auth::user()->id) }}"
                class="nav-link d-flex align-items-center {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                <i class="bi bi-person"></i>
                <span>{{ __('messages.profile') }}</span>
            </a>


            {{-- Account Settings --}}
            {{-- <a href="{{ url($prefix.'/users/'.$userId.'/profile') }}"
                class="nav-link d-flex align-items-center {{ request()->is($prefix.'/users/'.$userId.'/profile') ? 'active' : '' }}">
                <i class="bi bi-gear"></i>
                <span>{{ __('messages.account_settings') }}</span>
            </a> --}}

            {{-- Logout --}}
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="nav-link d-flex align-items-center">
                <i class="bi bi-box-arrow-right"></i>
                <span>{{ __('messages.logout') }}</span>
            </a>
        </div>
    </div>
</aside>
