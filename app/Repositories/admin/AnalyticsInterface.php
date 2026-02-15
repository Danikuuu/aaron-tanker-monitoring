<?php

namespace App\Repositories\Admin;

use Illuminate\Support\Collection;

interface AnalyticsInterface
{
    public function getMonthlyArrivalChart(int $months): Collection;

    public function getMonthlyDepartureChart(int $months): Collection;

    public function getArrivalTotals(int $months): Collection;

    public function getDepartureTotals(int $months): Collection;

    public function getArrivalExportRows(int $months): Collection;

    public function getDepartureExportRows(int $months): Collection;
}