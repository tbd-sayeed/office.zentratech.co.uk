<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password - ZentraTech</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/febicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-vh-100 d-flex align-items-center justify-content-center p-4 bg-light">
    <div class="card shadow-sm border-0" style="max-width: 400px; width: 100%;">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <img src="{{ asset('assets/img/zentratech_logo.svg') }}" alt="ZentraTech" class="img-fluid" style="max-height: 2rem;">
            </div>
            <h5 class="fw-semibold mb-2">Forgot password</h5>
            <p class="text-muted small mb-4">Enter your email and we'll send you a reset link.</p>
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="email" name="email" required placeholder="you@company.com">
                </div>
                <button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
            </form>
            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="small text-primary">Back to sign in</a>
            </div>
        </div>
    </div>
</body>
</html>
