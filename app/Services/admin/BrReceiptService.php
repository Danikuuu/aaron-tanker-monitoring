<?php

namespace App\Services\Admin;

use App\Repositories\Admin\BrReceiptInterface;
use Illuminate\Support\Collection;

class BrReceiptService
{
    public function __construct(
        protected BrReceiptInterface $repository
    ) {}

    /**
     * Return all departures shaped for the BR Receipt dropdown.
     */
    public function getDepartures(): Collection
    {
        return $this->repository->getAllDepartures();
    }
}