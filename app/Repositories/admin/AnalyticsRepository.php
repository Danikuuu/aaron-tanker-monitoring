<?php

namespace App\Repositories\Admin;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsRepository implements AnalyticsInterface
{
    /**
     * Return the start date and SQLite strftime group-by format for a given period.
     */
    private function periodMeta(string $period): array
    {
        return match ($period) {
            'daily'   => [
                'start'  => now()->subDays(30)->startOfDay(),
                'format' => "%Y-%m-%d",
                'column' => 'day',
            ],
            'weekly'  => [
                'start'  => now()->subWeeks(12)->startOfWeek(),
                'format' => "%Y-W%W",
                'column' => 'week',
            ],
            'yearly'  => [
                'start'  => now()->subYears(5)->startOfYear(),
                'format' => "%Y",
                'column' => 'year',
            ],
            default   => [                          // monthly
                'start'  => now()->subMonths(12)->startOfMonth(),
                'format' => "%Y-%m",
                'column' => 'month',
            ],
        };
    }

    // ── Arrival Chart ─────────────────────────────────────────────────────────

    public function getArrivalChart(string $period): Collection
    {
        ['start' => $start, 'format' => $fmt, 'column' => $col] = $this->periodMeta($period);

        return DB::table('tanker_arrival_fuels')
            ->join('tanker_arrivals', 'tanker_arrivals.id', '=', 'tanker_arrival_fuels.tanker_arrival_id')
            ->selectRaw("strftime('{$fmt}', tanker_arrivals.arrival_date) as {$col}, fuel_type, SUM(liters) as total")
            ->where('tanker_arrivals.arrival_date', '>=', $start)
            ->groupBy($col, 'fuel_type')
            ->orderBy($col)
            ->get()
            ->groupBy($col);
    }

    // ── Departure Chart ───────────────────────────────────────────────────────

    public function getDepartureChart(string $period): Collection
    {
        ['start' => $start, 'format' => $fmt, 'column' => $col] = $this->periodMeta($period);

        return DB::table('tanker_departure_fuels')
            ->join('tanker_departures', 'tanker_departures.id', '=', 'tanker_departure_fuels.tanker_departure_id')
            ->selectRaw("strftime('{$fmt}', tanker_departures.departure_date) as {$col},
                         fuel_type,
                         SUM(liters) as total,
                         SUM(methanol_liters) as total_methanol,
                         SUM(pure_liters) as total_pure")
            ->where('tanker_departures.departure_date', '>=', $start)
            ->groupBy($col, 'fuel_type')
            ->orderBy($col)
            ->get()
            ->groupBy($col);
    }

    // ── Summary Totals ────────────────────────────────────────────────────────

    public function getArrivalTotals(string $period): Collection
    {
        ['start' => $start] = $this->periodMeta($period);

        return DB::table('tanker_arrival_fuels')
            ->join('tanker_arrivals', 'tanker_arrivals.id', '=', 'tanker_arrival_fuels.tanker_arrival_id')
            ->selectRaw('fuel_type, SUM(liters) as total')
            ->where('tanker_arrivals.arrival_date', '>=', $start)
            ->groupBy('fuel_type')
            ->pluck('total', 'fuel_type');
    }

    public function getDepartureTotals(string $period): Collection
    {
        ['start' => $start] = $this->periodMeta($period);

        return DB::table('tanker_departure_fuels')
            ->join('tanker_departures', 'tanker_departures.id', '=', 'tanker_departure_fuels.tanker_departure_id')
            ->selectRaw('fuel_type, SUM(liters) as total, SUM(methanol_liters) as total_methanol, SUM(pure_liters) as total_pure')
            ->where('tanker_departures.departure_date', '>=', $start)
            ->groupBy('fuel_type')
            ->get()
            ->keyBy('fuel_type');
    }

    // ── CSV Export Rows ───────────────────────────────────────────────────────

    public function getArrivalExportRows(string $period): Collection
    {
        ['start' => $start] = $this->periodMeta($period);

        return DB::table('tanker_arrival_fuels')
            ->join('tanker_arrivals', 'tanker_arrivals.id', '=', 'tanker_arrival_fuels.tanker_arrival_id')
            ->join('users', 'users.id', '=', 'tanker_arrivals.recorded_by')
            ->selectRaw("tanker_arrivals.id,
                         tanker_arrivals.tanker_number,
                         tanker_arrivals.arrival_date,
                         users.first_name || ' ' || users.last_name as recorded_by,
                         fuel_type,
                         liters")
            ->where('tanker_arrivals.arrival_date', '>=', $start)
            ->orderBy('tanker_arrivals.arrival_date')
            ->get();
    }

    public function getDepartureExportRows(string $period): Collection
    {
        ['start' => $start] = $this->periodMeta($period);

        return DB::table('tanker_departure_fuels')
            ->join('tanker_departures', 'tanker_departures.id', '=', 'tanker_departure_fuels.tanker_departure_id')
            ->join('users', 'users.id', '=', 'tanker_departures.recorded_by')
            ->selectRaw("tanker_departures.id,
                         tanker_departures.tanker_number,
                         tanker_departures.driver,
                         tanker_departures.departure_date,
                         users.first_name || ' ' || users.last_name as recorded_by,
                         fuel_type,
                         liters,
                         methanol_percent,
                         methanol_liters,
                         pure_liters")
            ->where('tanker_departures.departure_date', '>=', $start)
            ->orderBy('tanker_departures.departure_date')
            ->get();
    }
}