@extends('super_admin.layout.app')

@section('title', 'Transaction History')

@section('content')
<div class="bg-white rounded-lg shadow p-6">

    {{-- Filters --}}
    <form method="GET" action="{{ route('super_admin.transaction-history') }}"
          class="grid grid-cols-4 gap-4 mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">

        {{-- Search --}}
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Search</label>
            <input type="text" name="search" value="{{ $search }}"
                   placeholder="Tanker no., driver..."
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
        </div>

        {{-- Type --}}
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Type</label>
            <select name="type"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                <option value="all"       {{ $type === 'all'       ? 'selected' : '' }}>All</option>
                <option value="arrival"   {{ $type === 'arrival'   ? 'selected' : '' }}>Arrivals Only</option>
                <option value="departure" {{ $type === 'departure' ? 'selected' : '' }}>Departures Only</option>
            </select>
        </div>

        {{-- Date From --}}
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Date From</label>
            <input type="date" name="date_from" value="{{ $dateFrom }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
        </div>

        {{-- Date To --}}
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Date To</label>
            <input type="date" name="date_to" value="{{ $dateTo }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
        </div>

        <div class="col-span-4 flex gap-3 justify-end">
            <a href="{{ route('super_admin.transaction-history') }}"
               class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-100 transition">
                Clear
            </a>
            <button type="submit"
                    class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-[#ff4040] transition text-sm">
                Filter
            </button>
        </div>
    </form>

    <div class="mb-6 mt-6 flex items-center justify-between">
        <h2 class="text-xl font-semibold">Transaction History</h2>
        <div class="flex items-center gap-2">
            <a href="{{ route('super_admin.transaction-history.export', request()->query()) }}"
               class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-[#ff4040] transition text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export CSV
            </a>
            <a href="{{ route('super_admin.transaction-history.export.pdf', request()->query()) }}"
               class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-3.866 3.582-7 8-7v14c-4.418 0-8-3.134-8-7z"/>
                </svg>
                Export PDF
            </a>
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-primary text-white">
                    <th class="px-4 py-3 text-left">Type</th>
                    <th class="px-4 py-3 text-left">Tanker Number</th>
                    <th class="px-4 py-3 text-left">Driver</th>
                    <th class="px-4 py-3 text-left">Date</th>
                    <th class="px-4 py-3 text-left">Recorded By</th>
                    <th class="px-4 py-3 text-left">Fuels</th>
                    <th class="px-4 py-3 text-left rounded-tr-lg">Action</th>
                </tr>
            </thead>
            <tbody class="bg-gray-50">
                @forelse($transactions as $transaction)
                <tr class="border-b border-gray-200 hover:bg-gray-100 transition">

                    {{-- Type badge --}}
                    <td class="px-4 py-3">
                        @if($transaction->type === 'arrival')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                ↓ Arrival
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">
                                ↑ Departure
                            </span>
                        @endif
                    </td>

                    <td class="px-4 py-3 font-medium">{{ $transaction->tanker_number }}</td>

                    <td class="px-4 py-3 text-sm text-gray-600">
                        {{ $transaction->driver ?? '—' }}
                    </td>

                    <td class="px-4 py-3 text-sm">
                        {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('m/d/Y') }}
                    </td>

                    <td class="px-4 py-3 text-sm text-gray-600">
                        {{ $transaction->recorded_by }}
                    </td>

                    {{-- Fuel badges --}}
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-1">
                            @foreach($transaction->fuels as $fuel)
                                <span class="inline-flex items-center gap-1 text-xs px-2 py-1 rounded-full font-semibold
                                    {{ $fuel->fuel_type === 'diesel'   ? 'bg-green-100 text-green-700'   :
                                      ($fuel->fuel_type === 'premium'  ? 'bg-yellow-100 text-yellow-700' :
                                      ($fuel->fuel_type === 'unleaded' ? 'bg-blue-100 text-blue-700'     :
                                                                         'bg-purple-100 text-purple-700')) }}">
                                    {{ ucfirst($fuel->fuel_type) }}
                                    <span class="font-normal">{{ number_format($fuel->liters, 2) }} L</span>
                                    @if(isset($fuel->methanol_liters) && $fuel->methanol_liters > 0)
                                        <span class="text-gray-400">({{ $fuel->methanol_percent }}% M)</span>
                                    @endif
                                </span>
                            @endforeach
                        </div>
                    </td>

                    <td class="px-4 py-3">
                        <button
                            data-id="{{ $transaction->id }}"
                            data-type="{{ $transaction->type }}"
                            class="view-transaction bg-primary text-white px-4 py-2 rounded-lg hover:bg-[#ff4040] transition text-sm">
                            View
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-400">
                        No transactions found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($transactions->hasPages())
    <div class="flex items-center justify-center gap-4 mt-6">
        @if($transactions->onFirstPage())
            <span class="bg-[#FFB8B8] text-white px-4 py-2 rounded-lg flex items-center gap-2 cursor-not-allowed opacity-60">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Prev
            </span>
        @else
            <a href="{{ $transactions->previousPageUrl() }}"
               class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-[#ff4040] transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Prev
            </a>
        @endif

        <span class="text-gray-600">
            Page {{ $transactions->currentPage() }} of {{ $transactions->lastPage() }}
        </span>

        @if($transactions->hasMorePages())
            <a href="{{ $transactions->nextPageUrl() }}"
               class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-[#ff4040] transition flex items-center gap-2">
                Next
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @else
            <span class="bg-[#FFB8B8] text-white px-4 py-2 rounded-lg flex items-center gap-2 cursor-not-allowed opacity-60">
                Next
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        @endif
    </div>
    @endif

</div>

{{-- ===================== TRANSACTION MODAL ===================== --}}
<div id="transactionModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeTransactionModal()"></div>

    {{-- Modal Card --}}
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden animate-modal">

        {{-- Header (color swaps dynamically via JS) --}}
        <div id="tm-header" class="px-6 py-4 flex items-center justify-between bg-primary">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 rounded-lg p-2">
                    <svg id="tm-icon" class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                </div>
                <h3 id="tm-title" class="text-white font-bold text-lg">Transaction Details</h3>
            </div>
            <button onclick="closeTransactionModal()" class="text-white/80 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Loading state --}}
        <div id="tm-loading" class="p-10 flex flex-col items-center justify-center gap-3">
            <svg class="w-8 h-8 text-primary animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
            </svg>
            <p class="text-sm text-gray-400">Loading transaction...</p>
        </div>

        {{-- Body --}}
        <div id="tm-body" class="p-6 space-y-4 hidden">
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Tanker Number</p>
                    <p id="tm-tanker" class="font-bold text-gray-800 text-sm">—</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Driver</p>
                    <p id="tm-driver" class="font-bold text-gray-800 text-sm">—</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Date</p>
                    <p id="tm-date" class="font-bold text-gray-800 text-sm">—</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-1">Recorded By</p>
                    <p id="tm-recorded" class="font-bold text-gray-800 text-sm">—</p>
                </div>
            </div>

            <div>
                <p class="text-xs font-medium text-gray-400 uppercase tracking-wide mb-2">Fuel Breakdown</p>
                <div id="tm-fuels" class="space-y-2"></div>
            </div>
        </div>

        {{-- Error state --}}
        <div id="tm-error" class="p-10 flex flex-col items-center justify-center gap-3 hidden">
            <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm text-gray-400">Failed to load transaction details.</p>
        </div>

        {{-- Footer --}}
        <div class="px-6 pb-6">
            <button onclick="closeTransactionModal()"
                    class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 rounded-xl transition text-sm">
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
@endsection

{{-- ===================== SCRIPTS ===================== --}}
@push('scripts')
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

    const capitalize = (s) => s ? s.charAt(0).toUpperCase() + s.slice(1) : '—';

    const arrivalIcon = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>`;
    const departureIcon = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>`;

    function openTransactionModal(id, type) {
        const modal   = document.getElementById('transactionModal');
        const loading = document.getElementById('tm-loading');
        const body    = document.getElementById('tm-body');
        const error   = document.getElementById('tm-error');
        const header  = document.getElementById('tm-header');
        const icon    = document.getElementById('tm-icon');
        const title   = document.getElementById('tm-title');

        // Reset states
        loading.classList.remove('hidden');
        body.classList.add('hidden');
        error.classList.add('hidden');

        // Style header by type
        if (type === 'arrival') {
            header.className = header.className.replace(/bg-\S+/, '') + ' bg-primary';
            icon.innerHTML = arrivalIcon;
            title.textContent = 'Fuel Arrival Details';
        } else {
            header.className = header.className.replace(/bg-\S+/, '') + ' bg-gray-800';
            icon.innerHTML = departureIcon;
            title.textContent = 'Fuel Departure Details';
        }

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        // Fetch data — base URL rendered by Blade, path built in JS
        const baseUrl = '{{ url("/") }}';
        fetch(`${baseUrl}/super_admin/transaction-history/${type}/${id}`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(r => r.ok ? r.json() : Promise.reject(r))
        .then(data => {
            document.getElementById('tm-tanker').textContent   = data.tanker_number || '—';
            document.getElementById('tm-driver').textContent   = data.driver || '—';
            document.getElementById('tm-recorded').textContent = data.recorded_by || '—';
            document.getElementById('tm-date').textContent     = data.transaction_date
                ? new Date(data.transaction_date).toLocaleDateString('en-US', { month: '2-digit', day: '2-digit', year: 'numeric' })
                : '—';

            const container = document.getElementById('tm-fuels');
            container.innerHTML = '';

            if (data.fuels && data.fuels.length) {
                data.fuels.forEach(fuel => {
                    const row = document.createElement('div');
                    row.className = 'flex items-center justify-between bg-gray-50 rounded-xl px-4 py-3';

                    let methanolBadge = '';
                    if (fuel.methanol_liters > 0) {
                        methanolBadge = `<span class="text-xs text-gray-400 ml-1">(${fuel.methanol_percent}% M — ${Number(fuel.methanol_liters).toFixed(2)} L)</span>`;
                    }

                    row.innerHTML = `
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full ${fuelBadgeClass(fuel.fuel_type)}">
                            ${capitalize(fuel.fuel_type)}
                        </span>
                        <div class="flex items-center gap-1">
                            <span class="font-bold text-gray-800 text-sm">${Number(fuel.liters).toFixed(2)} L</span>
                            ${methanolBadge}
                        </div>
                    `;
                    container.appendChild(row);
                });
            } else {
                container.innerHTML = '<p class="text-sm text-gray-400 text-center py-2">No fuel records.</p>';
            }

            loading.classList.add('hidden');
            body.classList.remove('hidden');
        })
        .catch(() => {
            loading.classList.add('hidden');
            error.classList.remove('hidden');
        });
    }

    function closeTransactionModal() {
        document.getElementById('transactionModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    // Delegate click on View buttons
    document.addEventListener('click', function (e) {
        if (!e.target.classList.contains('view-transaction')) return;
        const id   = e.target.getAttribute('data-id');
        const type = e.target.getAttribute('data-type');
        openTransactionModal(id, type);
    });

    // Close on Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeTransactionModal();
    });
</script>
@endpush