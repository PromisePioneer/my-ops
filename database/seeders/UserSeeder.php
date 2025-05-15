<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Master\Common\Branch;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');


        //super admin
        $superAdmin = User::factory()->create([
            'absent_id' => 999,
            'nip' => 112,
            'join_date' => $faker->date(),
            'name' => 'Super Admin',
            'email' => 'superadmin@mayatama.net',
            'password' => Hash::make('M4y4t4m4S0lus1nd0'),
            'branch_id' => null,
            'company_id' => 1,
            'placement' => 'Pusat',
        ]);
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        $superAdmin->assignRole($superAdminRole->name);


        //director
        $director = User::factory()->create([
            'branch_id' => null,
            'absent_id' => fake()->randomNumber('3'),
            'nip' => 1,
            'join_date' => $faker->date(),
            'name' => fake()->name,
            'email' => fake()->email . '@mayatama.net',
            'password' => Hash::make('M4y4t4m4S0lus1nd0'),
            'company_id' => 1,
            'placement' => 'Pusat',
        ]);
        $directorRole = Role::where('name', 'Director')->first();
        $director->assignRole($directorRole->name);


        //general manager
        $generalManager = User::factory()->create([
            'absent_id' => fake()->randomNumber('3'),
            'nip' => 4,
            'join_date' => $faker->date(),
            'name' => fake()->name,
            'email' => fake()->name . '@mayatama.net',
            'password' => Hash::make('M4y4t4m4S0lus1nd0'),
            'branch_id' => null,
            'company_id' => 1,
            'placement' => 'Pusat',
        ]);
        $generalManagerRole = Role::where('name', 'General Manager')->first();
        $generalManager->assignRole($generalManagerRole->name);

        $this->noc();
        $this->branchEmployee();
        $this->warehouseEmployee();
        $this->financeEmployee();
        $this->operationalEmployee();
    }


    public function operationalEmployee(): void
    {
//        Role::create(['name' => 'Stocker Supervisor']);
//        Role::create(['name' => 'Project Controller & Vendor Supervisor']);
//        Role::create(['name' => 'Quality Controller Supervisor']);
//        Role::create(['name' => 'Trainer & Quality Control Staff']);
//        Role::create(['name' => 'Quality Control Staff']);
//        Role::create(['name' => 'After Sales Customer Service']);
//        Role::create(['name' => 'Stocker Staff']);
//        Role::create(['name' => 'Graphic Designer & Socmed Admin']);
//        Role::create(['name' => 'Mechanic Senior Staff']);
//        Role::create(['name' => 'Support']);
//        Role::create(['name' => 'Welding Senior Engineer']);
//        Role::create(['name' => 'Electrical Senior Engineer']);


        //operational manager
        $operationalManager = User::factory()->create([
            'absent_id' => fake()->randomNumber('3'),
            'nip' => 2,
            'join_date' => fake()->date(),
            'name' => fake()->name,
            'email' => fake()->name . '@mayatama.net',
            'password' => Hash::make('M4y4t4m4S0lus1nd0'),
            'branch_id' => null,
            'company_id' => Company::where('name', 'Mayatama Solusindo')->first()->id,
            'placement' => 'Pusat',
        ]);
        $operationalManagerRole = Role::where('name', 'Operational Manager')->first();
        $operationalManager->assignRole($operationalManagerRole->name);


        $legalCommissioner = User::factory()->create([
            'absent_id' => 2,
            'nip' => 2,
            'join_date' => fake()->date(),
            'name' => fake()->name,
            'email' => fake()->name . '@mayatama.net',
            'password' => Hash::make('password'),
            'branch_id' => null,
            'company_id' => Company::where('name', 'Mayatama Solusindo')->first()->id,
            'placement' => 'Pusat',
        ]);
        $legalCommissionerRole = Role::where('name', 'Legal & Corporate Commissioner')->first();
        $legalCommissioner->assignRole($legalCommissionerRole->name);

        $hrOperationalStaff = User::factory()->create([
            'absent_id' => fake()->randomNumber('3'),
            'nip' => 2,
            'join_date' => fake()->date(),
            'name' => fake()->name,
            'email' => fake()->name . '@mayatama.net',
            'password' => Hash::make('M4y4t4m4S0lus1nd0'),
            'branch_id' => null,
            'company_id' => Company::where('name', 'Mayatama Solusindo')->first()->id,
            'placement' => 'Pusat',
        ]);
        $hrOperationalStaffRole = Role::where('name', 'HR & Operational Staff')->first();
        $hrOperationalStaff->assignRole($hrOperationalStaffRole->name);

        $backboneTeamSupervisor = User::factory()->create([
            'absent_id' => fake()->randomNumber('3'),
            'nip' => 2,
            'join_date' => fake()->date(),
            'name' => fake()->name,
            'email' => fake()->name . '@mayatama.net',
            'password' => Hash::make('M4y4t4m4S0lus1nd0'),
            'branch_id' => null,
            'company_id' => Company::where('name', 'Mayatama Solusindo')->first()->id,
            'placement' => 'Pusat',
        ]);

        $backboneTeamSupervisorRole = Role::where('name', 'Backbone Team Supervisor')->first();
        $backboneTeamSupervisor->assignRole($backboneTeamSupervisorRole->name);
    }

    public function warehouseEmployee(): void
    {

        //warehouse supervisor
        $warehouseSupervisor = User::factory()->create([
            'absent_id' => fake()->randomNumber('3'),
            'nip' => 4,
            'join_date' => fake()->date(),
            'name' => fake()->name,
            'email' => fake()->name . '@mayatama.net',
            'password' => Hash::make('M4y4t4m4S0lus1nd0'),
            'branch_id' => null,
            'company_id' => Company::where('name', 'Mayatama Solusindo')->first()->id,
            'placement' => 'Pusat',
        ]);
        $generalManagerRole = Role::where('name', 'Warehouse Supervisor')->first();
        $warehouseSupervisor->assignRole($generalManagerRole->name);

        for ($i = 0; $i < 5; $i++) {
            $warehouseStockerStaff = User::factory()->create([
                'absent_id' => fake()->randomNumber('3'),
                'nip' => 4,
                'join_date' => fake()->date(),
                'name' => fake()->name,
                'email' => fake()->name . '@mayatama.net',
                'password' => Hash::make('M4y4t4m4S0lus1nd0'),
                'branch_id' => null,
                'company_id' => Company::where('name', 'CV. Prestasi Sukses Gemilang')->first()->id,
                'placement' => 'Pusat',
            ]);
            $warehouseStockerRole = Role::where('name', 'Warehouse Stocker Staff')->first();
            $warehouseStockerStaff->assignRole($warehouseStockerRole->name);


            $warehouseSecurityStaff = User::factory()->create([
                'absent_id' => fake()->randomNumber('3'),
                'nip' => 4,
                'join_date' => fake()->date(),
                'name' => fake()->name,
                'email' => fake()->name . '@mayatama.net',
                'password' => Hash::make('M4y4t4m4S0lus1nd0'),
                'branch_id' => null,
                'company_id' => Company::where('name', 'CV. Prestasi Sukses Gemilang')->first()->id,
                'placement' => 'Pusat',
            ]);
            $warehouseSecurityRole = Role::where('name', 'Warehouse Stocker Staff')->first();
            $warehouseSecurityStaff->assignRole($warehouseSecurityRole->name);
        }

    }

    public function noc(): void
    {
        $nocSupervisor = User::factory()->create([
            'absent_id' => fake()->randomNumber('3'),
            'nip' => 4,
            'join_date' => fake()->date(),
            'name' => fake()->name,
            'email' => fake()->name . '@mayatama.net',
            'password' => Hash::make('M4y4t4m4S0lus1nd0'),
            'branch_id' => null,
            'company_id' => Company::where('name', 'Mayatama Solusindo')->first()->id,
            'placement' => 'Pusat',
        ]);
        $nocSupervisorRole = Role::where('name', 'NOC Supervisor')->first();
        $nocSupervisor->assignRole($nocSupervisorRole->name);


        for ($i = 0; $i < 5; $i++) {
            $noc = User::factory()->create([
                'absent_id' => fake()->randomNumber('3'),
                'nip' => 4,
                'join_date' => fake()->date(),
                'name' => fake()->name,
                'email' => fake()->name . '@mayatama.net',
                'password' => Hash::make('M4y4t4m4S0lus1nd0'),
                'branch_id' => Branch::where('name', 'Dumai')->first()->id,
                'company_id' => Company::where('name', 'CV. Prestasi Sukses Gemilang')->first()->id,
                'placement' => 'Cabang',
            ]);
            $nocRole = Role::where('name', 'NOC Staff')->first();
            $noc->assignRole($nocRole->name);
        }
    }

    public function branchEmployee(): void
    {
        $branches = Branch::whereNull('parent_id')->get();
        foreach ($branches as $branch) {
            //branch manager
            $branchManager = User::factory()->create([
                'branch_id' => $branch->id,
                'absent_id' => fake()->randomNumber('3'),
                'nip' => 4,
                'join_date' => fake()->date(),
                'name' => fake()->name,
                'email' => fake()->name . '@mayatama.net',
                'password' => Hash::make('M4y4t4m4S0lus1nd0'),
                'company_id' => Company::where('name', 'Mayatama Solusindo')->first()->id,
                'placement' => 'Cabang',
            ]);
            $branchManagerRole = Role::where('name', 'Branch Manager')->first();
            $branchManager->assignRole($branchManagerRole->name);


            //branch accounting staff
            $faStaff = User::factory()->create([
                'branch_id' => $branch->id,
                'absent_id' => fake()->randomNumber('3'),
                'nip' => 4,
                'join_date' => fake()->date(),
                'name' => fake()->name,
                'email' => fake()->name . '@mayatama.net',
                'password' => Hash::make('M4y4t4m4S0lus1nd0'),
                'company_id' => Company::where('name', 'CV. Prestasi Sukses Gemilang')->first()->id,
                'placement' => 'Cabang',
            ]);
            $faStaffRole = Role::where('name', 'Finance & Accounting Staff')->first();
            $faStaff->assignRole($faStaffRole->name);


            $headEngineer = User::factory()->create([
                'branch_id' => $branch->id,
                'absent_id' => fake()->randomNumber('3'),
                'nip' => 4,
                'join_date' => fake()->date(),
                'name' => fake()->name,
                'email' => fake()->name . '@mayatama.net',
                'password' => Hash::make('M4y4t4m4S0lus1nd0'),
                'company_id' => Company::where('name', 'CV. Prestasi Sukses Gemilang')->first()->id,
                'placement' => 'Cabang',
            ]);
            $headEngineerRole = Role::where('name', 'Head Engineer')->first();
            $headEngineer->assignRole($headEngineerRole->name);

            $stocker = User::factory()->create([
                'branch_id' => $branch->id,
                'absent_id' => fake()->randomNumber('3'),
                'nip' => 4,
                'join_date' => fake()->date(),
                'name' => fake()->name,
                'email' => fake()->name . '@mayatama.net',
                'password' => Hash::make('password'),
                'company_id' => Company::where('name', 'CV. Prestasi Sukses Gemilang')->first()->id,
                'placement' => 'Cabang',
            ]);
            $stockerRole = Role::where('name', 'Stocker Staff')->first();
            $stocker->assignRole($stockerRole->name);


            for ($i = 0; $i < 5; $i++) {
                $engineer = User::factory()->create([
                    'branch_id' => $branch->id,
                    'absent_id' => fake()->randomNumber('3'),
                    'nip' => 4,
                    'join_date' => fake()->date(),
                    'name' => fake()->name,
                    'email' => fake()->name . '@mayatama.net',
                    'password' => Hash::make('M4y4t4m4S0lus1nd0'),
                    'company_id' => Company::where('name', 'CV. Prestasi Sukses Gemilang')->first()->id,
                    'placement' => 'Cabang',
                ]);
                $engineerRole = Role::where('name', 'Engineer')->first();
                $engineer->assignRole($engineerRole->name);
            }
        }
    }

    private function financeEmployee(): void
    {
        //fa & tax manager
        $accountingManager = User::factory()->create([
            'absent_id' => 3,
            'nip' => 3,
            'join_date' => fake()->date(),
            'name' => fake()->name,
            'email' => fake()->name . '@mayatama.net',
            'password' => Hash::make('M4y4t4m4S0lus1nd0'),
            'branch_id' => null,
            'company_id' => Company::where('name', 'Mayatama Solusindo')->first()->id,
            'placement' => 'Pusat',
        ]);

        $accountingManagerRole = Role::where('name', 'FA & Tax Manager')->first();
        $accountingManager->assignRole($accountingManagerRole->name);

        $taxAdminSupervisor = User::factory()->create([
            'branch_id' => null,
            'absent_id' => fake()->randomNumber('3'),
            'nip' => 4,
            'join_date' => fake()->date(),
            'name' => fake()->name,
            'email' => fake()->name . '@mayatama.net',
            'password' => Hash::make('M4y4t4m4S0lus1nd0'),
            'company_id' => Company::where('name', 'Mayatama Solusindo')->first()->id,
            'placement' => 'Pusat',
        ]);
        $taxAdminSupervisorRole = Role::where('name', 'Tax Admin Supervisor')->first();
        $taxAdminSupervisor->assignRole($taxAdminSupervisorRole->name);


        $billingAdminSupervisor = User::factory()->create([
            'branch_id' => null,
            'absent_id' => fake()->randomNumber('3'),
            'nip' => 4,
            'join_date' => fake()->date(),
            'name' => fake()->name,
            'email' => fake()->name . '@mayatama.net',
            'password' => Hash::make('M4y4t4m4S0lus1nd0'),
            'company_id' => Company::where('name', 'Mayatama Solusindo')->first()->id,
            'placement' => 'Pusat',
        ]);
        $billingAdminSupervisorRole = Role::where('name', 'Billing Admin Supervisor')->first();
        $billingAdminSupervisor->assignRole($billingAdminSupervisorRole->name);

        $inventoryControllerSupervisor = User::factory()->create([
            'branch_id' => null,
            'absent_id' => fake()->randomNumber('3'),
            'nip' => 4,
            'join_date' => fake()->date(),
            'name' => fake()->name,
            'email' => fake()->name . '@mayatama.net',
            'password' => Hash::make('M4y4t4m4S0lus1nd0'),
            'company_id' => Company::where('name', 'Mayatama Solusindo')->first()->id,
            'placement' => 'Pusat',
        ]);
        $inventoryControllerSupervisorRole = Role::where('name', 'Inventory Controller Supervisor')->first();
        $inventoryControllerSupervisor->assignRole($inventoryControllerSupervisorRole->name);


        $customerPaymentSupervisor = User::factory()->create([
            'branch_id' => null,
            'absent_id' => fake()->randomNumber('3'),
            'nip' => 4,
            'join_date' => fake()->date(),
            'name' => fake()->name,
            'email' => fake()->name . '@mayatama.net',
            'password' => Hash::make('M4y4t4m4S0lus1nd0'),
            'company_id' => Company::where('name', 'Mayatama Solusindo')->first()->id,
            'placement' => 'Pusat',
        ]);
        $customerPaymentSupervisorRole = Role::where('name', 'Customer Payment Supervisor')->first();
        $customerPaymentSupervisor->assignRole($customerPaymentSupervisorRole->name);


        $financeAccountingSupervisor = User::factory()->create([
            'branch_id' => null,
            'absent_id' => fake()->randomNumber('3'),
            'nip' => 4,
            'join_date' => fake()->date(),
            'name' => fake()->name,
            'email' => fake()->name . '@mayatama.net',
            'password' => Hash::make('M4y4t4m4S0lus1nd0'),
            'company_id' => Company::where('name', 'Mayatama Solusindo')->first()->id,
            'placement' => 'Pusat',
        ]);
        $financeAccountingSupervisorRole = Role::where('name', 'Finance & Accounting Supervisor')->first();
        $financeAccountingSupervisor->assignRole($financeAccountingSupervisorRole->name);

        $faSeniorStaff = User::factory()->create([
            'branch_id' => null,
            'absent_id' => fake()->randomNumber('3'),
            'nip' => 4,
            'join_date' => fake()->date(),
            'name' => fake()->name,
            'email' => fake()->name . '@mayatama.net',
            'password' => Hash::make('M4y4t4m4S0lus1nd0'),
            'company_id' => Company::where('name', 'CV. Prestasi Sukses Gemilang')->first()->id,
            'placement' => 'Pusat',
        ]);
        $faSeniorStaffRole = Role::where('name', 'FA Senior Staff')->first();
        $faSeniorStaff->assignRole($faSeniorStaffRole->name);
    }
}
