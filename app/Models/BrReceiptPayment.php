<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrReceiptPayment extends Model
{
    protected $fillable = [
        'br_receipt_id',
        'recorded_by',
        'client_name',
        'total_amount',
        'down_payment',
        'down_payment_date',
        'final_payment',
        'final_payment_date',
        'due_date',
        'notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'total_amount'       => 'decimal:2',
            'down_payment'       => 'decimal:2',
            'final_payment'      => 'decimal:2',
            'down_payment_date'  => 'date',
            'final_payment_date' => 'date',
            'due_date'           => 'date',
        ];
    }

    // ── Relationships ──────────────────────────────────────────────────────
    public function receipt()
    {
        return $this->belongsTo(BrReceipt::class, 'br_receipt_id');
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    // ── Computed helpers ───────────────────────────────────────────────────
    public function getAmountPaidAttribute(): float
    {
        return (float) $this->down_payment + (float) $this->final_payment;
    }

    public function getRemainingBalanceAttribute(): float
    {
        return max(0, (float) $this->total_amount - $this->amount_paid);
    }

    public function getDownPaymentPercentAttribute(): float
    {
        if (!$this->total_amount) return 0;
        return round(($this->down_payment / $this->total_amount) * 100, 1);
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->status !== 'paid'
            && $this->due_date
            && $this->due_date->isPast();
    }

    // ── Auto-compute status before saving ─────────────────────────────────
    protected static function booted(): void
    {
        static::saving(function (self $payment) {
            $paid  = (float) $payment->down_payment + (float) $payment->final_payment;
            $total = (float) $payment->total_amount;

            if ($paid <= 0) {
                $payment->status = 'unpaid';
            } elseif ($paid >= $total) {
                $payment->status = 'paid';
            } else {
                $payment->status = 'partial';
            }
        });
    }
}