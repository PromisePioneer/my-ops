<?php

namespace Database\Seeders;

use App\Models\JointClosureCode;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            DepartmentSeeder::class,
            BranchSeeder::class,
            AccountSeeder::class,
            SubAccountSeeder::class,
            UserSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            ProductSeeder::class,
            ServicesCategoriesSeeder::class,
            OfferingLetterSeeder::class,
            CompanyProfileSeeder::class,
            LetterHeadSeeder::class,
            UnitTypesSeeder::class,
            PositionSeeder::class,
            WorkTimeSeeder::class,
            FpDeviceSeeder::class,
            RoleHasDepartmentSeeder::class,
            PayrollScheduleSeeder::class,
            BPJSKetSeeder::class,
            CutOffPayrollSettingSeeder::class,
            BroadbandPacketSeeder::class,
            SalaryCalculationMethodSeeder::class,
            TaxSettingSeeder::class,
            JointClosureCodeSeeder::class,
            FoCableSeeder::class,
            JointClosureSeeder::class,
        ]);
    }
}
