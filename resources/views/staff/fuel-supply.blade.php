<!-- resources/views/staff/fuel-supply.blade.php -->
@extends('staff.layout.guest')

@section('title', 'Fuel Supply In/Out')

@section('content')
<div class="min-h-screen" style="background-image: url('/images/img.png'); background-size: cover; background-position: center;">
    <div class="min-h-screen bg-black/50 backdrop-blur-sm">
  

        <!-- Main Content -->
        <div class="flex items-center justify-center px-8 py-16">
            <div class="bg-white rounded-lg shadow-2xl p-8 w-full max-w-3xl">
                <div class="text-center mb-8">
                    <div class="flex items-center justify-center gap-3 mb-4">
                        <img src="{{ asset('images/AARON.png') }}" class="h-48 xl:h-24 w-auto">
                    </div>
                    <h2 class="text-2xl font-bold">Fuel Supply In/Out</h2>
                </div>

                <div class="relative mb-6">
                    <input type="search" placeholder="Search"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                    <svg class="w-5 h-5 absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-primary text-white">
                                <th class="px-4 py-3 text-left">Tanker Number</th>
                                <th class="px-4 py-3 text-left">Arrival date In/Out</th>
                                <th class="px-4 py-3 text-left">Fuel Type</th>
                                <th class="px-4 py-3 text-left rounded-tr-lg">Litters Deliver In/Out</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-50">
                            @foreach($arrivals as $arrival)
                                @foreach($arrival->fuels as $fuel)
                                <tr class="border-b border-gray-200">
                                    <td class="px-4 py-3">{{ $arrival->tanker_number }}</td>
                                    <td class="px-4 py-3">{{ $arrival->arrival_date->format('Y-m-d') }}</td>
                                    <td class="px-4 py-3">{{ $fuel->fuel_type }}</td>
                                    <td class="px-4 py-3">{{ $fuel->liters}}</td>
                                </tr>
                                @endforeach
                             @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-primary py-4 fixed bottom-0 w-full">
            <p class="text-center text-white">© 2026 Aaron Gas Station. All Rights Reserved.</p>
        </footer>
    </div>
</div>
@endsection