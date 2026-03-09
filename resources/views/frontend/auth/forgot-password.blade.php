<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pirata+One&display=swap" rel="stylesheet" />
    <style>
        body, html { margin: 0; padding: 0; height: 100%; font-family: 'Pirata One', cursive; }
        #video-bg { position: fixed; top:0; left:0; width:100vw; height:100vh; object-fit:cover; z-index:-1; filter:brightness(0.5); }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center min-vh-100 bg-dark text-white">


    <video id="video-bg" autoplay muted loop playsinline>
        <source src="https://static.moewalls.com/videos/preview/2025/goth-anime-girl-preview.webm" type="video/mp4" />
        Your browser does not support the video tag.
    </video>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-5">
                <div class="card bg-dark text-white bg-opacity-75 shadow-lg">
                    <div class="card-body p-4">
                        <h2 class="text-center text-warning mb-4">Forgot Password, Pirate!</h2>
                        @error('email') <div class="alert alert-danger">{{ $message }}</div> @enderror

                        <form method="POST" action="{{ route('password.send') }}">
                            @csrf
                            <input type="email" name="email" class="form-control mb-2" placeholder="Enter your email">

                            <button type="submit" class="btn btn-warning w-100 text-dark fw-bold">
                                Send OTP
                            </button>
                        </form>
                    <div class="text-center mt-3">
                        <a href="{{ route('password.request') }}" class="text-decoration-none text-secondary">Back</a>
                    </div>

                </div>
            </div>
        </div>
    </div>




</body>

</html>

