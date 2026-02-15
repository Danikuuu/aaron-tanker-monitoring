<?php

namespace App\Repositories\Staff;

use Illuminate\Support\Collection;

interface TankerHistoryRepositoryInterface
{
    public function getUserArrivals(int $userId): Collection;

    public function getUserDepartures(int $userId): Collection;
}
