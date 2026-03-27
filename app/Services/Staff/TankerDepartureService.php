<?php

namespace App\Services\Staff;

use App\Mail\TankerActivityRecorded;
use App\Models\AuditLog;
use App\Models\FuelStock;
use App\Models\TankerDeparture;
use App\Models\User;
use App\Repositories\Staff\TankerDepartureRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class TankerDepartureService
{
    public function __construct(
        protected TankerDepartureRepositoryInterface $repository
    ) {}

    public function store(array $validated): TankerDeparture
    {
        return DB::transaction(function () use ($validated) {

            $departure = $this->repository->create([
                'recorded_by'   => Auth::id(),
                'tanker_number' => $validated['tanker_number'],
                'driver'         => $validated['driver'],
                'departure_date'=> $validated['departure_date'],
            ]);

            $fuelSummary = [];
            $totalLiters = 0;

            foreach ($validated['fuel_type'] as $key => $fuelType) {

                $liters         = $validated['liters'][$key] ?? null;
                $methanolLiters = $validated['methanol_liters'][$key] ?? 0;

                if (!$fuelType || !$liters) {
                    continue;
                }

                // Diesel departures should always be pure diesel.
                if ($fuelType === 'diesel') {
                    $methanolLiters = 0;
                }

                $pureLiters = $liters - $methanolLiters;

                $currentStock = FuelStock::getStock($fuelType);

                if ($currentStock < $pureLiters) {
                    throw new \RuntimeException(
                        "Insufficient stock for {$fuelType}. Available: {$currentStock}L"
                    );
                }

                if ($methanolLiters > 0) {

                    $methanolStock = FuelStock::getStock('methanol');

                    if ($methanolStock < $methanolLiters) {
                        throw new \RuntimeException(
                            "Insufficient stock for methanol. Available: {$methanolStock}L"
                        );
                    }
                }
                // Save fuel row with only pure fuel
                $this->repository->addFuel($departure, [
                    'fuel_type' => $fuelType,
                    'liters'    => $pureLiters,
                    'methanol_liters' => $methanolLiters, // optional, for tracking
                    'pure_liters' => $pureLiters,         // optional, for tracking
                    'methanol_percent' => $methanolLiters > 0 ? round($methanolLiters / $liters * 100, 2) : 0
                ]);

                // Subtract stock
                FuelStock::subtract($fuelType, $pureLiters);

                if ($methanolLiters > 0) {
                    FuelStock::subtract('methanol', $methanolLiters);
                }

                $fuelSummary[] = [
                    'fuel_type'       => $fuelType,
                    'total_liters'    => $liters,
                    'pure_liters'     => $pureLiters,
                    'methanol_liters' => $methanolLiters,
                ];

                $totalLiters += $pureLiters;

                // Audit log for this fuel
                AuditLog::record(
                    action: 'fuel_stock_decrease',
                    description: "Stock decreased for {$fuelType} by {$pureLiters} liters.",
                    model: $departure,
                    meta: [
                        'fuel_type' => $fuelType,
                        'liters_removed' => $pureLiters,
                        'methanol_component' => $methanolLiters
                    ]
                );
            }
            
            AuditLog::record(
                action: 'tanker_departure_created',
                description: "Tanker {$departure->tanker_number} departure recorded.",
                model: $departure,
                meta: [
                    'tanker_number' => $departure->tanker_number,
                    'total_liters'  => $totalLiters,
                    'fuel_breakdown'=> $fuelSummary
                ]
            );

            DB::afterCommit(function () use ($departure, $fuelSummary) {
                $adminEmails = User::query()
                    ->whereIn('role', ['admin', 'super_admin'])
                    ->whereNotNull('email')
                    ->pluck('email')
                    ->unique()
                    ->values()
                    ->all();

                if (empty($adminEmails)) {
                    return;
                }

                $recordedBy = trim((Auth::user()?->first_name ?? '') . ' ' . (Auth::user()?->last_name ?? ''));
                $recordedBy = $recordedBy !== '' ? $recordedBy : (Auth::user()?->email ?? 'Staff');

                $emailFuelBreakdown = collect($fuelSummary)->map(function (array $fuel) {
                    return [
                        'fuel_type' => $fuel['fuel_type'] ?? '',
                        'liters' => $fuel['total_liters'] ?? 0,
                    ];
                })->values()->all();

                try {
                    Mail::to($adminEmails)->send(new TankerActivityRecorded(
                        activityType: 'departure',
                        tankerNumber: (string) $departure->tanker_number,
                        recordedDate: optional($departure->departure_date)->format('M d, Y') ?? (string) $departure->departure_date,
                        recordedBy: $recordedBy,
                        fuelBreakdown: $emailFuelBreakdown
                    ));
                } catch (\Throwable $e) {
                    Log::warning('Failed to send tanker departure admin notification email.', [
                        'tanker_departure_id' => $departure->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            });

            return $departure;
        });
    }
}
