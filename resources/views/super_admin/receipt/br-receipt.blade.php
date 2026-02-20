@extends('admin.layout.app')

@section('title', 'BR Receipt Builder')

@section('content')

{{-- html2pdf CDN --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<style>
    /* ── receipt print styles ───────────────────────────── */
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
    /* hide preview wrapper scrollbar flicker */
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

            {{-- Read-only from DB --}}
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

            {{-- Manual fields --}}
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
                <input id="f_tin" type="text" placeholder="e.g. 123-456-789-000" readonly value="640-196-637-00000"
                       class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-700 cursor-not-allowed">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Terms</label>
                <input id="f_terms" type="text" placeholder="e.g. A 50% down"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
            </div>
        </div>

        {{-- ── Fuel rows (unit price + remarks) ── --}}
        <div class="mt-5">
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Fuel Details — Unit Price &amp; Remarks</label>
            <div id="fuelFormRows" class="space-y-2"></div>
        </div>

        {{-- Preview button --}}
        <div class="mt-5 flex justify-end">
            <button id="previewBtn"
                    onclick="buildPreview()"
                    class="bg-[#FF5757] text-white px-6 py-2.5 rounded-full text-sm font-semibold hover:bg-[#e04444] transition">
                Preview Receipt →
            </button>
        </div>
    </div>
</div>

{{-- ═══════════════════════════ STEP 3 — PREVIEW + EXPORT ═══════════════════════════ --}}
<div id="previewSection" class="hidden">

    {{-- Toolbar --}}
    <div class="flex items-center justify-between mb-3">
        <h3 class="font-semibold text-sm">3 — Preview</h3>
        <div class="flex gap-3">
            <button onclick="document.getElementById('previewSection').classList.add('hidden')"
                    class="text-xs px-4 py-2 border border-gray-300 rounded-full hover:bg-gray-50 transition">
                ← Edit Form
            </button>
            <button onclick="exportPdf()"
                    class="bg-black text-white text-xs px-5 py-2 rounded-full font-semibold hover:bg-gray-800 transition flex items-center gap-2">
                🖨 Export PDF
            </button>
        </div>
    </div>

    {{-- Receipt preview wrapper (scaled for screen) --}}
    <div id="previewWrapper"
         class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm bg-gray-100 p-4">
        {{-- The actual receipt DOM that html2pdf will capture --}}
        <div id="receiptCapture"
             style="width:8.5in;margin:0 auto;background:white;font-family:'IBM Plex Mono',monospace;">

            {{-- ══ COPY 1 ══ --}}
            <div class="receipt-block" id="copy1" style="border:1pt solid #ccc;padding:0;">
                {{-- Header --}}
                <div style="border-bottom:1.5pt solid black;padding:5pt 7pt 4pt;text-align:center;">
                    <div style="font-family:'IBM Plex Sans',sans-serif;font-size:11.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.02em;line-height:1.1;">
                        A.C. Ang Fuel Distribution Services
                    </div>
                    <div style="font-size:6.8pt;margin-top:1.5pt;color:#333;">Aaron Carl Agustin Ang — Prop. &nbsp;|&nbsp; Guimba, Nueva Ecija</div>
                    <div style="font-size:6.5pt;color:#555;">Cell Nos.: 0912-626-9364 &nbsp;·&nbsp; 09088856506</div>
                </div>
                {{-- Receipt No bar --}}
                <div style="display:flex;justify-content:space-between;align-items:center;background:black;color:white;padding:3pt 8pt;">
                    <span style="font-family:'IBM Plex Mono',monospace;font-size:6.5pt;font-weight:600;letter-spacing:.1em;text-transform:uppercase;">Delivery Receipt</span>
                    <span id="p1_receipt_no" style="font-family:'IBM Plex Mono',monospace;font-size:9.5pt;font-weight:700;letter-spacing:.03em;"></span>
                </div>
                {{-- Info rows --}}
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
                {{-- Products table --}}
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
                {{-- Waiver --}}
                <div style="padding:4pt 7pt 3pt;border-bottom:1pt solid black;font-size:5.4pt;line-height:1.4;color:#333;">
                    <span style="font-weight:700;">WAIVER OF GOOD QUALITY (MAAYOS NA KALIDAD) AND CORRECT QUANTITY (TAMANG SUKAT):</span>
                    BY SIGNING IN THE DELIVERY RECEIPT, RECEIVER ACKNOWLEDGE THAT PRODUCT PASSED GOOD QUALITY (MAAYOS NA KALIDAD)
                    AND CORRECT QUANTITY (TAMANG SUKAT). ONCE SIGNED THE CUSTOMER &amp; AUTHORIZED RECEIVER SHALL BE RESPONSIBLE
                    FOR THE PRODUCT AND THIS OFFICE WILL NOT ENTERTAIN ANY CLAIM OF DAMAGE.
                </div>
                {{-- Signatures --}}
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

{{-- ═══════════════════════════ JAVASCRIPT ═══════════════════════════ --}}
{{-- Replace only the JAVASCRIPT section at the bottom of your br-receipt.blade.php --}}

<script>
    // ── State ─────────────────────────────────────────────────────────────────
    let currentFuels = [];
    let currentDepartureId = null;

    // ── Auto-load next receipt number on page load ───────────────────────────
    window.addEventListener('DOMContentLoaded', function () {
        fetchNextReceiptNumber();
    });

    function fetchNextReceiptNumber() {
        fetch('{{ route("admin.br-receipt.next-number") }}')
            .then(res => res.json())
            .then(data => {
                document.getElementById('f_receipt_no').value = data.receipt_no;
            })
            .catch(err => console.error('Failed to fetch receipt number:', err));
    }

    // ── 1. Select departure → pre-fill form ──────────────────────────────────
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

        // Fill read-only fields
        document.getElementById('f_tanker').value = data.tanker_number;
        document.getElementById('f_driver').value = data.driver;
        document.getElementById('f_date').value   = data.departure_date;

        // Build fuel input rows (unit price + remarks)
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
                    <div class="text-sm font-mono text-gray-700">
                        ${totalLiters.toLocaleString('en', { minimumFractionDigits: 2 })} L
                    </div>
                    ${methanolLiters > 0 ? `
                    <div class="text-xs text-gray-400 mt-0.5 font-mono">
                        ${liters.toLocaleString('en', { minimumFractionDigits: 2 })} L fuel
                        + ${methanolLiters.toLocaleString('en', { minimumFractionDigits: 2 })} L methanol
                    </div>` : ''}
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Unit Price (₱)</label>
                    <input type="number" step="0.01" min="0"
                           id="price_${idx}"
                           placeholder="0.00"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Remarks <span class="text-gray-400">(optional)</span></label>
                    <input type="text"
                           id="remarks_${idx}"
                           placeholder="e.g. Price — up"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#FF5757]">
                </div>
            `;
            container.appendChild(row);
        });

        document.getElementById('formSection').classList.remove('hidden');
        document.getElementById('previewSection').classList.add('hidden');
    });

    // ── 2. Build preview ──────────────────────────────────────────────────────
    function buildPreview() {
        const receiptNo    = document.getElementById('f_receipt_no').value.trim()   || '—';
        const deliveredTo  = document.getElementById('f_delivered_to').value.trim() || '—';
        const address      = document.getElementById('f_address').value.trim()      || '—';
        const tin          = document.getElementById('f_tin').value.trim()          || '—';
        const terms        = document.getElementById('f_terms').value.trim()        || '—';
        const tankerDriver = `${document.getElementById('f_tanker').value} / ${document.getElementById('f_driver').value}`;
        const date         = document.getElementById('f_date').value;

        // Compute fuel rows + grand total
        let grandTotal = 0;
        let rowsHtml   = '';

        currentFuels.forEach((fuel, idx) => {
            const price          = parseFloat(document.getElementById(`price_${idx}`)?.value)   || 0;
            const remarks        = document.getElementById(`remarks_${idx}`)?.value.trim()      || '';
            const liters         = parseFloat(fuel.liters)          || 0;
            const methanolLiters = parseFloat(fuel.methanol_liters) || 0;
            const totalLiters    = liters + methanolLiters;
            const amount         = totalLiters * price;
            grandTotal          += amount;

            rowsHtml += `
                <tr>
                    <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">
                        ${totalLiters.toLocaleString('en', { minimumFractionDigits: 2 })}
                    </td>
                    <td class="r-cell" style="text-align:center;">Liters</td>
                    <td class="r-cell" style="text-align:center;font-weight:700;letter-spacing:.03em;">
                        ${fuel.fuel_type.toUpperCase()}
                    </td>
                    <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">
                        ${price > 0 ? price.toFixed(2) : '—'}
                    </td>
                    <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;font-weight:600;">
                        ${price > 0 ? amount.toLocaleString('en', { minimumFractionDigits: 2 }) : '—'}
                    </td>
                    <td class="r-cell" style="font-size:6pt;color:#555;">${remarks}</td>
                </tr>`;
        });

        // Pad to at least 5 rows
        const padRows = Math.max(0, 5 - currentFuels.length);
        for (let i = 0; i < padRows; i++) {
            rowsHtml += `<tr>
                <td class="r-cell">&nbsp;</td>
                <td class="r-cell"></td>
                <td class="r-cell"></td>
                <td class="r-cell"></td>
                <td class="r-cell"></td>
                <td class="r-cell"></td>
            </tr>`;
        }

        const totalFormatted = grandTotal > 0
            ? '₱ ' + grandTotal.toLocaleString('en', { minimumFractionDigits: 2 })
            : '—';

        // Fill both copies
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

    // ── 3. Export PDF via html2pdf + Save to DB ───────────────────────────────
    function exportPdf() {
        const receiptNo = document.getElementById('f_receipt_no').value.trim() || 'receipt';
        const element   = document.getElementById('receiptCapture');

        // First, save to database
        saveReceiptToDatabase()
            .then(() => {
                // Then export PDF
                const opt = {
                    margin:       [0, 0, 0, 0],
                    filename:     `BR-Receipt-${receiptNo}.pdf`,
                    image:        { type: 'jpeg', quality: 0.98 },
                    html2canvas:  { scale: 2, useCORS: true, letterRendering: true },
                    jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' },
                    pagebreak:    { mode: ['avoid-all', 'css'] }
                };

                element.style.width = '8.5in';
                return html2pdf().set(opt).from(element).save();
            })
            .then(() => {
                element.style.width = '';
                alert('Receipt saved and exported successfully!');
                // Refresh to get next receipt number
                fetchNextReceiptNumber();
            })
            .catch(err => {
                alert('Error: ' + err.message);
                console.error(err);
            });
    }

    // ── Save receipt data to database ─────────────────────────────────────────
    function saveReceiptToDatabase() {
        // Gather form data
        const receiptNo   = document.getElementById('f_receipt_no').value.trim();
        const deliveredTo = document.getElementById('f_delivered_to').value.trim();
        const address     = document.getElementById('f_address').value.trim();
        const tin         = document.getElementById('f_tin').value.trim();
        const terms       = document.getElementById('f_terms').value.trim();

        // Compute fuels and grand total
        let grandTotal = 0;
        const fuels = currentFuels.map((fuel, idx) => {
            const price          = parseFloat(document.getElementById(`price_${idx}`)?.value) || 0;
            const remarks        = document.getElementById(`remarks_${idx}`)?.value.trim() || '';
            const liters         = parseFloat(fuel.liters) || 0;
            const methanolLiters = parseFloat(fuel.methanol_liters) || 0;
            const totalLiters    = liters + methanolLiters;
            const amount         = totalLiters * price;
            grandTotal          += amount;

            return {
                fuel_type: fuel.fuel_type,
                liters: totalLiters,
                unit_price: price,
                amount: amount,
                remarks: remarks
            };
        });

        const payload = {
            tanker_departure_id: currentDepartureId,
            receipt_no: receiptNo,
            delivered_to: deliveredTo,
            address: address,
            tin: tin,
            terms: terms,
            grand_total: grandTotal,
            fuels: fuels
        };

        return fetch('{{ route("admin.br-receipt.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                throw new Error(data.message || 'Failed to save receipt');
            }
            return data;
        });
    }
</script>

@endsection