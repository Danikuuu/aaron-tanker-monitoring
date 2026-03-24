<?php

namespace App\Repositories\Admin;

use App\Models\TankerArrival;
use App\Models\TankerDeparture;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface FuelSummaryInterface
{
    public function getFuelStocks(): Collection;

    public function getPaginatedArrivals(int $perPage, array $filters = []): LengthAwarePaginator;

    public function getPaginatedDepartures(int $perPage, array $filters = []): LengthAwarePaginator;

    public function getArrivalExportRows(array $filters = []): Collection;

    public function getDepartureExportRows(array $filters = []): Collection;

    /**
     * Find a tanker arrival by ID.
     */
    public function findArrival(int $id): TankerArrival;

    /**
     * Update a tanker arrival and its fuel lines.
     *
     * @param  array{tanker_number: string, arrival_date: string, fuels: array} $data
     */
    public function updateArrival(TankerArrival $arrival, array $data): TankerArrival;

    /**
     * Find a tanker departure by ID.
     */
    public function findDeparture(int $id): TankerDeparture;

    /**
     * Update a tanker departure and its fuel lines.
     *
     * @param  array{tanker_number: string, driver: string, departure_date: string, fuels: array} $data
     */
    public function updateDeparture(TankerDeparture $departure, array $data): TankerDeparture;
}