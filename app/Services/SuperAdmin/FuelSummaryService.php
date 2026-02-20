<?php

namespace App\Services\Admin;

use App\Repositories\Admin\FuelSummaryInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FuelSummaryService
{
    public function __construct(
        protected FuelSummaryInterface $fuelSummaryRepository
    ) {}

    public function getSummaryData(array $filters = []): array
    {
        $stocks = $this->fuelSummaryRepository->getFuelStocks();

        return [
            'stocks'     => $stocks,
            'arrivals'   => $this->fuelSummaryRepository->getPaginatedArrivals(10, $filters),
            'departures' => $this->fuelSummaryRepository->getPaginatedDepartures(10, $filters),
            'filters'    => $filters,
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

    public function exportArrivalsPdf(array $filters = [])
    {
        $rows = $this->fuelSummaryRepository->getArrivalExportRows($filters);

        $pdfView = view('admin.fuel-summary-pdf-arrivals', ['rows' => $rows, 'filters' => $filters])->render();

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($pdfView);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $filename = 'fuel_arrivals_' . now()->format('Ymd_His') . '.pdf';

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    public function exportDeparturesPdf(array $filters = [])
    {
        $rows = $this->fuelSummaryRepository->getDepartureExportRows($filters);

        $pdfView = view('admin.fuel-summary-pdf-departures', ['rows' => $rows, 'filters' => $filters])->render();

        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($pdfView);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $filename = 'fuel_departures_' . now()->format('Ymd_His') . '.pdf';

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
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