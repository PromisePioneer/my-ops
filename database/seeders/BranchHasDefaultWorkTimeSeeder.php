<?php

namespace Database\Seeders;

use App\Models\BranchHasDefaultWorkTime;
use App\Models\Master\Common\Branch;
use App\Models\Role;
use App\Models\WorkTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchHasDefaultWorkTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = Branch::whereNull('parent_id')->get();

        $role = [
            'Branch Manager',
            'Finance & Accounting Staff',
            'Stocker Staff',
            'Support',
            'Head Engineer'
        ];

        foreach ($branches as $branch) {
            foreach ($role as $roleName) {
                BranchHasDefaultWorkTime::create([
                    'branch_id' => $branch->id,
                    'role_id' => Role::where('name', $roleName)->first()->id,
                    'work_time_id' => WorkTime::where('name', 'Pagi')->first()->id,
                ]);
            }
        }
    }
}
