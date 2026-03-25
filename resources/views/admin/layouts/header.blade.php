<header class="app-header shadow-sm">
    <div class="container-fluid d-flex align-items-center justify-content-between h-100 px-3">
        <div class="d-flex align-items-center gap-3">

            <a href="{{ route('admin.dashboard') }}" class="text-decoration-none  desktop-only">
                <span class="fw-bold fs-4" style="color: #0ea5e9;">
                    {{ $shopInfo->name_shop ?? 'Stock Management System' }}
                </span>
            </a>
            <button id="sidebarToggle" class="btn btn-link p-0 text-body" type="button" aria-label="Toggle sidebar">
                <i class="bi bi-list fs-4"></i>
            </button>
        </div>
        @if (!Request::is('admin/pos*'))
            <div class="d-flex align-items-center gap-2 ">

                <!-- 🌐 Language Switch -->
                <div class="btn-group">
                    <button class="btn nbt-outline-custom dropdown-toggle" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <img src="{{ app()->getLocale() == 'en' ? asset('flag/gb-eng.jpg') : asset('flag/kh.jpg') }}"
                            alt="Lang" width="20" height="14">
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item d-flex align-items-center {{ app()->getLocale() == 'en' ? 'active' : '' }}"
                                href="/lang/en">
                                <img src="{{ asset('flag/gb-eng.jpg') }}" class="me-2" width="20">
                                {{ __('messages.english') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center {{ app()->getLocale() == 'km' ? 'active' : '' }}"
                                href="/lang/km">
                                <img src="{{ asset('flag/kh.jpg') }}" class="me-2" width="20">
                                {{ __('messages.khmer') }}
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- 🎨 Theme Switch -->
                <div class="btn-group">
                    <button class="btn btn-outline-custom dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-moon-stars me-2"></i>
                        <span id="currentThemeLabel">Dark</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
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
                    </ul>
                </div>

                <!-- ⚠️ Alerts -->
                <div class="btn-group">
                    <button class="btn btn-outline-danger position-relative dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                            id="cartBadge" style="display:none;">0</span>
                    </button>

                    <div class="dropdown-menu dropdown-menu-end p-2" style="width: 300px;">
                        <h6 class="dropdown-header text-danger">Alerts</h6>
                        <div id="alertList"></div>
                        <hr class="dropdown-divider">
                        <a class="dropdown-item text-center" href="{{ url('/products') }}">
                            {{ __('messages.see_all') }}
                        </a>
                    </div>
                </div>
                <div class="btn-group">
                    <a href="{{ url('/') }}" class="btn btn-primary" title="{{ __('messages.shop') }}">
                        <i class="bi bi-shop-window"></i>
                    </a>
                </div>
                <!-- POS Button -->
                <div class="btn-group">
                    <a href="{{ route('pos.index') }}" class="btn btn-success">
                        <i class="bi bi-grid me-2"></i> POS
                    </a>
                </div>

                <!-- 👤 User Menu -->
                <div class="btn-group">
                    <button class="btn btn-outline-custom dropdown-toggle d-flex align-items-center"
                        data-bs-toggle="dropdown">
                        <img src="{{ Auth::user() && Auth::user()->avatar
                            ? asset('storage/' . Auth::user()->avatar)
                            : asset('assets/img/profile-img.jpg') }}"
                            class="rounded-circle me-2" width="32" height="32">
                        {{ Auth::user()->first_name }}
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.edit', Auth::user()->id) }}">
                                <i class="bi bi-person me-2"></i> {{ __('messages.profile') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item"
                                href="{{ route('profile.edit', ['id' => Auth::user()->id, 'tab' => 'change_password']) }}">
                                <i class="bi bi-lock me-2"></i> {{ __('messages.change_password') }}
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                            </form>
                            <a class="dropdown-item text-danger" href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right me-2"></i> {{ __('messages.logout') }}
                            </a>
                        </li>
                    </ul>
                </div>

            </div>
        @endunless
        @if (request()->is('admin/pos*'))
            <div class="d-flex gap-1 align-items-center desktop-only">


                <div class="dropdown">
                    <button
                        class="btn btn-outline-custom dropdown-toggle-color d-flex align-items-center justify-content-between"
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
                    </ul>
                </div>
                <!-- 🌐 Language Switch -->
                <div class="dropdown">
                    <button class="btn btn-outline-custom d-flex align-items-center justify-content-center"
                        type="button" data-bs-toggle="dropdown" aria-expanded="false"
                        style="width: 40px; height: 38px;">
                        <img src="{{ app()->getLocale() == 'en' ? asset('flag/gb-eng.jpg') : asset('flag/kh.jpg') }}"
                            alt="Lang" width="20" height="14">
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li>
                            <a class="dropdown-item d-flex align-items-center {{ app()->getLocale() == 'en' ? 'active' : '' }}"
                                href="/lang/en">
                                <img src="{{ asset('flag/gb-eng.jpg') }}" alt="English" class="me-2"
                                    width="20" height="14">
                                <span>{{ __('messages.english') }}</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center {{ app()->getLocale() == 'km' ? 'active' : '' }}"
                                href="/lang/km">
                                <img src="{{ asset('flag/kh.jpg') }}" alt="Khmer" class="me-2"
                                    width="20" height="14">
                                <span>{{ __('messages.khmer') }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <a href="{{ url('admin/pos/customer-display') }}" type="button" target="_blank"
                    class="btn btn-primary text-white d-flex align-items-center justify-content-center"
                    title="{{ __('messages.customer') }}" style="width: 40px; height: 38px;">
                    <i class="bi bi-pc-display-horizontal"></i>
                </a>
                <!-- Alerts / Cart -->
                <div class="dropdown">
                    <button class="btn btn-danger position-relative" id="cartIcon" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <span
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                            id="cartBadge" style="display: none;">0</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end p-2" style="width: 300px;" id="alertContainer">
                        <h6 class="dropdown-header text-danger">
                            <div id="alertList"></div>
                        </h6>
                        <hr class="dropdown-divider">
                        <a class="dropdown-item text-center"
                            href="{{ url('/products') }}">{{ __('messages.see_all') }}</a>
                    </div>
                </div>

                <!-- Info Button -->
                <button type="button"
                    class="btn btn-info text-white d-flex align-items-center justify-content-center"
                    title="{{ __('messages.register_detail') }}" style="width: 40px; height: 38px;">
                    <i class="bi bi-info-circle"></i>
                </button>

                <!-- Add Expense -->
                <button type="button"
                    class="btn btn-warning text-white d-flex align-items-center justify-content-center"
                    title="{{ __('messages.add_expense') }}" style="width: 40px; height: 38px;">
                    <i class="bi bi-dash-circle"></i>
                </button>

                <!-- Calculator -->
                <button type="button" class="btn btn-secondary d-flex align-items-center justify-content-center"
                    title="{{ __('messages.calculator') }}" onclick="toggleCalc()"
                    style="width: 40px; height: 38px;">
                    <i class="bi bi-calculator"></i>
                </button>

                <!-- View Bill -->


                <!-- Close Register -->
                <button type="button" class="btn btn-danger d-flex align-items-center justify-content-center"
                    title="{{ __('messages.close_register') }}" style="width: 40px; height: 38px;"
                    data-bs-toggle="modal" data-bs-target="#closePos">
                    <i class="bi bi-power"></i>
                </button>

                <!-- User Profile Dropdown -->
                <div class="dropdown desktop-only">
                    <button class="btn btn-outline-custom dropdown-toggle d-flex align-items-center"
                        type="button" data-bs-toggle="dropdown" aria-expanded="false"
                        style="height: 38px; padding: 0 12px;">
                        <img src="{{ Auth::check() && Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('assets/img/profile-img.jpg') }}"
                            alt="Profile" class="rounded-circle" width="32" height="32">
                        <span class="d-none d-md-inline">{{ Auth::user()?->first_name ?? 'User' }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('profile.edit') && request('tab') !== 'change_password' ? 'active' : '' }}"
                                href="{{ route('profile.edit', Auth::user()->id) }}">
                                <i class="bi bi-person me-2"></i>{{ __('messages.profile') }}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request('tab') === 'change_password' ? 'active' : '' }}"
                                href="{{ route('profile.edit', ['id' => Auth::user()->id, 'tab' => 'change_password']) }}">
                                <i class="bi bi-lock me-2"></i>{{ __('messages.change_password') }}
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                class="d-none">@csrf</form>
                            <a class="dropdown-item text-danger" href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right me-2"></i> {{ __('messages.logout') }}
                            </a>
                        </li>
                    </ul>
                </div>

            </div>
        @endif
</div>
</header>
