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
            AccountSeeder::class,
            SubAccountSeeder::class,
            UserSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            ContactSeeder::class,
            ProductSeeder::class,
            ServicesCategoriesSeeder::class,
            OfferingLetterSeeder::class,
            BastSeeder::class,
            CompanyProfileSeeder::class,
            LetterHeadSeeder::class,
            UnitTypesSeeder::class,
            PositionSeeder::class,
            UserPlacementSeeder::class,
        ]);
    }
}
