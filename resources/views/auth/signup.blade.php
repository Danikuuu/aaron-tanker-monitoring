@extends('welcome')

@section('content')

<div class="text-center mb-8">
    <img src="{{ asset('images/aaron-auth.png') }}" class="mx-auto h-36 mb-4">
</div>

@if ($errors->any())
    <div class="text-primary">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Hidden draft form OUTSIDE the main form to avoid nesting issues --}}
<form id="saveDraftForm" method="POST" action="{{ route('signup.save_draft') }}" style="display:none;">
    @csrf
    <input type="hidden" name="first_name"            id="draft_first_name">
    <input type="hidden" name="last_name"             id="draft_last_name">
    <input type="hidden" name="email"                 id="draft_email">
    <input type="hidden" name="password"              id="draft_password">
    <input type="hidden" name="password_confirmation" id="draft_password_confirmation">
</form>

<form method="POST" action="{{ route('register.attempt') }}" class="space-y-4" id="signupForm">
    @csrf

    <div class="grid grid-cols-2 gap-4">
        <input type="text" name="first_name" placeholder="First Name"
            value="{{ old('first_name', session('signup_draft.first_name')) }}"
            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:outline-none">

        <input type="text" name="last_name" placeholder="Last Name"
            value="{{ old('last_name', session('signup_draft.last_name')) }}"
            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:outline-none">
    </div>

    <input type="email" name="email" placeholder="Email"
        value="{{ old('email', session('signup_draft.email')) }}"
        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:outline-none">

    {{-- Password --}}
    <div class="relative">
        <input type="password" name="password" id="password"
            placeholder="Password"
            value="{{ session('signup_draft.password') }}"
            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:outline-none pr-10">
        <button type="button" onclick="togglePassword('password', 'eye-icon', 'eye-off-icon')"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
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

    {{-- Confirm Password --}}
    <div class="relative">
        <input type="password" name="password_confirmation" id="password_confirmation"
            placeholder="Confirm Password"
            value="{{ session('signup_draft.password_confirmation') }}"
            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:outline-none pr-10">
        <button type="button" onclick="togglePassword('password_confirmation', 'eye-icon-2', 'eye-off-icon-2')"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
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

    <div class="flex items-center gap-2 text-sm">
        <input type="checkbox" name="terms" id="terms"
            class="rounded border-gray-300 accent-primary"
            {{ session('signup_draft_accepted') === true ? 'checked' : '' }}
            required>
        <span>
            I Agree with
            <a href="#" id="privacyLink" class="text-primary">Privacy and Policy</a>
        </span>
    </div>

    <div class="flex items-center justify-center rounded-lg text-center">
        @if(session('signup_draft_accepted') === false)
            <div class="text-primary text-sm mb-2">
                You must agree to the Privacy Policy to create an account.
            </div>
        @endif
    </div>

    <div class="flex items-center justify-center rounded-lg text-center">
        <div class="g-recaptcha"
            data-sitekey="{{ config('services.recaptcha.site_key') }}">
        </div>

        @error('g-recaptcha-response')
            <p class="text-primary text-sm mt-2">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit"
        class="w-full bg-primary hover:bg-darkred text-white py-3 rounded-full font-semibold transition cursor-pointer">
        Sign Up
    </button>

    <p class="text-center text-sm mt-4">
        Already have an account?
        <a href="{{ route('login') }}" class="text-primary font-medium cursor-pointer">Sign in</a>
    </p>
</form>

<script>
    document.getElementById('privacyLink').addEventListener('click', function (e) {
        e.preventDefault();

        const signupForm = document.getElementById('signupForm');

        document.getElementById('draft_first_name').value            = signupForm.querySelector('[name="first_name"]').value;
        document.getElementById('draft_last_name').value             = signupForm.querySelector('[name="last_name"]').value;
        document.getElementById('draft_email').value                 = signupForm.querySelector('[name="email"]').value;
        document.getElementById('draft_password').value              = signupForm.querySelector('[name="password"]').value;
        document.getElementById('draft_password_confirmation').value = signupForm.querySelector('[name="password_confirmation"]').value;

        document.getElementById('saveDraftForm').submit();
    });

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

@endsection