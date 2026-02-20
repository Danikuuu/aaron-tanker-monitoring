<!-- resources/views/layouts/guest.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aaron Gas Station')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        #mobile-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, opacity 0.3s ease;
            opacity: 0;
        }
        #mobile-menu.open {
            max-height: 400px;
            opacity: 1;
        }
        #hamburger .bar {
            display: block;
            width: 22px;
            height: 2px;
            background: white;
            border-radius: 2px;
            transition: transform 0.25s ease, opacity 0.25s ease;
            transform-origin: center;
        }
        #hamburger.open .bar:nth-child(1) { transform: translateY(6px) rotate(45deg); }
        #hamburger.open .bar:nth-child(2) { opacity: 0; transform: scaleX(0); }
        #hamburger.open .bar:nth-child(3) { transform: translateY(-6px) rotate(-45deg); }

        /* Pill navbar */
        .nav-pill {
            background: #FF4445;
            border-radius: 999px;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 20px rgba(249, 64, 64, 0.35);
        }

        /* Mobile dropdown card */
        .mobile-nav-card {
            background: #F94040;
            border-radius: 20px;
            margin-top: 10px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(249, 64, 64, 0.3);
        }
    </style>
</head>

<body class="min-h-screen font-sans" style="
    background-image: url('/images/img.png');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
">
    <div class="min-h-screen flex flex-col" style="background: rgba(30, 30, 30, 0.45);">

        {{-- Header: just spacing, the pill floats inside --}}
        <header class="px-4 sm:px-6 pt-4 pb-2">
            <div class="container mx-auto">

                {{-- Pill navbar --}}
                <div class="nav-pill">

                    {{-- Logo --}}
                    <img src="{{ asset('images/AARON1.png') }}" class="h-9 sm:h-10 w-auto">

                    {{-- Desktop Nav --}}
                    <nav class="hidden md:flex items-center gap-1">
                        <a href="{{ route('staff.fuel-supply') }}"
                           class="px-4 py-2 rounded-full text-white text-sm font-medium hover:bg-white/15 transition">
                            Home
                        </a>
                        <a href="{{ route('staff.tanker-in') }}"
                           class="px-4 py-2 rounded-full text-white text-sm font-medium hover:bg-white/15 transition">
                            Tanker In
                        </a>
                        <a href="{{ route('staff.tanker-out') }}"
                           class="px-4 py-2 rounded-full text-white text-sm font-medium hover:bg-white/15 transition">
                            Tanker Out
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="ml-1">
                            @csrf
                            <button type="submit"
                                    class="flex items-center gap-2 px-4 py-2 rounded-full text-white text-sm font-medium bg-white/15 hover:bg-white hover:text-primary transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </nav>

                    {{-- Hamburger (mobile) --}}
                    <button id="hamburger"
                            class="md:hidden flex flex-col gap-[5px] p-2 rounded-full hover:bg-white/15 transition"
                            aria-label="Toggle menu">
                        <span class="bar"></span>
                        <span class="bar"></span>
                        <span class="bar"></span>
                    </button>
                </div>

                {{-- Mobile dropdown --}}
                <div id="mobile-menu">
                    <div class="mobile-nav-card">
                        <nav class="flex flex-col p-3 gap-1">
                            <a href="{{ route('staff.fuel-supply') }}"
                               class="flex items-center gap-3 px-4 py-3 rounded-xl text-white text-sm font-medium hover:bg-white/15 transition">
                                <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                Home
                            </a>
                            <a href="{{ route('staff.tanker-in') }}"
                               class="flex items-center gap-3 px-4 py-3 rounded-xl text-white text-sm font-medium hover:bg-white/15 transition">
                                <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                                </svg>
                                Tanker In
                            </a>
                            <a href="{{ route('staff.tanker-out') }}"
                               class="flex items-center gap-3 px-4 py-3 rounded-xl text-white text-sm font-medium hover:bg-white/15 transition">
                                <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                </svg>
                                Tanker Out
                            </a>
                            <div class="pt-1 mt-1 border-t border-white/20">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="flex items-center gap-3 w-full px-4 py-3 rounded-xl text-white text-sm font-medium hover:bg-white hover:text-primary transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </nav>
                    </div>
                </div>

            </div>
        </header>

        <main class="flex-1 container mx-auto px-4 sm:px-6 py-6 sm:py-8">
            @yield('content')
        </main>

    </div>

    <script>
        const hamburger = document.getElementById('hamburger');
        const mobileMenu = document.getElementById('mobile-menu');

        hamburger.addEventListener('click', () => {
            const isOpen = mobileMenu.classList.toggle('open');
            hamburger.classList.toggle('open', isOpen);
            hamburger.setAttribute('aria-expanded', isOpen);
        });

        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('open');
                hamburger.classList.remove('open');
            });
        });
    </script>

</body>
</html>