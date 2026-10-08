@php
    use Illuminate\Support\Facades\Lang;

    $prefix = 'admin';
    $sbUser = Auth::user();
    $isAdmin = $sbUser->group_id == 1;
    $locale = app()->getLocale();

    // Translate with a readable fallback when a key is not in lang/*/messages.php yet.
    $t = fn($key, $fallback) => Lang::has('messages.' . $key) ? __('messages.' . $key) : $fallback;

    // "is current page?"  Patterns are relative to /admin. Trailing * = wildcard.
    $on = fn($patterns) => collect((array) $patterns)->contains(
        fn($p) => request()->is(trim($prefix . '/' . $p, '/')),
    );

    /*
    |--------------------------------------------------------------------------
    | NAVIGATION MAP
    |--------------------------------------------------------------------------
    | i = bootstrap icon   t = messages.* key   x = raw text (no translation)
    | u = path under /admin   r = named route   m = active patterns
    | Group: id, open (patterns that keep it expanded), c = children
    */
    $nav = [
        [
            'items' => [['i' => 'speedometer2', 't' => 'dashboard', 'u' => '', 'm' => ['']]],
        ],
        [
            'label' => $t('menu_operations', 'Operations'),
            'items' => [
                [
                    'i' => 'box-seam', 't' => 'product', 'id' => 'shop-nav-products', 'open' => ['products*'],
                    'c' => [
                        ['i' => 'list-ul', 't' => 'products_list', 'u' => 'products', 'm' => ['products']],
                        ['i' => 'plus-circle', 't' => 'create', 'u' => 'products/create', 'm' => ['products/create']],
                        ['i' => 'file-earmark-arrow-up', 't' => 'import', 'u' => 'products/import', 'm' => ['products/import']],
                        ['i' => 'upc-scan', 't' => 'barcode-label', 'u' => 'products/code-label', 'm' => ['products/code-label']],
                        ['i' => 'wrench-adjustable', 't' => 'add_adjustment', 'u' => 'products/adjustment', 'm' => ['products/adjustment']],
                    ],
                ],
                [
                    'i' => 'receipt', 't' => 'sales', 'id' => 'shop-nav-sales', 'open' => ['sales*'],
                    'c' => [
                        ['i' => 'list-ul', 't' => 'list_sales', 'u' => 'sales', 'm' => ['sales']],
                        ['i' => 'basket2', 't' => 'pos_sales', 'u' => 'sales/pos', 'm' => ['sales/pos']],
                        ['i' => 'plus-circle', 't' => 'create', 'u' => 'sales/create', 'm' => ['sales/create']],
                    ],
                ],
                ['i' => 'cart4', 't' => 'purchases', 'u' => 'purchases', 'm' => ['purchases']],
            ],
        ],
        [
            'label' => $t('menu_management', 'Management'),
            'admin' => true,
            'items' => [
                [
                    'i' => 'people', 't' => 'users', 'id' => 'user-nav',
                    'open' => ['users*', 'billers*', 'suppliers*', 'customers*'],
                    'c' => [
                        ['i' => 'person', 't' => 'list_users', 'u' => 'users', 'm' => ['users']],
                        ['i' => 'person-plus', 't' => 'add_user', 'u' => 'users/create', 'm' => ['users/create']],
                        ['i' => 'buildings', 't' => 'list_billers', 'u' => 'billers', 'm' => ['billers']],
                        ['i' => 'building-add', 't' => 'add_billers', 'u' => 'billers/create', 'm' => ['billers/create']],
                        ['i' => 'truck', 't' => 'list_suppliers', 'u' => 'suppliers', 'm' => ['suppliers']],
                        ['i' => 'person-heart', 't' => 'customers_list', 'u' => 'customers', 'm' => ['customers']],
                    ],
                ],
                [
                    'i' => 'gear', 't' => 'system_settings', 'id' => 'settings-nav', 'open' => ['system_settings*'],
                    'c' => [
                        ['i' => 'shield-check', 't' => 'groups', 'u' => 'system_settings/groups', 'm' => ['system_settings/groups']],
                        ['i' => 'tags', 't' => 'brands', 'u' => 'system_settings/brands', 'm' => ['system_settings/brands']],
                        ['i' => 'house-door', 't' => 'warehouse', 'u' => 'system_settings/warehouse', 'm' => ['system_settings/warehouse']],
                        ['i' => 'sliders', 't' => 'qualitys_list', 'u' => 'system_settings/qualitys', 'm' => ['system_settings/qualitys']],
                        ['i' => 'bookmark', 't' => 'categories', 'u' => 'system_settings/categories', 'm' => ['system_settings/categories']],
                        ['i' => 'bookmarks', 't' => 'sub_categories', 'u' => 'system_settings/sub_category', 'm' => ['system_settings/sub_category']],
                        ['i' => 'rulers', 't' => 'units', 'u' => 'system_settings/units', 'm' => ['system_settings/units']],
                    ],
                ],
            ],
        ],
        [
            'label' => $t('menu_insights', 'Insights'),
            'items' => [
                [
                    'i' => 'graph-up-arrow', 't' => 'reports_list', 'id' => 'report-nav-slider', 'open' => ['reports*'],
                    'c' => [
                        ['i' => 'pie-chart', 't' => 'overview_chart', 'u' => 'reports', 'm' => ['reports']],
                        ['i' => 'calendar2-week', 't' => 'daily_sales', 'u' => 'reports/daily-sales', 'm' => ['reports/daily-sales']],
                        ['i' => 'calendar3', 't' => 'monthly_sales', 'u' => 'reports/monthly-sales', 'm' => ['reports/monthly-sales']],
                        ['i' => 'bar-chart-line', 'x' => 'Product Sales', 'r' => 'reports.product-sales', 'm' => ['reports/product-sales']],
                    ],
                ],
            ],
        ],
        [
            'label' => __('messages.shop'),
            'items' => [
                [
                    'i' => 'shop', 't' => 'shop_settings', 'id' => 'shop-nav-slider',
                    'open' => ['shop/settings*', 'shop/banners*'],
                    'c' => [
                        ['i' => 'gear', 't' => 'shop_settings', 'u' => 'shop/settings', 'm' => ['shop/settings*']],
                        ['i' => 'images', 't' => 'banner', 'u' => 'shop/banners', 'm' => ['shop/banners*']],
                    ],
                ],
                ['i' => 'briefcase', 't' => 'portfolio', 'u' => 'shop/portfolio', 'm' => ['shop/portfolio*']],
            ],
        ],
    ];

    $href = fn($n) => isset($n['r']) ? route($n['r']) : url(trim($prefix . '/' . ($n['u'] ?? ''), '/'));
    $label = fn($n) => $n['x'] ?? __('messages.' . $n['t']);
    $avatar = $sbUser->avatar ? asset('storage/' . $sbUser->avatar) : asset('assets/images/default-avatar.png');
