<!-- resources/views/admin/layout/app.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aaron Gas Station')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="overflow-hidde">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-[350px] bg-[#FF5757] text-white fixed h-screen flex flex-col justify-between">
            <!-- Logo -->
            <div>
                            <div class="p-8">
                <div class="flex items-center gap-3">
                    <div class="w-16 h-16 bg-black rounded-full flex items-center justify-center">
                        <div class="text-[#FF5757] text-2xl font-bold">A</div>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold tracking-wide">AARON</h1>
                        <div class="flex items-center gap-1 mt-1">
                            <div class="text-xs">SINCE 2004</div>
                            <div>
                                <div class="flex">
                                    @for($i = 0; $i < 20; $i++)
                                        <div class="w-2 h-2 {{ $i % 2 == 0 ? 'bg-white' : 'bg-black' }}"></div>
                                    @endfor
                                </div>
                                <div class="flex">
                                    @for($i = 0; $i < 20; $i++)
                                        <div class="w-2 h-2 {{ $i % 2 == 0 ? 'bg-black' : 'bg-white' }}"></div>
                                    @endfor
                                </div>
                           </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="px-4">
                <a href="{{ route('admin.overview') }}" class="flex items-center gap-3 px-6 py-4 rounded-lg hover:bg-white hover:text-[#FF5757] transition {{ request()->routeIs('admin.overview') ? 'bg-white text-black' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="text-md">Overview</span>
                </a>

                <a href="{{ route('admin.analytics') }}" class="flex items-center gap-3 px-6 py-4 rounded-lg hover:bg-white hover:text-[#FF5757] transition {{ request()->routeIs('admin.analytics') ? 'bg-white text-black' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span class="text-md">Analytics</span>
                </a>

                <a href="" class="flex items-center gap-3 px-6 py-4 rounded-lg hover:bg-white hover:text-[#FF5757] transition {{ request()->routeIs('admin.fuel-summary') ? 'bg-white text-black' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="text-md">Fuel Summary</span>
                </a>

                <a href="{{ route('admin.transaction-history') }}" class="flex items-center gap-3 px-6 py-4 rounded-lg hover:bg-white hover:text-[#FF5757] transition {{ request()->routeIs('admin.transaction-history') ? 'bg-white text-black' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-md">Transaction History</span>
                </a>

                <a href="{{ route('admin.staff-management') }}" class="flex items-center gap-3 px-6 py-4 rounded-lg hover:bg-white hover:text-[#FF5757] transition {{ request()->routeIs('admin.staff-management') ? 'bg-white text-black' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span class="text-md">Staff Management</span>
                </a>

                <a href="{{ route('admin.br-receipt') }}" class="flex items-center gap-3 px-6 py-4 rounded-lg hover:bg-white hover:text-[#FF5757] transition {{ request()->routeIs('admin.br-receipt') ? 'bg-white text-black' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span class="text-md">BR Receipt</span>
                </a>

                <a href="{{ route('admin.password-reset') }}" class="flex items-center gap-3 px-6 py-4 rounded-lg hover:bg-white hover:text-[#FF5757] transition {{ request()->routeIs('admin.password-reset') ? 'bg-white text-black' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <span class="text-md">Password Reset</span>
                </a>
            </nav>
            </div>

            <!-- Logout -->
            <div class="p-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-6 py-4 w-full rounded-lg hover:bg-white hover:text-[#FF5757] transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span class="text-md">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="ml-[400px] w-[calc(100%-400px)] h-screen overflow-y-auto flex flex-col">
            <!-- Header -->
            <header class="px-8 py-4 mt-8 flex justify-end items-end">
                <div class="flex items-center justify-between">
                    <div class="flex-1 max-w-md">
                        <div class="relative">
                            <input type="search" placeholder="Search" class="w-full px-4 py-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
                            <svg class="w-5 h-5 absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <!-- Notification -->
                        <button class="relative">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <span class="absolute -top-1 -right-1 w-4 h-4 bg-[#FF5757] text-white text-xs rounded-full flex items-center justify-center">1</span>
                        </button>

                        <!-- User Profile -->
                        <div class="flex items-center gap-3 bg-red-500 py-1 px-3 rounded-l-3xl">
                            <div class="w-10 h-10 bg-[#1e1a1a] rounded-full flex items-center justify-center text-white font-semibold">
                                <!-- {{ substr(auth()->user()->name ?? 'Admin', 0, 2) }} -->
                                  test
                            </div>
                            <div>
                                <div class="font-semibold">{{ auth()->user()->first_name ?? 'Admin Ipsum' }}</div>
                                <!-- <div class="font-semibold text-white">test</div> -->
                                <div class="text-xs text-gray-500">{{ auth()->user()->email ?? 'adminipsum@ipsum.com' }}</div>
                                <!-- <div class="text-xs text-white">test@email.com</div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="flex-1 p-8">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>