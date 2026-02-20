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

<form method="POST" action="{{ route('register.attempt') }}" class="space-y-4">
    @csrf

    <div class="grid grid-cols-2 gap-4">
        <input type="text" name="first_name" placeholder="First Name"
            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:outline-none">

        <input type="text" name="last_name" placeholder="Last Name"
            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:outline-none">
    </div>

    <input type="email" name="email" placeholder="Email"
        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:outline-none">

    <input type="password" name="password" placeholder="Password"
        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:outline-none">

    <input type="password" name="password_confirmation" placeholder="Confirm Password"
        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:outline-none">

    <div class="flex items-center gap-2 text-sm">
        <input type="checkbox" required>
        <span>
            I Agree with
            <a href="#" class="text-primary">Privacy</a>
            and
            <a href="#" class="text-primary">Policy</a>
        </span>
    </div>

    <div class="flex items-center justify-center rounded-lg text-cente">
        <div class="g-recaptcha" 
            data-sitekey="{{ config('services.recaptcha.site_key') }}">
        </div>

        @error('g-recaptcha-response')
            <p class="text-primary text-sm mt-2">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit"
        class="w-full bg-primary hover:bg-darkred text-white py-3 rounded-full font-semibold transition">
        Sign Up
    </button>

    <p class="text-center text-sm mt-4">
        Already have an account?
        <a href="{{ route('login') }}" class="text-primary font-medium">Sign in</a>
    </p>
</form>

@endsection
