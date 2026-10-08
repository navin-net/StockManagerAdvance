@php
    $isPosPage = request()->is('admin/pos*');
    $hdUser = Auth::user();
    $hdAvatar = $hdUser && $hdUser->avatar ? asset('storage/' . $hdUser->avatar) : asset('assets/img/profile-img.jpg');
    $locale = app()->getLocale();
    $flag = $locale == 'en' ? asset('flag/gb-eng.jpg') : asset('flag/kh.jpg');
@endphp

<header class="app-header" id="site-header">
    <div class="hd-inner">

        {{-- ============================ LEFT ============================ --}}
        <div class="hd-left">
            <button id="sidebarToggle" class="hd-btn" type="button" aria-label="Toggle sidebar">
                <i class="bi bi-layout-sidebar-inset"></i>
            </button>

            @if ($isPosPage)
                {{-- The sidebar is hidden on POS, so keep a way back to the dashboard --}}
                <a href="{{ route('admin.dashboard') }}" class="hd-brand">
                    <span class="sb-mark" style="width:32px;height:32px"><i class="bi bi-grid-fill"></i></span>
                    <span class="d-none d-sm-inline">{{ $shopInfo->name_shop ?? 'Stock Management System' }}</span>
                </a>
            @else
                <div class="hd-heading">
                    <h1 class="hd-title">@yield('title', __('messages.dashboard'))</h1>
                    <span class="hd-sub d-none d-md-block">{{ $shopInfo->name_shop ?? 'Stock Management System' }}</span>
                </div>
            @endif
        </div>


        {{-- ======================== GLOBAL SEARCH ======================== --}}
        @unless ($isPosPage)
            <div class="hd-search d-none d-md-block" id="hdSearch" role="search">
                <i class="bi bi-search"></i>
                <input id="hdSearchInput" type="search" autocomplete="off"
                       placeholder="Jump to a page or search products"
                       aria-label="Search pages and products" aria-controls="hdResults" aria-expanded="false">
                <kbd>Ctrl K</kbd>
                <div class="hd-results" id="hdResults" role="listbox" hidden></div>
            </div>
        @endunless

        {{-- ============================ RIGHT =========================== --}}
        <div class="hd-right">

            {{-- ---------- POS-only tools (desktop) ---------- --}}
            @if ($isPosPage)
                <div class="d-none d-lg-flex align-items-center gap-2">
                    <button type="button" class="hd-btn" title="{{ __('messages.customer') }}"
                            aria-label="{{ __('messages.customer') }}" onclick="openCustomerDisplay()">
                        <i class="bi bi-pc-display-horizontal"></i>
                    </button>
                    <button type="button" class="hd-btn" title="{{ __('messages.register_detail') }}"
                            aria-label="{{ __('messages.register_detail') }}" data-bs-toggle="modal"
                            data-bs-target="#registerDetail">
                        <i class="bi bi-clipboard-data"></i>
                    </button>
                    <button type="button" class="hd-btn" title="{{ __('messages.add_cash') }}"
                            aria-label="{{ __('messages.add_cash') }}" data-bs-toggle="modal" data-bs-target="#addCash">
                        <i class="bi bi-database-fill-add"></i>
                    </button>
                    <button type="button" class="hd-btn" title="{{ __('messages.calculator') }}"
                            aria-label="{{ __('messages.calculator') }}" onclick="toggleCalc()">
                        <i class="bi bi-calculator"></i>
                    </button>
                    <button type="button" class="hd-btn danger" title="{{ __('messages.close_register') }}"
                            aria-label="{{ __('messages.close_register') }}" data-bs-toggle="modal"
                            data-bs-target="#closePos">
                        <i class="bi bi-power"></i>
                    </button>
                    <span class="hd-sep"></span>
                </div>

                {{-- ---------- POS tools (mobile overflow) ---------- --}}
                <div class="dropdown d-lg-none">
                    <button class="hd-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false"
                            aria-label="POS tools">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); openCustomerDisplay()"><i
                                    class="bi bi-pc-display-horizontal me-2"></i>{{ __('messages.customer') }}</a></li>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                               data-bs-target="#registerDetail"><i
                                    class="bi bi-clipboard-data me-2"></i>{{ __('messages.register_detail') }}</a></li>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addCash"><i
                                    class="bi bi-database-fill-add me-2"></i>{{ __('messages.add_cash') }}</a></li>
                        <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); toggleCalc()"><i
                                    class="bi bi-calculator me-2"></i>{{ __('messages.calculator') }}</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="#" data-bs-toggle="modal"
                               data-bs-target="#closePos"><i
                                    class="bi bi-power me-2"></i>{{ __('messages.close_register') }}</a></li>
                    </ul>
                </div>
            @else
                {{-- ---------- Open POS (admin pages) ---------- --}}
                <a href="{{ route('pos.index') }}" class="hd-pos">
                    <i class="bi bi-grid"></i><span class="d-none d-sm-inline">POS</span>
                </a>
            @endif

            {{-- ---------- Notification center (filled by the script in footer.blade.php) ---------- --}}
            <div class="dropdown" id="ntf">
                <button class="hd-btn" id="cartIcon" type="button" data-bs-toggle="dropdown"
                        data-bs-auto-close="outside" aria-expanded="false" aria-label="Notifications">
                    <i class="bi bi-bell"></i>
                    <span class="hd-badge" id="cartBadge">0</span>
                </button>
                <div class="dropdown-menu dropdown-menu-end ntf-menu" id="alertContainer">
                    <div class="ntf-head">
                        <h6>{{ __('messages.notifications') !== 'messages.notifications' ? __('messages.notifications') : 'Notifications' }}</h6>
                        <div class="ntf-tools">
                            <button type="button" id="ntfReadAll" class="ntf-link"><i class="bi bi-check2-all"></i> Mark all read</button>
                            <button type="button" id="ntfClear" class="ntf-link danger"><i class="bi bi-trash3"></i> Clear</button>
                        </div>
                    </div>
                    <div class="ntf-tabs" role="tablist">
                        <button type="button" class="on" data-tab="all">All <b data-n="all">0</b></button>
                        <button type="button" data-tab="stock">Stock <b data-n="stock">0</b></button>
                        <button type="button" data-tab="act">Activity <b data-n="act">0</b></button>
                    </div>
                    <div id="alertList" class="ntf-list" aria-live="polite"></div>
                    <a class="ntf-foot" href="{{ url('/products') }}">{{ __('messages.see_all') }} <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>

            {{-- ---------- Language (desktop) ---------- --}}
            <div class="dropdown desktop-only">
                <button class="hd-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false"
                        aria-label="Language">
                    <img src="{{ $flag }}" alt="Lang" width="20" height="14" class="rounded-1">
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item d-flex align-items-center {{ $locale == 'en' ? 'active' : '' }}"
                           href="/lang/en">
                            <img src="{{ asset('flag/gb-eng.jpg') }}" alt="English" class="me-2 rounded-1"
                                 width="20" height="14">
                            {{ __('messages.english') }}
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center {{ $locale == 'km' ? 'active' : '' }}"
                           href="/lang/km">
                            <img src="{{ asset('flag/kh.jpg') }}" alt="Khmer" class="me-2 rounded-1" width="20"
                                 height="14">
                            {{ __('messages.khmer') }}
                        </a>
                    </li>
                </ul>
            </div>

            {{-- ---------- Theme (desktop) ---------- --}}
            <div class="dropdown desktop-only">
                <button class="hd-btn wide dropdown-toggle-color" type="button" id="themeDropdownButton"
                        data-bs-toggle="dropdown" aria-expanded="false" aria-label="Theme">
                    <i class="bi bi-moon-stars"></i>
                    <span id="currentThemeLabel" class="d-none d-xl-inline">Dark</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#" data-theme="dark"><i
                                class="bi bi-moon-stars me-2"></i>{{ __('messages.dark') }}</a></li>
                    <li><a class="dropdown-item" href="#" data-theme="light"><i
                                class="bi bi-sun me-2"></i>{{ __('messages.light') }}</a></li>
                </ul>
            </div>

            {{-- ---------- User menu (desktop) ---------- --}}
            <div class="dropdown desktop-only">
                <button class="hd-btn hd-user" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ $hdAvatar }}" alt="Profile">
                    <span class="nm">{{ $hdUser?->first_name ?? 'User' }}</span>
                    <i class="bi bi-chevron-down small"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" style="min-width: 220px;">
                    <li class="px-3 py-2">
                        <div class="fw-bold">{{ $hdUser->name }}</div>
                        <div class="small text-muted">{{ $hdUser->email }}</div>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item {{ request()->routeIs('profile.edit') && request('tab') !== 'change_password' ? 'active' : '' }}"
                           href="{{ route('profile.edit', $hdUser->id) }}">
                            <i class="bi bi-person me-2"></i>{{ __('messages.profile') }}
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request('tab') === 'change_password' ? 'active' : '' }}"
                           href="{{ route('profile.edit', ['id' => $hdUser->id, 'tab' => 'change_password']) }}">
                            <i class="bi bi-lock me-2"></i>{{ __('messages.change_password') }}
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        {{-- #logout-form lives in the sidebar include --}}
                        <a class="dropdown-item text-danger" href="#"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-2"></i>{{ __('messages.logout') }}
                        </a>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    <div class="progress-track">
        <div class="progress-fill" id="scroll-bar"></div>
    </div>
</header>

@unless ($isPosPage)
    @push('scripts')
        <script>
            // Header quick search: jumps to any sidebar page, or falls back to a product search.
            document.addEventListener('DOMContentLoaded', () => {
                const box = document.getElementById('hdSearch');
                const input = document.getElementById('hdSearchInput');
                const list = document.getElementById('hdResults');
                if (!box || !input) return;

                const productsUrl = @json(url('admin/products'));
                const pages = [...document.querySelectorAll('.sidebar a.nav-link[href]:not([data-bs-toggle])')].map(a => {
                    const group = a.closest('.collapse')
                        ? document.querySelector('[href="#' + a.closest('.collapse').id + '"] span')?.textContent.trim() : '';
                    return { label: a.querySelector('span')?.textContent.trim() || '', group, icon: a.querySelector('i')?.className || '', href: a.href };
                }).filter(p => p.label);

                let rows = [], active = -1;
                const close = () => { list.hidden = true; input.setAttribute('aria-expanded', 'false'); active = -1; };
                const mark = () => rows.forEach((r, i) => r.el.classList.toggle('on', i === active));

                function render() {
                    const q = input.value.trim().toLowerCase();
                    if (!q) return close();
                    rows = pages.filter(p => (p.label + ' ' + p.group).toLowerCase().includes(q)).slice(0, 6)
                        .map(p => ({ href: p.href, label: p.label, sub: p.group || 'Page', icon: p.icon }));
                    rows.push({ href: productsUrl + '?search=' + encodeURIComponent(input.value.trim()),
                        label: 'Search products for "' + input.value.trim() + '"', sub: 'Products', icon: 'bi bi-search' });
                    list.innerHTML = '';
                    rows.forEach(r => {
                        const a = document.createElement('a');
                        a.href = r.href; a.className = 'hd-res'; a.setAttribute('role', 'option');
                        const i = document.createElement('i'); i.className = r.icon;
                        const t = document.createElement('span'); t.textContent = r.label;
                        const g = document.createElement('small'); g.textContent = r.sub;
                        a.append(i, t, g); list.appendChild(a); r.el = a;
                    });
                    active = 0; mark(); list.hidden = false; input.setAttribute('aria-expanded', 'true');
                }

                input.addEventListener('input', render);
                input.addEventListener('focus', render);
                input.addEventListener('keydown', e => {
                    if (e.key === 'ArrowDown') { e.preventDefault(); active = Math.min(rows.length - 1, active + 1); mark(); }
                    else if (e.key === 'ArrowUp') { e.preventDefault(); active = Math.max(0, active - 1); mark(); }
                    else if (e.key === 'Enter' && rows[active]) { e.preventDefault(); window.location = rows[active].href; }
                    else if (e.key === 'Escape') { close(); input.blur(); }
                });
                document.addEventListener('click', e => { if (!box.contains(e.target)) close(); });
                document.addEventListener('keydown', e => {
                    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') { e.preventDefault(); input.focus(); input.select(); }
                });
            });
        </script>
    @endpush
@endunless
