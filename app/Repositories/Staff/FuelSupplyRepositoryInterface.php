<?php

namespace App\Repositories\Staff;

use Illuminate\Support\Collection;

interface FuelSupplyRepositoryInterface
{
    /**
     * Get all fuel supply transactions for the logged-in user.
     */
    public function getUserTransactions(): Collection;
}
