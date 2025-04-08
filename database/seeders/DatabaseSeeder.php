<?php

namespace Database\Seeders;

use App\Models\AccountCategory;
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
            AccountCategorySeeder::class,
            AccountSeeder::class,
            SubAccountSeeder::class,
            CompanySeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            PermissionSeeder::class,
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
            AttendancesSummarySeeder::class,
            ItemCategoriesSeeder::class,
            WarehouseSeeder::class,
            GoodsSeeder::class,
            SupplierSeeder::class,
            AreaSeeder::class,
            PSBSeeder::class,
            WeekHolidaySeeder::class,
            ContactSeeder::class,
            RoleHierarchySeeder::class,
        ]);
    }
}
