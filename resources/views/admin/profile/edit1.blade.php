@extends('admin.layouts.master')

@section('title', __('messages.my_account'))

@php
    $isAdmin   = auth()->user()->group_id == 1;
    $avatarUrl = $user->avatar ? asset('storage/' . $user->avatar) : asset('storage/profiles/noimage.png');
    $dash      = fn ($v) => filled($v) ? $v : '—';
    $initials  = strtoupper(mb_substr($user->first_name ?? 'U', 0, 1) . mb_substr($user->last_name ?? '', 0, 1));

    $validTabs = ['information', 'address', 'change_password', 'avatar'];
    $tab       = request('tab', 'information');
    if (!in_array($tab, $validTabs, true) || ($tab === 'address' && $isAdmin)) {
        $tab = 'information';
    }

    $addressAction = Route::has('profile.updateAddress') ? route('profile.updateAddress') : null;

    $sections = array_filter([
        ['id' => 'information',     'icon' => 'bi-person',       'label' => __('messages.personal_information'), 'hint' => __('messages.edit')],
        !$isAdmin ? ['id' => 'address', 'icon' => 'bi-geo-alt', 'label' => __('messages.address'), 'hint' => __('messages.street')] : null,
        ['id' => 'change_password', 'icon' => 'bi-shield-lock', 'label' => __('messages.change_password'), 'hint' => __('messages.update_password')],
        ['id' => 'avatar',          'icon' => 'bi-image',        'label' => __('messages.avatar'),              'hint' => __('messages.format_picture')],
    ]);
@endphp

