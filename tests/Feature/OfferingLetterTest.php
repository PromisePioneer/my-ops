<?php

beforeEach(function () {
    $this->user = setUpUserWithPermissions(
        [
            'Lihat Menu Penawaran',
            'Tambah Data Penawaran',
            'Edit Data Penawaran',
            'Lihat Detail Penawaran',
            'Print Data Penawaran',
            'Hapus Data Penawaran',
            'Konfirmasi Data Penawaran'
        ]
    );
});


it('can access page with a correct permission', function () {
    $this->actingAs($this->user);
    $this->get(url('income-transactions/offering-letter'))
        ->assertViewIs('pages.transaction.offering-letters.index');
});
