<?php

use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\EventResponseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ─── Public routes (no login required) ───────────────────────────────────────

// Home page: show all upcoming published events
Route::get('/', [HomeController::class, 'showUpcomingEvents'])->name('home');

// "Going" button: increment the going counter for a specific event
Route::post('/events/{event}/going', [EventResponseController::class, 'markAsGoing'])
    ->name('events.going');

// "Interested" button: increment the interested counter for a specific event
Route::post('/events/{event}/interested', [EventResponseController::class, 'markAsInterested'])
    ->name('events.interested');

// ─── Admin routes ─────────────────────────────────────────────────────────────

Route::get('/dashboard', function () {
    return redirect()->route('admin.events.index');
})->middleware(['auth', 'admin'])->name('dashboard');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.events.index');
    })->name('home');

    Route::resource('events', EventController::class)->whereNumber('event');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
