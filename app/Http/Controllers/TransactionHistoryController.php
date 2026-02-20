<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Requests\Admin\TransactionHistoryRequest;
use App\Services\Admin\TransactionHistoryService;
use Illuminate\Support\Facades\Auth;

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

        $user = Auth::user();

        if ($user->role === 'admin') {
            return view('admin.transaction-history', $data);
        }

        return view('super_admin.transaction-history', $data);
    }

    /**
     * Return JSON details for a single transaction (arrival or departure).
     */
    public function show(string $type, int $id)
    {
        $transaction = $this->transactionHistoryService->getTransaction($type, $id);

        if (! $transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        return response()->json($transaction);
    }

    /**
     * Export transactions to PDF based on current filters.
     */
    public function exportPdf(TransactionHistoryRequest $request)
    {
        return $this->transactionHistoryService->exportPdf($request);
    }

    /**
     * Export transaction history data to CSV based on the applied filters in the request.
     */
    public function export(TransactionHistoryRequest $request)
    {
        return $this->transactionHistoryService->export($request);
    }
}