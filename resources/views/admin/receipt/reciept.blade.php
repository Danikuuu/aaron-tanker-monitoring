@extends('admin.layout.app')

@section('title', 'BR Receipt')

@section('content')
<!-- SCREEN CONTROLS -->
<div class="screen-only" style="max-width:8.5in;margin:0 auto;padding:14px 0 10px;display:flex;align-items:center;gap:10px;">
    <span style="font-family:'IBM Plex Mono',monospace;font-size:.72rem;flex:1;color:#333;">
        Print Preview — Receipt No. 2501 &nbsp;·&nbsp; 2 copies on short bond paper (8.5 × 11 in)
    </span>
    <button onclick="window.print()"
            style="font-family:'IBM Plex Mono',monospace;font-size:.7rem;letter-spacing:.08em;text-transform:uppercase;
                   background:black;color:white;border:none;padding:8px 22px;cursor:pointer;">
        🖨 Print / Save PDF
    </button>
</div>

<div class="page-wrapper">
<div class="bond-page">

<!-- ════════════════════════ COPY 1 — OFFICE COPY ════════════════════════ -->
<div class="receipt-block">

    <!-- HEADER -->
    <div style="border-bottom:1.5pt solid black;padding:5pt 7pt 4pt;text-align:center;">
        <div style="font-family:'IBM Plex Sans',sans-serif;font-size:11.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.02em;line-height:1.1;">
            A.C. Ang Fuel Distribution Services
        </div>
        <div style="font-size:6.8pt;margin-top:1.5pt;color:#333;">
            Aaron Carl Agustin Ang — Prop. &nbsp;|&nbsp; Guimba, Nueva Ecija
        </div>
        <div style="font-size:6.5pt;color:#555;">
            Cell Nos.: 0912-626-9364 &nbsp;·&nbsp; 09088856506
        </div>
    </div>

    <!-- RECEIPT NO BAR -->
    <div style="display:flex;justify-content:space-between;align-items:center;background:black;color:white;padding:3pt 8pt;">
        <span style="font-family:'IBM Plex Mono',monospace;font-size:6.5pt;font-weight:600;letter-spacing:.1em;text-transform:uppercase;">
            Delivery Receipt
        </span>
        <span style="font-family:'IBM Plex Mono',monospace;font-size:9.5pt;font-weight:700;letter-spacing:.03em;">
            Nº 2501
        </span>
    </div>

    <!-- INFO ROWS -->
    <div style="display:grid;grid-template-columns:2fr 1.3fr 0.85fr 0.85fr;border-bottom:1pt solid black;">
        <div style="padding:3pt 6pt;border-right:1pt solid black;">
            <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#555;">Delivered To</div>
            <div style="border-bottom:0.5pt solid #999;min-height:11pt;padding-bottom:1pt;font-size:7pt;font-family:'IBM Plex Mono',monospace;">Sta. Ignacia Petron Station</div>
        </div>
        <div style="padding:3pt 6pt;border-right:1pt solid black;">
            <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#555;">Tanker Plate / Driver</div>
            <div style="border-bottom:0.5pt solid #999;min-height:11pt;padding-bottom:1pt;font-size:7pt;font-family:'IBM Plex Mono',monospace;">ABC-1234 / Juan dela Cruz</div>
        </div>
        <div style="padding:3pt 6pt;border-right:1pt solid black;">
            <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#555;">Terms</div>
            <div style="border-bottom:0.5pt solid #999;min-height:11pt;padding-bottom:1pt;font-size:7pt;font-family:'IBM Plex Mono',monospace;">A 50% down</div>
        </div>
        <div style="padding:3pt 6pt;">
            <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#555;">Date</div>
            <div style="border-bottom:0.5pt solid #999;min-height:11pt;padding-bottom:1pt;font-size:7pt;font-family:'IBM Plex Mono',monospace;">01/19/2025</div>
        </div>
    </div>
    <div style="display:grid;grid-template-columns:1.6fr 1fr;border-bottom:1pt solid black;">
        <div style="padding:3pt 6pt;border-right:1pt solid black;">
            <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#555;">Address</div>
            <div style="border-bottom:0.5pt solid #999;min-height:11pt;padding-bottom:1pt;font-size:7pt;font-family:'IBM Plex Mono',monospace;">Sta. Ignacia, Tarlac</div>
        </div>
        <div style="padding:3pt 6pt;">
            <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#555;">TIN</div>
            <div style="border-bottom:0.5pt solid #999;min-height:11pt;padding-bottom:1pt;font-size:7pt;font-family:'IBM Plex Mono',monospace;">123-456-789-000</div>
        </div>
    </div>

    <!-- PRODUCTS TABLE -->
    <div style="border-bottom:1pt solid black;">
        <table style="width:100%;border-collapse:collapse;font-size:7pt;">
            <thead>
                <tr>
                    <th class="r-cell-hdr" style="width:11%;text-align:left;">QTY</th>
                    <th class="r-cell-hdr" style="width:9%;text-align:left;">Unit</th>
                    <th class="r-cell-hdr" style="width:17%;text-align:center;">Product</th>
                    <th class="r-cell-hdr" style="width:17%;text-align:right;">Unit Price</th>
                    <th class="r-cell-hdr" style="width:22%;text-align:right;">Amount</th>
                    <th class="r-cell-hdr" style="width:24%;text-align:left;">Remarks</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">2,001.00</td>
                    <td class="r-cell" style="text-align:center;">Liters</td>
                    <td class="r-cell" style="text-align:center;font-weight:700;letter-spacing:.03em;">DIESEL</td>
                    <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">47.00</td>
                    <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;font-weight:600;">94,047.00</td>
                    <td class="r-cell" style="font-size:6pt;color:#555;">Price — up</td>
                </tr>
                <tr>
                    <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">301.00</td>
                    <td class="r-cell" style="text-align:center;">Liters</td>
                    <td class="r-cell" style="text-align:center;font-weight:700;letter-spacing:.03em;">PREMIUM</td>
                    <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">49.80</td>
                    <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;font-weight:600;">14,989.80</td>
                    <td class="r-cell"></td>
                </tr>
                <tr>
                    <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">274.00</td>
                    <td class="r-cell" style="text-align:center;">Liters</td>
                    <td class="r-cell" style="text-align:center;font-weight:700;letter-spacing:.03em;">REGULAR</td>
                    <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">43.50</td>
                    <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;font-weight:600;">11,919.00</td>
                    <td class="r-cell"></td>
                </tr>
                <tr><td class="r-cell">&nbsp;</td><td class="r-cell"></td><td class="r-cell"></td><td class="r-cell"></td><td class="r-cell"></td><td class="r-cell"></td></tr>
                <tr><td class="r-cell">&nbsp;</td><td class="r-cell"></td><td class="r-cell"></td><td class="r-cell"></td><td class="r-cell"></td><td class="r-cell"></td></tr>
            </tbody>
            <tfoot>
                <tr style="background:black;color:white;">
                    <td colspan="3" style="padding:3pt 5pt;font-family:'IBM Plex Mono',monospace;font-size:6.5pt;font-weight:700;letter-spacing:.08em;text-align:right;border:0.5pt solid #444;">
                        GRAND TOTAL
                    </td>
                    <td colspan="2" style="padding:3pt 5pt;font-family:'IBM Plex Mono',monospace;font-size:9pt;font-weight:700;text-align:right;border:0.5pt solid #444;">
                        ₱ 292,000.00
                    </td>
                    <td style="border:0.5pt solid #444;"></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- PUMP READINGS -->
    <div style="background:black;color:white;padding:2.5pt 7pt;">
        <span style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;letter-spacing:.1em;text-transform:uppercase;">
            While Unloading — Pump Readings
        </span>
    </div>
    <div style="display:flex;border-bottom:1pt solid black;">
        <div style="flex:1;border-right:1pt solid black;padding:3pt 5pt;">
            <table style="width:100%;border-collapse:collapse;font-size:6.5pt;">
                <tr>
                    <td colspan="3" class="r-cell-hdr">Reading (Start from Front / Driver)</td>
                </tr>
                <tr>
                    <td class="r-cell-hdr" style="width:30%;text-align:left;"></td>
                    <td class="r-cell-hdr">PREM</td>
                    <td class="r-cell-hdr">REG</td>
                </tr>
                <tr>
                    <td class="r-cell" style="font-weight:700;font-size:6pt;">Before</td>
                    <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">125,430.00</td>
                    <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">87,210.00</td>
                </tr>
                <tr>
                    <td class="r-cell" style="font-weight:700;font-size:6pt;">After</td>
                    <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">125,731.00</td>
                    <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">87,484.00</td>
                </tr>
                <tr>
                    <td class="r-cell" style="font-weight:700;font-size:6pt;">Total Sales</td>
                    <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">301.00</td>
                    <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">274.00</td>
                </tr>
            </table>
        </div>
        <div style="padding:4pt 6pt;min-width:92pt;font-size:6.5pt;font-family:'IBM Plex Mono',monospace;">
            <div style="font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#555;">Comp. No.</div>
            <div style="border-bottom:0.5pt solid #666;min-height:11pt;padding-bottom:1pt;margin-bottom:5pt;">C-01</div>
            <div style="font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#555;">Compartment No.</div>
            <div style="border-bottom:0.5pt solid #666;min-height:11pt;padding-bottom:1pt;">3</div>
            <div style="margin-top:3pt;font-size:5pt;color:#777;">(Start from front/driver)</div>
        </div>
    </div>

    <!-- DIPSTICK -->
    <div style="background:black;color:white;padding:2.5pt 7pt;">
        <span style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;letter-spacing:.1em;text-transform:uppercase;">
            Dipstick Readings
        </span>
    </div>
    <div style="border-bottom:1pt solid black;">
        <table style="width:100%;border-collapse:collapse;font-size:6.5pt;">
            <thead>
                <tr>
                    <th class="r-cell-hdr" style="width:32%;text-align:left;">Product</th>
                    <th class="r-cell-hdr">Diesel</th>
                    <th class="r-cell-hdr">Premium</th>
                    <th class="r-cell-hdr">Regular</th>
                </tr>
            </thead>
            <tbody>
                <tr><td class="r-cell" style="font-weight:700;font-size:6pt;">Starting Dipstick</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">8,266.00</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">1,800.00</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">2,100.00</td></tr>
                <tr><td class="r-cell" style="font-weight:700;font-size:6pt;">Add: Delivery (+)</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">2,001.00</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">301.00</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">274.00</td></tr>
                <tr><td class="r-cell" style="font-weight:700;font-size:6pt;">Sub Total</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">10,267.00</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">2,101.00</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">2,374.00</td></tr>
                <tr><td class="r-cell" style="font-weight:700;font-size:6pt;">Add: Pump Sales</td><td class="r-cell"></td><td class="r-cell"></td><td class="r-cell"></td></tr>
                <tr><td class="r-cell" style="font-weight:700;font-size:6pt;">Total</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">10,267.00</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">2,101.00</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">2,374.00</td></tr>
                <tr><td class="r-cell" style="font-weight:700;font-size:6pt;">Closing Dipstick</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">10,255.00</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">2,095.00</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">2,370.00</td></tr>
                <tr><td class="r-cell" style="font-weight:700;font-size:6pt;">Short / Over</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">(12.00)</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">(6.00)</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">(4.00)</td></tr>
            </tbody>
        </table>
    </div>

    <!-- WAIVER -->
    <div style="padding:4pt 7pt 3pt;border-bottom:1pt solid black;font-size:5.4pt;line-height:1.4;color:#333;">
        <span style="font-weight:700;">WAIVER OF GOOD QUALITY (MAAYOS NA KALIDAD) AND CORRECT QUANTITY (TAMANG SUKAT):</span>
        BY SIGNING IN THE DELIVERY RECEIPT, RECEIVER ACKNOWLEDGE THAT PRODUCT PASSED GOOD QUALITY (MAAYOS NA KALIDAD)
        AND CORRECT QUANTITY (TAMANG SUKAT). ONCE SIGNED THE CUSTOMER &amp; AUTHORIZED RECEIVER SHALL BE RESPONSIBLE
        FOR THE PRODUCT AND THIS OFFICE WILL NOT ENTERTAIN ANY CLAIM OF DAMAGE.
    </div>

    <!-- SIGNATURES -->
    <div style="display:flex;flex:1;">
        <div style="flex:1;border-right:1pt solid black;padding:4pt 7pt;">
            <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.04em;margin-bottom:2pt;">
                Receiver's Signature (Over Printed Name)
            </div>
            <div style="border-bottom:0.5pt solid #555;min-height:18pt;padding-bottom:2pt;"></div>
            <div style="font-size:5pt;color:#666;margin-top:2pt;font-style:italic;">
                Received the above goods and supplies in good order and condition.
            </div>
        </div>
        <div style="flex:1;padding:4pt 7pt;">
            <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.04em;margin-bottom:2pt;">
                Authorized Receiver's Signature (Over Printed Name)
            </div>
            <div style="border-bottom:0.5pt solid #555;min-height:18pt;padding-bottom:2pt;"></div>
            <div style="font-size:5pt;color:#666;margin-top:2pt;font-style:italic;">
                (Authorized Receiver Only)
            </div>
        </div>
    </div>

    <!-- COPY LABEL -->
    <div style="border-top:0.5pt solid #ddd;padding:2pt 7pt;text-align:right;font-family:'IBM Plex Mono',monospace;font-size:5pt;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#aaa;">
        OFFICE COPY
    </div>
