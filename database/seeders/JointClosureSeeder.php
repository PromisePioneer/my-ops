<?php

namespace Database\Seeders;

use App\Models\JointClosure;
use App\Models\JointClosureCode;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JointClosureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JointClosure::create([
            'code_id' => 1,
            'region' => 'asdasdasd',
            'fo_cable_id' => 1,
            'lat' => 12321321,
            'long' => 123213,
            'cut_off_date' => Carbon::now(),
        ]);
    }
}
