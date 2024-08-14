<?php


beforeEach(function () {
    $this->user = setUpUserWithPermissions([
        'lihat department', 'tambah department', 'update department', 'hapus department'
    ]);
});

it('', function () {
    $user = $this->user;
});