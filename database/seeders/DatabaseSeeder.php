<?php

namespace Database\Seeders;

use App\Models\AccountingPeriod;
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
            FpDeviceSeeder::class,
            AccountCategorySeeder::class,
            CompanySeeder::class,
            AccountSeeder::class,
//            AccountMayatamaSeeder::class,
//            AccountPSGSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            PermissionSeeder::class,
            ServicesCategoriesSeeder::class,
            CompanyProfileSeeder::class,
            LetterHeadSeeder::class,
            UnitTypesSeeder::class,
            WorkTimeSeeder::class,
            AccountingPeriodSeeder::class,
            RoleHasDepartmentSeeder::class,
            PayrollScheduleSeeder::class,
            BPJSKetSeeder::class,
            CutOffPayrollSettingSeeder::class,
            BroadbandPacketSeeder::class,
            SalaryCalculationMethodSeeder::class,
            TaxSettingSeeder::class,
            ItemCategoriesSeeder::class,
            ItemCollectionSeeder::class,
            AreaSeeder::class,
            WeekHolidaySeeder::class,
            ContactSeeder::class,
            RoleHierarchySeeder::class,
//            InitialBalanceSeeder::class,
            TransactionSeeder::class,
//            AccountTransactionSeeder::class,
        ]);
    }
}
