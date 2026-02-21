<!-- resources/views/staff/tanker-departure.blade.php -->
@extends('staff.layout.guest')

@section('title', 'Fuel Supply Out')

@section('content')

<style>
    .td-wrap {
        min-height: calc(100vh - 64px);
        /* background-image: url('/images/img.png'); */
        background-size: cover;
        background-position: center;
    }
    .td-overlay {
        min-height: inherit;
        /* backdrop-filter: blur(3px); */
        padding-bottom: 64px;
        display: flex;
        flex-direction: column;
    }

    /* Card */
    .td-card {
        background: #fff;
        border-radius: 18px;
        /* box-shadow: 0 20px 60px rgba(0,0,0,0.28); */
        width: 100%;
        max-width: 680px;
        margin: 0 auto;
    }
    .td-card-inner { padding: 28px 28px 32px; }
    @media (max-width: 480px) {
        .td-card-inner { padding: 20px 16px 24px; }
    }

    /* Logo */
    .td-logo { height: 64px; width: auto; }
    @media (max-width: 480px) { .td-logo { height: 48px; } }

    .td-heading {
        font-size: 1.25rem;
        font-weight: 700;
        color: #111;
        margin-top: 10px;
    }
    @media (max-width: 480px) { .td-heading { font-size: 1.05rem; } }

    /* Flash */
    .td-flash {
        padding: 12px 16px;
        border-radius: 10px;
        background: #F0FFF4;
        border: 1px solid #C6F6D5;
        color: #276749;
        font-size: 0.875rem;
        margin-bottom: 16px;
        transition: opacity 0.5s ease;
    }

    /* Form inputs */
    .td-label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: #555;
        margin-bottom: 6px;
    }
    .td-label .req { color: #E53E3E; }

    .td-input, .td-select {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid #e5e5e5;
        border-radius: 10px;
        font-size: 0.875rem;
        color: #222;
        background: #fff;
        outline: none;
        transition: border-color 0.15s;
        box-sizing: border-box;
        font-family: inherit;
    }
    .td-input:focus, .td-select:focus { border-color: #E53E3E; }

    /* Grid helpers */
    .td-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .td-col-2  { grid-column: 1 / -1; }
    @media (max-width: 520px) {
        .td-grid-2 { grid-template-columns: 1fr; }
        .td-col-2  { grid-column: 1; }
    }

    /* Fuel slot card */
    .fuel-row {
        background: #fafafa;
        border: 1.5px solid #f0f0f0;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 10px;
    }
    .fuel-slot-label {
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.07em;
        text-transform: uppercase;
        color: #bbb;
        margin-bottom: 10px;
    }

    /* Methanol controls */
    .methanol-controls { margin-top: 12px; }
    .methanol-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 8px;
    }
    .methanol-title {
        font-size: 0.82rem;
        font-weight: 600;
        color: #92610b;
    }
    .methanol-input-row {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .methanol-slider { accent-color: #d97706; width: 100px; }
    @media (max-width: 380px) { .methanol-slider { width: 70px; } }

    .methanol-num {
        width: 56px;
        padding: 4px 8px;
        border: 1.5px solid #FCD34D;
        border-radius: 8px;
        font-size: 0.82rem;
        text-align: center;
        outline: none;
        transition: border-color 0.15s;
        box-sizing: border-box;
    }
    .methanol-num:focus { border-color: #D97706; }
    .methanol-pct-label { font-size: 0.82rem; font-weight: 700; color: #92610b; }

    /* Methanol breakdown */
    .methanol-breakdown {
        background: #FFFBEB;
        border: 1px solid #FCD34D;
        border-radius: 10px;
        padding: 12px;
        margin-top: 8px;
    }
    .breakdown-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        text-align: center;
    }
    .breakdown-cell {
        background: #fff;
        border: 1px solid #FEF3C7;
        border-radius: 8px;
        padding: 8px 6px;
    }
    .breakdown-cell .bc-label { font-size: 0.65rem; color: #aaa; margin-bottom: 2px; }
    .breakdown-cell .bc-val   { font-size: 0.85rem; font-weight: 700; color: #92610b; }
    .bc-val.pure    { color: #276749; }
    .bc-val.methanol{ color: #B45309; }

    /* Summary */
    .td-summary {
        background: #f8f8f8;
        border: 1.5px solid #efefef;
        border-radius: 12px;
        padding: 16px;
        font-size: 0.85rem;
        margin-top: 4px;
    }
    .summary-title {
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #bbb;
        margin-bottom: 10px;
    }
    #summaryLines .empty-msg { color: #bbb; font-style: italic; font-size: 0.83rem; }
    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 5px 0;
        border-bottom: 1px solid #f0f0f0;
        gap: 8px;
    }
    .summary-row:last-child { border-bottom: none; }
    .summary-row.total {
        border-top: 1.5px solid #e0e0e0;
        padding-top: 8px;
        margin-top: 4px;
        font-weight: 700;
        color: #111;
    }
    .summary-row.methanol-total { color: #B45309; font-weight: 600; }
    .summary-sub { font-size: 0.72rem; color: #B45309; margin-top: 1px; }

    /* Submit */
    .td-submit {
        width: 100%;
        padding: 13px;
        background: #E53E3E;
        color: #fff;
        border: none;
        border-radius: 50px;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.15s;
        margin-top: 8px;
        letter-spacing: 0.01em;
    }
    .td-submit:hover { background: #c53030; }

    /* Footer */
    .td-footer {
        background: #E53E3E;
        padding: 14px;
        text-align: center;
        color: #fff;
        font-size: 0.8rem;
        position: fixed;
        bottom: 0; left: 0; right: 0;
        z-index: 50;
    }

     .ti-logo { height: 64px; width: auto; }
    @media (max-width: 480px) { .ti-logo { height: 48px; } }
</style>

<div class="td-wrap">
    <div class="td-overlay">
        <div class="flex-1 flex items-start justify-center px-4 sm:px-6 py-10 sm:py-16">
            <div class="td-card">
                <div class="td-card-inner">

                    {{-- Logo & Title --}}
                    <div class="text-center mb-8">
                        <div class="flex items-center justify-center gap-3 mb-4">
                            <img src="{{ asset('images/aaron-staff.png') }}" class="ti-logo">
                        </div>
                        <h2 class="text-lg font-bold">Fuel Supply Out</h2>
                    </div>

                    {{-- Flash --}}
                    @if(session('success'))
                        <div id="flashMessage" class="td-flash">{{ session('success') }}</div>
                        <script>
                            setTimeout(() => {
                                const msg = document.getElementById('flashMessage');
                                if (msg) { msg.style.opacity = '0'; setTimeout(() => msg.remove(), 500); }
                            }, 3000);
                        </script>
                    @endif

                    @error('stock')
                        <div class="td-flash flash-error">
                            {{ $message }}
                        </div>
                    @enderror

                    <script>
                        setTimeout(() => {
                            document.querySelectorAll('.flash-error').forEach(msg => {
                                msg.style.opacity = '0';
                                setTimeout(() => msg.remove(), 500);
                            });
                        }, 3000);
                    </script>

                    <form method="POST" action="{{ route('staff.tanker-departure.store') }}" id="fuelForm">
                        @csrf

                        {{-- Tanker / Driver / Date --}}
                        <div class="td-grid-2" style="margin-bottom:14px;">
                            <div>
                                <label class="td-label">Tanker Number <span class="req">*</span></label>
                                <input type="text" name="tanker_number" placeholder="e.g. TK-001"
                                       required class="td-input">
                            </div>
                            <div>
                                <label class="td-label">Driver <span class="req">*</span></label>
                                <input type="text" name="driver" placeholder="Driver name"
                                       required class="td-input">
                            </div>
                            <div class="td-col-2">
                                <label class="td-label">Departure Date <span class="req">*</span></label>
                                <input type="date" name="departure_date" required readonly
                                       value="{{ date('Y-m-d') }}" class="td-input">
                            </div>
                        </div>

                        {{-- Fuel Rows --}}
                        <div style="margin-bottom:14px;" id="fuelRows">
                            @for($i = 1; $i <= 4; $i++)
                            <div class="fuel-row">
                                <p class="fuel-slot-label">
                                    Fuel Slot {{ $i }}{{ $i === 1 ? ' — Required' : ' — Optional' }}
                                </p>

                                <div class="td-grid-2">
                                    <div>
                                        <label class="td-label">
                                            Fuel Type @if($i === 1)<span class="req">*</span>@endif
                                        </label>
                                        <select name="fuel_type[{{ $i }}]"
                                                class="fuel-select td-select"
                                                {{ $i === 1 ? 'required' : '' }}>
                                            <option value="">Select type</option>
                                            <option value="diesel">Diesel</option>
                                            <option value="premium">Premium</option>
                                            <option value="unleaded">Unleaded</option>
                                            <option value="methanol">Methanol (Pure)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="td-label">
                                            Litres @if($i === 1)<span class="req">*</span>@endif
                                        </label>
                                        <input type="number" name="liters[{{ $i }}]"
                                               placeholder="Enter litres" min="1"
                                               class="fuel-liters td-input"
                                               {{ $i === 1 ? 'required' : '' }}>
                                    </div>
                                </div>

                                {{-- Methanol controls --}}
                                <div class="methanol-controls hidden">
                                    <div class="methanol-header">
                                        <span class="methanol-title">⚗️ Methanol Mixture %</span>
                                        <div class="methanol-input-row">
                                            <input type="range"
                                                   name="methanol_percent[{{ $i }}]"
                                                   class="methanol-slider"
                                                   min="5" max="30" step="1" value="15">
                                            <input type="number"
                                                   class="methanol-num"
                                                   min="5" max="30" step="1" value="15">
                                            <span class="methanol-pct-label">%</span>
                                        </div>
                                    </div>
                                    <div class="methanol-breakdown">
                                        <div class="breakdown-grid">
                                            <div class="breakdown-cell">
                                                <div class="bc-label">Total</div>
                                                <div class="bc-val total-liters-display">— L</div>
                                            </div>
                                            <div class="breakdown-cell">
                                                <div class="bc-label">Pure Fuel</div>
                                                <div class="bc-val pure pure-liters">— L</div>
                                            </div>
                                            <div class="breakdown-cell">
                                                <div class="bc-label">Methanol</div>
                                                <div class="bc-val methanol methanol-liters">— L</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="methanol_liters[{{ $i }}]"
                                       class="methanol-liters-hidden" value="0">
                            </div>
                            @endfor
                        </div>

                        {{-- Summary --}}
                        <div class="td-summary" style="margin-bottom:16px;">
                            <div class="summary-title">📊 Delivery Summary</div>
                            <div id="summaryLines">
                                <p class="empty-msg">Select fuel types and enter litres to see summary.</p>
                            </div>
                        </div>

                        <button type="submit" class="td-submit">Submit Departure</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<footer class="td-footer">
    © 2026 Aaron Gas Station. All Rights Reserved.
</footer>

<script>
    const mixedFuels = ['diesel', 'premium', 'unleaded'];
    const rows       = document.querySelectorAll('.fuel-row');

    function updateAllSelects() {
        const selected = Array.from(document.querySelectorAll('.fuel-select'))
            .map(s => s.value).filter(v => v !== '');
        document.querySelectorAll('.fuel-select').forEach(select => {
            const current = select.value;
            Array.from(select.options).forEach(opt => {
                if (opt.value === '') return;
                opt.disabled = selected.includes(opt.value) && opt.value !== current;
            });
        });
    }

    function syncSliderAndInput(row, source) {
        const slider   = row.querySelector('.methanol-slider');
        const numInput = row.querySelector('.methanol-num');
        let val = parseInt(source.value) || 15;
        val = Math.min(30, Math.max(5, val));
        slider.value   = val;
        numInput.value = val;
        return val;
    }

    function updateRow(row) {
        const select   = row.querySelector('.fuel-select');
        const litersEl = row.querySelector('.fuel-liters');
        const controls = row.querySelector('.methanol-controls');
        const hidden   = row.querySelector('.methanol-liters-hidden');
        const fuelType = select.value;
        const liters   = parseFloat(litersEl.value) || 0;
        const pct      = parseInt(row.querySelector('.methanol-slider')?.value) || 15;

        if (mixedFuels.includes(fuelType)) {
            controls.classList.remove('hidden');
            if (liters > 0) {
                const methanolL = +(liters * pct / 100).toFixed(2);
                const pureL     = +(liters - methanolL).toFixed(2);
                row.querySelector('.total-liters-display').textContent = liters + ' L';
                row.querySelector('.pure-liters').textContent          = pureL + ' L';
                row.querySelector('.methanol-liters').textContent      = methanolL + ' L';
                hidden.value = methanolL;
            } else {
                ['total-liters-display','pure-liters','methanol-liters'].forEach(cls => {
                    row.querySelector('.' + cls).textContent = '— L';
                });
                hidden.value = 0;
            }
        } else {
            controls.classList.add('hidden');
            hidden.value = 0;
        }
        updateSummary();
    }

    function updateSummary() {
        const el = document.getElementById('summaryLines');
        const lines = [];
        let totalLiters = 0, totalMethanol = 0;

        rows.forEach(row => {
            const fuel   = row.querySelector('.fuel-select').value;
            const liters = parseFloat(row.querySelector('.fuel-liters').value) || 0;
            const pct    = parseInt(row.querySelector('.methanol-slider')?.value) || 15;
            if (!fuel || liters <= 0) return;
            totalLiters += liters;

            if (mixedFuels.includes(fuel)) {
                const methanol = +(liters * pct / 100).toFixed(2);
                const pure     = +(liters - methanol).toFixed(2);
                totalMethanol += methanol;
                lines.push(`
                    <div class="summary-row">
                        <span style="font-weight:600;text-transform:capitalize">${fuel}</span>
                        <div style="text-align:right">
                            <span>${liters} L</span>
                            <div class="summary-sub">${pure} L pure + ${methanol} L methanol (${pct}%)</div>
                        </div>
                    </div>`);
            } else {
                lines.push(`
                    <div class="summary-row">
                        <span style="font-weight:600;text-transform:capitalize">${fuel}</span>
                        <span>${liters} L</span>
                    </div>`);
            }
        });

        if (lines.length === 0) {
            el.innerHTML = '<p class="empty-msg">Select fuel types and enter litres to see summary.</p>';
            return;
        }
        if (totalMethanol > 0) {
            lines.push(`
                <div class="summary-row methanol-total">
                    <span>Total Methanol</span>
                    <span>${totalMethanol.toFixed(2)} L</span>
                </div>`);
        }
        lines.push(`
            <div class="summary-row total">
                <span>Total Delivery</span>
                <span>${totalLiters.toFixed(2)} L</span>
            </div>`);
        el.innerHTML = lines.join('');
    }

    rows.forEach(row => {
        const select   = row.querySelector('.fuel-select');
        const litersEl = row.querySelector('.fuel-liters');
        const slider   = row.querySelector('.methanol-slider');
        const numInput = row.querySelector('.methanol-num');

        select.addEventListener('change', () => { updateAllSelects(); updateRow(row); });
        litersEl.addEventListener('input', () => updateRow(row));
        slider.addEventListener('input', () => { syncSliderAndInput(row, slider); updateRow(row); });
        numInput.addEventListener('input', () => { syncSliderAndInput(row, numInput); updateRow(row); });
        numInput.addEventListener('blur',  () => { syncSliderAndInput(row, numInput); updateRow(row); });
    });
</script>

<script>
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', () => {
            const btn = form.querySelector('button[type="submit"]');
            if(btn) {
                btn.disabled = true;
                btn.innerText = 'Submitting...';
            }
        });
    });
</script>


@endsection