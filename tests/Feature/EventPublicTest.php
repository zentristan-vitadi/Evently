<?php

use App\Models\Category;
use App\Models\Event;

test('guests can view public events list', function () {
    $category = Category::factory()->create(['name' => 'Seminar']);
    $event = Event::factory()->upcoming()->create([
        'title' => 'Seminar Teknologi AI',
        'category_id' => $category->id,
    ]);

    $response = $this->get(route('events.index'));

    $response->assertOk();
    $response->assertSee('Seminar Teknologi AI');
    $response->assertSee('Seminar');
});

test('public events can be filtered by category', function () {
    $cat1 = Category::factory()->create(['name' => 'Kategori Satu']);
    $cat2 = Category::factory()->create(['name' => 'Kategori Dua']);

    $event1 = Event::factory()->upcoming()->create(['title' => 'Event Pertama', 'category_id' => $cat1->id]);
    $event2 = Event::factory()->upcoming()->create(['title' => 'Event Kedua', 'category_id' => $cat2->id]);

    $response = $this->get(route('events.index', ['category_id' => $cat1->id]));

    $response->assertOk();
    $response->assertSee('Event Pertama');
    $response->assertDontSee('Event Kedua');
});

test('public events can be searched by title or location', function () {
    $event1 = Event::factory()->upcoming()->create(['title' => 'Pelatihan Robotik', 'location' => 'Lab Fisika']);
    $event2 = Event::factory()->upcoming()->create(['title' => 'Lomba Melukis', 'location' => 'Aula Serbaguna']);

    $response = $this->get(route('events.index', ['search' => 'Robotik']));

    $response->assertOk();
    $response->assertSee('Pelatihan Robotik');
    $response->assertDontSee('Lomba Melukis');
});

test('guest can view event detail page', function () {
    $event = Event::factory()->upcoming()->create([
        'title' => 'Detail Event Spesial',
        'description' => 'Ini adalah penjelasan detail event spesial.',
        'location' => 'Ruang Teater',
    ]);

    $response = $this->get(route('events.show', $event));

    $response->assertOk();
    $response->assertSee('Detail Event Spesial');
    $response->assertSee('Ini adalah penjelasan detail event spesial.');
    $response->assertSee('Ruang Teater');
});
