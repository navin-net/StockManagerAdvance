<!DOCTYPE html>
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
    <link rel="stylesheet" href="{{ asset('backend/DataTables/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/css/style-custom.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    @yield('styles')
</head>
@php
    $isPos = request()->is('admin/pos*');
@endphp

<body class="{{ $isPos ? 'd-flex flex-column vh-100' : '' }}">
    {{-- <div class="{{ $isPos ? 'flex-grow-1 overflow-auto' : '' }}" data-bs-spy="scroll"> --}}

    <div class="sidebar-overlay"></div>

    <!-- Header -->
    @include('admin.layouts.header')
    <div id="loadingBar">
        <div id="loadingProgress"></div>
    </div>

    <!-- Sidebar -->
    @include('admin.layouts.slider')

    <!-- Main Content -->
    <main class="main-content {{ $isPos ? 'flex-grow-1 overflow-hidden d-flex flex-column' : '' }}">
        @yield('content')
    </main>


    @include('admin.layouts.footer')
    @stack('scripts')
</body>

</html>
