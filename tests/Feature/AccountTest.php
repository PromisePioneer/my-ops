<?php

use App\Models\Account;


beforeEach(function () {
    $this->user = setUpUserWithPermissions(['lihat akun', 'tambah akun', 'update akun', 'hapus akun']);
});

it('can access account page with correct permission', function () {
    $this->actingAs($this->user);
    $response = $this->get(url('/account-master/account'))->assertStatus(200);
    $response->assertViewIs('pages.account-master.account.index');
});

it('cannot access index page without permission', function () {
    $user = $this->user;
    $role = $user->roles->first();
    $role->revokePermissionTo('lihat akun');
    $this->actingAs($user);
    $this->get(url('/account-master/account'))->assertStatus(403);
});

it('can store account with correct permission', function () {
    $account = Account::factory()->make()->toArray();
    $this->actingAs($this->user);
    $response = $this->post(url('account-master/account/'), $account);
    $response->assertStatus(200);
    $this->assertDatabaseHas('accounts', $account);
});


it('cannot store account without permission', function () {
    $user = $this->user;
    $role = $user->roles->first();
    $role->revokePermissionTo('tambah akun');
    $this->actingAs($user);
    $account = Account::factory()->make()->toArray();
    $response = $this->post(url('/account-master/account/'), $account);
    $response->assertStatus(403);
    $this->assertDatabaseMissing('accounts', $account);
});

it('can update account with correct permission', function () {
    $account = Account::factory()->create();
    $updateData = [
        'branch_id' => null,
        'name' => fake()->name,
        'code' => fake()->unique()->numberBetween(1, 10000000000),
        'debit_balance' => 0,
        'credit_balance' => 0,
        'balance' => 0,
    ];
    $this->actingAs($this->user);
    $this->post(url('account-master/account/update/'.$account->id), $updateData)->assertStatus(200);
    $this->assertModelExists(Account::find($account->id));
});


it('cannot update account without permission', function () {
    $user = $this->user;
    $role = $user->roles->first();
    $role->revokePermissionTo('update akun');
    $account = Account::factory()->create();
    $updateData = [
        'id' => $account->id,
        'branch_id' => null,
        'name' => fake()->name,
        'code' => fake()->unique()->numberBetween(1, 10000000000),
        'debit_balance' => 0,
        'credit_balance' => 0,
        'balance' => 0,
    ];
    $this->actingAs($user);
    $this->post(url('account-master/account/update/'.$account->id), $updateData)->assertStatus(403);
    $this->assertModelExists(Account::find($account->id));
});


it('can delete account with correct permission', function () {
    $account = Account::factory()->create();
    $this->actingAs($this->user);
    $this->delete(url('account-master/account/'.$account->id))->assertStatus(200);
    $this->assertNull(Account::find($account->id));
});

it('cannot delete account without permission', function () {
    $account = Account::factory()->create();
    $user = $this->user;
    $role = $user->roles->first();
    $role->revokePermissionTo('hapus akun');
    $this->actingAs($user);
    $response = $this->delete(url('account-master/account', $account));

    $response->assertStatus(403);
    $this->assertDatabaseHas('accounts', ['id' => $account->id]);
});