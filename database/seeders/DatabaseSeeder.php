<?php

namespace Database\Seeders;

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
            AccountSeeder::class,
            CompanySeeder::class,
            RoleSeeder::class,
            MenuSectionSeeder::class,
            MenuSeeder::class,
            UserSeeder::class,
            PermissionSeeder::class,
            ServicesCategoriesSeeder::class,
            OfferingLetterSeeder::class,
            CompanyProfileSeeder::class,
            LetterHeadSeeder::class,
            UnitTypesSeeder::class,
            WorkTimeSeeder::class,
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
        ]);
    }
}
