<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TankerDeparture extends Model
{
    protected $fillable = ['recorded_by', 'tanker_number', 'driver', 'departure_date'];

    protected function casts(): array
    {
        return ['departure_date' => 'date'];
    }

    public function fuels()
    {
        return $this->hasMany(TankerDepartureFuel::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}