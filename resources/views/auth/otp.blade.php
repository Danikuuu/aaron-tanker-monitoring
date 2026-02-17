@extends('welcome')

@section('content')

<div class="text-center mb-8">
    <img src="{{ asset('images/AARON.png') }}" class="mx-auto mb-4 mix-blend-">
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
    <div class="w-24 h-24 bg-red-100 rounded-full mx-auto mb-4 flex items-center justify-center">
        <svg class="w-12 h-12 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
        </svg>
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
        Verify OTP
    </button>
</form>

<script src="{{ asset('js/auth/otphelper.js') }}"></script>

@endsection
