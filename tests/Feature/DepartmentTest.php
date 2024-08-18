<?php


use App\Models\Department;

beforeEach(function () {
    $this->user = setUpUserWithPermissions([
        'lihat department', 'tambah department', 'update department', 'hapus department'
    ]);
});


it('can access department page with correct permission', function () {
    $user = $this->user;
    $this->actingAs($user);
    $this->get(url('/master/department'))
        ->assertViewIs('pages.master.department.index')
        ->assertStatus(200);
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
    $department = Department::factory()->make()->toArray();
    $departmentUpdate = [
        'name' => $department['name'],
    ];

    $this->post('/master/department/'.$department->id, $departmentUpdate)->assertStatus(200);
});
