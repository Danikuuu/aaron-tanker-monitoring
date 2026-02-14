<!-- resources/views/admin/overview.blade.php -->
@extends('admin.layout.app')

@section('title', 'Dashboard Overview')

@section('content')
<div class="space-y-6">
    <!-- Fuel Stocks -->
    <div class="font-bold text-2xl">Fuel Stocks</div>
    <div class="grid grid-cols-4 gap-6">
        <div class="bg-white rounded-lg border border-gray-200 shadow-[0_0_10px_rgba(0,0,0,0.30)] p-6">
            <h3 class="font-bold text-lg mb-2">Diesel</h3>
            <p class="text-[#FF5757] text-3xl font-bold">1,234 Liters</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 shadow-[0_0_10px_rgba(0,0,0,0.30)] p-6">
            <h3 class="font-bold text-lg mb-2">Premium</h3>
            <p class="text-[#FF5757] text-3xl font-bold">1,234 Liters</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 shadow-[0_0_10px_rgba(0,0,0,0.30)] p-6">
            <h3 class="font-bold text-lg mb-2">Unleaded</h3>
            <p class="text-[#FF5757] text-3xl font-bold">1,234 Liters</p>
        </div>
        <div class="bg-white rounded-lg border border-gray-200 shadow-[0_0_10px_rgba(0,0,0,0.30)] p-6">
            <h3 class="font-bold text-lg mb-2">Overall total fuel</h3>
            <p class="text-[#FF5757] text-3xl font-bold">2,468 Liters</p>
        </div>
    </div>

    <!-- Monthly Fuel Delivery Summary -->
    <div class="flex justify-between items-start gap-8">
        <div class="w-[70%]">
            <h2 class="text-xl font-semibold mb-4">Monthly Fuel Delivery summary</h2>
            <div class="bg-gray-100 h-64 rounded-lg flex items-center justify-center text-gray-400">
                <p>Lorem ipsum liters</p>
            </div>
        </div>

        <div class="w-[30%]">
            <h3 class="text-xl font-semibold mb-4">Delivery Summary</h3>
            <div class="bg-gray-100 h-64 rounded-xl p-2 overflow-auto">
                <table class="w-full h-full text-sm border-collapse rounded-xl overflow-hidden shadow-md">
                    <thead class="bg-[#FF5757] text-white">
                        <tr>
                            <th class="px-3 py-2 text-left first:rounded-tl-xl last:rounded-tr-xl">Fuel</th>
                            <th class="px-3 py-2 text-left">Liters</th>
                        </tr>
                    </thead>
                    <tbody class="h-full">
                        <tr class="bg-white border-b border-gray-200">
                            <td class="px-3 py-2">Diesel</td>
                            <td class="px-3 py-2">1,234</td>
                        </tr>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <td class="px-3 py-2">Premium</td>
                            <td class="px-3 py-2">1,234</td>
                        </tr>
                        <tr class="bg-white">
                            <td class="px-3 py-2">Unleaded</td>
                            <td class="px-3 py-2">1,234</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>


    </div>

    <!-- Fuel Arrival Summary -->
    <div class="bg-white rounded-xl overflow-hidden">
        <h2 class="text-xl font-semibold mb-4">Fuel Arrival Summary</h2>
        <table class="w-full table-auto border-collapse">
            <thead>
                <tr class="bg-[#FF5757] text-white">
                    <th class="px-4 py-3 text-left first:rounded-tl-xl last:rounded-tr-xl">ID</th>
                    <th class="px-4 py-3 text-left">Tanker Number</th>
                    <th class="px-4 py-3 text-left">Arrival Date</th>
                    <th class="px-4 py-3 text-left">Fuel Type 1</th>
                    <th class="px-4 py-3 text-left">Litters</th>
                    <th class="px-4 py-3 text-left">Fuel Type 2</th>
                    <th class="px-4 py-3 text-left">Litters</th>
                    <th class="px-4 py-3 text-left">Fuel Type 3</th>
                    <th class="px-4 py-3 text-left">Litters</th>
                    <th class="px-4 py-3 text-left first:rounded-tl-xl last:rounded-tr-xl">Action</th>
                </tr>
            </thead>
            <tbody>
                @for($i = 1; $i <= 5; $i++)
                <tr class="odd:bg-gray-100 even:bg-white border-b border-gray-200 last:rounded-b-xl">
                    <td class="px-4 py-3">{{ $i }}</td>
                    <td class="px-4 py-3">012{{ $i }}</td>
                    <td class="px-4 py-3">01/18/2026</td>
                    <td class="px-4 py-3 text-green-600">Diesel</td>
                    <td class="px-4 py-3">1,234</td>
                    <td class="px-4 py-3 text-yellow-600">Premium</td>
                    <td class="px-4 py-3">1,234</td>
                    <td class="px-4 py-3 text-blue-600">Unleaded</td>
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

    <!-- Fuel Departure Summary -->
    <div class="bg-white rounded-xloverflow-hidden">
                <h2 class="text-xl font-semibold mb-4">Fuel Departure Summary</h2>
        <div class="overflow-x-auto rounded-lg">
            <table class="w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-[#FF5757] text-white">
                        <th class="px-4 py-3 text-left first:rounded-tl-lg last:rounded-tr-lg">ID</th>
                        <th class="px-4 py-3 text-left">Tanker Number</th>
                        <th class="px-4 py-3 text-left">Departure Date</th>
                        <th class="px-4 py-3 text-left">Fuel Type 1</th>
                        <th class="px-4 py-3 text-left">Litters</th>
                        <th class="px-4 py-3 text-left">Fuel Type 2</th>
                        <th class="px-4 py-3 text-left">Litters</th>
                        <th class="px-4 py-3 text-left">Fuel Type 3</th>
                        <th class="px-4 py-3 text-left">Litters</th>
                        <th class="px-4 py-3 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="border-t border-gray-200">
                    @for($i = 1; $i <= 5; $i++)
                    <tr class="odd:bg-gray-100 even:bg-white border-b border-gray-200">
                        <td class="px-4 py-3">{{ $i }}</td>
                        <td class="px-4 py-3">012{{ $i }}</td>
                        <td class="px-4 py-3">01/19/2026</td>
                        <td class="px-4 py-3 text-green-600">Diesel</td>
                        <td class="px-4 py-3">1,234</td>
                        <td class="px-4 py-3 text-yellow-600">Premium</td>
                        <td class="px-4 py-3">1,234</td>
                        <td class="px-4 py-3 text-blue-600">Unleaded</td>
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
    </div>


    <!-- Fuel Departure Summary -->
    <!-- <div class="bg-white rounded-xl p-6">
        <h2 class="text-xl font-semibold mb-4">Fuel Departure Summary</h2>
        <div class="overflow-x-auto rounded-lg">
            <table class="w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-[#FF5757] text-white">
                        <th class="px-4 py-3 text-left first:rounded-tl-lg last:rounded-tr-lg">ID</th>
                        <th class="px-4 py-3 text-left">Tanker Number</th>
                        <th class="px-4 py-3 text-left">Departure Date</th>
                        <th class="px-4 py-3 text-left">Fuel Type 1</th>
                        <th class="px-4 py-3 text-left">Litters</th>
                        <th class="px-4 py-3 text-left">Fuel Type 2</th>
                        <th class="px-4 py-3 text-left">Litters</th>
                        <th class="px-4 py-3 text-left">Fuel Type 3</th>
                        <th class="px-4 py-3 text-left">Litters</th>
                        <th class="px-4 py-3 text-left">Action</th>
                    </tr>
                </thead>
                <tbody class="border-t border-gray-200">
                    @for($i = 1; $i <= 5; $i++)
                    <tr class="odd:bg-gray-100 even:bg-white border-b border-gray-200">
                        <td class="px-4 py-3">{{ $i }}</td>
                        <td class="px-4 py-3">012{{ $i }}</td>
                        <td class="px-4 py-3">01/19/2026</td>
                        <td class="px-4 py-3 text-green-600">Diesel</td>
                        <td class="px-4 py-3">1,234</td>
                        <td class="px-4 py-3 text-yellow-600">Premium</td>
                        <td class="px-4 py-3">1,234</td>
                        <td class="px-4 py-3 text-blue-600">Unleaded</td>
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
    </div> -->

</div>
@endsection