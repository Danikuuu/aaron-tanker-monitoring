<?php

namespace App\Repositories\Admin;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface FuelSummaryInterface
{
    public function getFuelStocks(): Collection;

    public function getPaginatedArrivals(int $perPage, array $filters = []): LengthAwarePaginator;

    public function getPaginatedDepartures(int $perPage, array $filters = []): LengthAwarePaginator;

    public function getArrivalExportRows(array $filters = []): Collection;

    public function getDepartureExportRows(array $filters = []): Collection;
}