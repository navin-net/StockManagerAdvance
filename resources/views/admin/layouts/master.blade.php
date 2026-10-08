<!doctype html>
<html lang="{{ app()->getLocale() }}" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $shopInfo = \App\Models\Shop::first();
        $isPos = request()->is('admin/pos*');
    @endphp

    @if ($shopInfo && $shopInfo->logo_shop)
        <link rel="icon" href="{{ asset('storage/' . $shopInfo->logo_shop) }}" type="image/x-icon">
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @endif

    <script>
        window.APP_PATH = "/StockManagerAdvance"; // This would be dynamic in production

        // Apply the saved theme before first paint (prevents a dark/light flash).
        (function () {
            var t = localStorage.getItem('theme') || 'dark';
            if (t === 'system') {
                t = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            document.documentElement.setAttribute('data-bs-theme', t);
        })();
    </script>

    <title>@yield('title')-{{ $shopInfo->name_shop ?? config('app.name') }}</title>

    <!-- Fonts (Latin + Khmer) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">

    <!-- CSS Files -->
    <link href="{{ asset('backend/css/bootstrap.min.css') }}" rel="stylesheet">
    {{-- Select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
          rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('backend/DataTables/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/style-custom.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    {{-- New shell design: must stay AFTER style-custom.css --}}
    <link rel="stylesheet" href="{{ asset('backend/css/layout-v2.css') }}?v={{ @filemtime(public_path('backend/css/layout-v2.css')) }}">

    <style>
        #paymentMethodGroup button.pos-method-btn,
        #bankSelectorGroup button.pos-method-btn {
            border: 1px solid var(--wire2) !important;
            background: var(--ink3) !important;
            color: var(--chalk) !important;
            border-radius: 10px !important;
            padding: 8px 16px !important;
            font-size: 0.9rem !important;
            transition: background var(--trans), border-color var(--trans), color var(--trans) !important;
            box-shadow: none !important;
        }

        #paymentMethodGroup button.pos-method-btn:hover,
        #bankSelectorGroup button.pos-method-btn:hover {
            border-color: var(--brand) !important;
        }

        #paymentMethodGroup button.pos-method-btn.active,
        #bankSelectorGroup button.pos-method-btn.active {
            background: var(--brand) !important;
            color: #ffffff !important;
            border-color: var(--brand) !important;
            font-weight: 600 !important;
        }
    </style>

    @stack('styles')
</head>

<body class="{{ $isPos ? 'is-pos' : '' }}">

<div class="sidebar-overlay"></div>

@if (!request()->is('customer-display'))
    @include('admin.layouts.header')
@endif
@include('admin.layouts.slider')

<main class="{{ request()->routeIs('pos.*') ? 'main-content-pos' : 'main-content' }}">
    @yield('content')
</main>

@include('admin.layouts.footer')

@stack('scripts')

</body>

</html>
