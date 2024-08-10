<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('menus')->insert(
            [
                [
                    'id' => 1,
                    'parent_id' => 1,
                    'nama_menu' => 'Home',
                    'link_menu' => 'home',
                    'deskripsi_menu' => 'Home',
                    'icon_menu' => 'fas fa-home fs-1',
                    'level_menu' => null,
                    'no_urut' => 1,
                    'class_active' => '1',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 2,
                    'parent_id' => 2,
                    'nama_menu' => 'Master',
                    'link_menu' => 'master',
                    'deskripsi_menu' => 'Master',
                    'icon_menu' => 'bi bi-speedometer2 fs-1',
                    'level_menu' => null,
                    'no_urut' => 2,
                    'class_active' => '2',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 3,
                    'parent_id' => 2,
                    'nama_menu' => 'Cabang',
                    'link_menu' => 'master/branch',
                    'deskripsi_menu' => 'Master - Cabang',
                    'icon_menu' => null,
                    'level_menu' => null,
                    'no_urut' => 2,
                    'class_active' => '2,3',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 4,
                    'parent_id' => 2,
                    'nama_menu' => 'Contact',
                    'link_menu' => 'master/contact',
                    'deskripsi_menu' => 'Master - Contact',
                    'icon_menu' => null,
                    'level_menu' => null,
                    'no_urut' => 2,
                    'class_active' => '2,4',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 5,
                    'parent_id' => 2,
                    'nama_menu' => 'Produk',
                    'link_menu' => 'master/product',
                    'deskripsi_menu' => 'Master - Produk',
                    'icon_menu' => null,
                    'level_menu' => null,
                    'no_urut' => 2,
                    'class_active' => '2,5',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 6,
                    'parent_id' => 2,
                    'nama_menu' => 'Kategori Layanan',
                    'link_menu' => 'master/service-categories',
                    'deskripsi_menu' => 'Master - Kategori Layanan',
                    'icon_menu' => null,
                    'level_menu' => null,
                    'no_urut' => 2,
                    'class_active' => '2,6',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 7,
                    'parent_id' => 2,
                    'nama_menu' => 'Departemen',
                    'link_menu' => 'master/department',
                    'deskripsi_menu' => 'Master - Department',
                    'icon_menu' => null,
                    'level_menu' => null,
                    'no_urut' => 2,
                    'class_active' => '2,7',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 8,
                    'parent_id' => 2,
                    'nama_menu' => 'Jabatan',
                    'link_menu' => 'master/roles',
                    'deskripsi_menu' => 'Master - Jabatan',
                    'icon_menu' => null,
                    'level_menu' => null,
                    'no_urut' => 2,
                    'class_active' => '2,9',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 9,
                    'parent_id' => 10,
                    'nama_menu' => 'Setting',
                    'link_menu' => 'setting',
                    'deskripsi_menu' => 'Setting',
                    'icon_menu' => 'fa fa-cogs',
                    'level_menu' => null,
                    'no_urut' => 3,
                    'class_active' => '10',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
                [
                    'id' => 10,
                    'parent_id' => 10,
                    'nama_menu' => 'Menu Management',
                    'link_menu' => 'setting/menu',
                    'deskripsi_menu' => 'Setting - Menu Management',
                    'icon_menu' => null,
                    'level_menu' => null,
                    'no_urut' => 1,
                    'class_active' => '10,11',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ],
            ]
        );
    }
}
