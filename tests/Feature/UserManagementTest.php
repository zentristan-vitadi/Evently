<?php

use App\Models\User;

test('admin can view user list', function () {
    $admin = User::factory()->admin()->create();
    User::factory()->count(5)->create();

    $response = $this->actingAs($admin)->get(route('admin.users.index'));

    $response->assertOk();
    $response->assertViewHas('users');
});

test('admin can create a user with role', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->post(route('admin.users.store'), [
        'name' => 'Panitia Baru',
        'email' => 'panitiabaru@example.com',
        'role' => 'panitia',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertRedirect(route('admin.users.index'));
    $this->assertDatabaseHas('users', [
        'name' => 'Panitia Baru',
        'email' => 'panitiabaru@example.com',
        'role' => 'panitia',
    ]);
});

test('admin can update a user and change role', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->peserta()->create(['name' => 'Siswa Test']);

    $response = $this->actingAs($admin)->put(route('admin.users.update', $user), [
        'name' => 'Siswa Diangkat Panitia',
        'email' => $user->email,
        'role' => 'panitia',
    ]);

    $response->assertRedirect(route('admin.users.index'));
    $this->assertDatabaseHas('users', [
        'id' => $user->id,
        'name' => 'Siswa Diangkat Panitia',
        'role' => 'panitia',
    ]);
});

test('admin cannot delete their own account', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $admin));

    $response->assertSessionHas('error');
    $this->assertDatabaseHas('users', ['id' => $admin->id]);
});

test('admin can delete other user account', function () {
    $admin = User::factory()->admin()->create();
    $user = User::factory()->peserta()->create();

    $response = $this->actingAs($admin)->delete(route('admin.users.destroy', $user));

    $response->assertRedirect(route('admin.users.index'));
    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});
