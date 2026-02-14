<!-- resources/views/admin/transaction-history.blade.php -->
@extends('admin.layout.app')

@section('title', 'Transaction History')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold mb-6">Transaction history</h2>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-[#FF5757] text-white">
                    <th class="px-4 py-3 text-left rounded-tl-lg">ID</th>
                    <th class="px-4 py-3 text-left">Tanker Number</th>
                    <th class="px-4 py-3 text-left">Arrival Date</th>
                    <th class="px-4 py-3 text-left">Fuel Type 1</th>
                    <th class="px-4 py-3 text-left">Litters</th>
                    <th class="px-4 py-3 text-left">Fuel Type 2</th>
                    <th class="px-4 py-3 text-left">Litters</th>
                    <th class="px-4 py-3 text-left">Fuel Type 3</th>
                    <th class="px-4 py-3 text-left">Litters</th>
                    <th class="px-4 py-3 text-left rounded-tr-lg">Action</th>
                </tr>
            </thead>
            <tbody class="bg-gray-50">
                @for($i = 1; $i <= 5; $i++)
                <tr class="border-b border-gray-200">
                    <td class="px-4 py-3">{{ $i }}</td>
                    <td class="px-4 py-3">012{{ $i }}</td>
                    <td class="px-4 py-3">01/18/2026</td>
                    <td class="px-4 py-3 text-green-600 font-semibold">Diesel</td>
                    <td class="px-4 py-3">1,234</td>
                    <td class="px-4 py-3 text-yellow-600 font-semibold">Premium</td>
                    <td class="px-4 py-3">1,234</td>
                    <td class="px-4 py-3 text-blue-600 font-semibold">Unleaded</td>
                    <td class="px-4 py-3">1,234</td>
                    <td class="px-4 py-3">
                        <button class="bg-[#FF5757] text-white px-4 py-2 rounded-lg hover:bg-[#ff4040] transition">
                            View
                        </button>
                    </td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>

    <div class="flex items-center justify-center gap-4 mt-6">
        <button class="bg-[#FFB8B8] text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Prev
        </button>
        <span class="text-gray-600">Page 1 of 4</span>
        <button class="bg-[#FF5757] text-white px-4 py-2 rounded-lg hover:bg-[#ff4040] transition flex items-center gap-2">
            Next
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
    </div>
</div>
@endsection