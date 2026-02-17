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
<body class="overflow-hidden" x-data="{ sidebarOpen: false }">

    {{-- Mobile Overlay --}}
    <div
        x-show="sidebarOpen"
        x-transition:enter="transition-opacity duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="sidebarOpen = false"
        class="fixed inset-0 bg-black/50 z-20 lg:hidden"
    ></div>

    <div class="flex min-h-screen">

        {{-- ── Sidebar ─────────────────────────────────────────────────────── --}}
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="w-[280px] xl:w-[350px] bg-[#FF5757] text-white
                   fixed h-screen flex flex-col justify-between z-30
                   transition-transform duration-300 ease-in-out
                   lg:translate-x-0"
        >
            {{-- Logo --}}
            <div>
                <div class="p-6 xl:p-8">
                    {{-- Close button — mobile only --}}
                    <div class="flex items-center justify-between mb-4 lg:hidden">
                        <span class="text-white font-semibold text-sm uppercase tracking-widest">Menu</span>
                        <button @click="sidebarOpen = false" class="text-white hover:text-black transition p-1">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <img src="{{ asset('images/AARON1.png') }}" class="h-48 xl:h-24 w-100">
                </div>

                {{-- Navigation --}}
                <nav class="px-3 xl:px-4 space-y-1">

                    {{-- Overview --}}
                    <a href="{{ route('admin.overview') }}"
                       class="flex items-center gap-3 px-4 xl:px-6 py-3 xl:py-4 rounded-lg hover:bg-white hover:text-[#FF5757] transition
                              {{ request()->routeIs('admin.overview') ? 'bg-white text-black' : '' }}">
                        <svg class="w-5 h-5 xl:w-6 xl:h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span class="text-sm xl:text-md">Overview</span>
                    </a>

                    {{-- Analytics --}}
                    <a href="{{ route('admin.analytics') }}"
                       class="flex items-center gap-3 px-4 xl:px-6 py-3 xl:py-4 rounded-lg hover:bg-white hover:text-[#FF5757] transition
                              {{ request()->routeIs('admin.analytics') ? 'bg-white text-black' : '' }}">
                        <svg class="w-5 h-5 xl:w-6 xl:h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span class="text-sm xl:text-md">Analytics</span>
                    </a>

                    {{-- Fuel Summary --}}
                    <a href="{{ route('admin.fuel-summary') }}"
                       class="flex items-center gap-3 px-4 xl:px-6 py-3 xl:py-4 rounded-lg hover:bg-white hover:text-[#FF5757] transition
                              {{ request()->routeIs('admin.fuel-summary') ? 'bg-white text-black' : '' }}">
                        {{-- Gas pump icon --}}
                        <svg class="w-5 h-5 xl:w-6 xl:h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 20V6a2 2 0 012-2h8a2 2 0 012 2v14M3 20h14M8 20v-5h4v5M19 8l2 2v8a1 1 0 01-1 1h-1M17 4l2 2"/>
                        </svg>
                        <span class="text-sm xl:text-md">Fuel Summary</span>
                    </a>

                    {{-- Transaction History --}}
                    <a href="{{ route('admin.transaction-history') }}"
                       class="flex items-center gap-3 px-4 xl:px-6 py-3 xl:py-4 rounded-lg hover:bg-white hover:text-[#FF5757] transition
                              {{ request()->routeIs('admin.transaction-history') ? 'bg-white text-black' : '' }}">
                        {{-- Clock / history icon --}}
                        <svg class="w-5 h-5 xl:w-6 xl:h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm xl:text-md">Transaction History</span>
                    </a>

                    {{-- Staff Management --}}
                    <a href="{{ route('admin.staff-management') }}"
                       class="flex items-center gap-3 px-4 xl:px-6 py-3 xl:py-4 rounded-lg hover:bg-white hover:text-[#FF5757] transition
                              {{ request()->routeIs('admin.staff-management') ? 'bg-white text-black' : '' }}">
                        {{-- Users / people icon --}}
                        <svg class="w-5 h-5 xl:w-6 xl:h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4 0a4 4 0 100-8 4 4 0 000 8zm6 0a3 3 0 10-6 0"/>
                        </svg>
                        <span class="text-sm xl:text-md">Staff Management</span>
                    </a>

                    {{-- BR Receipt --}}
                    <a href="{{ route('admin.br-receipt') }}"
                       class="flex items-center gap-3 px-4 xl:px-6 py-3 xl:py-4 rounded-lg hover:bg-white hover:text-[#FF5757] transition
                              {{ request()->routeIs('admin.br-receipt') ? 'bg-white text-black' : '' }}">
                        {{-- Receipt / document icon --}}
                        <svg class="w-5 h-5 xl:w-6 xl:h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        <span class="text-sm xl:text-md">BR Receipt</span>
                    </a>

                    {{-- Payments --}}
                    <a href="{{ route('admin.br-receipt-payments.index') }}"
                       class="flex items-center gap-3 px-4 xl:px-6 py-3 xl:py-4 rounded-lg hover:bg-white hover:text-[#FF5757] transition
                              {{ request()->routeIs('admin.br-receipt-payments.index') ? 'bg-white text-black' : '' }}">
                        {{-- Credit card / payment icon --}}
                        <svg class="w-5 h-5 xl:w-6 xl:h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <span class="text-sm xl:text-md">Payments</span>
                    </a>

                    {{-- Password Reset --}}
                    <a href="{{ route('admin.password.request') }}"
                       class="flex items-center gap-3 px-4 xl:px-6 py-3 xl:py-4 rounded-lg hover:bg-white hover:text-[#FF5757] transition
                              {{ request()->routeIs('admin.password-reset') ? 'bg-white text-black' : '' }}">
                        {{-- Key icon --}}
                        <svg class="w-5 h-5 xl:w-6 xl:h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                        <span class="text-sm xl:text-md">Password Reset</span>
                    </a>

                </nav>
            </div>

            {{-- Logout --}}
            <div class="p-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="flex items-center gap-3 px-4 xl:px-6 py-3 xl:py-4 w-full rounded-lg hover:bg-white hover:text-[#FF5757] transition">
                        <svg class="w-5 h-5 xl:w-6 xl:h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span class="text-sm xl:text-md">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- ── Main Content ─────────────────────────────────────────────────── --}}
        <main class="w-full lg:ml-[280px] xl:ml-[350px] lg:w-[calc(100%-280px)] xl:w-[calc(100%-350px)] h-screen overflow-y-auto flex flex-col">

            {{-- Header --}}
            <header class="px-4 sm:px-6 xl:px-8 py-4 mt-4 xl:mt-8 flex items-center justify-between gap-4">

                {{-- Hamburger — mobile/tablet only --}}
                <button @click="sidebarOpen = true"
                        class="lg:hidden p-2 rounded-lg bg-[#FF5757] text-white hover:bg-[#e04444] transition shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                {{-- Search --}}
                <div class="flex-1 max-w-xs sm:max-w-sm">
                    <div class="relative">
                        <input type="search" placeholder="Search"
                               class="w-full px-4 py-1.5 border rounded-md focus:outline-none focus:ring-2 focus:ring-[#FF5757] text-sm">
                        <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                {{-- Right side: notification + profile --}}
                <div class="flex items-center gap-3 sm:gap-4 shrink-0">
                    {{-- Notification --}}
                    <button class="relative">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-[#FF5757] text-white text-xs rounded-full flex items-center justify-center">1</span>
                    </button>

                    {{-- User Profile --}}
                    <div class="flex items-center gap-2 sm:gap-3 bg-red-500 py-1 px-2 sm:px-3 rounded-l-3xl">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-[#1e1a1a] rounded-full flex items-center justify-center text-white font-semibold text-sm shrink-0">
                            <img src="{{ asset('images/logo.png') }}" alt="" srcset="">
                        </div>
                        <div class="hidden sm:block">
                            <div class="font-semibold text-white text-sm">{{ auth()->user()->first_name ?? 'Admin Ipsum' }}</div>
                            <div class="text-xs text-red-100">{{ auth()->user()->email ?? 'adminipsum@ipsum.com' }}</div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Page Content --}}
            <div class="flex-1 p-4 sm:p-6 xl:p-8">
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>