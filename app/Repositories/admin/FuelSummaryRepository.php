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
        $search = trim((string) ($filters['search'] ?? ''));
        $dateFrom = (string) ($filters['date_from'] ?? '');
        $dateTo = (string) ($filters['date_to'] ?? '');

        return TankerArrival::with(['fuels', 'recordedBy'])
            ->when($search !== '', fn($q) =>
                $q->where(fn($q2) =>
                    $q2->where('tanker_number', 'like', "%{$search}%")
                       ->orWhere('driver', 'like', "%{$search}%")
                       ->orWhereHas('recordedBy', fn($q3) =>
                           $q3->where('first_name', 'like', "%{$search}%")
                              ->orWhere('last_name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%")
                              ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"])
                       )
                )
            )
            ->when($dateFrom !== '', fn($q) => $q->whereDate('arrival_date', '>=', $dateFrom))
            ->when($dateTo !== '', fn($q) => $q->whereDate('arrival_date', '<=', $dateTo))
            ->latest()
            ->paginate($perPage, ['*'], 'arrival_page')
            ->withQueryString();
    }

    public function getPaginatedDepartures(int $perPage = 10, array $filters = []): LengthAwarePaginator
    {
        $search = trim((string) ($filters['search'] ?? ''));
        $dateFrom = (string) ($filters['date_from'] ?? '');
        $dateTo = (string) ($filters['date_to'] ?? '');

        return TankerDeparture::with(['fuels', 'recordedBy'])
            ->when($search !== '', fn($q) =>
                $q->where(fn($q2) =>
                    $q2->where('tanker_number', 'like', "%{$search}%")
                       ->orWhere('driver', 'like', "%{$search}%")
                       ->orWhereHas('recordedBy', fn($q3) =>
                           $q3->where('first_name', 'like', "%{$search}%")
                              ->orWhere('last_name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%")
                              ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"])
                       )
                )
            )
            ->when($dateFrom !== '', fn($q) => $q->whereDate('departure_date', '>=', $dateFrom))
            ->when($dateTo !== '', fn($q) => $q->whereDate('departure_date', '<=', $dateTo))
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
                         CONCAT(users.first_name, ' ', users.last_name) as recorded_by,
                         fuel_type,
                         liters");

        if (!empty($filters['search'])) {
            $q->where(function ($q2) use ($filters) {
                $q2->where('tanker_arrivals.tanker_number', 'like', "%{$filters['search']}%")
                   ->orWhereRaw("CONCAT(users.first_name, ' ', users.last_name) LIKE ?", ["%{$filters['search']}%"]);
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
                         CONCAT(users.first_name, ' ', users.last_name) as recorded_by,
                         fuel_type,
                         liters,
                         methanol_percent,
                         methanol_liters,
                         pure_liters");

        if (!empty($filters['search'])) {
            $q->where(function ($q2) use ($filters) {
                $q2->where('tanker_departures.tanker_number', 'like', "%{$filters['search']}%")
                   ->orWhere('tanker_departures.driver', 'like', "%{$filters['search']}%")
                   ->orWhereRaw("CONCAT(users.first_name, ' ', users.last_name) LIKE ?", ["%{$filters['search']}%"]);
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

    /**
     * Find a tanker arrival by ID (eager-loads fuels).
     */
    public function findArrival(int $id): TankerArrival
    {
        return TankerArrival::with('fuels')->findOrFail($id);
    }

    /**
     * Update a tanker arrival and sync its child fuel lines inside a transaction.
     */
    public function updateArrival(TankerArrival $arrival, array $data): TankerArrival
    {
        return DB::transaction(function () use ($arrival, $data) {
            $arrival->update([
                'tanker_number' => $data['tanker_number'],
                'arrival_date'  => $data['arrival_date'],
            ]);

            foreach ($data['fuels'] as $fuelData) {
                $arrival->fuels()
                    ->where('id', $fuelData['id'])
                    ->update([
                        'liters' => $fuelData['liters'],
                    ]);
            }

            return $arrival->fresh('fuels');
        });
    }

    /**
     * Find a tanker departure by ID (eager-loads fuels).
     */
    public function findDeparture(int $id): TankerDeparture
    {
        return TankerDeparture::with('fuels')->findOrFail($id);
    }

    /**
     * Update a tanker departure and sync its child fuel lines inside a transaction.
     */
    public function updateDeparture(TankerDeparture $departure, array $data): TankerDeparture
    {
        return DB::transaction(function () use ($departure, $data) {
            $departure->update([
                'tanker_number'  => $data['tanker_number'],
                'driver'         => $data['driver'],
                'departure_date' => $data['departure_date'],
            ]);

            foreach ($data['fuels'] as $fuelData) {
                $departure->fuels()
                    ->where('id', $fuelData['id'])
                    ->update([
                        'liters'           => $fuelData['liters'],
                        'methanol_liters'  => $fuelData['methanol_liters']  ?? 0,
                        'methanol_percent' => $fuelData['methanol_percent'] ?? 0,
                    ]);
            }

            return $departure->fresh('fuels');
        });
    }
}