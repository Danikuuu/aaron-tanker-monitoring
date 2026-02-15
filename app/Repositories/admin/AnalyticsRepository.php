<?php

namespace App\Repositories\Admin;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AnalyticsRepository implements AnalyticsInterface
{
    public function getMonthlyArrivalChart(int $months): Collection
    {
        return DB::table('tanker_arrival_fuels')
            ->join('tanker_arrivals', 'tanker_arrivals.id', '=', 'tanker_arrival_fuels.tanker_arrival_id')
            ->selectRaw("strftime('%Y-%m', tanker_arrivals.arrival_date) as month, fuel_type, SUM(liters) as total")
            ->where('tanker_arrivals.arrival_date', '>=', now()->subMonths($months)->startOfMonth())
            ->groupBy('month', 'fuel_type')
            ->orderBy('month')
            ->get()
            ->groupBy('month');
    }

    public function getMonthlyDepartureChart(int $months): Collection
    {
        return DB::table('tanker_departure_fuels')
            ->join('tanker_departures', 'tanker_departures.id', '=', 'tanker_departure_fuels.tanker_departure_id')
            ->selectRaw("strftime('%Y-%m', tanker_departures.departure_date) as month,
                         fuel_type,
                         SUM(liters) as total,
                         SUM(methanol_liters) as total_methanol,
                         SUM(pure_liters) as total_pure")
            ->where('tanker_departures.departure_date', '>=', now()->subMonths($months)->startOfMonth())
            ->groupBy('month', 'fuel_type')
            ->orderBy('month')
            ->get()
            ->groupBy('month');
    }

    public function getArrivalTotals(int $months): Collection
    {
        return DB::table('tanker_arrival_fuels')
            ->join('tanker_arrivals', 'tanker_arrivals.id', '=', 'tanker_arrival_fuels.tanker_arrival_id')
            ->selectRaw('fuel_type, SUM(liters) as total')
            ->where('tanker_arrivals.arrival_date', '>=', now()->subMonths($months)->startOfMonth())
            ->groupBy('fuel_type')
            ->pluck('total', 'fuel_type');
    }

    public function getDepartureTotals(int $months): Collection
    {
        return DB::table('tanker_departure_fuels')
            ->join('tanker_departures', 'tanker_departures.id', '=', 'tanker_departure_fuels.tanker_departure_id')
            ->selectRaw('fuel_type, SUM(liters) as total, SUM(methanol_liters) as total_methanol, SUM(pure_liters) as total_pure')
            ->where('tanker_departures.departure_date', '>=', now()->subMonths($months)->startOfMonth())
            ->groupBy('fuel_type')
            ->get()
            ->keyBy('fuel_type');
    }

    public function getArrivalExportRows(int $months): Collection
    {
        return DB::table('tanker_arrival_fuels')
            ->join('tanker_arrivals', 'tanker_arrivals.id', '=', 'tanker_arrival_fuels.tanker_arrival_id')
            ->join('users', 'users.id', '=', 'tanker_arrivals.recorded_by')
            ->selectRaw("tanker_arrivals.id,
                         tanker_arrivals.tanker_number,
                         tanker_arrivals.arrival_date,
                         users.first_name || ' ' || users.last_name as recorded_by,
                         fuel_type,
                         liters")
            ->where('tanker_arrivals.arrival_date', '>=', now()->subMonths($months)->startOfMonth())
            ->orderBy('tanker_arrivals.arrival_date')
            ->get();
    }

    public function getDepartureExportRows(int $months): Collection
    {
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
            ->where('tanker_departures.departure_date', '>=', now()->subMonths($months)->startOfMonth())
            ->orderBy('tanker_departures.departure_date')
            ->get();
    }
}