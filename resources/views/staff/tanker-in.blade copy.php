<!-- resources/views/staff/tanker-departure.blade.php -->
@extends('staff.layout.guest')

@section('title', 'Fuel Supply In')

@section('content')
<div class="min-h-screen" style="background-image: url('/images/img.png'); background-size: cover; background-position: center;">
    <div class="min-h-screen bg-black/50 backdrop-blur-sm">

        <div class="flex items-center justify-center px-8 py-16">
            <div class="bg-white rounded-lg shadow-2xl p-8 w-full max-w-2xl">
                <div class="text-center mb-8">
                    <div class="flex items-center justify-center gap-3 mb-4">
                        <div class="w-16 h-16 bg-black rounded-full flex items-center justify-center">
                            <div class="text-primary text-2xl font-bold">A</div>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold tracking-wide">AARON</h1>
                            <div class="flex items-center gap-2">
                                <div class="text-xs">SINCE 2004</div>
                                <div class="flex gap-0.5">
                                    @for($i = 0; $i < 12; $i++)
                                        <div class="w-1.5 h-1.5 {{ $i % 2 == 0 ? 'bg-black' : 'bg-white border border-black' }}"></div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                    <h2 class="text-2xl font-bold">Fuel Supply In</h2>
                </div>
                @if(session('success'))
                    <div id="flashMessage"
                        class="mt-4 mb-4 px-4 py-3 rounded-lg bg-green-100 border border-green-300 text-green-800 transition-opacity duration-500">
                        {{ session('success') }}
                    </div>

                    <script>
                        setTimeout(() => {
                            const msg = document.getElementById('flashMessage');
                            if (msg) {
                                msg.style.opacity = '0';
                                setTimeout(() => msg.remove(), 500);
                            }
                        }, 3000);
                    </script>
                @endif
                <form method="POST" action="{{ route('tanker-arrival.store') }}" class="space-y-4" id="fuelForm">
                    @csrf

                    {{-- Tanker + Driver + Date --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">
                                Tanker Number<span class="text-primary">*</span>
                            </label>
                            <input type="text" name="tanker_number" placeholder="Enter tanker number" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">
                                Driver<span class="text-primary">*</span>
                            </label>
                            <input type="text" name="driver" placeholder="Enter driver name" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium mb-2">
                                Arrival Date<span class="text-primary">*</span>
                            </label>
                            <input type="date" name="departure_date" required value="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                    </div>

                    {{-- Fuel Rows --}}
                    <div class="space-y-3" id="fuelRows">
                        @for($i = 1; $i <= 4; $i++)
                        <div class="fuel-row p-4 bg-gray-50 rounded-lg border border-gray-200 space-y-3">

                            {{-- Row label --}}
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">
                                Fuel Slot {{ $i }}{{ $i === 1 ? ' (Required)' : ' (Optional)' }}
                            </p>

                            {{-- Fuel type + Liters --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">
                                        Fuel Type @if($i === 1)<span class="text-primary">*</span>@endif
                                    </label>
                                    <select name="fuel_type[{{ $i }}]"
                                            class="fuel-select w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                            {{ $i === 1 ? 'required' : '' }}>
                                        <option value="">Select fuel type</option>
                                        <option value="diesel">Diesel</option>
                                        <option value="premium">Premium</option>
                                        <option value="unleaded">Unleaded</option>
                                        <option value="methanol">Methanol (Pure)</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">
                                        Liters to Deliver @if($i === 1)<span class="text-primary">*</span>@endif
                                    </label>
                                    <input type="number" name="liters[{{ $i }}]"
                                        placeholder="Enter liters" min="1"
                                        class="fuel-liters w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
                                        {{ $i === 1 ? 'required' : '' }}>
                                </div>
                            </div>

                            {{-- Methanol % slider (hidden until mixed fuel selected) --}}
                            <div class="methanol-controls hidden">
                                <div class="flex items-center justify-between mb-1">
                                    <label class="text-sm font-medium text-yellow-700">
                                        ⚗️ Methanol Mixture %
                                    </label>
                                    <div class="flex items-center gap-2">
                                        <input type="range"
                                               name="methanol_percent[{{ $i }}]"
                                               class="methanol-slider w-32 accent-yellow-500"
                                               min="5" max="30" step="1" value="15">
                                        <div class="flex items-center gap-1">
                                            <input type="number"
                                                   class="methanol-percent-input w-16 px-2 py-1 text-sm border border-yellow-300 rounded-lg text-center focus:outline-none focus:ring-2 focus:ring-yellow-400"
                                                   min="5" max="30" step="1" value="15">
                                            <span class="text-sm font-semibold text-yellow-700">%</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Breakdown card --}}
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 text-sm methanol-breakdown">
                                    <div class="grid grid-cols-3 gap-2 text-yellow-700 text-center">
                                        <div class="bg-white rounded-lg p-2 border border-yellow-100">
                                            <p class="text-xs text-gray-400 mb-1">Total</p>
                                            <p class="font-bold total-liters-display">— L</p>
                                        </div>
                                        <div class="bg-white rounded-lg p-2 border border-yellow-100">
                                            <p class="text-xs text-gray-400 mb-1">Pure Fuel</p>
                                            <p class="font-bold text-green-600 pure-liters">— L</p>
                                        </div>
                                        <div class="bg-white rounded-lg p-2 border border-yellow-100">
                                            <p class="text-xs text-gray-400 mb-1">Methanol</p>
                                            <p class="font-bold text-yellow-600 methanol-liters">— L</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Hidden fields for backend --}}
                            <input type="hidden" name="methanol_liters[{{ $i }}]" class="methanol-liters-hidden" value="0">

                        </div>
                        @endfor
                    </div>

                    {{-- Total Summary --}}
                    <div class="bg-gray-100 rounded-lg p-4 border border-gray-200 text-sm">
                        <p class="font-semibold text-gray-700 mb-2">📊 Delivery Summary</p>
                        <div id="summaryLines" class="space-y-1 text-gray-600">
                            <p class="text-gray-400 italic">Select fuel types and enter liters to see summary.</p>
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full bg-primary text-white py-3 rounded-full hover:bg-[#ff4040] transition font-semibold text-lg mt-6">
                        Submit
                    </button>
                </form>
            </div>
        </div>

        <footer class="bg-primary py-4 fixed bottom-0 w-full">
            <p class="text-center text-white">© 2026 Aaron Gas Station. All Rights Reserved.</p>
        </footer>
    </div>
</div>

<script>
    const mixedFuels = ['diesel', 'premium', 'unleaded'];
    const rows       = document.querySelectorAll('.fuel-row');

    // ── Disable already-selected options across all selects ──────────────────
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

    // ── Sync slider ↔ number input ────────────────────────────────────────────
    function syncSliderAndInput(row, source) {
        const slider    = row.querySelector('.methanol-slider');
        const numInput  = row.querySelector('.methanol-percent-input');
        let val = parseInt(source.value) || 15;
        val = Math.min(30, Math.max(5, val)); // clamp 5–30
        slider.value   = val;
        numInput.value = val;
        return val;
    }

    // ── Update methanol breakdown for a single row ────────────────────────────
    function updateRow(row) {
        const select    = row.querySelector('.fuel-select');
        const litersEl  = row.querySelector('.fuel-liters');
        const controls  = row.querySelector('.methanol-controls');
        const hidden    = row.querySelector('.methanol-liters-hidden');
        const fuelType  = select.value;
        const liters    = parseFloat(litersEl.value) || 0;
        const pct       = parseInt(row.querySelector('.methanol-slider')?.value) || 15;

        if (mixedFuels.includes(fuelType)) {
            controls.classList.remove('hidden');

            if (liters > 0) {
                const methanolLiters = +(liters * pct / 100).toFixed(2);
                const pureLiters     = +(liters - methanolLiters).toFixed(2);

                row.querySelector('.total-liters-display').textContent = liters + ' L';
                row.querySelector('.pure-liters').textContent          = pureLiters + ' L';
                row.querySelector('.methanol-liters').textContent      = methanolLiters + ' L';
                hidden.value = methanolLiters;
            } else {
                row.querySelector('.total-liters-display').textContent = '— L';
                row.querySelector('.pure-liters').textContent          = '— L';
                row.querySelector('.methanol-liters').textContent      = '— L';
                hidden.value = 0;
            }
        } else {
            controls.classList.add('hidden');
            hidden.value = 0;
        }

        updateSummary();
    }

    // ── Build summary panel ───────────────────────────────────────────────────
    function updateSummary() {
        const lines       = [];
        let totalLiters   = 0;
        let totalMethanol = 0;

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
                    <div class="flex justify-between items-center">
                        <span class="capitalize font-medium">${fuel}</span>
                        <span class="text-right">
                            ${liters} L
                            <span class="text-yellow-600 text-xs block">
                                ${pure} L pure + ${methanol} L methanol (${pct}%)
                            </span>
                        </span>
                    </div>`);
            } else {
                lines.push(`
                    <div class="flex justify-between items-center">
                        <span class="capitalize font-medium">${fuel}</span>
                        <span>${liters} L</span>
                    </div>`);
            }
        });

        const el = document.getElementById('summaryLines');

        if (lines.length === 0) {
            el.innerHTML = '<p class="text-gray-400 italic">Select fuel types and enter liters to see summary.</p>';
            return;
        }

        if (totalMethanol > 0) {
            lines.push(`
                <div class="border-t border-gray-300 pt-2 mt-2 flex justify-between font-semibold text-yellow-700">
                    <span>Total Methanol Content</span>
                    <span>${totalMethanol.toFixed(2)} L</span>
                </div>`);
        }

        lines.push(`
            <div class="border-t border-gray-300 pt-1 mt-1 flex justify-between font-bold text-gray-800">
                <span>Total Delivery</span>
                <span>${totalLiters.toFixed(2)} L</span>
            </div>`);

        el.innerHTML = lines.join('');
    }

    // ── Attach events to each row ─────────────────────────────────────────────
    rows.forEach(row => {
        const select   = row.querySelector('.fuel-select');
        const litersEl = row.querySelector('.fuel-liters');
        const slider   = row.querySelector('.methanol-slider');
        const numInput = row.querySelector('.methanol-percent-input');

        select.addEventListener('change', () => {
            updateAllSelects();
            updateRow(row);
        });

        litersEl.addEventListener('input', () => updateRow(row));

        // Slider moves → sync number input
        slider.addEventListener('input', () => {
            syncSliderAndInput(row, slider);
            updateRow(row);
        });

        // Number input changes → sync slider
        numInput.addEventListener('input', () => {
            syncSliderAndInput(row, numInput);
            updateRow(row);
        });

        // Clamp on blur in case user types out of range
        numInput.addEventListener('blur', () => {
            syncSliderAndInput(row, numInput);
            updateRow(row);
        });
    });
</script>
@endsection