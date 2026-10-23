@extends('admin.layouts.master')
@section('title', __('messages.add_products'))

@php
    // Falls back to English if a translation key hasn't been added yet.
    $t = fn($key, $default) => \Illuminate\Support\Facades\Lang::has("messages.$key") ? __("messages.$key") : $default;

    // Strings used by the JavaScript below.
    $i18n = [
        'loading' => __('messages.Loading'),
        'selectSub' => __('messages.select_subcategory'),
        'selectSubs' => __('messages.select_sub_categories'),
        'submit' => __('messages.submit'),
        'saving' => $t('saving', 'Saving...'),
        'fixErrors' => $t('please_correct_errors', 'Please correct the errors below.'),
        'csrf' => $t('csrf_mismatch', 'Your session expired. Please refresh the page and try again.'),
        'server' => $t('server_error', 'Server error occurred. Please try again later.'),
        'error' => $t('error', 'Error'),
        'subFail' => $t('subcategories_failed', 'Could not load subcategories.'),
    ];
@endphp

@section('content')
    <style>
        /* ==========================================================
           Add product - built on layout-v2.css tokens
           ========================================================== */
        .pl-card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 14px; margin-bottom: 1rem; }
        .pl-card:hover { transform: none; box-shadow: 0 1px 2px rgba(16, 24, 40, .05); }
        .pl-card-h { padding: 1rem 1.25rem .9rem; border-bottom: 1px solid var(--border-color); }
        .pl-card-h h2 { font-size: 1rem; font-weight: 700; margin: 0; }
        .pl-card-h p { margin: .15rem 0 0; font-size: .8rem; color: var(--text-muted); }
        .pl-card-b { padding: 1.25rem; }

        .pl-head { display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: .75rem 1rem; margin-bottom: 1.25rem; }
        .pl-head h1 { font-size: 1.5rem; font-weight: 800; letter-spacing: -.02em; margin: 0 0 .35rem; }
        .pl-head .breadcrumb { font-size: .8rem; }
        .pl-chip { display: inline-flex; align-items: center; gap: .45rem; padding: .35rem .75rem; border-radius: 999px; border: 1px solid var(--border-color); background: var(--card-bg); font-size: .78rem; color: var(--text-muted); }
        .pl-chip b { color: var(--text-color); font-weight: 600; }

        .pl-form .form-label { font-weight: 600; font-size: .84rem; margin-bottom: .35rem; }
        .pl-form .form-control, .pl-form .form-select { height: 42px; }
        .pl-form textarea.form-control { height: auto; }
        .pl-form .req { color: #E5484D; }
        /* errors also show for inputs wrapped in an input-group */
        .invalid-feedback:not(:empty) { display: block; }

        /* price margin readout */
        .pl-margin { display: flex; flex-wrap: wrap; gap: .5rem 1.5rem; padding: .75rem 1rem; border-radius: 12px; background: var(--hover-bg); font-size: .86rem; color: var(--text-muted); }
        .pl-margin b { color: var(--text-color); font-variant-numeric: tabular-nums; }
        .pl-margin b.neg { color: #E5484D; }
        .pl-margin b.pos { color: #1E9A52; }

        /* image drop areas */
        .pl-drop { position: relative; display: grid; place-items: center; text-align: center; min-height: 190px; border: 1.5px dashed var(--border-color); border-radius: 12px; background: var(--bg-color); color: var(--text-muted); overflow: hidden; transition: border-color .15s, background .15s; }
        .pl-drop:hover, .pl-drop:focus-within, .pl-drop.drag { border-color: var(--brand); background: var(--brand-soft); }
        .pl-drop input[type=file] { position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; }
        .pl-drop .hint { padding: 1rem; pointer-events: none; }
        .pl-drop .hint i { display: block; font-size: 1.8rem; margin-bottom: .35rem; }
        .pl-drop .hint b { display: block; color: var(--text-color); font-size: .88rem; }
        .pl-drop .hint span { font-size: .76rem; }
        .pl-drop img.pv { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; display: none; pointer-events: none; }
        .pl-drop.has-img { border-style: solid; }
        .pl-drop.has-img img.pv { display: block; }
        .pl-drop.has-img .hint { display: none; }
        .pl-drop.sm { min-height: 96px; }
        .pl-drop.sm .hint i { font-size: 1.3rem; }

        .pl-gallery { display: grid; grid-template-columns: repeat(auto-fill, minmax(64px, 1fr)); gap: .5rem; margin-top: .75rem; }
        .pl-gallery:empty { display: none; }
        .pl-gallery img { width: 100%; aspect-ratio: 1; object-fit: cover; border-radius: 10px; border: 1px solid var(--border-color); }

        .pl-sticky { position: sticky; top: calc(var(--header-height) + 1rem); }
        .pl-sticky .pl-card { margin-bottom: 0; }

        @media (max-width: 991.98px) { .pl-sticky { position: static; } }
    </style>

    <div class="container-fluid p-0">

        {{-- page head --}}
        <div class="pl-head">
            <div>
                <h1>{{ $pageTitle }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        @foreach ($breadcrumbs as $breadcrumb)
                            @if (!$breadcrumb['active'])
                                <li class="breadcrumb-item">
                                    <a href="{{ $breadcrumb['url'] }}" class="text-decoration-none">{{ $breadcrumb['label'] }}</a>
                                </li>
                            @else
                                <li class="breadcrumb-item active text-muted" aria-current="page">{{ $breadcrumb['label'] }}</li>
                            @endif
                        @endforeach
                    </ol>
                </nav>
            </div>
            <span class="pl-chip">
                <i class="bi bi-hdd-network"></i>
                {{ __('messages.ip_address') }}: <b>{{ auth()->user()->ip_address }}</b>
            </span>
        </div>

        <div id="formError" class="alert alert-danger d-none" role="alert"></div>

        <form id="createProductForm" class="pl-form" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">

                {{-- main column --}}
                <div class="col-lg-8">

                    <div class="pl-card">
                        <div class="pl-card-h">
                            <h2>{{ $t('basic_info', 'Basic information') }}</h2>
                        </div>
                        <div class="pl-card-b">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">{{ __('messages.name') }} <span class="req">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control" required>
                                    <div class="invalid-feedback" id="name_error"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="second_name" class="form-label">{{ __('messages.second_name') }} <span class="req">*</span></label>
                                    <input type="text" name="second_name" id="second_name" class="form-control" required>
                                    <div class="invalid-feedback" id="second_name_error"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="code" class="form-label">{{ __('messages.code') }}</label>
                                    <div class="input-group">
                                        <input type="text" name="code" id="code" class="form-control">
                                        <button type="button" class="btn btn-outline-custom" id="generateCodeBtn">
                                            <i class="bi bi-shuffle me-1"></i>{{ __('messages.generate') }}
                                        </button>
                                    </div>
                                    <div class="invalid-feedback" id="code_error"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pl-card">
                        <div class="pl-card-h">
                            <h2>{{ $t('classification', 'Classification') }}</h2>
                        </div>
                        <div class="pl-card-b">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="brand_id" class="form-label">{{ __('messages.brand') }} <span class="req">*</span></label>
                                    <select name="brand_id" id="brand_id" class="form-select" required>
                                        <option value="">{{ __('messages.select_brand') }}</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="brand_id_error"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="quality_id" class="form-label">{{ __('messages.quality') }} <span class="req">*</span></label>
                                    <select name="quality_id" id="quality_id" class="form-select" required>
                                        <option value="">{{ __('messages.select_quality') }}</option>
                                        @foreach ($qualities as $quality)
                                            <option value="{{ $quality->id }}">{{ $quality->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="quality_id_error"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="category_id" class="form-label">{{ __('messages.category') }} <span class="req">*</span></label>
                                    <select name="category_id" id="category_id" class="form-select" required>
                                        <option value="">{{ __('messages.select_category') }}</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="category_id_error"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="subcategory_id" class="form-label">{{ __('messages.subcategory') }}</label>
                                    <select name="subcategory_id" id="subcategory_id" class="form-select">
                                        <option value="">{{ __('messages.select_subcategory') }}</option>
                                    </select>
                                    <div class="invalid-feedback" id="subcategory_id_error"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="unit_id" class="form-label">{{ __('messages.unit') }} <span class="req">*</span></label>
                                    <select name="unit_id" id="unit_id" class="form-select" required>
                                        <option value="">{{ __('messages.select_unit') }}</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="unit_id_error"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pl-card">
                        <div class="pl-card-h">
                            <h2>{{ $t('pricing', 'Pricing') }}</h2>
                        </div>
                        <div class="pl-card-b">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="cost_price" class="form-label">{{ __('messages.cost_price') }} <span class="req">*</span></label>
                                    <input type="number" name="cost_price" id="cost_price" class="form-control" step="0.01" min="0" required>
                                    <div class="invalid-feedback" id="cost_price_error"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="selling_price" class="form-label">{{ __('messages.selling_price') }} <span class="req">*</span></label>
                                    <input type="number" name="selling_price" id="selling_price" class="form-control" step="0.01" min="0" required>
                                    <div class="invalid-feedback" id="selling_price_error"></div>
                                </div>
                                <div class="col-12">
                                    <div class="pl-margin" aria-live="polite">
                                        <span>{{ $t('profit', 'Profit') }}: <b id="profitVal">-</b></span>
                                        <span>{{ $t('margin', 'Margin') }}: <b id="marginVal">-</b></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pl-card">
                        <div class="pl-card-h">
                            <h2>{{ __('messages.description') }}</h2>
                        </div>
                        <div class="pl-card-b">
                            <textarea name="description" id="description" class="form-control" rows="5" aria-label="{{ __('messages.description') }}"></textarea>
                            <div class="invalid-feedback" id="description_error"></div>
                        </div>
                    </div>
                </div>

                {{-- side column --}}
                <div class="col-lg-4">
                    <div class="pl-sticky">
                        <div class="pl-card">
                            <div class="pl-card-h">
                                <h2>{{ $t('images', 'Images') }}</h2>
                            </div>
                            <div class="pl-card-b">
                                <label for="image" class="form-label fw-semibold small">{{ __('messages.image') }}</label>
                                <div class="pl-drop" id="mainDrop">
                                    <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/jpg">
                                    <img class="pv" alt="">
                                    <div class="hint">
                                        <i class="bi bi-image"></i>
                                        <b>{{ $t('choose_image', 'Choose or drop an image') }}</b>
                                        <span>JPG, PNG</span>
                                    </div>
                                </div>
                                <div class="invalid-feedback" id="image_error"></div>

                                <label for="image_review" class="form-label fw-semibold small mt-3">{{ __('messages.image_review') }}</label>
                                <div class="pl-drop sm" id="galleryDrop">
                                    <input type="file" name="image_review[]" id="image_review" multiple accept="image/jpeg,image/png,image/jpg">
                                    <div class="hint">
                                        <i class="bi bi-images"></i>
                                        <b>{{ $t('choose_images', 'Add more images') }}</b>
                                    </div>
                                </div>
                                <div class="pl-gallery" id="galleryPreview"></div>
                                <div class="invalid-feedback" id="image_review_error"></div>

                                <div class="d-grid gap-2 mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg fs-6" id="submitBtn">
                                        <i class="bi bi-save me-1"></i>{{ __('messages.submit') }}
                                    </button>
                                    <a href="{{ route('products.index') }}" class="btn btn-outline-custom">
                                        <i class="bi bi-x-circle me-1"></i>{{ __('messages.cancel') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        const I18N = @json($i18n);

        const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
        }[c]));

        function generateRandomCode() {
            const prefix = 'P0';
            const randomNumber = Math.floor(Math.random() * 100000);
            return prefix + String(randomNumber).padStart(5, '0');
        }

        $(document).ready(function () {

            /* ---------- second name follows name until edited by hand ---------- */
            let secondDirty = false;
            $('#second_name').on('input', function () { secondDirty = true; });
            $('#name').on('input', function () {
                if (!secondDirty) $('#second_name').val(this.value.toLowerCase());
            });

            /* ---------- code ---------- */
            $('#code').val(generateRandomCode());
            $('#generateCodeBtn').on('click', function () { $('#code').val(generateRandomCode()); });

            /* ---------- subcategories ---------- */
            $('#category_id').on('change', function () {
                const categoryId = $(this).val();
                const $sub = $('#subcategory_id');

                if (!categoryId) {
                    $sub.prop('disabled', false).html(`<option value="">${esc(I18N.selectSub)}</option>`);
                    return;
                }

                $sub.prop('disabled', true).html(`<option value="">${esc(I18N.loading)}</option>`);

                $.ajax({
                    url: "{{ route('products.subcategories', ':id') }}".replace(':id', categoryId),
                    type: 'GET',
                    success: function (data) {
                        $sub.prop('disabled', false).html(`<option value="">${esc(I18N.selectSubs)}</option>`);
                        $.each(data, function (key, sc) {
                            $sub.append(`<option value="${esc(sc.id)}">${esc(sc.name)}</option>`);
                        });
                    },
                    error: function () {
                        $sub.prop('disabled', false).html(`<option value="">${esc(I18N.selectSub)}</option>`);
                        $('#subcategory_id_error').text(I18N.subFail);
                    }
                });
            });

            /* ---------- profit / margin readout ---------- */
            function updateMargin() {
                const cost = parseFloat($('#cost_price').val());
                const sell = parseFloat($('#selling_price').val());
                const $p = $('#profitVal'), $m = $('#marginVal');

                if (isNaN(cost) || isNaN(sell)) {
                    $p.text('-').removeClass('pos neg');
                    $m.text('-').removeClass('pos neg');
                    return;
                }
                const profit = sell - cost;
                const cls = profit < 0 ? 'neg' : 'pos';
                $p.text(profit.toFixed(2)).removeClass('pos neg').addClass(cls);
                $m.text(sell > 0 ? (profit / sell * 100).toFixed(1) + '%' : '-').removeClass('pos neg').addClass(cls);
            }
            $('#cost_price, #selling_price').on('input', updateMargin);

            /* ---------- image previews ---------- */
            let mainUrl = null;
            $('#image').on('change', function () {
                const $drop = $('#mainDrop');
                if (mainUrl) URL.revokeObjectURL(mainUrl);
                const file = this.files && this.files[0];
                if (file) {
                    mainUrl = URL.createObjectURL(file);
                    $drop.addClass('has-img').find('img.pv').attr('src', mainUrl);
                } else {
                    mainUrl = null;
                    $drop.removeClass('has-img').find('img.pv').removeAttr('src');
                }
            });

            let galleryUrls = [];
            $('#image_review').on('change', function () {
                galleryUrls.forEach(u => URL.revokeObjectURL(u));
                galleryUrls = [];
                const $g = $('#galleryPreview').empty();
                Array.from(this.files || []).forEach(file => {
                    const url = URL.createObjectURL(file);
                    galleryUrls.push(url);
                    $g.append($('<img>', { src: url, alt: '' }));
                });
            });

            $('.pl-drop').on('dragenter dragover', function () { $(this).addClass('drag'); })
                .on('dragleave drop', function () { $(this).removeClass('drag'); });

            /* ---------- submit ---------- */
            function showFormError(msg) {
                $('#formError').removeClass('d-none').text(msg);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }

            $('#createProductForm').on('submit', function (e) {
                e.preventDefault();
                $('#formError').addClass('d-none').text('');
                $('.invalid-feedback').text('');
                $('.form-control, .form-select').removeClass('is-invalid');

                const formData = new FormData(this);
                const $btn = $('#submitBtn').prop('disabled', true).html(
                    `<span class="spinner-border spinner-border-sm me-1"></span>${esc(I18N.saving)}`
                );

                $.ajax({
                    url: '{{ route('products.store') }}',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        window.location.href = response.redirect;
                    },
                    error: function (xhr) {
                        $btn.prop('disabled', false).html(`<i class="bi bi-save me-1"></i>${esc(I18N.submit)}`);
                        console.error('Form submission error:', xhr.status, xhr.responseText);

                        if (xhr.status === 422) {
                            const errors = (xhr.responseJSON && xhr.responseJSON.errors) || {};
                            $.each(errors, function (key, value) {
                                // image_review.0, image_review.1 ... share one message box
                                const base = key.split('.')[0];
                                const $err = $('#' + base + '_error');
                                if (!$err.text()) $err.text(value[0]);
                                $('#' + base).addClass('is-invalid');
                            });
                            showFormError(I18N.fixErrors);
                            $('.is-invalid').first().trigger('focus');
                        } else if (xhr.status === 419) {
                            showFormError(I18N.csrf);
                        } else if (xhr.status === 500) {
                            showFormError((xhr.responseJSON && xhr.responseJSON.error) || I18N.server);
                        } else {
                            showFormError(`${I18N.error} (${xhr.status}). ${I18N.server}`);
                        }
                    }
                });
            });
        });
    </script>
@endpush
