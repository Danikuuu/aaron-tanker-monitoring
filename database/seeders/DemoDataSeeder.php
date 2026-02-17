<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{
    User,
    TankerArrival,
    TankerArrivalFuel,
    TankerDeparture,
    TankerDepartureFuel,
    FuelStock,
    BrReceipt,
    BrReceiptFuel,
    BrReceiptPayment,
    AuditLog
};
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    private array $fuelTypes = ['diesel', 'premium', 'unleaded', 'methanol'];
    private array $tankerNumbers = [
        'TKR-001','TKR-002','TKR-003','TKR-004','TKR-005',
        'TKR-006','TKR-007','TKR-008','TKR-009','TKR-010',
    ];
    private array $drivers = [
        'Juan dela Cruz','Pedro Santos','Mario Reyes','Jose Garcia','Ramon Flores',
        'Eduardo Lopez','Antonio Cruz','Roberto Diaz','Miguel Torres','Carlos Ramos',
    ];

    public function run(): void
    {
        // Create admin
        $admin = User::firstOrCreate(
            ['email'=>'admin@aaron.com'],
            [
                'first_name'=>'Admin',
                'last_name'=>'Aaron',
                'password'=>Hash::make('password'),
                'role'=>'admin',
                'status'=>'approved',
            ]
        );

        // Create staff
        $staffData = [
            ['first_name'=>'Maria','last_name'=>'Santos'],
            ['first_name'=>'Jose','last_name'=>'Reyes'],
            ['first_name'=>'Ana','last_name'=>'Garcia'],
        ];
        $staffMembers = [];
        foreach($staffData as $data){
            $staffMembers[] = User::firstOrCreate(
                ['email'=>strtolower($data['first_name']).'@aaron.com'],
                [
                    'first_name'=>$data['first_name'],
                    'last_name'=>$data['last_name'],
                    'password'=>Hash::make('password'),
                    'role'=>'staff',
                    'status'=>'approved',
                    'approved_by'=>$admin->id,
                    'approved_at'=>now()->subDays(rand(10,30)),
                ]
            );
        }
        $allUsers = array_merge([$admin], $staffMembers);

        // Reset fuel stock
        foreach($this->fuelTypes as $type){
            FuelStock::updateOrCreate(['fuel_type'=>$type],['liters'=>0]);
        }

        // 10 Tanker Arrivals
        $this->command->info('Seeding 10 tanker arrivals...');
        for($i=0;$i<10;$i++){
            $recorder = $allUsers[array_rand($allUsers)];
            $arrivalDate = now()->subDays(rand(1,180))->startOfDay();
            $fuels = $this->randomFuelSubset();

            $arrival = TankerArrival::create([
                'recorded_by'=>$recorder->id,
                'tanker_number'=>$this->tankerNumbers[$i],
                'arrival_date'=>$arrivalDate,
                'created_at'=>$arrivalDate,
                'updated_at'=>$arrivalDate,
            ]);

            foreach($fuels as $fuel){
                $liters = rand(500,5000);
                TankerArrivalFuel::create([
                    'tanker_arrival_id'=>$arrival->id,
                    'fuel_type'=>$fuel,
                    'liters'=>$liters,
                    'created_at'=>$arrivalDate,
                    'updated_at'=>$arrivalDate,
                ]);
                FuelStock::add($fuel,$liters);
            }

            AuditLog::create([
                'user_id'=>$recorder->id,
                'action'=>'tanker_arrival',
                'description'=>"Tanker {$arrival->tanker_number} arrived with: ".implode(', ',$fuels),
                'model_type'=>TankerArrival::class,
                'model_id'=>$arrival->id,
                'ip_address'=>'127.0.0.1',
                'created_at'=>$arrivalDate,
                'updated_at'=>$arrivalDate,
            ]);
        }

        // 10 Tanker Departures with BR receipts & payments
        $this->command->info('Seeding 10 tanker departures with BR receipts & payments...');
        for($i=0;$i<10;$i++){
            $recorder = $allUsers[array_rand($allUsers)];
            $departureDate = now()->subDays(rand(1,90))->startOfDay();
            $fuels = $this->randomFuelSubset(exclude:['methanol']);

            $departure = TankerDeparture::create([
                'recorded_by'=>$recorder->id,
                'tanker_number'=>'DEP-'.str_pad($i+1,3,'0',STR_PAD_LEFT),
                'driver'=>$this->drivers[$i],
                'departure_date'=>$departureDate,
                'created_at'=>$departureDate,
                'updated_at'=>$departureDate,
            ]);

            foreach($fuels as $fuel){
                $stock = FuelStock::getStock($fuel);
                $liters = min(rand(100,2000), max(100,$stock*0.6));
                $methanolPct = rand(15,30);
                $methanolLiters = round($liters*$methanolPct/100,2);
                $pureLiters = $liters - $methanolLiters;

                TankerDepartureFuel::create([
                    'tanker_departure_id'=>$departure->id,
                    'fuel_type'=>$fuel,
                    'liters'=>$liters,
                    'methanol_percent'=>$methanolPct,
                    'methanol_liters'=>$methanolLiters,
                    'pure_liters'=>$pureLiters,
                    'created_at'=>$departureDate,
                    'updated_at'=>$departureDate,
                ]);

                FuelStock::subtract($fuel,$liters);
                FuelStock::subtract('methanol',$methanolLiters);
            }

            AuditLog::create([
                'user_id'=>$recorder->id,
                'action'=>'tanker_departure',
                'description'=>"Tanker {$departure->tanker_number} departed with: ".implode(', ',$fuels).". Driver: {$departure->driver}",
                'model_type'=>TankerDeparture::class,
                'model_id'=>$departure->id,
                'ip_address'=>'127.0.0.1',
                'created_at'=>$departureDate,
                'updated_at'=>$departureDate,
            ]);

            // Generate corresponding BR receipt
            $receiptNo = 'BR-'.str_pad($i+1,4,'0',STR_PAD_LEFT);
            $receipt = BrReceipt::create([
                'tanker_departure_id'=>$departure->id,
                'recorded_by'=>$admin->id,
                'receipt_no'=>$receiptNo,
                'delivered_to'=>'Client '.($i+1),
                'address'=>'City '.($i+1),
                'tin'=>'123-456-'.str_pad($i+1,3,'0',STR_PAD_LEFT),
                'terms'=>'30 days',
                'grand_total'=>rand(500000,1000000),
            ]);

            // Add fuels to BR
            foreach($fuels as $fuel){
                $liters = rand(1000,5000);
                BrReceiptFuel::create([
                    'br_receipt_id'=>$receipt->id,
                    'fuel_type'=>$fuel,
                    'liters'=>$liters,
                    'unit_price'=>rand(100,200),
                    'amount'=>$liters*rand(100,200),
                    'remarks'=>'Delivered successfully',
                ]);
            }

            // Add payment
            $totalAmount = $receipt->grand_total;
            $downPayment = round($totalAmount*0.4); // 40%
            BrReceiptPayment::create([
                'br_receipt_id'=>$receipt->id,
                'recorded_by'=>$admin->id,
                'client_name'=>$receipt->delivered_to,
                'total_amount'=>$totalAmount,
                'down_payment'=>$downPayment,
                'down_payment_date'=>now()->subDay(),
                'final_payment'=>$totalAmount-$downPayment,
                'due_date'=>now()->addDays(30),
                'notes'=>'Demo payment',
            ]);
        }

        $this->command->info('Demo data seeded successfully!');
    }

    private function randomFuelSubset(array $exclude=[]): array
    {
        $available = array_diff($this->fuelTypes,$exclude);
        shuffle($available);
        $count = rand(1,min(3,count($available)));
        return array_slice($available,0,$count);
    }
}
