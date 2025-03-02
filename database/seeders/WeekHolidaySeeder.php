<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WeekHoliday;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WeekHolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        foreach ($users as $user){
            WeekHoliday::create([
                'user_id' => $user->id,
                'day' => 'Senin',
            ]);
        }
    }
}
