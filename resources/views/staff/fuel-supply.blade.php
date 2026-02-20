<!-- resources/views/staff/fuel-supply.blade.php -->
@extends('staff.layout.guest')

@section('title', 'Fuel Supply In/Out')

@section('content')

<style>
    .fs-card {
        background: #fff;
        border-radius: 18px;
        width: 100%;
        max-width: 900px;
        margin: 0 auto;
        overflow: hidden;
    }
    .fs-card-inner { padding: 28px 28px 32px; }
    @media (max-width: 480px) { .fs-card-inner { padding: 20px 16px 24px; } }

    .fs-logo { height: 64px; width: auto; }
    @media (max-width: 480px) { .fs-logo { height: 48px; } }
    .fs-heading { font-size: 1.25rem; font-weight: 700; color: #111; margin-top: 10px; }

    /* Tabs */
    .fs-tabs { display: flex; gap: 6px; background: #f5f5f5; border-radius: 12px; padding: 4px; margin-bottom: 16px; }
    .fs-tab {
        flex: 1; padding: 8px 12px; border-radius: 9px; border: none;
        background: transparent; font-size: 0.82rem; font-weight: 600; color: #999;
        cursor: pointer; transition: all 0.18s ease;
        display: flex; align-items: center; justify-content: center; gap: 6px;
    }
    .fs-tab.active { background: #fff; color: #E53E3E; box-shadow: 0 1px 6px rgba(0,0,0,0.1); }
    .fs-tab:hover:not(.active) { color: #555; }
    .tab-count { background: #E53E3E; color: #fff; border-radius: 20px; padding: 1px 7px; font-size: 0.68rem; }

    /* Search */
    .fs-search-wrap { position: relative; margin-bottom: 16px; }
    .fs-search {
        width: 100%; padding: 10px 42px 10px 14px;
        border: 1.5px solid #e5e5e5; border-radius: 10px;
        font-size: 0.875rem; outline: none;
        transition: border-color 0.15s; box-sizing: border-box; color: #333;
    }
    .fs-search:focus { border-color: #E53E3E; }
    .fs-search-icon { position: absolute; right: 13px; top: 50%; transform: translateY(-50%); color: #bbb; pointer-events: none; }

    /* Table */
    .fs-table-wrap { overflow-x: auto; border-radius: 10px; border: 1px solid #f0f0f0; }
    .fs-table { width: 100%; border-collapse: collapse; font-size: 0.875rem; min-width: 580px; }
    .fs-table thead tr { background: #E53E3E; }
    .fs-table thead th {
        padding: 12px 16px; text-align: left; color: #fff;
        font-size: 0.72rem; font-weight: 700; letter-spacing: 0.07em;
        text-transform: uppercase; white-space: nowrap;
    }
    .fs-table tbody tr { border-bottom: 1px solid #f3f3f3; transition: background 0.1s; }
    .fs-table tbody tr:last-child { border-bottom: none; }
    .fs-table tbody tr:hover { background: #fff5f5; }
    .fs-table td { padding: 12px 16px; color: #444; vertical-align: middle; }

    .fs-empty { text-align: center; padding: 40px 16px; color: #bbb; font-size: 0.85rem; }

    /* Fuel badges */
    .badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 10px; border-radius: 20px; font-size: 0.72rem; font-weight: 600;
        white-space: nowrap;
    }
    .badge-diesel   { background:#F0FFF4; color:#276749; border:1px solid #C6F6D5; }
    .badge-premium  { background:#FFFFF0; color:#744210; border:1px solid #FAF089; }
    .badge-unleaded { background:#EBF8FF; color:#2A4365; border:1px solid #BEE3F8; }
    .badge-methanol { background:#FAF5FF; color:#553C9A; border:1px solid #E9D8FD; }

    /* Liters cell with methanol breakdown */
    .liters-cell { display: flex; flex-direction: column; gap: 3px; }
    .liters-total { font-weight: 600; color: #222; font-size: 0.875rem; }
    .liters-breakdown {
        display: flex; flex-wrap: wrap; gap: 6px; margin-top: 2px;
    }
    .breakdown-pill {
        display: inline-flex; align-items: center; gap: 3px;
        padding: 2px 8px; border-radius: 6px; font-size: 0.68rem; font-weight: 600;
    }
    .pill-pure     { background: #F0FFF4; color: #276749; border: 1px solid #C6F6D5; }
    .pill-methanol { background: #FFFBEB; color: #92600A; border: 1px solid #FCD34D; }
    .pill-pct      { background: #FEF3C7; color: #92600A; border: 1px solid #FDE68A; }

    /* Tab panels */
    .tab-panel { display: none; }
    .tab-panel.active { display: block; }

    /* Footer */
    .fs-footer {
        background: #E53E3E; padding: 14px; text-align: center;
        color: #fff; font-size: 0.8rem;
        position: fixed; bottom: 0; left: 0; right: 0; z-index: 50;
    }
</style>

<div class="flex-1 flex items-start justify-center px-4 sm:px-6 py-6 sm:py-10" style="padding-bottom: 80px;">
    <div class="fs-card">
        <div class="fs-card-inner">

            {{-- Logo & Title --}}
            <div class="text-center mb-6">
                <img src="{{ asset('images/aaron-staff.png') }}" class="fs-logo mx-auto mb-3">
                <h2 class="fs-heading">Fuel Supply In / Out</h2>
            </div>

            {{-- Tabs --}}
            <div class="fs-tabs">
                <button class="fs-tab active" data-tab="arrivals">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                    Arrivals
                    <span class="tab-count">{{ $arrivals->count() }}</span>
                </button>
                <button class="fs-tab" data-tab="departures">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                    Departures
                    <span class="tab-count">{{ $departures->count() }}</span>
                </button>
            </div>

            {{-- Search --}}
            <div class="fs-search-wrap">
                <input type="search" id="fs-search" placeholder="Search tanker, fuel type..." class="fs-search">
                <svg class="fs-search-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>

            {{-- ARRIVALS --}}
            <div class="tab-panel active" id="panel-arrivals">
                <div class="fs-table-wrap">
                    <table class="fs-table">
                        <thead>
                            <tr>
                                <th>Tanker Number</th>
                                <th>Arrival Date</th>
                                <!-- <th>Driver</th> -->
                                <th>Fuel Type</th>
                                <th>Litres Delivered</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-arrivals">
                            @forelse($arrivals as $arrival)
                                @foreach($arrival->fuels as $fuel)
                                <tr>
                                    <td style="font-weight:600;">{{ $arrival->tanker_number }}</td>
                                    <td>{{ $arrival->arrival_date->format('M d, Y') }}</td>
                                    <!-- <td style="color:#888;">{{ $arrival->driver ?? '—' }}</td> -->
                                    <td><span class="badge badge-{{ $fuel->fuel_type }}">{{ ucfirst($fuel->fuel_type) }}</span></td>
                                    <td>{{ number_format($fuel->liters, 2) }} L</td>
                                </tr>
                                @endforeach
                            @empty
                            <tr><td colspan="5" class="fs-empty">No arrival records found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- DEPARTURES --}}
            <div class="tab-panel" id="panel-departures">
                <div class="fs-table-wrap">
                    <table class="fs-table">
                        <thead>
                            <tr>
                                <th>Tanker Number</th>
                                <th>Departure Date</th>
                                <th>Driver</th>
                                <th>Fuel Type</th>
                                <th>Litres Delivered</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-departures">
                            @forelse($departures as $departure)
                                @foreach($departure->fuels as $fuel)
                                <tr>
                                    <td style="font-weight:600;">{{ $departure->tanker_number }}</td>
                                    <td>{{ $departure->departure_date->format('M d, Y') }}</td>
                                    <td style="color:#888;">{{ $departure->driver ?? '—' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $fuel->fuel_type }}">
                                            {{ ucfirst($fuel->fuel_type) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            
                                            $pureLiters     = $fuel->liters;
                                            $methanolLiters = $fuel->methanol_liters ?? 0;
                                            $totalLiters    = $pureLiters + $methanolLiters;
                                            $methanolPct    = $fuel->methanol_percent ?? 0;
                                            $hasMethanol    = $methanolLiters > 0;
                                        @endphp

                                        <div class="liters-cell">
                                            <span class="liters-total">
                                                {{ number_format($totalLiters, 2) }} L
                                            </span>

                                            @if($hasMethanol)
                                            <div class="liters-breakdown">
                                                <span class="breakdown-pill pill-pure">
                                                    <svg width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    {{ number_format($pureLiters, 2) }} L pure
                                                </span>
                                                <span class="breakdown-pill pill-methanol">
                                                    ⚗️ {{ number_format($methanolLiters, 2) }} L methanol
                                                </span>
                                                <span class="breakdown-pill pill-pct">
                                                    {{ $methanolPct }}% mix
                                                </span>
                                            </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @empty
                            <tr><td colspan="5" class="fs-empty">No departure records found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<footer class="fs-footer">
    © 2026 Aaron Gas Station. All Rights Reserved.
</footer>

<script>
    const tabs   = document.querySelectorAll('.fs-tab');
    const panels = document.querySelectorAll('.tab-panel');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            panels.forEach(p => p.classList.remove('active'));
            tab.classList.add('active');
            document.getElementById('panel-' + tab.dataset.tab).classList.add('active');
            document.getElementById('fs-search').value = '';
            filterRows('');
        });
    });

    function filterRows(q) {
        document.querySelector('.tab-panel.active').querySelectorAll('tbody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    }

    document.getElementById('fs-search').addEventListener('input', function () {
        filterRows(this.value.toLowerCase());
    });
</script>

@endsection