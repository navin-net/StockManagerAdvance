<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>@yield('title', 'Stock Management')</title>

    <!-- Bootstrap 5.3 CSS -->
    <link href="{{ asset('assets1/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Bootstrap Icons -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.7/dist/sweetalert2.min.css" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-width: 260px;
            --header-height: 60px;
            --primary-color: #0ea5e9;
            --transition-speed: 0.3s;
        }

        [data-bs-theme="light"] {
            --bg-color: #ffffff;
            --sidebar-bg: #f8fafc;
            --header-bg: #ffffff;
            --card-bg: #ffffff;
            --border-color: rgba(0, 0, 0, 0.1);
            --text-color: #1e293b;
            --text-muted: #64748b;
            --hover-bg: rgba(0, 0, 0, 0.05);
            --editor-bg: #ffffff;
            --editor-text: #1e293b;
            --editor-border: #d1d5db;
        }

        [data-bs-theme="dark"] {
            --bg-color: #0f172a;
            --sidebar-bg: #020617;
            --header-bg: #020617;
            --card-bg: #1e293b;
            --border-color: rgba(255, 255, 255, 0.1);
            --text-color: #e2e8f0;
            --text-muted: #94a3b8;
            --hover-bg: rgba(255, 255, 255, 0.05);
            --editor-bg: #1e293b;
            --editor-text: #e2e8f0;
            --editor-border: #374151;
        }

        /* CKEditor5 Custom Styling */
        .ck.ck-editor {
            border: 1px solid var(--editor-border) !important;
            border-radius: 0.375rem !important;
        }

        .ck.ck-editor__main > .ck-editor__editable {
            background-color: var(--editor-bg) !important;
            color: var(--editor-text) !important;
            border: none !important;
            min-height: 200px !important;
            padding: 1rem !important;
        }

        .ck.ck-editor__main > .ck-editor__editable:focus {
            border: none !important;
            box-shadow: 0 0 0 2px var(--primary-color) !important;
            outline: none !important;
        }

        .ck.ck-toolbar {
            background-color: var(--card-bg) !important;
            border-bottom: 1px solid var(--editor-border) !important;
            border-top: none !important;
            border-left: none !important;
            border-right: none !important;
        }

        .ck.ck-toolbar .ck-toolbar__items {
            flex-wrap: wrap;
        }

        .ck.ck-button {
            color: var(--text-color) !important;
        }

        .ck.ck-button:hover {
            background-color: var(--hover-bg) !important;
        }

        .ck.ck-button.ck-on {
            background-color: var(--primary-color) !important;
            color: white !important;
        }

        .ck.ck-dropdown__panel {
            background-color: var(--card-bg) !important;
            border: 1px solid var(--border-color) !important;
        }

        .ck.ck-list__item .ck-button {
            color: var(--text-color) !important;
        }

        .ck.ck-list__item .ck-button:hover {
            background-color: var(--hover-bg) !important;
        }

        /* Rest of your existing styles... */
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: background-color var(--transition-speed), color var(--transition-speed);
        }

        .app-header {
            height: var(--header-height);
            border-bottom: 1px solid var(--border-color);
            background-color: var(--header-bg);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            transition: background-color var(--transition-speed);
        }

        .sidebar {
            width: var(--sidebar-width);
            position: fixed;
            top: var(--header-height);
            left: 0;
            bottom: 0;
            background-color: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            transition: transform var(--transition-speed) ease, background-color var(--transition-speed);
            z-index: 1020;
            overflow-y: auto;
            transform: translateX(-100%);
        }

        .sidebar-visible .sidebar {
            transform: translateX(0);
        }

        .main-content {
            margin-left: 0;
            margin-top: var(--header-height);
            transition: margin-left var(--transition-speed) ease;
            flex: 1;
            background-color: var(--bg-color);
            min-height: calc(100vh - var(--header-height));
            padding: 1.5rem;
        }

        .nav-link {
            color: var(--text-color);
            border-radius: 4px;
            margin-bottom: 5px;
            transition: all 0.2s;
            padding: 0.5rem 1rem;
        }

        .nav-link:hover {
            background-color: var(--hover-bg);
            transform: translateX(5px);
            color: var(--primary-color);
        }

        .nav-link.active {
            background-color: rgba(14, 165, 233, 0.2);
            color: #38bdf8;
            font-weight: 500;
        }

        .nav-link i {
            width: 20px;
            text-align: center;
            margin-right: 10px;
            transition: transform 0.2s;
        }

        .nav-link:hover i {
            transform: scale(1.2);
        }

        .settings-heading {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            padding: 0.5rem 1rem;
            margin-top: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .card {
            border-radius: 8px;
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color var(--transition-speed), border-color var(--transition-speed), transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid var(--border-color);
            transition: border-color var(--transition-speed);
        }

        .theme-toggle {
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.375rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.2s, transform 0.2s;
            color: var(--text-color);
        }

        .theme-toggle:hover {
            background-color: var(--hover-bg);
            transform: rotate(15deg);
        }

        .btn-outline-custom {
            border: 1px solid var(--border-color);
            color: var(--text-color);
            background-color: transparent;
            transition: background-color 0.2s, color 0.2s, border-color 0.2s, transform 0.2s;
        }

        .btn-outline-custom:hover {
            background-color: var(--hover-bg);
            transform: translateY(-2px);
        }

        .section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            margin-bottom: 1rem;
        }

        .dropdown-menu {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            transition: background-color var(--transition-speed), border-color var(--transition-speed);
        }

        .dropdown-item {
            color: var(--text-color);
            transition: color var(--transition-speed), background-color var(--transition-speed), transform 0.2s;
        }

        .dropdown-item:hover {
            background-color: var(--hover-bg);
            transform: translateX(5px);
        }

        .search-bar {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            transition: background-color var(--transition-speed), box-shadow 0.2s;
        }

        .search-bar:hover {
            box-shadow: 0 0 0 2px rgba(14, 165, 233, 0.3);
        }

        [data-bs-theme="light"] .search-bar {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .stat-card-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            transition: transform 0.2s;
        }

        .card:hover .stat-card-icon {
            transform: scale(1.1) rotate(10deg);
        }

        .sidebar-user {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 1rem;
            display: none;
        }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1015;
            display: none;
            pointer-events: none;
        }

        .sidebar-visible .sidebar-overlay {
            display: block;
            pointer-events: auto;
        }

        /* DataTables customization */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_processing,
        .dataTables_wrapper .dataTables_paginate {
            color: var(--text-color) !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            color: var(--text-color) !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: var(--primary-color) !important;
            color: white !important;
            border-color: var(--primary-color) !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: var(--hover-bg) !important;
            color: var(--primary-color) !important;
        }

        table.dataTable tbody tr {
            background-color: var(--card-bg) !important;
            color: var(--text-color) !important;
        }

        table.dataTable.stripe tbody tr.odd {
            background-color: rgba(0, 0, 0, 0.05) !important;
        }

        [data-bs-theme="dark"] table.dataTable.stripe tbody tr.odd {
            background-color: rgba(255, 255, 255, 0.05) !important;
        }

        @media (max-width: 992px) {
            .main-content {
                padding: 1rem;
            }
            .sidebar-user {
                display: flex;
                flex-direction: column;
            }
            .desktop-only {
                display: none !important;
            }
            .app-header {
                height: 56px;
            }
            .main-content {
                margin-top: 56px;
            }
            .sidebar {
                top: 56px;
            }
            .card {
                margin-bottom: 1rem;
            }
            .card-body {
                padding: 1rem;
            }
            h1.display-6 {
                font-size: 1.75rem;
            }
        }

        .back-to-top {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 3.5rem;
            height: 3.5rem;
            opacity: 0;
            visibility: hidden;
            transform: translateY(1rem);
            transition: all 0.3s ease-in-out;
            z-index: 1050;
        }

        .back-to-top.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .back-to-top:hover {
            transform: translateY(-0.25rem);
        }
    </style>
    @yield('styles')
