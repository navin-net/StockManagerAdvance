<!doctype html>
<html lang="{{ app()->getLocale() }}" data-bs-theme="auto">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php
        $shopInfo = \App\Models\Shop::first();
    @endphp

    @if ($shopInfo && $shopInfo->logo_shop)
        <link rel="icon" href="{{ asset('storage/' . $shopInfo->logo_shop) }}" type="image/x-icon">
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @endif
    <script>
        window.APP_PATH = "/StockManagerAdvance"; // This would be dynamic in production
    </script>

    <title>@yield('title')-{{ $shopInfo->name_shop }}</title>

    <!-- CSS Files -->
    <link href="{{ asset('backend/css/bootstrap.min.css') }}" rel="stylesheet">
    {{-- Select2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('backend/DataTables/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/style-custom.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        #paymentMethodGroup button.pos-method-btn,
        #bankSelectorGroup button.pos-method-btn {
            border: 1px solid var(--wire2) !important;
            background: var(--ink3) !important;
            color: var(--chalk) !important;
            border-radius: 8px !important;
            padding: 8px 16px !important;
            font-size: 0.9rem !important;
            transition: background var(--trans), border-color var(--trans), color var(--trans) !important;
            box-shadow: none !important;
        }

        #paymentMethodGroup button.pos-method-btn:hover,
        #bankSelectorGroup button.pos-method-btn:hover {
            border-color: var(--lime) !important;
        }

        #paymentMethodGroup button.pos-method-btn.active,
        #bankSelectorGroup button.pos-method-btn.active {
            background: var(--lime) !important;
            color: #ffffff !important;
            border-color: var(--lime) !important;
            font-weight: 600 !important;
        }
    </style>


    @stack('styles')
</head>
@php
    $isPos = request()->is('admin/pos*');
@endphp

<body>

    <div class="sidebar-overlay"></div>

    @if(!request()->is('customer-display'))
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
