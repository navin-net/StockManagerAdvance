@if (!Request::is('admin/pos*'))
    <!-- Back to Top Button -->
    <button type="button"
        class="btn btn-primary back-to-top rounded-circle shadow d-flex align-items-center justify-content-center no-print"
        id="backToTopBtn" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Back to top"
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


@if (Request::is('admin/pos'))
    <!-- Calculator Modal -->
    <div id="calc-modal" class="calc-overlay" onclick="if(event.target===this)toggleCalc()">
        <div class="calc-box">

            <div class="calc-header">
                <span>{{ __('messages.calculator') }}</span>
                <button onclick="toggleCalc()" class="calc-close">&times;</button>
            </div>

            <div class="calc-display">
                <div id="calc-expr">&nbsp;</div>
                <div id="calc-result">0</div>
            </div>

            <div class="calc-grid">
                <button class="cbtn func" onclick="calcFn('AC')">AC</button>
                <button class="cbtn func" onclick="calcFn('+/-')">+/-</button>
                <button class="cbtn func" onclick="calcFn('%')">%</button>
                <button class="cbtn op" onclick="calcFn('/')">÷</button>

                <button class="cbtn num" onclick="calcNum('7')">7</button>
                <button class="cbtn num" onclick="calcNum('8')">8</button>
                <button class="cbtn num" onclick="calcNum('9')">9</button>
                <button class="cbtn op" onclick="calcFn('*')">×</button>

                <button class="cbtn num" onclick="calcNum('4')">4</button>
                <button class="cbtn num" onclick="calcNum('5')">5</button>
                <button class="cbtn num" onclick="calcNum('6')">6</button>
                <button class="cbtn op" onclick="calcFn('-')">−</button>

                <button class="cbtn num" onclick="calcNum('1')">1</button>
                <button class="cbtn num" onclick="calcNum('2')">2</button>
                <button class="cbtn num" onclick="calcNum('3')">3</button>
                <button class="cbtn op" onclick="calcFn('+')">+</button>

                <button class="cbtn num zero" onclick="calcNum('0')">0</button>
                <button class="cbtn num" onclick="calcDot()">.</button>
                <button class="cbtn eq" onclick="calcEq()">=</button>
            </div>

        </div>
    </div>

    <div class="modal fade" id="closePos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="closePosLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h1 class="modal-title fs-5 mb-0" id="closePosLabel">
                        {{ __('messages.close_register') }}
                        {{ now()->setTimezone('Asia/Phnom_Penh')->format('Y-m-d H:i:s') }}
                    </h1>

                    <div class="d-flex gap-2 align-items-center">
                        <!-- Print Button -->
                        <button type="button" class="btn btn-outline-success btn-sm"
                            onclick="printAnyModal('closePos')">
                            <i class="bi bi-printer me-1"></i> {{ __('messages.print') }}
                        </button>

                        <!-- Close Button (X) -->
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                </div>
                <form method="POST" action="{{ route('pos.close-register') }}" class="d-inline">
                    @csrf
                    <div class="modal-body">
                        <p>{{ __('messages.cpr') }}</p>
                        <div class="row">
                            <div class="col-md-6">
                                <p>Cash in hand:</p>
                                <p>Cash Payment:</p>
                            </div>
                            <div class="col-md-6 text-end">
                                <p>{{ $records->cash_in_hand }}</p>
                                <p>{{ $records->total_cash }}</p>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="cash_in_hand"
                                        class="form-label">{{ __('messages.cash_in_hand') }}</label>
                                    <input min="0" id="cash_in_hand" name="cash_in_hand"
                                        class="form-control @error('cash_in_hand') is-invalid @enderror"
                                        value="{{ $records->cash_in_hand }}" required>
                                    @error('cash_in_hand')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="total_cash"
                                        class="form-label">{{ __('messages.total_cash') }}</label>
                                    <input type="text" class="form-control"
                                        value="{{ ($records->total_cash ?? 0) + ($records->cash_in_hand ?? 0) }}"
                                        id="total_cash" name="total_cash">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="note" class="form-label">{{ __('messages.note') }}</label>
                                <input type="text" class="form-control" value="{{ $records->cash_in_hand }}"
                                    id="note">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-power me-1"></i> {{ __('messages.close') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif















<script src="{{ asset('backend/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('backend/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('backend/DataTables/datatables.min.js') }}"></script>
<script src="{{ asset('backend/js/chart.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

{{--
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
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

    document.addEventListener('DOMContentLoaded', function() {
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
        document.addEventListener('DOMContentLoaded', function() {
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
            backToTopBtn.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    scrollToTop();
                }
            });


        });
    @endunless


    function toggleCalc() {
        document.getElementById('calc-modal').classList.toggle('show');
    }

    let cur = '0',
        op = null,
        prev = null,
        fresh = false;

    function calcNum(d) {
        if (fresh) {
            cur = d;
            fresh = false;
        } else cur = cur === '0' ? d : cur + d;
        calcUpd();
    }

    function calcDot() {
        if (fresh) {
            cur = '0.';
            fresh = false;
        } else if (!cur.includes('.')) cur += '.';
        calcUpd();
    }

    function calcFn(fn) {
        const n = parseFloat(cur);
        if (fn === 'AC') {
            cur = '0';
            op = null;
            prev = null;
            fresh = false;
            calcUpd();
            return;
        }
        if (fn === '+/-') {
            cur = String(-n);
            calcUpd();
            return;
        }
        if (fn === '%') {
            cur = String(n / 100);
            calcUpd();
            return;
        }
        if (prev !== null && !fresh) cur = String(calcDo(prev, n, op));
        prev = parseFloat(cur);
        op = fn;
        fresh = true;
        const sym = fn === '*' ? '×' : fn === '/' ? '÷' : fn;
        document.getElementById('calc-expr').textContent = prev + ' ' + sym;
        document.querySelectorAll('.cbtn.op').forEach(b => b.classList.toggle('active', b.textContent === sym));
        document.getElementById('calc-result').textContent = cur;
    }

    function calcEq() {
        if (!op || prev === null) return;
        const n = parseFloat(cur);
        const sym = op === '*' ? '×' : op === '/' ? '÷' : op;
        const res = calcDo(prev, n, op);
        document.getElementById('calc-expr').textContent = prev + ' ' + sym + ' ' + n + ' =';
        cur = String(parseFloat(res.toFixed(10)));
        op = null;
        prev = null;
        fresh = true;
        document.getElementById('calc-result').textContent = cur;
    }

    function calcDo(a, b, o) {
        if (o === '+') return a + b;
        if (o === '-') return a - b;
        if (o === '*') return a * b;
        if (o === '/') return b !== 0 ? a / b : 0;
    }

    function calcUpd() {
        document.getElementById('calc-result').textContent = cur;
    }





</script>
