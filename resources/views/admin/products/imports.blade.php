@extends('admin.layouts.master')

@section('title', __($pageTitle))

@section('content')
    <div class="container-fluid im">

        {{-- PAGE HEADER --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <div>
                <h1 class="h4 fw-bold mb-1">{{ $pageTitle }}</h1>
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
            <div class="im-chip">
                <i class="bi bi-hdd-network"></i>
                <span>{{ __('messages.ip_address') }}</span>
                <strong>{{ auth()->user()->ip_address }}</strong>
            </div>
        </div>

        {{-- Filled by the script after each import --}}
        <div id="importAlert" aria-live="polite"></div>

        <div class="row g-4">

            {{-- STEPS --}}
            <div class="col-xl-8">
                <form id="importForm" action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" novalidate>
                    @csrf

                    {{-- Step 1 --}}
                    <section class="im-card im-step">
                        <span class="im-num">1</span>
                        <div class="im-step__body">
                            <h2 class="im-title">Download the template</h2>
                            <p class="im-muted mb-3">Fill it in with your products. Keep the first row, it holds the column names.</p>
                            <a href="{{ asset('backend/file/products.csv') }}" download class="btn btn-outline-primary">
                                <i class="bi bi-download me-2"></i>{{ __('messages.download_file_excel') }}
                            </a>
                        </div>
                    </section>

                    {{-- Step 2 --}}
                    <section class="im-card im-step">
                        <span class="im-num">2</span>
                        <div class="im-step__body">
                            <h2 class="im-title">{{ __('messages.upload_excel_file') }}</h2>

                            <input type="file" name="file" id="excelInput" class="d-none"
                                   accept=".csv,.xlsx,.xls,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel">

                            <label for="excelInput" class="im-drop" id="dropZone">
                                <i class="bi bi-file-earmark-spreadsheet" id="dropIcon"></i>
                                <span class="im-drop__main" id="excelFileName">{{ __('messages.no_file_chosen') }}</span>
                                <span class="im-drop__sub" id="dropHint">Drop a file here, or <u>{{ __('messages.choose_file') }}</u>. Accepts .xlsx, .xls and .csv</span>
                            </label>

                            <button type="button" class="btn btn-link btn-sm text-decoration-none px-0 mt-2" id="clearFile" hidden>
                                <i class="bi bi-x-circle me-1"></i>Remove file
                            </button>
                        </div>
                    </section>

                    {{-- Step 3 --}}
                    <section class="im-card im-step">
                        <span class="im-num">3</span>
                        <div class="im-step__body">
                            <h2 class="im-title">Import</h2>
                            <p class="im-muted mb-3">Products that already exist are updated, new ones are created.</p>

                            <div class="im-progress" id="progress" hidden>
                                <span></span>
                            </div>

                            <button type="submit" class="btn btn-primary fw-semibold px-4" id="saveBtn" disabled>
                                <i class="bi bi-cloud-arrow-up me-2"></i>{{ __('messages.import_data') }}
                            </button>
                        </div>
                    </section>
                </form>
            </div>

            {{-- TIPS --}}
            <div class="col-xl-4">
                <section class="im-card im-side">
                    <h2 class="im-title"><i class="bi bi-lightbulb me-2"></i>Before you import</h2>
                    <ul class="im-tips">
                        <li>Use the template. Column names must stay exactly as they are.</li>
                        <li>Save as .xlsx or .csv. Only the first sheet is read.</li>
                        <li>Do not leave blank rows in the middle of the data.</li>
                        <li>Keep codes as text so leading zeros are not lost.</li>
                        <li>A row with a problem is skipped and listed, the other rows still import.</li>
                    </ul>
                    <a href="{{ route('products.index') }}" class="btn btn-light w-100 mt-2">
                        <i class="bi bi-arrow-left me-1"></i>{{ __('messages.back') }}
                    </a>
                </section>
            </div>
        </div>

        <style>
            .im {
                --accent: var(--bs-primary, #4f46e5);
                --accent-soft: color-mix(in srgb, var(--accent) 14%, transparent);
                --muted: color-mix(in srgb, currentColor 62%, transparent);
                --line: color-mix(in srgb, currentColor 14%, transparent);
                --paper: color-mix(in srgb, currentColor 4%, transparent);
                --wash: color-mix(in srgb, currentColor 8%, transparent);
            }

            .im-muted {
                color: var(--muted);
            }

            .im-chip {
                display: inline-flex;
                align-items: center;
                gap: .5rem;
                padding: .4rem .8rem;
                border: 1px solid var(--line);
                border-radius: 999px;
                font-size: .85rem;
                color: var(--muted);
            }

            .im-chip strong {
                color: inherit;
            }

            .im-card {
                background: var(--paper);
                border: 1px solid var(--line);
                border-radius: 14px;
                padding: 1.25rem;
                margin-bottom: 1.25rem;
            }

            .im-title {
                font-size: 1rem;
                font-weight: 700;
                margin: 0 0 .5rem;
            }

            .im-side {
                position: sticky;
                top: 1rem;
            }

            /* Steps */
            .im-step {
                display: flex;
                gap: 1rem;
                align-items: flex-start;
            }

            .im-step__body {
                flex: 1 1 auto;
                min-width: 0;
            }

            .im-num {
                display: grid;
                place-items: center;
                flex: none;
                width: 34px;
                height: 34px;
                border-radius: 50%;
                background: var(--accent-soft);
                color: var(--accent);
                font-weight: 800;
            }

            .im-step.is-done .im-num {
                background: var(--accent);
                color: #fff;
            }

            /* Drop zone */
            .im-drop {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: .25rem;
                padding: 1.75rem 1rem;
                border: 2px dashed var(--line);
                border-radius: 12px;
                text-align: center;
                cursor: pointer;
                margin: 0;
                transition: border-color .15s, background .15s;
            }

            .im-drop:hover,
            .im-drop.is-over {
                border-color: var(--accent);
                background: var(--accent-soft);
            }

            .im-drop i {
                font-size: 2.2rem;
                color: var(--muted);
            }

            .im-drop.has-file {
                border-style: solid;
                border-color: var(--accent);
                background: var(--accent-soft);
            }

            .im-drop.has-file i {
                color: var(--accent);
            }

            .im-drop__main {
                font-weight: 700;
                overflow-wrap: anywhere;
            }

            .im-drop__sub {
                font-size: .85rem;
                color: var(--muted);
            }

            /* Progress */
            .im-progress {
                height: 6px;
                margin-bottom: 1rem;
                border-radius: 999px;
                background: var(--wash);
                overflow: hidden;
            }

            .im-progress[hidden] {
                display: none;
            }

            .im-progress span {
                display: block;
                width: 35%;
                height: 100%;
                border-radius: 999px;
                background: var(--accent);
                animation: im-slide 1.1s ease-in-out infinite;
            }

            @keyframes im-slide {
                0% {
                    transform: translateX(-100%);
                }

                100% {
                    transform: translateX(300%);
                }
            }

            @media (prefers-reduced-motion: reduce) {
                .im-progress span {
                    animation: none;
                    width: 100%;
                }
            }

            /* Tips */
            .im-tips {
                margin: 0 0 1rem;
                padding-left: 1.1rem;
                color: var(--muted);
            }

            .im-tips li {
                margin-bottom: .5rem;
            }

            /* Result */
            .im-result {
                margin-bottom: 1.25rem;
                padding: 1.25rem;
                border-radius: 14px;
                border: 1px solid var(--line);
            }

            .im-result--ok {
                background: rgba(25, 135, 84, .12);
                border-color: rgba(25, 135, 84, .4);
            }

            .im-result--bad {
                background: rgba(220, 53, 69, .1);
                border-color: rgba(220, 53, 69, .4);
            }

            .im-result h2 {
                font-size: 1.05rem;
                font-weight: 700;
                margin: 0 0 .75rem;
            }

            .im-stats {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: .75rem;
            }

            .im-stats div {
                padding: .75rem;
                border-radius: 10px;
                background: var(--paper);
                text-align: center;
            }

            .im-stats strong {
                display: block;
                font-size: 1.7rem;
                line-height: 1.1;
                font-variant-numeric: tabular-nums;
            }

            .im-stats span {
                font-size: .8rem;
                color: var(--muted);
            }

            .im-errors {
                max-height: 240px;
                overflow-y: auto;
                margin: .75rem 0 0;
                padding-left: 1.2rem;
                font-size: .9rem;
            }
        </style>
    </div>
@endsection

@push('scripts')
    <script>
        (function () {
            const form = document.getElementById('importForm');
            const input = document.getElementById('excelInput');
            const saveBtn = document.getElementById('saveBtn');
            const alertBox = document.getElementById('importAlert');
            const progress = document.getElementById('progress');
            const drop = document.getElementById('dropZone');
            const nameEl = document.getElementById('excelFileName');
            const hintEl = document.getElementById('dropHint');
            const iconEl = document.getElementById('dropIcon');
            const clearBtn = document.getElementById('clearFile');

            const noFile = @json(__('messages.no_file_chosen'));
            const defaultHint = hintEl.innerHTML;
            const allowed = ['csv', 'xlsx', 'xls'];
            const btnHtml = saveBtn.innerHTML;

            const esc = v => String(v ?? '').replace(/[&<>"']/g, c => ({
                '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
            }[c]));

            const size = bytes => bytes < 1024 * 1024
                ? Math.max(1, Math.round(bytes / 1024)) + ' KB'
                : (bytes / 1024 / 1024).toFixed(1) + ' MB';

            function showAlert(html) { alertBox.innerHTML = html; }

            function problem(text) {
                showAlert(`
                    <div class="im-result im-result--bad" role="alert">
                        <h2><i class="bi bi-exclamation-triangle me-2"></i>{{ __('messages.error') }}</h2>
                        <p class="mb-0">${esc(text)}</p>
                    </div>`);
            }

            /* ===== FILE CHOICE ===== */
            function setFile(file) {
                const ext = file ? file.name.split('.').pop().toLowerCase() : '';

                if (file && !allowed.includes(ext)) {
                    input.value = '';
                    problem('Please choose an .xlsx, .xls or .csv file.');
                    return render(null);
                }
                showAlert('');
                render(file);
            }

            function render(file) {
                const has = !!file;
                drop.classList.toggle('has-file', has);
                iconEl.className = has ? 'bi bi-file-earmark-check' : 'bi bi-file-earmark-spreadsheet';
                nameEl.textContent = has ? file.name : noFile;
                hintEl.innerHTML = has ? size(file.size) : defaultHint;
                clearBtn.hidden = !has;
                saveBtn.disabled = !has;
                document.querySelectorAll('.im-step')[1].classList.toggle('is-done', has);
            }

            input.addEventListener('change', () => setFile(input.files[0] || null));

            clearBtn.addEventListener('click', () => { input.value = ''; render(null); });

            ['dragenter', 'dragover'].forEach(ev => drop.addEventListener(ev, e => {
                e.preventDefault();
                drop.classList.add('is-over');
            }));
            ['dragleave', 'drop'].forEach(ev => drop.addEventListener(ev, e => {
                e.preventDefault();
                drop.classList.remove('is-over');
            }));
            drop.addEventListener('drop', e => {
                const file = e.dataTransfer.files[0];
                if (!file) return;
                const transfer = new DataTransfer();
                transfer.items.add(file);
                input.files = transfer.files;
                setFile(file);
            });

            /* ===== RESULT ===== */
            function renderResult(data) {
                const msgs = data.messages || {};
                let errors = Array.isArray(msgs.error_messages) ? msgs.error_messages : [];

                // Laravel validation errors (for example "the file field is required")
                if (!errors.length && data.errors) errors = Object.values(data.errors).flat();
                if (!errors.length && !data.success && data.message) errors = [data.message];

                if (data.success) {
                    const skipped = msgs.skipped_count || 0;
                    showAlert(`
                        <div class="im-result im-result--ok" role="status">
                            <h2><i class="bi bi-check-circle-fill me-2"></i>{{ __('messages.success') }}</h2>
                            <div class="im-stats">
                                <div><strong>${esc(msgs.created ?? 0)}</strong><span>Created</span></div>
                                <div><strong>${esc(msgs.updated ?? 0)}</strong><span>Updated</span></div>
                                <div><strong>${esc(skipped)}</strong><span>Skipped</span></div>
                            </div>
                            ${errors.length ? `<ul class="im-errors">${errors.map(e => `<li>${esc(e)}</li>`).join('')}</ul>` : ''}
                            <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary mt-3">
                                <i class="bi bi-box-seam me-1"></i>{{ __('messages.products_list') }}
                    </a>
                </div>`);

                    form.reset();
                    render(null);
                    return;
                }

                showAlert(`
                    <div class="im-result im-result--bad" role="alert">
                        <h2><i class="bi bi-exclamation-triangle me-2"></i>{{ __('messages.error') }}</h2>
                        ${errors.length
                    ? `<ul class="im-errors mt-0">${errors.map(e => `<li>${esc(e)}</li>`).join('')}</ul>`
                    : '<p class="mb-0">Import failed. Please check your file and try again.</p>'}
                    </div>`);
            }

            /* ===== SUBMIT ===== */
            form.addEventListener('submit', function (e) {
                e.preventDefault();   // always sent in the background, never a full page submit

                if (!input.files.length) return problem('Please choose a file first.');

                saveBtn.disabled = true;
                saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Importing...';
                progress.hidden = false;
                showAlert('');

                fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    }
                })
                    .then(response => {
                        if (response.status === 419) throw new Error('Your session expired. Refresh the page and try again.');
                        if (response.status === 413) throw new Error('The file is too large for the server.');
                        return response.json().then(data => ({ ok: response.ok, data }))
                            .catch(() => { throw new Error('The server did not answer correctly. Please try again.'); });
                    })
                    .then(({ data }) => renderResult(data))
                    .catch(error => problem(error.message || 'Something went wrong. Please try again.'))
                    .finally(() => {
                        progress.hidden = true;
                        saveBtn.innerHTML = btnHtml;
                        saveBtn.disabled = !input.files.length;
                    });
            });
        })();
    </script>
@endpush
