<?php

namespace App\Repositories\Admin;

use App\Models\TankerArrival;
use App\Models\TankerDeparture;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TransactionHistoryRepository implements TransactionHistoryInterface
{
    public function getPaginatedTransactions(
        int    $perPage  = 10,
        string $type     = 'all',
        string $search   = '',
        string $dateFrom = '',
        string $dateTo   = ''
    ): LengthAwarePaginator {

        // Build a unified UNION query of arrivals + departures
        $arrivals = DB::table('tanker_arrivals')
            ->join('users', 'users.id', '=', 'tanker_arrivals.recorded_by')
            ->selectRaw("
                tanker_arrivals.id,
                'arrival'   as type,
                tanker_arrivals.tanker_number,
                NULL        as driver,
                tanker_arrivals.arrival_date   as transaction_date,
                users.first_name || ' ' || users.last_name as recorded_by,
                tanker_arrivals.created_at
            ");

        $departures = DB::table('tanker_departures')
            ->join('users', 'users.id', '=', 'tanker_departures.recorded_by')
            ->selectRaw("
                tanker_departures.id,
                'departure' as type,
                tanker_departures.tanker_number,
                tanker_departures.driver,
                tanker_departures.departure_date as transaction_date,
                users.first_name || ' ' || users.last_name as recorded_by,
                tanker_departures.created_at
            ");

        // Apply type filter before union
        if ($type === 'arrival') {
            $query = $arrivals;
        } elseif ($type === 'departure') {
            $query = $departures;
        } else {
            $query = $arrivals->unionAll($departures);
        }

        // Wrap in subquery for filtering + sorting
        $results = DB::table(DB::raw("({$query->toSql()}) as transactions"))
            ->mergeBindings($type === 'arrival' ? $arrivals : ($type === 'departure' ? $departures : $arrivals->unionAll($departures)))
            ->when($search, fn($q) =>
                $q->where(fn($q2) =>
                    $q2->where('tanker_number', 'like', "%{$search}%")
                       ->orWhere('recorded_by', 'like', "%{$search}%")
                       ->orWhere('driver', 'like', "%{$search}%")
                )
            )
            ->when($dateFrom, fn($q) => $q->where('transaction_date', '>=', $dateFrom))
            ->when($dateTo,   fn($q) => $q->where('transaction_date', '<=', $dateTo))
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        // Eager load fuels for each transaction
        $this->hydrateFuels($results);

        return $results;
    }

    public function getExportRows(
        string $type     = 'all',
        string $search   = '',
        string $dateFrom = '',
        string $dateTo   = ''
    ): Collection {
        $arrivals = DB::table('tanker_arrivals')
            ->join('users', 'users.id', '=', 'tanker_arrivals.recorded_by')
            ->join('tanker_arrival_fuels', 'tanker_arrivals.id', '=', 'tanker_arrival_fuels.tanker_arrival_id')
            ->selectRaw("
                tanker_arrivals.id,
                'arrival' as type,
                tanker_arrivals.tanker_number,
                NULL as driver,
                tanker_arrivals.arrival_date as transaction_date,
                users.first_name || ' ' || users.last_name as recorded_by,
                tanker_arrival_fuels.fuel_type,
                tanker_arrival_fuels.liters,
                NULL as methanol_percent,
                NULL as methanol_liters
            ");

        $departures = DB::table('tanker_departures')
            ->join('users', 'users.id', '=', 'tanker_departures.recorded_by')
            ->join('tanker_departure_fuels', 'tanker_departures.id', '=', 'tanker_departure_fuels.tanker_departure_id')
            ->selectRaw("
                tanker_departures.id,
                'departure' as type,
                tanker_departures.tanker_number,
                tanker_departures.driver,
                tanker_departures.departure_date as transaction_date,
                users.first_name || ' ' || users.last_name as recorded_by,
                tanker_departure_fuels.fuel_type,
                tanker_departure_fuels.liters,
                tanker_departure_fuels.methanol_percent,
                tanker_departure_fuels.methanol_liters
            ");

        if ($type === 'arrival') {
            $query = $arrivals;
        } elseif ($type === 'departure') {
            $query = $departures;
        } else {
            $query = $arrivals->unionAll($departures);
        }

        return DB::table(DB::raw("({$query->toSql()}) as transactions"))
            ->mergeBindings($type === 'arrival' ? $arrivals : ($type === 'departure' ? $departures : $arrivals->unionAll($departures)))
            ->when($search,   fn($q) => $q->where('tanker_number', 'like', "%{$search}%"))
            ->when($dateFrom, fn($q) => $q->where('transaction_date', '>=', $dateFrom))
            ->when($dateTo,   fn($q) => $q->where('transaction_date', '<=', $dateTo))
            ->orderBy('transaction_date', 'desc')
            ->get();
    }

    // Attach fuel rows to each paginated transaction item
    private function hydrateFuels(\Illuminate\Pagination\LengthAwarePaginator $paginator): void
    {
        $arrivalIds   = [];
        $departureIds = [];

        foreach ($paginator->items() as $item) {
            if ($item->type === 'arrival') {
                $arrivalIds[] = $item->id;
            } else {
                $departureIds[] = $item->id;
            }
        }

        $arrivalFuels = DB::table('tanker_arrival_fuels')
            ->whereIn('tanker_arrival_id', $arrivalIds)
            ->get()
            ->groupBy('tanker_arrival_id');

        $departureFuels = DB::table('tanker_departure_fuels')
            ->whereIn('tanker_departure_id', $departureIds)
            ->get()
            ->groupBy('tanker_departure_id');

        foreach ($paginator->items() as $item) {
            $item->fuels = $item->type === 'arrival'
                ? ($arrivalFuels[$item->id] ?? collect())
                : ($departureFuels[$item->id] ?? collect());
        }
    }
}