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
    $total    = $receipts->count();
    $unpaid   = $receipts->filter(fn($r) => !$r->payment || $r->payment->status === 'unpaid')->count();
    $partial  = $receipts->filter(fn($r) => $r->payment?->status === 'partial')->count();
    $paid     = $receipts->filter(fn($r) => $r->payment?->status === 'paid')->count();
    $overdue  = $receipts->filter(fn($r) => $r->payment?->is_overdue)->count();
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
                            $payment  = $receipt->payment;
                            $status   = $payment?->status ?? 'unpaid';
                            $balance  = $payment?->remaining_balance ?? $receipt->grand_total;
                            $overdue  = $payment?->is_overdue ?? false;
                            $dueDate  = $payment?->due_date;
                        @endphp
                        <tr class="hover:bg-gray-50 transition {{ $overdue ? 'bg-red-50/40' : '' }}">
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
                            <td class="px-4 py-3 text-center text-xs {{ $overdue ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                                @if($dueDate)
                                    {{ $dueDate->format('m/d/Y') }}
                                    @if($overdue) <span class="block text-red-500 font-bold">OVERDUE</span> @endif
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
    @endif
</div>
<script>
    const fuelForm = document.getElementById('fuelForm');
    const submitBtn = fuelForm.querySelector('button[type="submit"]');

    fuelForm.addEventListener('submit', function() {
        // Disable the button immediately to prevent multiple clicks
        submitBtn.disabled = true;
        submitBtn.innerText = 'Submitting...'; // Optional: give user feedback
    });
</script>

@endsection