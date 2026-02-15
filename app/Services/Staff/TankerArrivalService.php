<?php

namespace App\Services\Staff;

use App\Models\AuditLog;
use App\Models\FuelStock;
use App\Models\TankerArrival;
use App\Repositories\Staff\TankerArrivalRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TankerArrivalService
{
    public function __construct(
        protected TankerArrivalRepositoryInterface $repository
    ) {}

    public function store(array $validated): TankerArrival
    {
        return DB::transaction(function () use ($validated) {

            $arrival = $this->repository->create([
                'recorded_by'   => Auth::id(),
                'tanker_number' => $validated['tanker_number'],
                'driver' => $validated['driver'],
                'arrival_date'  => $validated['departure_date'],
            ]);


            $fuelSummary   = [];
            $totalLiters   = 0;
            $totalMethanol = 0;

            foreach ($validated['fuel_type'] as $key => $fuelType) {

                $liters         = $validated['liters'][$key] ?? null;
                $methanolLiters = $validated['methanol_liters'][$key] ?? 0;

                if (!$fuelType || !$liters) {
                    continue;
                }

                $pureLiters = $liters - $methanolLiters;

                // Save tanker fuel row with only pure fuel
                $this->repository->addFuel($arrival, [
                    'fuel_type' => $fuelType,
                    'liters'    => $pureLiters,
                ]);

                // Update stock
                FuelStock::add($fuelType, $pureLiters);
                if ($methanolLiters > 0) {
                    FuelStock::add('methanol', $methanolLiters);
                }

                // Build audit summary
                $fuelSummary[] = [
                    'fuel_type'        => $fuelType,
                    'total_liters'     => $liters,
                    'pure_liters'      => $pureLiters,
                    'methanol_liters'  => $methanolLiters,
                ];

                $totalLiters   += $liters;
                $totalMethanol += $methanolLiters;

                // 🔹 Log stock update per fuel
                AuditLog::record(
                    action: 'fuel_stock_increase',
                    description: "Stock increased for {$fuelType} by {$liters} liters.",
                    model: $arrival,
                    meta: [
                        'fuel_type' => $fuelType,
                        'liters_added' => $liters,
                        'methanol_component' => $methanolLiters
                    ]
                );
            }

            // 🔹 Main arrival log
            AuditLog::record(
                action: 'tanker_arrival_created',
                description: "Tanker {$arrival->tanker_number} arrival recorded.",
                model: $arrival,
                meta: [
                    'tanker_number'  => $arrival->tanker_number,
                    'arrival_date'   => $arrival->arrival_date,
                    'total_liters'   => $totalLiters,
                    'total_methanol' => $totalMethanol,
                    'fuel_breakdown' => $fuelSummary
                ]
            );

            return $arrival;
        });
    }
}
