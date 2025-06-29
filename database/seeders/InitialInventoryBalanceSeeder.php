<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\InitialInventoryBalance;
use App\Models\ItemCollection;
use App\Models\Master\Common\Branch;
use App\Models\Master\Common\Contact;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InitialInventoryBalanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //transaksi aset PKP (ASET)
        $qty1 = 300;
        $unitPrice1 = 300000;
        InitialInventoryBalance::create([
            'branch_id' => Branch::where('name', 'Kantor')->where('parent_id', 1)->first()->id,
            'contact_id' => Contact::where('tax_type', 'PKP')->first()->id,
            'date' => Carbon::now()->format('Y-m-d'),
            'item_id' => ItemCollection::where('name', 'Box ODC')->first()->id,
            'qty' => $qty1,
            'unit_price' => $unitPrice1,
            'total_price' => $qty1 * $unitPrice1,
            'detail' => 'Pembelian Box ODC Persediaan Awal',
            'stock_account_id' => Account::where('code', '112-01')->first()->id,
            'created_by' => User::where('name', 'Super Admin')->first()->id,
            'attachment' => 'test.jpg',
        ]);


        //transaksi aset PKP (JUAL)
        $qty2 = 300;
        $unitPrice2 = 300000;
        InitialInventoryBalance::create([
            'branch_id' => Branch::where('name', 'Kantor')->where('parent_id', 1)->first()->id,
            'contact_id' => Contact::where('tax_type', 'PKP')->first()->id,
            'date' => Carbon::now()->format('Y-m-d'),
            'item_id' => ItemCollection::where('name', 'Pigtail')->first()->id,
            'qty' => $qty2,
            'unit_price' => $unitPrice2,
            'total_price' => $qty2 * $unitPrice2,
            'detail' => 'Pembelian Pigtail persediaan Awal',
            'stock_account_id' => Account::where('code', '112-01')->first()->id,
            'created_by' => User::where('name', 'Super Admin')->first()->id,
            'attachment' => 'test.jpg',
        ]);


        //transaksi aset Non PKP (ASET)
        $qty3 = 200;
        $unitPrice3 = 200000;
        InitialInventoryBalance::create([
            'branch_id' => Branch::where('name', 'Kantor')->where('parent_id', 1)->first()->id,
            'contact_id' => Contact::where('tax_type', 'NON PKP')->first()->id,
            'date' => Carbon::now()->format('Y-m-d'),
            'item_id' => ItemCollection::where('name', 'GPON')->first()->id,
            'qty' => $qty3,
            'unit_price' => $unitPrice3,
            'total_price' => $qty3 * $unitPrice3,
            'detail' => 'Pembelian GPON persediaan Awal',
            'stock_account_id' => Account::where('code', '112-01')->first()->id,
            'created_by' => User::where('name', 'Super Admin')->first()->id,
            'attachment' => 'test.jpg',
        ]);


        //transaksi aset Non PKP (ASET)
        $qty4 = 20;
        $unitPrice4 = 5000000;
        InitialInventoryBalance::create([
            'branch_id' => Branch::where('name', 'Kantor')->where('parent_id', 1)->first()->id,
            'contact_id' => Contact::where('tax_type', 'NON PKP')->first()->id,
            'date' => Carbon::parse('07-07-2020 00:00:00')->format('Y-m-d'),
            'item_id' => ItemCollection::where('name', 'KU 96 Core')->first()->id,
            'qty' => $qty4,
            'qty_in_meter' => 2000,
            'unit_price' => $unitPrice4,
            'total_price' => $qty4 * $unitPrice4,
            'detail' => 'Pembelian KU 96 Core Persediaan awal',
            'stock_account_id' => Account::where('code', '112-01')->first()->id,
            'created_by' => User::where('name', 'Super Admin')->first()->id,
            'attachment' => 'test.jpg',
        ]);


        $qty5 = 10;
        $unitPrice5 = 20000000;
        InitialInventoryBalance::create([
            'branch_id' => Branch::where('name', 'Kantor')->where('parent_id', 1)->first()->id,
            'contact_id' => Contact::where('tax_type', 'NON PKP')->first()->id,
            'date' => Carbon::now()->format('Y-m-d'),
            'item_id' => ItemCollection::where('name', 'Splicer')->first()->id,
            'qty' => $qty3,
            'unit_price' => $unitPrice5,
            'total_price' => $qty5 * $unitPrice5,
            'detail' => 'Pembelian GPON Persediaan',
            'stock_account_id' => Account::where('code', '112-01')->first()->id,
            'created_by' => User::where('name', 'Super Admin')->first()->id,
            'attachment' => 'test.jpg',
        ]);
    }
}
