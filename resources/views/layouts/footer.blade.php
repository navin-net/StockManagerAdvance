<!-- Back to Top -->
<button type="button"
    class="btn btn-primary back-to-top rounded-circle shadow d-flex align-items-center justify-content-center"
    id="backToTopBtn" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Back to top" aria-label="Back to top">
    <i class="bi bi-arrow-up fs-5"></i>
</button>

<footer class="footer mt-auto py-3  border-top">
    <div class="container-fluid">
        <div class="row align-items-center">

            <!-- LEFT -->
            <div class="col-md-6 text-center text-md-start">
                {{-- <span class="text-muted">
                    © {{ date('Y') }}
                    <strong>
                        {{ $shopInfo->name_shop ?? 'Stock Management System' }}
                    </strong>.
                    All rights reserved.
                </span> --}}
            </div>

            <!-- RIGHT -->
            <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                <span class="text-muted">
                    © {{ date('Y') }}
                    <strong>
                        {{ $shopInfo->name_shop ?? 'Stock Management System' }}
                    </strong>.
                    All rights reserved.
                </span>
            </div>

        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>

<script src="{{ asset('assets/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/DataTables/datatables.min.js') }}"></script>
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
    $(document).on('change', '.Checkbox', function() {
        toggleBulkDeleteButton();
    });
    function toggleBulkDeleteButton() {
        const anyChecked = $('.Checkbox:checked').length > 0;
        $('#bulkDeleteBtn').prop('disabled', !anyChecked);
    }


    let editorInstance = null;

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

        // Auto-close sidebar on mobile when selecting theme (dropdown menus)
        const sidebarThemeDropdowns = document.querySelectorAll('.sidebar .dropdown-menu');
        sidebarThemeDropdowns.forEach(dropdown => {
            dropdown.addEventListener('click', () => {
                if (window.innerWidth < 992) {
                    body.classList.remove('sidebar-visible');
                    localStorage.setItem('sidebar-visible', false);
                }
            });
        });

        // Auto-close sidebar on mobile when clicking navigation links that navigate
        // Exclude links that toggle collapse or open dropdowns so those interactions remain functional
        const sidebarNavLinks = document.querySelectorAll(
            '.sidebar .nav-link[href]:not([data-bs-toggle="collapse"]):not(.dropdown-toggle)');
        sidebarNavLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 992) {
                    body.classList.remove('sidebar-visible');
                    localStorage.setItem('sidebar-visible', false);
                }
            });
        });

        // Also support explicit mobile-close links
        const mobileCloseLinks = document.querySelectorAll('.sidebar .nav-link.mobile-close');
        mobileCloseLinks.forEach(link => {
            link.addEventListener('click', () => {
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

    window.addEventListener('load', function() {
        const preloader = document.getElementById('preloader');
        preloader.style.opacity = '0';
        preloader.style.transition = 'opacity 0.5s ease';
        setTimeout(() => {
            preloader.style.display = 'none';
        }, 500);
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

    document.addEventListener('DOMContentLoaded', function() {
        const hash = window.location.hash;

        if (hash) {
            const triggerEl = document.querySelector(
                `[data-bs-target="${hash}"]`
            );

            if (triggerEl) {
                new bootstrap.Tab(triggerEl).show();
            }
        }
    });
</script>
