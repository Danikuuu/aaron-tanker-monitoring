<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Admin\BrReceiptService;
use App\Requests\Admin\StoreBrReceiptRequest;
use Illuminate\Http\JsonResponse;

class BrReceiptController extends Controller
{
    public function __construct(
        protected BrReceiptService $service
    ) {}

    /**
     * List all departures that can have BR Receipts created for them.
     */
    public function index()
    {
        $departures = $this->service->getDepartures();

        return view('admin.receipt.br-receipt', compact('departures'));
    }

    /**
     * Create a new BR Receipt for a departure. Validates input and returns JSON response with success status and receipt number or error message.
     */
    public function store(StoreBrReceiptRequest $request): JsonResponse
    {
        try {
            $receipt = $this->service->createReceipt($request->validated());

            return response()->json([
                'success' => true,
                'receipt_no' => $receipt->receipt_no,
                'message' => 'Receipt saved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save receipt: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate the next available receipt number based on the current date and existing receipts. Returns JSON response with the generated receipt number.
     */
    public function getNextReceiptNumber(): JsonResponse
    {
        $nextNumber = $this->service->generateReceiptNumber();
        
        return response()->json([
            'receipt_no' => $nextNumber
        ]);
    }
}