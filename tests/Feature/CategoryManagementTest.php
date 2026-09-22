<?php

use App\Models\Category;
use App\Models\Event;
use App\Models\User;

test('admin can view categories list', function () {
    $admin = User::factory()->admin()->create();
    Category::factory()->count(3)->create();

    $response = $this->actingAs($admin)->get(route('admin.categories.index'));

    $response->assertOk();
    $response->assertViewHas('categories');
});

test('admin can create a category with validation', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->post(route('admin.categories.store'), [
        'name' => 'Seminar Teknologi',
    ]);

    $response->assertRedirect(route('admin.categories.index'));
    $this->assertDatabaseHas('categories', ['name' => 'Seminar Teknologi']);
});

test('category name must be unique', function () {
    $admin = User::factory()->admin()->create();
    Category::factory()->create(['name' => 'Lomba Desain']);

    $response = $this->actingAs($admin)->post(route('admin.categories.store'), [
        'name' => 'Lomba Desain',
    ]);

    $response->assertSessionHasErrors('name');
});

test('admin can update a category', function () {
    $admin = User::factory()->admin()->create();
    $category = Category::factory()->create(['name' => 'Nama Lama']);

    $response = $this->actingAs($admin)->put(route('admin.categories.update', $category), [
        'name' => 'Nama Baru',
    ]);

    $response->assertRedirect(route('admin.categories.index'));
    $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Nama Baru']);
});

test('admin can delete an unused category', function () {
    $admin = User::factory()->admin()->create();
    $category = Category::factory()->create();

    $response = $this->actingAs($admin)->delete(route('admin.categories.destroy', $category));

    $response->assertRedirect(route('admin.categories.index'));
    $this->assertDatabaseMissing('categories', ['id' => $category->id]);
});

test('admin cannot delete a category that is attached to events', function () {
    $admin = User::factory()->admin()->create();
    $category = Category::factory()->create();
    Event::factory()->create(['category_id' => $category->id]);

    $response = $this->actingAs($admin)->delete(route('admin.categories.destroy', $category));

    $response->assertSessionHas('error');
    $this->assertDatabaseHas('categories', ['id' => $category->id]);
});
