<?php

use App\Models\Department;

beforeEach(function () {
    $this->user = setUpUserWithPermissions([
        'lihat department', 'tambah department', 'update department', 'hapus department',
    ]);
});

it('can access department page with correct permission', function () {
    $user = $this->user;
    $this->actingAs($user);
    $response = $this->get(url('/master/department'))->assertStatus(200);
    $response->assertViewIs('pages.master.department.index');
});

it('cannot access department without permission', function () {
    $user = $this->user;
    $role = $user->roles()->first();
    $role->revokePermissionTo('lihat department');
    $this->actingAs($user);
    $this->get('/master/department')->assertStatus(403);
});

it('can store department with correct permission', function () {
    $user = $this->user;
    $this->actingAs($user);
    $department = Department::factory()->make()->toArray();
    $this->post('/master/department', $department)->assertStatus(200);
});

it('cannot store department without permission', function () {
    $user = $this->user;
    $this->actingAs($user);
    $role = $this->user->roles()->first();
    $role->revokePermissionTo('tambah department');
    $department = Department::factory()->make()->toArray();
    $this->post('/master/department', $department)->assertStatus(403);
});

it('can update department with correct permission', function () {
    $user = $this->user;
    $this->actingAs($user);
    $department = Department::factory()->create([
        'id' => 300,
        'code' => 'test',
        'name' => 'test',
    ])->toArray();
    $departmentUpdate = [
        'name' => $department['name'],
        'code' => $department['code'],
    ];
    $this->post('/master/department/'.$department['id'], $departmentUpdate)->assertStatus(200);
});

it('cannot update department without permission', function () {
    $user = $this->user;
    $role = $this->user->roles()->first();
    $role->revokePermissionTo('update department');
    $this->actingAs($user);
    $department = Department::factory()->create([
        'id' => 300,
        'code' => 'test',
        'name' => 'test',
    ])->toArray();
    $departmentUpdate = [
        'name' => $department['name'],
        'code' => $department['code'],
    ];
    $this->post('/master/department/'.$department['id'], $departmentUpdate)->assertStatus(403);
});

it('can delete department with correct permission', function () {
    $user = $this->user;
    $this->actingAs($user);
    $department = Department::factory()->create([
        'id' => 300,
        'code' => 'test',
        'name' => 'test',
    ])->toArray();

    $this->delete(url('/master/department/'.$department['id']))->assertStatus(200);
});

it('cannot delete department without permission', function () {
    $user = $this->user;
    $role = $this->user->roles()->first();
    $role->revokePermissionTo('hapus department');
    $this->actingAs($user);
    $department = Department::factory()->create([
        'id' => 300,
        'code' => 'test',
        'name' => 'test',
    ])->toArray();

    $this->delete(url('/master/department/'.$department['id']))->assertStatus(403);
});
