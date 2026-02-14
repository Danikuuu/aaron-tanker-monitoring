<!-- resources/views/auth/password-reset.blade.php -->
@extends('welcome')

@section('title', 'Reset Password')

@section('content')
<div class="min-h-screen flex" style="background-image: url('/images/gas-station-bg.jpg'); background-size: cover; background-position: center;">
    <!-- Left Side - Welcome -->
    <div class="w-1/2 bg-black/60 backdrop-blur-sm flex flex-col items-center justify-center text-white p-12">
        <h1 class="text-6xl font-bold italic mb-8">Welcome</h1>
        <p class="text-center text-lg italic max-w-md leading-relaxed">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
        </p>
        <div class="flex gap-2 mt-8">
            <div class="w-3 h-3 bg-white rounded-full"></div>
            <div class="w-3 h-3 bg-white/50 rounded-full"></div>
            <div class="w-3 h-3 bg-white/50 rounded-full"></div>
        </div>
    </div>

    <!-- Right Side - Reset Form -->
    <div class="w-1/2 bg-white flex items-center justify-center p-12">
        <div class="max-w-md w-full">
            <div class="text-center mb-8">
                <div class="flex items-center justify-center gap-3 mb-6">
                    <div class="w-20 h-20 bg-black rounded-full flex items-center justify-center">
                        <div class="text-[#FF5757] text-3xl font-bold">A</div>
                    </div>
                    <div>
                        <h1 class="text-5xl font-bold tracking-wide">AARON</h1>
                        <div class="flex items-center gap-2 mt-1">
                            <div class="text-sm">SINCE 2004</div>
                            <div class="flex gap-0.5">
                                @for($i = 0; $i < 12; $i++)
                                    <div class="w-2 h-2 {{ $i % 2 == 0 ? 'bg-black' : 'bg-white border border-black' }}"></div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-24 h-24 bg-gray-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>

                <h2 class="text-2xl font-bold mb-2">Reset Password</h2>
                <p class="text-gray-600">Enter your email to reset your password.</p>
            </div>

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-2">
                        Email address<span class="text-[#FF5757]">*</span>
                    </label>
                    <div class="relative">
                        <svg class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <input type="email" name="email" placeholder="Username" required
                               class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#FF5757] text-white py-3 rounded-full hover:bg-[#ff4040] transition font-semibold text-lg">
                    Reset Password
                </button>
            </form>
        </div>
    </div>
</div>
@endsection