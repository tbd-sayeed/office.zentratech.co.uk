<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - ZentraTech</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/febicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-vh-100 d-flex align-items-center justify-content-center p-4" style="background: linear-gradient(135deg, #064054 0%, #2e80c3 50%, #f9a31a 100%) !important;">
    <div class="card shadow-lg border-0" style="width: 100%; max-width: 400px;">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <img src="{{ asset('assets/img/zentratech_logo.svg') }}" alt="ZentraTech" class="img-fluid" style="max-height: 2.5rem;">
            </div>

            @if ($errors->any())
                <div class="alert alert-danger py-2 mb-3">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="input-group mb-3">
                    <span class="input-group-text bg-light border-end-0"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="text-secondary" viewBox="0 0 16 16"><path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/></svg></span>
                    <input type="email" name="email" id="email" class="form-control border-start-0" placeholder="Email" value="{{ old('email') }}" required autocomplete="email">
                </div>
                <div class="input-group mb-4">
                    <span class="input-group-text bg-light border-end-0"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="text-secondary" viewBox="0 0 16 16"><path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/></svg></span>
                    <input type="password" name="password" id="password" class="form-control border-start-0" placeholder="Password" required autocomplete="current-password">
                </div>
                <button type="submit" class="btn w-100 text-white text-uppercase fw-semibold py-2" style="background: #ec1260;">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
