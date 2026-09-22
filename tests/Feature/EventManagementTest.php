<?php

use App\Models\Category;
use App\Models\Event;
use App\Models\User;

test('panitia can create an event', function () {
    $panitia = User::factory()->panitia()->create();
    $category = Category::factory()->create();

    $response = $this->actingAs($panitia)->post(route('manage.events.store'), [
        'title' => 'Workshop UI/UX Design',
        'category_id' => $category->id,
        'description' => 'Deskripsi workshop interaktif.',
        'location' => 'Lab Komputer 1',
        'start_date' => now()->addDays(5)->format('Y-m-d H:i:s'),
        'end_date' => now()->addDays(5)->addHours(4)->format('Y-m-d H:i:s'),
        'capacity' => 40,
        'status' => 'upcoming',
    ]);

    $response->assertRedirect(route('manage.events.index'));
    $this->assertDatabaseHas('events', [
        'title' => 'Workshop UI/UX Design',
        'organizer_id' => $panitia->id,
        'capacity' => 40,
        'status' => 'upcoming',
    ]);
});

test('admin can create an event and assign organizer', function () {
    $admin = User::factory()->admin()->create();
    $panitia = User::factory()->panitia()->create();
    $category = Category::factory()->create();

    $response = $this->actingAs($admin)->post(route('manage.events.store'), [
        'title' => 'Lomba Coding Nasional',
        'category_id' => $category->id,
        'organizer_id' => $panitia->id,
        'description' => 'Deskripsi lomba coding.',
        'location' => 'Auditorium',
        'start_date' => now()->addDays(10)->format('Y-m-d H:i:s'),
        'end_date' => now()->addDays(10)->addHours(6)->format('Y-m-d H:i:s'),
        'capacity' => 100,
        'status' => 'upcoming',
    ]);

    $response->assertRedirect(route('manage.events.index'));
    $this->assertDatabaseHas('events', [
        'title' => 'Lomba Coding Nasional',
        'organizer_id' => $panitia->id,
    ]);
});

test('panitia can update their own event', function () {
    $panitia = User::factory()->panitia()->create();
    $category = Category::factory()->create();
    $event = Event::factory()->create([
        'organizer_id' => $panitia->id,
        'category_id' => $category->id,
        'title' => 'Judul Lama',
    ]);

    $response = $this->actingAs($panitia)->put(route('manage.events.update', $event), [
        'title' => 'Judul Baru',
        'category_id' => $category->id,
        'description' => 'Deskripsi baru.',
        'location' => 'Lab Baru',
        'start_date' => now()->addDays(7)->format('Y-m-d H:i:s'),
        'end_date' => now()->addDays(7)->addHours(3)->format('Y-m-d H:i:s'),
        'capacity' => 50,
        'status' => 'ongoing',
    ]);

    $response->assertRedirect(route('manage.events.index'));
    $this->assertDatabaseHas('events', [
        'id' => $event->id,
        'title' => 'Judul Baru',
        'capacity' => 50,
        'status' => 'ongoing',
    ]);
});

test('panitia cannot edit another panitias event', function () {
    $panitia1 = User::factory()->panitia()->create();
    $panitia2 = User::factory()->panitia()->create();
    $event = Event::factory()->create(['organizer_id' => $panitia1->id]);

    $response = $this->actingAs($panitia2)->get(route('manage.events.edit', $event));
    $response->assertForbidden();

    $updateResponse = $this->actingAs($panitia2)->put(route('manage.events.update', $event), [
        'title' => 'Coba Ubah',
        'category_id' => $event->category_id,
        'description' => 'Deskripsi coba.',
        'location' => 'Lokasi',
        'start_date' => now()->addDays(2)->format('Y-m-d H:i:s'),
        'end_date' => now()->addDays(2)->addHours(2)->format('Y-m-d H:i:s'),
        'capacity' => 20,
        'status' => 'upcoming',
    ]);
    $updateResponse->assertForbidden();
});

test('panitia can delete their own event', function () {
    $panitia = User::factory()->panitia()->create();
    $event = Event::factory()->create(['organizer_id' => $panitia->id]);

    $response = $this->actingAs($panitia)->delete(route('manage.events.destroy', $event));

    $response->assertRedirect(route('manage.events.index'));
    $this->assertDatabaseMissing('events', ['id' => $event->id]);
});
