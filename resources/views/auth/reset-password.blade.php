<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password - ZentraTech</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/febicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 flex items-center justify-center p-6">
    <div class="w-full max-w-md">
        <div class="flex justify-center mb-8">
            <img src="{{ asset('assets/img/zentratech_logo.svg') }}" alt="ZentraTech" class="h-12">
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">
            <h1 class="text-xl font-semibold text-slate-900">Set new password</h1>
            <p class="mt-2 text-sm text-slate-600">Enter your new password below.</p>
            <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">New password</label>
                    <input id="password" name="password" type="password" required
                        class="block w-full px-4 py-3 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#064054] focus:border-transparent"
                        placeholder="••••••••">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1.5">Confirm password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                        class="block w-full px-4 py-3 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#064054] focus:border-transparent"
                        placeholder="••••••••">
                </div>
                <button type="submit"
                    class="w-full flex justify-center py-3 px-4 rounded-lg font-medium text-white bg-[#064054] hover:bg-[#052d3d] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#064054]">
                    Reset Password
                </button>
            </form>
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-sm font-medium text-[#064054] hover:text-[#2e80c3]">Back to sign in</a>
            </div>
        </div>
    </div>
</body>
</html>

