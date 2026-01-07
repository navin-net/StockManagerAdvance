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

    <title>@yield('title', 'Stock Management')</title>

    <!-- CSS Files -->
    <link href="{{ asset('assets/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/DataTables/datatables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style-custom.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.7/dist/sweetalert2.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">
    @yield('styles')
</head>

<body data-bs-spy="scroll">
    <div id="preloader">
        <div class="loader"></div>
    </div>
    <div class="sidebar-overlay"></div>

    <!-- Header -->
    @include('layouts.header')

    <!-- Sidebar -->
    @include('layouts.slider')

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    @include('layouts.footer')
    @stack('scripts')
</body>

</html>
