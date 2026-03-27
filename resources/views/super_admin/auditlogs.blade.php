@extends('super_admin.layout.app')

@section('title', 'Audit Logs')

@section('content')
<div class="bg-white rounded-lg shadow p-6">

    {{-- ── Filters ── --}}
    <form method="GET" action="{{ route('super_admin.audit-logs') }}"
          class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">

        {{-- Search --}}
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Search</label>
            <input type="text" name="search" value="{{ $search }}"
                   placeholder="Action, description, IP, user…"
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
        </div>

        {{-- Action type --}}
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Action Type</label>
            <select name="action"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                <option value="all" {{ $action === 'all' ? 'selected' : '' }}>All Actions</option>
                @foreach($actions as $act)
                    <option value="{{ $act }}" {{ $action === $act ? 'selected' : '' }}>
                        {{ str_ireplace('Br Receipt', 'DR Receipt', ucwords(str_replace('_', ' ', $act))) }}
                    </option>
                @endforeach
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

        <div class="col-span-2 md:col-span-4 flex gap-3 justify-end">
            <a href="{{ route('super_admin.audit-logs') }}"
               class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-100 transition">
                Clear
            </a>
            <button type="submit"
                    class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-[#ff4040] transition text-sm">
                Filter
            </button>
        </div>
    </form>

    {{-- ── Header ── --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold">Audit Logs</h2>
            <p class="text-sm text-gray-400 mt-0.5">System-wide activity trail for all user actions</p>
        </div>
        <span class="text-xs text-gray-400 bg-gray-100 px-3 py-1.5 rounded-full font-medium">
            {{ $logs->total() }} {{ Str::plural('record', $logs->total()) }}
        </span>
    </div>

    {{-- ── Table ── --}}
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-primary text-white text-sm">
                    <th class="px-4 py-3 text-left rounded-tl-lg">#</th>
                    <th class="px-4 py-3 text-left">Date &amp; Time</th>
                    <th class="px-4 py-3 text-left">User</th>
                    <th class="px-4 py-3 text-left">Action</th>
                    <th class="px-4 py-3 text-left">Description</th>
                    <th class="px-4 py-3 text-left">IP Address</th>
                    <th class="px-4 py-3 text-left rounded-tr-lg">Details</th>
                </tr>
            </thead>
            <tbody class="bg-gray-50 divide-y divide-gray-200">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-100 transition text-sm">

                    <td class="px-4 py-3 text-gray-400 text-xs">
                        {{ $logs->firstItem() + $loop->index }}
                    </td>

                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="font-medium text-gray-700">{{ $log->created_at->format('m/d/Y') }}</div>
                        <div class="text-xs text-gray-400">{{ $log->created_at->format('h:i A') }}</div>
                    </td>

                    <td class="px-4 py-3">
                        @if($log->user)
                            <div class="font-medium text-gray-800">
                                {{ $log->user->first_name }} {{ $log->user->last_name }}
                            </div>
                            <div class="text-xs text-gray-400">{{ $log->user->email }}</div>
                        @else
                            <span class="text-gray-400 italic text-xs">System / Deleted</span>
                        @endif
                    </td>

                    <td class="px-4 py-3">
                        @php
                            $actionColors = [
                                'tanker_arrival'    => 'bg-blue-100 text-blue-700',
                                'tanker_departure'  => 'bg-orange-100 text-orange-700',
                                'staff_approved'    => 'bg-green-100 text-green-700',
                                'staff_blocked'     => 'bg-red-100 text-red-700',
                                'staff_unblocked'   => 'bg-teal-100 text-teal-700',
                                'staff_deleted'     => 'bg-red-200 text-red-800',
                                'staff_created'     => 'bg-green-100 text-green-700',
                                'login'             => 'bg-purple-100 text-purple-700',
                                'logout'            => 'bg-gray-200 text-gray-600',
                                'password_reset'    => 'bg-yellow-100 text-yellow-700',
                                'br_receipt_created'=> 'bg-indigo-100 text-indigo-700',
                                'payment_updated'   => 'bg-pink-100 text-pink-700',
                                'arrival_updated'   => 'bg-blue-200 text-blue-800',
                                'departure_updated' => 'bg-orange-200 text-orange-800',
                            ];
                            $colorClass = $actionColors[$log->action] ?? 'bg-gray-100 text-gray-600';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $colorClass }}">
                            {{ str_ireplace('Br Receipt', 'DR Receipt', ucwords(str_replace('_', ' ', $log->action))) }}
                        </span>
                    </td>

                    <td class="px-4 py-3 text-gray-600 max-w-xs">
                        <span class="line-clamp-2">{{ str_ireplace('BR Receipt', 'DR Receipt', $log->description) }}</span>
                    </td>

                    <td class="px-4 py-3 text-gray-500 font-mono text-xs">
                        {{ $log->ip_address ?? '—' }}
                    </td>

                    <td class="px-4 py-3">
                        @if($log->meta)
                            <button
                                onclick="toggleMeta({{ $log->id }})"
                                class="text-primary hover:text-[#ff4040] text-xs font-medium underline transition">
                                View Meta
                            </button>
                        @else
                            <span class="text-gray-300 text-xs">—</span>
                        @endif
                    </td>
                </tr>

                {{-- Expandable meta row --}}
                @if($log->meta)
                <tr id="meta-{{ $log->id }}" class="hidden bg-indigo-50">
                    <td colspan="7" class="px-6 py-4">
                        <div class="text-xs font-semibold text-indigo-500 uppercase tracking-wide mb-2">
                            Meta — {{ str_ireplace('Br Receipt', 'DR Receipt', ucwords(str_replace('_', ' ', $log->action))) }}
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach($log->meta as $key => $value)
                                @if($key === 'fuel_breakdown' && is_array($value))
                                    {{-- Span full width and render as a mini table --}}
                                    <div class="col-span-2 md:col-span-4 bg-white rounded-lg border border-indigo-100 overflow-hidden">
                                        <div class="text-xs text-indigo-400 uppercase tracking-wide px-3 pt-2 pb-1 font-semibold">
                                            Fuel Breakdown
                                        </div>
                                        <table class="w-full text-sm">
                                            <thead>
                                                <tr class="bg-indigo-50 text-indigo-500 text-xs uppercase tracking-wide">
                                                    <th class="px-3 py-1.5 text-left font-semibold">Fuel Type</th>
                                                    <th class="px-3 py-1.5 text-right font-semibold">Liters</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-indigo-50">
                                                @foreach($value as $fuel)
                                                <tr>
                                                    <td class="px-3 py-1.5 capitalize font-medium text-gray-700">
                                                        {{ $fuel['fuel_type'] ?? '—' }}
                                                    </td>
                                                    <td class="px-3 py-1.5 text-right text-gray-800 font-semibold">
                                                        {{ number_format($fuel['liters'] ?? 0, 2) }} L
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @elseif(is_array($value))
                                    <div class="bg-white rounded-lg px-3 py-2 border border-indigo-100">
                                        <div class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">
                                            {{ ucwords(str_replace('_', ' ', $key)) }}
                                        </div>
                                        <div class="font-medium text-gray-800 text-sm break-all">
                                            {{ json_encode($value) }}
                                        </div>
                                    </div>
                                @else
                                    <div class="bg-white rounded-lg px-3 py-2 border border-indigo-100">
                                        <div class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">
                                            {{ ucwords(str_replace('_', ' ', $key)) }}
                                        </div>
                                        <div class="font-medium text-gray-800 text-sm">
                                            {{ $value ?? '—' }}
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </td>
                </tr>
                @endif

                @empty
                <tr>
                    <td colspan="7" class="px-4 py-12 text-center text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        No audit log entries found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ── Pagination ── --}}
    @if($logs->hasPages())
    <div class="flex items-center justify-center flex-wrap gap-2 mt-6">

        {{-- Prev --}}
        @if($logs->onFirstPage())
            <span class="bg-[#FFB8B8] text-white px-3 py-2 rounded-lg flex items-center gap-1 cursor-not-allowed opacity-60 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Prev
            </span>
        @else
            <a href="{{ $logs->previousPageUrl() }}"
               class="bg-primary text-white px-3 py-2 rounded-lg hover:bg-[#ff4040] transition flex items-center gap-1 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Prev
            </a>
        @endif

        {{-- Numbered pages (window of 5 around current) --}}
        @php
            $current  = $logs->currentPage();
            $last     = $logs->lastPage();
            $start    = max(1, $current - 2);
            $end      = min($last, $current + 2);
        @endphp

        @if($start > 1)
            <a href="{{ $logs->url(1) }}"
               class="px-3 py-2 rounded-lg text-sm border border-gray-200 hover:bg-gray-100 transition text-gray-600">1</a>
            @if($start > 2)
                <span class="px-2 py-2 text-gray-400 text-sm">…</span>
            @endif
        @endif

        @for($page = $start; $page <= $end; $page++)
            @if($page === $current)
                <span class="bg-primary text-white px-3 py-2 rounded-lg text-sm font-semibold">{{ $page }}</span>
            @else
                <a href="{{ $logs->url($page) }}"
                   class="px-3 py-2 rounded-lg text-sm border border-gray-200 hover:bg-gray-100 transition text-gray-600">{{ $page }}</a>
            @endif
        @endfor

        @if($end < $last)
            @if($end < $last - 1)
                <span class="px-2 py-2 text-gray-400 text-sm">…</span>
            @endif
            <a href="{{ $logs->url($last) }}"
               class="px-3 py-2 rounded-lg text-sm border border-gray-200 hover:bg-gray-100 transition text-gray-600">{{ $last }}</a>
        @endif

        {{-- Next --}}
        @if($logs->hasMorePages())
            <a href="{{ $logs->nextPageUrl() }}"
               class="bg-primary text-white px-3 py-2 rounded-lg hover:bg-[#ff4040] transition flex items-center gap-1 text-sm">
                Next
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        @else
            <span class="bg-[#FFB8B8] text-white px-3 py-2 rounded-lg flex items-center gap-1 cursor-not-allowed opacity-60 text-sm">
                Next
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </span>
        @endif

    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    function toggleMeta(id) {
        const row = document.getElementById('meta-' + id);
        if (row) row.classList.toggle('hidden');
    }
</script>
@endpush
