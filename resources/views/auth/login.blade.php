@extends('welcome')

@section('content')

<div class="text-center mb-8">
    <img src="{{ asset('images/AARON.png') }}" class="mx-auto mb-4 mix-blend-">
</div>

        @error('email')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror

<form method="POST" action="{{ route('login.attempt') }}" class="space-y-5">
    @csrf

    <!-- Username -->
    <div>
        <input type="email" name="email" placeholder="Email"
            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:outline-none">
    </div>

    <!-- Password -->
    <div>
        <input type="password" name="password" placeholder="Password"
            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:outline-none">
    </div>

    <div class="flex justify-between items-center text-sm">
        <label class="flex items-center gap-2">
            <input type="checkbox" class="rounded border-gray-300">
            Remember Me
        </label>

        <a href="{{ route('password.forgot.show') }}" class="text-primary hover:underline">Forgot password</a>
    </div>

    <!-- Captcha Placeholder -->
    <div class="flex items-center justify-center rounded-lg text-cente">
        <div class="g-recaptcha" 
            data-sitekey="{{ config('services.recaptcha.site_key') }}">
        </div>

        @error('g-recaptcha-response')
            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit"
        class="w-full bg-primary hover:bg-darkred text-white py-3 rounded-full font-semibold transition">
        Login
    </button>

    <p class="text-center text-sm mt-4">
        Don’t have an account?
        <a href="{{ route('register') }}" class="text-primary font-medium">Sign Up</a>
    </p>
</form>

@endsection
