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

    public function index(TransactionHistoryRequest $request)
    {
        $data = $this->transactionHistoryService->getTransactions($request);

        return view('admin.transaction-history', $data);
    }

    public function export(TransactionHistoryRequest $request)
    {
        return $this->transactionHistoryService->export($request);
    }
}