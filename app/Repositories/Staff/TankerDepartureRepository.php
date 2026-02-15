<?php

namespace App\Repositories\Staff;

use App\Models\TankerDeparture;
use App\Repositories\Staff\TankerDepartureRepositoryInterface;

class TankerDepartureRepository implements TankerDepartureRepositoryInterface
{
    public function create(array $data): TankerDeparture
    {
        return TankerDeparture::create($data);
    }

    public function addFuel(TankerDeparture $departure, array $fuelData): void
    {
        $departure->fuels()->create($fuelData);
    }
}
