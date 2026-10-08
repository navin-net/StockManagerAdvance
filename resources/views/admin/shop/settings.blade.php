@extends('admin.layouts.master')

@section('title', __('messages.shop_settings'))

@php
    use Illuminate\Support\Facades\Lang;
    $t = fn($key, $fallback) => Lang::has('messages.' . $key) ? __('messages.' . $key) : $fallback;

    $logo = isset($shop) && $shop->logo_shop ? asset('storage/' . $shop->logo_shop) : null;
    $initial = mb_strtoupper(mb_substr($shop->name_shop ?? 'S', 0, 1));

    // name => [label, input type, icon, placeholder]
    $f = [
        'name_shop' => [__('messages.name_shop'), 'text', 'shop', ''],
        'description' => [__('messages.description'), 'textarea', 'card-text', ''],
        'phone' => [__('messages.phone'), 'tel', 'telephone', '+855 12 345 678'],
        'email' => [__('messages.email'), 'email', 'envelope', 'shop@example.com'],
        'address' => [__('messages.address'), 'text', 'geo-alt', ''],
        'open_shop_time' => [__('messages.open_shop_time'), 'text', 'sunrise', '08:00 AM'],
        'close_shop' => [__('messages.close_shop'), 'text', 'moon', '09:00 PM'],
        'x' => [__('messages.x'), 'text', 'twitter-x', 'https://x.com/yourshop'],
    ];

    // One reusable field renderer ($errors must be captured explicitly inside a closure)
    $shop = $shop ?? null;
    $field = function (string $name) use ($f, $shop, $errors) {
        [$label, $type, $icon, $ph] = $f[$name];
        $val = old($name, $shop->{$name} ?? '');
        $bad = $errors->has($name) ? ' is-invalid' : '';
        $req = $name === 'name_shop' ? ' required' : '';
        $star = $name === 'name_shop' ? ' <span class="text-danger">*</span>' : '';
        $input = $type === 'textarea'
            ? '<textarea name="' . $name . '" id="' . $name . '" rows="3" class="form-control' . $bad . '" placeholder="' . e($ph) . '">' . e($val) . '</textarea>'
            : '<input type="' . $type . '" name="' . $name . '" id="' . $name . '" value="' . e($val) . '" class="form-control' . $bad . '" placeholder="' . e($ph) . '"' . $req . '>';
        $err = $errors->has($name) ? '<div class="invalid-feedback d-block">' . e($errors->first($name)) . '</div>' : '';
        return '<label for="' . $name . '" class="form-label fw-semibold">' . e($label) . $star . '</label>'
            . '<div class="ss-input' . ($type === 'textarea' ? ' ss-area' : '') . '"><i class="bi bi-' . $icon . '"></i>' . $input . '</div>' . $err;
    };
@endphp

