<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TankerDepartureFuel extends Model
{
    protected $fillable = [
        'tanker_departure_id', 'fuel_type',
        'liters', 'methanol_percent', 'methanol_liters', 'pure_liters',
    ];

    public function departure()
    {
        return $this->belongsTo(TankerDeparture::class);
    }
}