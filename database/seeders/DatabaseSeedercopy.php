<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\{
    User,
    FuelStock,
    TankerArrival,
    TankerArrivalFuel,
    TankerDeparture,
    TankerDepartureFuel,
    BrReceipt,
    BrReceiptFuel,
    BrReceiptPayment,
    AuditLog
};

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─────────────────────────────────────────────
        // 1️⃣ CREATE USERS
        // ─────────────────────────────────────────────

        $superAdmin = User::create([
            'first_name' => 'Super',
            'last_name'  => 'Admin',
            'email'      => 'superadmin@test.com',
            'password'   => Hash::make('password'),
            'role'       => 'super_admin',
            'status'     => 'approved',
        ]);

        $admin = User::create([
            'first_name' => 'System',
            'last_name'  => 'Admin',
            'email'      => 'admin@test.com',
            'password'   => Hash::make('password'),
            'role'       => 'admin',
            'status'     => 'approved',
            'approved_by'=> $superAdmin->id,
            'approved_at'=> now(),
        ]);

        $staffUsers = User::factory()->count(10)->create([
            'role'       => 'staff',
            'status'     => 'approved',
            'approved_by'=> $admin->id,
            'approved_at'=> now(),
        ]);

        // ─────────────────────────────────────────────
        // 2️⃣ INITIAL FUEL STOCK
        // ─────────────────────────────────────────────

        $fuelTypes = ['methanol', 'premium', 'diesel', 'unleaded'];

        foreach ($fuelTypes as $fuel) {
            FuelStock::updateOrCreate(
                ['fuel_type' => $fuel],
                ['liters' => 50000]
            );
        }

        // ─────────────────────────────────────────────
        // 3️⃣ SEED 100 CONNECTED RECORDS
        // ─────────────────────────────────────────────

        for ($i = 1; $i <= 100; $i++) {

            $staff = $staffUsers->random();

            // ───────────── ARRIVAL ─────────────
            $arrival = TankerArrival::create([
                'recorded_by'   => $staff->id,
                'tanker_number' => 'ARR-' . Str::random(5),
                'arrival_date'  => now()->subDays(rand(1, 60)),
            ]);

            foreach ($fuelTypes as $fuel) {
                $liters = rand(5000, 10000);

                TankerArrivalFuel::create([
                    'tanker_arrival_id' => $arrival->id,
                    'fuel_type'         => $fuel,
                    'liters'            => $liters,
                ]);

                FuelStock::add($fuel, $liters);
            }

            // ───────────── DEPARTURE ─────────────
            $departure = TankerDeparture::create([
                'recorded_by'   => $staff->id,
                'tanker_number' => 'DEP-' . Str::random(5),
                'driver'        => fake()->name(),
                'departure_date'=> now()->subDays(rand(1, 30)),
            ]);

            $totalAmount = 0;

            foreach ($fuelTypes as $fuel) {

                $liters = rand(1000, 3000);
                $price  = rand(40, 70);
                $amount = $liters * $price;

                TankerDepartureFuel::create([
                    'tanker_departure_id' => $departure->id,
                    'fuel_type'           => $fuel,
                    'liters'              => $liters,
                    'methanol_percent'    => rand(0, 100),
                    'methanol_liters'     => rand(0, $liters),
                    'pure_liters'         => rand(0, $liters),
                ]);

                FuelStock::subtract($fuel, $liters);

                $totalAmount += $amount;
            }

            // ───────────── RECEIPT ─────────────
            $receipt = BrReceipt::create([
                'tanker_departure_id' => $departure->id,
                'recorded_by'         => $staff->id,
                'receipt_no'          => 'RCPT-' . strtoupper(Str::random(6)),
                'delivered_to'        => fake()->company(),
                'address'             => fake()->address(),
                'tin'                 => rand(100000, 999999),
                'terms'               => '30 days',
                'grand_total'         => $totalAmount,
            ]);

            foreach ($fuelTypes as $fuel) {

                $liters = rand(1000, 3000);
                $price  = rand(40, 70);

                BrReceiptFuel::create([
                    'br_receipt_id' => $receipt->id,
                    'fuel_type'     => $fuel,
                    'liters'        => $liters,
                    'unit_price'    => $price,
                    'amount'        => $liters * $price,
                    'remarks'       => 'Delivered successfully',
                ]);
            }

            // ───────────── PAYMENT ─────────────
            $downPayment = rand(0, $totalAmount / 2);
            $finalPayment = rand(0, $totalAmount - $downPayment);

            BrReceiptPayment::create([
                'br_receipt_id'     => $receipt->id,
                'recorded_by'       => $staff->id,
                'client_name'       => $receipt->delivered_to,
                'total_amount'      => $totalAmount,
                'down_payment'      => $downPayment,
                'down_payment_date' => now()->subDays(rand(1, 20)),
                'final_payment'     => $finalPayment,
                'final_payment_date'=> now()->subDays(rand(0, 10)),
                'due_date'          => now()->addDays(rand(5, 30)),
                'notes'             => 'Auto generated payment',
            ]);

            // ───────────── AUDIT LOG ─────────────
            AuditLog::create([
                'user_id'    => $staff->id,
                'action'     => 'created',
                'description'=> 'Created full tanker cycle record',
                'model_type' => TankerDeparture::class,
                'model_id'   => $departure->id,
                'meta'       => ['receipt_id' => $receipt->id],
                'ip_address' => '127.0.0.1',
            ]);
        }
    }
}
