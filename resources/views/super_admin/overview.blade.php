@extends('admin.layout.app')

@section('title', 'Dashboard Overview')

@section('content')
<div class="space-y-6">

    {{-- Fuel Stocks --}}
    <div class="font-bold text-2xl">Fuel Stocks</div>
    <div class="grid grid-cols-4 gap-6">
        @foreach(['diesel' => 'Diesel', 'premium' => 'Premium', 'unleaded' => 'Unleaded', 'methanol' => 'Methanol'] as $type => $label)
        <div class="bg-white rounded-lg border border-gray-200 shadow-[0_0_10px_rgba(0,0,0,0.30)] p-6">
            <h3 class="font-bold text-lg mb-2">{{ $label }}</h3>
            <p class="text-primary text-3xl font-bold">
                {{ number_format($stocks[$type]->liters ?? 0, 2) }} L
            </p>
        </div>
        @endforeach
    </div>

    {{-- Second stock row: overall total --}}
    <div class="grid grid-cols-4 gap-6">
        <div class="col-span-4 bg-white rounded-lg border border-gray-200 shadow-[0_0_10px_rgba(0,0,0,0.30)] p-6 flex items-center justify-between">
            <h3 class="font-bold text-lg">Overall Total Fuel</h3>
            <p class="text-primary text-3xl font-bold">
                {{ number_format($stocks->sum('liters'), 2) }} L
            </p>
        </div>
    </div>

    {{-- Chart + Delivery Summary --}}
    <div class="flex justify-between items-start gap-8">
        <div class="w-[70%]">
            <h2 class="text-xl font-semibold mb-4">Monthly Fuel Delivery Summary</h2>
            <div class="bg-white rounded-lg border border-gray-200 p-4" style="height: 280px;">
                <canvas id="fuelChart"></canvas>
            </div>
        </div>

        <div class="w-[30%]">
            <h3 class="text-xl font-semibold mb-4">Delivery Summary</h3>
            <div class="bg-gray-100 rounded-xl p-2 overflow-auto" style="height: 280px;">
                <table class="w-full text-sm border-collapse rounded-xl overflow-hidden shadow-md">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="px-3 py-2 text-left">Fuel</th>
                            <th class="px-3 py-2 text-left">Total Delivered</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(['diesel' => 'Diesel', 'premium' => 'Premium', 'unleaded' => 'Unleaded', 'methanol' => 'Methanol'] as $type => $label)
                        <tr class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} border-b border-gray-200">
                            <td class="px-3 py-2">{{ $label }}</td>
                            <td class="px-3 py-2">{{ number_format($deliverySummary[$type] ?? 0, 2) }} L</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Fuel Arrival Summary --}}
    <div class="bg-white rounded-xl overflow-hidden">
        <h2 class="text-xl font-semibold mb-4">Fuel Arrival Summary</h2>
        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-primary text-white">
                        <!-- <th class="px-4 py-3 text-left">ID</th> -->
                        <th class="px-4 py-3 text-left">Tanker No.</th>
                        <th class="px-4 py-3 text-left">Arrival Date</th>
                        <th class="px-4 py-3 text-left">Recorded By</th>
                        <th class="px-4 py-3 text-left">Fuels</th>
                        <th class="px-4 py-3 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($arrivals as $arrival)
                    <tr class="odd:bg-gray-100 even:bg-white border-b border-gray-200">
                        <!-- <td class="px-4 py-3">{{ $arrival->id }}</td> -->
                        <td class="px-4 py-3">{{ $arrival->tanker_number }}</td>
                        <td class="px-4 py-3">{{ $arrival->arrival_date->format('m/d/Y') }}</td>
                        <td class="px-4 py-3">{{ $arrival->recordedBy->first_name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @foreach($arrival->fuels as $fuel)
                                <span class="inline-block text-xs px-2 py-0.5 rounded-full mr-1
                                    {{ $fuel->fuel_type === 'diesel' ? 'bg-green-100 text-green-700' :
                                       ($fuel->fuel_type === 'premium' ? 'bg-yellow-100 text-yellow-700' :
                                       ($fuel->fuel_type === 'unleaded' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700')) }}">
                                    {{ ucfirst($fuel->fuel_type) }}: {{ number_format($fuel->liters, 2) }}L
                                </span>
                            @endforeach
                        </td>
                        <td class="px-4 py-3">
                            <button class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-[#ff4040] transition text-sm">
                                View
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-400">No arrivals recorded yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Fuel Departure Summary --}}
    <div class="bg-white rounded-xl overflow-hidden">
        <h2 class="text-xl font-semibold mb-4">Fuel Departure Summary</h2>
        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-primary text-white">
                        <!-- <th class="px-4 py-3 text-left">ID</th> -->
                        <th class="px-4 py-3 text-left">Tanker No.</th>
                        <th class="px-4 py-3 text-left">Driver</th>
                        <th class="px-4 py-3 text-left">Departure Date</th>
                        <th class="px-4 py-3 text-left">Recorded By</th>
                        <th class="px-4 py-3 text-left">Fuels</th>
                        <th class="px-4 py-3 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departures as $departure)
                    <tr class="odd:bg-gray-100 even:bg-white border-b border-gray-200">
                        <!-- <td class="px-4 py-3">{{ $departure->id }}</td> -->
                        <td class="px-4 py-3">{{ $departure->tanker_number }}</td>
                        <td class="px-4 py-3">{{ $departure->driver }}</td>
                        <td class="px-4 py-3">{{ $departure->departure_date->format('m/d/Y') }}</td>
                        <td class="px-4 py-3">{{ $departure->recordedBy->first_name ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @foreach($departure->fuels as $fuel)
                                <span class="inline-block text-xs px-2 py-0.5 rounded-full mr-1
                                    {{ $fuel->fuel_type === 'diesel' ? 'bg-green-100 text-green-700' :
                                       ($fuel->fuel_type === 'premium' ? 'bg-yellow-100 text-yellow-700' :
                                       ($fuel->fuel_type === 'unleaded' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700')) }}">
                                    {{ ucfirst($fuel->fuel_type) }}: {{ number_format($fuel->liters, 2) }}L
                                    @if($fuel->methanol_liters > 0)
                                        <span class="text-gray-400">({{ $fuel->methanol_percent }}% M)</span>
                                    @endif
                                </span>
                            @endforeach
                        </td>
                        <td class="px-4 py-3">
                            <button class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-[#ff4040] transition text-sm">
                                View
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-400">No departures recorded yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Audit Logs --}}
    <div class="bg-white rounded-xl overflow-hidden">
        <h2 class="text-xl font-semibold mb-4">Audit Logs</h2>
        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-primary text-white">
                        <th class="px-4 py-3 text-left">Time</th>
                        <th class="px-4 py-3 text-left">User</th>
                        <th class="px-4 py-3 text-left">Action</th>
                        <th class="px-4 py-3 text-left">Description</th>
                        <th class="px-4 py-3 text-left">IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($auditLogs as $log)
                    <tr class="odd:bg-gray-100 even:bg-white border-b border-gray-200">
                        <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">
                            {{ $log->created_at->format('m/d/Y H:i') }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $log->user ? $log->user->first_name . ' ' . $log->user->last_name : 'System' }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold
                                @if(str_contains($log->action, 'approved')) bg-green-100 text-green-700
                                @elseif(str_contains($log->action, 'blocked') || str_contains($log->action, 'deleted')) bg-red-100 text-red-700
                                @elseif(str_contains($log->action, 'arrival')) bg-blue-100 text-blue-700
                                @elseif(str_contains($log->action, 'departure')) bg-yellow-100 text-yellow-700
                                @else bg-gray-100 text-gray-700
                                @endif">
                                {{ str_replace('_', ' ', ucfirst($log->action)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">{{ $log->description }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $log->ip_address ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-400">No audit logs yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Chart.js Bar Chart --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const chartData = @json($chartData);
    const months    = Object.keys(chartData);
    const fuelTypes = ['diesel', 'premium', 'unleaded', 'methanol'];
    const colors    = {
        diesel:   '#22c55e',
        premium:  '#eab308',
        unleaded: '#3b82f6',
        methanol: '#a855f7',
    };

    const datasets = fuelTypes.map(fuel => ({
        label:           fuel.charAt(0).toUpperCase() + fuel.slice(1),
        data:            months.map(month => {
            const row = chartData[month]?.find(r => r.fuel_type === fuel);
            return row ? parseFloat(row.total) : 0;
        }),
        backgroundColor: colors[fuel],
        borderRadius:    4,
    }));

    new Chart(document.getElementById('fuelChart'), {
        type: 'bar',
        data: { labels: months, datasets },
        options: {
            responsive:          true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: ctx => `${ctx.dataset.label}: ${ctx.parsed.y.toLocaleString()} L`
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: val => val.toLocaleString() + ' L' }
                }
            }
        }
    });
</script>
@endsection