<!-- resources/views/staff/fuel-supply.blade.php -->
@extends('staff.layout.guest')

@section('title', 'Fuel Supply In/Out')

@section('content')
<div class="min-h-screen" style="background-image: url('/images/gas-station-bg.jpg'); background-size: cover; background-position: center;">
    <div class="min-h-screen bg-black/50 backdrop-blur-sm">
        <!-- Header -->
        <header class="bg-[#FF5757] rounded-full mx-8 mt-8 px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-black rounded-full flex items-center justify-center">
                        <div class="text-[#FF5757] text-xl font-bold">A</div>
                    </div>
                </div>
                <nav class="flex items-center gap-8">
                    <a href="" class="text-white hover:text-gray-200 transition">Home</a>
                    <a href="" class="text-white hover:text-gray-200 transition">Tanker/In</a>
                    <div class="relative">
                        <button class="text-white hover:text-gray-200 transition flex items-center gap-2">
                            Tanker/Out
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>
                </nav>
            </div>
        </header>

        <!-- Main Content -->
        <div class="flex items-center justify-center px-8 py-16">
            <div class="bg-white rounded-lg shadow-2xl p-8 w-full max-w-3xl">
                <div class="text-center mb-8">
                    <div class="flex items-center justify-center gap-3 mb-4">
                        <div class="w-16 h-16 bg-black rounded-full flex items-center justify-center">
                            <div class="text-[#FF5757] text-2xl font-bold">A</div>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold tracking-wide">AARON</h1>
                            <div class="flex items-center gap-2">
                                <div class="text-xs">SINCE 2004</div>
                                <div class="flex gap-0.5">
                                    @for($i = 0; $i < 12; $i++)
                                        <div class="w-1.5 h-1.5 {{ $i % 2 == 0 ? 'bg-black' : 'bg-white border border-black' }}"></div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                    <h2 class="text-2xl font-bold">Fuel Supply In/Out</h2>
                </div>

                <div class="relative mb-6">
                    <input type="search" placeholder="Search"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
                    <svg class="w-5 h-5 absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-[#FF5757] text-white">
                                <th class="px-4 py-3 text-left rounded-tl-lg">ID</th>
                                <th class="px-4 py-3 text-left">Tanker Number</th>
                                <th class="px-4 py-3 text-left">Arrival date In/Out</th>
                                <th class="px-4 py-3 text-left">Fuel Type</th>
                                <th class="px-4 py-3 text-left rounded-tr-lg">Litters Deliver In/Out</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-50">
                            @for($i = 1; $i <= 5; $i++)
                            <tr class="border-b border-gray-200">
                                <td class="px-4 py-3">{{ $i }}</td>
                                <td class="px-4 py-3">01234</td>
                                <td class="px-4 py-3">01/18/2026</td>
                                <td class="px-4 py-3">Ipsum</td>
                                <td class="px-4 py-3">1,234 Litters</td>
                            </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-[#FF5757] py-4 fixed bottom-0 w-full">
            <p class="text-center text-white">© 2026 Aaron Gas Station. All Rights Reserved.</p>
        </footer>
    </div>
</div>
@endsection