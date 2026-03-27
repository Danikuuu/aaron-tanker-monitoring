@extends('super_admin.layout.app')

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
                        <th class="px-4 py-3 text-left">Tanker No.</th>
                        <th class="px-4 py-3 text-left">Arrival Date</th>
                        <th class="px-4 py-3 text-left">Recorded By</th>
                        <th class="px-4 py-3 text-left">Fuels</th>
                        <th class="px-4 py-3 text-left w-24">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($arrivals as $arrival)
                    @php
                        $arrivalData = json_encode([
                            'tanker_number' => $arrival->tanker_number,
                            'arrival_date'  => $arrival->arrival_date->format('m/d/Y'),
                            'recorded_by'   => trim(($arrival->recordedBy->first_name ?? '') . ' ' . ($arrival->recordedBy->last_name ?? '')),
                            'fuels'         => $arrival->fuels->map(fn($f) => [
                                'fuel_type' => $f->fuel_type,
                                'liters'    => number_format($f->liters, 2),
                            ])->values(),
                        ]);
                    @endphp
                    <tr class="odd:bg-gray-100 even:bg-white border-b border-gray-200">
                        <td class="px-4 py-3">{{ $arrival->tanker_number }}</td>
                        <td class="px-4 py-3">{{ $arrival->arrival_date->format('m/d/Y') }}</td>
                        <td class="px-4 py-3">{{ $arrival->recordedBy->first_name ?? '—' }} {{ $arrival->recordedBy->last_name ?? '' }}</td>
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
                            <button
                                onclick="openArrivalModal({{ $arrivalData }})"
                                class="w-20 bg-primary cursor-pointer text-white px-4 py-2 rounded-lg hover:bg-[#ff4040] transition text-sm text-center">
                                View
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-400">No arrivals recorded yet.</td>
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
                        <th class="px-4 py-3 text-left">Tanker No.</th>
                        <th class="px-4 py-3 text-left">Driver</th>
                        <th class="px-4 py-3 text-left">Departure Date</th>
                        <th class="px-4 py-3 text-left">Recorded By</th>
                        <th class="px-4 py-3 text-left">Fuels</th>
                        <th class="px-4 py-3 text-left w-24">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($departures as $departure)
                    @php
                        $departureData = json_encode([
                            'tanker_number'  => $departure->tanker_number,
                            'driver'         => $departure->driver,
                            'departure_date' => $departure->departure_date->format('m/d/Y'),
                            'recorded_by'    => trim(($departure->recordedBy->first_name ?? '') . ' ' . ($departure->recordedBy->last_name ?? '')),
                            'fuels'          => $departure->fuels->map(fn($f) => [
                                'fuel_type'        => $f->fuel_type,
                                'liters'           => number_format($f->liters, 2),
                                'methanol_liters'  => $f->methanol_liters ?? 0,
                                'methanol_percent' => $f->methanol_percent ?? 0,
                            ])->values(),
                        ]);
                    @endphp
                    <tr class="odd:bg-gray-100 even:bg-white border-b border-gray-200">
                        <td class="px-4 py-3">{{ $departure->tanker_number }}</td>
                        <td class="px-4 py-3">{{ $departure->driver }}</td>
                        <td class="px-4 py-3">{{ $departure->departure_date->format('m/d/Y') }}</td>
                        <td class="px-4 py-3">{{ $departure->recordedBy->first_name ?? '—' }} {{ $departure->recordedBy->last_name ?? '' }}</td>
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
                            <button
                                onclick="openDepartureModal({{ $departureData }})"
                                class="w-20 bg-primary cursor-pointer text-white px-4 py-2 rounded-lg hover:bg-[#ff4040] transition text-sm text-center">
                                View
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-400">No departures recorded yet.</td>
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
                                {{ str_ireplace('Br Receipt', 'DR Receipt', str_replace('_', ' ', ucfirst($log->action))) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">{{ str_ireplace('BR Receipt', 'DR Receipt', $log->description) }}</td>
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

{{-- ===================== ARRIVAL MODAL ===================== --}}
<div id="arrivalModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeArrivalModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden animate-modal">
        <div class="bg-primary px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 rounded-lg p-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                </div>
                <h3 class="text-white font-bold text-lg">Fuel Arrival Details</h3>
            </div>
            <button onclick="closeArrivalModal()" class="text-white/80 cursor-pointer hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Tanker Number</p>
                    <p id="am-tanker" class="font-bold text-gray-800 text-sm">—</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Arrival Date</p>
                    <p id="am-date" class="font-bold text-gray-800 text-sm">—</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 col-span-2">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Recorded By</p>
                    <p id="am-recorded" class="font-bold text-gray-800 text-sm">—</p>
                </div>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">Fuel Breakdown</p>
                <div id="am-fuels" class="space-y-2"></div>
            </div>
        </div>
        <div class="px-6 pb-6">
            <button onclick="closeArrivalModal()" class="w-full cursor-pointer bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 rounded-xl transition text-sm">
                Close
            </button>
        </div>
    </div>
</div>

{{-- ===================== DEPARTURE MODAL ===================== --}}
<div id="departureModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeDepartureModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden animate-modal">
        <div class="bg-gray-800 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 rounded-lg p-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </div>
                <h3 class="text-white font-bold text-lg">Fuel Departure Details</h3>
            </div>
            <button onclick="closeDepartureModal()" class="text-white/80 cursor-pointer hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Tanker Number</p>
                    <p id="dm-tanker" class="font-bold text-gray-800 text-sm">—</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Driver</p>
                    <p id="dm-driver" class="font-bold text-gray-800 text-sm">—</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Departure Date</p>
                    <p id="dm-date" class="font-bold text-gray-800 text-sm">—</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Recorded By</p>
                    <p id="dm-recorded" class="font-bold text-gray-800 text-sm">—</p>
                </div>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">Fuel Breakdown</p>
                <div id="dm-fuels" class="space-y-2"></div>
            </div>
        </div>
        <div class="px-6 pb-6">
            <button onclick="closeDepartureModal()" class="w-full cursor-pointer bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 rounded-xl transition text-sm">
                Close
            </button>
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

    // ── Modal helpers ──────────────────────────────────────────────────────
    const fuelBadgeClass = (type) => ({
        diesel:   'bg-green-100 text-green-700',
        premium:  'bg-yellow-100 text-yellow-700',
        unleaded: 'bg-blue-100 text-blue-700',
        methanol: 'bg-purple-100 text-purple-700',
    })[type] ?? 'bg-gray-100 text-gray-700';

    const capitalize = s => s.charAt(0).toUpperCase() + s.slice(1);

    // ── Arrival Modal ──────────────────────────────────────────────────────
    function openArrivalModal(data) {
        document.getElementById('am-tanker').textContent   = data.tanker_number || '—';
        document.getElementById('am-date').textContent     = data.arrival_date  || '—';
        document.getElementById('am-recorded').textContent = data.recorded_by   || '—';

        const container = document.getElementById('am-fuels');
        container.innerHTML = '';

        (data.fuels || []).forEach(fuel => {
            container.insertAdjacentHTML('beforeend', `
                <div class="flex items-center justify-between bg-gray-50 rounded-xl px-4 py-3">
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full ${fuelBadgeClass(fuel.fuel_type)}">
                        ${capitalize(fuel.fuel_type)}
                    </span>
                    <span class="font-bold text-gray-800 text-sm">${fuel.liters} L</span>
                </div>`);
        });

        if (!data.fuels || !data.fuels.length) {
            container.innerHTML = '<p class="text-sm text-gray-400 text-center py-2">No fuel records.</p>';
        }

        document.getElementById('arrivalModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeArrivalModal() {
        document.getElementById('arrivalModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    // ── Departure Modal ────────────────────────────────────────────────────
    function openDepartureModal(data) {
        document.getElementById('dm-tanker').textContent   = data.tanker_number  || '—';
        document.getElementById('dm-driver').textContent   = data.driver         || '—';
        document.getElementById('dm-date').textContent     = data.departure_date || '—';
        document.getElementById('dm-recorded').textContent = data.recorded_by    || '—';

        const container = document.getElementById('dm-fuels');
        container.innerHTML = '';

        (data.fuels || []).forEach(fuel => {
            const extra = fuel.methanol_liters > 0
                ? `<span class="text-xs text-gray-400 ml-1">(${fuel.methanol_percent}% M)</span>`
                : '';
            container.insertAdjacentHTML('beforeend', `
                <div class="flex items-center justify-between bg-gray-50 rounded-xl px-4 py-3">
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full ${fuelBadgeClass(fuel.fuel_type)}">
                        ${capitalize(fuel.fuel_type)}
                    </span>
                    <div class="flex items-center gap-1">
                        <span class="font-bold text-gray-800 text-sm">${fuel.liters} L</span>
                        ${extra}
                    </div>
                </div>`);
        });

        if (!data.fuels || !data.fuels.length) {
            container.innerHTML = '<p class="text-sm text-gray-400 text-center py-2">No fuel records.</p>';
        }

        document.getElementById('departureModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDepartureModal() {
        document.getElementById('departureModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') { closeArrivalModal(); closeDepartureModal(); }
    });
</script>

<style>
    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.95) translateY(10px); }
        to   { opacity: 1; transform: scale(1)    translateY(0); }
    }
    .animate-modal { animation: modalIn 0.2s ease-out forwards; }
</style>
@endsection