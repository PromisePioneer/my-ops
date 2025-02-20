<?php

namespace Database\Seeders;

use App\Models\PSB;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PSBSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 81; $i++) {
            PSB::create([
                'date' => Carbon::now()->format('Y-m-d'),
                'registration_date' => Carbon::now()->format('Y-m-d'),
                'active_date' => Carbon::now()->format('Y-m-d'),
                'phone_number' => fake()->phoneNumber,
                'address' => fake()->address,
                'area_id' => 1,
                'technician' => json_encode('vdr-dumai-2'),
                'last_pay' => Carbon::now()->format('Y-m-d'),
                'pic' => 1,
            ]);
        }
    }
}
