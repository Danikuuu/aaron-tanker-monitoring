<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrReceipt extends Model
{
    protected $fillable = [
        'tanker_departure_id',
        'recorded_by',
        'receipt_no',
        'delivered_to',
        'address',
        'tin',
        'terms',
        'grand_total',
    ];

    protected function casts(): array
    {
        return [
            'grand_total' => 'decimal:2',
        ];
    }

    public function departure()
    {
        return $this->belongsTo(TankerDeparture::class, 'tanker_departure_id');
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function fuels()
    {
        return $this->hasMany(BrReceiptFuel::class);
    }

    public function payment()
    {
        return $this->hasOne(BrReceiptPayment::class);
    }
}