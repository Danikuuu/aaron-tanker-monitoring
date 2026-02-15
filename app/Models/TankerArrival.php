<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TankerArrival extends Model
{
    protected $fillable = ['recorded_by', 'tanker_number', 'arrival_date'];

    protected function casts(): array
    {
        return ['arrival_date' => 'date'];
    }

    public function fuels()
    {
        return $this->hasMany(TankerArrivalFuel::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}