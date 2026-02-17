<?php

namespace App\Services\Admin;

use App\Repositories\Admin\AnalyticsInterface;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AnalyticsService
{
    public function __construct(
        protected AnalyticsInterface $analyticsRepository
    ) {}

    public function getAnalyticsData(string $period): array
    {
        return [
            'arrivalData'     => $this->analyticsRepository->getArrivalChart($period),
            'departureData'   => $this->analyticsRepository->getDepartureChart($period),
            'arrivalTotals'   => $this->analyticsRepository->getArrivalTotals($period),
            'departureTotals' => $this->analyticsRepository->getDepartureTotals($period),
            'period'          => $period,
        ];
    }

    public function exportCsv(string $type, string $period): StreamedResponse
    {
        if ($type === 'arrival') {
            $rows     = $this->analyticsRepository->getArrivalExportRows($period);
            $headers  = ['ID', 'Tanker No.', 'Arrival Date', 'Recorded By', 'Fuel Type', 'Liters'];
            $filename = 'fuel_arrivals_' . now()->format('Ymd') . '.csv';
        } else {
            $rows     = $this->analyticsRepository->getDepartureExportRows($period);
            $headers  = ['ID', 'Tanker No.', 'Driver', 'Departure Date', 'Recorded By', 'Fuel Type', 'Liters', 'Methanol %', 'Methanol L', 'Pure L'];
            $filename = 'fuel_departures_' . now()->format('Ymd') . '.csv';
        }

        return $this->streamCsv($filename, $headers, $rows);
    }

    private function streamCsv(string $filename, array $headers, Collection $rows): StreamedResponse
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