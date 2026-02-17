<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Requests\Admin\StoreBrReceiptPaymentRequest;
use App\Services\Admin\BrReceiptPaymentService;
use Illuminate\Http\Request;

class BrReceiptPaymentController extends Controller
{
    public function __construct(
        protected BrReceiptPaymentService $service
    ) {}

    /**
     * List all BR Receipts with their payment status.
     */
    public function index()
    {
        $receipts = $this->service->getAllReceipts();
        return view('admin.receipt.br-receipt-payment-index', compact('receipts'));
    }

    /**
     * Show payment details + form for a single receipt.
     */
    public function show(int $id)
    {
        $receipt = $this->service->findReceipt($id);
        return view('admin.receipt.br-receipt-payment-show', compact('receipt'));
    }

    /**
     * Create or update the payment for a receipt.
     */
    public function upsertPayment(StoreBrReceiptPaymentRequest $request, int $id)
    {
        $receipt = $this->service->findReceipt($id);
        $this->service->upsertPayment($receipt, $request->validated());

        return redirect()
            ->route('admin.br-receipt-payments.show', $id)
            ->with('success', 'Payment record saved successfully.');
    }
}