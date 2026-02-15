<?php

namespace App\Services\Staff;

use App\Repositories\Staff\TankerHistoryRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class TankerHistoryService
{
    public function __construct(
        protected TankerHistoryRepositoryInterface $repository
    ) {}

    public function getUserHistory(): array
    {
        $userId = Auth::id();

        return [
            'arrivals'   => $this->repository->getUserArrivals($userId),
            'departures' => $this->repository->getUserDepartures($userId),
        ];
    }
}
