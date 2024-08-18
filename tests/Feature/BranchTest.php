<?php

use App\Models\Branch;

beforeEach(function () {
    $this->user = setUpUserWithPermissions(['lihat cabang', 'tambah cabang', 'update cabang', 'hapus cabang']);
});

it('can access index page with correct permission', function () {
    $this->actingAs($this->user);
    $response = $this->get(url('master/branch/'))->assertStatus(200);
    $response->assertViewIs('pages.master.branch.index');
});

it('can cannot access index page without permission', function () {
    $user = $this->user;
    $role = $user->roles->first();
    $role->revokePermissionTo('lihat cabang');
    $this->actingAs($user);
    $this->get(url('master/branch/'))->assertStatus(403);
});


it('can store branch with correct permission', function () {
    $user = $this->user;
    $this->actingAs($user);
    $branch = Branch::factory()->make()->toArray();
    $this->post(url('master/branch/'), $branch)->assertStatus(200);
    $this->assertDatabaseHas('branches', $branch);
});

it('cannot store branch without permission', function () {
    $user = $this->user;
    $role = $user->roles->first();
    $role->revokePermissionTo('tambah cabang');
    $this->actingAs($user);
    $branch = Branch::factory()->make()->toArray();
    $this->post(url('master/branch/'), $branch)->assertStatus(403);
    $this->assertDatabaseMissing('branches', $branch);
});

it('can update branch with correct permission', function () {
    $user = $this->user;
    $branch = Branch::factory()->create();
    $updatedBranch = [
        'code' => fake()->unique()->randomNumber(),
        'name' => $branch->name,
    ];

    $this->actingAs($user);
    $this->post(url('master/branch/update', $branch->id), $updatedBranch)->assertStatus(200);
});

it('cannot update branch without correct permission', function () {
    $user = $this->user;
    $role = $user->roles->first();
    $role->revokePermissionTo('update cabang');
    $branch = Branch::factory()->create();
    $updatedBranch = [
        'code' => fake()->unique()->randomNumber(),
    ];

    $this->actingAs($user);
    $this->post(url('master/branch/update/'.$branch->id), $updatedBranch)->assertStatus(302);
});

it('can delete branch with correct permission', function () {
    $branch = Branch::factory()->create()->toArray();
    $branchesArr = [
        'id' => $branch['id'],
    ];

    $this->actingAs($this->user);
    $this->post(url('master/branch/destroy'), $branchesArr)->assertStatus(200);
});

