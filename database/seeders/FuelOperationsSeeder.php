<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\FuelStock;
use App\Models\TankerArrival;
use App\Models\TankerArrivalFuel;
use App\Models\TankerDeparture;
use App\Models\TankerDepartureFuel;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FuelOperationsSeeder extends Seeder
{
    private array $fuelTypes = ['diesel', 'premium', 'unleaded', 'methanol'];

    private array $tankerNumbers = [
        'TKR-001', 'TKR-002', 'TKR-003', 'TKR-004', 'TKR-005',
        'TKR-006', 'TKR-007', 'TKR-008', 'TKR-009', 'TKR-010',
    ];

    private array $drivers = [
        'Juan dela Cruz', 'Pedro Santos', 'Mario Reyes',
        'Jose Garcia', 'Ramon Flores', 'Eduardo Lopez',
        'Antonio Cruz', 'Roberto Diaz', 'Miguel Torres', 'Carlos Ramos',
    ];

    public function run(): void
    {
        // ── 1. Create admin ───────────────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@aaron.com'],
            [
                'first_name' => 'Admin',
                'last_name'  => 'Aaron',
                'password'   => Hash::make('password'),
                'role'       => 'admin',
                'status'     => 'approved',
            ]
        );

        // ── 2. Create staff members ───────────────────────────────────────
        $staffMembers = [];
        $staffData = [
            ['first_name' => 'Maria',   'last_name' => 'Santos'],
            ['first_name' => 'Jose',    'last_name' => 'Reyes'],
            ['first_name' => 'Ana',     'last_name' => 'Garcia'],
        ];

        foreach ($staffData as $data) {
            $staffMembers[] = User::firstOrCreate(
                ['email' => strtolower($data['first_name']) . '@aaron.com'],
                [
                    'first_name'  => $data['first_name'],
                    'last_name'   => $data['last_name'],
                    'password'    => Hash::make('password'),
                    'role'        => 'staff',
                    'status'      => 'approved',
                    'approved_by' => $admin->id,
                    'approved_at' => now()->subDays(rand(10, 30)),
                ]
            );
        }

        $allUsers = array_merge([$admin], $staffMembers);

        // ── 3. Reset fuel stocks ──────────────────────────────────────────
        FuelStock::whereIn('fuel_type', $this->fuelTypes)
            ->update(['liters' => 0]);

        // ── 4. Create 10 tanker arrivals ──────────────────────────────────
        $this->command->info('Seeding 10 tanker arrivals...');

        for ($i = 0; $i < 10; $i++) {
            $recorder    = $allUsers[array_rand($allUsers)];
            $arrivalDate = now()->subDays(rand(1, 180))->startOfDay();
            $fuelsToAdd  = $this->randomFuelSubset();

            $arrival = TankerArrival::create([
                'recorded_by'   => $recorder->id,
                'tanker_number' => $this->tankerNumbers[$i],
                'arrival_date'  => $arrivalDate,
                'created_at'    => $arrivalDate,
                'updated_at'    => $arrivalDate,
            ]);

            foreach ($fuelsToAdd as $fuelType) {
                $liters = rand(500, 5000);

                TankerArrivalFuel::create([
                    'tanker_arrival_id' => $arrival->id,
                    'fuel_type'         => $fuelType,
                    'liters'            => $liters,
                    'created_at'        => $arrivalDate,
                    'updated_at'        => $arrivalDate,
                ]);

                FuelStock::add($fuelType, $liters);
            }

            // Audit log
            $fuelSummary = implode(', ', array_map(
                fn($f) => ucfirst($f),
                $fuelsToAdd
            ));

            AuditLog::create([
                'user_id'     => $recorder->id,
                'action'      => 'tanker_arrival',
                'description' => "Tanker {$arrival->tanker_number} arrived with: {$fuelSummary}",
                'model_type'  => TankerArrival::class,
                'model_id'    => $arrival->id,
                'ip_address'  => '127.0.0.1',
                'created_at'  => $arrivalDate,
                'updated_at'  => $arrivalDate,
            ]);

            $this->command->line("  ✓ Arrival #{$arrival->id} — {$arrival->tanker_number}");
        }

        // ── 5. Create 10 tanker departures ────────────────────────────────
        $this->command->info('Seeding 10 tanker departures...');

        for ($i = 0; $i < 10; $i++) {
            $recorder      = $allUsers[array_rand($allUsers)];
            $departureDate = now()->subDays(rand(1, 90))->startOfDay();
            $fuelsToUse    = $this->randomFuelSubset(exclude: ['methanol']);

            $departure = TankerDeparture::create([
                'recorded_by'    => $recorder->id,
                'tanker_number'  => 'DEP-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'driver'         => $this->drivers[$i],
                'departure_date' => $departureDate,
                'created_at'     => $departureDate,
                'updated_at'     => $departureDate,
            ]);

            foreach ($fuelsToUse as $fuelType) {
                $currentStock = FuelStock::getStock($fuelType);

                // Don't deplete more than 60% of current stock
                $maxDispense = max(100, $currentStock * 0.6);
                $liters      = min(rand(100, 2000), $maxDispense);
                $liters      = round($liters, 2);

                // Methanol mixture (15–30%) for mixed fuels
                $methanolPct    = rand(15, 30);
                $methanolLiters = round($liters * $methanolPct / 100, 2);
                $pureLiters     = round($liters - $methanolLiters, 2);

                TankerDepartureFuel::create([
                    'tanker_departure_id' => $departure->id,
                    'fuel_type'           => $fuelType,
                    'liters'              => $liters,
                    'methanol_percent'    => $methanolPct,
                    'methanol_liters'     => $methanolLiters,
                    'pure_liters'         => $pureLiters,
                    'created_at'          => $departureDate,
                    'updated_at'          => $departureDate,
                ]);

                FuelStock::subtract($fuelType, $liters);

                // Track methanol used from methanol stock
                FuelStock::subtract('methanol', $methanolLiters);
            }

            $fuelSummary = implode(', ', array_map(
                fn($f) => ucfirst($f),
                $fuelsToUse
            ));

            AuditLog::create([
                'user_id'     => $recorder->id,
                'action'      => 'tanker_departure',
                'description' => "Tanker {$departure->tanker_number} departed with: {$fuelSummary}. Driver: {$departure->driver}",
                'model_type'  => TankerDeparture::class,
                'model_id'    => $departure->id,
                'ip_address'  => '127.0.0.1',
                'created_at'  => $departureDate,
                'updated_at'  => $departureDate,
            ]);

            $this->command->line("  ✓ Departure #{$departure->id} — {$departure->tanker_number} ({$departure->driver})");
        }

        // ── 6. Staff approval audit logs ──────────────────────────────────
        foreach ($staffMembers as $staff) {
            AuditLog::create([
                'user_id'     => $admin->id,
                'action'      => 'staff_approved',
                'description' => "Admin approved staff: {$staff->email}",
                'model_type'  => User::class,
                'model_id'    => $staff->id,
                'ip_address'  => '127.0.0.1',
                'created_at'  => $staff->approved_at ?? now()->subDays(5),
                'updated_at'  => $staff->approved_at ?? now()->subDays(5),
            ]);
        }

        // ── 7. Print final stock summary ──────────────────────────────────
        $this->command->info('');
        $this->command->info('Final Fuel Stocks:');
        foreach (FuelStock::all() as $stock) {
            $this->command->line("  {$stock->fuel_type}: {$stock->liters} L");
        }

        $this->command->info('');
        $this->command->info('Credentials:');
        $this->command->line('  Admin  → admin@aaron.com / password');
        $this->command->line('  Staff  → maria@aaron.com / password');
        $this->command->line('  Staff  → jose@aaron.com  / password');
        $this->command->line('  Staff  → ana@aaron.com   / password');
    }

    /**
     * Pick 1–3 random fuel types, optionally excluding some.
     */
    private function randomFuelSubset(array $exclude = []): array
    {
        $available = array_diff($this->fuelTypes, $exclude);
        shuffle($available);
        $count = rand(1, min(3, count($available)));
        return array_slice($available, 0, $count);
    }
}