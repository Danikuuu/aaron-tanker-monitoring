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
                        <img src="{{ asset('images/AARON1.png') }}" class="h-48 xl:h-24 w-auto">
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
                                        <option value="methanol">Methanol</option>
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
    const rows = document.querySelectorAll('.fuel-row');

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

    // ── Build summary panel ───────────────────────────────────────────────────
    function updateSummary() {
        const lines     = [];
        let totalLiters = 0;

        rows.forEach(row => {
            const fuel   = row.querySelector('.fuel-select').value;
            const liters = parseFloat(row.querySelector('.fuel-liters').value) || 0;

            if (!fuel || liters <= 0) return;

            totalLiters += liters;

            lines.push(`
                <div class="flex justify-between items-center">
                    <span class="capitalize font-medium">${fuel}</span>
                    <span>${liters} L</span>
                </div>`);
        });

        const el = document.getElementById('summaryLines');

        if (lines.length === 0) {
            el.innerHTML = '<p class="text-gray-400 italic">Select fuel types and enter liters to see summary.</p>';
            return;
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
        row.querySelector('.fuel-select').addEventListener('change', () => {
            updateAllSelects();
            updateSummary();
        });

        row.querySelector('.fuel-liters').addEventListener('input', () => updateSummary());
    });
</script>
@endsection