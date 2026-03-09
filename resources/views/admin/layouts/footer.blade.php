@if (!Request::is('admin/pos*'))
    <!-- Back to Top Button -->
    <button type="button"
        class="btn btn-primary back-to-top rounded-circle shadow d-flex align-items-center justify-content-center no-print"
        id="backToTopBtn"
        data-bs-toggle="tooltip"
        data-bs-placement="left"
        data-bs-title="Back to top"
        aria-label="Back to top">
        <i class="bi bi-arrow-up fs-5"></i>
    </button>

    <!-- Footer -->
    <footer class="footer mt-auto py-3 border-top">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start"></div>

                <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                    <span class="text-muted">
                        © {{ date('Y') }}
                        <strong>{{ $shopInfo->name_shop ?? 'Stock Management System' }}</strong>.
                        {{ __('messages.add_rights_reserved') }}
                    </span>
                </div>
            </div>
        </div>
    </footer>
@endunless













<div id="mini-calculator" class="card shadow-lg d-none"
    style="position: fixed; bottom: 80px; right: 20px; width: 260px; z-index: 9999;">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
        <span><i class="bi bi-calculator"></i> Calculator</span>
        <button type="button" class="btn-close btn-close-white" onclick="toggleCalc()"></button>
    </div>
    <div class="card-body p-2 bg-secondary">
        <input type="text" id="calc-display" class="form-control form-control-lg text-end mb-2" readonly
            style="background: #e9ecef; font-family: monospace;">
        <div class="row g-1">
            @foreach (['C', '/', '*', '-', '7', '8', '9', '+', '4', '5', '6', '1', '2', '3', '0', '.', '='] as $key)
                <div class="{{ $key == '=' ? 'col-6' : ($key == '0' ? 'col-3' : 'col-3') }}">
                    <button class="btn {{ is_numeric($key) || $key == '.' ? 'btn-light' : 'btn-warning' }} w-100 fw-bold"
                        onclick="calcInput('{{ $key }}')">
                        {{ $key }}
                    </button>
                </div>
            @endforeach
        </div>
    </div>
</div>


<script src="{{ asset('backend/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('backend/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('backend/DataTables/datatables.min.js') }}"></script>
<script src="{{ asset('backend/js/chart.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

<script>

    var projectName = "{{ config('app.name') }}";
// console.log("Welcome to " + projectName);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const tab = new URLSearchParams(window.location.search).get('tab');
        if (tab) {
            const trigger = document.querySelector(`[data-bs-target="#${tab}"]`);
            if (trigger) {
                new bootstrap.Tab(trigger).show();
            }
        }
    });
    setTimeout(() => {
        $('.alert-success').alert('close');
    }, 4000);

    setTimeout(() => {
        $('.alert-danger').alert('close');
    }, 7000);

    $('#selectAll').on('click', function () {
        $('.Checkbox').prop('checked', $(this).prop('checked'));
        toggleBulkDeleteButton();
    });
    $(document).on('change', '.Checkbox', function () {
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

        const savedTheme = localStorage.getItem('theme') || 'dark';
        setTheme(savedTheme);

        themeDropdownItems.forEach(item => {
            item.addEventListener('click', e => {
                e.preventDefault();
                const selectedTheme = item.getAttribute('data-theme');
                setTheme(selectedTheme);
            });
        });

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

        const sidebarThemeDropdowns = document.querySelectorAll('.sidebar .dropdown-menu');
        sidebarThemeDropdowns.forEach(dropdown => {
            dropdown.addEventListener('click', () => {
                if (window.innerWidth < 992) {
                    body.classList.remove('sidebar-visible');
                    localStorage.setItem('sidebar-visible', false);
                }
            });
        });

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

        fetch("{{ url('product-alerts') }}")
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
                    alertItem.textContent = product.code;

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
    @if (!Request::is('admin/pos*'))
        document.addEventListener('DOMContentLoaded', function () {
            const backToTopBtn = document.getElementById('backToTopBtn');
            const scrollThreshold = 400;

            // Initialize Bootstrap tooltip
            const tooltip = new bootstrap.Tooltip(backToTopBtn);
            function toggleBackToTopButton() {
                if (window.pageYOffset > scrollThreshold) {
                    backToTopBtn.classList.add('show');
                } else {
                    backToTopBtn.classList.remove('show');
                }
            }
            function scrollToTop() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
                tooltip.hide();
            }
            window.addEventListener('scroll', toggleBackToTopButton);
            backToTopBtn.addEventListener('click', scrollToTop);
            backToTopBtn.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    scrollToTop();
                }
            });


        });
    @endunless

    let calcDisplay = '';
    function toggleCalc() {
        const calc = document.getElementById('mini-calculator');
        calc.classList.toggle('d-none');
    }
    function calcInput(value) {
        const displayElement = document.getElementById('calc-display');

        if (value === 'C') {
            calcDisplay = '';
        } else if (value === '=') {
            try {
                calcDisplay = new Function('return ' + calcDisplay)();
            } catch (e) {
                calcDisplay = 'Error';
            }
        } else {
            calcDisplay += value;
        }
        displayElement.value = calcDisplay;
    }

</script>
