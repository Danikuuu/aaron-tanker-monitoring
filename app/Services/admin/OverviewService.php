<?php

namespace App\Services\Admin; 

use App\Repositories\Admin\OverviewInterface;

class OverviewService
{
    public function __construct(
        protected OverviewInterface $overviewRepository
    ) {}

    public function getDashboardData(): array
    {
        return [
            'stocks'          => $this->overviewRepository->getFuelStocks(),
            'arrivals'        => $this->overviewRepository->getRecentArrivals(10),
            'departures'      => $this->overviewRepository->getRecentDepartures(10),
            'chartData'       => $this->overviewRepository->getMonthlyArrivalChart(6),
            'deliverySummary' => $this->overviewRepository->getDeliverySummary(),
            'auditLogs'       => $this->overviewRepository->getAuditLogs(20),
        ];
    }
}