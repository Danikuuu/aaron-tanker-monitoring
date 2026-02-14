<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Aaron Auth</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

<div class="min-h-screen flex">

    <!-- Left Section -->
    <div class="hidden lg:flex w-1/2 relative">
        <div class="absolute inset-0">
            <img src="{{ asset('/images/img.png') }}" 
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/60"></div>
        </div>

        <div class="relative z-10 text-white flex flex-col justify-center items-center px-16">
            <h1 class="text-5xl font-bold mb-6">Welcome</h1>
            <p class="text-lg leading-relaxed text-gray-200">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                Ut enim ad minim veniam.
            </p>
        </div>
    </div>

    <!-- Right Section -->
    <div class="w-full lg:w-1/2 flex items-center justify-center bg-gray-100 px-6">
        <div class="w-full max-w-md">
            @yield('content')
        </div>
    </div>

</div>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>


</body>
</html>
