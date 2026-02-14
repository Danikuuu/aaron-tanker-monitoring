<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your OTP Code</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F5F5F5] text-[#111111] font-sans p-6">
    <div class="max-w-lg mx-auto bg-white rounded-lg shadow-md p-8 text-center">
        <h2 class="text-2xl font-semibold text-[#FF3B3B] mb-2">Hello!</h2>
        <p class="text-[#6B7280] text-base mb-6">
            We received a request to log in to your account. Use the OTP below to complete your login:
        </p>

        <h1 class="text-4xl font-bold text-[#FF3B3B] mb-6">{{ $otp }}</h1>

        <p class="text-[#6B7280] text-sm">
            This OTP is valid for 5 minutes. Please do not share it with anyone.
        </p>

        <p class="text-[#6B7280] text-xs mt-6">
            &copy; {{ date('Y') }} Your Company Name. All rights reserved.
        </p>
    </div>
</body>
</html>
