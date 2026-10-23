@extends('admin.layouts.master')

@section('title', __('messages.add_billers'))

@php
    use Illuminate\Support\Facades\Lang;
    $t = fn($key, $fallback) => Lang::has('messages.' . $key) ? __('messages.' . $key) : $fallback;

    // name => [label, input type, icon, placeholder]
    $f = [
        'name' => [__('messages.name'), 'text', 'person-badge', ''],
        'email' => [__('messages.email'), 'email', 'envelope', 'name@example.com'],
        'phone' => [__('messages.phone'), 'tel', 'telephone', '+855 12 345 678'],
        'address' => [__('messages.address'), 'text', 'geo-alt', ''],
        'street' => [__('messages.street'), 'text', 'signpost-split', ''],
        'number_of_houses' => [__('messages.number_of_houses'), 'text', 'house-door', ''],
        'city' => [__('messages.city'), 'text', 'buildings', ''],
        'note' => [__('messages.note'), 'textarea', 'sticky', ''],
    ];

    // Every field except the logo is required by your form.
    $field = function (string $name) use ($f, $errors) {
        [$label, $type, $icon, $ph] = $f[$name];
        $bad = $errors->has($name) ? ' is-invalid' : '';
        $val = e(old($name));
        $input = $type === 'textarea'
            ? '<textarea name="' . $name . '" id="' . $name . '" rows="4" class="form-control' . $bad . '" placeholder="' . e($ph) . '" required>' . $val . '</textarea>'
            : '<input type="' . $type . '" name="' . $name . '" id="' . $name . '" value="' . $val . '" class="form-control' . $bad . '" placeholder="' . e($ph) . '" required>';
        $err = $errors->has($name) ? '<div class="invalid-feedback d-block">' . e($errors->first($name)) . '</div>' : '';
        return '<label for="' . $name . '" class="form-label fw-semibold">' . e($label) . ' <span class="text-danger">*</span></label>'
            . '<div class="bc-input' . ($type === 'textarea' ? ' bc-area' : '') . '"><i class="bi bi-' . $icon . '"></i>' . $input . '</div>' . $err;
    };
@endphp

