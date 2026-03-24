@extends('welcome')

@section('content')

<style>
    /* Scale down reCAPTCHA on small screens */
    @media (max-width: 360px) {
        .g-recaptcha {
            transform: scale(0.85);
            transform-origin: center top;
        }
    }
</style>

{{-- Logo --}}
<div class="text-center mb-8">
    <img src="{{ asset('images/aaron-auth.png') }}"
         class="mx-auto w-auto"
         style="height: clamp(80px, 20vw, 144px);">
</div>

{{-- Success message (e.g. after registration) --}}
@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 flex items-start gap-2">
        <svg class="w-5 h-5 mt-0.5 shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm">{{ session('success') }}</p>
    </div>
@endif

{{-- Email error --}}
@error('email')
    <p class="text-primary text-sm mb-3">{{ $message }}</p>
@enderror


<form method="POST" action="{{ route('login.attempt') }}" class="space-y-4">
    @csrf

    {{-- Email --}}
    <div>
        <input type="email" name="email"
               placeholder="Email"
               value="{{ old('email') }}"
               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:outline-none text-sm"
               autocomplete="email">
    </div>

    {{-- Password --}}
    <div class="relative">
        <input type="password" name="password"
            id="password"
            placeholder="Password"
            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:outline-none text-sm pr-10"
            autocomplete="current-password">
        <button type="button"
                onclick="togglePassword()"
                class="absolute right-3 top-1/2 cursor-pointer -translate-y-1/2 text-gray-400 hover:text-gray-600">
            <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7
                        -1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            <svg id="eye-off-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7
                        a9.956 9.956 0 012.293-3.95M6.696 6.696A9.956 9.956 0 0112 5
                        c4.477 0 8.268 2.943 9.542 7a9.956 9.956 0 01-1.558 2.934
                        M3 3l18 18" />
            </svg>
        </button>
    </div>

    {{-- Remember me + Forgot password --}}
    <div class="flex justify-between items-center text-sm flex-wrap gap-y-2">
        <label class="flex items-center gap-2 cursor-pointer select-none">
            <input type="checkbox" name="remember"
                   class="rounded border-gray-300 accent-primary">
            <span class="text-gray-600">Remember Me</span>
        </label>
        <a href="{{ route('password.forgot.show') }}"
           class="text-primary hover:underline">
            Forgot password?
        </a>
    </div>

    {{-- reCAPTCHA --}}
    <div class="flex flex-col items-center gap-1">
        <div class="g-recaptcha"
             data-sitekey="{{ config('services.recaptcha.site_key') }}">
        </div>
        @error('g-recaptcha-response')
            <p class="text-primary text-sm">{{ $message }}</p>
        @enderror
    </div>

    {{-- Submit --}}
    <button type="submit"
            class="w-full bg-primary hover:bg-darkred text-white py-3 rounded-full font-semibold transition text-sm sm:text-base cursor-pointer">
        Login
    </button>

    {{-- Sign up link --}}
    <p class="text-center text-sm text-gray-500">
        Don't have an account?
        <a href="{{ route('register') }}" class="text-primary font-semibold hover:underline">Sign Up</a>
    </p>
</form>

<script>
    function togglePassword() {
        const input   = document.getElementById('password');
        const eyeOn   = document.getElementById('eye-icon');
        const eyeOff  = document.getElementById('eye-off-icon');
        const isHidden = input.type === 'password';

        input.type    = isHidden ? 'text' : 'password';
        eyeOn.classList.toggle('hidden', isHidden);
        eyeOff.classList.toggle('hidden', !isHidden);
    }
</script>
@endsection