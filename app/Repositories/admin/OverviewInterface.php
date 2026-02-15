<?php

namespace App\Repositories\Admin;

use Illuminate\Support\Collection;

interface OverviewInterface
{
    public function getFuelStocks(): Collection;

    public function getRecentArrivals(int $limit): Collection;

    public function getRecentDepartures(int $limit): Collection;

    public function getMonthlyArrivalChart(int $months): Collection;

    public function getDeliverySummary(): Collection;

    public function getAuditLogs(int $limit): Collection;
}