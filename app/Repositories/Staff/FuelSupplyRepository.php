<?php

namespace App\Repositories\Staff;

use App\Models\TankerArrivalFuel;
use App\Models\TankerDepartureFuel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class FuelSupplyRepository implements FuelSupplyRepositoryInterface
{
    public function getUserTransactions(): Collection
    {
        $userId = Auth::id();

        // Arrivals
        $arrivals = TankerArrivalFuel::with('arrival')
            ->whereHas('arrival', fn($q) => $q->where('recorded_by', $userId))
            ->get()
            ->map(fn($fuel) => [
                'id'            => $fuel->id,
                'tanker_number' => $fuel->arrival->tanker_number,
                'date'          => $fuel->arrival->arrival_date,
                'fuel_type'     => $fuel->fuel_type,
                'liters'        => $fuel->liters,
                'type'          => 'In',
            ]);

        // Departures
        $departures = TankerDepartureFuel::with('departure')
            ->whereHas('departure', fn($q) => $q->where('recorded_by', $userId))
            ->get()
            ->map(fn($fuel) => [
                'id'            => $fuel->id,
                'tanker_number' => $fuel->departure->tanker_number,
                'date'          => $fuel->departure->departure_date,
                'fuel_type'     => $fuel->fuel_type,
                'liters'        => $fuel->liters,
                'type'          => 'Out',
            ]);

        // Merge and sort by date descending
        return $arrivals->merge($departures)->sortByDesc('date')->values();
    }
}
