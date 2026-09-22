<?php

use App\Models\Category;
use App\Models\Event;
use App\Models\Registration;
use App\Models\User;

test('admin dashboard renders with statistics', function () {
    $admin = User::factory()->admin()->create();
    Category::factory()->count(2)->create();
    Event::factory()->count(3)->create();

    $response = $this->actingAs($admin)->get(route('dashboard'));

    $response->assertOk();
    $response->assertSee('Dashboard Administrator');
    $response->assertViewHas('stats');
});

test('panitia dashboard renders with stats and events', function () {
    $panitia = User::factory()->panitia()->create();
    $event = Event::factory()->create(['organizer_id' => $panitia->id]);
    Registration::factory()->pending()->create(['event_id' => $event->id]);

    $response = $this->actingAs($panitia)->get(route('dashboard'));

    $response->assertOk();
    $response->assertSee('Dashboard Panitia Penyelenggara');
    $response->assertViewHas('stats');
    $response->assertViewHas('pendingApprovals');
});

test('peserta dashboard renders with user statistics', function () {
    $peserta = User::factory()->peserta()->create();
    Registration::factory()->approved()->create(['user_id' => $peserta->id]);

    $response = $this->actingAs($peserta)->get(route('dashboard'));

    $response->assertOk();
    $response->assertSee('Dashboard Peserta');
    $response->assertViewHas('stats');
});
