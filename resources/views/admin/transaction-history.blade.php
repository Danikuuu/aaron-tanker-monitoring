@extends('admin.layout.app')

@section('title', 'Transaction History')

@section('content')
<div class="bg-white rounded-lg shadow p-6">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold">Transaction History</h2>
        <a href="{{ route('admin.transaction-history.export', request()->query()) }}"
           class="bg-[#FF5757] text-white px-6 py-2 rounded-lg hover:bg-[#ff4040] transition text-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Export CSV
        </a>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.transaction-history') }}"
          class="grid grid-cols-4 gap-4 mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">

        {{-- Search --}}
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Search</label>
            <input type="text" name="search" value="{{ $search }}"
                   placeholder="Tanker no., driver..."
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
        </div>

        {{-- Type --}}
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Type</label>
            <select name="type"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
                <option value="all"       {{ $type === 'all'       ? 'selected' : '' }}>All</option>
                <option value="arrival"   {{ $type === 'arrival'   ? 'selected' : '' }}>Arrivals Only</option>
                <option value="departure" {{ $type === 'departure' ? 'selected' : '' }}>Departures Only</option>
            </select>
        </div>

        {{-- Date From --}}
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Date From</label>
            <input type="date" name="date_from" value="{{ $dateFrom }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
        </div>

        {{-- Date To --}}
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Date To</label>
            <input type="date" name="date_to" value="{{ $dateTo }}"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
        </div>

        <div class="col-span-4 flex gap-3 justify-end">
            <a href="{{ route('admin.transaction-history') }}"
               class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-100 transition">
                Clear
            </a>
            <button type="submit"
                    class="bg-[#FF5757] text-white px-6 py-2 rounded-lg hover:bg-[#ff4040] transition text-sm">
                Filter
            </button>
        </div>
    </form>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-[#FF5757] text-white">
                    <!-- <th class="px-4 py-3 text-left rounded-tl-lg">ID</th> -->
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
                    <!-- <td class="px-4 py-3 text-gray-500 text-sm">{{ $transaction->id }}</td> -->

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
                        <button class="bg-[#FF5757] text-white px-4 py-2 rounded-lg hover:bg-[#ff4040] transition text-sm">
                            View
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-gray-400">
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
               class="bg-[#FF5757] text-white px-4 py-2 rounded-lg hover:bg-[#ff4040] transition flex items-center gap-2">
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
               class="bg-[#FF5757] text-white px-4 py-2 rounded-lg hover:bg-[#ff4040] transition flex items-center gap-2">
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
@endsection