@extends('layouts.master')

@section('title', __('messages.my_account'))

@section('content')
    <div class="container-fluid">
        <div class="row align-items-center mb-4">
            <div class="col-md-6">
                <div class="pagetitle">
                    <h1 class="h3 fw-bold mb-2">{{ $pageTitle }}</h1>
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
                    </nav>
                </div>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <small class="text-muted fw-semibold">
                    {{ __('messages.ip_address') }}:
                </small>
                <span class="fw-semibold text-primary">
                    {{ auth()->user()->ip_address }}
                </span>
            </div>
        </div>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row g-4">
            <!-- LEFT SIDE PROFILE -->
            <div class="col-lg-4">

                <!-- PROFILE CARD -->
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <!-- PROFILE AVATAR -->
                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('storage/profiles/noimage.png') }}"
                            class="rounded mb-3 cursor-pointer" width="120" height="200" alt="Profile Picture"
                            data-bs-toggle="modal" data-bs-target="#avatarModal">

                        <h5 class="fw-bold mb-0">{{ $user->first_name }} {{ $user->last_name }}</h5>
                        <small class="text-muted">{{ $user->gender }}</small><br>
                        <small class="text-muted">{{ $user->email }}</small>

                        <hr>

                        {{-- <div class="d-flex justify-content-between">
                        <span>Followers</span>
                        <span class="text-primary fw-bold">1,322</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Following</span>
                        <span class="text-primary fw-bold">543</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Friends</span>
                        <span class="text-primary fw-bold">13,287</span>
                    </div> --}}

                        <button class="btn btn-danger w-100 mt-4"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('messages.sigout') }}</button>
                    </div>
                </div>

                <!-- ABOUT ME -->
                <div class="card mt-4 shadow-sm">
                    <div class="card-header  text-white fw-bold">
                        About Me
                    </div>
                    <div class="card-body">
                        <p><i class="bi bi-mortarboard"></i> B.S. in Computer Science</p>
                        <p><i class="bi bi-geo-alt"></i> Phnom Penh, Cambodia</p>
                        <p><i class="bi bi-briefcase"></i> 5+ Years Experience</p>
                    </div>
                </div>
            </div>
            <!-- RIGHT SIDE CONTENT -->
            <div class="col-lg-8">

                <div class="card shadow-sm">
                    <div class="card-body">

                        <!-- TABS -->
                        <ul class="nav nav-tabs mb-4" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#information">
                                    {{ __('messages.personal_information') }}
                                </button>
                            </li>
                            @unless(auth()->user()->group_id == 1)
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#address">
                                    {{ __('messages.address') }}
                                </button>
                            </li>
                            @endunless
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#change_password">
                                    {{ __('messages.change_password') }}
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#avatar">
                                    {{ __('messages.avatar') }}
                                </button>
                            </li>
                        </ul>

                        <!-- TAB CONTENT -->
                        <div class="tab-content">

                            <!-- information -->
                            <div class="tab-pane fade show active" id="information">
                                <div class="card-body py-4">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h5 class="fw-bold mb-1">{{ __('messages.personal_information') }}</h5>
                                            <p class="text-muted mb-0 small">
                                            </p>
                                        </div>
                                        <div class="col-auto">
                                            <button href="#" class="btn btn-outline-primary btn-sm rounded-pill"
                                                data-bs-toggle="modal" data-bs-target="#personalModal">
                                                <i class="bi bi-pencil me-2"></i>{{ __('messages.edit') }}
                                            </button>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-label">{{ __('messages.first_name') }}</div>
                                                <div class="info-value">{{ $user->first_name }}</div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-label">{{ __('messages.last_name') }}</div>
                                                <div class="info-value">{{ $user->last_name }}</div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-label">{{ __('messages.dob') }}</div>
                                                <div class="info-value">{{ $user->dob }}</div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-label">{{ __('messages.gender') }}</div>
                                                <div class="info-value">{{ $user->gender }}</div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-label">{{ __('messages.email') }}</div>
                                                <div class="info-value">{{ $user->email }}</div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-label">{{ __('messages.phone') }}</div>
                                                <div class="info-value">{{ $user->phone }}</div>
                                            </div>
                                                <div class="col-md-12">
                                                <div class="info-label">{{ __('messages.username') }}</div>
                                                <div class="info-value">{{ $user->name }}</div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                                {{-- </div> --}}
                            </div>

                            <!-- address -->
                            <div class="tab-pane fade" id="address">
                                {{-- <div class="card border-0 shadow-sm mb-4"> --}}
                                <div class="card-body py-4">
                                    <div class="row align-items-center">
                                        <!-- Main Content -->
                                        <div class="col">
                                            <h5 class="fw-bold mb-1">{{ __('messages.address') }}</h5>
                                            <p class="text-muted mb-0 small">
                                            </p>
                                        </div>
                                        <!-- Timestamp -->
                                        <div class="col-auto">
                                            <button class="btn btn-outline-primary btn-sm rounded-pill"
                                                data-bs-toggle="modal" data-bs-target="#addressModal">
                                                <i class="bi bi-pencil me-2"></i>{{ __('messages.edit') }}
                                            </button>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-label">{{ __('messages.country') }}</div>
                                                <div class="info-value">{{ $company->country ?? 'Not Specified' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-label">{{ __('messages.street') }}</div>
                                                <div class="info-value">{{ $company->street ?? 'Not Specified' }}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-label">{{ __('messages.city_state') }}</div>
                                                <div class="info-value">{{ $company->city ?? 'Not Specified' }}</div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-label">{{ __('messages.number_of_houses') }}</div>
                                                <div class="info-value">
                                                    {{ $company->number_of_houses ?? 'Not Specified' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- </div> --}}
                            </div>

                            <!-- change_password -->
                            <div class="tab-pane fade " id="change_password">
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-body py-4">
                                        <div class="row align-items-center">

                                            {{-- HEADER --}}
                                            <div class="mb-4">
                                                <h5 class="fw-bold mb-1">{{ __('messages.change_password') }}</h5>
                                                <p class="text-muted small">
                                                    {{ __('messages.update_password') }}
                                                </p>
                                            </div>
                                            {{-- FORM --}}
                                            <form method="POST" action="{{ route('profile.change-password') }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">
                                                        {{ __('messages.current_password') }}
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">
                                                            <i class="bi bi-lock"></i>
                                                        </span>
                                                        <input type="password" name="current_password"
                                                            class="form-control"
                                                            placeholder="{{ __('messages.enter_current_password') }}"
                                                            required>
                                                    </div>
                                                    @error('current_password')
                                                        <small class="text-danger">
                                                            {{ $message }}
                                                        </small>
                                                    @enderror
                                                </div>

                                                {{-- NEW PASSWORD --}}
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">
                                                        {{ __('messages.new_password') }}
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">
                                                            <i class="bi bi-shield-lock"></i>
                                                        </span>
                                                        <input type="password" name="password" class="form-control"
                                                            placeholder="{{ __('messages.enter_new_password') }}"
                                                            required>
                                                    </div>
                                                    @error('password')
                                                        <small class="text-danger">
                                                            {{ $message }}
                                                        </small>
                                                    @enderror
                                                </div>

                                                {{-- CONFIRM PASSWORD --}}
                                                <div class="mb-4">
                                                    <label class="form-label fw-semibold">
                                                        {{ __('messages.password_confirmation') }}
                                                    </label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">
                                                            <i class="bi bi-shield-check"></i>
                                                        </span>
                                                        <input type="password" name="password_confirmation"
                                                            class="form-control"
                                                            placeholder="{{ __('messages.password_confirmation') }}"
                                                            required>
                                                    </div>
                                                </div>

                                                {{-- ACTIONS --}}
                                                <div class="d-flex justify-content-end gap-2">
                                                    <button type="submit" class="btn btn-primary px-4">
                                                        <i class="bi bi-save me-1"></i>
                                                        {{ __('messages.updated_password') }}
                                                    </button>
                                                </div>

                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="avatar">
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-body py-4">

                                        <!-- Header -->
                                        <div class="row align-items-center mb-4">
                                            <div class="col">
                                                <h5 class="fw-bold mb-1">{{ __('messages.avatar') }}</h5>
                                                <p class="text-muted mb-0 small">
                                                    {{ __('messages.format_picture') }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Avatar Upload -->
                                        <form method="POST" action="{{ route('profile.upload-avatar') }}"
                                            enctype="multipart/form-data">
                                            {{-- <from> --}}
                                            @csrf
                                            @method('PUT')

                                            <div class="row align-items-center">
                                                <!-- Avatar Preview -->
                                                <div class="col-md-3 text-center mb-3 mb-md-0">
                                                    <img id="avatarPreview"
                                                        src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('storage/profiles/noimage.png') }}"
                                                        class="rounded-circle border shadow-sm" width="120"
                                                        height="120" style="object-fit: cover;" alt="Avatar">
                                                </div>

                                                <!-- Upload Controls -->
                                                <div class="col-md-9">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">
                                                            {{ __('messages.choose_new_avatar') }}
                                                        </label>

                                                        <!-- HIDDEN FILE INPUT -->
                                                        <input type="file" name="avatar" id="avatarInput"
                                                            class="d-none" accept="image/png,image/jpeg"
                                                            onchange="onAvatarChange(event)">

                                                        <!-- BUTTON -->
                                                        <label for="avatarInput" class="btn btn-outline-primary w-100">
                                                            {{ __('messages.choose_file') }}
                                                        </label>

                                                        <!-- FILE NAME -->
                                                        <small id="avatarFileName" class="text-muted d-block mt-1">
                                                            {{ __('messages.no_file_chosen') }}
                                                        </small>
                                                    </div>

                                                    <!-- AVATAR PREVIEW -->
                                                    {{-- <img id="avatarPreview"
                                                        src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('noimage.png') }}"
                                                        class="rounded-circle mt-3" width="120" height="120"> --}}


                                                    <div class="d-flex gap-2">
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="bi bi-upload me-1"></i> {{ __('messages.upload') }}
                                                        </button>

                                                        <button type="reset" class="btn btn-light"
                                                            onclick="resetAvatar()">
                                                            {{ __('messages.cancel') }}
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>

                        </div>


                    </div>
                </div>
            </div>
        </div>



        <div class="modal fade" id="personalModal" tabindex="-1" aria-hidden="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header border-0 pb-0">
                        {{-- <h5>Profile</h5> --}}
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="inforForm" action="{{ route('profile.updateInformation') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body px-4 pb-4">
                            <div class="row g-3 mb-3">
                                <div class="col-md-6"> <label class="form-label">{{ __('messages.first_name') }}</label>
                                    <input type="text" name="first_name" class="form-control"
                                        value="{{ $user->first_name }}">
                                </div>
                                <div class="col-md-6"> <label class="form-label">{{ __('messages.last_name') }}</label>
                                    <input type="text" name="last_name" class="form-control"
                                        value="{{ $user->last_name }}">
                                </div>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('messages.dob') }}</label>
                                    <input type="date" name="dob" class="form-control"
                                        value="{{ $user->dob }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('messages.gender') }}</label>
                                    <select name="gender" class="form-select">
                                        <option value="">{{ __('messages.select_gender') }}</option>
                                        @foreach (['Male', 'Female', 'Other'] as $gender)
                                            <option value="{{ $gender }}"
                                                {{ old('gender', $user->gender) === $gender ? 'selected' : '' }}>
                                                {{ ucfirst($gender) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email address</label>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ $user->email }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone</label>
                                    <input type="tel" name="phone" class="form-control"
                                        value="{{ $user->phone }}">
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="d-flex gap-2 justify-content-end">
                                <button type="submit" class="btn btn-primary px-4 rounded-3">
                                    {{ __('messages.submit') }}
                                </button>

                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>



        <div class="modal fade" id="addressModal" tabindex="-1" aria-hidden="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header border-0 pb-0">
                        {{-- <h5>Profile</h5> --}}
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    {{-- <form id="inforForm" action="{{ route('profile.updateInformation') }}" method="POST">
                        @csrf
                        @method('PUT') --}}
                    <div class="modal-body px-4 pb-4">

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">{{ __('messages.number_of_houses') }}</label>
                                <input type="text" name="number_of_houses" class="form-control" value="{{ $company->number_of_houses }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('messages.street') }}</label>
                                <input type="text" name="street" class="form-control" value="{{ $company->street }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('messages.city') }}</label>
                                <input type="text" name="city" class="form-control" value="{{ $company->city }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('messages.country') }}</label>
                                <input type="text" name="country" class="form-control" value="{{ $company->country }}">
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex gap-2 justify-content-end">
                            <button type="submit" class="btn btn-primary px-4 rounded-3">
                                {{ __('messages.submit') }}
                            </button>

                        </div>

                    </div>
                    {{-- </form> --}}
                </div>
            </div>
        </div>



        <div class="modal fade" id="avatarModal" tabindex="-1" aria-hidden="false">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content bg-dark border-0">
                    <div class="modal-header border-0">
                        <button type="button" class="btn-close btn-close-white ms-auto"
                            data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body text-center p-0">
                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('storage/profiles/noimage.png') }}"
                            class="img-fluid" alt="Profile Picture">
                    </div>
                </div>
            </div>
        </div>

    @endsection
    @push('style')
        <style>
            .info-card {
                border-radius: 15px;
                border: 1px solid #dee2e6;
                padding: 30px;
            }

            .info-label {
                color: #6c757d;
                font-size: 0.85rem;
                margin-bottom: 5px;
            }

            .info-value {
                font-weight: 600;
                color: #212529;
                margin-bottom: 25px;
            }

            .profile-avatar {
                width: 120px;
                height: 200px;
                object-fit: cover;
                cursor: pointer;
                border-radius: 6px;
            }

            /* Viewer */
            .image-viewer {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.95);
                display: none;
                align-items: center;
                justify-content: center;
                z-index: 9999;
            }

            .image-viewer img {
                max-width: 90%;
                max-height: 90%;
                object-fit: contain;
            }

            .close-btn {
                position: absolute;
                top: 20px;
                right: 30px;
                bottom: 20px;
                font-size: 40px;
                color: white;
                cursor: pointer;
            }
        </style>
    @endpush
    @push('scripts')
        <script>
            const defaultAvatar =
                "{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('noimage.png') }}";

            function onAvatarChange(event) {
                const input = event.target;

                // Preview image
                if (input.files && input.files[0]) {
                    document.getElementById('avatarPreview').src =
                        URL.createObjectURL(input.files[0]);

                    document.getElementById('avatarFileName').textContent =
                        input.files[0].name;
                } else {
                    resetAvatar();
                }
            }

            function resetAvatar() {
                document.getElementById('avatarPreview').src = defaultAvatar;
                document.getElementById('avatarFileName').textContent =
                    "{{ __('messages.no_file_chosen') }}";
            }
        </script>
    @endpush
