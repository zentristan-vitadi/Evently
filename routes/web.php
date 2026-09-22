<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicEventController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public event routes
Route::get('/', [PublicEventController::class, 'index'])->name('home');
Route::get('/events', [PublicEventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [PublicEventController::class, 'show'])->name('events.show');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Dashboard router according to role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Peserta routes
    Route::middleware('role:peserta')->group(function () {
        Route::post('/events/register', [RegistrationController::class, 'store'])->name('events.register');
        Route::get('/my-registrations', [RegistrationController::class, 'myRegistrations'])->name('my.registrations');
        Route::delete('/my-registrations/{registration}', [RegistrationController::class, 'cancel'])->name('my.registrations.cancel');
    });

    // Panitia & Admin routes (Event and Registration Management)
    Route::middleware('role:admin,panitia')->group(function () {
        Route::get('/manage/events', [EventController::class, 'index'])->name('manage.events.index');
        Route::get('/manage/events/create', [EventController::class, 'create'])->name('manage.events.create');
        Route::post('/manage/events', [EventController::class, 'store'])->name('manage.events.store');
        Route::get('/manage/events/{event}/edit', [EventController::class, 'edit'])->name('manage.events.edit');
        Route::put('/manage/events/{event}', [EventController::class, 'update'])->name('manage.events.update');
        Route::delete('/manage/events/{event}', [EventController::class, 'destroy'])->name('manage.events.destroy');

        Route::get('/manage/registrations/{event?}', [RegistrationController::class, 'manage'])->name('manage.registrations.index');
        Route::patch('/manage/registrations/{registration}/status', [RegistrationController::class, 'updateStatus'])->name('manage.registrations.update-status');
    });

    // Admin only routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('users', UserController::class)->except(['show']);
    });
});

require __DIR__.'/auth.php';
