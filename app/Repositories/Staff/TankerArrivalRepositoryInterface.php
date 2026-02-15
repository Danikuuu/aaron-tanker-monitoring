<?php

namespace App\Repositories\Staff;

use App\Models\TankerArrival;

interface TankerArrivalRepositoryInterface
{
    public function create(array $data): TankerArrival;

    public function addFuel(TankerArrival $arrival, array $fuelData): void;
}
