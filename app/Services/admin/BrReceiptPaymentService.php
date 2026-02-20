<?php

namespace App\Services\Admin;

use App\Models\AuditLog;
use App\Models\BrReceipt;
use App\Models\BrReceiptPayment;
use App\Repositories\Admin\BrReceiptPaymentInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BrReceiptPaymentService
{
    public function __construct(
        protected BrReceiptPaymentInterface $repository
    ) {}

    public function getAllReceipts(): Collection
    {
        return $this->repository->getAllReceipts();
    }

    public function findReceipt(int $id): BrReceipt
    {
        return $this->repository->findReceipt($id);
    }

    /**
     * Store Receipt + Fuels
     */
    public function storeReceipt(int $departureId, array $validated): BrReceipt
    {
        return DB::transaction(function () use ($departureId, $validated) {

            $receipt = $this->repository->saveReceipt([
                'tanker_departure_id' => $departureId,
                'recorded_by'         => Auth::id(),
                'receipt_no'          => $validated['receipt_no'],
                'delivered_to'        => $validated['delivered_to']  ?? null,
                'address'             => $validated['address']       ?? null,
                'tin'                 => $validated['tin']           ?? null,
                'terms'               => $validated['terms']         ?? null,
                'grand_total'         => $validated['grand_total'],
            ]);

            $this->repository->saveReceiptFuels($receipt, $validated['fuels']);

            // Build fuel summary for audit
            $fuelSummary = collect($validated['fuels'])->map(function ($fuel) {
                return [
                    'fuel_type'  => $fuel['fuel_type'],
                    'liters'     => $fuel['liters'],
                    'unit_price' => $fuel['unit_price'],
                    'amount'     => $fuel['amount'],
                ];
            });

            // Main Receipt Log
            AuditLog::record(
                action: 'br_receipt_created',
                description: "BR Receipt {$receipt->receipt_no} created.",
                model: $receipt,
                meta: [
                    'receipt_no'   => $receipt->receipt_no,
                    'grand_total'  => $receipt->grand_total,
                    'fuel_lines'   => $fuelSummary,
                ]
            );

            return $receipt;
        });
    }

    /**
     * Create or update payment
     */
    public function upsertPayment(BrReceipt $receipt, array $validated): BrReceiptPayment
    {
        return DB::transaction(function () use ($receipt, $validated) {

            $payment = $this->repository->upsertPayment(
                $receipt,
                array_merge($validated, [
                    'recorded_by'  => Auth::id(),
                    'total_amount' => $receipt->grand_total,
                ])
            );

            // Payment Log
            AuditLog::record(
                action: 'br_payment_updated',
                description: "Payment updated for Receipt {$receipt->receipt_no}.",
                model: $payment,
                meta: [
                    'receipt_no'        => $receipt->receipt_no,
                    'total_amount'      => $payment->total_amount,
                    'down_payment'      => $payment->down_payment,
                    'final_payment'     => $payment->final_payment,
                    'amount_paid'       => $payment->amount_paid,
                    'remaining_balance' => $payment->remaining_balance,
                    'status'            => $payment->status,
                    'due_date'          => $payment->due_date,
                ]
            );

            return $payment;
        });
    }
}
