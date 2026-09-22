<?php

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;

test('peserta can register for an upcoming event', function () {
    $peserta = User::factory()->peserta()->create();
    $event = Event::factory()->upcoming()->create(['capacity' => 10]);

    $response = $this->actingAs($peserta)->post(route('events.register'), [
        'event_id' => $event->id,
    ]);

    $response->assertRedirect(route('my.registrations'));
    $this->assertDatabaseHas('registrations', [
        'user_id' => $peserta->id,
        'event_id' => $event->id,
        'status' => 'pending',
    ]);
});

test('peserta cannot register twice for the same event', function () {
    $peserta = User::factory()->peserta()->create();
    $event = Event::factory()->upcoming()->create(['capacity' => 10]);

    Registration::factory()->create([
        'user_id' => $peserta->id,
        'event_id' => $event->id,
        'status' => 'pending',
    ]);

    $response = $this->actingAs($peserta)->post(route('events.register'), [
        'event_id' => $event->id,
    ]);

    $response->assertSessionHasErrors('event_id');
});

test('peserta cannot register when event capacity is full', function () {
    $peserta = User::factory()->peserta()->create();
    $event = Event::factory()->upcoming()->create(['capacity' => 2]);

    Registration::factory()->approved()->create(['event_id' => $event->id]);
    Registration::factory()->approved()->create(['event_id' => $event->id]);

    $response = $this->actingAs($peserta)->post(route('events.register'), [
        'event_id' => $event->id,
    ]);

    $response->assertSessionHasErrors('event_id');
});

test('peserta cannot register for draft or completed events', function () {
    $peserta = User::factory()->peserta()->create();
    $draftEvent = Event::factory()->draft()->create();
    $completedEvent = Event::factory()->completed()->create();

    $this->actingAs($peserta)->post(route('events.register'), ['event_id' => $draftEvent->id])
        ->assertSessionHasErrors('event_id');

    $this->actingAs($peserta)->post(route('events.register'), ['event_id' => $completedEvent->id])
        ->assertSessionHasErrors('event_id');
});

test('peserta can cancel pending registration', function () {
    $peserta = User::factory()->peserta()->create();
    $reg = Registration::factory()->pending()->create(['user_id' => $peserta->id]);

    $response = $this->actingAs($peserta)->delete(route('my.registrations.cancel', $reg));

    $response->assertRedirect(route('my.registrations'));
    $this->assertDatabaseMissing('registrations', ['id' => $reg->id]);
});

test('panitia can approve participant registration', function () {
    $panitia = User::factory()->panitia()->create();
    $event = Event::factory()->upcoming()->create([
        'organizer_id' => $panitia->id,
        'capacity' => 10,
    ]);
    $registration = Registration::factory()->pending()->create(['event_id' => $event->id]);

    $response = $this->actingAs($panitia)->patch(route('manage.registrations.update-status', $registration), [
        'status' => 'approved',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('registrations', [
        'id' => $registration->id,
        'status' => 'approved',
    ]);
});

test('panitia can reject participant registration', function () {
    $panitia = User::factory()->panitia()->create();
    $event = Event::factory()->upcoming()->create(['organizer_id' => $panitia->id]);
    $registration = Registration::factory()->pending()->create(['event_id' => $event->id]);

    $response = $this->actingAs($panitia)->patch(route('manage.registrations.update-status', $registration), [
        'status' => 'rejected',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('registrations', [
        'id' => $registration->id,
        'status' => 'rejected',
    ]);
});

test('panitia cannot approve registration when capacity is already exceeded', function () {
    $panitia = User::factory()->panitia()->create();
    $event = Event::factory()->upcoming()->create([
        'organizer_id' => $panitia->id,
        'capacity' => 1,
    ]);

    // Already 1 approved registration
    Registration::factory()->approved()->create(['event_id' => $event->id]);
    $pendingReg = Registration::factory()->pending()->create(['event_id' => $event->id]);

    $response = $this->actingAs($panitia)->patch(route('manage.registrations.update-status', $pendingReg), [
        'status' => 'approved',
    ]);

    $response->assertSessionHas('error');
    $this->assertDatabaseHas('registrations', [
        'id' => $pendingReg->id,
        'status' => 'pending',
    ]);
});
