@extends('welcome')

@section('content')

<div class="text-center mb-8">
    <img src="{{ asset('images/aaron-auth.png') }}" class="mx-auto h-36 mb-4">
</div>

@if ($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg mb-4">
        <ul class="text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Success message for resend --}}
<div id="successMessage" class="hidden bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-lg mb-4">
    <p class="text-sm"></p>
</div>

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
    
    {{-- Timer Display --}}
    <div class="mt-3">
        <p class="text-sm text-gray-600">
            Code expires in: <span id="timer" class="font-semibold text-primary"></span>
        </p>
    </div>
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

{{-- Resend OTP Button --}}
<div class="text-center mt-4">
    <button id="resendBtn"
        disabled
        class="text-sm text-gray-400 hover:text-primary transition disabled:cursor-not-allowed disabled:hover:text-gray-400">
        Resend OTP <span id="resendTimer"></span>
    </button>
</div>

<script src="{{ asset('js/auth/otphelper.js') }}"></script>
<script>
    // OTP Timer and Resend Logic
    let resendCooldown = 60; // 60 seconds cooldown between resends
    let resendTimer = resendCooldown;
    let countdownInterval;
    let resendInterval;

    // Detect which context we're in and use appropriate route
    const isLogin = {{ Session::has('otp.login') ? 'true' : 'false' }};
    const isRegister = {{ Session::has('otp.register') ? 'true' : 'false' }};
    const isPasswordReset = {{ Session::has('otp.password_reset') ? 'true' : 'false' }};
    
    let resendUrl = '{{ route("otp.resend") }}'; // default fallback
    
    if (isLogin) {
        resendUrl = '{{ route("otp.resend") }}';
    } else if (isRegister) {
        resendUrl = '{{ route("register.resend.otp") }}';
    } else if (isPasswordReset) {
        resendUrl = '{{ route("password.resend.otp") }}';
    }

    // Get expiry time - ALWAYS prioritize localStorage over session
    let expiryTime;
    const storedExpiryTime = localStorage.getItem('otp_expiry_time');
    const sessionExpiryTime = @if(Session::has('otp.login'))
            {{ \Carbon\Carbon::parse(Session::get('otp.login')['expires_at'])->timestamp * 1000 }}
        @elseif(Session::has('otp.register'))
            {{ \Carbon\Carbon::parse(Session::get('otp.register')['expires_at'])->timestamp * 1000 }}
        @elseif(Session::has('otp.password_reset'))
            {{ \Carbon\Carbon::parse(Session::get('otp.password_reset')['expires_at'])->timestamp * 1000 }}
        @else
            null
        @endif;

    if (storedExpiryTime) {
        // ALWAYS use stored expiry time if it exists
        expiryTime = parseInt(storedExpiryTime);
        console.log('Using stored expiry time from localStorage');
    } else if (sessionExpiryTime) {
        // Only use session time if localStorage doesn't exist (first visit)
        expiryTime = sessionExpiryTime;
        localStorage.setItem('otp_expiry_time', expiryTime);
        console.log('First visit - stored session expiry time to localStorage');
    } else {
        // Fallback - no session, no localStorage
        expiryTime = Date.now() + (5 * 60 * 1000);
        localStorage.setItem('otp_expiry_time', expiryTime);
        console.log('Fallback - created new expiry time');
    }

    // Start countdown timer
    function startCountdown() {
        clearInterval(countdownInterval);
        
        countdownInterval = setInterval(() => {
            const now = new Date().getTime();
            const distance = expiryTime - now;

            if (distance < 0) {
                clearInterval(countdownInterval);
                document.getElementById('timer').textContent = 'Expired';
                document.getElementById('timer').classList.add('text-red-500');
                enableResendButton();
                return;
            }

            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById('timer').textContent = 
                `${minutes}:${seconds.toString().padStart(2, '0')}`;
        }, 1000);
    }

    // Start resend cooldown (with localStorage persistence)
    function startResendCooldown() {
        const resendBtn = document.getElementById('resendBtn');
        const resendTimerSpan = document.getElementById('resendTimer');
        
        // Check if there's a stored cooldown in localStorage
        const storedCooldownEnd = localStorage.getItem('otp_resend_cooldown_end');
        const now = Date.now();
        
        console.log('=== Resend Cooldown Check ===');
        console.log('Stored cooldown end:', storedCooldownEnd ? new Date(parseInt(storedCooldownEnd)) : 'none');
        console.log('Current time:', new Date(now));
        
        if (storedCooldownEnd && now < parseInt(storedCooldownEnd)) {
            // Resume existing cooldown
            resendTimer = Math.ceil((parseInt(storedCooldownEnd) - now) / 1000);
            console.log('Resuming resend cooldown:', resendTimer, 'seconds remaining');
        } else {
            // Check if timer is already expired, enable button immediately
            if (Date.now() > expiryTime) {
                console.log('OTP expired, enabling resend button immediately');
                localStorage.removeItem('otp_resend_cooldown_end');
                enableResendButton();
                return;
            }
            
            // Check if this is first page load (no cooldown stored yet)
            if (!storedCooldownEnd) {
                console.log('First page load - no cooldown needed, enabling button');
                enableResendButton();
                return;
            }
            
            // Cooldown expired, enable button
            console.log('Cooldown expired, enabling resend button');
            localStorage.removeItem('otp_resend_cooldown_end');
            enableResendButton();
            return;
        }
        
        if (resendTimer > 0) {
            resendBtn.disabled = true;
            resendBtn.classList.remove('text-primary', 'hover:underline');
            resendBtn.classList.add('text-gray-400');
            resendTimerSpan.textContent = `(${resendTimer}s)`;
            
            clearInterval(resendInterval);
            resendInterval = setInterval(() => {
                resendTimer--;
                resendTimerSpan.textContent = `(${resendTimer}s)`;
                
                if (resendTimer <= 0) {
                    clearInterval(resendInterval);
                    localStorage.removeItem('otp_resend_cooldown_end');
                    enableResendButton();
                }
            }, 1000);
        } else {
            enableResendButton();
        }
    }

    // Enable resend button
    function enableResendButton() {
        const resendBtn = document.getElementById('resendBtn');
        const resendTimerSpan = document.getElementById('resendTimer');
        
        resendBtn.disabled = false;
        resendBtn.classList.remove('text-gray-400');
        resendBtn.classList.add('text-primary', 'hover:underline');
        resendTimerSpan.textContent = '';
        console.log('Resend button enabled');
    }

    // Handle resend OTP
    document.getElementById('resendBtn').addEventListener('click', async function() {
        if (this.disabled) return;

        this.disabled = true;
        this.textContent = 'Sending...';

        try {
            console.log('Resending OTP to:', resendUrl);
            
            const response = await fetch(resendUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (data.success) {
                // Show success message
                const successMsg = document.getElementById('successMessage');
                successMsg.querySelector('p').textContent = data.message;
                successMsg.classList.remove('hidden');
                
                setTimeout(() => {
                    successMsg.classList.add('hidden');
                }, 5000);

                // Update expiry time and FORCE update localStorage
                expiryTime = data.expires_at * 1000;
                localStorage.setItem('otp_expiry_time', expiryTime);
                console.log('New OTP sent - updated expiry time in localStorage');
                
                document.getElementById('timer').classList.remove('text-red-500');
                startCountdown();
                
                // Clear OTP inputs
                document.querySelectorAll('.otp-input').forEach(input => {
                    input.value = '';
                });
                document.querySelector('.otp-input').focus();
                
                // Reset button and start cooldown
                this.textContent = 'Resend OTP ';
                const timerSpan = document.createElement('span');
                timerSpan.id = 'resendTimer';
                this.appendChild(timerSpan);
                
                // Store new cooldown end time
                const cooldownEnd = Date.now() + (resendCooldown * 1000);
                localStorage.setItem('otp_resend_cooldown_end', cooldownEnd);
                console.log('Set new cooldown end:', new Date(cooldownEnd));
                startResendCooldown();
            } else {
                throw new Error(data.message || 'Failed to resend OTP');
            }
        } catch (error) {
            console.error('Resend OTP Error:', error);
            alert('Failed to resend OTP: ' + error.message);
            
            // Reset button
            this.disabled = false;
            this.textContent = 'Resend OTP ';
            const timerSpan = document.createElement('span');
            timerSpan.id = 'resendTimer';
            this.appendChild(timerSpan);
        }
    });

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        console.log('=== OTP Page Loaded ===');
        console.log('Context - Login:', isLogin, 'Register:', isRegister, 'Password Reset:', isPasswordReset);
        console.log('Using resend URL:', resendUrl);
        console.log('OTP expires at:', new Date(expiryTime));
        console.log('Current time:', new Date());
        console.log('Time remaining (ms):', expiryTime - Date.now());
        
        // Check if OTP is already expired
        if (Date.now() > expiryTime) {
            document.getElementById('timer').textContent = 'Expired';
            document.getElementById('timer').classList.add('text-red-500');
            console.log('OTP already expired on page load');
            enableResendButton();
        } else {
            startCountdown();
        }
        
        startResendCooldown();
    });

    // Clear localStorage ONLY when user successfully verifies OTP and leaves the page
    const otpForm = document.getElementById('otpForm');
    if (otpForm) {
        otpForm.addEventListener('submit', function(e) {
            console.log('Form submitted - will clear localStorage after navigation');
            // Set a flag that we're submitting
            sessionStorage.setItem('otp_form_submitted', 'true');
        });
    }

    // On page load, check if we just came from a successful submission
    if (sessionStorage.getItem('otp_form_submitted') === 'true') {
        console.log('Came from successful OTP submission - clearing localStorage');
        localStorage.removeItem('otp_expiry_time');
        localStorage.removeItem('otp_resend_cooldown_end');
        sessionStorage.removeItem('otp_form_submitted');
    }

    // If no OTP session exists, clear localStorage (user verified or session expired on backend)
    if (!isLogin && !isRegister && !isPasswordReset) {
        console.log('No OTP session found - clearing localStorage');
        localStorage.removeItem('otp_expiry_time');
        localStorage.removeItem('otp_resend_cooldown_end');
    }
</script>
<script>
    const fuelForm = document.getElementById('fuelForm');
    const submitBtn = fuelForm.querySelector('button[type="submit"]');

    fuelForm.addEventListener('submit', function() {
        // Disable the button immediately to prevent multiple clicks
        submitBtn.disabled = true;
        submitBtn.innerText = 'Verifying...'; // Optional: give user feedback
    });
</script>

@endsection