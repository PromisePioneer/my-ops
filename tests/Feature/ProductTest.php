<?php

use App\Models\Product;

beforeEach(function () {
    $this->user = setUpUserWithPermissions([
        'lihat produk', 'tambah produk', 'update produk', 'hapus produk',
    ]);
});

it('can access product page with correct permission', function () {
    $user = $this->user;
    $this->actingAs($user);
    $response = $this->get(url('master/product'))->assertStatus(200);
    $response->assertViewIs('pages.master.product.index');
});

it('cannot access product page without correct permission', function () {
    $user = $this->user;
    $role = $user->roles->first();
    $role->revokePermissionTo('lihat produk');
    $this->actingAs($user);
    $this->get(url('master/product'))->assertStatus(403);
});

it('can create a product page with correct permission', function () {
    $user = $this->user;
    $this->actingAs($user);
    $product = Product::factory()->create();
    $this->post(url('master/product'), $product->toArray())->assertStatus(200);
});
