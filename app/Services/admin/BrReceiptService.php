<?php

namespace App\Services\Admin;

use App\Repositories\Admin\BrReceiptInterface;
use App\Models\BrReceipt;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BrReceiptService
{
    public function __construct(
        protected BrReceiptInterface $repository
    ) {}

    /**
     * Return all departures shaped for the BR Receipt dropdown.
     */
    public function getDepartures(): Collection
    {
        return $this->repository->getAllDepartures();
    }

    /**
     * Generate the next receipt number (e.g., BR-2501, BR-2502, etc.)
     */
    public function generateReceiptNumber(): string
    {
        $year = date('y'); // 2-digit year (e.g., "25" for 2025)
        $prefix = "BR-{$year}";

        // Get the latest receipt number for this year
        $latest = BrReceipt::where('receipt_no', 'LIKE', "{$prefix}%")
            ->orderByDesc('receipt_no')
            ->first();

        if (!$latest) {
            return "{$prefix}01"; // First receipt of the year
        }

        // Extract the numeric part and increment
        $lastNumber = (int) substr($latest->receipt_no, -2);
        $nextNumber = str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);

        return "{$prefix}{$nextNumber}";
    }

    /**
     * Create a new BR receipt with fuels.
     */
    public function createReceipt(array $data): BrReceipt
    {
        return DB::transaction(function () use ($data) {
            // Auto-generate receipt number if not provided
            if (empty($data['receipt_no'])) {
                $data['receipt_no'] = $this->generateReceiptNumber();
            }

            // Add the authenticated user as recorded_by
            $data['recorded_by'] = Auth::id();

            // Create the receipt
            $receipt = BrReceipt::create([
                'tanker_departure_id' => $data['tanker_departure_id'],
                'recorded_by' => $data['recorded_by'],
                'receipt_no' => $data['receipt_no'],
                'delivered_to' => $data['delivered_to'] ?? null,
                'address' => $data['address'] ?? null,
                'tin' => $data['tin'] ?? null,
                'terms' => $data['terms'] ?? null,
                'grand_total' => $data['grand_total'],
            ]);

            // Create fuel entries
            foreach ($data['fuels'] as $fuel) {
                $receipt->fuels()->create([
                    'fuel_type' => $fuel['fuel_type'],
                    'liters' => $fuel['liters'],
                    'unit_price' => $fuel['unit_price'],
                    'amount' => $fuel['amount'],
                    'remarks' => $fuel['remarks'] ?? null,
                ]);
            }

            return $receipt;
        });
    }
}