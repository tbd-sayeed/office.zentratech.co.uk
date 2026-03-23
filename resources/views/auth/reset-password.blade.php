<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password - ZentraTech</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/febicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-vh-100 d-flex align-items-center justify-content-center p-4 bg-light">
    <div class="card shadow-sm border-0" style="max-width: 400px; width: 100%;">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <img src="{{ asset('assets/img/zentratech_logo.svg') }}" alt="ZentraTech" class="img-fluid" style="max-height: 2rem;">
            </div>
            <h5 class="fw-semibold mb-2">Set new password</h5>
            <p class="text-muted small mb-4">Enter your new password below.</p>
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">
                <div class="mb-3">
                    <label for="password" class="form-label">New password</label>
                    <input type="password" class="form-control" id="password" name="password" required placeholder="••••••••">
                </div>
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Confirm password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required placeholder="••••••••">
                </div>
                <button type="submit" class="btn btn-primary w-100">Reset Password</button>
            </form>
            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="small text-primary">Back to sign in</a>
            </div>
        </div>
    </div>
</body>
</html>
