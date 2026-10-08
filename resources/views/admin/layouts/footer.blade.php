@php
    // Data for the notification center script below (kept out of <script> so editors parse the JS cleanly).
    $nt = fn($k, $fb) => \Illuminate\Support\Facades\Lang::has('messages.' . $k) ? __('messages.' . $k) : $fb;
    $ntLabels = [
        'now' => $nt('just_now', 'Just now'),
        'out' => $nt('out_of_stock', 'Out of stock'),
        'low' => $nt('low_stock', 'Low stock'),
        'left' => $nt('left', 'left'),
        'view' => $nt('view', 'View'),
        'read' => $nt('mark_read', 'Mark read'),
        'dismiss' => $nt('dismiss', 'Dismiss'),
        'empty' => $nt('no_notifications', "You're all caught up"),
        'emptyHint' => $nt('no_notifications_hint', 'New alerts and activity will show up here.'),
        'error' => $nt('alerts_error', 'Could not load stock alerts'),
        'retry' => $nt('retry', 'Retry'),
        'sale' => $nt('sale_complete', 'Sale :ref completed'),
    ];
    $ntFlash = array_values(array_filter([
        session('success') ? ['success', session('success')] : null,
        session('error') ? ['error', session('error')] : null,
        session('status') ? ['info', session('status')] : null,
    ]));
@endphp
@if (!Request::is('admin/pos*'))
    <!-- Back to Top Button -->
    <button type="button"
            class="btn btn-primary back-to-top rounded-circle shadow d-flex align-items-center justify-content-center no-print"
            id="backToTopBtn" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Back to top"
            aria-label="Back to top">
        <i class="bi bi-arrow-up fs-5"></i>
    </button>

    <!-- Footer -->
    <footer class="footer mt-auto py-3 no-print">
        <div class="container-fluid px-3 px-lg-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-1">
                <span>
                    &copy; {{ date('Y') }}
                    <strong class="text-body">{{ $shopInfo->name_shop ?? 'Stock Management System' }}</strong>.
                    {{ __('messages.add_rights_reserved') }}
                </span>
                <span class="small">{{ config('app.name') }}</span>
            </div>
        </div>
    </footer>
