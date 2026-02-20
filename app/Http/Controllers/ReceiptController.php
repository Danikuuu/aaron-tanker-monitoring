<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Admin\ReceiptService;

class ReceiptController extends Controller
{
    public function __construct(
        protected ReceiptService $receiptService
    ) {}

    /**
     * Display the receipt details page for a specific receipt ID, showing all relevant information and allowing the user to download the receipt as a PDF.
      * Retrieves the receipt data from the service layer and passes it to the view.
      * The view will handle displaying the receipt information in a user-friendly format.
     */
    public function show(int $id)
    {
        $data = $this->receiptService->getReceiptData($id);

        return view('admin.receipt', $data);
    }

    /**
     * Handle the request to download the receipt as a PDF. Validates the receipt ID and generates a PDF file for download.
      * Uses the service layer to create the PDF and returns it as a response with appropriate headers for downloading.
      * If the receipt ID is invalid or the PDF generation fails, returns an error response.
      * This method is typically triggered by a "Download PDF" button on the receipt details page.
     */
    public function download(int $id)
    {
        return $this->receiptService->downloadPdf($id);
    }
}