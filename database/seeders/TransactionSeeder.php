<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\ItemCollection;
use App\Models\Master\Common\Branch;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $unitPrice = fake()->numberBetween(100000, 1000000);
        $qty = fake()->numberBetween(1, 9999);


        //transaksi aset PKP (ASET)
        Transaction::create([
            'transaction_number' => '12345435',
            'branch_id' => Branch::where('name', 'Kantor')->where('parent_id', 1)->first()->id,
            'supplier_id' => Supplier::where('tax_type', 'PKP')->first()->id,
            'date' => Carbon::now()->format('Y-m-d'),
            'item_id' => ItemCollection::where('name', 'Box ODC')->first()->id,
            'qty' => $qty,
            'type' => 'Barang',
            'unit_price' => $unitPrice,
            'total_price' => $qty * $unitPrice,
            'detail' => 'Pembelian Box ODC (PKP)',
            'debit_account_id' => Account::where('code', '112-01')->first()->id,
            'credit_account_id' => Account::where('code', '111-01')->first()->id,
            'created_by' => User::where('name', 'Super Admin')->first()->id,
            'attachment' => 'test.jpg',
            'tax_invoice' => 'test.jpg',
        ]);


        //transaksi aset PKP (JUAL)
        Transaction::create([
            'transaction_number' => '12345435',
            'branch_id' => Branch::where('name', 'Kantor')->where('parent_id', 1)->first()->id,
            'supplier_id' => Supplier::where('tax_type', 'PKP')->first()->id,
            'date' => Carbon::now()->format('Y-m-d'),
            'item_id' => ItemCollection::where('name', 'Pigtail')->first()->id,
            'qty' => $qty,
            'type' => 'Barang',
            'unit_price' => $unitPrice,
            'total_price' => $qty * $unitPrice,
            'detail' => 'Pembelian Pigtail (PKP)',
            'debit_account_id' => Account::where('code', '112-01')->first()->id,
            'credit_account_id' => Account::where('code', '111-01')->first()->id,
            'created_by' => User::where('name', 'Super Admin')->first()->id,
            'attachment' => 'test.jpg',
            'tax_invoice' => 'test.jpg',
        ]);


        //transaksi aset Non PKP (ASET)
        Transaction::create([
            'transaction_number' => '12345435',
            'branch_id' => Branch::where('name', 'Kantor')->where('parent_id', 1)->first()->id,
            'supplier_id' => Supplier::where('tax_type', 'NON PKP')->first()->id,
            'date' => Carbon::now()->format('Y-m-d'),
            'item_id' => ItemCollection::where('name', 'GPON')->first()->id,
            'qty' => $qty,
            'type' => 'Barang',
            'unit_price' => $unitPrice,
            'total_price' => $qty * $unitPrice,
            'detail' => 'Pembelian GPON (NON PKP)',
            'debit_account_id' => Account::where('code', '112-01')->first()->id,
            'credit_account_id' => Account::where('code', '111-01')->first()->id,
            'created_by' => User::where('name', 'Super Admin')->first()->id,
            'attachment' => 'test.jpg',
        ]);
    }
}
