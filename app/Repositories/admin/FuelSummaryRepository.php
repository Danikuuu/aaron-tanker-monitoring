<?php

namespace App\Repositories\Admin;

use App\Models\FuelStock;
use App\Models\TankerArrival;
use App\Models\TankerDeparture;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class FuelSummaryRepository implements FuelSummaryInterface
{
    public function getFuelStocks(): Collection
    {
        return FuelStock::all()->keyBy('fuel_type');
    }

    public function getPaginatedArrivals(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        return TankerArrival::with(['fuels', 'recordedBy'])
            ->when(isset($filters['search']) && $filters['search'], fn($q) =>
                $q->where(fn($q2) =>
                    $q2->where('tanker_number', 'like', "%{$filters['search']}%")
                       ->orWhereHas('recordedBy', fn($q3) => $q3->whereRaw("first_name || ' ' || last_name like ?", ["%{$filters['search']}%"]))
                )
            )
            ->when(isset($filters['date_from']) && $filters['date_from'], fn($q) => $q->whereDate('arrival_date', '>=', $filters['date_from']))
            ->when(isset($filters['date_to']) && $filters['date_to'], fn($q) => $q->whereDate('arrival_date', '<=', $filters['date_to']))
            ->latest()
            ->paginate($perPage, ['*'], 'arrival_page')
            ->withQueryString();
    }

    public function getPaginatedDepartures(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        return TankerDeparture::with(['fuels', 'recordedBy'])
            ->when(isset($filters['search']) && $filters['search'], fn($q) =>
                $q->where(fn($q2) =>
                    $q2->where('tanker_number', 'like', "%{$filters['search']}%")
                       ->orWhere('driver', 'like', "%{$filters['search']}%")
                       ->orWhereHas('recordedBy', fn($q3) => $q3->whereRaw("first_name || ' ' || last_name like ?", ["%{$filters['search']}%"]))
                )
            )
            ->when(isset($filters['date_from']) && $filters['date_from'], fn($q) => $q->whereDate('departure_date', '>=', $filters['date_from']))
            ->when(isset($filters['date_to']) && $filters['date_to'], fn($q) => $q->whereDate('departure_date', '<=', $filters['date_to']))
            ->latest()
            ->paginate($perPage, ['*'], 'departure_page')
            ->withQueryString();
    }

    public function getArrivalExportRows(array $filters = []): Collection
    {
        $q = DB::table('tanker_arrival_fuels')
            ->join('tanker_arrivals', 'tanker_arrivals.id', '=', 'tanker_arrival_fuels.tanker_arrival_id')
            ->join('users', 'users.id', '=', 'tanker_arrivals.recorded_by')
            ->selectRaw("tanker_arrivals.id,
                         tanker_arrivals.tanker_number,
                         tanker_arrivals.arrival_date,
                         users.first_name || ' ' || users.last_name as recorded_by,
                         fuel_type,
                         liters");

        if (!empty($filters['search'])) {
            $q->where(function($q2) use ($filters) {
                $q2->where('tanker_arrivals.tanker_number', 'like', "%{$filters['search']}%")
                   ->orWhereRaw("users.first_name || ' ' || users.last_name like ?", ["%{$filters['search']}%"]);
            });
        }
        if (!empty($filters['date_from'])) {
            $q->whereDate('tanker_arrivals.arrival_date', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $q->whereDate('tanker_arrivals.arrival_date', '<=', $filters['date_to']);
        }

        return $q->orderBy('tanker_arrivals.arrival_date', 'desc')->get();
    }

    public function getDepartureExportRows(array $filters = []): Collection
    {
        $q = DB::table('tanker_departure_fuels')
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
                         pure_liters");

        if (!empty($filters['search'])) {
            $q->where(function($q2) use ($filters) {
                $q2->where('tanker_departures.tanker_number', 'like', "%{$filters['search']}%")
                   ->orWhere('tanker_departures.driver', 'like', "%{$filters['search']}%")
                   ->orWhereRaw("users.first_name || ' ' || users.last_name like ?", ["%{$filters['search']}%"]);
            });
        }
        if (!empty($filters['date_from'])) {
            $q->whereDate('tanker_departures.departure_date', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $q->whereDate('tanker_departures.departure_date', '<=', $filters['date_to']);
        }

        return $q->orderBy('tanker_departures.departure_date', 'desc')->get();
    }
}