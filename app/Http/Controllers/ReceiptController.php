<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Admin\ReceiptService;

class ReceiptController extends Controller
{
    public function __construct(
        protected ReceiptService $receiptService
    ) {}

    public function show(int $id)
    {
        $data = $this->receiptService->getReceiptData($id);

        return view('admin.receipt', $data);
    }

    public function download(int $id)
    {
        return $this->receiptService->downloadPdf($id);
    }
}