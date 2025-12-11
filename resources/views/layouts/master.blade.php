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

    @if($shopInfo && $shopInfo->logo_shop)
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



    <script src="{{ asset('assets/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/DataTables/datatables.min.js')}}"></script>
    <!-- 8. SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#selectAll').on('click', function() {
                $('.Checkbox').prop('checked', $(this).prop('checked'));
                toggleBulkDeleteButton();
            });
       let editorInstance = null;
        document.querySelectorAll('.sidebar .nav-link[data-bs-toggle="collapse"]').forEach(link => {
            link.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            const html = document.documentElement;
            const body = document.body;
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarOverlay = document.querySelector('.sidebar-overlay');

            const themeDropdownItems = document.querySelectorAll('.dropdown-item[data-theme]');
            const currentThemeLabels = document.querySelectorAll('#currentThemeLabel');
            const currentThemeIcons = document.querySelectorAll('.dropdown-toggle-color i');

            // Theme Management
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

            // Load Saved Theme
            const savedTheme = localStorage.getItem('theme') || 'dark';
            setTheme(savedTheme);

            // Theme dropdown click events
            themeDropdownItems.forEach(item => {
                item.addEventListener('click', e => {
                    e.preventDefault();
                    const selectedTheme = item.getAttribute('data-theme');
                    setTheme(selectedTheme);
                });
            });

            // Sidebar State Persistence
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

            // Auto-close sidebar on mobile when selecting theme
            const sidebarThemeDropdowns = document.querySelectorAll('.sidebar .dropdown-menu');
            sidebarThemeDropdowns.forEach(dropdown => {
                dropdown.addEventListener('click', () => {
                    if (window.innerWidth < 992) {
                        body.classList.remove('sidebar-visible');
                        localStorage.setItem('sidebar-visible', false);
                    }
                });
            });

            // Product Alerts
            const alertList = document.getElementById('alertList');
            const cartBadge = document.getElementById('cartBadge');

            fetch('/product-alerts')
                .then(res => res.json())
                .then(products => {
                    alertList.innerHTML = '';

                    if (!products.length) {
                        alertList.innerHTML = '<div class="text-center small">Null</div>';
                        cartBadge.style.display = 'none';
                        return;
                    }

                    cartBadge.style.display = 'inline-block';
                    cartBadge.textContent = products.length;

                    products.forEach(product => {
                        const alertItem = document.createElement('a');
                        alertItem.href = `/products/show/${product.id}`;
                        alertItem.className = 'dropdown-item d-flex justify-content-between align-items-center';
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

        // Back to Top Button
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

            const descriptionElement = document.querySelector('#description');

            // if (descriptionElement) {
            //     ClassicEditor
            //         .create(descriptionElement, {
            //             toolbar: {
            //                 items: [
            //                     'heading', '|',
            //                     'bold', 'italic', 'underline', '|',
            //                     'link', 'bulletedList', 'numberedList', '|',
            //                     'outdent', 'indent', '|',
            //                     'blockQuote', 'insertTable', '|',
            //                     'undo', 'redo'
            //                 ]
            //             },
            //             language: 'en',
            //             table: {
            //                 contentToolbar: [
            //                     'tableColumn',
            //                     'tableRow',
            //                     'mergeTableCells'
            //                 ]
            //             }
            //         })
            //         .then(editor => {
            //             editorInstance = editor;
            //             window.descriptionEditor = editor;

            //             // Apply initial theme
            //             applyEditorTheme();

            //             // Set up theme observer
            //             const observer = new MutationObserver((mutations) => {
            //                 mutations.forEach((mutation) => {
            //                     if (mutation.type === 'attributes' && mutation.attributeName === 'data-bs-theme') {
            //                         applyEditorTheme();
            //                     }
            //                 });
            //             });

            //             observer.observe(document.documentElement, {
            //                 attributes: true,
            //                 attributeFilter: ['data-bs-theme']
            //             });
            //         })
            //         .catch(error => {
            //             console.error('CKEditor initialization error:', error);
            //         });
            // }
        });

        // Function to apply theme to CKEditor
        function applyEditorTheme() {
            if (!editorInstance) return;

            const theme = document.documentElement.getAttribute('data-bs-theme');
            const editorElement = editorInstance.ui.view.element;

            // Force update CSS custom properties
            const rootStyles = getComputedStyle(document.documentElement);

            if (editorElement) {
                // Update editor container
                editorElement.style.setProperty('--editor-bg', rootStyles.getPropertyValue('--editor-bg'));
                editorElement.style.setProperty('--editor-text', rootStyles.getPropertyValue('--editor-text'));
                editorElement.style.setProperty('--editor-border', rootStyles.getPropertyValue('--editor-border'));
                editorElement.style.setProperty('--card-bg', rootStyles.getPropertyValue('--card-bg'));
                editorElement.style.setProperty('--text-color', rootStyles.getPropertyValue('--text-color'));
                editorElement.style.setProperty('--hover-bg', rootStyles.getPropertyValue('--hover-bg'));
                editorElement.style.setProperty('--primary-color', rootStyles.getPropertyValue('--primary-color'));
                editorElement.style.setProperty('--border-color', rootStyles.getPropertyValue('--border-color'));

                // Force repaint
                editorElement.style.display = 'none';
                editorElement.offsetHeight; // Trigger reflow
                editorElement.style.display = '';
            }
        }


    </script>

    @stack('scripts')
</body>
</html>
