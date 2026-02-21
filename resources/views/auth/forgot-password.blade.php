@extends('welcome')

@section('content')

<div class="text-center mb-8">
    <img src="{{ asset('images/aaron-auth.png') }}" class="mx-auto h-36 mb-4">
</div>

<div class="text-center mb-6">
    <div class="mx-auto w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
        <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
        </svg>
    </div>
    <h2 class="text-xl font-semibold text-gray-800">Forgot Password?</h2>
    <p class="text-gray-500 text-sm mt-2">
        Enter your email address and we'll send you a one-time code to reset your password.
    </p>
</div>

@if (session('error'))
    <div class="bg-red-50 border border-red-200 text-red-600 text-sm rounded-lg px-4 py-3 mb-4">
        {{ session('error') }}
    </div>
@endif

@error('email')
    <p class="text-red-500 text-sm mb-3">{{ $message }}</p>
@enderror

<form method="POST" action="{{ route('password.forgot.send') }}" class="space-y-5">
    @csrf

    <div>
        <input
            type="email"
            name="email"
            value="{{ old('email') }}"
            placeholder="Enter your email address"
            required
            autofocus
            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:outline-none @error('email') border-red-400 @enderror">
    </div>

    <button type="submit"
        class="w-full bg-primary hover:bg-darkred text-white py-3 rounded-full font-semibold transition">
        Send OTP Code
    </button>

    <p class="text-center text-sm mt-4">
        Remember your password?
        <a href="{{ route('login') }}" class="text-primary font-medium hover:underline">Back to Login</a>
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