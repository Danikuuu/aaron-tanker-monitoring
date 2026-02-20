<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Requests\Admin\TransactionHistoryRequest;
use App\Services\Admin\TransactionHistoryService;

class TransactionHistoryController extends Controller
{
    public function __construct(
        protected TransactionHistoryService $transactionHistoryService
    ) {}

    /**
     * Display the transaction history page with a list of past transactions (arrivals and departures) based on the applied filters in the request.
     */
    public function index(TransactionHistoryRequest $request)
    {
        $data = $this->transactionHistoryService->getTransactions($request);

        return view('admin.transaction-history', $data);
    }

    /**
     * Export transaction history data to CSV based on the applied filters in the request.
     */
    public function export(TransactionHistoryRequest $request)
    {
        return $this->transactionHistoryService->export($request);
    }
}