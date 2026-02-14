<!-- resources/views/admin/analytics.blade.php -->
@extends('admin.layout.app')

@section('title', 'Analytics')

@section('content')
<div class="space-y-8">
    <!-- Monthly Fuel Tanker Arrival -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-6">Monthly fuel tanker arrival</h2>
        <div class="bg-gray-50 rounded-lg p-6 h-96">
            <!-- Chart placeholder -->
            <canvas id="arrivalChart"></canvas>
        </div>
        <div class="flex justify-end gap-3 mt-4">
            <button class="bg-[#FF5757] text-white px-6 py-2 rounded-lg hover:bg-[#ff4040] transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                Filter
            </button>
            <button class="bg-[#FF5757] text-white px-6 py-2 rounded-lg hover:bg-[#ff4040] transition">
                Export
            </button>
        </div>
    </div>

    <!-- Monthly Fuel Tanker Departure -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-6">Monthly fuel tanker departure</h2>
        <div class="bg-gray-50 rounded-lg p-6 h-96">
            <!-- Chart placeholder -->
            <canvas id="departureChart"></canvas>
        </div>
        <div class="flex justify-end gap-3 mt-4">
            <button class="bg-[#FF5757] text-white px-6 py-2 rounded-lg hover:bg-[#ff4040] transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                Filter
            </button>
            <button class="bg-[#FF5757] text-white px-6 py-2 rounded-lg hover:bg-[#ff4040] transition">
                Export
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Add your Chart.js implementation here
</script>
@endpush
@endsection