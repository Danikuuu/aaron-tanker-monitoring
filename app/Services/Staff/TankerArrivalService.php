<?php

namespace App\Services\Staff;

use App\Mail\TankerActivityRecorded;
use App\Models\AuditLog;
use App\Models\FuelStock;
use App\Models\TankerArrival;
use App\Models\User;
use App\Repositories\Staff\TankerArrivalRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
                'driver'        => $validated['driver'] ?? null,
                'arrival_date'  => $validated['departure_date'],
            ]);

            $fuelSummary = [];
            $totalLiters = 0;

            foreach ($validated['fuel_type'] as $key => $fuelType) {

                $liters = $validated['liters'][$key] ?? null;

                if (!$fuelType || !$liters) {
                    continue;
                }

                // Save tanker fuel row
                $this->repository->addFuel($arrival, [
                    'fuel_type' => $fuelType,
                    'liters'    => $liters,
                ]);

                // Update stock
                FuelStock::add($fuelType, $liters);

                // Build audit summary
                $fuelSummary[] = [
                    'fuel_type' => $fuelType,
                    'liters'    => $liters,
                ];

                $totalLiters += $liters;

                // Log stock update per fuel
                AuditLog::record(
                    action: 'fuel_stock_increase',
                    description: "Stock increased for {$fuelType} by {$liters} liters.",
                    model: $arrival,
                    meta: [
                        'fuel_type'    => $fuelType,
                        'liters_added' => $liters,
                    ]
                );
            }

            // Main arrival log
            AuditLog::record(
                action: 'tanker_arrival_created',
                description: "Tanker {$arrival->tanker_number} arrival recorded.",
                model: $arrival,
                meta: [
                    'tanker_number'  => $arrival->tanker_number,
                    'arrival_date'   => $arrival->arrival_date,
                    'total_liters'   => $totalLiters,
                    'fuel_breakdown' => $fuelSummary,
                ]
            );

            DB::afterCommit(function () use ($arrival, $fuelSummary) {
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

                try {
                    Mail::to($adminEmails)->send(new TankerActivityRecorded(
                        activityType: 'arrival',
                        tankerNumber: (string) $arrival->tanker_number,
                        recordedDate: optional($arrival->arrival_date)->format('M d, Y') ?? (string) $arrival->arrival_date,
                        recordedBy: $recordedBy,
                        fuelBreakdown: $fuelSummary
                    ));
                } catch (\Throwable $e) {
                    Log::warning('Failed to send tanker arrival admin notification email.', [
                        'tanker_arrival_id' => $arrival->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            });

            return $arrival;
        });
    }
}