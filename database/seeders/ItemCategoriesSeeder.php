<?php

namespace Database\Seeders;

use App\Models\ItemCategory;
use Illuminate\Database\Seeder;

class ItemCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ItemCategory::create([
            'name' => 'Kategori 1',
            'notes' => 'Setiap NAMA BARANG memiliki banyak KODE, jadi perhitungan Barang Keluar dan Barang Kembali berdasarkan KODE. Tetapi perhitungan Laporan Stok, Re-Order dan Barang Masuk berdasarkan NAMA BARANG.',
            'description' => 'Khusus KU, satuannya harus meter, jika barang berupa gulungan atau haspel lebih dari 1, tetap dibuat 1 kode dengan urutan panjang yang disesuaikan dan satuan tetap meter.'
        ]);

        ItemCategory::create([
            'name' => 'Kategori 2',
            'notes' => 'Setiap NAMA BARANG memiliki banyak KODE, tapi perhitungan Barang Keluar dan Barang Kembali termasuk Laporan Stok, Re-Order, dan Barang Masuk berdasarkan NAMA BARANG.',
            'description' => 'Gpon & STB termasuk barang jual karena pelanggan bayar deposit, dan bisa menjadi pengembalian.'
        ]);

        ItemCategory::create([
            'name' => 'Kategori 3',
            'notes' => 'Setiap NAMA BARANG memiliki banyak KODE, Barang Keluar wajib dikembalikan dan didata Barang Kembali, perhitungan Laporan Stok, Re-Order dan Barang Masuk berdasarkan NAMA BARANG.',
            'description' => 'Kategori ini tidak ada yg dijual, semua barang yg digunakan wajib dikembalikan dan tetap menjadi ASET hingga nilainya habis.'
        ]);

        ItemCategory::create([
            'name' => 'Kategori 4',
            'notes' => 'Setiap NAMA BARANG memiliki KODE masing-masing bahkan tidak memiliki KODE, jadi perhitungan Barang Keluar dan Barang Kembali termasuk Laporan Stok, Re-Order dan Barang Masuk hanya berdasarkan NAMA BARANG itu sendiri.',
            'description' => 'Barang yang termasuk ASET biasanya yang digunakan di dalam Kantor atau di dalam Server, sementara barang JUAL biasanya dipakai untuk pelanggan.'
        ]);
    }
}
