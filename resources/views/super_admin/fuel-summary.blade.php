@extends('super_admin.layout.app')

@section('title', 'Fuel Summary')

@section('content')
<div class="space-y-6">

    {{-- Fuel Stocks --}}
    <div class="grid grid-cols-4 gap-6">
        @foreach(['diesel' => 'Diesel', 'premium' => 'Premium', 'unleaded' => 'Unleaded', 'methanol' => 'Methanol'] as $type => $label)
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="font-bold text-lg mb-2">{{ $label }}</h3>
            <p class="text-primary text-3xl font-bold">
                {{ number_format($stocks[$type]->liters ?? 0, 2) }} L
            </p>
        </div>
        @endforeach
    </div>

    {{-- Overall Total --}}
    <div class="bg-white rounded-lg shadow p-6 flex items-center justify-between">
        <h3 class="font-bold text-lg">Overall Total Fuel</h3>
        <p class="text-primary text-3xl font-bold">
            {{ number_format($stocks->sum('liters'), 2) }} L
        </p>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('super_admin.fuel-summary') }}" class="grid grid-cols-4 gap-4 mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Search</label>
            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Tanker no., driver, recorded by"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Date From</label>
            <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Date To</label>
            <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
        </div>
        <div class="col-span-4 flex gap-3 justify-end">
            <a href="{{ route('super_admin.fuel-summary') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-100 transition">Clear</a>
            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-[#ff4040] transition text-sm">Filter</button>
        </div>
    </form>

    {{-- Fuel Arrival Summary --}}
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Fuel Arrival Summary</h2>
            <div class="flex gap-2">
                <a href="{{ route('super_admin.fuel-summary.export.arrivals', request()->query()) }}"
                    class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-[#ff4040] transition text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export CSV
                </a>
                <a href="{{ route('super_admin.fuel-summary.export.arrivals.pdf', request()->query()) }}"
                   class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-3.866 3.582-7 8-7v14c-4.418 0-8-3.134-8-7z"/>
                    </svg>
                    Export PDF
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-primary text-white">
                        <th class="px-4 py-3 text-left">Tanker Number</th>
                        <th class="px-4 py-3 text-left">Arrival Date</th>
                        <th class="px-4 py-3 text-left">Recorded By</th>
                        <th class="px-4 py-3 text-left">Fuels</th>
                        <th class="px-4 py-3 text-left rounded-tr-lg">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-50">
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
                    <tr class="border-b border-gray-200 hover:bg-gray-100 transition">
                        <td class="px-4 py-3 font-medium">{{ $arrival->tanker_number }}</td>
                        <td class="px-4 py-3">{{ $arrival->arrival_date->format('m/d/Y') }}</td>
                        <td class="px-4 py-3 text-sm">
                            {{ $arrival->recordedBy->first_name ?? '—' }}
                            {{ $arrival->recordedBy->last_name ?? '' }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1">
                                @foreach($arrival->fuels as $fuel)
                                    <span class="inline-flex items-center gap-1 text-xs px-2 py-1 rounded-full font-semibold
                                        {{ $fuel->fuel_type === 'diesel'   ? 'bg-green-100 text-green-700'  :
                                          ($fuel->fuel_type === 'premium'  ? 'bg-yellow-100 text-yellow-700' :
                                          ($fuel->fuel_type === 'unleaded' ? 'bg-blue-100 text-blue-700'    :
                                                                             'bg-purple-100 text-purple-700')) }}">
                                        {{ ucfirst($fuel->fuel_type) }}
                                        <span class="font-normal">{{ number_format($fuel->liters, 2) }} L</span>
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <button
                                onclick="openArrivalModal({{ $arrivalData }})"
                                class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-[#ff4040] transition text-sm">
                                View
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-400">No arrivals recorded yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Arrival Pagination --}}
        @if($arrivals->hasPages())
        <div class="flex items-center justify-center gap-4 mt-6">
            @if($arrivals->onFirstPage())
                <span class="bg-[#FFB8B8] text-white px-4 py-2 rounded-lg flex items-center gap-2 cursor-not-allowed opacity-60">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Prev
                </span>
            @else
                <a href="{{ $arrivals->previousPageUrl() }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-[#ff4040] transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Prev
                </a>
            @endif
            <span class="text-gray-600">Page {{ $arrivals->currentPage() }} of {{ $arrivals->lastPage() }}</span>
            @if($arrivals->hasMorePages())
                <a href="{{ $arrivals->nextPageUrl() }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-[#ff4040] transition flex items-center gap-2">
                    Next
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            @else
                <span class="bg-[#FFB8B8] text-white px-4 py-2 rounded-lg flex items-center gap-2 cursor-not-allowed opacity-60">
                    Next
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </span>
            @endif
        </div>
        @endif
    </div>

    {{-- Fuel Departure Summary --}}
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Fuel Departure Summary</h2>
            <div class="flex gap-2">
                <a href="{{ route('super_admin.fuel-summary.export.departures', request()->query()) }}"
                    class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-[#ff4040] transition text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export CSV
                </a>
                <a href="{{ route('super_admin.fuel-summary.export.departures.pdf', request()->query()) }}"
                   class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-3.866 3.582-7 8-7v14c-4.418 0-8-3.134-8-7z"/>
                    </svg>
                    Export PDF
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-primary text-white">
                        <th class="px-4 py-3 text-left">Tanker Number</th>
                        <th class="px-4 py-3 text-left">Driver</th>
                        <th class="px-4 py-3 text-left">Departure Date</th>
                        <th class="px-4 py-3 text-left">Recorded By</th>
                        <th class="px-4 py-3 text-left">Fuels</th>
                        <th class="px-4 py-3 text-left rounded-tr-lg">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-50">
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
                    <tr class="border-b border-gray-200 hover:bg-gray-100 transition">
                        <td class="px-4 py-3 font-medium">{{ $departure->tanker_number }}</td>
                        <td class="px-4 py-3">{{ $departure->driver }}</td>
                        <td class="px-4 py-3">{{ $departure->departure_date->format('m/d/Y') }}</td>
                        <td class="px-4 py-3 text-sm">
                            {{ $departure->recordedBy->first_name ?? '—' }}
                            {{ $departure->recordedBy->last_name ?? '' }}
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1">
                                @foreach($departure->fuels as $fuel)
                                    <span class="inline-flex items-center gap-1 text-xs px-2 py-1 rounded-full font-semibold
                                        {{ $fuel->fuel_type === 'diesel'   ? 'bg-green-100 text-green-700'  :
                                          ($fuel->fuel_type === 'premium'  ? 'bg-yellow-100 text-yellow-700' :
                                          ($fuel->fuel_type === 'unleaded' ? 'bg-blue-100 text-blue-700'    :
                                                                             'bg-purple-100 text-purple-700')) }}">
                                        {{ ucfirst($fuel->fuel_type) }}
                                        <span class="font-normal">{{ number_format($fuel->liters, 2) }} L</span>
                                        @if($fuel->methanol_liters > 0)
                                            <span class="text-gray-400">({{ $fuel->methanol_percent }}% M)</span>
                                        @endif
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <button
                                onclick="openDepartureModal({{ $departureData }})"
                                class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-[#ff4040] transition text-sm">
                                View
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-400">No departures recorded yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Departure Pagination --}}
        @if($departures->hasPages())
        <div class="flex items-center justify-center gap-4 mt-6">
            @if($departures->onFirstPage())
                <span class="bg-[#FFB8B8] text-white px-4 py-2 rounded-lg flex items-center gap-2 cursor-not-allowed opacity-60">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Prev
                </span>
            @else
                <a href="{{ $departures->previousPageUrl() }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-[#ff4040] transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Prev
                </a>
            @endif
            <span class="text-gray-600">Page {{ $departures->currentPage() }} of {{ $departures->lastPage() }}</span>
            @if($departures->hasMorePages())
                <a href="{{ $departures->nextPageUrl() }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-[#ff4040] transition flex items-center gap-2">
                    Next
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            @else
                <span class="bg-[#FFB8B8] text-white px-4 py-2 rounded-lg flex items-center gap-2 cursor-not-allowed opacity-60">
                    Next
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </span>
            @endif
        </div>
        @endif
    </div>

</div>

{{-- ===================== ARRIVAL MODAL ===================== --}}
<div id="arrivalModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeArrivalModal()"></div>

    {{-- Modal Card --}}
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden animate-modal">
        {{-- Header --}}
        <div class="bg-primary px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 rounded-lg p-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                </div>
                <h3 class="text-white font-bold text-lg">Fuel Arrival Details</h3>
            </div>
            <button onclick="closeArrivalModal()" class="text-white/80 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="p-6 space-y-4">
            {{-- Info Grid --}}
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

            {{-- Fuels --}}
            <div>
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">Fuel Breakdown</p>
                <div id="am-fuels" class="space-y-2">
                    {{-- populated by JS --}}
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="px-6 pb-6">
            <button onclick="closeArrivalModal()" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 rounded-xl transition text-sm">
                Close
            </button>
        </div>
    </div>
</div>

{{-- ===================== DEPARTURE MODAL ===================== --}}
<div id="departureModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeDepartureModal()"></div>

    {{-- Modal Card --}}
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden animate-modal">
        {{-- Header --}}
        <div class="bg-gray-800 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 rounded-lg p-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </div>
                <h3 class="text-white font-bold text-lg">Fuel Departure Details</h3>
            </div>
            <button onclick="closeDepartureModal()" class="text-white/80 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="p-6 space-y-4">
            {{-- Info Grid --}}
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

            {{-- Fuels --}}
            <div>
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">Fuel Breakdown</p>
                <div id="dm-fuels" class="space-y-2">
                    {{-- populated by JS --}}
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="px-6 pb-6">
            <button onclick="closeDepartureModal()" class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 rounded-xl transition text-sm">
                Close
            </button>
        </div>
    </div>
</div>

{{-- ===================== STYLES ===================== --}}
<style>
    @keyframes modalIn {
        from { opacity: 0; transform: scale(0.95) translateY(10px); }
        to   { opacity: 1; transform: scale(1)    translateY(0); }
    }
    .animate-modal {
        animation: modalIn 0.2s ease-out forwards;
    }
</style>

{{-- ===================== SCRIPTS ===================== --}}
<script>
    const fuelBadgeClass = (type) => {
        const map = {
            diesel:   'bg-green-100 text-green-700',
            premium:  'bg-yellow-100 text-yellow-700',
            unleaded: 'bg-blue-100 text-blue-700',
            methanol: 'bg-purple-100 text-purple-700',
        };
        return map[type] ?? 'bg-gray-100 text-gray-700';
    };

    const capitalize = (s) => s.charAt(0).toUpperCase() + s.slice(1);

    /* ---------- ARRIVAL MODAL ---------- */
    function openArrivalModal(data) {
        document.getElementById('am-tanker').textContent   = data.tanker_number  || '—';
        document.getElementById('am-date').textContent     = data.arrival_date   || '—';
        document.getElementById('am-recorded').textContent = data.recorded_by || '—';

        const container = document.getElementById('am-fuels');
        container.innerHTML = '';

        if (data.fuels && data.fuels.length) {
            data.fuels.forEach(fuel => {
                const row = document.createElement('div');
                row.className = 'flex items-center justify-between bg-gray-50 rounded-xl px-4 py-3';
                row.innerHTML = `
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full ${fuelBadgeClass(fuel.fuel_type)}">
                        ${capitalize(fuel.fuel_type)}
                    </span>
                    <span class="font-bold text-gray-800 text-sm">${fuel.liters} L</span>
                `;
                container.appendChild(row);
            });
        } else {
            container.innerHTML = '<p class="text-sm text-gray-400 text-center py-2">No fuel records.</p>';
        }

        document.getElementById('arrivalModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeArrivalModal() {
        document.getElementById('arrivalModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    /* ---------- DEPARTURE MODAL ---------- */
    function openDepartureModal(data) {
        document.getElementById('dm-tanker').textContent   = data.tanker_number  || '—';
        document.getElementById('dm-driver').textContent   = data.driver         || '—';
        document.getElementById('dm-date').textContent     = data.departure_date || '—';
        document.getElementById('dm-recorded').textContent = data.recorded_by    || '—';

        const container = document.getElementById('dm-fuels');
        container.innerHTML = '';

        if (data.fuels && data.fuels.length) {
            data.fuels.forEach(fuel => {
                const row = document.createElement('div');
                row.className = 'flex items-center justify-between bg-gray-50 rounded-xl px-4 py-3';

                let extra = '';
                if (fuel.methanol_liters > 0) {
                    extra = `<span class="text-xs text-gray-400 ml-1">(${fuel.methanol_percent}% M)</span>`;
                }

                row.innerHTML = `
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full ${fuelBadgeClass(fuel.fuel_type)}">
                        ${capitalize(fuel.fuel_type)}
                    </span>
                    <div class="flex items-center gap-1">
                        <span class="font-bold text-gray-800 text-sm">${fuel.liters} L</span>
                        ${extra}
                    </div>
                `;
                container.appendChild(row);
            });
        } else {
            container.innerHTML = '<p class="text-sm text-gray-400 text-center py-2">No fuel records.</p>';
        }

        document.getElementById('departureModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDepartureModal() {
        document.getElementById('departureModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    /* Close on Escape key */
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeArrivalModal();
            closeDepartureModal();
        }
    });
</script>
@endsection