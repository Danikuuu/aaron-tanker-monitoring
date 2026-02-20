<!-- resources/views/admin/password-reset.blade.php -->
@extends('super_admin.layout.app')

@section('title', 'Password Reset')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="text-center mb-8">
        <div class="flex items-center justify-center gap-3 mb-8">
            <div>
                <img src="{{ asset('images/AARON.png') }}" class="h-48 xl:h-24 w-auto">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-8">
        <div class="text-center mb-8">
            <div class="w-24 h-24 bg-red-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                <svg class="w-12 h-12 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold mb-2">Reset Password</h2>
            <p class="text-gray-600">Enter your email to reset your password.</p>
        </div>

        <form method="POST" action="{{ route('super_admin.password.email') }}">
            @csrf
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">
                    Email address<span class="text-primary">*</span>
                </label>
                <div class="relative">
                    <svg class="w-5 h-5 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <input type="email" name="email" placeholder="Email" required
                           class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
            </div>

            <button type="submit" class="w-full bg-primary text-white py-3 rounded-full hover:bg-[#ff4040] transition font-semibold text-lg">
                Reset Password
            </button>
        </form>
    </div>
</div>
@endsection