<?php

namespace App\Services\Staff;

use App\Repositories\Staff\FuelSupplyRepositoryInterface;
use Illuminate\Support\Collection;

class FuelSupplyService
{
    public function __construct(protected FuelSupplyRepositoryInterface $repository) {}

    public function getUserTransactions(): Collection
    {
        return $this->repository->getUserTransactions();
    }
}
