@extends('welcome')

@section('content')

<div class="text-center mb-8">
    <img src="{{ asset('images/logo.png') }}" class="mx-auto h-16 mb-4">
</div>
@if ($errors->any())
    <div class="text-red-500">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="text-center mb-6">
    <div class="mx-auto w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mb-4">
        🔒
    </div>
    <h2 class="text-xl font-semibold">Enter OTP</h2>
    <p class="text-gray-500 text-sm mt-2">
        Please enter the OTP code sent to your email address.
    </p>
</div>

<form method="POST" action="{{ route('otp.verify') }}" class="space-y-5" id="otpForm">
    @csrf

    <div class="flex justify-center gap-4 mb-6">
        @for($i = 0; $i < 4; $i++)
            <input type="text"
                name="otp[]"
                maxlength="1"
                inputmode="numeric"
                pattern="[0-9]*"
                class="otp-input w-14 h-14 text-center text-xl border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:outline-none">
        @endfor
    </div>

    <button type="submit"
        class="w-full bg-primary hover:bg-darkred text-white py-3 rounded-full font-semibold transition">
        Log In
    </button>
</form>

<script src="{{ asset('js/auth/otphelper.js') }}"></script>

@endsection
