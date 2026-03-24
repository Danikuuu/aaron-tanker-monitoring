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
                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
        </svg>
    </div>
    <h2 class="text-xl font-semibold text-gray-800">Reset Password</h2>
    <p class="text-gray-500 text-sm mt-2">
        Enter and confirm your new password below.
    </p>
</div>

@if ($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-600 text-sm rounded-lg px-4 py-3 mb-4">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<form method="POST" action="{{ route('password.reset.update') }}" class="space-y-5">
    @csrf

{{-- New Password --}}
<div class="relative">
    <input
        type="password"
        name="password"
        id="password"
        placeholder="New Password"
        required
        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:outline-none pr-10 @error('password') border-red-400 @enderror">
    <button type="button" onclick="togglePassword('password', 'eye-icon', 'eye-off-icon')"
        class="absolute right-3 cursor-pointer top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
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
    @error('password')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

{{-- Confirm New Password --}}
<div class="relative">
    <input
        type="password"
        name="password_confirmation"
        id="password_confirmation"
        placeholder="Confirm New Password"
        required
        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:outline-none pr-10">
    <button type="button" onclick="togglePassword('password_confirmation', 'eye-icon-2', 'eye-off-icon-2')"
        class="absolute right-3 cursor-pointer top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
        <svg id="eye-icon-2" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7
                     -1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
        </svg>
        <svg id="eye-off-icon-2" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7
                     a9.956 9.956 0 012.293-3.95M6.696 6.696A9.956 9.956 0 0112 5
                     c4.477 0 8.268 2.943 9.542 7a9.956 9.956 0 01-1.558 2.934
                     M3 3l18 18" />
        </svg>
    </button>
</div>

<script>
    function togglePassword(inputId, eyeOnId, eyeOffId) {
        const input  = document.getElementById(inputId);
        const eyeOn  = document.getElementById(eyeOnId);
        const eyeOff = document.getElementById(eyeOffId);
        const isHidden = input.type === 'password';

        input.type = isHidden ? 'text' : 'password';
        eyeOn.classList.toggle('hidden', isHidden);
        eyeOff.classList.toggle('hidden', !isHidden);
    }
</script>

    <button type="submit"
        class="w-full bg-primary cursor-pointer hover:bg-darkred text-white py-3 rounded-full font-semibold transition">
        Save New Password
    </button>

    <p class="text-center text-sm mt-4">
        Remember your password?
        <a href="{{ route('login') }}" class="text-primary cursor-pointer font-medium hover:underline">Back to Login</a>
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