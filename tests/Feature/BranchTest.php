<?php


test('it can access branch menu', function () {
    $permissions = [
        'Lihat Menu Riwayat Absensi',
        'Lihat Detail Riwayat Absensi',
        'Ubah Data Riwayat Absensi',
        'Filter Data Riwayat Absensi Berdasarkan Cabang',
        'Koreksi Data Riwayat Absensi',
    ];


    setUpUserWithPermissions($permissions)->get('/master/common/branch')->assertStatus(200);
});
