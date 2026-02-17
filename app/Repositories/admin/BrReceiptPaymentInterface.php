<?php

namespace App\Repositories\Admin;

use App\Models\BrReceipt;
use App\Models\BrReceiptPayment;
use Illuminate\Support\Collection;

interface BrReceiptPaymentInterface
{
    public function getAllReceipts(): Collection;
    public function findReceipt(int $id): BrReceipt;
    public function saveReceipt(array $data): BrReceipt;
    public function saveReceiptFuels(BrReceipt $receipt, array $fuels): void;
    public function upsertPayment(BrReceipt $receipt, array $data): BrReceiptPayment;
}