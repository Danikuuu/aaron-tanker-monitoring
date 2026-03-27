@extends('super_admin.layout.app')

@section('title', 'Payment Ledger')

@section('content')

<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-xl font-bold">Payment Ledger</h2>
        <p class="text-sm text-gray-500 mt-0.5">Track down payments and balances for all BR Receipts.</p>
    </div>
</div>

{{-- Summary Cards --}}
@php
    $allReceipts = $receipts->getCollection();
    $total    = $receipts->total();
    $unpaid   = $allReceipts->filter(fn($r) => !$r->payment || $r->payment->status === 'unpaid')->count();
    $partial  = $allReceipts->filter(fn($r) => $r->payment?->status === 'partial')->count();
    $paid     = $allReceipts->filter(fn($r) => $r->payment?->status === 'paid')->count();
    $overdue  = $allReceipts->filter(fn($r) => $r->payment?->is_overdue)->count();
@endphp

<div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Total Receipts</p>
        <p class="text-2xl font-bold text-gray-800">{{ $total }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Unpaid</p>
        <p class="text-2xl font-bold text-red-500">{{ $unpaid }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Partial</p>
        <p class="text-2xl font-bold text-yellow-500">{{ $partial }}</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Fully Paid</p>
        <p class="text-2xl font-bold text-green-500">{{ $paid }}</p>
    </div>
</div>

@if($overdue > 0)
    <div class="mb-4 px-4 py-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700 flex items-center gap-2">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
        </svg>
        <span><strong>{{ $overdue }}</strong> receipt{{ $overdue > 1 ? 's are' : ' is' }} past due date.</span>
    </div>
@endif

{{-- Filters --}}
<form method="GET" action="{{ route('super_admin.br-receipt-payments.index') }}"
      class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 mb-4">
    <div class="flex flex-col sm:flex-row gap-3 flex-wrap">

        {{-- Search --}}
        <div class="relative flex-1 min-w-[200px]">
            <input type="text" name="search"
                   value="{{ $filters['search'] ?? '' }}"
                   placeholder="Search receipt no. or client..."
                   class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
            <svg class="w-4 h-4 absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>

        {{-- Status --}}
        <select name="status"
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#FF5757] bg-white text-gray-700">
            <option value="">All Statuses</option>
            <option value="unpaid"  {{ ($filters['status'] ?? '') === 'unpaid'  ? 'selected' : '' }}>Unpaid</option>
            <option value="partial" {{ ($filters['status'] ?? '') === 'partial' ? 'selected' : '' }}>Partial</option>
            <option value="paid"    {{ ($filters['status'] ?? '') === 'paid'    ? 'selected' : '' }}>Paid</option>
        </select>

        {{-- Date From --}}
        <div class="flex items-center gap-2">
            <label class="text-xs text-gray-500 whitespace-nowrap">From</label>
            <input type="date" name="date_from"
                   value="{{ $filters['date_from'] ?? '' }}"
                   class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
        </div>

        {{-- Date To --}}
        <div class="flex items-center gap-2">
            <label class="text-xs text-gray-500 whitespace-nowrap">To</label>
            <input type="date" name="date_to"
                   value="{{ $filters['date_to'] ?? '' }}"
                   class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
        </div>

        {{-- Actions --}}
        <div class="flex gap-2">
            <button type="submit"
                    class="px-4 py-2 bg-[#FF5757] text-white text-sm font-semibold rounded-lg hover:bg-[#e04444] transition">
                Filter
            </button>
            @if(array_filter($filters ?? []))
                <a href="{{ route('super_admin.br-receipt-payments.index') }}"
                   class="px-4 py-2 bg-gray-100 text-gray-600 text-sm font-semibold rounded-lg hover:bg-gray-200 transition">
                    Clear
                </a>
            @endif
        </div>
    </div>
</form>

{{-- Receipts Table --}}
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    @if($receipts->isEmpty())
        <div class="p-10 text-center text-gray-400 text-sm">
            No BR Receipts saved yet. Generate one from the
            <a href="{{ route('super_admin.br-receipt') }}" class="text-[#FF5757] underline">BR Receipt Builder</a>.
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Receipt No.</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Delivered To</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanker / Driver</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Date</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Grand Total</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Balance</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Due Date</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wide">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($receipts as $receipt)
                        @php
                            $payment   = $receipt->payment;
                            $paid      = (float)($payment?->down_payment ?? $receipt->downpayment ?? 0) + (float)($payment?->final_payment ?? 0);
                            $balance   = max(0, (float)$receipt->grand_total - $paid);
                            $status    = $paid <= 0 ? 'unpaid' : ($balance > 0 ? 'partial' : 'paid');
                            $isOverdue = $payment?->is_overdue ?? false;
                            $dueDate   = $payment?->due_date;
                        @endphp
                        <tr class="hover:bg-gray-50 transition {{ $isOverdue ? 'bg-red-50/40' : '' }}">
                            <td class="px-4 py-3 font-mono font-semibold text-gray-800">
                                Nº {{ $receipt->receipt_no }}
                            </td>
                            <td class="px-4 py-3 text-gray-700">{{ $receipt->delivered_to ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-600 text-xs">
                                {{ $receipt->departure?->tanker_number }} /
                                {{ $receipt->departure?->driver }}
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-xs">
                                {{ $receipt->created_at->format('m/d/Y') }}
                            </td>
                            <td class="px-4 py-3 text-right font-mono font-semibold text-gray-800">
                                ₱ {{ number_format($receipt->grand_total, 2) }}
                            </td>
                            <td class="px-4 py-3 text-right font-mono {{ $balance > 0 ? 'text-red-600 font-semibold' : 'text-green-600' }}">
                                {{ $balance > 0 ? '₱ ' . number_format($balance, 2) : '—' }}
                            </td>
                            <td class="px-4 py-3 text-center text-xs {{ $isOverdue ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                                @if($dueDate)
                                    {{ $dueDate->format('m/d/Y') }}
                                    @if($isOverdue) <span class="block text-red-500 font-bold">OVERDUE</span> @endif
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                @php
                                    $badge = match($status) {
                                        'paid'    => 'bg-green-100 text-green-700',
                                        'partial' => 'bg-yellow-100 text-yellow-700',
                                        default   => 'bg-red-100 text-red-700',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badge }} uppercase tracking-wide">
                                    {{ $status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('super_admin.br-receipt-payments.show', $receipt->id) }}"
                                   class="inline-flex items-center gap-1 text-xs text-[#FF5757] hover:underline font-semibold">
                                    View / Pay
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($receipts->hasPages())
            <div class="px-4 py-3 border-t border-gray-100 flex items-center justify-between gap-4 flex-wrap">
                <p class="text-xs text-gray-500">
                    Showing {{ $receipts->firstItem() }}–{{ $receipts->lastItem() }} of {{ $receipts->total() }} receipts
                </p>
                <div class="flex items-center gap-1">
                    @if($receipts->onFirstPage())
                        <span class="px-3 py-1.5 text-xs text-gray-300 border border-gray-200 rounded-lg cursor-not-allowed">← Prev</span>
                    @else
                        <a href="{{ $receipts->previousPageUrl() }}"
                           class="px-3 py-1.5 text-xs text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition">← Prev</a>
                    @endif

                    @foreach($receipts->getUrlRange(1, $receipts->lastPage()) as $page => $url)
                        @if($page == $receipts->currentPage())
                            <span class="px-3 py-1.5 text-xs font-bold text-white bg-[#FF5757] border border-[#FF5757] rounded-lg">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                               class="px-3 py-1.5 text-xs text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($receipts->hasMorePages())
                        <a href="{{ $receipts->nextPageUrl() }}"
                           class="px-3 py-1.5 text-xs text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition">Next →</a>
                    @else
                        <span class="px-3 py-1.5 text-xs text-gray-300 border border-gray-200 rounded-lg cursor-not-allowed">Next →</span>
                    @endif
                </div>
            </div>
        @endif
    @endif
</div>

@endsection