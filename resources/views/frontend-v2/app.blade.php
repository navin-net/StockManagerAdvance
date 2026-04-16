<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title')-{{ $shopDetail->name }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 5.3 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
    @php
        $shopName = $shopDetail->name ?? 'My Shop';
    @endphp
    <style>
        /* .page-hero::after {
            content: "{{ $shopName }}";
        } */
        .modal-header {
            border-bottom: none;
        }
    </style>
    @stack('style')

</head>
<body>

    @include('frontend-v2.layout.header')
    @yield('content')
    @include('frontend-v2.layout.footer')
    @stack('scripts')


</body>

</html>
