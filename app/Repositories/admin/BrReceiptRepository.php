<?php

namespace App\Repositories\Admin;

use App\Models\TankerDeparture;
use Illuminate\Support\Collection;

class BrReceiptRepository implements BrReceiptInterface
{
    /**
     * Get all tanker departures with their fuels, newest first.
     */
    public function getAllDepartures(): Collection
    {
        return TankerDeparture::with('fuels')
            ->orderByDesc('departure_date')
            ->get()
            ->map(fn(TankerDeparture $d) => [
                'id'             => $d->id,
                'tanker_number'  => $d->tanker_number,
                'driver'         => $d->driver,
                'departure_date' => $d->departure_date->format('m/d/Y'),
                'label'          => "{$d->tanker_number} — {$d->driver} ({$d->departure_date->format('m/d/Y')})",
                'fuels'          => $d->fuels->map(fn($f) => [
                    'fuel_type'      => $f->fuel_type,
                    'liters'         => $f->liters,
                    'methanol_liters' => $f->methanol_liters ?? 0,
                ])->values(),
            ]);
    }
}