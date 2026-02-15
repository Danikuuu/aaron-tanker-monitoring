<?php

namespace App\Repositories\Admin;

use App\Models\AuditLog;
use App\Models\FuelStock;
use App\Models\TankerArrival;
use App\Models\TankerDeparture;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OverviewRepository implements OverviewInterface
{
    public function getFuelStocks(): Collection
    {
        return FuelStock::all()->keyBy('fuel_type');
    }

    public function getRecentArrivals(int $limit = 10): Collection
    {
        return TankerArrival::with(['fuels', 'recordedBy'])
            ->latest()
            ->take($limit)
            ->get();
    }

    public function getRecentDepartures(int $limit = 10): Collection
    {
        return TankerDeparture::with(['fuels', 'recordedBy'])
            ->latest()
            ->take($limit)
            ->get();
    }

    public function getMonthlyArrivalChart(int $months = 6): Collection
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

    public function getDeliverySummary(): Collection
    {
        return DB::table('tanker_arrival_fuels')
            ->selectRaw('fuel_type, SUM(liters) as total')
            ->groupBy('fuel_type')
            ->pluck('total', 'fuel_type');
    }

    public function getAuditLogs(int $limit = 20): Collection
    {
        return AuditLog::with('user')
            ->latest()
            ->take($limit)
            ->get();
    }
}