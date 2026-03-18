<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - ZentraTech</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/febicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .login-page { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1.5rem; }
        .login-card { width: 100%; max-width: 400px; background: white; border-radius: 1rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); overflow: hidden; }
        .login-input { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1rem; border-radius: 0.75rem; background: #f3f4f6; border: 1px solid #e5e7eb; margin-bottom: 1rem; }
        .login-input input { flex: 1; background: transparent; border: none; outline: none; font-size: 0.875rem; }
        .login-input input::placeholder { color: #9ca3af; }
        .login-btn { width: 100%; padding: 0.875rem 1rem; border-radius: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; border: none; cursor: pointer; background: #ec1260; color: white; transition: background 0.2s; }
        .login-btn:hover { background: #c90f51; }
    </style>
</head>
<body class="login-page" style="background: linear-gradient(135deg, #064054 0%, #2e80c3 50%, #f9a31a 100%) !important;">
    <div class="login-card">
        {{-- Logo at top --}}
        <div style="display: flex; justify-content: center; padding: 2.5rem 2rem 1.5rem;">
            <img src="{{ asset('assets/img/zentratech_logo.svg') }}" alt="ZentraTech" style="height: 2.5rem; width: auto; object-fit: contain;">
        </div>

        @if ($errors->any())
            <div style="margin: 0 2rem 1rem; padding: 0.75rem; background: #fef2f2; border: 1px solid #fecaca; border-radius: 0.5rem;">
                <p style="font-size: 0.813rem; color: #b91c1c;">{{ $errors->first() }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" style="padding: 0 2rem 2rem;">
            @csrf
            {{-- Email field with icon --}}
            <div class="login-input">
                <svg style="width: 1.25rem; height: 1.25rem; color: #6b7280; flex-shrink: 0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <input id="email" name="email" type="email" autocomplete="email" required placeholder="Email" value="{{ old('email') }}">
            </div>

            {{-- Password field with icon --}}
            <div class="login-input">
                <svg style="width: 1.25rem; height: 1.25rem; color: #6b7280; flex-shrink: 0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <input id="password" name="password" type="password" autocomplete="current-password" required placeholder="••••••••">
            </div>

            {{-- Login button --}}
            <button type="submit" class="login-btn">Login</button>
        </form>
    </div>
</body>
</html>
