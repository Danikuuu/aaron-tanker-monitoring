<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FuelStock extends Model
{
    protected $fillable = ['fuel_type', 'liters'];

    public static function getStock(string $fuelType): float
    {
        return static::where('fuel_type', $fuelType)->value('liters') ?? 0;
    }

    public static function add(string $fuelType, float $liters): void
    {
        static::where('fuel_type', $fuelType)->increment('liters', $liters);
    }

    public static function subtract(string $fuelType, float $liters): void
    {
        static::where('fuel_type', $fuelType)->decrement('liters', $liters);
    }
}