@endif


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
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content pos-modal">

                <!-- Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="closePosLabel">
                        <i class="bi bi-power me-2"></i>{{ __('messages.close_register') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form method="POST" action="{{ route('pos.close-register') }}">
                    @csrf
                    <!-- Body -->
                    <div class="modal-body">
                        <p class="text-muted mb-3">{{ __('messages.cpr') }}</p>

                        <div class="row g-2">

                            <!-- Cash In Hand (editable) -->
                            <div class="col-6">
                                <label for="cash_in_hand" class="pos-field-label">{{ __('messages.cash_in_hand') }}</label>
                                <input min="0" step="0.01" type="number" id="cash_in_hand" name="cash_in_hand"
                                       class="pos-input w-100 fw-bold @error('cash_in_hand') is-invalid @enderror"
                                       value="{{ $records->cash_in_hand }}" required>
                                @error('cash_in_hand')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Cash Payment -->
                            <div class="col-6">
                                <label class="pos-field-label">{{ __('messages.cash_payment') ?? 'Cash Payment' }}</label>
                                <input type="text" class="pos-input w-100" value="{{ $total }}" readonly>
                            </div>

                            <!-- Note (editable) -->
                            <div class="col-6">
                                <label for="note" class="pos-field-label">{{ __('messages.note') }}</label>
                                <input type="text" id="note" name="note" class="pos-input w-100" value="Null">
                            </div>

                            <!-- Total Cash -->
                            <div class="col-6">
                                <label class="pos-field-label">{{ __('messages.total_cash') }}</label>
                                <input type="text" id="total_cash" name="total_cash" class="pos-input w-100 fw-bold"
                                       value="{{ ($total ?? 0) + ($records->cash_in_hand ?? 0) }}" readonly>
                            </div>

                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer">
                        <button type="button" class="pos-btn pos-btn--ghost" data-bs-dismiss="modal">
                            {{ __('messages.cancel') ?? 'Cancel' }}
                        </button>
                        <button type="button" class="pos-btn pos-btn--ghost" onclick="printAnyModal('closePos')">
                            <i class="bi bi-printer me-1"></i>{{ __('messages.print') }}
                        </button>
                        <button type="submit" class="pos-btn pos-btn--primary">
                            <i class="bi bi-lock me-1"></i>{{ __('messages.close') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="modal fade" id="addCash" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content pos-modal">

                <!-- Header -->
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-cash-coin me-2"></i>Add / Remove Cash
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="cashForm">
                    @csrf
                    <!-- Body -->
                    <div class="modal-body">

                        <!-- Current balance (context, read-only) -->
                        <div class="pos-balance-note mb-3 p-2 rounded d-flex justify-content-between align-items-center">
                            <span class="pos-field-label mb-0">Current Balance</span>
                            <span class="fw-bold">480.00</span>
                        </div>

                        <div class="row g-2">

                            <!-- Type -->
                            <div class="col-12">
                                <label class="pos-field-label">Type *</label>
                                <div class="d-flex gap-2">
                                    <label class="pos-btn pos-btn--ghost flex-fill text-center mb-0">
                                        <input type="radio" name="type" value="in" class="form-check-input me-1" checked>
                                        <i class="bi bi-plus-circle me-1"></i>Cash In
                                    </label>
                                    <label class="pos-btn pos-btn--ghost flex-fill text-center mb-0">
                                        <input type="radio" name="type" value="out" class="form-check-input me-1">
                                        <i class="bi bi-dash-circle me-1"></i>Cash Out
                                    </label>
                                </div>
                            </div>

                            <!-- Amount -->
                            <div class="col-12">
                                <label class="pos-field-label">Amount *</label>
                                <input type="number" step="0.01" min="0.01" name="amount" id="amount" class="pos-input w-100" placeholder="Enter amount">
                            </div>

                            <!-- Reason -->
                            <div class="col-12">
                                <label class="pos-field-label">Reason</label>
                                <textarea name="reason" id="reason" class="pos-input w-100" rows="2" placeholder="e.g. Change top-up, bank deposit"></textarea>
                            </div>

                        </div>

                        <!-- Message -->
                        <div id="cashMsg" class="mt-2 small text-muted"></div>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer">
                        <button type="button" class="pos-btn pos-btn--ghost" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="pos-btn pos-btn--primary">
                            {{ __('messages.submit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="registerDetail" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content pos-modal">

                <!-- Header -->
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-cash-register me-2"></i>Cash Register Details
                    </h5>
                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"></button>
                </div>

                <!-- Body -->
                <div class="modal-body">

                    <div class="pos-balance-note p-2 rounded d-flex justify-content-between align-items-center ">
                        <span class="pos-field-label mb-0">Cash in Hand</span>
                        <span class="text-secondary">{{ $records->cash_in_hand }}</span>
                    </div>

                    <div class="pos-balance-note p-2 rounded d-flex justify-content-between align-items-center">
                        <span class="pos-field-label mb-0">Total Sale Amount</span>
                        <span class="text-secondary">480.00</span>
                    </div>

                    <div class="pos-balance-note p-2 rounded d-flex justify-content-between align-items-center ">
                        <span class="pos-field-label mb-0">Total Payment</span>
                        <span class="text-secondary">480.00</span>
                    </div>

                    <div class="pos-balance-note p-2 rounded d-flex justify-content-between align-items-center">
                        <span class="pos-field-label mb-0">Cash Payment</span>
                        <span class="text-secondary">480.00</span>
                    </div>

                    <div class="pos-balance-note p-2 rounded d-flex justify-content-between align-items-center">
                        <span class="pos-field-label mb-0">Total Expense</span>
                        <span class="text-secondary">480.00</span>
                    </div>

                    <div class="pos-balance-note p-2 rounded d-flex justify-content-between align-items-center">
                        <span class="mb-0 fw-bold">Total Cash</span>
                        <span class="fw-bold">{{ number_format($total, 2) }}</span>
                    </div>

                </div>

                <!-- Footer -->
                <div class="modal-footer">

                    <button type="button"
                            class="pos-btn pos-btn--primary"
                            data-bs-dismiss="modal"
                            data-bs-toggle="modal"
                            data-bs-target="#closeRegisterModal">

                        <i class="bi bi-lock me-1"></i>Cancel

                    </button>

                </div>

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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- SheetJS for Excel/CSV Export -->
<script src="https://cdn.sheetjs.com/xlsx-0.20.0/package/dist/xlsx.full.min.js"></script>

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

        // Desktop (>=1200px, not POS): sidebar is an icon rail; the button pins it open.
        // Mobile / tablet / POS: sidebar is an overlay; the button opens it.
        const sbDesktop = () => window.innerWidth >= 1200 && !body.classList.contains('is-pos');
        const sbOverlayMode = () => !sbDesktop();
        if (localStorage.getItem('rail-pinned') === 'true' && sbDesktop()) body.classList.add('rail-pinned');
        if (localStorage.getItem('sidebar-visible') === 'true' && !sbDesktop()) body.classList.add('sidebar-visible');
        sidebarToggle?.addEventListener('click', () => {
            if (sbDesktop()) {
                const pinned = body.classList.toggle('rail-pinned');
                localStorage.setItem('rail-pinned', pinned);
            } else {
                const isVisible = body.classList.toggle('sidebar-visible');
                localStorage.setItem('sidebar-visible', isVisible);
            }
        });
        sidebarOverlay?.addEventListener('click', () => {
            body.classList.remove('sidebar-visible');
            localStorage.setItem('sidebar-visible', false);
        });
        const sidebarThemeDropdowns = document.querySelectorAll('.sidebar .dropdown-menu');
        sidebarThemeDropdowns.forEach(dropdown => {
            dropdown.addEventListener('click', () => {
                if (sbOverlayMode()) {
                    body.classList.remove('sidebar-visible');
                    localStorage.setItem('sidebar-visible', false);
                }
            });
        });

        const sidebarNavLinks = document.querySelectorAll(
            '.sidebar .nav-link[href]:not([data-bs-toggle="collapse"]):not(.dropdown-toggle)');
        sidebarNavLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (sbOverlayMode()) {
                    body.classList.remove('sidebar-visible');
                    localStorage.setItem('sidebar-visible', false);
                }
            });
        });

        // Also support explicit mobile-close links
        const mobileCloseLinks = document.querySelectorAll('.sidebar .nav-link.mobile-close');
        mobileCloseLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (sbOverlayMode()) {
                    body.classList.remove('sidebar-visible');
                    localStorage.setItem('sidebar-visible', false);
                }
            });
        });

        // ═══════════════════════════════════════════════════════
        // NOTIFICATION CENTER
        //  - stock alerts (out of stock / low stock) from /product-alerts
        //  - activity (create / edit success) with "1 minute ago"
        //  - actions: view product, mark read, dismiss, mark all read, clear
        //  Other scripts can add items:  window.notify('Product saved', 'success')
        // ═══════════════════════════════════════════════════════
        (function () {
            const L = @json($ntLabels);
            const LS_N = 'app-notifs', LS_S = 'app-stock-state';
            const list = document.getElementById('alertList'), badge = document.getElementById('cartBadge');
            const btn = document.getElementById('cartIcon');
            if (!list || !badge) return;

            const load = (k, d) => { try { return JSON.parse(localStorage.getItem(k)) || d; } catch (e) { return d; } };
            const save = (k, v) => { try { localStorage.setItem(k, JSON.stringify(v)); } catch (e) {} };
            let acts = load(LS_N, []), state = load(LS_S, {}), stock = [], tab = 'all', failed = false;

            const rtf = new Intl.RelativeTimeFormat(document.documentElement.lang || 'en', { numeric: 'auto' });
            function ago(ts) {
                const s = Math.round((ts - Date.now()) / 1000), a = Math.abs(s);
                if (a < 45) return L.now;
                if (a < 3600) return rtf.format(Math.round(s / 60), 'minute');
                if (a < 86400) return rtf.format(Math.round(s / 3600), 'hour');
                return rtf.format(Math.round(s / 86400), 'day');
            }
            function el(tag, cls, text) {
                const n = document.createElement(tag);
                if (cls) n.className = cls;
                if (text !== undefined) n.textContent = text;
                return n;
            }

            // ---- data ----
            function stockItems() {
                return stock.map(p => {
                    const q = Number(p.stock_quantity), st = state[p.id], same = st && st.q === q;
                    return { kind: 'stock', id: p.id, p, q, out: q <= 0, read: !!(same && st.read), gone: !!(same && st.gone) };
                }).filter(x => !x.gone).sort((a, b) => b.out - a.out);
            }
            function setStock(id, q, patch) { state[id] = Object.assign({ q: q, read: false, gone: false }, state[id] && state[id].q === q ? state[id] : {}, patch); save(LS_S, state); }

            window.notify = function (text, type) {
                if (!text) return;
                const now = Date.now();
                if (acts[0] && acts[0].text === text && now - acts[0].ts < 2500) return; // de-dupe
                acts.unshift({ id: now + Math.random(), type: type || 'success', text: String(text), ts: now, read: false });
                acts = acts.slice(0, 30); save(LS_N, acts); render();
            };

            // ---- render ----
            function row(opts) {
                const r = el('div', 'ntf-item' + (opts.read ? '' : ' unread'));
                r.dataset.key = opts.key;
                const ic = el('span', 'ntf-ic ' + opts.tone); ic.appendChild(el('i', 'bi bi-' + opts.icon));
                const body = el('div', 'ntf-body');
                body.appendChild(el('div', 'ntf-t', opts.title));
                if (opts.sub) body.appendChild(el('div', 'ntf-s', opts.sub));
                const meta = el('div', 'ntf-m');
                if (opts.ts) { const t = el('span', 'ntf-time', ago(opts.ts)); meta.appendChild(t); }
                (opts.actions || []).forEach(a => {
                    const x = a.href ? el('a', 'ntf-act') : el('button', 'ntf-act'); if (a.href) x.href = a.href; else x.type = 'button';
                    x.dataset.act = a.act; x.textContent = a.label; meta.appendChild(x);
                });
                body.appendChild(meta);
                const close = el('button', 'ntf-x'); close.type = 'button'; close.dataset.act = 'dismiss'; close.title = L.dismiss; close.setAttribute('aria-label', L.dismiss);
                close.appendChild(el('i', 'bi bi-x-lg'));
                r.append(ic, body, close);
                return r;
            }

            function render() {
                const S = stockItems(), outs = S.filter(x => x.out), lows = S.filter(x => !x.out);
                const unread = S.filter(x => !x.read).length + acts.filter(n => !n.read).length;
                badge.textContent = unread > 99 ? '99+' : unread;
                badge.style.display = unread ? 'inline-flex' : 'none';
                document.querySelector('[data-n="all"]').textContent = S.length + acts.length;
                document.querySelector('[data-n="stock"]').textContent = S.length;
                document.querySelector('[data-n="act"]').textContent = acts.length;

                const rows = [];
                const stockRow = x => row({
                    key: 's' + x.id, read: x.read, tone: x.out ? 'red' : 'amber', icon: x.out ? 'box-seam' : 'exclamation-triangle',
                    title: (x.p.name || x.p.code) + (x.p.name && x.p.code ? ' (' + x.p.code + ')' : ''),
                    sub: x.out ? L.out : L.low + ': ' + x.q + ' ' + L.left,
                    actions: [{ href: '/products/show/' + x.id, act: 'view', label: L.view }].concat(x.read ? [] : [{ act: 'read', label: L.read }])
                });
                const actRow = n => row({
                    key: 'a' + n.id, read: n.read, ts: n.ts, title: n.text,
                    tone: n.type === 'error' ? 'red' : n.type === 'info' ? 'blue' : 'green',
                    icon: n.type === 'error' ? 'x-circle' : n.type === 'info' ? 'info-circle' : 'check-circle',
                    actions: n.read ? [] : [{ act: 'read', label: L.read }]
                });

                if (tab !== 'act') outs.forEach(x => rows.push(stockRow(x)));
                if (tab !== 'stock') acts.forEach(n => rows.push(actRow(n)));
                if (tab !== 'act') lows.forEach(x => rows.push(stockRow(x)));

                list.innerHTML = '';
                if (!rows.length) {
                    const e = el('div', 'ntf-empty');
                    if (failed && tab !== 'act') {
                        e.appendChild(el('i', 'bi bi-wifi-off')); e.appendChild(el('div', 'fw-semibold', L.error));
                        const r = el('button', 'ntf-act', L.retry); r.type = 'button'; r.dataset.act = 'retry'; e.appendChild(r);
                    } else {
                        e.appendChild(el('i', 'bi bi-bell-slash')); e.appendChild(el('div', 'fw-semibold', L.empty)); e.appendChild(el('small', '', L.emptyHint));
                    }
                    list.appendChild(e);
                } else rows.forEach(r => list.appendChild(r));
            }

            // ---- actions (event delegation) ----
            list.addEventListener('click', e => {
                const t = e.target.closest('[data-act]'); const item = e.target.closest('.ntf-item');
                if (t && t.dataset.act === 'retry') return loadStock();
                if (!item) return;
                const key = item.dataset.key, id = key.slice(1), act = t ? t.dataset.act : 'read';
                if (act === 'view') return;                                // normal link
                if (key[0] === 's') {
                    const x = stockItems().find(i => String(i.id) === id); if (!x) return;
                    setStock(x.id, x.q, act === 'dismiss' ? { gone: true, read: true } : { read: true });
                } else {
                    if (act === 'dismiss') acts = acts.filter(n => String(n.id) !== id);
                    else acts.forEach(n => { if (String(n.id) === id) n.read = true; });
                    save(LS_N, acts);
                }
                render();
            });
            document.getElementById('ntfReadAll').addEventListener('click', () => {
                stockItems().forEach(x => setStock(x.id, x.q, { read: true }));
                acts.forEach(n => n.read = true); save(LS_N, acts); render();
            });
            document.getElementById('ntfClear').addEventListener('click', () => {
                stockItems().forEach(x => setStock(x.id, x.q, { gone: true, read: true }));
                acts = []; save(LS_N, acts); render();
            });
            document.querySelectorAll('.ntf-tabs [data-tab]').forEach(b => b.addEventListener('click', () => {
                tab = b.dataset.tab;
                document.querySelectorAll('.ntf-tabs [data-tab]').forEach(x => x.classList.toggle('on', x === b));
                render();
            }));

            // ---- stock alerts from the server ----
            function loadStock() {
                fetch("{{ url('product-alerts') }}", { headers: { 'Accept': 'application/json' } })
                    .then(r => { if (!r.ok) throw new Error(r.status); return r.json(); })
                    .then(p => { stock = Array.isArray(p) ? p : []; failed = false; render(); })
                    .catch(err => { console.error('Failed to fetch product alerts:', err); failed = true; render(); });
            }

            // ---- success messages become activity items ----
            @json($ntFlash).forEach(function (f) { window.notify(f[1], f[0]); });

            // ---- AJAX saves (customer added, sale completed...) ----
            if (window.jQuery) {
                jQuery(document).ajaxSuccess(function (e, xhr, opts, data) {
                    if (String(opts.type || 'GET').toUpperCase() === 'GET' || !data || typeof data !== 'object') return;
                    if (typeof data.success === 'string') window.notify(data.success, 'success');
                    else if (data.success === true && data.reference) window.notify(L.sale.replace(':ref', data.reference), 'success');
                });
            }

            // ---- keep in sync ----
            window.addEventListener('storage', e => { if (e.key === LS_N) { acts = load(LS_N, []); render(); } if (e.key === LS_S) { state = load(LS_S, {}); render(); } });
            if (btn) btn.addEventListener('shown.bs.dropdown', loadStock);
            setInterval(() => { loadStock(); }, 60000);
            setInterval(render, 30000);                                   // refresh "x minutes ago"
            render(); loadStock();
        })();
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
    @endif


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

    window.addEventListener('scroll', () => {
        const scrollTop = window.scrollY;
        const docHeight = document.documentElement.scrollHeight
            - document.documentElement.clientHeight;
        const pct = (scrollTop / docHeight) * 100;
        document.getElementById('scroll-bar').style.width = pct + '%';
    });



</script>
