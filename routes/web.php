<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/* ====================================================================== */
/* Public site                                                            */
/* ====================================================================== */

Route::get('/', [HomeController::class, 'index'])->name('home');

// Public submission form + live preview flow
Route::get('/create', [EventController::class, 'create'])->name('events.create');
Route::post('/events', [EventController::class, 'store'])->name('events.store');
Route::get('/slug-check', [EventController::class, 'checkSlug'])->name('events.slug-check');

// "Preview Ready" screen (temporary signed link only)
Route::get('/preview-ready/{event}', [EventController::class, 'previewReady'])
    ->middleware('signed')
    ->name('events.preview-ready');

// Public brochure: negahm.ir/B/{slug}  (lowercase alias included for safety)
Route::get('/B/{slug}', [EventController::class, 'show'])->name('events.show');
Route::get('/b/{slug}', [EventController::class, 'show']);

// RSVP submissions on active RSVP events
Route::post('/B/{slug}/rsvp', [EventController::class, 'storeRsvp'])->name('events.rsvp.store');

/* ====================================================================== */
/* Admin / concierge review panel                                         */
/* ====================================================================== */

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login.submit');

    Route::middleware('admin.auth')->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('events', [AdminEventController::class, 'index'])->name('events.index');
        Route::get('events/{event}', [AdminEventController::class, 'show'])->name('events.show');
        Route::get('events/{event}/edit', [AdminEventController::class, 'edit'])->name('events.edit');
        Route::put('events/{event}', [AdminEventController::class, 'update'])->name('events.update');
        Route::post('events/{event}/status', [AdminEventController::class, 'updateStatus'])->name('events.status');
        Route::delete('events/{event}', [AdminEventController::class, 'destroy'])->name('events.destroy');
    });
});
