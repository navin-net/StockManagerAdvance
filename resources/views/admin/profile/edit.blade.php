@extends('layouts.master')

@section('title', __('messages.my_account'))

@push('style')
    <style>

    </style>
@endpush

@section('content')
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

    <div class="row mb-4">
        <div class="col-12">
            <h2 class="mb-0">My Account</h2>
            <p class="text-muted">Manage your personal information and security</p>
        </div>
    </div>

    <form method="POST" action="{{ route('profile.update', $user->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <!-- Sidebar / Avatar -->
            <div class="col-lg-3 mb-4">
                <div class="dashboard-sidebar p-4 text-center">
                    <div class="avatar-wrapper" tabindex="0" role="button" aria-label="Click to upload new avatar">
                        <img src="{{ optional($user->profile)->image ? asset('storage/' . optional($user->profile)->image) : asset('storage/profiles/noimage.png') }}"
                            alt="Avatar" class="user-avatar mb-3" id="avatar-preview" aria-describedby="avatar-error"
                            style="height: 300px; width: 250px;">
                        <div class="avatar-overlay"></div>
                        <input type="file" name="image" id="image" class="file-input-hidden" accept="image/*"
                            hidden>
                    </div>
                    <div id="avatar-error" class="avatar-error"></div>
                    @error('image')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                    <h5 class="mb-1">{{ $user->name }}</h5>
                    <p class="text-muted mb-3">{{ $user->email }}</p>

                    <a href="{{ route('logout') }}" class="btn btn-outline-danger w-100"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign Out</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9 dashboard-content">

                <!-- Personal Info -->
                <div class="dashboard-card">
                    <div class="dashboard-card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Personal Information</h4>
                        <span class="badge bg-primary rounded-pill px-3">Primary</span>
                    </div>
                    <div class="dashboard-card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Birth Date</label>
                            <input type="date" name="dob" class="form-control"
                                value="{{ old('dob', optional($user->profile)->dob) }}">
                            @error('dob')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Change Password -->
                <div class="dashboard-card mt-4">
                    <div class="dashboard-card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Change Password</h4>
                        <span class="badge bg-warning text-dark rounded-pill px-3">Security</span>
                    </div>
                    <div class="dashboard-card-body">

                        <!-- Current Password -->
                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <div class="input-group">
                                <input type="password" name="old_password" class="form-control"
                                    placeholder="Current Password" autocomplete="current-password">
                                <button type="button" class="btn btn-outline-secondary toggle-password"
                                    aria-label="Show password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('old_password')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <div class="input-group">
                                <input type="password" name="new_password" class="form-control" placeholder="New Password"
                                    autocomplete="new-password">
                                <button type="button" class="btn btn-outline-secondary toggle-password"
                                    aria-label="Show password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('new_password')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm New Password -->
                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <div class="input-group">
                                <input type="password" name="new_password_confirmation" class="form-control"
                                    placeholder="Confirm New Password" autocomplete="new-password">
                                <button type="button" class="btn btn-outline-secondary toggle-password"
                                    aria-label="Show password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary save-btn mt-3 px-4">
                        <i class="bi bi-check2 me-2"></i>Save Changes
                    </button>
                </div>

            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.closest('.input-group').querySelector('input');
                const icon = this.querySelector('i');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            });
        });

        const avatarWrapper = document.querySelector('.avatar-wrapper');
        const avatarPreview = document.getElementById('avatar-preview');
        const fileInput = document.getElementById('image');
        const errorDiv = document.getElementById('avatar-error');
        const defaultAvatar =
            "{{ optional($user->profile)->image ? asset('storage/' . optional($user->profile)->image) : asset('storage/profiles/noimage.png') }}";
        const maxFileSize = 2 * 1024 * 1024; 

        avatarWrapper.addEventListener('click', () => fileInput.click());
        avatarWrapper.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                fileInput.click();
            }
        });

        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            errorDiv.textContent = '';
            errorDiv.classList.remove('active');

            if (!file) {
                avatarPreview.src = defaultAvatar;
                avatarPreview.style.opacity = '1';
                return;
            }

            if (!file.type.startsWith('image/')) {
                errorDiv.textContent = 'Please select an image file (e.g., JPG, PNG).';
                errorDiv.classList.add('active');
                avatarPreview.src = defaultAvatar;
                this.value = '';
                return;
            }

            if (file.size > maxFileSize) {
                errorDiv.textContent = 'Image file is too large. Maximum size is 2MB.';
                errorDiv.classList.add('active');
                avatarPreview.src = defaultAvatar;
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = e => {
                avatarPreview.src = e.target.result;
                avatarPreview.style.opacity = '1';
            };
            reader.readAsDataURL(file);
        });
    </script>
@endpush