@endphp

<aside class="sidebar" id="app-sidebar" aria-label="Main navigation">

    {{-- ===================== BRAND ===================== --}}
    <a href="{{ route('admin.dashboard') }}" class="sb-brand">
        <span class="sb-mark">
            @if ($shopInfo && $shopInfo->logo_shop)
                <img src="{{ asset('storage/' . $shopInfo->logo_shop) }}" alt="">
            @else
                <i class="bi bi-grid-fill"></i>
            @endif
        </span>
        <span class="sb-name">
            {{ $shopInfo->name_shop ?? 'Stock Management System' }}
{{--            <small>{{ $t('menu_console', 'Management console') }}</small>--}}
        </span>
    </a>

    {{-- ===================== NAVIGATION ===================== --}}
    <nav class="sb-scroll">
        @foreach ($nav as $section)
            @continue(!empty($section['admin']) && !$isAdmin)

            @if (!empty($section['label']))
                <div class="sb-label">{{ $section['label'] }}</div>
            @endif

            @foreach ($section['items'] as $item)
                @if (isset($item['c']))
                    @php $open = $on($item['open']); @endphp
                    <a class="nav-link {{ $open ? 'parent-active' : 'collapsed' }}" title="{{ $label($item) }}" data-bs-toggle="collapse"
                        href="#{{ $item['id'] }}" role="button" aria-expanded="{{ $open ? 'true' : 'false' }}"
                        aria-controls="{{ $item['id'] }}">
                        <i class="bi bi-{{ $item['i'] }}"></i>
                        <span>{{ $label($item) }}</span>
                        <i class="bi bi-chevron-down toggle-icon"></i>
                    </a>
                    <div id="{{ $item['id'] }}" class="collapse {{ $open ? 'show' : '' }} sb-sub">
                        @foreach ($item['c'] as $child)
                            <a href="{{ $href($child) }}" class="nav-link {{ $on($child['m']) ? 'active' : '' }}">
                                <i class="bi bi-{{ $child['i'] }}"></i>
                                <span>{{ $label($child) }}</span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <a href="{{ $href($item) }}" title="{{ $label($item) }}" class="nav-link {{ $on($item['m']) ? 'active' : '' }}">
                        <i class="bi bi-{{ $item['i'] }}"></i>
                        <span>{{ $label($item) }}</span>
                    </a>
                @endif
            @endforeach
        @endforeach
    </nav>

    {{-- ===================== FOOTER: tools + user ===================== --}}
    <div class="sb-foot">

        {{-- Language / theme live in the header on desktop; show them here on small screens --}}
        <div class="sb-tools">
            <div class="sb-pills" role="group" aria-label="Language">
                <a href="/lang/en" class="{{ $locale == 'en' ? 'active' : '' }}">{{ __('messages.english') }}</a>
                <a href="/lang/km" class="{{ $locale == 'km' ? 'active' : '' }}">{{ __('messages.khmer') }}</a>
            </div>
            <div class="sb-pills" role="group" aria-label="Theme">
                <a href="#" class="dropdown-item" data-theme="dark"><i class="bi bi-moon-stars me-1"></i>{{ __('messages.dark') }}</a>
                <a href="#" class="dropdown-item" data-theme="light"><i class="bi bi-sun me-1"></i>{{ __('messages.light') }}</a>
            </div>
        </div>

        <div class="sb-user">
            <img src="{{ $avatar }}" alt="">
            <a href="{{ route('profile.edit', $sbUser->id) }}" class="who">
                <b>{{ $sbUser->name }}</b>
                <span>{{ $sbUser->email }}</span>
            </a>
            <button type="button" class="sb-out" title="{{ __('messages.logout') }}"
                aria-label="{{ __('messages.logout') }}"
                onclick="document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i>
            </button>
        </div>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    </div>
</aside>