@push('styles')
    <style>
        .bc-card { padding: 1.5rem; }
        .bc-title { display: flex; align-items: center; gap: .75rem; margin-bottom: 1.25rem; }
        .bc-title i { width: 38px; height: 38px; border-radius: 10px; display: grid; place-items: center; background: rgba(var(--brand-rgb), .14); color: var(--brand-text); }
        .bc-title h2 { font-size: 1.02rem; font-weight: 700; margin: 0; }
        .bc-title small { display: block; color: var(--text-muted); font-weight: 500; font-size: .78rem; }

        .bc-input { position: relative; }
        .bc-input > i { position: absolute; left: .9rem; top: 50%; transform: translateY(-50%); color: var(--text-muted); pointer-events: none; z-index: 2; }
        .bc-input .form-control, .bc-input .form-select { height: 46px; padding-left: 2.6rem; border-radius: 12px; }
        .bc-area > i { top: 1.35rem; }
        .bc-area .form-control { height: auto; padding-top: .75rem; resize: vertical; min-height: 110px; }

        .bc-side { text-align: center; position: sticky; top: calc(var(--header-height) + 1rem); }
        .bc-drop { display: block; cursor: pointer; padding: 1.5rem 1rem; border: 2px dashed var(--border-color); border-radius: 16px; transition: border-color .15s, background .15s; }
        .bc-drop:hover, .bc-drop.drag { border-color: var(--brand); background: rgba(var(--brand-rgb), .06); }
        .bc-preview {
            width: 110px; height: 110px; margin: 0 auto .9rem; border-radius: 50%; overflow: hidden; display: grid; place-items: center;
            background: var(--brand); color: #fff; font-size: 2.6rem; font-weight: 800; box-shadow: 0 12px 30px rgba(var(--brand-rgb), .3);
        }
        .bc-preview img { width: 100%; height: 100%; object-fit: cover; }
        .bc-drop input { display: none; }
        .bc-bar {
            position: sticky; bottom: 0; z-index: 5; display: flex; justify-content: flex-end; gap: .6rem; flex-wrap: wrap;
            padding: .9rem 1.25rem; border: 1px solid var(--border-color); border-radius: 16px; background: var(--card-bg);
            box-shadow: 0 -8px 24px rgba(16, 24, 40, .08);
        }
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

        <form method="POST" action="{{ route('billers.store') }}" enctype="multipart/form-data" id="billerForm">
            @csrf

            <div class="row g-4">

                {{-- ============ Logo ============ --}}
                <div class="col-lg-4">
                    <div class="card bc-card bc-side">
                        <div class="bc-title justify-content-center text-start">
                            <i class="bi bi-image"></i>
                            <h2>{{ __('messages.logo') }}<small>{{ $t('optional', 'Optional') }}</small></h2>
                        </div>

                        <label class="bc-drop" id="drop" for="logo">
                            <div class="bc-preview" id="preview"><span id="initial">B</span></div>
                            <div class="fw-semibold">{{ $t('upload_logo', 'Click to upload or drop an image') }}</div>
                            <div class="text-muted small mt-1" id="fileName">{{ $t('logo_format', 'PNG or JPG. A square image works best.') }}</div>
                            <input type="file" name="logo" id="logo" accept="image/*">
                        </label>
                        @error('logo')
                        <div class="text-danger small mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- ============ Fields ============ --}}
                <div class="col-lg-8 d-flex flex-column gap-4">

                    <div class="card bc-card">
                        <div class="bc-title"><i class="bi bi-person-lines-fill"></i>
                            <h2>{{ $t('biller_details', 'Biller details') }}<small>{{ $t('biller_hint', 'Who they are and where they work') }}</small></h2>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">{!! $field('name') !!}</div>
                            <div class="col-md-6">{!! $field('email') !!}</div>
                            <div class="col-md-6">{!! $field('phone') !!}</div>
                            <div class="col-md-6">
                                <label for="warehouse_id" class="form-label fw-semibold">{{ __('messages.warehouse') }} <span class="text-danger">*</span></label>
                                <div class="bc-input">
                                    <i class="bi bi-house-gear"></i>
                                    <select name="warehouse_id" id="warehouse_id" class="form-select @error('warehouse_id') is-invalid @enderror" required>
                                        <option value="">{{ __('messages.select_warehouse') }}</option>
                                        {{-- Your original reused $warehouse as the loop variable, which overwrote it. Fixed. --}}
                                        @foreach ($warehouse as $wh)
                                            <option value="{{ $wh->id }}" @selected(old('warehouse_id') == $wh->id)>{{ $wh->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('warehouse_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card bc-card">
                        <div class="bc-title"><i class="bi bi-geo-alt"></i>
                            <h2>{{ $t('address_info', 'Address') }}<small>{{ $t('address_hint', 'Where to find them') }}</small></h2>
                        </div>
                        <div class="row g-3">
                            <div class="col-12">{!! $field('address') !!}</div>
                            <div class="col-md-4">{!! $field('number_of_houses') !!}</div>
                            <div class="col-md-4">{!! $field('street') !!}</div>
                            <div class="col-md-4">{!! $field('city') !!}</div>
                        </div>
                    </div>

                    <div class="card bc-card">
                        <div class="bc-title"><i class="bi bi-sticky"></i>
                            <h2>{{ __('messages.note') }}<small>{{ $t('note_hint', 'Anything the team should know') }}</small></h2>
                        </div>
                        {!! $field('note') !!}
                    </div>

                    {{-- ============ Actions ============ --}}
                    <div class="bc-bar">
                        <a href="{{ route('billers.index') }}" class="btn btn-outline-custom">
                            <i class="bi bi-x-circle me-1"></i>{{ __('messages.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary px-4" id="saveBtn">
                            <i class="bi bi-save me-1"></i>{{ __('messages.submit') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            const form = document.getElementById('billerForm');
            const input = document.getElementById('logo');
            const preview = document.getElementById('preview');
            const drop = document.getElementById('drop');
            const fileName = document.getElementById('fileName');
            const nameEl = document.getElementById('name');
            let hasImage = false;

            // Letter avatar follows the name until a logo is chosen
            const setInitial = () => {
                if (hasImage) return;
                preview.innerHTML = '';
                const s = document.createElement('span');
                s.textContent = (nameEl.value.trim()[0] || 'B').toUpperCase();
                preview.appendChild(s);
            };
            nameEl.addEventListener('input', setInitial); setInitial();

            function showFile(file) {
                if (!file || !file.type.startsWith('image/')) return;
                const reader = new FileReader();
                reader.onload = e => {
                    hasImage = true; preview.innerHTML = '';
                    const img = new Image(); img.src = e.target.result; img.alt = '';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
                fileName.textContent = file.name;
            }
            input.addEventListener('change', () => showFile(input.files[0]));
            ['dragenter', 'dragover'].forEach(ev => drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.add('drag'); }));
            ['dragleave', 'drop'].forEach(ev => drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.remove('drag'); }));
            drop.addEventListener('drop', e => { if (e.dataTransfer.files.length) { input.files = e.dataTransfer.files; showFile(input.files[0]); } });

            // Prevent double submit (only once the browser says the form is valid)
            form.addEventListener('submit', () => {
                if (!form.checkValidity()) return;
                const b = document.getElementById('saveBtn');
                b.disabled = true;
                b.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>' + b.textContent.trim();
            });
        })();
    </script>
@endpush
