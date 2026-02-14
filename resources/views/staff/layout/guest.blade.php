<!-- resources/views/layouts/guest.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aaron Gas Station')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen font-sans">

    <!-- Header -->
    <header class="bg-[#FF5757] text-white shadow-md">
        <div class="container mx-auto flex items-center justify-between px-6 py-4">
            <!-- Logo -->
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center">
                    <div class="text-[#FF5757] font-bold text-xl">A</div>
                </div>
                <span class="font-semibold text-lg">Aaron Gas Station</span>
            </div>

            <!-- Navigation -->
            <nav class="flex items-center gap-6">
                <a href="#" class="hover:text-gray-200 transition">Home</a>
                <a href="#" class="hover:text-gray-200 transition">Tanker/In</a>

                <!-- Dropdown example -->
                <div class="relative group">
                    <button class="flex items-center gap-1 hover:text-gray-200 transition">
                        Tanker/Out
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="absolute hidden group-hover:block mt-2 bg-white text-black rounded-md shadow-lg w-40">
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Option 1</a>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Option 2</a>
                    </div>
                </div>

                <div class="p-4">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex items-center gap-3 px-6 py-4 w-full rounded-lg hover:bg-white hover:text-[#FF5757] transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            <span class="text-lg">Logout</span>
                        </button>
                    </form>
                </div>
            </nav>
        </div>
    </header>

    <!-- Main content -->
    <main class="container mx-auto px-6 py-8">
        @yield('content')
    </main>

</body>
</html>
