<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\Master\Common\Branch;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InitialBalanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        AccountTransaction::create([
//            'branch_id' => Branch::where('name', 'Dumai')->first()->id,
//            'date' => Carbon::now()->subYear()->endOfYear(),
//            'account_id' => Account::where('name', 'Kas Tunai')->first()->id,
//            'description' => 'SA',
//            'transaction_type' => 'SA',
//            'entries_type' => 'debit',
//            'amount' => 1000000000,
//        ]);
    }
}
