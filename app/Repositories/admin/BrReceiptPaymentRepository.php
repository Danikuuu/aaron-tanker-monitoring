<?php

namespace App\Repositories\Admin;

use App\Models\BrReceipt;
use App\Models\BrReceiptPayment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class BrReceiptPaymentRepository implements BrReceiptPaymentInterface
{
    public function getAllReceipts(array $filters = []): LengthAwarePaginator
    {
        $query = BrReceipt::with(['departure', 'fuels', 'payment'])
            ->latest();

        // Search by client name (via payment) or receipt no.
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('receipt_no', 'LIKE', "%{$search}%")
                  ->orWhere('delivered_to', 'LIKE', "%{$search}%")
                  ->orWhereHas('payment', fn($p) => $p->where('client_name', 'LIKE', "%{$search}%"));
            });
        }

        // Filter by status via payment relationship
        if (!empty($filters['status'])) {
            if ($filters['status'] === 'unpaid') {
                $query->whereDoesntHave('payment')
                      ->orWhereHas('payment', fn($p) => $p->where('status', 'unpaid'));
            } else {
                $query->whereHas('payment', fn($p) => $p->where('status', $filters['status']));
            }
        }

        // Filter by date range (receipt created_at)
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->paginate(10)->withQueryString();
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

    // ── Payment helpers ────────────────────────────────────────────────────

    public function findPayment(int $receiptId): ?BrReceiptPayment
    {
        return BrReceiptPayment::where('br_receipt_id', $receiptId)->first();
    }

    public function getPaymentsByStatus(string $status): Collection
    {
        return BrReceiptPayment::with(['receipt.departure', 'receipt.fuels'])
            ->where('status', $status)
            ->latest()
            ->get();
    }

    public function getOverduePayments(): Collection
    {
        return BrReceiptPayment::with(['receipt.departure', 'receipt.fuels'])
            ->where('status', '!=', 'paid')
            ->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->latest('due_date')
            ->get();
    }

    public function deletePayment(int $receiptId): bool
    {
        return BrReceiptPayment::where('br_receipt_id', $receiptId)->delete() > 0;
    }
}