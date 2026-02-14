<!-- resources/views/staff/tanker-departure.blade.php -->
@extends('welcome')

@section('title', 'Fuel Supply Out')

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
                    <a href="{{ route('staff.home') }}" class="text-white hover:text-gray-200 transition">Home</a>
                    <a href="{{ route('staff.tanker-in') }}" class="text-white hover:text-gray-200 transition">Tanker/In</a>
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
            <div class="bg-white rounded-lg shadow-2xl p-8 w-full max-w-2xl">
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
                    <h2 class="text-2xl font-bold">Fuel Supply Out</h2>
                </div>

                <form method="POST" action="{{ route('staff.tanker-departure.store') }}" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">
                                Tanker Number<span class="text-[#FF5757]">*</span>
                            </label>
                            <input type="text" name="tanker_number" placeholder="Enter tanker number" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">
                                Departure Date<span class="text-[#FF5757]">*</span>
                            </label>
                            <input type="date" name="departure_date" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">
                                Select Fuel type<span class="text-[#FF5757]">*</span>
                            </label>
                            <select name="fuel_type_1" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
                                <option value="">Select fuel type 1</option>
                                <option value="diesel">Diesel</option>
                                <option value="premium">Premium</option>
                                <option value="unleaded">Unleaded</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">
                                Litters Deliver<span class="text-[#FF5757]">*</span>
                            </label>
                            <input type="number" name="litters_1" placeholder="Litters Deliver" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">
                                Select Fuel type<span class="text-[#FF5757]">*</span>
                            </label>
                            <select name="fuel_type_2"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
                                <option value="">Select fuel type 2</option>
                                <option value="diesel">Diesel</option>
                                <option value="premium">Premium</option>
                                <option value="unleaded">Unleaded</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">
                                Litters Deliver<span class="text-[#FF5757]">*</span>
                            </label>
                            <input type="number" name="litters_2" placeholder="Litters Deliver"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">
                                Select Fuel type<span class="text-[#FF5757]">*</span>
                            </label>
                            <select name="fuel_type_3"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
                                <option value="">Select fuel type 3</option>
                                <option value="diesel">Diesel</option>
                                <option value="premium">Premium</option>
                                <option value="unleaded">Unleaded</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">
                                Litters Deliver<span class="text-[#FF5757]">*</span>
                            </label>
                            <input type="number" name="litters_3" placeholder="Litters Deliver"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-[#FF5757] text-white py-3 rounded-full hover:bg-[#ff4040] transition font-semibold text-lg mt-6">
                        Submit
                    </button>
                </form>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-[#FF5757] py-4 fixed bottom-0 w-full">
            <p class="text-center text-white">© 2026 Aaron Gas Station. All Rights Reserved.</p>
        </footer>
    </div>
</div>
@endsection