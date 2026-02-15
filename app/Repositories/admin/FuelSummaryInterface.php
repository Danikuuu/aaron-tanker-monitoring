<?php

namespace App\Repositories\Admin;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface FuelSummaryInterface
{
    public function getFuelStocks(): Collection;

    public function getPaginatedArrivals(int $perPage): LengthAwarePaginator;

    public function getPaginatedDepartures(int $perPage): LengthAwarePaginator;

    public function getArrivalExportRows(): Collection;

    public function getDepartureExportRows(): Collection;
}