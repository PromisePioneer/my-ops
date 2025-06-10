<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\ItemCollection;
use App\Models\Master\Common\Branch;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        //transaksi aset PKP (ASET)
        $qty1 = 300;
        $unitPrice1 = 300000;
        Transaction::create([
            'transaction_number' => '12345435',
            'branch_id' => Branch::where('name', 'Kantor')->where('parent_id', 1)->first()->id,
            'supplier_id' => Supplier::where('tax_type', 'PKP')->first()->id,
            'date' => Carbon::now()->format('Y-m-d'),
            'item_id' => ItemCollection::where('name', 'Box ODC')->first()->id,
            'qty' => $qty1,
            'type' => 'Barang',
            'unit_price' => $unitPrice1,
            'total_price' => $qty1 * $unitPrice1,
            'detail' => 'Pembelian Box ODC (PKP)',
            'debit_account_id' => Account::where('code', '112-01')->first()->id,
            'credit_account_id' => Account::where('code', '111-01')->first()->id,
            'created_by' => User::where('name', 'Super Admin')->first()->id,
            'attachment' => 'test.jpg',
            'tax_invoice' => 'test.jpg',
        ]);


        //transaksi aset PKP (JUAL)
        $qty2 = 300;
        $unitPrice2 = 300000;
        Transaction::create([
            'transaction_number' => '12345435',
            'branch_id' => Branch::where('name', 'Kantor')->where('parent_id', 1)->first()->id,
            'supplier_id' => Supplier::where('tax_type', 'PKP')->first()->id,
            'date' => Carbon::now()->format('Y-m-d'),
            'item_id' => ItemCollection::where('name', 'Pigtail')->first()->id,
            'qty' => $qty2,
            'type' => 'Barang',
            'unit_price' => $unitPrice2,
            'total_price' => $qty2 * $unitPrice2,
            'detail' => 'Pembelian Pigtail (PKP)',
            'debit_account_id' => Account::where('code', '112-01')->first()->id,
            'credit_account_id' => Account::where('code', '111-01')->first()->id,
            'created_by' => User::where('name', 'Super Admin')->first()->id,
            'attachment' => 'test.jpg',
            'tax_invoice' => 'test.jpg',
        ]);


        //transaksi aset Non PKP (ASET)
        $qty3 = 200;
        $unitPrice3 = 200000;
        Transaction::create([
            'transaction_number' => '12345435',
            'branch_id' => Branch::where('name', 'Kantor')->where('parent_id', 1)->first()->id,
            'supplier_id' => Supplier::where('tax_type', 'NON PKP')->first()->id,
            'date' => Carbon::now()->format('Y-m-d'),
            'item_id' => ItemCollection::where('name', 'GPON')->first()->id,
            'qty' => $qty3,
            'type' => 'Barang',
            'unit_price' => $unitPrice3,
            'total_price' => $qty3 * $unitPrice3,
            'detail' => 'Pembelian GPON (NON PKP)',
            'debit_account_id' => Account::where('code', '112-01')->first()->id,
            'credit_account_id' => Account::where('code', '111-01')->first()->id,
            'created_by' => User::where('name', 'Super Admin')->first()->id,
            'attachment' => 'test.jpg',
        ]);
    }
}
