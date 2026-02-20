@extends('welcome')

@section('content')

<div class="text-center mb-8">
    <img src="{{ asset('images/AARON.png') }}" class="mx-auto mb-4">
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

    <div>
        <input
            type="password"
            name="password"
            placeholder="New Password"
            required
            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:outline-none @error('password') border-red-400 @enderror">
        @error('password')
            <p class="text-primary text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <input
            type="password"
            name="password_confirmation"
            placeholder="Confirm New Password"
            required
            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-primary focus:outline-none">
    </div>

    <button type="submit"
        class="w-full bg-primary hover:bg-darkred text-white py-3 rounded-full font-semibold transition">
        Save New Password
    </button>

    <p class="text-center text-sm mt-4">
        Remember your password?
        <a href="{{ route('login') }}" class="text-primary font-medium hover:underline">Back to Login</a>
    </p>
</form>

@endsection