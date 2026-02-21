<!-- resources/views/admin/layout/app.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aaron Gas Station')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* Notification dropdown */
        .notif-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 360px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 12px 48px rgba(0,0,0,0.15);
            border: 1px solid #f0f0f0;
            z-index: 200;
            overflow: hidden;
            display: none;
        }
        .notif-dropdown.open { display: block; }

        @media (max-width: 480px) {
            .notif-dropdown {
                width: calc(100vw - 24px);
                right: -60px;
            }
        }

        .notif-head {
            padding: 14px 18px 10px;
            border-bottom: 1px solid #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .notif-head h4 {
            font-size: 0.85rem;
            font-weight: 700;
            color: #111;
        }
        .notif-clear {
            font-size: 0.72rem;
            color: #E53E3E;
            cursor: pointer;
            font-weight: 600;
            border: none;
            background: none;
            padding: 0;
        }
        .notif-clear:hover { text-decoration: underline; }

        .notif-list {
            max-height: 360px;
            overflow-y: auto;
        }
        .notif-item {
            display: flex;
            gap: 12px;
            padding: 12px 18px;
            border-bottom: 1px solid #fafafa;
            cursor: pointer;
            transition: background 0.12s;
            align-items: flex-start;
        }
        .notif-item:last-child { border-bottom: none; }
        .notif-item:hover { background: #fff5f5; }

        .notif-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 2px;
        }
        .notif-icon.arrival   { background: #EBF8FF; color: #2B6CB0; }
        .notif-icon.departure { background: #FFF5F5; color: #C53030; }

        .notif-body { flex: 1; min-width: 0; }
        .notif-body strong { font-size: 0.82rem; font-weight: 700; color: #111; display: block; }
        .notif-body span { font-size: 0.75rem; color: #999; }
        .notif-body p { font-size: 0.78rem; color: #555; margin-top: 2px; }

        .notif-empty {
            padding: 32px 18px;
            text-align: center;
            color: #ccc;
            font-size: 0.82rem;
        }

        /* Notification modal */
        .notif-modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(3px);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease;
        }
        .notif-modal-backdrop.open {
            opacity: 1;
            pointer-events: all;
        }
        .notif-modal {
            background: #fff;
            border-radius: 18px;
            width: 90%;
            max-width: 480px;
            max-height: 85vh;
            overflow-y: auto;
            box-shadow: 0 24px 80px rgba(0,0,0,0.18);
            transform: translateY(16px) scale(0.98);
            transition: transform 0.22s ease;
        }
        .notif-modal-backdrop.open .notif-modal {
            transform: translateY(0) scale(1);
        }
        .notif-modal-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 22px 16px;
            border-bottom: 1px solid #f0f0f0;
        }
        .notif-modal-head h3 {
            font-size: 1rem;
            font-weight: 700;
            color: #111;
        }
        .btn-modal-close {
            width: 32px; height: 32px;
            border-radius: 8px; border: 1.5px solid #e8e8e8;
            background: transparent; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            color: #888; transition: all 0.15s;
        }
        .btn-modal-close:hover { background: #f5f5f5; color: #333; }

        .notif-modal-body { padding: 20px 22px 24px; }

        .modal-type-badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 12px; border-radius: 20px;
            font-size: 0.72rem; font-weight: 700;
            margin-bottom: 16px;
        }
        .modal-type-badge.arrival   { background: #EBF8FF; color: #2B6CB0; border: 1px solid #BEE3F8; }
        .modal-type-badge.departure { background: #FFF5F5; color: #C53030; border: 1px solid #FED7D7; }

        .modal-meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 18px;
        }
        .modal-meta-item label {
            display: block;
            font-size: 0.67rem;
            font-weight: 700;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            color: #bbb;
            margin-bottom: 3px;
        }
        .modal-meta-item p {
            font-size: 0.875rem;
            color: #222;
            font-weight: 500;
        }
        .modal-fuel-title {
            font-size: 0.7rem; font-weight: 700;
            letter-spacing: 0.07em; text-transform: uppercase;
            color: #bbb; margin-bottom: 8px;
        }
        .modal-fuel-row {
            display: flex; align-items: flex-start;
            justify-content: space-between;
            padding: 10px 14px;
            background: #fafafa; border-radius: 10px;
            margin-bottom: 6px; border: 1px solid #f0f0f0;
            gap: 8px;
        }
        .modal-fuel-name { font-weight: 600; font-size: 0.85rem; color: #222; }
        .modal-fuel-liters { font-weight: 700; font-size: 0.95rem; color: #E53E3E; white-space: nowrap; }
        .modal-methanol-line {
            font-size: 0.72rem; color: #B45309; margin-top: 3px;
            display: flex; flex-wrap: wrap; gap: 6px;
        }
        .pill-sm {
            display: inline-flex; align-items: center; gap: 3px;
            padding: 2px 7px; border-radius: 6px;
            font-size: 0.67rem; font-weight: 600;
        }
        .pill-pure     { background: #F0FFF4; color: #276749; border: 1px solid #C6F6D5; }
        .pill-methanol { background: #FFFBEB; color: #92600A; border: 1px solid #FCD34D; }
        .pill-pct      { background: #FEF3C7; color: #92600A; border: 1px solid #FDE68A; }

        /* Badge on bell */
        .bell-badge {
            position: absolute;
            top: -4px; right: -4px;
            min-width: 18px; height: 18px;
            background: #E53E3E;
            color: #fff;
            font-size: 0.65rem;
            font-weight: 700;
            border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            padding: 0 4px;
            border: 2px solid #fff;
        }
    </style>
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

        {{-- ── Sidebar ──────────────────────────────────────────────────────── --}}
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="w-[280px] xl:w-[350px] bg-primary text-white
                   fixed h-screen flex flex-col justify-between z-30
                   transition-transform duration-300 ease-in-out
                   lg:translate-x-0"
        >
            <div>
                <div class="p-3">
                    {{-- Close button (mobile) --}}
                    <div class="flex items-center justify-between mb-4 lg:hidden">
                        <span class="text-white font-semibold text-sm uppercase tracking-widest">Menu</span>
                        <button @click="sidebarOpen = false" class="text-white hover:text-black transition p-1">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <img src="{{ asset('images/AARON1.png') }}" class="h-24 xl:h-32 w-100">
                </div>

                <nav class="px-3 xl:px-4 space-y-1">
                    <a href="{{ route('super_admin.overview') }}"
                       class="flex items-center gap-3 px-4 xl:px-6 py-3 xl:py-4 rounded-lg hover:bg-white hover:text-primary transition
                              {{ request()->routeIs('super_admin.overview') ? 'bg-white text-black' : '' }}">
                        <svg class="w-5 h-5 xl:w-6 xl:h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span class="text-sm xl:text-md">Overview</span>
                    </a>

                    <a href="{{ route('super_admin.analytics') }}"
                       class="flex items-center gap-3 px-4 xl:px-6 py-3 xl:py-4 rounded-lg hover:bg-white hover:text-primary transition
                              {{ request()->routeIs('super_admin.analytics') ? 'bg-white text-black' : '' }}">
                        <svg class="w-5 h-5 xl:w-6 xl:h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span class="text-sm xl:text-md">Analytics</span>
                    </a>

                    <a href="{{ route('super_admin.fuel-summary') }}"
                       class="flex items-center gap-3 px-4 xl:px-6 py-3 xl:py-4 rounded-lg hover:bg-white hover:text-primary transition
                              {{ request()->routeIs('super_admin.fuel-summary') ? 'bg-white text-black' : '' }}">
                        <svg class="w-5 h-5 xl:w-6 xl:h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 20V6a2 2 0 012-2h8a2 2 0 012 2v14M3 20h14M8 20v-5h4v5M19 8l2 2v8a1 1 0 01-1 1h-1M17 4l2 2"/>
                        </svg>
                        <span class="text-sm xl:text-md">Fuel Summary</span>
                    </a>

                    <a href="{{ route('super_admin.transaction-history') }}"
                       class="flex items-center gap-3 px-4 xl:px-6 py-3 xl:py-4 rounded-lg hover:bg-white hover:text-primary transition
                              {{ request()->routeIs('super_admin.transaction-history') ? 'bg-white text-black' : '' }}">
                        <svg class="w-5 h-5 xl:w-6 xl:h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm xl:text-md">Transaction History</span>
                    </a>

                    {{-- Staff Management --}}
                    <a href="{{ route('super_admin.staff-management') }}"
                       class="flex items-center gap-3 px-4 xl:px-6 py-3 xl:py-4 rounded-lg hover:bg-white hover:text-primary transition
                              {{ request()->routeIs('super_admin.staff-management') ? 'bg-white text-black' : '' }}">
                        {{-- Users / people icon --}}
                        <svg class="w-5 h-5 xl:w-6 xl:h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4 0a4 4 0 100-8 4 4 0 000 8zm6 0a3 3 0 10-6 0"/>
                        </svg>
                        <span class="text-sm xl:text-md">Staff Management</span>
                    </a>

                    {{-- BR Receipt --}}
                    <a href="{{ route('super_admin.br-receipt') }}"
                       class="flex items-center gap-3 px-4 xl:px-6 py-3 xl:py-4 rounded-lg hover:bg-white hover:text-primary transition
                              {{ request()->routeIs('super_admin.br-receipt') ? 'bg-white text-black' : '' }}">
                        {{-- Receipt / document icon --}}
                        <svg class="w-5 h-5 xl:w-6 xl:h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        <span class="text-sm xl:text-md">BR Receipt</span>
                    </a>

                    {{-- Payments --}}
                    <a href="{{ route('super_admin.br-receipt-payments.index') }}"
                       class="flex items-center gap-3 px-4 xl:px-6 py-3 xl:py-4 rounded-lg hover:bg-white hover:text-primary transition
                              {{ request()->routeIs('super_admin.br-receipt-payments.index') ? 'bg-white text-black' : '' }}">
                        {{-- Credit card / payment icon --}}
                        <svg class="w-5 h-5 xl:w-6 xl:h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <span class="text-sm xl:text-md">Payments</span>
                    </a>

                    <a href="{{ route('super_admin.password.edit') }}"
                       class="flex items-center gap-3 px-4 xl:px-6 py-3 xl:py-4 rounded-lg hover:bg-white hover:text-primary transition
                              {{ request()->routeIs('super_admin.password-reset') ? 'bg-white text-black' : '' }}">
                        <svg class="w-5 h-5 xl:w-6 xl:h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                        </svg>
                        <span class="text-sm xl:text-md">Password Reset</span>
                    </a>
                </nav>
            </div>

            <div class="p-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="flex items-center gap-3 px-4 xl:px-6 py-3 xl:py-4 w-full rounded-lg hover:bg-white hover:text-primary transition">
                        <svg class="w-5 h-5 xl:w-6 xl:h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span class="text-sm xl:text-md">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- ── Main Content ──────────────────────────────────────────────────── --}}
        <main class="w-full lg:ml-[280px] xl:ml-[350px] lg:w-[calc(100%-280px)] xl:w-[calc(100%-350px)] h-screen overflow-y-auto flex flex-col">

            <header class="px-4 sm:px-6 xl:px-8 py-4 mt-4 xl:mt-8 flex items-center justify-between gap-4">

                {{-- Hamburger --}}
                <button @click="sidebarOpen = true"
                        class="lg:hidden p-2 rounded-lg bg-primary text-white hover:bg-[#e04444] transition shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                {{-- Search --}}
                <div class="flex-1 max-w-xs sm:max-w-sm">
                    <div class="relative">
                        <input type="search" placeholder="Search"
                               class="w-full px-4 py-1.5 border rounded-md focus:outline-none focus:ring-2 focus:ring-primary text-sm">
                        <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                {{-- Right: notification + profile --}}
                <div class="flex items-center gap-3 sm:gap-4 shrink-0">

                    {{-- Notification Bell --}}
                    <div class="relative" id="notif-wrap">
                        <button id="bell-btn"
                                class="relative p-1.5 rounded-lg hover:bg-gray-100 transition"
                                aria-label="Notifications">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span id="bell-badge" class="bell-badge" style="display:none;">0</span>
                        </button>

                        {{-- Dropdown --}}
                        <div class="notif-dropdown" id="notif-dropdown">
                            <div class="notif-head">
                                <h4>Recent Activity</h4>
                                <button class="notif-clear" id="notif-mark-read">Mark all read</button>
                            </div>
                            <div class="notif-list" id="notif-list">
                                <div class="notif-empty">Loading...</div>
                            </div>
                        </div>
                    </div>

                    {{-- Profile --}}
                    <div class="flex items-center gap-2 sm:gap-3 bg-primary py-1 px-2 sm:px-3 rounded-l-3xl">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-[#1e1a1a] rounded-full flex items-center justify-center text-white font-semibold text-sm shrink-0">
                            <img src="{{ asset('images/logo.png') }}" alt="">
                        </div>
                        <div class="hidden sm:block">
                            <div class="font-semibold text-white text-sm">{{ auth()->user()->first_name ?? 'Admin' }}</div>
                            <div class="text-xs text-red-100">{{ auth()->user()->email ?? '' }}</div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="flex-1 p-4 sm:p-6 xl:p-8">
                @yield('content')
            </div>
        </main>
    </div>

    {{-- ── Notification Detail Modal ──────────────────────────────────────── --}}
    <div class="notif-modal-backdrop" id="notif-modal-backdrop">
        <div class="notif-modal">
            <div class="notif-modal-head">
                <h3 id="modal-title">Transaction Detail</h3>
                <button class="btn-modal-close" id="modal-close-btn">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="notif-modal-body" id="modal-body"></div>
        </div>
    </div>

    <script>
    (() => {
        // ── State ──────────────────────────────────────────────────────────
        let notifications  = [];
        let readIds        = JSON.parse(localStorage.getItem('notif_read_ids') || '[]');
        let dropdownOpen   = false;

        const bellBtn      = document.getElementById('bell-btn');
        const bellBadge    = document.getElementById('bell-badge');
        const dropdown     = document.getElementById('notif-dropdown');
        const notifList    = document.getElementById('notif-list');
        const markReadBtn  = document.getElementById('notif-mark-read');
        const backdrop     = document.getElementById('notif-modal-backdrop');
        const modalBody    = document.getElementById('modal-body');
        const modalTitle   = document.getElementById('modal-title');
        const modalClose   = document.getElementById('modal-close-btn');

        // ── Fetch notifications ────────────────────────────────────────────
        function fetchNotifications() {
            fetch('{{ route("super_admin.notifications") }}', {
                headers: { 'Accept': 'application/json' }
            })
            .then(r => r.json())
            .then(data => {
                notifications = data.notifications || [];
                renderDropdown();
                updateBadge();
            })
            .catch(() => {});
        }

        // ── Badge count = unread ───────────────────────────────────────────
        function unreadCount() {
            return notifications.filter(n => !readIds.includes(notifKey(n))).length;
        }
        function notifKey(n) { return n.type + '_' + n.id; }

        function updateBadge() {
            const count = unreadCount();
            if (count > 0) {
                bellBadge.textContent = count > 99 ? '99+' : count;
                bellBadge.style.display = 'flex';
            } else {
                bellBadge.style.display = 'none';
            }
        }

        // ── Render dropdown list ───────────────────────────────────────────
        function renderDropdown() {
            if (notifications.length === 0) {
                notifList.innerHTML = '<div class="notif-empty">No recent activity in the last 24 hours.</div>';
                return;
            }

            notifList.innerHTML = notifications.map(n => {
                const isUnread = !readIds.includes(notifKey(n));
                const fuelNames = n.fuels.map(f => f.fuel_type).join(', ');
                return `
                    <div class="notif-item${isUnread ? ' notif-unread' : ''}"
                         data-key="${notifKey(n)}"
                         data-idx="${notifications.indexOf(n)}">
                        <div class="notif-icon ${n.type}">
                            ${n.type === 'arrival'
                                ? `<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                   </svg>`
                                : `<svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                   </svg>`
                            }
                        </div>
                        <div class="notif-body">
                            <strong>
                                ${isUnread ? '<span style="display:inline-block;width:7px;height:7px;border-radius:50%;background:#E53E3E;margin-right:5px;vertical-align:middle;"></span>' : ''}
                                Tanker ${n.tanker_number}
                                <span style="font-weight:400;color:#888;font-size:0.75rem;margin-left:4px;">${n.type === 'arrival' ? '↓ Arrival' : '↑ Departure'}</span>
                            </strong>
                            <p>${fuelNames} · ${n.date}</p>
                            <span>${n.created_at} · by ${n.recorded_by}</span>
                        </div>
                    </div>
                `;
            }).join('');

            // Attach click handlers
            notifList.querySelectorAll('.notif-item').forEach(item => {
                item.addEventListener('click', () => {
                    const key = item.dataset.key;
                    const idx = parseInt(item.dataset.idx);
                    markRead(key);
                    openModal(notifications[idx]);
                    closeDropdown();
                });
            });
        }

        // ── Mark read ──────────────────────────────────────────────────────
        function markRead(key) {
            if (!readIds.includes(key)) {
                readIds.push(key);
                localStorage.setItem('notif_read_ids', JSON.stringify(readIds));
            }
            updateBadge();
            renderDropdown();
        }

        markReadBtn.addEventListener('click', () => {
            notifications.forEach(n => {
                const key = notifKey(n);
                if (!readIds.includes(key)) readIds.push(key);
            });
            localStorage.setItem('notif_read_ids', JSON.stringify(readIds));
            updateBadge();
            renderDropdown();
        });

        // ── Dropdown toggle ────────────────────────────────────────────────
        function openDropdown()  { dropdown.classList.add('open'); dropdownOpen = true; }
        function closeDropdown() { dropdown.classList.remove('open'); dropdownOpen = false; }

        bellBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdownOpen ? closeDropdown() : openDropdown();
        });

        document.addEventListener('click', (e) => {
            if (!document.getElementById('notif-wrap').contains(e.target)) closeDropdown();
        });

        // ── Modal ──────────────────────────────────────────────────────────
        function openModal(n) {
            modalTitle.textContent = `Tanker ${n.tanker_number} — ${n.type.charAt(0).toUpperCase() + n.type.slice(1)}`;

            const fuelsHtml = n.fuels.map(f => {
                const pure          = parseFloat(f.liters);
                const methanol      = parseFloat(f.methanol_liters || 0);
                const total         = pure + methanol;
                const hasMethanol   = methanol > 0;
                return `
                    <div class="modal-fuel-row">
                        <div>
                            <div class="modal-fuel-name" style="text-transform:capitalize">${f.fuel_type}</div>
                            ${hasMethanol ? `
                            <div class="modal-methanol-line">
                                <span class="pill-sm pill-pure">✓ ${pure.toFixed(2)} L pure</span>
                                <span class="pill-sm pill-methanol">⚗️ ${methanol.toFixed(2)} L methanol</span>
                                <span class="pill-sm pill-pct">${f.methanol_percent}% mix</span>
                            </div>` : ''}
                        </div>
                        <div class="modal-fuel-liters">${total.toFixed(2)} L</div>
                    </div>`;
            }).join('');

            modalBody.innerHTML = `
                <span class="modal-type-badge ${n.type}">
                    ${n.type === 'arrival' ? '↓ Arrival' : '↑ Departure'}
                </span>
                <div class="modal-meta-grid">
                    <div class="modal-meta-item">
                        <label>Tanker Number</label>
                        <p style="font-weight:700;">${n.tanker_number}</p>
                    </div>
                    <div class="modal-meta-item">
                        <label>Driver</label>
                        <p>${n.driver || '—'}</p>
                    </div>
                    <div class="modal-meta-item">
                        <label>${n.type === 'arrival' ? 'Arrival' : 'Departure'} Date</label>
                        <p>${n.date}</p>
                    </div>
                    <div class="modal-meta-item">
                        <label>Recorded By</label>
                        <p>${n.recorded_by}</p>
                    </div>
                    <div class="modal-meta-item" style="grid-column:1/-1">
                        <label>Logged</label>
                        <p>${n.created_at}</p>
                    </div>
                </div>
                <div class="modal-fuel-title">Fuel Breakdown</div>
                ${fuelsHtml}
            `;

            backdrop.classList.add('open');
        }

        function closeModal() { backdrop.classList.remove('open'); }

        modalClose.addEventListener('click', closeModal);
        backdrop.addEventListener('click', e => { if (e.target === backdrop) closeModal(); });
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

        // ── Poll every 30 seconds ──────────────────────────────────────────
        fetchNotifications();
        setInterval(fetchNotifications, 3000);
    })();
    </script>
@stack('scripts')
</body>
</html>