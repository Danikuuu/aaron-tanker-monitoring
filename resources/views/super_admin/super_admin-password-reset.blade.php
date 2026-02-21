<!-- resources/views/admin/password-reset.blade.php -->
@extends('super_admin.layout.app')

@section('title', 'Change Password')

@section('content')
<div class="max-w-lg mx-auto">

    <div class="bg-white rounded-2xl shadow-lg p-8">

        {{-- Icon + heading --}}
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-red-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold mb-1">Change Password</h2>
            <p class="text-gray-500 text-sm">Enter and confirm your new password below.</p>
        </div>

        {{-- Success message --}}
        @if(session('status'))
            <div class="mb-6 flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ session('status') }}
            </div>
        @endif

        {{-- Validation errors --}}
        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                <ul class="space-y-1">
                    @foreach($errors->all() as $error)
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            {{ $error }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('super_admin.password.update') }}">
            @csrf
            @method('PUT')

            {{-- New Password --}}
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    New Password <span class="text-primary">*</span>
                </label>
                <div class="relative">
                    <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <input type="password" name="password" id="password"
                           placeholder="New password"
                           class="w-full pl-11 pr-11 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('password') border-red-400 @enderror"
                           required>
                    <button type="button" onclick="togglePassword('password', 'eye1')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                        <svg id="eye1" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
                <p class="text-xs text-gray-400 mt-1">Minimum 8 characters.</p>
            </div>

            {{-- Confirm Password --}}
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Confirm New Password <span class="text-primary">*</span>
                </label>
                <div class="relative">
                    <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           placeholder="Confirm new password"
                           class="w-full pl-11 pr-11 py-3 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                           required>
                    <button type="button" onclick="togglePassword('password_confirmation', 'eye2')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                        <svg id="eye2" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-primary text-white py-3 rounded-full hover:bg-[#ff4040] transition font-semibold text-base">
                Update Password
            </button>
        </form>
    </div>
</div>

<script>
    function togglePassword(fieldId, eyeId) {
        const input = document.getElementById(fieldId);
        const eye   = document.getElementById(eyeId);
        const isHidden = input.type === 'password';

        input.type = isHidden ? 'text' : 'password';

        eye.innerHTML = isHidden
            ? `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                     d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>`
            : `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                     d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                     d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
    }
</script>
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