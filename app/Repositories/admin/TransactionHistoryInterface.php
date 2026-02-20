<?php

namespace App\Repositories\Admin;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TransactionHistoryInterface
{
    public function getPaginatedTransactions(
        int    $perPage,
        string $type,
        string $search,
        string $dateFrom,
        string $dateTo
    ): LengthAwarePaginator;

    public function getExportRows(
        string $type,
        string $search,
        string $dateFrom,
        string $dateTo
    ): \Illuminate\Support\Collection;

    public function getTransaction(string $type, int $id);
}