<?php

namespace App\Services\Admin;

use App\Repositories\Admin\TransactionHistoryInterface;
use App\Requests\Admin\TransactionHistoryRequest;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TransactionHistoryService
{
    public function __construct(
        protected TransactionHistoryInterface $transactionRepository
    ) {}

    public function getTransactions(TransactionHistoryRequest $request): array
    {
        $transactions = $this->transactionRepository->getPaginatedTransactions(
            perPage:  15,
            type:     $request->type(),
            search:   $request->search(),
            dateFrom: $request->dateFrom(),
            dateTo:   $request->dateTo(),
        );

        return [
            'transactions' => $transactions,
            'type'         => $request->type(),
            'search'       => $request->search(),
            'dateFrom'     => $request->dateFrom(),
            'dateTo'       => $request->dateTo(),
        ];
    }

    public function export(TransactionHistoryRequest $request): StreamedResponse
    {
        $rows     = $this->transactionRepository->getExportRows(
            type:     $request->type(),
            search:   $request->search(),
            dateFrom: $request->dateFrom(),
            dateTo:   $request->dateTo(),
        );

        $filename = 'transactions_' . now()->format('Ymd_His') . '.csv';
        $headers  = ['ID', 'Type', 'Tanker No.', 'Driver', 'Date', 'Recorded By', 'Fuel Type', 'Liters', 'Methanol %', 'Methanol L'];

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