@push('styles')
    <style>
        .ss-card { padding: 1.5rem; }
        .ss-title { display: flex; align-items: center; gap: .75rem; margin-bottom: 1.25rem; }
        .ss-title i { width: 38px; height: 38px; border-radius: 10px; display: grid; place-items: center; background: rgba(var(--brand-rgb), .14); color: var(--brand-text); }
        .ss-title h2 { font-size: 1.02rem; font-weight: 700; margin: 0; }
        .ss-title small { display: block; color: var(--text-muted); font-weight: 500; font-size: .78rem; }

        .ss-input { position: relative; }
        .ss-input > i { position: absolute; left: .9rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); pointer-events: none; }
        .ss-input .form-control { height: 46px; padding-left: 2.6rem; border-radius: 12px; }
        .ss-area > i { top: 1.35rem; }
        .ss-area .form-control { height: auto; padding-top: .75rem; resize: vertical; min-height: 96px; }

        .ss-logo { text-align: center; position: sticky; top: calc(var(--header-height) + 1rem); }
        .ss-drop {
            display: block; cursor: pointer; padding: 1.5rem 1rem; border: 2px dashed var(--border-color); border-radius: 16px;
            transition: border-color .15s, background .15s;
        }
        .ss-drop:hover, .ss-drop.drag { border-color: var(--brand); background: rgba(var(--brand-rgb), .06); }
        .ss-preview {
            width: 120px; height: 120px; margin: 0 auto .9rem; border-radius: 28px; overflow: hidden; display: grid; place-items: center;
            background: var(--brand); color: #fff; font-size: 2.6rem; font-weight: 800; box-shadow: 0 12px 30px rgba(var(--brand-rgb), .3);
        }
        .ss-preview img { width: 100%; height: 100%; object-fit: cover; }
        .ss-drop input { display: none; }
        .ss-savebar {
            position: sticky; bottom: 0; z-index: 5; display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;
            padding: .9rem 1.25rem; border: 1px solid var(--border-color); border-radius: 16px; background: var(--card-bg);
            box-shadow: 0 -8px 24px rgba(16, 24, 40, .08);
        }
        .ss-dirty { color: var(--text-muted); font-size: .85rem; display: none; align-items: center; gap: .5rem; }
        .ss-dirty.on { display: inline-flex; }
        .ss-dirty i { width: 8px; height: 8px; border-radius: 50%; background: #F59E0B; }
    </style>
@endpush

@section('content')
    <div class="container-fluid">

        {{-- ============ Page header ============ --}}
        <div class="row align-items-center mb-4">
            <div class="col-md-8">
                <h1 class="h3 fw-bold mb-1">{{ $pageTitle }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 small">
                        @foreach ($breadcrumbs as $breadcrumb)
                            @if (!$breadcrumb['active'])
                                <li class="breadcrumb-item">
                                    <a href="{{ $breadcrumb['url'] }}" class="text-decoration-none">{{ $breadcrumb['label'] }}</a>
                                </li>
                            @else
                                <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb['label'] }}</li>
                            @endif
                        @endforeach
                    </ol>
                </nav>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <span class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill border small">
                    <i class="bi bi-hdd-network text-muted"></i>
                    <span class="text-muted fw-semibold">{{ __('messages.ip_address') }}:</span>
                    <span class="fw-semibold">{{ auth()->user()->ip_address }}</span>
                </span>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-check-circle-fill"></i><div>{{ session('success') }}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i><div>{{ $t('check_fields', 'Please check the highlighted fields.') }}</div>
            </div>
        @endif

        <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data" id="shopForm">
            @csrf

            <div class="row g-4">

                {{-- ============ Logo ============ --}}
                <div class="col-lg-4">
                    <div class="card ss-card ss-logo">
                        <div class="ss-title justify-content-center text-start">
                            <i class="bi bi-image"></i>
                            <h2>{{ __('messages.logo_shop') }}<small>{{ $t('logo_hint', 'Shown on the sidebar, receipts and login page') }}</small></h2>
                        </div>

                        <label class="ss-drop" id="drop" for="logo_shop">
                            <div class="ss-preview" id="preview">
                                @if ($logo)
                                    <img src="{{ $logo }}" alt="{{ __('messages.logo_shop') }}">
                                @else
                                    <span>{{ $initial }}</span>
                                @endif
                            </div>
                            <div class="fw-semibold">{{ $t('upload_logo', 'Click to upload or drop an image') }}</div>
                            <div class="text-muted small mt-1" id="fileName">{{ $t('logo_format', 'PNG or JPG. A square image works best.') }}</div>
                            <input type="file" name="logo_shop" id="logo_shop" accept="image/*">
                        </label>
                        @error('logo_shop')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- ============ Fields ============ --}}
                <div class="col-lg-8 d-flex flex-column gap-4">

                    <div class="card ss-card">
                        <div class="ss-title"><i class="bi bi-shop"></i>
                            <h2>{{ $t('shop_profile', 'Shop profile') }}<small>{{ $t('shop_profile_hint', 'Name and short description of your shop') }}</small></h2>
                        </div>
                        <div class="mb-3">{!! $field('name_shop') !!}</div>
                        <div>{!! $field('description') !!}</div>
                    </div>

                    <div class="card ss-card">
                        <div class="ss-title"><i class="bi bi-telephone"></i>
                            <h2>{{ $t('contact_info', 'Contact') }}<small>{{ $t('contact_hint', 'How customers can reach you') }}</small></h2>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">{!! $field('phone') !!}</div>
                            <div class="col-md-6">{!! $field('email') !!}</div>
                            <div class="col-12">{!! $field('address') !!}</div>
                        </div>
                    </div>

                    <div class="card ss-card">
                        <div class="ss-title"><i class="bi bi-clock"></i>
                            <h2>{{ $t('hours_social', 'Opening hours & social') }}<small>{{ $t('hours_hint', 'Shown on your storefront') }}</small></h2>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">{!! $field('open_shop_time') !!}</div>
                            <div class="col-md-6">{!! $field('close_shop') !!}</div>
                            <div class="col-12">{!! $field('x') !!}</div>
                        </div>
                    </div>

                    {{-- ============ Save bar ============ --}}
                    <div class="ss-savebar">
                        <span class="ss-dirty" id="dirty"><i></i>{{ $t('unsaved', 'You have unsaved changes') }}</span>
                        <div class="d-flex gap-2 ms-auto">
                            <button type="reset" class="btn btn-outline-custom" id="resetBtn">{{ $t('reset', 'Reset') }}</button>
                            <button type="submit" class="btn btn-primary px-4" id="saveBtn">
                                <i class="bi bi-check2-circle me-1"></i>{{ __('messages.save_changes') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            const form = document.getElementById('shopForm');
            const input = document.getElementById('logo_shop');
            const preview = document.getElementById('preview');
            const drop = document.getElementById('drop');
            const fileName = document.getElementById('fileName');
            const dirty = document.getElementById('dirty');
            const original = preview.innerHTML;
            const hint = fileName.textContent;

            // Live logo preview (also for drag & drop)
            function showFile(file) {
                if (!file || !file.type.startsWith('image/')) return;
                const reader = new FileReader();
                reader.onload = e => {
                    preview.innerHTML = '';
                    const img = new Image(); img.src = e.target.result; img.alt = '';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
                fileName.textContent = file.name;
                dirty.classList.add('on');
            }
            input.addEventListener('change', () => showFile(input.files[0]));
            ['dragenter', 'dragover'].forEach(ev => drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.add('drag'); }));
            ['dragleave', 'drop'].forEach(ev => drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.remove('drag'); }));
            drop.addEventListener('drop', e => {
                if (e.dataTransfer.files.length) { input.files = e.dataTransfer.files; showFile(input.files[0]); }
            });

            // Unsaved-changes hint + reset
            form.addEventListener('input', () => dirty.classList.add('on'));
            form.addEventListener('reset', () => {
                setTimeout(() => { preview.innerHTML = original; fileName.textContent = hint; dirty.classList.remove('on'); });
            });
            window.addEventListener('beforeunload', e => { if (dirty.classList.contains('on') && !form.dataset.saving) { e.preventDefault(); e.returnValue = ''; } });

            // Prevent double submit
            form.addEventListener('submit', () => {
                form.dataset.saving = '1';
                const b = document.getElementById('saveBtn');
                b.disabled = true;
                b.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>' + b.textContent.trim();
            });
        })();
    </script>
@endpush
