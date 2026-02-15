<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TankerArrivalFuel extends Model
{
    protected $fillable = ['tanker_arrival_id', 'fuel_type', 'liters'];

    // Specify the correct foreign key
    public function arrival()
    {
        return $this->belongsTo(TankerArrival::class, 'tanker_arrival_id');
    }
}
