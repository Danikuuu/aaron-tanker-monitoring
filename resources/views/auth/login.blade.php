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
    <div>
        <input type="password" name="password"
               placeholder="Password"
               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:outline-none text-sm"
               autocomplete="current-password">
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
            class="w-full bg-primary hover:bg-darkred text-white py-3 rounded-full font-semibold transition text-sm sm:text-base">
        Login
    </button>

    {{-- Sign up link --}}
    <p class="text-center text-sm text-gray-500">
        Don't have an account?
        <a href="{{ route('register') }}" class="text-primary font-semibold hover:underline">Sign Up</a>
    </p>
</form>
<script>
    const fuelForm = document.getElementById('fuelForm');
    const submitBtn = fuelForm.querySelector('button[type="submit"]');

    fuelForm.addEventListener('submit', function() {
        // Disable the button immediately to prevent multiple clicks
        submitBtn.disabled = true;
        submitBtn.innerText = 'Submitting...'; // Optional: give user feedback
    });
</script>

@endsection