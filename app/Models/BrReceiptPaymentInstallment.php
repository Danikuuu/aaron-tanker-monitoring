<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrReceiptPaymentInstallment extends Model
{
    protected $fillable = [
        'br_receipt_payment_id',
        'recorded_by',
        'amount',
        'payment_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'payment_date' => 'date',
        ];
    }

    public function payment()
    {
        return $this->belongsTo(BrReceiptPayment::class, 'br_receipt_payment_id');
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
