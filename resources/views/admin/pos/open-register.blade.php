@extends('admin.layouts.master')

@section('title', $pageTitle)

@php
    use Illuminate\Support\Facades\Lang;
    $cashier = auth()->user();
    $hint = Lang::has('messages.register_not_open')
        ? __('messages.register_not_open')
        : 'Register is not open. Enter the cash in hand amount and click open register.';
@endphp

@push('styles')
    <style>
        .reg-hero {
            background: linear-gradient(155deg, var(--brand) 0%, #3A3AA6 100%);
            color: #fff;
            border-radius: 18px 0 0 18px;
            padding: 2rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            position: relative;
            overflow: hidden;
        }
        .reg-hero::after {
            content: "";
            position: absolute;
            right: -60px;
            bottom: -60px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .08);
        }
        .reg-pill {
            align-self: flex-start;
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .3rem .75rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, .16);
            font-size: .75rem;
            font-weight: 700;
        }
        .reg-pill i { font-size: .5rem; color: #FFB4B8; }
        .reg-time { font-size: 2.6rem; font-weight: 800; letter-spacing: -.03em; line-height: 1; font-variant-numeric: tabular-nums; }
        .reg-date { opacity: .8; margin-top: .35rem; }
        .reg-meta { display: grid; gap: .75rem; margin-top: auto; z-index: 1; }
        .reg-meta div { display: flex; align-items: center; gap: .75rem; }
        .reg-meta i {
            width: 36px; height: 36px; border-radius: 10px; flex: none;
            display: grid; place-items: center; background: rgba(255, 255, 255, .14);
        }
        .reg-meta small { display: block; opacity: .7; font-size: .72rem; }
        .reg-meta b { font-weight: 600; word-break: break-all; }

        .reg-form { padding: 2.25rem; }
        .reg-icon {
            width: 52px; height: 52px; border-radius: 14px; display: grid; place-items: center;
            background: var(--brand-soft); color: var(--brand-text); font-size: 1.5rem; margin-bottom: 1rem;
        }
        .reg-amount {
            display: flex; align-items: center; gap: .5rem; padding: .5rem 1rem;
            border: 1.5px solid var(--border-color); border-radius: 14px; background: var(--bg-color);
            transition: border-color .15s, box-shadow .15s;
        }
        .reg-amount:focus-within { border-color: var(--brand); box-shadow: 0 0 0 .25rem rgba(var(--brand-rgb), .18); }
        .reg-amount.is-invalid { border-color: #dc3545; }
        .reg-amount span { font-size: 1.6rem; font-weight: 700; color: var(--text-muted); }
        .reg-amount input {
            flex: 1; min-width: 0; border: 0; outline: 0; background: transparent; color: var(--text-color);
            font-size: 2.1rem; font-weight: 800; letter-spacing: -.02em; padding: .25rem 0;
        }
        .reg-amount input::placeholder { color: var(--text-muted); opacity: .5; }
        .reg-amount input::-webkit-outer-spin-button,
        .reg-amount input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        .reg-chips { display: flex; flex-wrap: wrap; gap: .5rem; margin-top: .85rem; }
        .reg-chip {
            padding: .4rem .9rem; border-radius: 999px; border: 1px solid var(--border-color);
            background: var(--card-bg); color: var(--text-color); font-weight: 600; font-size: .85rem;
        }
        .reg-chip:hover, .reg-chip.on { border-color: var(--brand); background: var(--brand-soft); color: var(--brand-text); }
        .reg-submit { height: 54px; border-radius: 14px; font-size: 1.05rem; font-weight: 700; }
        @media (max-width: 991.98px) {
            .reg-hero { border-radius: 18px 18px 0 0; padding: 1.5rem; }
            .reg-form { padding: 1.5rem; }
            .reg-time { font-size: 2.1rem; }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">

        {{-- ========================= PAGE HEADER ========================= --}}
        <div class="row align-items-center mb-5">
            <div class="col-md-8">
                {{-- <h1 class="h3 fw-bold mb-2">{{ $pageTitle }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        @foreach ($breadcrumbs as $breadcrumb)
                            @if (!$breadcrumb['active'])
                                <li class="breadcrumb-item">
                                    <a href="{{ $breadcrumb['url'] }}" class="text-decoration-none">
                                        {{ $breadcrumb['label'] }}
                                    </a>
                                </li>
                            @else
                                <li class="breadcrumb-item active text-muted" aria-current="page">
                                    {{ $breadcrumb['label'] }}
                                </li>
                            @endif
                        @endforeach
                    </ol>
                </nav> --}}
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <span class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill ">
                    {{-- <i class="bi bi-hdd-network text-muted"></i> --}}
                    {{-- <span class="text-muted fw-semibold">{{ __('messages.ip_address') }}:</span> --}}
                    {{-- <span class="fw-semibold text-brand">{{ $cashier->ip_address }}</span> --}}
                </span>
            </div>
        </div>

        {{-- ========================= OPEN REGISTER ========================= --}}
        <section class="section">
            <div class="row justify-content-center">
                <div class="col-xl-9 col-xxl-8">

                    @if (session('error'))
                        <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
                            <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                        </div>
                    @endif

                    <div class="card border-0 shadow-sm" style="border-radius: 18px;">
                        <div class="row g-0">

                            {{-- ---------- Left: shift summary ---------- --}}
                            <div class="col-lg-5">
                                <div class="reg-hero">
                                    <span class="reg-pill"><i class="bi bi-circle-fill"></i> Register closed</span>
                                    <div>
                                        <div class="reg-time" id="regTime">--:--</div>
                                        <div class="reg-date" id="regDate"></div>
                                    </div>
                                    <div class="reg-meta">
                                        <div>
                                            <i class="bi bi-person-badge"></i>
                                            <span><small>Cashier</small><b>{{ $cashier->name }}</b></span>
                                        </div>
                                        <div>
                                            <i class="bi bi-shield-check"></i>
                                            <span><small>{{ __('messages.ip_address') }}</small><b>{{ $cashier->ip_address }}</b></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ---------- Right: form ---------- --}}
                            <div class="col-lg-7">
                                <div class="reg-form">
                                    <div class="reg-icon"><i class="bi bi-door-open"></i></div>
                                    <h5 class="fw-bold mb-1">{{ __('messages.open_register') }}</h5>
                                    <p class="text-muted small mb-4">{{ $hint }}</p>

                                    <form method="POST" action="{{ route('pos.open-register.store') }}" id="openRegisterForm">
                                        @csrf

                                        <label for="cash_in_hand" class="form-label fw-semibold">
                                            {{ __('messages.cash_in_hand') }} <span class="text-danger">*</span>
                                        </label>

                                        <div class="reg-amount @error('cash_in_hand') is-invalid @enderror">
                                            <span>$</span>
                                            <input type="number" name="cash_in_hand" id="cash_in_hand" inputmode="decimal"
                                                min="0" step="0.01" placeholder="0.00" autofocus required
                                                value="{{ old('cash_in_hand') }}"
                                                aria-describedby="cashHelp">
                                        </div>
                                        @error('cash_in_hand')
                                            <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror

                                        <div class="reg-chips" role="group" aria-label="Quick amounts">
                                            @foreach ([0, 50, 100, 200, 500] as $amt)
                                                <button type="button" class="reg-chip" data-amt="{{ $amt }}">${{ $amt }}</button>
                                            @endforeach
                                        </div>
                                        <div id="cashHelp" class="form-text mt-2">
                                            Count the drawer first. This becomes the opening balance for your shift.
                                        </div>

                                        <div class="d-grid mt-4">
                                            <button type="submit" class="btn btn-primary reg-submit">
                                                <i class="bi bi-door-open me-2"></i>{{ __('messages.open_register') }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const input = document.getElementById('cash_in_hand');
            const chips = document.querySelectorAll('.reg-chip');

            // Quick amount chips
            const sync = () => chips.forEach(c => c.classList.toggle('on', input.value !== '' && +input.value === +c.dataset.amt));
            chips.forEach(c => c.addEventListener('click', () => { input.value = c.dataset.amt; input.focus(); sync(); }));
            input.addEventListener('input', sync);
            sync();

            // Live clock
            const t = document.getElementById('regTime'), d = document.getElementById('regDate');
            const tick = () => {
                const n = new Date();
                t.textContent = n.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                d.textContent = n.toLocaleDateString([], { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
            };
            tick(); setInterval(tick, 10000);

            // Prevent double submit
            document.getElementById('openRegisterForm').addEventListener('submit', e => {
                const b = e.target.querySelector('button[type="submit"]');
                b.disabled = true;
            });
        });
    </script>
@endpush