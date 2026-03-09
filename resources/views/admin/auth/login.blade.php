<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pirata+One&display=swap" rel="stylesheet" />
    <style>
        body, html { margin: 0; padding: 0; height: 100%; font-family: 'Pirata One', cursive; }
        #video-bg { position: fixed; top:0; left:0; width:100vw; height:100vh; object-fit:cover; z-index:-1; filter:brightness(0.5); }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 bg-dark text-white">
    @auth
        <script>window.location.href = "{{ route('/') }}";</script>
    @else

    <video id="video-bg" autoplay muted loop playsinline>
        <source src="https://static.moewalls.com/videos/preview/2025/goth-anime-girl-preview.webm" type="video/mp4" />
        Your browser does not support the video tag.
    </video>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-5">
                <div class="card bg-dark text-white bg-opacity-75 shadow-lg">
                    <div class="card-body p-4">
                        <h2 class="text-center text-warning mb-4">Welcome Back, Pirate!</h2>

                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
                        @endif

                        <form method="POST" action="/login">
                            @csrf

                            <div class="mb-3">
                                <label for="login" class="form-label">Email</label>
                                <input type="text" id="login" name="login" value="{{ old('login') }}" required autofocus class="form-control bg-dark text-white border-1">
                                @error('login')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" id="password" name="password" required class="form-control bg-dark text-white border-1">
                                    <button type="button" class="btn btn-outline-secondary" onclick="togglePasswordVisibility('password')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" id="eye-icon">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                </div>
                                @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember">
                                    <label class="form-check-label">{{ __('messages.remember_me') }}</label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a class="text-warning" href="{{ route('password.request') }}">Forgot password?</a>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-warning w-100 text-dark fw-bold">Log In</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePasswordVisibility(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const eyeIcon = document.getElementById('eye-icon');
            if (!passwordField) return;
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            // swap simple icon paths to indicate state
            if (eyeIcon) {
                if (type === 'text') {
                    eyeIcon.innerHTML = '\n                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.977 9.977 0 011.563-3.029m4.146-2.146A3 3 0 0112 9a3 3 0 013.873 4.558M9.027 7.227A9.977 9.977 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-2.146 3.026"></path>\n                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
                } else {
                    eyeIcon.innerHTML = '\n                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>\n                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
                }
            }
        }
    </script>
</body>
</html>
