@extends('super_admin.layout.app')

@section('title', 'Payment Details — Nº {{ $receipt->receipt_no }}')

@section('content')

@php
    $payment  = $receipt->payment;
    $status   = $payment?->status ?? 'unpaid';
    $paid     = (float)($payment?->down_payment ?? 0) + (float)($payment?->final_payment ?? 0);
    $balance  = max(0, (float)$receipt->grand_total - $paid);
    $pct      = $receipt->grand_total > 0 ? round(($paid / $receipt->grand_total) * 100) : 0;
    $overdue  = $payment?->is_overdue ?? false;

    $badgeClass = match($status) {
        'paid'    => 'bg-green-100 text-green-700 border-green-200',
        'partial' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
        default   => 'bg-red-100 text-red-700 border-red-200',
    };
@endphp

{{-- Back + header --}}
<div class="mb-5 flex items-center gap-3">
    <a href="{{ route('super_admin.br-receipt-payments.index') }}"
       class="text-xs text-gray-500 hover:text-gray-700 flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Ledger
    </a>
</div>

<div class="flex flex-col xl:flex-row gap-5">

    {{-- ══ LEFT: Receipt details + fuel breakdown ══ --}}
    <div class="flex-1 space-y-4">

        {{-- Receipt header card --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold mb-0.5">Delivery Receipt</p>
                    <h2 class="text-2xl font-black font-mono tracking-tight">Nº {{ $receipt->receipt_no }}</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $receipt->departure?->tanker_number }} &nbsp;/&nbsp; {{ $receipt->departure?->driver }}
                        &nbsp;·&nbsp; {{ $receipt->created_at->format('F d, Y') }}
                    </p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold border uppercase tracking-wide {{ $badgeClass }}">
                    {{ $status }}
                    @if($overdue) &nbsp;· OVERDUE @endif
                </span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-4 pt-4 border-t border-gray-100">
                <div>
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Delivered To</p>
                    <p class="text-sm font-semibold text-gray-700 mt-0.5">{{ $receipt->delivered_to ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Address</p>
                    <p class="text-sm font-semibold text-gray-700 mt-0.5">{{ $receipt->address ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">TIN</p>
                    <p class="text-sm font-semibold text-gray-700 mt-0.5">{{ $receipt->tin ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Terms</p>
                    <p class="text-sm font-semibold text-gray-700 mt-0.5">{{ $receipt->terms ?? '—' }}</p>
                </div>
            </div>
        </div>

        {{-- Fuel breakdown --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Fuel Breakdown</p>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-400 uppercase">Product</th>
                        <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-400 uppercase">Liters</th>
                        <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-400 uppercase">Unit Price</th>
                        <th class="px-4 py-2.5 text-right text-xs font-semibold text-gray-400 uppercase">Amount</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-400 uppercase">Remarks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($receipt->fuels as $fuel)
                        <tr>
                            <td class="px-4 py-2.5 font-bold uppercase tracking-wide text-gray-800">{{ $fuel->fuel_type }}</td>
                            <td class="px-4 py-2.5 text-right font-mono text-gray-700">{{ number_format($fuel->liters, 2) }}</td>
                            <td class="px-4 py-2.5 text-right font-mono text-gray-700">₱ {{ number_format($fuel->unit_price, 2) }}</td>
                            <td class="px-4 py-2.5 text-right font-mono font-semibold text-gray-800">₱ {{ number_format($fuel->amount, 2) }}</td>
                            <td class="px-4 py-2.5 text-xs text-gray-400">{{ $fuel->remarks ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-gray-200 bg-gray-50">
                        <td colspan="3" class="px-4 py-3 text-right text-sm font-bold text-gray-700 uppercase tracking-wide">Grand Total</td>
                        <td class="px-4 py-3 text-right font-mono font-black text-gray-900 text-base">₱ {{ number_format($receipt->grand_total, 2) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Payment progress bar --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Payment Progress</p>
                <p class="text-sm font-bold text-gray-700">{{ $pct }}%</p>
            </div>
            <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-3 rounded-full transition-all duration-500
                    {{ $pct >= 100 ? 'bg-green-500' : ($pct > 0 ? 'bg-yellow-400' : 'bg-red-400') }}"
                     style="width: {{ $pct }}%"></div>
            </div>
            <div class="grid grid-cols-3 gap-3 mt-4">
                <div class="text-center">
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Total</p>
                    <p class="text-sm font-black font-mono text-gray-800">₱ {{ number_format($receipt->grand_total, 2) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Paid</p>
                    <p class="text-sm font-black font-mono text-green-600">₱ {{ number_format($paid, 2) }}</p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Balance</p>
                    <p class="text-sm font-black font-mono {{ $balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                        {{ $balance > 0 ? '₱ ' . number_format($balance, 2) : 'Fully Paid' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Payment history timeline --}}
        @if($payment)
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4">Payment History</p>
            <div class="relative pl-6">
                <div class="absolute left-2 top-0 bottom-0 w-px bg-gray-200"></div>

                @if($payment->down_payment > 0)
                <div class="relative mb-5">
                    <div class="absolute -left-4 top-1 w-3 h-3 rounded-full bg-yellow-400 border-2 border-white shadow"></div>
                    <div class="bg-yellow-50 border border-yellow-100 rounded-lg p-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-yellow-700 uppercase tracking-wide">Down Payment</p>
                                <p class="text-lg font-black font-mono text-yellow-800">₱ {{ number_format($payment->down_payment, 2) }}</p>
                                @php $dpPct = $receipt->grand_total > 0 ? round(($payment->down_payment / $receipt->grand_total) * 100, 1) : 0; @endphp
                                <p class="text-xs text-yellow-600 mt-0.5">{{ $dpPct }}% of total</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-400 font-medium">Date Paid</p>
                                <p class="text-sm font-semibold text-gray-700">
                                    {{ $payment->down_payment_date?->format('m/d/Y') ?? '—' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if($payment->final_payment > 0)
                <div class="relative mb-5">
                    <div class="absolute -left-4 top-1 w-3 h-3 rounded-full bg-green-500 border-2 border-white shadow"></div>
                    <div class="bg-green-50 border border-green-100 rounded-lg p-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold text-green-700 uppercase tracking-wide">Final Payment</p>
                                <p class="text-lg font-black font-mono text-green-800">₱ {{ number_format($payment->final_payment, 2) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-400 font-medium">Date Paid</p>
                                <p class="text-sm font-semibold text-gray-700">
                                    {{ $payment->final_payment_date?->format('m/d/Y') ?? '—' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if($payment->due_date && $status !== 'paid')
                <div class="relative">
                    <div class="absolute -left-4 top-1 w-3 h-3 rounded-full {{ $overdue ? 'bg-red-500' : 'bg-gray-300' }} border-2 border-white shadow"></div>
                    <div class="border {{ $overdue ? 'border-red-200 bg-red-50' : 'border-gray-200 bg-gray-50' }} rounded-lg p-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-semibold {{ $overdue ? 'text-red-600' : 'text-gray-500' }} uppercase tracking-wide">
                                    Final Payment Due
                                </p>
                                <p class="text-sm font-bold {{ $overdue ? 'text-red-700' : 'text-gray-700' }}">
                                    {{ $payment->due_date->format('F d, Y') }}
                                </p>
                            </div>
                            @if($overdue)
                                <span class="text-xs font-bold text-red-600 bg-red-100 px-2 py-1 rounded-full">OVERDUE</span>
                            @else
                                <span class="text-xs text-gray-500">
                                    {{ now()->diffInDays($payment->due_date, false) }} days remaining
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                @if(!$payment->down_payment && !$payment->final_payment)
                <p class="text-sm text-gray-400 italic">No payments recorded yet.</p>
                @endif
            </div>

            @if($payment->notes)
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Notes</p>
                <p class="text-sm text-gray-600">{{ $payment->notes }}</p>
            </div>
            @endif
        </div>
        @endif
    </div>

    {{-- ══ RIGHT: Payment form ══ --}}
    @php
        $status = $payment?->status ?? 'unpaid';
    @endphp
    @if ($status === 'paid')
        <div class="w-full xl:w-[360px] shrink-0">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 sticky top-6">
                <p class="text-sm font-bold text-gray-800 mb-4">
                    {{ $payment ? 'Update Payment' : 'Record Payment' }}
                </p>

                @if(session('success'))
                    <div class="mb-4 px-4 py-3 rounded-lg bg-green-100 border border-green-200 text-green-800 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('super_admin.br-receipt-payments.upsert', $receipt->id) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Client Name <span class="text-[#FF5757]">*</span></label>
                        <input type="text" name="client_name" required readonly
                            value="{{ old('client_name', $payment?->client_name) }}"
                            placeholder="e.g. Sta. Ignacia Petron"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#FF5757] @error('client_name') border-red-400 @enderror">
                        @error('client_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Total (read-only) --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Total Amount</label>
                        <input type="text" readonly
                            value="₱ {{ number_format($receipt->grand_total, 2) }}"
                            class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-500 cursor-not-allowed font-mono">
                    </div>

                    <hr class="border-gray-100">

                    {{-- Down payment --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Down Payment (₱)</label>
                        <input type="number" name="down_payment" step="0.01" min="0" readonly
                            value="{{ old('down_payment', $payment?->down_payment) }}"
                            placeholder="0.00"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Down Payment Date</label>
                        <input type="date" name="down_payment_date" readonly
                            value="{{ old('down_payment_date', $payment?->down_payment_date?->format('Y-m-d')) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
                    </div>

                    <hr class="border-gray-100">

                    {{-- Final payment --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Final Payment (₱)</label>
                        <input type="number" name="final_payment" step="0.01" min="0" readonly
                            value="{{ old('final_payment', $payment?->final_payment) }}"
                            placeholder="0.00"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Final Payment Date</label>
                        <input type="date" name="final_payment_date" readonly
                            value="{{ old('final_payment_date', $payment?->final_payment_date?->format('Y-m-d')) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
                    </div>

                    <hr class="border-gray-100">

                    {{-- Due date --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">
                            Due Date for Final Payment
                            <span class="text-gray-400">(admin-set)</span>
                        </label>
                        <input type="date" name="due_date" readonly
                            value="{{ old('due_date', $payment?->due_date?->format('Y-m-d')) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Notes <span class="text-gray-400">(optional)</span></label>
                        <textarea name="notes" rows="3" placeholder="Any additional notes..." readonly
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#FF5757] resize-none">{{ old('notes', $payment?->notes) }}</textarea>
                    </div>

                    {{-- Live balance preview --}}
                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200 text-sm" id="balancePreview">
                        <div class="flex justify-between text-gray-500 mb-1">
                            <span>Grand Total</span>
                            <span class="font-mono">₱ {{ number_format($receipt->grand_total, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-500 mb-1">
                            <span>Total Paid</span>
                            <span class="font-mono text-green-600" id="liveAmountPaid">₱ 0.00</span>
                        </div>
                        <div class="flex justify-between font-bold border-t border-gray-200 pt-1 mt-1">
                            <span>Remaining Balance</span>
                            <span class="font-mono text-red-600" id="liveBalance">₱ {{ number_format($receipt->grand_total, 2) }}</span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if ($status === 'unpaid' || !$payment)
        <div class="w-full xl:w-[360px] shrink-0">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 sticky top-6">
                <p class="text-sm font-bold text-gray-800 mb-4">
                    {{ $payment ? 'Update Payment' : 'Record Payment' }}
                </p>

                @if(session('success'))
                    <div class="mb-4 px-4 py-3 rounded-lg bg-green-100 border border-green-200 text-green-800 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('super_admin.br-receipt-payments.upsert', $receipt->id) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Client Name <span class="text-[#FF5757]">*</span></label>
                        <input type="text" name="client_name" required
                            value="{{ old('client_name', $payment?->client_name) }}"
                            placeholder="e.g. Sta. Ignacia Petron"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#FF5757] @error('client_name') border-red-400 @enderror">
                        @error('client_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Total (read-only) --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Total Amount</label>
                        <input type="text" readonly
                            value="₱ {{ number_format($receipt->grand_total, 2) }}"
                            class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-500 cursor-not-allowed font-mono">
                    </div>

                    <hr class="border-gray-100">

                    {{-- Down payment --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Down Payment (₱)</label>
                        <input type="number" name="down_payment" step="0.01" min="0"
                            value="{{ old('down_payment', $payment?->down_payment) }}"
                            placeholder="0.00"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Down Payment Date</label>
                        <input type="date" name="down_payment_date"
                            value="{{ old('down_payment_date', $payment?->down_payment_date?->format('Y-m-d')) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
                    </div>

                    <hr class="border-gray-100">

                    {{-- Final payment --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Final Payment (₱)</label>
                        <input type="number" name="final_payment" step="0.01" min="0"
                            value="{{ old('final_payment', $payment?->final_payment) }}"
                            placeholder="0.00"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Final Payment Date</label>
                        <input type="date" name="final_payment_date"
                            value="{{ old('final_payment_date', $payment?->final_payment_date?->format('Y-m-d')) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
                    </div>

                    <hr class="border-gray-100">

                    {{-- Due date --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">
                            Due Date for Final Payment
                            <span class="text-gray-400">(admin-set)</span>
                        </label>
                        <input type="date" name="due_date"
                            value="{{ old('due_date', $payment?->due_date?->format('Y-m-d')) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Notes <span class="text-gray-400">(optional)</span></label>
                        <textarea name="notes" rows="3" placeholder="Any additional notes..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#FF5757] resize-none">{{ old('notes', $payment?->notes) }}</textarea>
                    </div>

                    {{-- Live balance preview --}}
                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200 text-sm" id="balancePreview">
                        <div class="flex justify-between text-gray-500 mb-1">
                            <span>Grand Total</span>
                            <span class="font-mono">₱ {{ number_format($receipt->grand_total, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-500 mb-1">
                            <span>Total Paid</span>
                            <span class="font-mono text-green-600" id="liveAmountPaid">₱ 0.00</span>
                        </div>
                        <div class="flex justify-between font-bold border-t border-gray-200 pt-1 mt-1">
                            <span>Remaining Balance</span>
                            <span class="font-mono text-red-600" id="liveBalance">₱ {{ number_format($receipt->grand_total, 2) }}</span>
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full bg-[#FF5757] text-white py-2.5 rounded-full font-semibold text-sm hover:bg-[#e04444] transition">
                        Save Payment Record
                    </button>
                </form>
            </div>
        </div>
    @endif


</div>

<script>
    const grandTotal   = {{ (float) $receipt->grand_total }};
    const downInput    = document.querySelector('[name="down_payment"]');
    const finalInput   = document.querySelector('[name="final_payment"]');
    const livePaid     = document.getElementById('liveAmountPaid');
    const liveBalance  = document.getElementById('liveBalance');

    function fmt(n) {
        return '₱ ' + n.toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function updatePreview() {
        const down    = parseFloat(downInput.value)  || 0;
        const final   = parseFloat(finalInput.value) || 0;
        const paid    = down + final;
        const balance = Math.max(0, grandTotal - paid);
        livePaid.textContent    = fmt(paid);
        liveBalance.textContent = balance > 0 ? fmt(balance) : 'Fully Paid ✓';
        liveBalance.className   = 'font-mono ' + (balance > 0 ? 'text-red-600' : 'text-green-600');
    }

    downInput?.addEventListener('input', updatePreview);
    finalInput?.addEventListener('input', updatePreview);
    updatePreview();
</script>
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