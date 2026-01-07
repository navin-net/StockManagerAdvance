<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php $shopInfo = \App\Models\Shop::first(); @endphp
    <link rel="icon"
        href="{{ $shopInfo && $shopInfo->logo_shop ? asset('storage/' . $shopInfo->logo_shop) : asset('favicon.ico') }}">

    <title>@yield('title', 'Stock Management')</title>

    <!-- Bootstrap 5.3 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-collapsed: 90px;
            --header-height: 70px;
            --primary: #0ea5e9;
            --primary-light: #fff4e5;
            --text: #374151;
            --text-muted: #9ca3af;
            --header-bg: #020617;
            --border: #e5e7eb;
            --hover: #f8fafc;
            --bg-color: #0f172a;
            --text-color: #e2e8f0;
        }

        [data-bs-theme="dark"] {
            --text: #f1f5f9;
            --text-muted: #94a3b8;
            --border: #334155;
            --hover: #1e293b;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: background-color var(--transition-speed),
                color var(--transition-speed);
            font-family: 'Segoe UI', sans-serif;
            transition: background 0.3s;
        }

        /* Header */
        .app-header {
            height: var(--header-height);
            border-bottom: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .logo {
            font-weight: 700;
            color: var(--primary);
            font-size: 1.5rem;
        }

        /* Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: #020617;
            border-right: 1px solid var(--border);
            position: fixed;
            top: var(--header-height);
            left: 0;
            bottom: 0;
            overflow-y: auto;
            transition: width 0.3s ease;
            z-index: 1020;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }

        .sidebar.collapsed .sidebar-text,
        .sidebar.collapsed .section-title {
            opacity: 0;
            visibility: hidden;
        }

        .nav-link {
            color: var(--text-color);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 4px 12px;
            display: flex;
            align-items: center;
            transition: all 0.2s;
            position: relative;
        }

        .nav-link i {
            font-size: 1.2rem;
            width: 30px;
            text-align: center;
            flex-shrink: 0;
        }

        .sidebar-text {
            margin-left: 12px;
            white-space: nowrap;
            transition: opacity 0.2s;
        }

        .nav-link:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .nav-link.active {
            background: var(--primary-light);
            color: var(--primary);
            font-weight: 600;
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--primary);
            border-radius: 4px;
        }

        .section-title {
            padding: 20px 20px 8px;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            transition: opacity 0.3s;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            min-height: calc(100vh - var(--header-height));
            transition: margin-left 0.3s ease;
            padding: 2rem;
            background-color: var(--bg-color);
        }

        /* Overlay for mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1010;
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* Mobile */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width);
                z-index: 1040;
                box-shadow: 4px 0 20px rgba(0, 0, 0, 0.2);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0 !important;
            }

            .desktop-only {
                display: none !important;
            }
        }

        .btn-primary {
            background: linear-gradient(135deg, #4a90e2, #357abd);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #357abd, #2a5d8f);
        }

        .btn-info {
            background: linear-gradient(135deg, #5bc0de, #31b0d5);
            color: white;
        }

        .btn-info:hover {
            background: linear-gradient(135deg, #31b0d5, #2390b0);
        }

        .btn-warning {
            background: linear-gradient(135deg, #f0ad4e, #ec971f);
            color: white;
        }

        .btn-warning:hover {
            background: linear-gradient(135deg, #ec971f, #d58512);
        }

        .btn-danger {
            background: linear-gradient(135deg, #d9534f, #c9302c);
            color: white;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #c9302c, #a02622);
        }

        /* Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 10px;
        }
    </style>

    @yield('styles')
</head>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Header -->
    <header class="app-header px-4">
        <div class="d-flex align-items-center justify-content-between h-100">
            <div class="d-flex align-items-center gap-4">

                <button id="sidebarToggle" class="btn btn-link p-0 text-body" type="button"
                    aria-label="Toggle sidebar">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <a href="{{ route('dashboard') }}" class="logo text-decoration-none">
                    <i class="bi bi-bag-fill me-2"></i>StockMangment
                </a>
            </div>

            <div class="d-flex align-items-center gap-3">
                <!-- Search, Language, Theme, Alerts, POS, Profile (same as yours) -->
                <!-- Keeping your original header items -->
                {{-- @include('layouts.partials.header-right') --}}
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="p-4 border-bottom">
            <div class="d-flex align-items-center mb-3">
                <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('assets/images/default-avatar.png') }}"
                    class="rounded-circle me-3" width="50" height="50">
                <div class="sidebar-text">
                    <div class="fw-bold">{{ Auth::user()->name }}</div>
                    <small class="text-muted">{{ Auth::user()->email }}</small>
                </div>
            </div>
        </div>

        <div class="p-3">
            <!-- Your existing menu items -->
            <div class="section-title">Main</div>
            <a href="{{ url('admin') }}" class="nav-link {{ request()->is('admin') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span class="sidebar-text">{{ __('messages.dashboard') }}</span>
            </a>
            <a href="{{ url('admin') }}" class="nav-link {{ request()->is('admin') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span class="sidebar-text">{{ __('messages.dashboard') }}</span>
            </a>
            <!-- Products, Sales, etc. (keep your collapse logic) -->
            <!-- ... your full menu ... -->
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/jquery-3.7.1.min.js') }}"></script>

    <script>
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const toggleBtn = document.getElementById('sidebarToggle');
        const overlay = document.getElementById('sidebarOverlay');

        function updateLayout() {
            if (window.innerWidth > 992) {
                const collapsed = sidebar.classList.contains('collapsed');
                mainContent.style.marginLeft = collapsed ? '70px' : '260px';
                sidebar.classList.remove('open');
                overlay.classList.remove('active');
            } else {
                mainContent.style.marginLeft = '0';
            }
        }

        toggleBtn.addEventListener('click', () => {
            if (window.innerWidth > 992) {
                sidebar.classList.toggle('collapsed');
            } else {
                sidebar.classList.toggle('open');
                overlay.classList.toggle('active');
            }
            updateLayout();
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        });

        window.addEventListener('resize', updateLayout);
        updateLayout(); // Initial call
    </script>

    @stack('scripts')
</body>

</html>
