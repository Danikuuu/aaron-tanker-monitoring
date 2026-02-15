<?php

namespace App\Repositories\Staff;

use App\Models\TankerDeparture;

interface TankerDepartureRepositoryInterface
{
    public function create(array $data): TankerDeparture;

    public function addFuel(TankerDeparture $departure, array $fuelData): void;
}
