<?php

use App\Models\User;

test('unauthenticated user is redirected to login when accessing protected routes', function () {
    $this->get('/dashboard')->assertRedirect('/login');
    $this->get('/manage/events')->assertRedirect('/login');
    $this->get('/admin/categories')->assertRedirect('/login');
    $this->get('/admin/users')->assertRedirect('/login');
    $this->get('/my-registrations')->assertRedirect('/login');
});

test('peserta cannot access admin or panitia management routes', function () {
    $peserta = User::factory()->peserta()->create();

    $this->actingAs($peserta)->get('/admin/categories')->assertForbidden();
    $this->actingAs($peserta)->get('/admin/users')->assertForbidden();
    $this->actingAs($peserta)->get('/manage/events')->assertForbidden();
});

test('panitia cannot access admin-only routes', function () {
    $panitia = User::factory()->panitia()->create();

    $this->actingAs($panitia)->get('/admin/categories')->assertForbidden();
    $this->actingAs($panitia)->get('/admin/users')->assertForbidden();
});

test('panitia can access event management routes', function () {
    $panitia = User::factory()->panitia()->create();

    $this->actingAs($panitia)->get('/manage/events')->assertOk();
    $this->actingAs($panitia)->get('/manage/registrations')->assertOk();
});

test('admin can access all management routes', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->get('/admin/categories')->assertOk();
    $this->actingAs($admin)->get('/admin/users')->assertOk();
    $this->actingAs($admin)->get('/manage/events')->assertOk();
    $this->actingAs($admin)->get('/manage/registrations')->assertOk();
});
