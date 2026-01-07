@extends('layouts.master')

@section('title', __('messages.my_account'))

@section('content')
    <div class="container-fluid">
        <div class="row align-items-center mb-4">
            <div class="col-md-6">
                <div class="pagetitle">
                    {{-- <h1 class="h3 fw-bold mb-2">{{ $pageTitle }}</h1> --}}
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

        <form method="POST" action="{{ route('profile.update', $user->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <!-- Sidebar / Avatar -->
                <div class="col-lg-3 mb-4">
                    <div class="text-center">
                        <div class="avatar-wrapper" tabindex="0" role="button">
                            <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('storage/profiles/noimage.png') }}"
                                alt="Avatar" class="user-avatar mb-3" id="avatar-preview"
                                style="height: 300px; width: 250px; object-fit: cover;">
                            <div class="avatar-overlay"></div>
                            <input type="file" name="avatar" id="image" accept="image/*" hidden>
                        </div>
                        @error('avatar')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                        <h5 class="mb-1">{{ $user->name }}</h5>
                        <p class="text-muted mb-3">{{ $user->email }}</p>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-lg-9">
                    <!-- Personal Info -->
                    <div class="mb-4">
                        <h4>Personal Information</h4>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>First Name</label>
                                <input type="text" name="first_name" class="form-control"
                                    value="{{ old('first_name', $user->first_name) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Last Name</label>
                                <input type="text" name="last_name" class="form-control"
                                    value="{{ old('last_name', $user->last_name) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Phone</label>
                                <input type="text" name="phone" class="form-control"
                                    value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Email</label>
                                <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                            </div>
                        </div>
                    </div>

                    <!-- Change Password -->
                    <div class="mb-4">
                        <h4>Change Password</h4>
                        <div class="mb-3">
                            <label>Current Password</label>
                            <div class="input-group">
                                <input type="password" name="old_password" class="form-control"
                                    placeholder="Current Password">
                                <button type="button" class="btn btn-outline-secondary toggle-password"
                                    aria-label="Show password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('old_password')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>New Password</label>
                            <div class="input-group">
                                <input type="password" name="new_password" class="form-control" placeholder="New Password">
                                <button type="button" class="btn btn-outline-secondary toggle-password"
                                    aria-label="Show password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('new_password')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Confirm New Password</label>
                            <div class="input-group">
                                <input type="password" name="new_password_confirmation" class="form-control"
                                    placeholder="Confirm Password">
                                <button type="button" class="btn btn-outline-secondary toggle-password"
                                    aria-label="Show password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>


                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        const avatarWrapper = document.querySelector('.avatar-wrapper');
        const avatarPreview = document.getElementById('avatar-preview');
        const fileInput = document.getElementById('image');
        const defaultAvatar =
            "{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('storage/profiles/noimage.png') }}";
        const maxFileSize = 2 * 1024 * 1024; // 2MB

        avatarWrapper.addEventListener('click', () => fileInput.click());
        avatarWrapper.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' || e.key === ' ') fileInput.click();
        });

        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (!file) {
                avatarPreview.src = defaultAvatar;
                return;
            }

            if (!file.type.startsWith('image/')) {
                alert('Please select an image file.');
                this.value = '';
                return;
            }
            if (file.size > maxFileSize) {
                alert('Maximum image size is 2MB.');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = e => avatarPreview.src = e.target.result;
            reader.readAsDataURL(file);
        });

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
    </script>
@endpush
