<!-- resources/views/staff/tanker-in.blade.php -->
@extends('staff.layout.guest')

@section('title', 'Fuel Supply In')

@section('content')

<style>
    .ti-card {
        background: #fff;
        border-radius: 18px;
        width: 100%;
        max-width: 680px;
        margin: 0 auto;
    }
    .ti-card-inner { padding: 28px 28px 32px; }
    @media (max-width: 480px) { .ti-card-inner { padding: 20px 16px 24px; } }

    .ti-logo { height: 64px; width: auto; }
    @media (max-width: 480px) { .ti-logo { height: 48px; } }

    .ti-heading { font-size: 1.25rem; font-weight: 700; color: #111; margin-top: 10px; }
    @media (max-width: 480px) { .ti-heading { font-size: 1.05rem; } }

    /* Flash */
    .ti-flash {
        padding: 12px 16px; border-radius: 10px;
        background: #F0FFF4; border: 1px solid #C6F6D5;
        color: #276749; font-size: 0.875rem;
        margin-bottom: 16px; transition: opacity 0.5s ease;
    }

    /* Labels & inputs */
    .ti-label { display: block; font-size: 0.8rem; font-weight: 600; color: #555; margin-bottom: 6px; }
    .ti-label .req { color: #E53E3E; }
    .ti-input, .ti-select {
        width: 100%; padding: 10px 14px;
        border: 1.5px solid #e5e5e5; border-radius: 10px;
        font-size: 0.875rem; color: #222; background: #fff;
        outline: none; transition: border-color 0.15s;
        box-sizing: border-box; font-family: inherit;
    }
    .ti-input:focus, .ti-select:focus { border-color: #E53E3E; }

    /* Responsive 2-col grid */
    .ti-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .ti-col-2  { grid-column: 1 / -1; }
    @media (max-width: 520px) {
        .ti-grid-2 { grid-template-columns: 1fr; }
        .ti-col-2  { grid-column: 1; }
    }

    /* Fuel slot */
    .fuel-row {
        background: #fafafa; border: 1.5px solid #f0f0f0;
        border-radius: 12px; padding: 16px; margin-bottom: 10px;
    }
    .fuel-slot-label {
        font-size: 0.68rem; font-weight: 700; letter-spacing: 0.07em;
        text-transform: uppercase; color: #bbb; margin-bottom: 10px;
    }

    /* Summary */
    .ti-summary {
        background: #f8f8f8; border: 1.5px solid #efefef;
        border-radius: 12px; padding: 16px;
        font-size: 0.85rem; margin-bottom: 16px;
    }
    .summary-title {
        font-size: 0.78rem; font-weight: 700; letter-spacing: 0.06em;
        text-transform: uppercase; color: #bbb; margin-bottom: 10px;
    }
    .empty-msg { color: #bbb; font-style: italic; font-size: 0.83rem; }
    .summary-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 5px 0; border-bottom: 1px solid #f0f0f0; gap: 8px;
    }
    .summary-row:last-child { border-bottom: none; }
    .summary-row.total {
        border-top: 1.5px solid #e0e0e0; padding-top: 8px;
        margin-top: 4px; font-weight: 700; color: #111;
    }
    .summary-fuel-name { font-weight: 600; text-transform: capitalize; }

    /* Submit */
    .ti-submit {
        width: 100%; padding: 13px; background: #E53E3E;
        color: #fff; border: none; border-radius: 50px;
        font-size: 1rem; font-weight: 700; cursor: pointer;
        transition: background 0.15s; letter-spacing: 0.01em;
    }
    .ti-submit:hover { background: #c53030; }

    /* Footer */
    .ti-footer {
        background: #E53E3E; padding: 14px; text-align: center;
        color: #fff; font-size: 0.8rem;
        position: fixed; bottom: 0; left: 0; right: 0; z-index: 50;
    }
</style>

<div class="flex items-start justify-center px-4 sm:px-6 py-6 sm:py-10" style="padding-bottom: 80px;">
    <div class="ti-card">
        <div class="ti-card-inner">

            {{-- Logo & Title --}}
            <div class="text-center mb-6">
                <img src="{{ asset('images/aaron-staff.png') }}" class="ti-logo mx-auto mb-3">
                <h2 class="ti-heading">Fuel Supply In</h2>
            </div>

            {{-- Flash --}}
            @if(session('success'))
                <div id="flashMessage" class="ti-flash">{{ session('success') }}</div>
                <script>
                    setTimeout(() => {
                        const msg = document.getElementById('flashMessage');
                        if (msg) { msg.style.opacity = '0'; setTimeout(() => msg.remove(), 500); }
                    }, 3000);
                </script>
            @endif

            <form method="POST" action="{{ route('tanker-arrival.store') }}" id="fuelForm">
                @csrf

                {{-- Tanker / Driver / Date --}}
                <div class="ti-grid-2" style="margin-bottom: 14px;">
                    <div>
                        <label class="ti-label">Tanker Number <span class="req">*</span></label>
                        <input type="text" name="tanker_number" placeholder="e.g. TK-001"
                               required class="ti-input">
                    </div>
                    <div>
                        <label class="ti-label">Driver <span class="req">*</span></label>
                        <input type="text" name="driver" placeholder="Driver name"
                               required class="ti-input">
                    </div>
                    <div class="ti-col-2">
                        <label class="ti-label">Arrival Date <span class="req">*</span></label>
                        <input type="date" name="departure_date" required
                               value="{{ date('Y-m-d') }}" class="ti-input">
                    </div>
                </div>

                {{-- Fuel Rows --}}
                <div style="margin-bottom: 14px;" id="fuelRows">
                    @for($i = 1; $i <= 4; $i++)
                    <div class="fuel-row">
                        <p class="fuel-slot-label">
                            Fuel Slot {{ $i }}{{ $i === 1 ? ' — Required' : ' — Optional' }}
                        </p>
                        <div class="ti-grid-2">
                            <div>
                                <label class="ti-label">
                                    Fuel Type @if($i === 1)<span class="req">*</span>@endif
                                </label>
                                <select name="fuel_type[{{ $i }}]"
                                        class="fuel-select ti-select"
                                        {{ $i === 1 ? 'required' : '' }}>
                                    <option value="">Select type</option>
                                    <option value="diesel">Diesel</option>
                                    <option value="premium">Premium</option>
                                    <option value="unleaded">Unleaded</option>
                                    <option value="methanol">Methanol</option>
                                </select>
                            </div>
                            <div>
                                <label class="ti-label">
                                    Litres @if($i === 1)<span class="req">*</span>@endif
                                </label>
                                <input type="number" name="liters[{{ $i }}]"
                                       placeholder="Enter litres" min="1"
                                       class="fuel-liters ti-input"
                                       {{ $i === 1 ? 'required' : '' }}>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>

                {{-- Summary --}}
                <div class="ti-summary">
                    <div class="summary-title">📊 Delivery Summary</div>
                    <div id="summaryLines">
                        <p class="empty-msg">Select fuel types and enter litres to see summary.</p>
                    </div>
                </div>

                <button type="submit" class="ti-submit">Submit Arrival</button>
            </form>

        </div>
    </div>
</div>

<footer class="ti-footer">
    © 2026 Aaron Gas Station. All Rights Reserved.
</footer>

<script>
    const rows = document.querySelectorAll('.fuel-row');

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

    function updateSummary() {
        const lines = [];
        let totalLiters = 0;

        rows.forEach(row => {
            const fuel   = row.querySelector('.fuel-select').value;
            const liters = parseFloat(row.querySelector('.fuel-liters').value) || 0;
            if (!fuel || liters <= 0) return;
            totalLiters += liters;
            lines.push(`
                <div class="summary-row">
                    <span class="summary-fuel-name">${fuel}</span>
                    <span>${liters.toFixed(2)} L</span>
                </div>`);
        });

        const el = document.getElementById('summaryLines');
        if (lines.length === 0) {
            el.innerHTML = '<p class="empty-msg">Select fuel types and enter litres to see summary.</p>';
            return;
        }
        lines.push(`
            <div class="summary-row total">
                <span>Total Delivery</span>
                <span>${totalLiters.toFixed(2)} L</span>
            </div>`);
        el.innerHTML = lines.join('');
    }

    rows.forEach(row => {
        row.querySelector('.fuel-select').addEventListener('change', () => {
            updateAllSelects();
            updateSummary();
        });
        row.querySelector('.fuel-liters').addEventListener('input', () => updateSummary());
    });
</script>

@endsection