<?php

namespace App\Repositories\Staff;

use App\Models\TankerArrival;
use App\Models\TankerArrivalFuel;
use App\Repositories\Staff\TankerArrivalRepositoryInterface;

class TankerArrivalRepository implements TankerArrivalRepositoryInterface
{
    public function create(array $data): TankerArrival
    {
        return TankerArrival::create($data);
    }

    public function addFuel(TankerArrival $arrival, array $fuelData): void
    {
        $arrival->fuels()->create($fuelData);
    }
}
