<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BrReceipt;
use App\Services\Admin\BrReceiptService;
use App\Requests\Admin\StoreBrReceiptRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

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

        $receipts = BrReceipt::with(['fuels', 'departure'])
            ->latest()
            ->paginate(15);

        $user = Auth::user();

        if ($user->role === 'admin') {
            return view('admin.receipt.br-receipt', compact('departures', 'receipts'));
        }

        return view('super_admin.receipt.br-receipt', compact('departures', 'receipts'));
    }

    /**
     * Create a new BR Receipt for a departure.
     */
    public function store(StoreBrReceiptRequest $request): JsonResponse
    {
        try {
            $receipt = $this->service->createReceipt($request->validated());

            return response()->json([
                'success'    => true,
                'receipt_no' => $receipt->receipt_no,
                'message'    => 'Receipt saved successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save receipt: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate the next available receipt number.
     */
    public function getNextReceiptNumber(): JsonResponse
    {
        $nextNumber = $this->service->generateReceiptNumber();

        return response()->json([
            'receipt_no' => $nextNumber,
        ]);
    }

    /**
     * Return JSON data for a single receipt (used by re-download).
     */
    public function show(int $id): JsonResponse
    {
        $receipt = BrReceipt::with(['fuels', 'departure'])->findOrFail($id);

        return response()->json([
            'receipt' => [
                'id'             => $receipt->id,
                'receipt_no'     => $receipt->receipt_no,
                'delivered_to'   => $receipt->delivered_to,
                'address'        => $receipt->address,
                'tin'            => $receipt->tin,
                'terms'          => $receipt->terms,
                'grand_total'    => $receipt->grand_total,
                'tanker_number'  => $receipt->departure->tanker_number ?? '—',
                'driver'         => $receipt->departure->driver        ?? '—',
                'departure_date' => optional($receipt->departure->departure_date)->format('m/d/Y'),
                'fuels'          => $receipt->fuels->map(fn($f) => [
                    'fuel_type'  => $f->fuel_type,
                    'liters'     => (float) $f->liters,
                    'unit_price' => (float) $f->unit_price,
                    'amount'     => (float) $f->amount,
                    'remarks'    => $f->remarks,
                ]),
            ],
        ]);
    }
}