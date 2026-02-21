@extends('super_admin.layout.app')

@section('title', 'BR Receipt Builder')

@section('content')

{{-- html2pdf CDN --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<style>
    .r-cell-hdr {
        padding: 2.5pt 5pt;
        border: 0.5pt solid #ccc;
        font-family: 'IBM Plex Mono', monospace;
        font-size: 6pt;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
        background: #f5f5f5;
        text-align: center;
    }
    .r-cell {
        padding: 2.5pt 5pt;
        border: 0.5pt solid #ddd;
        font-family: 'IBM Plex Mono', monospace;
        font-size: 6.5pt;
        vertical-align: top;
    }
    .cut-line {
        border: none;
        border-top: 1.5pt dashed #aaa;
        margin: 8pt 0;
    }
    #previewWrapper { scroll-behavior: smooth; }
</style>

{{-- ═══════════════════════════ STEP 1 — SELECT DEPARTURE ═══════════════════════════ --}}
<div class="mb-6">
    <h2 class="text-xl font-bold mb-1">BR Receipt Builder</h2>
    <p class="text-sm text-gray-500 mb-4">Select a tanker departure to pre-fill the form, complete any remaining fields, then export as PDF.</p>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
        <label class="block text-sm font-semibold mb-2">1 — Select Tanker Departure</label>
        <select id="departureSelect"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
            <option value="">— choose a departure —</option>
            @foreach($departures as $d)
                <option value="{{ $d['id'] }}" data-payload="{{ json_encode($d) }}">
                    {{ $d['label'] }}
                </option>
            @endforeach
        </select>
    </div>
</div>

{{-- ═══════════════════════════ STEP 2 — FILL FORM ═══════════════════════════ --}}
<div id="formSection" class="hidden">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 mb-6">
        <h3 class="font-semibold text-sm mb-4">2 — Complete Receipt Details</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Tanker No.</label>
                <input id="f_tanker" type="text" readonly
                       class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-700 cursor-not-allowed">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Driver</label>
                <input id="f_driver" type="text" readonly
                       class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-700 cursor-not-allowed">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Date</label>
                <input id="f_date" type="text" readonly
                       class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-700 cursor-not-allowed">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Receipt No. <span class="text-[#FF5757]">*</span></label>
                <input id="f_receipt_no" type="text" placeholder="e.g. 2501"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Delivered To</label>
                <input id="f_delivered_to" type="text" placeholder="e.g. Sta. Ignacia Petron Station"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Address</label>
                <input id="f_address" type="text" placeholder="e.g. Sta. Ignacia, Tarlac"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">TIN</label>
                <input id="f_tin" type="text" readonly value="640-196-637-00000"
                       class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-700 cursor-not-allowed">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Terms</label>
                <input id="f_terms" type="text" placeholder="e.g. A 50% down"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
            </div>
        </div>

        <div class="mt-5">
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Fuel Details — Unit Price &amp; Remarks</label>
            <div id="fuelFormRows" class="space-y-2"></div>
        </div>

        <div class="mt-5 flex justify-end">
            <button id="previewBtn" onclick="buildPreview()"
                    class="bg-[#FF5757] text-white px-6 py-2.5 rounded-full text-sm font-semibold hover:bg-[#e04444] transition">
                Preview Receipt →
            </button>
        </div>
    </div>
</div>

{{-- ═══════════════════════════ STEP 3 — PREVIEW + EXPORT ═══════════════════════════ --}}
<div id="previewSection" class="hidden">
    <div class="flex items-center justify-between mb-3">
        <h3 class="font-semibold text-sm">3 — Preview</h3>
        <div class="flex gap-3">
            <button onclick="document.getElementById('previewSection').classList.add('hidden')"
                    class="text-xs px-4 py-2 border border-gray-300 rounded-full hover:bg-gray-50 transition">
                ← Edit Form
            </button>
            <button onclick="exportPdf()"
                    id="exportBtn"
                    class="bg-black text-white text-xs px-5 py-2 rounded-full font-semibold hover:bg-gray-800 transition flex items-center gap-2">
                🖨 Export PDF
            </button>
        </div>
    </div>

    <div id="previewWrapper" class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm bg-gray-100 p-4">
        <div id="receiptCapture" style="width:8.5in;margin:0 auto;background:white;font-family:'IBM Plex Mono',monospace;">

            {{-- ══ COPY 1 ══ --}}
            <div class="receipt-block" id="copy1" style="border:1pt solid #ccc;padding:0;">
                <div style="border-bottom:1.5pt solid black;padding:5pt 7pt 4pt;text-align:center;">
                    <div style="font-family:'IBM Plex Sans',sans-serif;font-size:11.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.02em;line-height:1.1;">A.C. Ang Fuel Distribution Services</div>
                    <div style="font-size:6.8pt;margin-top:1.5pt;color:#333;">Aaron Carl Agustin Ang — Prop. &nbsp;|&nbsp; Guimba, Nueva Ecija</div>
                    <div style="font-size:6.5pt;color:#555;">Cell Nos.: 0912-626-9364 &nbsp;·&nbsp; 09088856506</div>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;background:black;color:white;padding:3pt 8pt;">
                    <span style="font-family:'IBM Plex Mono',monospace;font-size:6.5pt;font-weight:600;letter-spacing:.1em;text-transform:uppercase;">Delivery Receipt</span>
                    <span id="p1_receipt_no" style="font-family:'IBM Plex Mono',monospace;font-size:9.5pt;font-weight:700;letter-spacing:.03em;"></span>
                </div>
                <div style="display:grid;grid-template-columns:2fr 1.3fr 0.85fr 0.85fr;border-bottom:1pt solid black;">
                    <div style="padding:3pt 6pt;border-right:1pt solid black;">
                        <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#555;">Delivered To</div>
                        <div id="p1_delivered_to" style="border-bottom:0.5pt solid #999;min-height:11pt;padding-bottom:1pt;font-size:7pt;font-family:'IBM Plex Mono',monospace;"></div>
                    </div>
                    <div style="padding:3pt 6pt;border-right:1pt solid black;">
                        <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#555;">Tanker Plate / Driver</div>
                        <div id="p1_tanker_driver" style="border-bottom:0.5pt solid #999;min-height:11pt;padding-bottom:1pt;font-size:7pt;font-family:'IBM Plex Mono',monospace;"></div>
                    </div>
                    <div style="padding:3pt 6pt;border-right:1pt solid black;">
                        <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#555;">Terms</div>
                        <div id="p1_terms" style="border-bottom:0.5pt solid #999;min-height:11pt;padding-bottom:1pt;font-size:7pt;font-family:'IBM Plex Mono',monospace;"></div>
                    </div>
                    <div style="padding:3pt 6pt;">
                        <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#555;">Date</div>
                        <div id="p1_date" style="border-bottom:0.5pt solid #999;min-height:11pt;padding-bottom:1pt;font-size:7pt;font-family:'IBM Plex Mono',monospace;"></div>
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1.6fr 1fr;border-bottom:1pt solid black;">
                    <div style="padding:3pt 6pt;border-right:1pt solid black;">
                        <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#555;">Address</div>
                        <div id="p1_address" style="border-bottom:0.5pt solid #999;min-height:11pt;padding-bottom:1pt;font-size:7pt;font-family:'IBM Plex Mono',monospace;"></div>
                    </div>
                    <div style="padding:3pt 6pt;">
                        <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#555;">TIN</div>
                        <div id="p1_tin" style="border-bottom:0.5pt solid #999;min-height:11pt;padding-bottom:1pt;font-size:7pt;font-family:'IBM Plex Mono',monospace;"></div>
                    </div>
                </div>
                <div style="border-bottom:1pt solid black;">
                    <table style="width:100%;border-collapse:collapse;font-size:7pt;">
                        <thead>
                            <tr>
                                <th class="r-cell-hdr" style="width:12%;text-align:right;">QTY</th>
                                <th class="r-cell-hdr" style="width:9%;text-align:center;">Unit</th>
                                <th class="r-cell-hdr" style="width:17%;text-align:center;">Product</th>
                                <th class="r-cell-hdr" style="width:17%;text-align:right;">Unit Price</th>
                                <th class="r-cell-hdr" style="width:22%;text-align:right;">Amount</th>
                                <th class="r-cell-hdr" style="width:23%;text-align:left;">Remarks</th>
                            </tr>
                        </thead>
                        <tbody id="p1_fuelRows"></tbody>
                        <tfoot>
                            <tr style="background:black;color:white;">
                                <td colspan="3" style="padding:3pt 5pt;font-family:'IBM Plex Mono',monospace;font-size:6.5pt;font-weight:700;letter-spacing:.08em;text-align:right;border:0.5pt solid #444;">GRAND TOTAL</td>
                                <td colspan="2" id="p1_grand_total" style="padding:3pt 5pt;font-family:'IBM Plex Mono',monospace;font-size:9pt;font-weight:700;text-align:right;border:0.5pt solid #444;"></td>
                                <td style="border:0.5pt solid #444;"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div style="padding:4pt 7pt 3pt;border-bottom:1pt solid black;font-size:5.4pt;line-height:1.4;color:#333;">
                    <span style="font-weight:700;">WAIVER OF GOOD QUALITY (MAAYOS NA KALIDAD) AND CORRECT QUANTITY (TAMANG SUKAT):</span>
                    BY SIGNING IN THE DELIVERY RECEIPT, RECEIVER ACKNOWLEDGE THAT PRODUCT PASSED GOOD QUALITY (MAAYOS NA KALIDAD)
                    AND CORRECT QUANTITY (TAMANG SUKAT). ONCE SIGNED THE CUSTOMER &amp; AUTHORIZED RECEIVER SHALL BE RESPONSIBLE
                    FOR THE PRODUCT AND THIS OFFICE WILL NOT ENTERTAIN ANY CLAIM OF DAMAGE.
                </div>
                <div style="display:flex;">
                    <div style="flex:1;border-right:1pt solid black;padding:4pt 7pt;">
                        <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.04em;margin-bottom:2pt;">Receiver's Signature (Over Printed Name)</div>
                        <div style="border-bottom:0.5pt solid #555;min-height:18pt;padding-bottom:2pt;"></div>
                        <div style="font-size:5pt;color:#666;margin-top:2pt;font-style:italic;">Received the above goods and supplies in good order and condition.</div>
                    </div>
                    <div style="flex:1;padding:4pt 7pt;">
                        <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.04em;margin-bottom:2pt;">Authorized Receiver's Signature (Over Printed Name)</div>
                        <div style="border-bottom:0.5pt solid #555;min-height:18pt;padding-bottom:2pt;"></div>
                        <div style="font-size:5pt;color:#666;margin-top:2pt;font-style:italic;">(Authorized Receiver Only)</div>
                    </div>
                </div>
                <div style="border-top:0.5pt solid #ddd;padding:2pt 7pt;text-align:right;font-family:'IBM Plex Mono',monospace;font-size:5pt;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#aaa;">OFFICE COPY</div>
            </div>

            <hr class="cut-line">

            {{-- ══ COPY 2 ══ --}}
            <div class="receipt-block" id="copy2" style="border:1pt solid #ccc;padding:0;">
                <div style="border-bottom:1.5pt solid black;padding:5pt 7pt 4pt;text-align:center;">
                    <div style="font-family:'IBM Plex Sans',sans-serif;font-size:11.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.02em;line-height:1.1;">A.C. Ang Fuel Distribution Services</div>
                    <div style="font-size:6.8pt;margin-top:1.5pt;color:#333;">Aaron Carl Agustin Ang — Prop. &nbsp;|&nbsp; Guimba, Nueva Ecija</div>
                    <div style="font-size:6.5pt;color:#555;">Cell Nos.: 0912-626-9364 &nbsp;·&nbsp; 09088856506</div>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;background:black;color:white;padding:3pt 8pt;">
                    <span style="font-family:'IBM Plex Mono',monospace;font-size:6.5pt;font-weight:600;letter-spacing:.1em;text-transform:uppercase;">Delivery Receipt</span>
                    <span id="p2_receipt_no" style="font-family:'IBM Plex Mono',monospace;font-size:9.5pt;font-weight:700;letter-spacing:.03em;"></span>
                </div>
                <div style="display:grid;grid-template-columns:2fr 1.3fr 0.85fr 0.85fr;border-bottom:1pt solid black;">
                    <div style="padding:3pt 6pt;border-right:1pt solid black;">
                        <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#555;">Delivered To</div>
                        <div id="p2_delivered_to" style="border-bottom:0.5pt solid #999;min-height:11pt;padding-bottom:1pt;font-size:7pt;font-family:'IBM Plex Mono',monospace;"></div>
                    </div>
                    <div style="padding:3pt 6pt;border-right:1pt solid black;">
                        <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#555;">Tanker Plate / Driver</div>
                        <div id="p2_tanker_driver" style="border-bottom:0.5pt solid #999;min-height:11pt;padding-bottom:1pt;font-size:7pt;font-family:'IBM Plex Mono',monospace;"></div>
                    </div>
                    <div style="padding:3pt 6pt;border-right:1pt solid black;">
                        <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#555;">Terms</div>
                        <div id="p2_terms" style="border-bottom:0.5pt solid #999;min-height:11pt;padding-bottom:1pt;font-size:7pt;font-family:'IBM Plex Mono',monospace;"></div>
                    </div>
                    <div style="padding:3pt 6pt;">
                        <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#555;">Date</div>
                        <div id="p2_date" style="border-bottom:0.5pt solid #999;min-height:11pt;padding-bottom:1pt;font-size:7pt;font-family:'IBM Plex Mono',monospace;"></div>
                    </div>
                </div>
                <div style="display:grid;grid-template-columns:1.6fr 1fr;border-bottom:1pt solid black;">
                    <div style="padding:3pt 6pt;border-right:1pt solid black;">
                        <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#555;">Address</div>
                        <div id="p2_address" style="border-bottom:0.5pt solid #999;min-height:11pt;padding-bottom:1pt;font-size:7pt;font-family:'IBM Plex Mono',monospace;"></div>
                    </div>
                    <div style="padding:3pt 6pt;">
                        <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#555;">TIN</div>
                        <div id="p2_tin" style="border-bottom:0.5pt solid #999;min-height:11pt;padding-bottom:1pt;font-size:7pt;font-family:'IBM Plex Mono',monospace;"></div>
                    </div>
                </div>
                <div style="border-bottom:1pt solid black;">
                    <table style="width:100%;border-collapse:collapse;font-size:7pt;">
                        <thead>
                            <tr>
                                <th class="r-cell-hdr" style="width:12%;text-align:right;">QTY</th>
                                <th class="r-cell-hdr" style="width:9%;text-align:center;">Unit</th>
                                <th class="r-cell-hdr" style="width:17%;text-align:center;">Product</th>
                                <th class="r-cell-hdr" style="width:17%;text-align:right;">Unit Price</th>
                                <th class="r-cell-hdr" style="width:22%;text-align:right;">Amount</th>
                                <th class="r-cell-hdr" style="width:23%;text-align:left;">Remarks</th>
                            </tr>
                        </thead>
                        <tbody id="p2_fuelRows"></tbody>
                        <tfoot>
                            <tr style="background:black;color:white;">
                                <td colspan="3" style="padding:3pt 5pt;font-family:'IBM Plex Mono',monospace;font-size:6.5pt;font-weight:700;letter-spacing:.08em;text-align:right;border:0.5pt solid #444;">GRAND TOTAL</td>
                                <td colspan="2" id="p2_grand_total" style="padding:3pt 5pt;font-family:'IBM Plex Mono',monospace;font-size:9pt;font-weight:700;text-align:right;border:0.5pt solid #444;"></td>
                                <td style="border:0.5pt solid #444;"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div style="padding:4pt 7pt 3pt;border-bottom:1pt solid black;font-size:5.4pt;line-height:1.4;color:#333;">
                    <span style="font-weight:700;">WAIVER OF GOOD QUALITY (MAAYOS NA KALIDAD) AND CORRECT QUANTITY (TAMANG SUKAT):</span>
                    BY SIGNING IN THE DELIVERY RECEIPT, RECEIVER ACKNOWLEDGE THAT PRODUCT PASSED GOOD QUALITY (MAAYOS NA KALIDAD)
                    AND CORRECT QUANTITY (TAMANG SUKAT). ONCE SIGNED THE CUSTOMER &amp; AUTHORIZED RECEIVER SHALL BE RESPONSIBLE
                    FOR THE PRODUCT AND THIS OFFICE WILL NOT ENTERTAIN ANY CLAIM OF DAMAGE.
                </div>
                <div style="display:flex;">
                    <div style="flex:1;border-right:1pt solid black;padding:4pt 7pt;">
                        <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.04em;margin-bottom:2pt;">Receiver's Signature (Over Printed Name)</div>
                        <div style="border-bottom:0.5pt solid #555;min-height:18pt;padding-bottom:2pt;"></div>
                        <div style="font-size:5pt;color:#666;margin-top:2pt;font-style:italic;">Received the above goods and supplies in good order and condition.</div>
                    </div>
                    <div style="flex:1;padding:4pt 7pt;">
                        <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.04em;margin-bottom:2pt;">Authorized Receiver's Signature (Over Printed Name)</div>
                        <div style="border-bottom:0.5pt solid #555;min-height:18pt;padding-bottom:2pt;"></div>
                        <div style="font-size:5pt;color:#666;margin-top:2pt;font-style:italic;">(Authorized Receiver Only)</div>
                    </div>
                </div>
                <div style="border-top:0.5pt solid #ddd;padding:2pt 7pt;text-align:right;font-family:'IBM Plex Mono',monospace;font-size:5pt;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#aaa;">CUSTOMER COPY</div>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════ RECEIPT HISTORY ═══════════════════════════ --}}
<div class="mt-8">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-base">Receipt History</h3>
            <span class="text-xs text-gray-400">{{ $receipts->total() }} receipt(s) total</span>
        </div>

        @if($receipts->isEmpty())
            <div class="text-center py-10 text-gray-400">
                <svg class="w-10 h-10 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-sm">No receipts exported yet.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-primary text-white text-sm">
                            <th class="px-4 py-3 text-left rounded-tl-lg">Receipt No.</th>
                            <th class="px-4 py-3 text-left">Delivered To</th>
                            <th class="px-4 py-3 text-left">Tanker / Driver</th>
                            <th class="px-4 py-3 text-left">Date</th>
                            <th class="px-4 py-3 text-left">Terms</th>
                            <th class="px-4 py-3 text-left">Fuels</th>
                            <th class="px-4 py-3 text-right">Grand Total</th>
                            <th class="px-4 py-3 text-center rounded-tr-lg">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-gray-50 text-sm">
                        @foreach($receipts as $receipt)
                        <tr class="border-b border-gray-200 hover:bg-gray-100 transition">
                            <td class="px-4 py-3 font-bold font-mono">Nº {{ $receipt->receipt_no }}</td>
                            <td class="px-4 py-3">
                                <div class="font-medium">{{ $receipt->delivered_to ?? '—' }}</div>
                                <div class="text-xs text-gray-400">{{ $receipt->address ?? '' }}</div>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-600">
                                {{ $receipt->departure->tanker_number ?? '—' }}
                                / {{ $receipt->departure->driver ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                {{ optional($receipt->departure->departure_date)->format('m/d/Y') ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-500">{{ $receipt->terms ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($receipt->fuels as $fuel)
                                        <span class="inline-flex items-center gap-1 text-xs px-2 py-0.5 rounded-full font-semibold
                                            {{ $fuel->fuel_type === 'diesel'   ? 'bg-green-100 text-green-700'   :
                                              ($fuel->fuel_type === 'premium'  ? 'bg-yellow-100 text-yellow-700' :
                                              ($fuel->fuel_type === 'unleaded' ? 'bg-blue-100 text-blue-700'     :
                                                                                 'bg-purple-100 text-purple-700')) }}">
                                            {{ ucfirst($fuel->fuel_type) }}
                                            <span class="font-normal">{{ number_format($fuel->liters, 2) }} L</span>
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right font-bold font-mono text-sm">
                                ₱ {{ number_format($receipt->grand_total, 2) }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button
                                    onclick="redownloadReceipt({{ $receipt->id }})"
                                    class="bg-gray-800 text-white px-3 py-1.5 rounded-lg hover:bg-gray-700 transition text-xs flex items-center gap-1.5 mx-auto">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Re-download
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($receipts->hasPages())
            <div class="flex items-center justify-center gap-4 mt-5">
                @if($receipts->onFirstPage())
                    <span class="bg-[#FFB8B8] text-white px-4 py-2 rounded-lg flex items-center gap-2 cursor-not-allowed opacity-60 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Prev
                    </span>
                @else
                    <a href="{{ $receipts->previousPageUrl() }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-[#ff4040] transition flex items-center gap-2 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Prev
                    </a>
                @endif
                <span class="text-gray-600 text-sm">Page {{ $receipts->currentPage() }} of {{ $receipts->lastPage() }}</span>
                @if($receipts->hasMorePages())
                    <a href="{{ $receipts->nextPageUrl() }}" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-[#ff4040] transition flex items-center gap-2 text-sm">
                        Next
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                @else
                    <span class="bg-[#FFB8B8] text-white px-4 py-2 rounded-lg flex items-center gap-2 cursor-not-allowed opacity-60 text-sm">
                        Next
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </span>
                @endif
            </div>
            @endif
        @endif
    </div>
</div>

{{-- ═══════════════════════════ HIDDEN RE-DOWNLOAD RECEIPT ═══════════════════════════ --}}
{{-- Off-screen receipt DOM used only for re-download rendering --}}
<div id="redownloadCapture"
     style="position:absolute;left:-9999px;top:0;width:8.5in;background:white;font-family:'IBM Plex Mono',monospace;">
</div>

{{-- ═══════════════════════════ JAVASCRIPT ═══════════════════════════ --}}
<script>
    let currentFuels = [];
    let currentDepartureId = null;

    window.addEventListener('DOMContentLoaded', function () {
        fetchNextReceiptNumber();
    });

    function fetchNextReceiptNumber() {
        fetch('{{ route("super_admin.br-receipt.next-number") }}')
            .then(res => res.json())
            .then(data => {
                document.getElementById('f_receipt_no').value = data.receipt_no;
            })
            .catch(err => console.error('Failed to fetch receipt number:', err));
    }

    // ── 1. Select departure ──────────────────────────────────────────────────
    document.getElementById('departureSelect').addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        if (!opt.value) {
            document.getElementById('formSection').classList.add('hidden');
            document.getElementById('previewSection').classList.add('hidden');
            return;
        }

        const data = JSON.parse(opt.dataset.payload);
        currentFuels = data.fuels;
        currentDepartureId = data.id;

        document.getElementById('f_tanker').value = data.tanker_number;
        document.getElementById('f_driver').value = data.driver;
        document.getElementById('f_date').value   = data.departure_date;

        const container = document.getElementById('fuelFormRows');
        container.innerHTML = '';

        data.fuels.forEach((fuel, idx) => {
            const liters         = parseFloat(fuel.liters)          || 0;
            const methanolLiters = parseFloat(fuel.methanol_liters) || 0;
            const totalLiters    = liters + methanolLiters;

            const row = document.createElement('div');
            row.className = 'grid grid-cols-3 gap-3 items-end p-3 bg-gray-50 rounded-lg border border-gray-200';
            row.innerHTML = `
                <div>
                    <div class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">${fuel.fuel_type}</div>
                    <div class="text-sm font-mono text-gray-700">${totalLiters.toLocaleString('en', { minimumFractionDigits: 2 })} L</div>
                    ${methanolLiters > 0 ? `<div class="text-xs text-gray-400 mt-0.5 font-mono">${liters.toLocaleString('en', { minimumFractionDigits: 2 })} L fuel + ${methanolLiters.toLocaleString('en', { minimumFractionDigits: 2 })} L methanol</div>` : ''}
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Unit Price (₱)</label>
                    <input type="number" step="0.01" min="0" id="price_${idx}" placeholder="0.00"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Remarks <span class="text-gray-400">(optional)</span></label>
                    <input type="text" id="remarks_${idx}" placeholder="e.g. Price — up"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
                </div>
            `;
            container.appendChild(row);
        });

        document.getElementById('formSection').classList.remove('hidden');
        document.getElementById('previewSection').classList.add('hidden');
    });

    // ── 2. Build preview ─────────────────────────────────────────────────────
    function buildPreview() {
        const receiptNo    = document.getElementById('f_receipt_no').value.trim()   || '—';
        const deliveredTo  = document.getElementById('f_delivered_to').value.trim() || '—';
        const address      = document.getElementById('f_address').value.trim()      || '—';
        const tin          = document.getElementById('f_tin').value.trim()          || '—';
        const terms        = document.getElementById('f_terms').value.trim()        || '—';
        const tankerDriver = `${document.getElementById('f_tanker').value} / ${document.getElementById('f_driver').value}`;
        const date         = document.getElementById('f_date').value;

        let grandTotal = 0;
        let rowsHtml   = '';

        currentFuels.forEach((fuel, idx) => {
            const price          = parseFloat(document.getElementById(`price_${idx}`)?.value) || 0;
            const remarks        = document.getElementById(`remarks_${idx}`)?.value.trim()    || '';
            const liters         = parseFloat(fuel.liters)          || 0;
            const methanolLiters = parseFloat(fuel.methanol_liters) || 0;
            const totalLiters    = liters + methanolLiters;
            const amount         = totalLiters * price;
            grandTotal          += amount;

            rowsHtml += buildFuelRow(totalLiters, fuel.fuel_type, price, amount, remarks);
        });

        const padRows = Math.max(0, 5 - currentFuels.length);
        for (let i = 0; i < padRows; i++) {
            rowsHtml += `<tr><td class="r-cell">&nbsp;</td><td class="r-cell"></td><td class="r-cell"></td><td class="r-cell"></td><td class="r-cell"></td><td class="r-cell"></td></tr>`;
        }

        const totalFormatted = grandTotal > 0 ? '₱ ' + grandTotal.toLocaleString('en', { minimumFractionDigits: 2 }) : '—';

        ['1','2'].forEach(n => {
            document.getElementById(`p${n}_receipt_no`).textContent    = `Nº ${receiptNo}`;
            document.getElementById(`p${n}_delivered_to`).textContent  = deliveredTo;
            document.getElementById(`p${n}_tanker_driver`).textContent = tankerDriver;
            document.getElementById(`p${n}_terms`).textContent         = terms;
            document.getElementById(`p${n}_date`).textContent          = date;
            document.getElementById(`p${n}_address`).textContent       = address;
            document.getElementById(`p${n}_tin`).textContent           = tin;
            document.getElementById(`p${n}_fuelRows`).innerHTML        = rowsHtml;
            document.getElementById(`p${n}_grand_total`).textContent   = totalFormatted;
        });

        document.getElementById('previewSection').classList.remove('hidden');
        document.getElementById('previewSection').scrollIntoView({ behavior: 'smooth' });
    }

    function buildFuelRow(totalLiters, fuelType, price, amount, remarks) {
        return `
            <tr>
                <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">${totalLiters.toLocaleString('en', { minimumFractionDigits: 2 })}</td>
                <td class="r-cell" style="text-align:center;">Liters</td>
                <td class="r-cell" style="text-align:center;font-weight:700;letter-spacing:.03em;">${fuelType.toUpperCase()}</td>
                <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">${price > 0 ? price.toFixed(2) : '—'}</td>
                <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;font-weight:600;">${price > 0 ? amount.toLocaleString('en', { minimumFractionDigits: 2 }) : '—'}</td>
                <td class="r-cell" style="font-size:6pt;color:#555;">${remarks}</td>
            </tr>`;
    }

    // ── 3. Export PDF + save + remove from dropdown ───────────────────────────
    function exportPdf() {
        const btn       = document.getElementById('exportBtn');
        const receiptNo = document.getElementById('f_receipt_no').value.trim() || 'receipt';
        const element   = document.getElementById('receiptCapture');

        btn.disabled = true;
        btn.textContent = 'Saving...';

        saveReceiptToDatabase()
            .then(data => {
                const opt = {
                    margin:      [0, 0, 0, 0],
                    filename:    `BR-Receipt-${receiptNo}.pdf`,
                    image:       { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2, useCORS: true, letterRendering: true },
                    jsPDF:       { unit: 'in', format: 'letter', orientation: 'portrait' },
                    pagebreak:   { mode: ['avoid-all', 'css'] }
                };
                element.style.width = '8.5in';
                btn.textContent = 'Exporting...';
                return html2pdf().set(opt).from(element).save().then(() => data);
            })
            .then(data => {
                element.style.width = '';

                // Remove the used departure from the dropdown
                const select = document.getElementById('departureSelect');
                const optToRemove = select.querySelector(`option[value="${currentDepartureId}"]`);
                if (optToRemove) optToRemove.remove();

                // Reset the form
                select.value = '';
                document.getElementById('formSection').classList.add('hidden');
                document.getElementById('previewSection').classList.add('hidden');
                currentFuels = [];
                currentDepartureId = null;

                // Add to history table immediately (prepend)
                addToHistoryTable(data.receipt);

                fetchNextReceiptNumber();

                btn.disabled = false;
                btn.innerHTML = '🖨 Export PDF';
            })
            .catch(err => {
                alert('Error: ' + err.message);
                console.error(err);
                btn.disabled = false;
                btn.innerHTML = '🖨 Export PDF';
            });
    }

    // ── Add new receipt row to history table dynamically ─────────────────────
    function addToHistoryTable(receipt) {
        const tbody = document.querySelector('#receiptHistoryTable tbody');
        if (!tbody) return; // table not yet rendered (empty state), reload instead
        window.location.reload(); // simplest — reload to reflect new history row
    }

    // ── Save to DB ────────────────────────────────────────────────────────────
    function saveReceiptToDatabase() {
        const receiptNo   = document.getElementById('f_receipt_no').value.trim();
        const deliveredTo = document.getElementById('f_delivered_to').value.trim();
        const address     = document.getElementById('f_address').value.trim();
        const tin         = document.getElementById('f_tin').value.trim();
        const terms       = document.getElementById('f_terms').value.trim();

        let grandTotal = 0;
        const fuels = currentFuels.map((fuel, idx) => {
            const price          = parseFloat(document.getElementById(`price_${idx}`)?.value) || 0;
            const remarks        = document.getElementById(`remarks_${idx}`)?.value.trim()    || '';
            const liters         = parseFloat(fuel.liters)          || 0;
            const methanolLiters = parseFloat(fuel.methanol_liters) || 0;
            const totalLiters    = liters + methanolLiters;
            const amount         = totalLiters * price;
            grandTotal          += amount;
            return { fuel_type: fuel.fuel_type, liters: totalLiters, unit_price: price, amount, remarks };
        });

        const payload = {
            tanker_departure_id: currentDepartureId,
            receipt_no:   receiptNo,
            delivered_to: deliveredTo,
            address, tin, terms,
            grand_total:  grandTotal,
            fuels
        };

        return fetch('{{ route("super_admin.br-receipt.store") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) throw new Error(data.message || 'Failed to save receipt');
            return data;
        });
    }

    // ── Re-download an existing receipt from history ──────────────────────────
    function redownloadReceipt(id) {
        const btn = event.currentTarget;
        btn.disabled = true;
        btn.textContent = 'Loading...';

        fetch(`{{ url('/super_admin/br-receipt') }}/${id}`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(res => res.ok ? res.json() : Promise.reject(res))
        .then(r => {
            const receipt = r.receipt;
            const tankerDriver = `${receipt.tanker_number} / ${receipt.driver}`;

            // Build rows HTML
            let rowsHtml = '';
            let grandTotal = 0;
            receipt.fuels.forEach(fuel => {
                const amount = parseFloat(fuel.liters) * parseFloat(fuel.unit_price);
                grandTotal  += amount;
                rowsHtml    += buildFuelRow(
                    parseFloat(fuel.liters),
                    fuel.fuel_type,
                    parseFloat(fuel.unit_price),
                    amount,
                    fuel.remarks || ''
                );
            });
            const padRows = Math.max(0, 5 - receipt.fuels.length);
            for (let i = 0; i < padRows; i++) {
                rowsHtml += `<tr><td class="r-cell">&nbsp;</td><td class="r-cell"></td><td class="r-cell"></td><td class="r-cell"></td><td class="r-cell"></td><td class="r-cell"></td></tr>`;
            }
            const totalFormatted = '₱ ' + grandTotal.toLocaleString('en', { minimumFractionDigits: 2 });

            // Inject into the off-screen capture div
            const capture = document.getElementById('redownloadCapture');
            capture.innerHTML = buildReceiptHtml(receipt, tankerDriver, rowsHtml, totalFormatted);

            const opt = {
                margin:      [0, 0, 0, 0],
                filename:    `BR-Receipt-${receipt.receipt_no}.pdf`,
                image:       { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true, letterRendering: true },
                jsPDF:       { unit: 'in', format: 'letter', orientation: 'portrait' },
                pagebreak:   { mode: ['avoid-all', 'css'] }
            };

            return html2pdf().set(opt).from(capture).save();
        })
        .then(() => {
            btn.disabled = false;
            btn.innerHTML = '⬇ Re-download';
        })
        .catch(err => {
            console.error(err);
            alert('Failed to load receipt data.');
            btn.disabled = false;
            btn.innerHTML = '⬇ Re-download';
        });
    }

    // ── Build full receipt HTML for re-download (both copies) ────────────────
    function buildReceiptHtml(r, tankerDriver, rowsHtml, totalFormatted) {
        const copy = (label) => `
        <div style="border:1pt solid #ccc;padding:0;">
            <div style="border-bottom:1.5pt solid black;padding:5pt 7pt 4pt;text-align:center;">
                <div style="font-family:'IBM Plex Sans',sans-serif;font-size:11.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.02em;line-height:1.1;">A.C. Ang Fuel Distribution Services</div>
                <div style="font-size:6.8pt;margin-top:1.5pt;color:#333;">Aaron Carl Agustin Ang — Prop. | Guimba, Nueva Ecija</div>
                <div style="font-size:6.5pt;color:#555;">Cell Nos.: 0912-626-9364 · 09088856506</div>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;background:black;color:white;padding:3pt 8pt;">
                <span style="font-family:'IBM Plex Mono',monospace;font-size:6.5pt;font-weight:600;letter-spacing:.1em;text-transform:uppercase;">Delivery Receipt</span>
                <span style="font-family:'IBM Plex Mono',monospace;font-size:9.5pt;font-weight:700;">Nº ${r.receipt_no}</span>
            </div>
            <div style="display:grid;grid-template-columns:2fr 1.3fr 0.85fr 0.85fr;border-bottom:1pt solid black;">
                <div style="padding:3pt 6pt;border-right:1pt solid black;">
                    <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;color:#555;">Delivered To</div>
                    <div style="border-bottom:0.5pt solid #999;min-height:11pt;font-size:7pt;font-family:'IBM Plex Mono',monospace;">${r.delivered_to || ''}</div>
                </div>
                <div style="padding:3pt 6pt;border-right:1pt solid black;">
                    <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;color:#555;">Tanker Plate / Driver</div>
                    <div style="border-bottom:0.5pt solid #999;min-height:11pt;font-size:7pt;font-family:'IBM Plex Mono',monospace;">${tankerDriver}</div>
                </div>
                <div style="padding:3pt 6pt;border-right:1pt solid black;">
                    <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;color:#555;">Terms</div>
                    <div style="border-bottom:0.5pt solid #999;min-height:11pt;font-size:7pt;font-family:'IBM Plex Mono',monospace;">${r.terms || ''}</div>
                </div>
                <div style="padding:3pt 6pt;">
                    <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;color:#555;">Date</div>
                    <div style="border-bottom:0.5pt solid #999;min-height:11pt;font-size:7pt;font-family:'IBM Plex Mono',monospace;">${r.departure_date || ''}</div>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1.6fr 1fr;border-bottom:1pt solid black;">
                <div style="padding:3pt 6pt;border-right:1pt solid black;">
                    <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;color:#555;">Address</div>
                    <div style="border-bottom:0.5pt solid #999;min-height:11pt;font-size:7pt;font-family:'IBM Plex Mono',monospace;">${r.address || ''}</div>
                </div>
                <div style="padding:3pt 6pt;">
                    <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;color:#555;">TIN</div>
                    <div style="border-bottom:0.5pt solid #999;min-height:11pt;font-size:7pt;font-family:'IBM Plex Mono',monospace;">${r.tin || ''}</div>
                </div>
            </div>
            <div style="border-bottom:1pt solid black;">
                <table style="width:100%;border-collapse:collapse;font-size:7pt;">
                    <thead><tr>
                        <th class="r-cell-hdr" style="width:12%;text-align:right;">QTY</th>
                        <th class="r-cell-hdr" style="width:9%;text-align:center;">Unit</th>
                        <th class="r-cell-hdr" style="width:17%;text-align:center;">Product</th>
                        <th class="r-cell-hdr" style="width:17%;text-align:right;">Unit Price</th>
                        <th class="r-cell-hdr" style="width:22%;text-align:right;">Amount</th>
                        <th class="r-cell-hdr" style="width:23%;text-align:left;">Remarks</th>
                    </tr></thead>
                    <tbody>${rowsHtml}</tbody>
                    <tfoot><tr style="background:black;color:white;">
                        <td colspan="3" style="padding:3pt 5pt;font-family:'IBM Plex Mono',monospace;font-size:6.5pt;font-weight:700;text-align:right;border:0.5pt solid #444;">GRAND TOTAL</td>
                        <td colspan="2" style="padding:3pt 5pt;font-family:'IBM Plex Mono',monospace;font-size:9pt;font-weight:700;text-align:right;border:0.5pt solid #444;">${totalFormatted}</td>
                        <td style="border:0.5pt solid #444;"></td>
                    </tr></tfoot>
                </table>
            </div>
            <div style="padding:4pt 7pt 3pt;border-bottom:1pt solid black;font-size:5.4pt;line-height:1.4;color:#333;">
                <span style="font-weight:700;">WAIVER OF GOOD QUALITY (MAAYOS NA KALIDAD) AND CORRECT QUANTITY (TAMANG SUKAT):</span>
                BY SIGNING IN THE DELIVERY RECEIPT, RECEIVER ACKNOWLEDGE THAT PRODUCT PASSED GOOD QUALITY (MAAYOS NA KALIDAD)
                AND CORRECT QUANTITY (TAMANG SUKAT). ONCE SIGNED THE CUSTOMER & AUTHORIZED RECEIVER SHALL BE RESPONSIBLE
                FOR THE PRODUCT AND THIS OFFICE WILL NOT ENTERTAIN ANY CLAIM OF DAMAGE.
            </div>
            <div style="display:flex;">
                <div style="flex:1;border-right:1pt solid black;padding:4pt 7pt;">
                    <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;margin-bottom:2pt;">Receiver's Signature (Over Printed Name)</div>
                    <div style="border-bottom:0.5pt solid #555;min-height:18pt;"></div>
                    <div style="font-size:5pt;color:#666;margin-top:2pt;font-style:italic;">Received the above goods and supplies in good order and condition.</div>
                </div>
                <div style="flex:1;padding:4pt 7pt;">
                    <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;margin-bottom:2pt;">Authorized Receiver's Signature (Over Printed Name)</div>
                    <div style="border-bottom:0.5pt solid #555;min-height:18pt;"></div>
                    <div style="font-size:5pt;color:#666;margin-top:2pt;font-style:italic;">(Authorized Receiver Only)</div>
                </div>
            </div>
            <div style="border-top:0.5pt solid #ddd;padding:2pt 7pt;text-align:right;font-family:'IBM Plex Mono',monospace;font-size:5pt;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#aaa;">${label}</div>
        </div>`;

        return copy('OFFICE COPY')
             + `<hr style="border:none;border-top:1.5pt dashed #aaa;margin:8pt 0;">`
             + copy('CUSTOMER COPY');
    }
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