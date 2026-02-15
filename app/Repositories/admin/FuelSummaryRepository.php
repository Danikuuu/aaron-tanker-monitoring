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

    public function getPaginatedArrivals(int $perPage = 10): LengthAwarePaginator
    {
        return TankerArrival::with(['fuels', 'recordedBy'])
            ->latest()
            ->paginate($perPage, ['*'], 'arrival_page');
    }

    public function getPaginatedDepartures(int $perPage = 10): LengthAwarePaginator
    {
        return TankerDeparture::with(['fuels', 'recordedBy'])
            ->latest()
            ->paginate($perPage, ['*'], 'departure_page');
    }

    public function getArrivalExportRows(): Collection
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
            ->orderBy('tanker_arrivals.arrival_date', 'desc')
            ->get();
    }

    public function getDepartureExportRows(): Collection
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
            ->orderBy('tanker_departures.departure_date', 'desc')
            ->get();
    }
}