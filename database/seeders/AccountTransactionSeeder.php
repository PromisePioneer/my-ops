<?php

namespace Database\Seeders;

use AllowDynamicProperties;
use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\Master\Common\Branch;
use App\Support\AccountTransactions\Service\AccountTransactionService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

#[AllowDynamicProperties] class AccountTransactionSeeder extends Seeder
{


    public function __construct()
    {
        $this->accountTransactionService = new AccountTransactionService();
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 100000; $i++) {
            $this->accountTransactionService->createDebitTransaction(
                Branch::whereNull('parent_id')->inRandomOrder()->first()->id,
                'test data',
                Account::inRandomOrder()->first()->id,
                random_int(1000000, 9999999),
            );


            $this->accountTransactionService->createCreditTransaction(
                Branch::whereNull('parent_id')->inRandomOrder()->first()->id,
                'test data',
                Account::inRandomOrder()->first()->id,
                random_int(1000000, 9999999),
            );
        }
    }
}
