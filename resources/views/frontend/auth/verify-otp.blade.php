<form method="POST" action="{{ route('password.verify') }}">
    @csrf
    <input type="hidden" name="email" value="{{ session('email') }}">
    <input type="text" name="otp" placeholder="Enter OTP">
    <button type="submit">Verify OTP</button>
</form>

@error('otp') <div>{{ $message }}</div> @enderror
