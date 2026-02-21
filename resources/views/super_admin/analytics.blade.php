@extends('super_admin.layout.app')

@section('title', 'Analytics')

@section('content')
<div class="space-y-8">

    {{-- Filter Bar --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Analytics</h1>
        <form method="GET" action="{{ route('super_admin.analytics') }}" class="flex items-center gap-3">
            <label class="text-sm font-medium text-gray-600">Period:</label>
            <div class="flex rounded-lg border border-gray-300 overflow-hidden text-sm">
                @foreach(['daily' => 'Daily', 'weekly' => 'Weekly', 'monthly' => 'Monthly', 'yearly' => 'Yearly'] as $value => $label)
                <button type="submit" name="period" value="{{ $value }}"
                    class="px-4 py-2 transition
                        {{ $period === $value
                            ? 'bg-primary text-white font-semibold'
                            : 'bg-white text-gray-600 hover:bg-gray-50' }}">
                    {{ $label }}
                </button>
                @endforeach
            </div>
        </form>
    </div>

    {{-- Summary Cards - Arrivals --}}
    <div class="grid grid-cols-4 gap-4">
        @foreach(['diesel' => ['label' => 'Diesel Arrived', 'color' => 'text-green-600'],
                  'premium'  => ['label' => 'Premium Arrived',  'color' => 'text-yellow-600'],
                  'unleaded' => ['label' => 'Unleaded Arrived', 'color' => 'text-blue-600'],
                  'methanol' => ['label' => 'Methanol Arrived', 'color' => 'text-purple-600']] as $type => $cfg)
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
            <p class="font-bold text-lg mb-1">{{ $cfg['label'] }}</p>
            <p class="text-2xl font-bold {{ $cfg['color'] }}">
                {{ number_format($arrivalTotals[$type] ?? 0, 2) }} L
            </p>
        </div>
        @endforeach
    </div>

    {{-- Summary Cards - Departures --}}
    <div class="grid grid-cols-4 gap-4">
        @foreach(['diesel' => ['label' => 'Diesel Dispatched', 'color' => 'text-green-600'],
                  'premium'  => ['label' => 'Premium Dispatched',  'color' => 'text-yellow-600'],
                  'unleaded' => ['label' => 'Unleaded Dispatched', 'color' => 'text-blue-600'],
                  'methanol' => ['label' => 'Methanol Used (Mix)', 'color' => 'text-purple-600']] as $type => $cfg)
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
            <p class="font-bold text-lg mb-1">{{ $cfg['label'] }}</p>
            <p class="text-2xl font-bold {{ $cfg['color'] }}">
                @if($type === 'methanol')
                    {{ number_format($departureTotals->sum('total_methanol'), 2) }} L
                @else
                    {{ number_format($departureTotals[$type]->total ?? 0, 2) }} L
                @endif
            </p>
        </div>
        @endforeach
    </div>

    {{-- Arrival Chart --}}
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold">Fuel Tanker Arrival</h2>

            <div class="flex gap-2">
                <!-- CSV Export -->
                <!-- <a href="{{ route('super_admin.analytics.export.csv', ['type' => 'arrival', 'period' => $period]) }}"
                class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-[#ff4040] transition text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">...</svg>
                    Export CSV
                </a> -->

                <!-- PDF Export -->
                <!-- <a href="{{ route('super_admin.analytics.export.pdf', ['type' => 'arrival', 'period' => $period]) }}"
                class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">...</svg>
                    Export PDF
                </a> -->
            </div>
        </div>

        <div class="bg-gray-50 rounded-lg p-4" style="height: 380px;">
            <canvas id="arrivalChart"></canvas>
        </div>
    </div>

    {{-- Departure Chart --}}
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold">Fuel Tanker Departure</h2>
            <!-- <a href="{{ route('super_admin.analytics.export.csv', ['type' => 'departure', 'period' => $period]) }}"
                class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-[#ff4040] transition text-sm flex items-center gap-2">
                Export CSV
            </a>

            <a href="{{ route('super_admin.analytics.export.pdf', ['type' => 'departure', 'period' => $period]) }}"
                class="bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition text-sm flex items-center gap-2">
                Export PDF
            </a> -->
        </div>
        <div class="bg-gray-50 rounded-lg p-4" style="height: 380px;">
            <canvas id="departureChart"></canvas>
        </div>
    </div>

    {{-- Methanol Breakdown --}}
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-6">Methanol Mixture Breakdown (Departures)</h2>
        <div class="grid grid-cols-2 gap-6">
            <div class="bg-gray-50 rounded-lg p-4" style="height: 300px;">
                <canvas id="methanolPieChart"></canvas>
            </div>
            <div class="space-y-3 flex flex-col justify-center">
                @foreach(['diesel', 'premium', 'unleaded'] as $fuel)
                @php
                    $row      = $departureTotals[$fuel] ?? null;
                    $total    = $row ? (float)$row->total : 0;
                    $methanol = $row ? (float)$row->total_methanol : 0;
                    $pure     = $total - $methanol;
                    $pct      = $total > 0 ? round($methanol / $total * 100, 1) : 0;
                @endphp
                <div class="bg-white border border-gray-200 rounded-lg p-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-semibold capitalize
                            {{ $fuel === 'diesel' ? 'text-green-700' :
                               ($fuel === 'premium' ? 'text-yellow-700' : 'text-blue-700') }}">
                            {{ ucfirst($fuel) }}
                        </span>
                        <span class="text-sm text-gray-500">{{ number_format($total, 2) }} L total</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                        <div class="h-3 rounded-full
                            {{ $fuel === 'diesel' ? 'bg-green-500' :
                               ($fuel === 'premium' ? 'bg-yellow-500' : 'bg-blue-500') }}"
                             style="width: {{ 100 - $pct }}%"></div>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 mt-1">
                        <span>Pure: {{ number_format($pure, 2) }} L</span>
                        <span>Methanol: {{ number_format($methanol, 2) }} L ({{ $pct }}%)</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const arrivalRaw   = @json($arrivalData);
    const departureRaw = @json($departureData);
    const period       = @json($period);
    const fuelTypes    = ['diesel', 'premium', 'unleaded', 'methanol'];
    const fuelColors   = {
        diesel:   '#22c55e',
        premium:  '#eab308',
        unleaded: '#3b82f6',
        methanol: '#a855f7',
    };

    // The groupBy key changes depending on period
    const groupKey = { daily: 'day', weekly: 'week', monthly: 'month', yearly: 'year' }[period];

    function buildDatasets(raw) {
        const labels = Object.keys(raw).sort();
        return {
            labels,
            datasets: fuelTypes.map(fuel => ({
                label:           fuel.charAt(0).toUpperCase() + fuel.slice(1),
                data:            labels.map(lbl => {
                    const row = raw[lbl]?.find(r => r.fuel_type === fuel);
                    return row ? parseFloat(row.total) : 0;
                }),
                backgroundColor: fuelColors[fuel],
                borderRadius:    4,
            }))
        };
    }

    function makeChart(id, raw) {
        const { labels, datasets } = buildDatasets(raw);
        new Chart(document.getElementById(id), {
            type: 'bar',
            data: { labels, datasets },
            options: {
                responsive:          true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: {
                        callbacks: {
                            label: ctx => `${ctx.dataset.label}: ${ctx.parsed.y.toLocaleString()} L`
                        }
                    }
                },
                scales: {
                    x: { stacked: false },
                    y: {
                        beginAtZero: true,
                        ticks: { callback: v => v.toLocaleString() + ' L' }
                    }
                }
            }
        });
    }

    makeChart('arrivalChart',   arrivalRaw);
    makeChart('departureChart', departureRaw);

    // Methanol pie chart
    const departureTotals = @json($departureTotals);
    const mixedFuels      = ['diesel', 'premium', 'unleaded'];
    const pieLabels       = [];
    const pieData         = [];
    const pieColors       = [];

    mixedFuels.forEach(fuel => {
        const row = departureTotals[fuel];
        if (row && parseFloat(row.total_methanol) > 0) {
            pieLabels.push(fuel.charAt(0).toUpperCase() + fuel.slice(1) + ' (Pure)');
            pieData.push(parseFloat(row.total_pure ?? (row.total - row.total_methanol)));
            pieColors.push(fuelColors[fuel]);

            pieLabels.push(fuel.charAt(0).toUpperCase() + fuel.slice(1) + ' (Methanol)');
            pieData.push(parseFloat(row.total_methanol));
            pieColors.push(fuelColors[fuel] + '80');
        }
    });

    new Chart(document.getElementById('methanolPieChart'), {
        type: 'doughnut',
        data: {
            labels:   pieLabels,
            datasets: [{ data: pieData, backgroundColor: pieColors, borderWidth: 2 }]
        },
        options: {
            responsive:          true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'right', labels: { boxWidth: 12, font: { size: 11 } } },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.parsed.toLocaleString()} L`
                    }
                }
            }
        }
    });
</script>
@endsection