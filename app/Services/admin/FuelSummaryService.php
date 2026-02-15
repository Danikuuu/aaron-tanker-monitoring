<?php

namespace App\Services\Admin;

use App\Repositories\Admin\FuelSummaryInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FuelSummaryService
{
    public function __construct(
        protected FuelSummaryInterface $fuelSummaryRepository
    ) {}

    public function getSummaryData(): array
    {
        $stocks = $this->fuelSummaryRepository->getFuelStocks();

        return [
            'stocks'     => $stocks,
            'arrivals'   => $this->fuelSummaryRepository->getPaginatedArrivals(10),
            'departures' => $this->fuelSummaryRepository->getPaginatedDepartures(10),
        ];
    }

    public function exportArrivals(): StreamedResponse
    {
        $rows = $this->fuelSummaryRepository->getArrivalExportRows();

        return $this->streamCsv(
            'fuel_arrivals_' . now()->format('Ymd') . '.csv',
            ['ID', 'Tanker No.', 'Arrival Date', 'Recorded By', 'Fuel Type', 'Liters'],
            $rows
        );
    }

    public function exportDepartures(): StreamedResponse
    {
        $rows = $this->fuelSummaryRepository->getDepartureExportRows();

        return $this->streamCsv(
            'fuel_departures_' . now()->format('Ymd') . '.csv',
            ['ID', 'Tanker No.', 'Driver', 'Departure Date', 'Recorded By', 'Fuel Type', 'Liters', 'Methanol %', 'Methanol L', 'Pure L'],
            $rows
        );
    }

    private function streamCsv(string $filename, array $headers, $rows): StreamedResponse
    {
        return response()->stream(
            function () use ($headers, $rows) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, $headers);
                foreach ($rows as $row) {
                    fputcsv($handle, (array) $row);
                }
                fclose($handle);
            },
            200,
            [
                'Content-Type'        => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ]
        );
    }
}