</head>

<body data-bs-spy="scroll">
    <div class="sidebar-overlay"></div>

    <!-- Header -->
    @include('layouts.header')

    <!-- Sidebar -->
    @include('layouts.slider')

    <!-- Main Content -->
    <main class="main-content">
        <div class="container-fluid p-0">
            @yield('content')
        </div>
    </main>

    <!-- Back to Top -->
    <button type="button"
        class="btn btn-primary back-to-top rounded-circle shadow d-flex align-items-center justify-content-center"
        id="backToTopBtn" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Back to top"
        aria-label="Back to top">
        <i class="bi bi-arrow-up fs-5"></i>
    </button>


    <!-- Bootstrap 5.3 JS -->
    <script src="{{ asset('assets1/bootstrap.bundle.min.js') }}"></script>
    

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.0/classic/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        document.querySelectorAll('.sidebar .nav-link[data-bs-toggle="collapse"]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const now = new Date();
            const offset = now.getTimezoneOffset() * 60000;
            const localISOTime = new Date(now - offset).toISOString().slice(0, 16);
            // document.getElementById('date').value = localISOTime;
        });

        document.addEventListener('DOMContentLoaded', () => {
            const html = document.documentElement;
            const body = document.body;
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.querySelector('.sidebar-overlay');

            const themeDropdownItems = document.querySelectorAll('.dropdown-item[data-theme]');
            const currentThemeLabels = document.querySelectorAll('#currentThemeLabel');
            const currentThemeIcons = document.querySelectorAll('.dropdown-toggle-color i');

            function setTheme(theme) {
                let finalTheme = theme;

                if (theme === 'system') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    finalTheme = prefersDark ? 'dark' : 'light';
                }

                html.setAttribute('data-bs-theme', finalTheme);
                localStorage.setItem('theme', theme);

                // Update all labels
                currentThemeLabels.forEach(label => {
                    if (theme === 'dark') label.textContent = @json(__('messages.dark'));
                    else if (theme === 'light') label.textContent = @json(__('messages.light'));
                    else label.textContent = @json(__('messages.system'));
                });

                // Update all icons
                currentThemeIcons.forEach(icon => {
                    if (theme === 'system') icon.className = 'bi bi-circle-half me-2 currentThemeIcon';
                    else if (theme === 'dark') icon.className = 'bi bi-moon-stars me-2 currentThemeIcon';
                    else icon.className = 'bi bi-sun me-2 currentThemeIcon';
                });
            }

            // --- Load Saved Theme ---
            const savedTheme = localStorage.getItem('theme') || 'dark';
            setTheme(savedTheme);

            // --- Dropdown click event ---
            themeDropdownItems.forEach(item => {
                item.addEventListener('click', e => {
                    e.preventDefault();
                    const selectedTheme = item.getAttribute('data-theme');
                    setTheme(selectedTheme);
                });
            });
            // --- Sidebar State Persistence ---
            const savedSidebarState = localStorage.getItem('sidebar-visible');
            if (savedSidebarState === 'true') body.classList.add('sidebar-visible');

            sidebarToggle?.addEventListener('click', () => {
                const isVisible = body.classList.toggle('sidebar-visible');
                localStorage.setItem('sidebar-visible', isVisible);
            });

            sidebarOverlay?.addEventListener('click', () => {
                body.classList.remove('sidebar-visible');
                localStorage.setItem('sidebar-visible', false);
            });

            // --- Auto-close sidebar on mobile when selecting theme ---
            const sidebarThemeDropdowns = document.querySelectorAll('.sidebar .dropdown-menu');
            sidebarThemeDropdowns.forEach(dropdown => {
                dropdown.addEventListener('click', () => {
                    if (window.innerWidth < 992) {
                        body.classList.remove('sidebar-visible');
                        localStorage.setItem('sidebar-visible', false);
                    }
                });
            });
        });


        document.addEventListener('DOMContentLoaded', () => {
            const alertList = document.getElementById('alertList');
            const cartBadge = document.getElementById('cartBadge');

            fetch('/product-alerts')
                .then(res => res.json())
                .then(products => {
                    alertList.innerHTML = ''; // clear old alerts

                    if (!products.length) {
                        alertList.innerHTML = '<div class="text-center small">Null</div>';
                        cartBadge.style.display = 'none';
                        return;
                    }

                    cartBadge.style.display = 'inline-block';
                    cartBadge.textContent = products.length;

                    products.forEach(product => {
                        // Create clickable alert item linking to product detail page
                        const alertItem = document.createElement('a');
                        alertItem.href = `/products/show/${product.id}`;
                        alertItem.className =
                            'dropdown-item d-flex justify-content-between align-items-center';
                        alertItem.textContent = product.name;

                        const badge = document.createElement('span');
                        badge.className = 'badge bg-danger rounded-pill';
                        badge.textContent = `Stock: ${product.stock_quantity}`;

                        alertItem.appendChild(badge);
                        alertList.appendChild(alertItem);
                    });
                })
                .catch(err => {
                    console.error('Failed to fetch product alerts:', err);
                    alertList.innerHTML = '<div class="text-danger small">Error loading alerts</div>';
                    cartBadge.style.display = 'none';
                });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const backToTopBtn = document.getElementById('backToTopBtn');
            const scrollThreshold = 300;

            // Initialize Bootstrap tooltip
            const tooltip = new bootstrap.Tooltip(backToTopBtn);

            // Show/hide button based on scroll position
            function toggleBackToTopButton() {
                if (window.pageYOffset > scrollThreshold) {
                    backToTopBtn.classList.add('show');
                } else {
                    backToTopBtn.classList.remove('show');
                }
            }

            // Smooth scroll to top
            function scrollToTop() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });

                // Hide tooltip after click
                tooltip.hide();
            }

            // Event listeners
            window.addEventListener('scroll', toggleBackToTopButton);
            backToTopBtn.addEventListener('click', scrollToTop);

            // Keyboard accessibility
            backToTopBtn.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    scrollToTop();
                }
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
