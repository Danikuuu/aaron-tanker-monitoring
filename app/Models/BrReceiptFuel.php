<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrReceiptFuel extends Model
{
    protected $fillable = [
        'br_receipt_id',
        'fuel_type',
        'liters',
        'unit_price',
        'amount',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'liters'     => 'decimal:2',
            'unit_price' => 'decimal:2',
            'amount'     => 'decimal:2',
        ];
    }

    public function receipt()
    {
        return $this->belongsTo(BrReceipt::class, 'br_receipt_id');
    }
}