@push('styles')
    <style>
        .pf { display: flex; flex-direction: column; gap: 1.25rem; }

        /* ---------- header ---------- */
        .pf-head { display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 1rem; }
        .pf-head h1 { font-size: 1.5rem; font-weight: 800; letter-spacing: -.02em; margin: 0 0 .35rem; color: var(--text-color); }
        .pf-head .breadcrumb { font-size: .8rem; margin: 0; }
        .pf-head .breadcrumb-item a { color: var(--text-muted); text-decoration: none; }
        .pf-head .breadcrumb-item a:hover { color: var(--brand-text); }
        .pf-head .breadcrumb-item.active { color: var(--text-muted); }
        .pf-ip {
            display: inline-flex; align-items: center; gap: .4rem; height: 36px; padding: 0 .75rem; border-radius: 10px;
            background: var(--card-bg); border: 1px solid var(--border-color); font-size: .8rem; color: var(--text-muted);
        }
        .pf-ip b { color: var(--brand-text); font-weight: 700; }

        /* ---------- layout ---------- */
        .pf-grid { display: grid; grid-template-columns: 320px minmax(0, 1fr); gap: 1.25rem; align-items: start; }
        @media (max-width: 991.98px) { .pf-grid { grid-template-columns: 1fr; } }

        .pf-card {
            background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 18px;
            box-shadow: 0 1px 2px rgba(16,24,40,.05); overflow: hidden;
        }
        .pf-card:hover { transform: none !important; box-shadow: 0 1px 2px rgba(16,24,40,.05) !important; }

        /* ---------- hero ---------- */
        .pf-hero { text-align: center; }
        .pf-cover {
            height: 96px; position: relative;
            background:
                radial-gradient(circle at 20% 30%, rgba(255,255,255,.22) 0, transparent 40%),
                radial-gradient(circle at 80% 70%, rgba(255,255,255,.14) 0, transparent 45%),
                linear-gradient(135deg, var(--brand) 0%, #8C8FFF 100%);
        }
        .pf-avatar-wrap { position: relative; width: 116px; margin: -58px auto 0; }
        .pf-avatar {
            width: 116px; height: 116px; border-radius: 50%; object-fit: cover; cursor: zoom-in;
            border: 4px solid var(--card-bg); box-shadow: 0 8px 22px rgba(16,24,40,.18); display: block;
        }
        .pf-avatar-fallback {
            width: 116px; height: 116px; border-radius: 50%; display: none; place-items: center;
            border: 4px solid var(--card-bg); background: var(--brand); color: #fff; font-weight: 800; font-size: 2rem;
        }
        .pf-cam {
            position: absolute; right: 4px; bottom: 6px; width: 32px; height: 32px; border-radius: 50%;
            display: grid; place-items: center; border: 2px solid var(--card-bg); background: var(--brand); color: #fff; cursor: pointer;
        }
        .pf-cam:hover { background: var(--brand-hover); color: #fff; }
        .pf-name { font-size: 1.15rem; font-weight: 800; letter-spacing: -.01em; margin: .85rem 1rem .2rem; color: var(--text-color); overflow-wrap: anywhere; }
        .pf-sub { font-size: .82rem; color: var(--text-muted); margin: 0 1rem; overflow-wrap: anywhere; }
        .pf-chips { display: flex; flex-wrap: wrap; justify-content: center; gap: .4rem; margin: .8rem 1rem 0; }
        .pf-chip {
            padding: .2rem .6rem; border-radius: 999px; font-size: .72rem; font-weight: 700;
            background: var(--brand-soft); color: var(--brand-text);
        }
        .pf-chip.neutral { background: var(--hover-bg); color: var(--text-muted); }

        .pf-stats { display: grid; grid-template-columns: 1fr 1fr; margin: 1.25rem 0 0; border-top: 1px solid var(--border-color); }
        .pf-stat { padding: .85rem .75rem; min-width: 0; }
        .pf-stat + .pf-stat { border-left: 1px solid var(--border-color); }
        .pf-stat span { display: block; font-size: .68rem; text-transform: uppercase; letter-spacing: .06em; font-weight: 700; color: var(--text-muted); }
        .pf-stat b { display: block; margin-top: .2rem; font-size: .85rem; color: var(--text-color); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

        /* ---------- side menu ---------- */
        .pf-menu { padding: .75rem; border-top: 1px solid var(--border-color); display: flex; flex-direction: column; gap: 4px; }
        .pf-menu .nav-link {
            display: flex; align-items: center; gap: .75rem; padding: .6rem .7rem; border-radius: 12px; border: 1px solid transparent;
            color: var(--text-color) !important; background: transparent !important; text-align: left; margin: 0; transform: none !important;
        }
        .pf-menu .nav-link:hover { background: var(--hover-bg) !important; }
        .pf-menu .nav-link.active { background: var(--brand-soft) !important; border-color: rgba(var(--brand-rgb), .25); }
        .pf-menu .nav-link .ic {
            width: 34px; height: 34px; flex: none; display: grid; place-items: center; border-radius: 10px;
            background: var(--card-bg); border: 1px solid var(--border-color); color: var(--text-muted); font-size: 1rem;
        }
        .pf-menu .nav-link.active .ic { background: var(--brand); border-color: var(--brand); color: #fff; }
        .pf-menu .nav-link b { display: block; font-size: .86rem; font-weight: 700; line-height: 1.2; }
        .pf-menu .nav-link small { display: block; font-size: .72rem; color: var(--text-muted); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .pf-menu .nav-link .txt { min-width: 0; flex: 1; }

        .pf-logout {
            margin: .25rem .75rem .75rem; height: 40px; border-radius: 12px; font-weight: 600; font-size: .85rem;
            border: 1px solid rgba(229,72,77,.35); color: #E5484D; background: transparent; width: calc(100% - 1.5rem);
        }
        .pf-logout:hover { background: rgba(229,72,77,.1); color: #E5484D; }

        /* ---------- content ---------- */
        .pf-panel { padding: 1.5rem 1.75rem; }
        .pf-panel-head { display: flex; align-items: center; gap: .9rem; margin-bottom: 1.4rem; padding-bottom: 1.1rem; border-bottom: 1px solid var(--border-color); }
        .pf-panel-head .ic {
            width: 44px; height: 44px; flex: none; display: grid; place-items: center; border-radius: 13px;
            background: var(--brand-soft); color: var(--brand-text); font-size: 1.2rem;
        }
        .pf-panel-head h2 { font-size: 1.1rem; font-weight: 800; margin: 0; color: var(--text-color); letter-spacing: -.01em; }
        .pf-panel-head p { margin: .15rem 0 0; font-size: .82rem; color: var(--text-muted); }
        .pf-panel-head .btn { margin-left: auto; flex: none; }

        /* ---------- read-only fields ---------- */
        .pf-fields { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 1rem; }
        .pf-field { padding: .9rem 1rem; border-radius: 14px; background: var(--hover-bg); min-width: 0; }
        .pf-field.full { grid-column: 1 / -1; }
        .pf-label { display: block; font-size: .68rem; text-transform: uppercase; letter-spacing: .06em; font-weight: 700; color: var(--text-muted); }
        .pf-value { display: block; margin-top: .3rem; font-weight: 600; color: var(--text-color); overflow-wrap: anywhere; }
        @media (max-width: 576px) { .pf-fields { grid-template-columns: 1fr; } .pf-panel { padding: 1.25rem; } }

        /* ---------- forms ---------- */
        .pf-form-label { font-size: .8rem; font-weight: 600; color: var(--text-color); margin-bottom: .35rem; }
        .pf-form .input-group-text { background: var(--hover-bg); border-color: var(--border-color); color: var(--text-muted); }
        .pf-form .form-control, .pf-form .form-select { border-color: var(--border-color); background-color: var(--bg-color); color: var(--text-color); }
        .pf-form .input-group > .form-control { border-radius: 0 10px 10px 0; }
        .pf-form .input-group > .input-group-text { border-radius: 10px 0 0 10px; }
        .pf-error { display: block; margin-top: .3rem; font-size: .78rem; color: #E5484D; }
        .pf-actions { display: flex; justify-content: flex-end; gap: .5rem; margin-top: 1.4rem; padding-top: 1.25rem; border-top: 1px solid var(--border-color); }

        /* password strength */
        .pf-strength { display: flex; gap: 4px; margin-top: .5rem; }
        .pf-strength i { flex: 1; height: 4px; border-radius: 999px; background: var(--border-color); transition: background .2s; }
        .pf-strength-text { font-size: .74rem; color: var(--text-muted); margin-top: .3rem; min-height: 1em; }

        /* ---------- avatar uploader ---------- */
        .pf-drop {
            display: flex; align-items: center; gap: 1rem; padding: 1.1rem; border-radius: 16px; cursor: pointer;
            border: 1.5px dashed var(--border-color); background: var(--hover-bg); transition: border-color .15s, background .15s;
        }
        .pf-drop:hover { border-color: var(--brand); background: var(--brand-soft); }
        .pf-drop-ic {
            width: 44px; height: 44px; flex: none; border-radius: 13px; display: grid; place-items: center;
            background: var(--card-bg); color: var(--brand-text); font-size: 1.2rem; border: 1px solid var(--border-color);
        }
        .pf-drop-title { font-weight: 700; font-size: .9rem; color: var(--text-color); display: block; }
        .pf-drop-sub { font-size: .78rem; color: var(--text-muted); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; display: block; }
        .pf-preview-wrap { position: relative; width: 136px; flex: none; }
        .pf-preview { width: 136px; height: 136px; border-radius: 24px; object-fit: cover; border: 1px solid var(--border-color); box-shadow: 0 8px 22px rgba(16,24,40,.12); }
        .pf-preview-tag {
            position: absolute; left: 50%; bottom: -10px; transform: translateX(-50%); white-space: nowrap;
            font-size: .68rem; font-weight: 700; padding: .2rem .55rem; border-radius: 999px; background: var(--card-bg); border: 1px solid var(--border-color); color: var(--text-muted);
        }

        /* ---------- modals ---------- */
        .pf-modal { border-radius: 18px; border: 1px solid var(--border-color); background: var(--card-bg); }
        .pf-modal .modal-header { border-bottom: 1px solid var(--border-color); }
        .pf-modal .form-control, .pf-modal .form-select { background-color: var(--bg-color); border-color: var(--border-color); color: var(--text-color); }
        .pf-viewer { background: #000; border-radius: 18px; overflow: hidden; }

        @media (max-width: 576px) { .pf-actions .btn { flex: 1; } .pf-preview-wrap { margin: 0 auto; } }
        @media print { .pf-ip, .pf-logout, .pf-menu, .pf-cam, .pf-actions { display: none !important; } }
    </style>
@endpush

@section('content')
    <div class="pf">

        {{-- Header --}}
        <div class="pf-head">
            <div>
                <h1>{{ $pageTitle }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        @foreach ($breadcrumbs as $breadcrumb)
                            @if (!$breadcrumb['active'])
                                <li class="breadcrumb-item"><a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['label'] }}</a></li>
                            @else
                                <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb['label'] }}</li>
                            @endif
                        @endforeach
                    </ol>
                </nav>
            </div>
            <span class="pf-ip">{{ __('messages.ip_address') }}: <b>{{ auth()->user()->ip_address }}</b></span>
        </div>

        @if (session('success'))
            <div class="alert alert-success mb-0">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger mb-0">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <div class="pf-grid">

            {{-- LEFT: hero + section menu --}}
            <aside class="pf-card">
                <div class="pf-hero">
                    <div class="pf-cover"></div>

                    <div class="pf-avatar-wrap">
                        <img src="{{ $avatarUrl }}" class="pf-avatar" alt="{{ $user->first_name }}"
                             data-bs-toggle="modal" data-bs-target="#avatarModal">
                        <button type="button" class="pf-cam" aria-label="{{ __('messages.choose_new_avatar') }}"
                                data-bs-toggle="tab" data-bs-target="#avatar" data-tab-trigger="avatar">
                            <i class="bi bi-camera"></i>
                        </button>
                    </div>

                    <div class="pf-name">{{ $user->first_name }} {{ $user->last_name }}</div>
                    <div class="pf-sub">{{ $dash($user->email) }}</div>

                    <div class="pf-chips">
                        <span class="pf-chip">{{ $isAdmin ? 'Admin' : 'Staff' }}</span>
                        @filled($user->gender)<span class="pf-chip neutral">{{ $user->gender }}</span>@endfilled
                    </div>

                    <div class="pf-stats">
                        <div class="pf-stat">
                            <span>{{ __('messages.username') }}</span>
                            <b>{{ $dash($user->name) }}</b>
                        </div>
                        <div class="pf-stat">
                            <span>{{ __('messages.phone') }}</span>
                            <b>{{ $dash($user->phone) }}</b>
                        </div>
                    </div>
                </div>

                <div class="pf-menu nav flex-column" role="tablist">
                    @foreach ($sections as $s)
                        <button type="button" role="tab"
                                class="nav-link {{ $tab === $s['id'] ? 'active' : '' }}"
                                data-bs-toggle="tab" data-bs-target="#{{ $s['id'] }}">
                            <span class="ic"><i class="bi {{ $s['icon'] }}"></i></span>
                            <span class="txt">
                            <b>{{ $s['label'] }}</b>
                            <small>{{ $s['hint'] }}</small>
                        </span>
                            <i class="bi bi-chevron-right small text-muted"></i>
                        </button>
                    @endforeach
                </div>

                <button type="button" class="pf-logout"
                        onclick="event.preventDefault(); document.getElementById('logout-form')?.submit();">
                    <i class="bi bi-box-arrow-right me-1"></i>{{ __('messages.sigout') }}
                </button>
            </aside>

            {{-- RIGHT: active section --}}
            <section class="pf-card">
                <div class="tab-content">

                    {{-- Personal information --}}
                    <div class="tab-pane fade pf-panel {{ $tab === 'information' ? 'show active' : '' }}" id="information" role="tabpanel">
                        <div class="pf-panel-head">
                            <span class="ic"><i class="bi bi-person"></i></span>
                            <div>
                                <h2>{{ __('messages.personal_information') }}</h2>
                                <p>{{ __('messages.edit') }}</p>
                            </div>
                            <button type="button" class="btn btn-outline-custom btn-sm px-3"
                                    data-bs-toggle="modal" data-bs-target="#personalModal">
                                <i class="bi bi-pencil me-1"></i>{{ __('messages.edit') }}
                            </button>
                        </div>

                        <div class="pf-fields">
                            <div class="pf-field"><span class="pf-label">{{ __('messages.first_name') }}</span><span class="pf-value">{{ $dash($user->first_name) }}</span></div>
                            <div class="pf-field"><span class="pf-label">{{ __('messages.last_name') }}</span><span class="pf-value">{{ $dash($user->last_name) }}</span></div>
                            <div class="pf-field"><span class="pf-label">{{ __('messages.dob') }}</span><span class="pf-value">{{ $dash($user->dob) }}</span></div>
                            <div class="pf-field"><span class="pf-label">{{ __('messages.gender') }}</span><span class="pf-value">{{ $dash($user->gender) }}</span></div>
                            <div class="pf-field"><span class="pf-label">{{ __('messages.email') }}</span><span class="pf-value">{{ $dash($user->email) }}</span></div>
                            <div class="pf-field"><span class="pf-label">{{ __('messages.phone') }}</span><span class="pf-value">{{ $dash($user->phone) }}</span></div>
                            <div class="pf-field full"><span class="pf-label">{{ __('messages.username') }}</span><span class="pf-value">{{ $dash($user->name) }}</span></div>
                        </div>
                    </div>

                    {{-- Address (staff only) --}}
                    @unless ($isAdmin)
                        <div class="tab-pane fade pf-panel {{ $tab === 'address' ? 'show active' : '' }}" id="address" role="tabpanel">
                            <div class="pf-panel-head">
                                <span class="ic"><i class="bi bi-geo-alt"></i></span>
                                <div>
                                    <h2>{{ __('messages.address') }}</h2>
                                    <p>{{ __('messages.edit') }}</p>
                                </div>
                                <button type="button" class="btn btn-outline-custom btn-sm px-3"
                                        data-bs-toggle="modal" data-bs-target="#addressModal">
                                    <i class="bi bi-pencil me-1"></i>{{ __('messages.edit') }}
                                </button>
                            </div>

                            <div class="pf-fields">
                                <div class="pf-field"><span class="pf-label">{{ __('messages.country') }}</span><span class="pf-value">{{ $dash($company?->country) }}</span></div>
                                <div class="pf-field"><span class="pf-label">{{ __('messages.street') }}</span><span class="pf-value">{{ $dash($company?->street) }}</span></div>
                                <div class="pf-field"><span class="pf-label">{{ __('messages.city_state') }}</span><span class="pf-value">{{ $dash($company?->city) }}</span></div>
                                <div class="pf-field"><span class="pf-label">{{ __('messages.number_of_houses') }}</span><span class="pf-value">{{ $dash($company?->number_of_houses) }}</span></div>
                            </div>
                        </div>
                    @endunless

                    {{-- Change password --}}
                    <div class="tab-pane fade pf-panel {{ $tab === 'change_password' ? 'show active' : '' }}" id="change_password" role="tabpanel">
                        <div class="pf-panel-head">
                            <span class="ic"><i class="bi bi-shield-lock"></i></span>
                            <div>
                                <h2>{{ __('messages.change_password') }}</h2>
                                <p>{{ __('messages.update_password') }}</p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('profile.change-password') }}" class="pf-form" style="max-width: 520px;">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="pf-form-label" for="current_password">{{ __('messages.current_password') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input type="password" id="current_password" name="current_password" class="form-control"
                                           placeholder="{{ __('messages.enter_current_password') }}" autocomplete="current-password" required>
                                </div>
                                @error('current_password') <small class="pf-error">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="pf-form-label" for="new_password">{{ __('messages.new_password') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                                    <input type="password" id="new_password" name="password" class="form-control"
                                           placeholder="{{ __('messages.enter_new_password') }}" autocomplete="new-password" required>
                                </div>
                                <div class="pf-strength" id="pwStrength" aria-hidden="true"><i></i><i></i><i></i><i></i></div>
                                <div class="pf-strength-text" id="pwStrengthText"></div>
                                @error('password') <small class="pf-error">{{ $message }}</small> @enderror
                            </div>

                            <div class="mb-0">
                                <label class="pf-form-label" for="confirm_password">{{ __('messages.password_confirmation') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-shield-check"></i></span>
                                    <input type="password" id="confirm_password" name="password_confirmation" class="form-control"
                                           placeholder="{{ __('messages.password_confirmation') }}" autocomplete="new-password" required>
                                </div>
                            </div>

                            <div class="pf-actions">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-check2 me-1"></i>{{ __('messages.updated_password') }}
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Avatar --}}
                    <div class="tab-pane fade pf-panel {{ $tab === 'avatar' ? 'show active' : '' }}" id="avatar" role="tabpanel">
                        <div class="pf-panel-head">
                            <span class="ic"><i class="bi bi-image"></i></span>
                            <div>
                                <h2>{{ __('messages.avatar') }}</h2>
                                <p>{{ __('messages.format_picture') }}</p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('profile.upload-avatar') }}" enctype="multipart/form-data" class="pf-form">
                            @csrf
                            @method('PUT')

                            <div class="d-flex flex-column flex-md-row align-items-md-center gap-4 pb-3">
                                <div class="pf-preview-wrap">
                                    <img id="avatarPreview" src="{{ $avatarUrl }}" class="pf-preview" alt="Avatar">
                                    <span class="pf-preview-tag" id="avatarTag">{{ __('messages.avatar') }}</span>
                                </div>

                                <div class="flex-grow-1 w-100" style="max-width: 440px;">
                                    <input type="file" name="avatar" id="avatarInput" class="d-none"
                                           accept="image/png,image/jpeg" onchange="onAvatarChange(event)">

                                    <label for="avatarInput" class="pf-drop w-100 mb-2">
                                        <span class="pf-drop-ic"><i class="bi bi-cloud-arrow-up"></i></span>
                                        <span class="flex-grow-1" style="min-width:0">
                                        <span class="pf-drop-title">{{ __('messages.choose_new_avatar') }}</span>
                                        <span class="pf-drop-sub" id="avatarFileName">{{ __('messages.no_file_chosen') }}</span>
                                    </span>
                                        <span class="btn btn-outline-custom btn-sm">{{ __('messages.choose_file') }}</span>
                                    </label>

                                    @error('avatar') <small class="pf-error">{{ $message }}</small> @enderror

                                    <div class="d-flex gap-2 mt-3">
                                        <button type="submit" class="btn btn-primary px-4">
                                            <i class="bi bi-upload me-1"></i>{{ __('messages.upload') }}
                                        </button>
                                        <button type="button" class="btn btn-outline-custom" onclick="resetAvatar()">
                                            {{ __('messages.cancel') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </section>
        </div>
    </div>

    {{-- Personal information --}}
    <div class="modal fade" id="personalModal" tabindex="-1" aria-labelledby="personalModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content pf-modal">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="personalModalLabel">{{ __('messages.personal_information') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="inforForm" action="{{ route('profile.updateInformation') }}" method="POST" class="pf-form">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="pf-form-label" for="first_name">{{ __('messages.first_name') }}</label>
                                <input type="text" id="first_name" name="first_name" class="form-control" value="{{ $user->first_name }}">
                            </div>
                            <div class="col-md-6">
                                <label class="pf-form-label" for="last_name">{{ __('messages.last_name') }}</label>
                                <input type="text" id="last_name" name="last_name" class="form-control" value="{{ $user->last_name }}">
                            </div>
                            <div class="col-md-6">
                                <label class="pf-form-label" for="dob">{{ __('messages.dob') }}</label>
                                <input type="date" id="dob" name="dob" class="form-control" value="{{ $user->dob }}">
                            </div>
                            <div class="col-md-6">
                                <label class="pf-form-label" for="gender">{{ __('messages.gender') }}</label>
                                <select id="gender" name="gender" class="form-select">
                                    <option value="">{{ __('messages.select_gender') }}</option>
                                    @foreach (['Male', 'Female', 'Other'] as $gender)
                                        <option value="{{ $gender }}" @selected(old('gender', $user->gender) === $gender)>{{ $gender }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="pf-form-label" for="email">{{ __('messages.email') }}</label>
                                <input type="email" id="email" name="email" class="form-control" value="{{ $user->email }}">
                            </div>
                            <div class="col-md-6">
                                <label class="pf-form-label" for="phone">{{ __('messages.phone') }}</label>
                                <input type="tel" id="phone" name="phone" class="form-control" value="{{ $user->phone }}">
                            </div>
                        </div>
                        <div class="pf-actions">
                            <button type="button" class="btn btn-outline-custom px-3" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                            <button type="submit" class="btn btn-primary px-4">{{ __('messages.submit') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Address --}}
    <div class="modal fade" id="addressModal" tabindex="-1" aria-labelledby="addressModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content pf-modal">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="addressModalLabel">{{ __('messages.address') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ $addressAction ?? '#' }}" class="pf-form"
                      @unless ($addressAction) onsubmit="return false" @endunless>
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="pf-form-label" for="number_of_houses">{{ __('messages.number_of_houses') }}</label>
                                <input type="text" id="number_of_houses" name="number_of_houses" class="form-control" value="{{ $company?->number_of_houses }}">
                            </div>
                            <div class="col-md-6">
                                <label class="pf-form-label" for="street">{{ __('messages.street') }}</label>
                                <input type="text" id="street" name="street" class="form-control" value="{{ $company?->street }}">
                            </div>
                            <div class="col-md-6">
                                <label class="pf-form-label" for="city">{{ __('messages.city') }}</label>
                                <input type="text" id="city" name="city" class="form-control" value="{{ $company?->city }}">
                            </div>
                            <div class="col-md-6">
                                <label class="pf-form-label" for="country">{{ __('messages.country') }}</label>
                                <input type="text" id="country" name="country" class="form-control" value="{{ $company?->country }}">
                            </div>
                        </div>
                        <div class="pf-actions">
                            <button type="button" class="btn btn-outline-custom px-3" data-bs-dismiss="modal">{{ __('messages.cancel') }}</button>
                            <button type="submit" class="btn btn-primary px-4" @unless ($addressAction) disabled @endunless>
                                {{ __('messages.submit') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Avatar viewer --}}
    <div class="modal fade" id="avatarModal" tabindex="-1" aria-labelledby="avatarViewLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content pf-viewer border-0">
                <div class="modal-header border-0 py-2">
                    <h5 class="modal-title visually-hidden" id="avatarViewLabel">{{ __('messages.avatar') }}</h5>
                    <button type="button" class="btn-close btn-close-white ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <img src="{{ $avatarUrl }}" class="img-fluid" alt="{{ $user->first_name }}">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const defaultAvatar = @json($avatarUrl);
        const noFileText    = @json(__('messages.no_file_chosen'));

        function onAvatarChange(event) {
            const file = event.target.files?.[0];
            if (!file) return resetAvatar();

            document.getElementById('avatarPreview').src = URL.createObjectURL(file);
            document.getElementById('avatarFileName').textContent = file.name;
            document.getElementById('avatarTag').textContent = 'New';
        }

        function resetAvatar() {
            const input = document.getElementById('avatarInput');
            if (input) input.value = '';
            document.getElementById('avatarPreview').src = defaultAvatar;
            document.getElementById('avatarFileName').textContent = noFileText;
            document.getElementById('avatarTag').textContent = @json(__('messages.avatar'));
        }

        // Password strength meter
        (function () {
            const input = document.getElementById('new_password');
            const bars  = document.querySelectorAll('#pwStrength i');
            const text  = document.getElementById('pwStrengthText');
            if (!input) return;

            const levels = [
                { label: 'Too short', color: '#E5484D' },
                { label: 'Weak',      color: '#F97316' },
                { label: 'Good',      color: '#F59E0B' },
                { label: 'Strong',    color: '#22A55B' },
            ];

            function score(v) {
                let s = 0;
                if (v.length >= 8) s++;
                if (/[a-z]/.test(v) && /[A-Z]/.test(v)) s++;
                if (/\d/.test(v)) s++;
                if (/[^A-Za-z0-9]/.test(v)) s++;
                return s;
            }

            input.addEventListener('input', () => {
                const v = input.value;
                if (!v) {
                    bars.forEach(b => b.style.background = '');
                    text.textContent = '';
                    return;
                }
                const s = Math.min(score(v), 4);
                const lvl = v.length < 8 ? 0 : Math.max(1, Math.min(s, 3));
                const level = levels[lvl];
                bars.forEach((b, i) => b.style.background = i <= lvl ? level.color : '');
                text.textContent = level.label;
                text.style.color = level.color;
            });
        })();
    </script>
@endpush
