<!doctype html>
@php
    use Illuminate\Support\Facades\Lang;

    $shopInfo = \App\Models\Shop::first();
    $shopName = $shopInfo->name_shop ?? config('app.name', 'Stock Management System');
    // Translate with an English fallback when the key is not in lang/*/messages.php yet.
    $t = fn($key, $fallback) => Lang::has('messages.' . $key) ? __('messages.' . $key) : $fallback;
@endphp
<html lang="{{ app()->getLocale() }}" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $t('customer', 'Customer') }} - {{ $shopName }}</title>
    <script>
        (function () {
            var t = localStorage.getItem('theme') || 'dark';
            if (t === 'system') t = matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            document.documentElement.setAttribute('data-bs-theme', t);
        })();
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@400;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --brand: #5B5BD6; --font: 'Plus Jakarta Sans', 'Noto Sans Khmer', system-ui, sans-serif; --ok: #22C55E; }
        [data-bs-theme="light"] { --bg: #F5F4F1; --card: #fff; --line: #E7E4DE; --tx: #1B1D24; --mut: #6B7080; --soft: rgba(91, 91, 214, .1); --link: #4343B8; }
        [data-bs-theme="dark"] { --bg: #0E1015; --card: #161922; --line: rgba(255, 255, 255, .09); --tx: #E7E9EE; --mut: #9AA1AF; --soft: rgba(124, 124, 255, .14); --link: #A5A8FF; }
        * { box-sizing: border-box; }
        html, body { height: 100%; margin: 0; overflow: hidden; }
        body { font-family: var(--font); background: var(--bg); color: var(--tx); -webkit-font-smoothing: antialiased; cursor: default; }
        .view { position: absolute; inset: 0; display: none; }
        .view.on { display: flex; }

        /* ---------- idle ---------- */
        #vIdle { flex-direction: column; align-items: center; justify-content: center; text-align: center; gap: 1rem;
            background: radial-gradient(900px 500px at 50% 0%, rgba(91, 91, 214, .28), transparent 65%), var(--bg); padding: 2rem; }
        .mark { width: clamp(84px, 11vw, 140px); aspect-ratio: 1; border-radius: 28px; background: var(--brand); color: #fff;
            display: grid; place-items: center; font-size: clamp(2.4rem, 5vw, 4rem); overflow: hidden; box-shadow: 0 20px 60px rgba(91, 91, 214, .4); }
        .mark img { width: 100%; height: 100%; object-fit: cover; }
        #vIdle h1 { font-size: clamp(2rem, 5vw, 4rem); font-weight: 800; letter-spacing: -.03em; margin: .6rem 0 0; }
        #vIdle p { color: var(--mut); font-size: clamp(1rem, 2vw, 1.5rem); margin: 0; }

        /* ---------- order ---------- */
        #vMain { padding: 1.75rem; gap: 1.5rem; }
        .col-items { flex: 1 1 58%; min-width: 0; display: flex; flex-direction: column; background: var(--card); border: 1px solid var(--line); border-radius: 24px; overflow: hidden; }
        .head { display: flex; justify-content: space-between; align-items: center; gap: 1rem; padding: 1.25rem 1.75rem; border-bottom: 1px solid var(--line); }
        .head h2 { margin: 0; font-size: clamp(1.2rem, 2.2vw, 1.8rem); font-weight: 800; letter-spacing: -.02em; }
        .head small { color: var(--mut); font-size: .95rem; }
        .who { display: inline-flex; align-items: center; gap: .5rem; padding: .4rem .9rem; border-radius: 999px; background: var(--soft); color: var(--link); font-weight: 700; }
        #items { flex: 1; overflow-y: auto; padding: .5rem 1rem; scroll-behavior: smooth; }
        .row-i { display: grid; grid-template-columns: 64px 1fr auto; gap: 1rem; align-items: center; padding: .85rem .75rem; border-radius: 16px; }
        .row-i + .row-i { border-top: 1px solid var(--line); }
        .row-i img { width: 64px; height: 64px; object-fit: cover; border-radius: 14px; background: var(--soft); }
        .row-i .nm { font-size: clamp(1.05rem, 1.8vw, 1.5rem); font-weight: 700; line-height: 1.25; }
        .row-i .sub { color: var(--mut); font-size: clamp(.9rem, 1.3vw, 1.1rem); margin-top: .15rem; }
        .row-i .amt { font-size: clamp(1.15rem, 2vw, 1.7rem); font-weight: 800; font-variant-numeric: tabular-nums; }
        .row-i.flash { animation: flash 1.2s ease-out; }
        @keyframes flash { 0% { background: rgba(124, 124, 255, .35); transform: scale(1.015); } 100% { background: transparent; transform: none; } }
        .empty { display: grid; place-items: center; height: 100%; color: var(--mut); font-size: 1.2rem; }

        .col-side { flex: 1 1 42%; max-width: 560px; display: flex; flex-direction: column; gap: 1.25rem; min-width: 0; }
        .card { background: var(--card); border: 1px solid var(--line); border-radius: 24px; padding: 1.75rem; }
        .tot { display: flex; justify-content: space-between; align-items: baseline; padding: .4rem 0; color: var(--mut); font-size: clamp(1rem, 1.7vw, 1.35rem); font-variant-numeric: tabular-nums; }
        .tot b { color: var(--tx); }
        .tot.disc b { color: var(--ok); }
        .grand { margin-top: .75rem; padding-top: 1.1rem; border-top: 2px dashed var(--line); display: flex; justify-content: space-between; align-items: baseline; gap: 1rem; }
        .grand span { font-weight: 700; font-size: clamp(1.1rem, 2vw, 1.6rem); color: var(--mut); }
        .grand b { font-size: clamp(2.4rem, 6vw, 4.6rem); font-weight: 800; letter-spacing: -.04em; line-height: 1; font-variant-numeric: tabular-nums; }
        #payBox { display: none; background: linear-gradient(160deg, var(--brand), #3A3AA6); color: #fff; border: 0; }
        #payBox.on { display: block; animation: rise .35s ease-out; }
        @keyframes rise { from { opacity: 0; transform: translateY(14px); } }
        .method { display: inline-flex; align-items: center; gap: .6rem; padding: .4rem 1rem; border-radius: 999px; background: rgba(255, 255, 255, .16); font-weight: 700; font-size: 1.05rem; }
        .pay-line { display: flex; justify-content: space-between; align-items: baseline; margin-top: 1rem; font-size: clamp(1.05rem, 1.8vw, 1.4rem); font-variant-numeric: tabular-nums; }
        .pay-line span { opacity: .8; }
        .pay-line.chg b { font-size: clamp(1.8rem, 4vw, 3rem); color: #86F0AE; }
        .pay-hint { margin-top: 1rem; opacity: .85; font-size: 1.05rem; }

        /* ---------- overlays ---------- */
        .ov { position: absolute; inset: 0; z-index: 10; display: none; flex-direction: column; align-items: center; justify-content: center; gap: 1rem; text-align: center; padding: 2rem; background: color-mix(in srgb, var(--bg) 92%, transparent); backdrop-filter: blur(6px); }
        .ov.on { display: flex; animation: rise .3s ease-out; }
        .ov h2 { font-size: clamp(2rem, 5vw, 4rem); font-weight: 800; letter-spacing: -.03em; margin: 0; }
        .ov p { margin: 0; color: var(--mut); font-size: clamp(1rem, 2vw, 1.5rem); }
        .check { width: clamp(96px, 14vw, 160px); aspect-ratio: 1; border-radius: 50%; background: var(--ok); color: #fff; display: grid; place-items: center; font-size: clamp(3rem, 7vw, 5.5rem); box-shadow: 0 20px 60px rgba(34, 197, 94, .4); animation: pop .5s cubic-bezier(.2, 1.4, .4, 1); }
        @keyframes pop { from { transform: scale(.4); opacity: 0; } }
        .spin { width: 72px; height: 72px; border-radius: 50%; border: 6px solid var(--line); border-top-color: var(--brand); animation: sp .8s linear infinite; }
        @keyframes sp { to { transform: rotate(360deg); } }

        /* ---------- chrome ---------- */
        .status { position: fixed; left: 1rem; bottom: .8rem; z-index: 20; display: flex; align-items: center; gap: .5rem; font-size: .78rem; color: var(--mut); }
        .status i { width: 9px; height: 9px; border-radius: 50%; background: #E5484D; }
        .status.ok i { background: var(--ok); }
        .fs { position: fixed; right: 1rem; bottom: .6rem; z-index: 20; width: 40px; height: 40px; border-radius: 12px; border: 1px solid var(--line); background: var(--card); color: var(--mut); opacity: .35; transition: opacity .2s; }
        .fs:hover { opacity: 1; }

        @media (max-width: 900px) { #vMain { flex-direction: column; padding: 1rem; } .col-side { max-width: none; flex: none; } }
        @media (prefers-reduced-motion: reduce) { * { animation: none !important; } }
    </style>
</head>

<body>
    {{-- ============ IDLE / WELCOME ============ --}}
    <section class="view on" id="vIdle">
        <div class="mark">
            @if ($shopInfo && $shopInfo->logo_shop)
                <img src="{{ asset('storage/' . $shopInfo->logo_shop) }}" alt="">
            @else
                <i class="bi bi-grid-fill"></i>
            @endif
        </div>
        <h1>{{ $t('cd_welcome', 'Welcome to') }} {{ $shopName }}</h1>
        <p>{{ $t('cd_idle_hint', 'Your order will appear here as it is scanned.') }}</p>
    </section>

    {{-- ============ ORDER + PAYMENT ============ --}}
    <section class="view" id="vMain">
        <div class="col-items">
            <div class="head">
                <div>
                    <h2>{{ $t('cd_your_order', 'Your order') }}</h2>
                    <small id="ref"></small>
                </div>
                <span class="who" id="who" hidden><i class="bi bi-person-circle"></i><span></span></span>
            </div>
            <div id="items" aria-live="polite"></div>
        </div>

        <div class="col-side">
            <div class="card">
                <div class="tot"><span>{{ $t('subtotal', 'Subtotal') }}</span><b id="sub">$0.00</b></div>
                <div class="tot disc" id="discRow"><span>{{ $t('discount', 'Discount') }}</span><b id="disc">-$0.00</b></div>
                <div class="grand"><span>{{ $t('total_due', 'Total due') }}</span><b id="grand">$0.00</b></div>
            </div>

            <div class="card" id="payBox">
                <span class="method" id="mLabel"></span>
                <div class="pay-line"><span>{{ $t('total_to_pay', 'Amount to pay') }}</span><b id="pDue">$0.00</b></div>
                <div id="cashRows">
                    <div class="pay-line"><span>{{ $t('received_amount', 'Received') }}</span><b id="pRec">$0.00</b></div>
                    <div class="pay-line chg"><span>{{ $t('change', 'Change') }}</span><b id="pChg">$0.00</b></div>
                </div>
                <div class="pay-hint" id="bankHint" hidden></div>
            </div>
        </div>
    </section>

    {{-- ============ OVERLAYS ============ --}}
    <section class="ov" id="ovProc" aria-live="assertive">
        <div class="spin"></div>
        <h2>{{ $t('cd_processing', 'Processing payment') }}</h2>
        <p>{{ $t('cd_please_wait', 'Please wait a moment.') }}</p>
    </section>
    <section class="ov" id="ovThanks" aria-live="assertive">
        <div class="check"><i class="bi bi-check-lg"></i></div>
        <h2>{{ $t('cd_thanks', 'Thank you!') }}</h2>
        <p id="thRef"></p>
        <p>{{ $t('cd_come_again', 'We hope to see you again soon.') }}</p>
    </section>

    <div class="status" id="status"><i></i><span>{{ $t('cd_waiting', 'Waiting for POS') }}</span></div>
    <button class="fs" id="fs" type="button" aria-label="Fullscreen" title="Fullscreen (or double-click)"><i class="bi bi-arrows-fullscreen"></i></button>

    <script>
        const KEY = 'pos-cd-state', HELLO = 'pos-cd-hello';
        const ch = 'BroadcastChannel' in window ? new BroadcastChannel('pos-customer-display') : null;
        const $ = id => document.getElementById(id);
        const money = n => '$' + (+n || 0).toFixed(2);
        const BANKS = { aba: 'ABA Bank', acleda: 'ACLEDA Bank', canadia: 'Canadia Bank', wing: 'Wing Bank' };

        let lastTs = 0, seen = 0, thanksUntil = 0, thanksTimer = null, prev = {};

        function show(view) {
            $('vIdle').classList.toggle('on', view === 'idle');
            $('vMain').classList.toggle('on', view === 'main');
        }

        function renderItems(items) {
            const box = $('items'), next = {}; let target = null;
            box.innerHTML = '';
            if (!items.length) { box.innerHTML = '<div class="empty">&nbsp;</div>'; prev = {}; return; }
            items.forEach(it => {
                next[it.id] = it.qty;
                const row = document.createElement('div'); row.className = 'row-i';
                if (prev[it.id] !== it.qty) { row.classList.add('flash'); target = row; }
                const img = document.createElement('img'); img.alt = ''; img.src = it.image || '';
                img.onerror = () => { img.style.visibility = 'hidden'; };
                const mid = document.createElement('div');
                const nm = document.createElement('div'); nm.className = 'nm'; nm.textContent = it.name;
                const sb = document.createElement('div'); sb.className = 'sub'; sb.textContent = it.qty + ' × ' + money(it.price);
                mid.append(nm, sb);
                const amt = document.createElement('div'); amt.className = 'amt'; amt.textContent = money(it.price * it.qty);
                row.append(img, mid, amt); box.appendChild(row);
            });
            prev = next;
            if (target) target.scrollIntoView({ block: 'nearest' });
        }

        function render(s) {
            const now = Date.now();

            // Thank-you screen stays up even though the POS reloads to an empty cart.
            if (s.stage === 'thanks') {
                thanksUntil = now + 9000;
                $('thRef').textContent = s.ref ? s.ref : '';
                $('ovThanks').classList.add('on'); $('ovProc').classList.remove('on');
                clearTimeout(thanksTimer);
                thanksTimer = setTimeout(() => { thanksUntil = 0; $('ovThanks').classList.remove('on'); show('idle'); prev = {}; }, 9000);
                return;
            }
            if (now < thanksUntil && s.stage === 'idle') return;
            thanksUntil = 0; clearTimeout(thanksTimer); $('ovThanks').classList.remove('on');

            $('ovProc').classList.toggle('on', s.stage === 'processing');

            if (s.stage === 'idle' || !s.items.length) { show('idle'); prev = {}; return; }
            show('main');

            $('ref').textContent = s.ref || '';
            const who = $('who'); who.hidden = !s.customer; who.querySelector('span').textContent = s.customer || '';
            renderItems(s.items);
            $('sub').textContent = money(s.subtotal);
            $('discRow').style.display = s.discount > 0 ? '' : 'none';
            $('disc').textContent = '-' + money(s.discount);
            $('grand').textContent = money(s.grand);

            const pay = s.stage === 'payment' || s.stage === 'processing';
            $('payBox').classList.toggle('on', pay);
            if (pay) {
                const p = s.payment || {}, cash = p.method !== 'bank';
                $('mLabel').innerHTML = '<i class="bi bi-' + (cash ? 'cash-stack' : 'bank') + '"></i> ';
                $('mLabel').append(cash ? 'Cash' : 'Bank transfer' + (p.bank ? ' - ' + (BANKS[p.bank] || p.bank.toUpperCase()) : ''));
                $('pDue').textContent = money(s.grand);
                $('cashRows').style.display = cash ? '' : 'none';
                $('pRec').textContent = money(p.received);
                $('pChg').textContent = money(p.received >= s.grand ? p.change : 0);
                const hint = $('bankHint'); hint.hidden = cash;
                hint.textContent = cash ? '' : (p.bank ? 'Please complete the transfer of ' + money(s.grand) + '.' : 'Your cashier will help you choose a bank.');
            }
        }

        function apply(s) {
            if (!s || s.type !== 'state' || s.ts <= lastTs) return;
            lastTs = s.ts; seen = Date.now(); render(s);
        }

        // ---- receive: BroadcastChannel (fast) + localStorage (fallback) ----
        if (ch) ch.onmessage = e => apply(e.data);
        addEventListener('storage', e => { if (e.key === KEY && e.newValue) { try { apply(JSON.parse(e.newValue)); } catch (_) {} } });

        // ---- ask the POS for the current state, then use any saved one ----
        try { const saved = JSON.parse(localStorage.getItem(KEY) || 'null'); if (saved && Date.now() - saved.ts < 15000) apply(saved); } catch (_) {}
        if (ch) ch.postMessage({ type: 'hello' });
        localStorage.setItem(HELLO, Date.now());

        // ---- connection dot ----
        setInterval(() => {
            const ok = Date.now() - seen < 12000;
            $('status').classList.toggle('ok', ok);
            $('status').lastElementChild.textContent = ok ? 'Connected' : 'Waiting for POS';
        }, 1500);

        // ---- fullscreen ----
        const fs = () => document.fullscreenElement ? document.exitFullscreen() : document.documentElement.requestFullscreen();
        $('fs').addEventListener('click', fs); addEventListener('dblclick', fs);
    </script>
</body>

</html>