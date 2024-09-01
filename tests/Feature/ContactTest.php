<?php

use App\Models\Contact;

beforeEach(function () {
    $this->user = setUpUserWithPermissions(['lihat contact', 'tambah contact', 'update contact', 'hapus contact']);
});

it('can access the contact page with correct permission', function () {
    $this->actingAs($this->user);
    $response = $this->get(url('/master/contact'))->assertStatus(200);
    $response->assertViewIs('pages.master.contact.index');
});

it('cannot access the contact page without correct permission', function () {
    $user = $this->user;
    $role = $user->roles->first();
    $role->revokePermissionTo('lihat contact');
    $this->actingAs($user);
    $this->get(url('/master/contact'))->assertStatus(403);
});

it('can store the contact page with correct permission', function () {
    $contact = Contact::factory()->create();
    $this->actingAs($this->user);
    $this->post(url('/master/contact'), $contact->toArray())->assertStatus(200);
});

it('cannot store the contact page without correct permission', function () {
    $contact = Contact::factory()->create();
    $user = $this->user;
    $role = $user->roles->first();
    $role->revokePermissionTo('tambah contact');
    $this->actingAs($user);
    $this->post(url('/master/contact'), $contact->toArray())->assertStatus(403);
});

it('can update the contact page with correct permission', function () {
    $user = $this->user;
    $contact = Contact::factory()->create();
    $updatedContact = [
        'full_name' => fake()->name,
        'company_name' => fake()->company,
        'email' => fake()->unique(true)->safeEmail,
        'phone_number' => fake()->phoneNumber,
        'identity_type' => 'ktp',
        'identity_number' => fake()->unique(true)->randomNumber(),
        'fax' => fake()->unique(true)->randomNumber(),
        'npwp' => fake()->unique(true)->randomNumber(),
        'complete_address' => fake()->unique(true)->address(),
        'other_info' => fake()->word(),
    ];
    $this->actingAs($user);
    $this->post(url('/master/contact/update', $contact->id), $updatedContact)->assertStatus(200);
});
