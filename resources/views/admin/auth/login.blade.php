<!doctype html>
@php
    use Illuminate\Support\Facades\Lang;
    use Illuminate\Support\Facades\Route;

    $shopInfo = \App\Models\Shop::first();
    $shopName = $shopInfo->name_shop ?? config('app.name', 'Stock Management System');
    $locale = app()->getLocale();
    // Translate with a readable English fallback when a key is missing.
    $t = fn($key, $fallback) => Lang::has('messages.' . $key) ? __('messages.' . $key) : $fallback;
@endphp
<html lang="{{ $locale }}" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $t('login', 'Sign in') }} - {{ $shopName }}</title>

    @if ($shopInfo && $shopInfo->logo_shop)
        <link rel="icon" href="{{ asset('storage/' . $shopInfo->logo_shop) }}">
    @else
        <link rel="icon" href="{{ asset('favicon.ico') }}">
    @endif

    <script>
        (function () {
            var t = localStorage.getItem('theme') || 'dark';
            if (t === 'system') t = matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            document.documentElement.setAttribute('data-bs-theme', t);
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('backend/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root { --brand: #5B5BD6; --brand-hover: #4A4AC4; --font: 'Plus Jakarta Sans', 'Noto Sans Khmer', system-ui, sans-serif; }
        [data-bs-theme="light"] { --bg: #F5F4F1; --card: #fff; --line: #E7E4DE; --tx: #1B1D24; --mut: #6B7080; --field: #F7F6F3; --link: #4343B8; }
        [data-bs-theme="dark"] { --bg: #0E1015; --card: #161922; --line: rgba(255, 255, 255, .1); --tx: #E7E9EE; --mut: #9AA1AF; --field: #0E1015; --link: #A5A8FF; }

        html, body { height: 100%; }
        body { margin: 0; font-family: var(--font); background: var(--bg); color: var(--tx); -webkit-font-smoothing: antialiased; }
        :focus-visible { outline: 2px solid var(--brand); outline-offset: 2px; }

        .auth { min-height: 100%; display: grid; grid-template-columns: minmax(0, 1.05fr) minmax(0, 1fr); }

        /* ---------- brand panel ---------- */
        .auth-brand {
            position: relative; overflow: hidden; color: #fff; padding: 3rem; display: flex; flex-direction: column; justify-content: space-between;
            background: radial-gradient(900px 500px at 15% 0%, #4B4BC0 0%, transparent 60%), linear-gradient(160deg, #1A1C26 0%, #14161C 100%);
        }
        .auth-brand::before, .auth-brand::after { content: ""; position: absolute; border-radius: 50%; border: 1px solid rgba(255, 255, 255, .08); }
        .auth-brand::before { width: 520px; height: 520px; right: -200px; bottom: -220px; }
        .auth-brand::after { width: 320px; height: 320px; right: -100px; bottom: -120px; }
        .brand-row { display: flex; align-items: center; gap: .8rem; font-weight: 800; font-size: 1.15rem; position: relative; z-index: 1; }
        .brand-mark { width: 42px; height: 42px; border-radius: 12px; background: var(--brand); display: grid; place-items: center; overflow: hidden; font-size: 1.2rem; }
        .brand-mark img { width: 100%; height: 100%; object-fit: cover; }
        .brand-copy { position: relative; z-index: 1; max-width: 460px; }
        .brand-copy h2 { font-size: 2.3rem; font-weight: 800; letter-spacing: -.03em; line-height: 1.15; margin-bottom: .9rem; }
        .brand-copy p { color: #B4B9C6; margin: 0 0 1.75rem; }
        .feat { display: grid; gap: .9rem; }
        .feat div { display: flex; align-items: center; gap: .8rem; color: #D9DCE5; font-weight: 500; }
        .feat i { width: 36px; height: 36px; border-radius: 10px; display: grid; place-items: center; background: rgba(255, 255, 255, .08); color: #B6B8FF; }
        .brand-foot { font-size: .8rem; color: #7E8497; position: relative; z-index: 1; }

        /* ---------- form panel ---------- */
        .auth-main { display: flex; flex-direction: column; padding: 1.25rem 1.5rem 2rem; }
        .auth-top { display: flex; justify-content: flex-end; gap: .5rem; }
        .chip {
            height: 40px; padding: 0 .8rem; display: inline-flex; align-items: center; gap: .45rem; border-radius: 10px;
            border: 1px solid var(--line); background: transparent; color: var(--tx); font-weight: 600; font-size: .85rem; text-decoration: none;
        }
        .chip:hover { background: rgba(127, 127, 127, .12); color: var(--tx); }
        .chip.on { border-color: var(--brand); color: var(--link); }
        .auth-box { width: 100%; max-width: 410px; margin: auto; }
        .auth-box .mini { display: none; align-items: center; gap: .6rem; font-weight: 800; margin-bottom: 1.75rem; }
        .auth-box h1 { font-size: 1.8rem; font-weight: 800; letter-spacing: -.02em; margin-bottom: .35rem; }
        .auth-box .sub { color: var(--mut); margin-bottom: 1.75rem; }

        .lbl { display: flex; justify-content: space-between; align-items: center; font-weight: 600; font-size: .88rem; margin-bottom: .4rem; }
        .lbl a { color: var(--link); font-weight: 600; text-decoration: none; font-size: .82rem; }
        .field { position: relative; margin-bottom: 1.15rem; }
        .field > i.lead { position: absolute; left: .95rem; top: 50%; transform: translateY(-50%); color: var(--mut); pointer-events: none; }
        .field input.form-control {
            height: 50px; padding: 0 3rem 0 2.7rem; border-radius: 12px; background: var(--field); color: var(--tx);
            border: 1.5px solid var(--line); font-size: .95rem;
        }
        .field input.form-control::placeholder { color: var(--mut); opacity: .7; }
        .field input.form-control:focus { border-color: var(--brand); box-shadow: 0 0 0 .22rem rgba(91, 91, 214, .2); background: var(--field); color: var(--tx); }
        .field input.is-invalid { border-color: #E5484D; background-image: none; }
        .eye { position: absolute; right: .35rem; top: 50%; transform: translateY(-50%); width: 40px; height: 40px; border: 0; background: transparent; color: var(--mut); border-radius: 10px; }
        .eye:hover { color: var(--tx); }
        .err { color: #FF7A80; font-size: .82rem; margin-top: .35rem; display: flex; align-items: center; gap: .35rem; }
        .caps { display: none; color: #F5A524; font-size: .8rem; margin-top: .35rem; }
        .form-check-input:checked { background-color: var(--brand); border-color: var(--brand); }
        .btn-login {
            width: 100%; height: 52px; border: 0; border-radius: 12px; background: var(--brand); color: #fff; font-weight: 700; font-size: 1rem;
            display: inline-flex; align-items: center; justify-content: center; gap: .6rem; transition: background .15s;
        }
        .btn-login:hover { background: var(--brand-hover); }
        .btn-login:disabled { opacity: .75; cursor: progress; }
        .alert-soft { background: rgba(229, 72, 77, .12); color: #FF8A8F; border: 1px solid rgba(229, 72, 77, .35); border-radius: 12px; padding: .75rem 1rem; display: flex; gap: .6rem; align-items: center; margin-bottom: 1.25rem; font-size: .9rem; }
        [data-bs-theme="light"] .alert-soft { color: #B42318; }
        .auth-foot { text-align: center; color: var(--mut); font-size: .8rem; margin-top: 2rem; }

        @media (max-width: 991.98px) {
            .auth { grid-template-columns: 1fr; }
            .auth-brand { display: none; }
            .auth-box .mini { display: flex; }
        }
        @media (prefers-reduced-motion: reduce) { * { transition: none !important; } }
    </style>
</head>

<body>
    <div class="auth">

        {{-- ============================ BRAND PANEL ============================ --}}
        <aside class="auth-brand" aria-hidden="true">
            <div class="brand-row">
                <span class="brand-mark">
                    @if ($shopInfo && $shopInfo->logo_shop)
                        <img src="{{ asset('storage/' . $shopInfo->logo_shop) }}" alt="">
                    @else
                        <i class="bi bi-grid-fill"></i>
                    @endif
                </span>
                {{ $shopName }}
            </div>

            <div class="brand-copy">
                <h2>{{ $t('login_headline', 'Run your stock and sales from one place.') }}</h2>
                <p>{{ $t('login_tagline', 'Track inventory, ring up sales, and see how the shop is doing in real time.') }}</p>
                <div class="feat">
                    <div><i class="bi bi-grid"></i> {{ $t('login_f1', 'Fast point of sale for the counter') }}</div>
                    <div><i class="bi bi-box-seam"></i> {{ $t('login_f2', 'Live stock levels and low-stock alerts') }}</div>
                    <div><i class="bi bi-graph-up-arrow"></i> {{ $t('login_f3', 'Daily and monthly sales reports') }}</div>
                </div>
            </div>

            <div class="brand-foot">&copy; {{ date('Y') }} {{ $shopName }}</div>
        </aside>

        {{-- ============================ FORM PANEL ============================ --}}
        <main class="auth-main">
            <div class="auth-top">
                <a href="/lang/en" class="chip {{ $locale == 'en' ? 'on' : '' }}">EN</a>
                <a href="/lang/km" class="chip {{ $locale == 'km' ? 'on' : '' }}">ខ្មែរ</a>
                <button type="button" class="chip" id="themeBtn" aria-label="Toggle theme"><i class="bi bi-moon-stars"></i></button>
            </div>

            <div class="auth-box">
                <div class="mini">
                    <span class="brand-mark" style="width:36px;height:36px;color:#fff"><i class="bi bi-grid-fill"></i></span>
                    {{ $shopName }}
                </div>

                <h1>{{ $t('welcome_back', 'Welcome back') }}</h1>
                <p class="sub">{{ $t('login_subtitle', 'Sign in to your account to continue.') }}</p>

                @if (session('status'))
                    <div class="alert alert-success py-2 small" role="status">{{ session('status') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert-soft" role="alert"><i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}</div>
                @endif
                @if ($errors->any() && !$errors->has('login') && !$errors->has('password'))
                    <div class="alert-soft" role="alert"><i class="bi bi-exclamation-triangle-fill"></i> {{ $errors->first() }}</div>
                @endif

                <form method="POST" action="/login" id="loginForm" novalidate>
                    @csrf

                    <div class="field">
                        <label for="login" class="lbl">{{ $t('login_id', 'Email or username') }}</label>
                        <i class="bi bi-person lead" style="top: 52px"></i>
                        <input type="text" name="login" id="login" value="{{ old('login') }}"
                            class="form-control @error('login') is-invalid @enderror" placeholder="Email or username"
                            inputmode="email" autocapitalize="none" spellcheck="false"
                            autocomplete="username" autofocus required>
                        @error('login')
                            <div class="err"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="password" class="lbl">
                            {{ $t('password', 'Password') }}
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}">{{ $t('forgot_password', 'Forgot password?') }}</a>
                            @endif
                        </label>
                        <i class="bi bi-lock lead" style="top: 52px"></i>
                        <input type="password" name="password" id="password"
                            class="form-control @error('password') is-invalid @enderror" placeholder="••••••••"
                            autocomplete="current-password" required>
                        <button type="button" class="eye" id="eye" aria-label="Show password" style="top: 52px"><i class="bi bi-eye"></i></button>
                        <div class="caps" id="caps"><i class="bi bi-capslock me-1"></i>{{ $t('caps_lock', 'Caps Lock is on') }}</div>
                        @error('password')
                            <div class="err"><i class="bi bi-exclamation-circle"></i>{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">{{ $t('remember_me', 'Keep me signed in') }}</label>
                    </div>

                    <button type="submit" class="btn-login" id="loginBtn">
                        <span>{{ $t('login', 'Sign in') }}</span><i class="bi bi-arrow-right"></i>
                    </button>
                </form>

                <p class="auth-foot">&copy; {{ date('Y') }} {{ $shopName }}. {{ $t('add_rights_reserved', 'All rights reserved.') }}</p>
            </div>
        </main>
    </div>

    <script src="{{ asset('backend/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        const $ = id => document.getElementById(id);

        // Show / hide password
        $('eye').addEventListener('click', () => {
            const show = $('password').type === 'password';
            $('password').type = show ? 'text' : 'password';
            $('eye').innerHTML = '<i class="bi bi-eye' + (show ? '-slash' : '') + '"></i>';
            $('eye').setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        });

        // Caps Lock hint
        $('password').addEventListener('keyup', e => {
            $('caps').style.display = e.getModifierState && e.getModifierState('CapsLock') ? 'block' : 'none';
        });
        $('password').addEventListener('blur', () => $('caps').style.display = 'none');

        // Theme toggle (same storage key as the admin layout)
        const root = document.documentElement, tb = $('themeBtn');
        const icon = () => tb.innerHTML = '<i class="bi bi-' + (root.getAttribute('data-bs-theme') === 'dark' ? 'sun' : 'moon-stars') + '"></i>';
        icon();
        tb.addEventListener('click', () => {
            const next = root.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
            root.setAttribute('data-bs-theme', next); localStorage.setItem('theme', next); icon();
        });

        // Loading state, blocks double submit
        $('loginForm').addEventListener('submit', () => {
            const b = $('loginBtn'); b.disabled = true;
            b.innerHTML = '<span class="spinner-border spinner-border-sm" aria-hidden="true"></span><span>Signing in…</span>';
        });
    </script>
</body>

</html>