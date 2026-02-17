<?php

namespace App\Repositories\Admin;

use Illuminate\Support\Collection;

interface AnalyticsInterface
{
    public function getArrivalChart(string $period): Collection;

    public function getDepartureChart(string $period): Collection;

    public function getArrivalTotals(string $period): Collection;

    public function getDepartureTotals(string $period): Collection;

    public function getArrivalExportRows(string $period): Collection;

    public function getDepartureExportRows(string $period): Collection;
}