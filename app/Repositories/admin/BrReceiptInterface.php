<?php

namespace App\Repositories\Admin;

use Illuminate\Support\Collection;

interface BrReceiptInterface
{
    /**
     * Get all tanker departures with their fuels for the dropdown.
     */
    public function getAllDepartures(): Collection;
}