</div>
<!-- end copy 1 -->

<hr class="cut-line">

<!-- ════════════════════════ COPY 2 — CUSTOMER COPY ════════════════════════ -->
<div class="receipt-block">

    <div style="border-bottom:1.5pt solid black;padding:5pt 7pt 4pt;text-align:center;">
        <div style="font-family:'IBM Plex Sans',sans-serif;font-size:11.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.02em;line-height:1.1;">
            A.C. Ang Fuel Distribution Services
        </div>
        <div style="font-size:6.8pt;margin-top:1.5pt;color:#333;">Aaron Carl Agustin Ang — Prop. &nbsp;|&nbsp; Guimba, Nueva Ecija</div>
        <div style="font-size:6.5pt;color:#555;">Cell Nos.: 0912-626-9364 &nbsp;·&nbsp; 09088856506</div>
    </div>

    <div style="display:flex;justify-content:space-between;align-items:center;background:black;color:white;padding:3pt 8pt;">
        <span style="font-family:'IBM Plex Mono',monospace;font-size:6.5pt;font-weight:600;letter-spacing:.1em;text-transform:uppercase;">Delivery Receipt</span>
        <span style="font-family:'IBM Plex Mono',monospace;font-size:9.5pt;font-weight:700;letter-spacing:.03em;">Nº 2501</span>
    </div>

    <div style="display:grid;grid-template-columns:2fr 1.3fr 0.85fr 0.85fr;border-bottom:1pt solid black;">
        <div style="padding:3pt 6pt;border-right:1pt solid black;">
            <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#555;">Delivered To</div>
            <div style="border-bottom:0.5pt solid #999;min-height:11pt;padding-bottom:1pt;font-size:7pt;font-family:'IBM Plex Mono',monospace;">Sta. Ignacia Petron Station</div>
        </div>
        <div style="padding:3pt 6pt;border-right:1pt solid black;">
            <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#555;">Tanker Plate / Driver</div>
            <div style="border-bottom:0.5pt solid #999;min-height:11pt;padding-bottom:1pt;font-size:7pt;font-family:'IBM Plex Mono',monospace;">ABC-1234 / Juan dela Cruz</div>
        </div>
        <div style="padding:3pt 6pt;border-right:1pt solid black;">
            <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#555;">Terms</div>
            <div style="border-bottom:0.5pt solid #999;min-height:11pt;padding-bottom:1pt;font-size:7pt;font-family:'IBM Plex Mono',monospace;">A 50% down</div>
        </div>
        <div style="padding:3pt 6pt;">
            <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#555;">Date</div>
            <div style="border-bottom:0.5pt solid #999;min-height:11pt;padding-bottom:1pt;font-size:7pt;font-family:'IBM Plex Mono',monospace;">01/19/2025</div>
        </div>
    </div>
    <div style="display:grid;grid-template-columns:1.6fr 1fr;border-bottom:1pt solid black;">
        <div style="padding:3pt 6pt;border-right:1pt solid black;">
            <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#555;">Address</div>
            <div style="border-bottom:0.5pt solid #999;min-height:11pt;padding-bottom:1pt;font-size:7pt;font-family:'IBM Plex Mono',monospace;">Sta. Ignacia, Tarlac</div>
        </div>
        <div style="padding:3pt 6pt;">
            <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#555;">TIN</div>
            <div style="border-bottom:0.5pt solid #999;min-height:11pt;padding-bottom:1pt;font-size:7pt;font-family:'IBM Plex Mono',monospace;">123-456-789-000</div>
        </div>
    </div>

    <div style="border-bottom:1pt solid black;">
        <table style="width:100%;border-collapse:collapse;font-size:7pt;">
            <thead>
                <tr>
                    <th class="r-cell-hdr" style="width:11%;text-align:left;">QTY</th>
                    <th class="r-cell-hdr" style="width:9%;text-align:left;">Unit</th>
                    <th class="r-cell-hdr" style="width:17%;text-align:center;">Product</th>
                    <th class="r-cell-hdr" style="width:17%;text-align:right;">Unit Price</th>
                    <th class="r-cell-hdr" style="width:22%;text-align:right;">Amount</th>
                    <th class="r-cell-hdr" style="width:24%;text-align:left;">Remarks</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">2,001.00</td>
                    <td class="r-cell" style="text-align:center;">Liters</td>
                    <td class="r-cell" style="text-align:center;font-weight:700;">DIESEL</td>
                    <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">47.00</td>
                    <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;font-weight:600;">94,047.00</td>
                    <td class="r-cell" style="font-size:6pt;color:#555;">Price — up</td>
                </tr>
                <tr>
                    <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">301.00</td>
                    <td class="r-cell" style="text-align:center;">Liters</td>
                    <td class="r-cell" style="text-align:center;font-weight:700;">PREMIUM</td>
                    <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">49.80</td>
                    <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;font-weight:600;">14,989.80</td>
                    <td class="r-cell"></td>
                </tr>
                <tr>
                    <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">274.00</td>
                    <td class="r-cell" style="text-align:center;">Liters</td>
                    <td class="r-cell" style="text-align:center;font-weight:700;">REGULAR</td>
                    <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">43.50</td>
                    <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;font-weight:600;">11,919.00</td>
                    <td class="r-cell"></td>
                </tr>
                <tr><td class="r-cell">&nbsp;</td><td class="r-cell"></td><td class="r-cell"></td><td class="r-cell"></td><td class="r-cell"></td><td class="r-cell"></td></tr>
                <tr><td class="r-cell">&nbsp;</td><td class="r-cell"></td><td class="r-cell"></td><td class="r-cell"></td><td class="r-cell"></td><td class="r-cell"></td></tr>
            </tbody>
            <tfoot>
                <tr style="background:black;color:white;">
                    <td colspan="3" style="padding:3pt 5pt;font-family:'IBM Plex Mono',monospace;font-size:6.5pt;font-weight:700;letter-spacing:.08em;text-align:right;border:0.5pt solid #444;">GRAND TOTAL</td>
                    <td colspan="2" style="padding:3pt 5pt;font-family:'IBM Plex Mono',monospace;font-size:9pt;font-weight:700;text-align:right;border:0.5pt solid #444;">₱ 292,000.00</td>
                    <td style="border:0.5pt solid #444;"></td>
                </tr>
            </tfoot>
        </table>
    </div>

    <div style="background:black;color:white;padding:2.5pt 7pt;">
        <span style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;letter-spacing:.1em;text-transform:uppercase;">While Unloading — Pump Readings</span>
    </div>
    <div style="display:flex;border-bottom:1pt solid black;">
        <div style="flex:1;border-right:1pt solid black;padding:3pt 5pt;">
            <table style="width:100%;border-collapse:collapse;font-size:6.5pt;">
                <tr><td colspan="3" class="r-cell-hdr">Reading (Start from Front / Driver)</td></tr>
                <tr>
                    <td class="r-cell-hdr" style="width:30%;text-align:left;"></td>
                    <td class="r-cell-hdr">PREM</td>
                    <td class="r-cell-hdr">REG</td>
                </tr>
                <tr>
                    <td class="r-cell" style="font-weight:700;font-size:6pt;">Before</td>
                    <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">125,430.00</td>
                    <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">87,210.00</td>
                </tr>
                <tr>
                    <td class="r-cell" style="font-weight:700;font-size:6pt;">After</td>
                    <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">125,731.00</td>
                    <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">87,484.00</td>
                </tr>
                <tr>
                    <td class="r-cell" style="font-weight:700;font-size:6pt;">Total Sales</td>
                    <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">301.00</td>
                    <td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">274.00</td>
                </tr>
            </table>
        </div>
        <div style="padding:4pt 6pt;min-width:92pt;font-size:6.5pt;font-family:'IBM Plex Mono',monospace;">
            <div style="font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#555;">Comp. No.</div>
            <div style="border-bottom:0.5pt solid #666;min-height:11pt;padding-bottom:1pt;margin-bottom:5pt;">C-01</div>
            <div style="font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#555;">Compartment No.</div>
            <div style="border-bottom:0.5pt solid #666;min-height:11pt;padding-bottom:1pt;">3</div>
            <div style="margin-top:3pt;font-size:5pt;color:#777;">(Start from front/driver)</div>
        </div>
    </div>

    <div style="background:black;color:white;padding:2.5pt 7pt;">
        <span style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;letter-spacing:.1em;text-transform:uppercase;">Dipstick Readings</span>
    </div>
    <div style="border-bottom:1pt solid black;">
        <table style="width:100%;border-collapse:collapse;font-size:6.5pt;">
            <thead>
                <tr>
                    <th class="r-cell-hdr" style="width:32%;text-align:left;">Product</th>
                    <th class="r-cell-hdr">Diesel</th>
                    <th class="r-cell-hdr">Premium</th>
                    <th class="r-cell-hdr">Regular</th>
                </tr>
            </thead>
            <tbody>
                <tr><td class="r-cell" style="font-weight:700;font-size:6pt;">Starting Dipstick</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">8,266.00</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">1,800.00</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">2,100.00</td></tr>
                <tr><td class="r-cell" style="font-weight:700;font-size:6pt;">Add: Delivery (+)</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">2,001.00</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">301.00</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">274.00</td></tr>
                <tr><td class="r-cell" style="font-weight:700;font-size:6pt;">Sub Total</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">10,267.00</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">2,101.00</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">2,374.00</td></tr>
                <tr><td class="r-cell" style="font-weight:700;font-size:6pt;">Add: Pump Sales</td><td class="r-cell"></td><td class="r-cell"></td><td class="r-cell"></td></tr>
                <tr><td class="r-cell" style="font-weight:700;font-size:6pt;">Total</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">10,267.00</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">2,101.00</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">2,374.00</td></tr>
                <tr><td class="r-cell" style="font-weight:700;font-size:6pt;">Closing Dipstick</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">10,255.00</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">2,095.00</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">2,370.00</td></tr>
                <tr><td class="r-cell" style="font-weight:700;font-size:6pt;">Short / Over</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">(12.00)</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">(6.00)</td><td class="r-cell" style="text-align:right;font-family:'IBM Plex Mono',monospace;">(4.00)</td></tr>
            </tbody>
        </table>
    </div>

    <div style="padding:4pt 7pt 3pt;border-bottom:1pt solid black;font-size:5.4pt;line-height:1.4;color:#333;">
        <span style="font-weight:700;">WAIVER OF GOOD QUALITY (MAAYOS NA KALIDAD) AND CORRECT QUANTITY (TAMANG SUKAT):</span>
        BY SIGNING IN THE DELIVERY RECEIPT, RECEIVER ACKNOWLEDGE THAT PRODUCT PASSED GOOD QUALITY (MAAYOS NA KALIDAD)
        AND CORRECT QUANTITY (TAMANG SUKAT). ONCE SIGNED THE CUSTOMER &amp; AUTHORIZED RECEIVER SHALL BE RESPONSIBLE
        FOR THE PRODUCT AND THIS OFFICE WILL NOT ENTERTAIN ANY CLAIM OF DAMAGE.
    </div>

    <div style="display:flex;flex:1;">
        <div style="flex:1;border-right:1pt solid black;padding:4pt 7pt;">
            <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.04em;margin-bottom:2pt;">
                Receiver's Signature (Over Printed Name)
            </div>
            <div style="border-bottom:0.5pt solid #555;min-height:18pt;padding-bottom:2pt;"></div>
            <div style="font-size:5pt;color:#666;margin-top:2pt;font-style:italic;">
                Received the above goods and supplies in good order and condition.
            </div>
        </div>
        <div style="flex:1;padding:4pt 7pt;">
            <div style="font-family:'IBM Plex Mono',monospace;font-size:5.5pt;font-weight:700;text-transform:uppercase;letter-spacing:.04em;margin-bottom:2pt;">
                Authorized Receiver's Signature (Over Printed Name)
            </div>
            <div style="border-bottom:0.5pt solid #555;min-height:18pt;padding-bottom:2pt;"></div>
            <div style="font-size:5pt;color:#666;margin-top:2pt;font-style:italic;">
                (Authorized Receiver Only)
            </div>
        </div>
    </div>

    <div style="border-top:0.5pt solid #ddd;padding:2pt 7pt;text-align:right;font-family:'IBM Plex Mono',monospace;font-size:5pt;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#aaa;">
        CUSTOMER COPY
    </div>
</div>
<!-- end copy 2 -->

</div>
</div>
@endsection