<?php

namespace App\Repositories\Admin;

use App\Models\BrReceipt;
use App\Models\BrReceiptPayment;
use Illuminate\Support\Collection;

class BrReceiptPaymentRepository implements BrReceiptPaymentInterface
{
    public function getAllReceipts(): Collection
    {
        return BrReceipt::with(['departure', 'fuels', 'payment'])
            ->latest()
            ->get();
    }

    public function findReceipt(int $id): BrReceipt
    {
        return BrReceipt::with(['departure.fuels', 'fuels', 'payment'])
            ->findOrFail($id);
    }

    public function saveReceipt(array $data): BrReceipt
    {
        return BrReceipt::create($data);
    }

    public function saveReceiptFuels(BrReceipt $receipt, array $fuels): void
    {
        foreach ($fuels as $fuel) {
            $receipt->fuels()->create($fuel);
        }
    }

    public function upsertPayment(BrReceipt $receipt, array $data): BrReceiptPayment
    {
        return BrReceiptPayment::updateOrCreate(
            ['br_receipt_id' => $receipt->id],
            array_merge($data, ['br_receipt_id' => $receipt->id])
        );
    }
}