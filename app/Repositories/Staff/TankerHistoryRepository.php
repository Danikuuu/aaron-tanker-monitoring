<?php

namespace App\Repositories\Staff;

use App\Models\TankerArrival;
use App\Models\TankerDeparture;
use Illuminate\Support\Collection;

class TankerHistoryRepository implements TankerHistoryRepositoryInterface
{
    public function getUserArrivals(int $userId): Collection
    {
        return TankerArrival::with('fuels')
            ->where('recorded_by', $userId)
            ->orderBy('arrival_date', 'desc')
            ->get();
    }

    public function getUserDepartures(int $userId): Collection
    {
        return TankerDeparture::with('fuels')
            ->where('recorded_by', $userId)
            ->orderBy('departure_date', 'desc')
            ->get();
    }
}
