<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\StationController as ApiStationController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\AlertController;

// Routes publiques
Route::get('/', fn() => view('welcome'))->name('home');
Route::get('/map', [MapController::class, 'index'])->name('map');
Route::get('/api/stations', [ApiStationController::class, 'index'])->name('api.stations');
Route::get('/stations/{station}', [StationController::class, 'show'])->name('stations.show');

// Routes utilisateurs connectés
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
    Route::get('/favorites', [FavoriteController::class, 'index'])
        ->name('favorites.index');
    Route::post('/favorites/{station}', [FavoriteController::class, 'store'])
        ->name('favorites.store');
    Route::delete('/favorites/{station}', [FavoriteController::class, 'destroy'])
        ->name('favorites.destroy');
    Route::post('/reviews/{station}', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');

    Route::get('/alerts', [AlertController::class, 'index'])->name('alerts.index');
    Route::post('/alerts', [AlertController::class, 'store'])->name('alerts.store');
    Route::patch('/alerts/{alert}/toggle', [AlertController::class, 'toggle'])->name('alerts.toggle');
    Route::delete('/alerts/{alert}', [AlertController::class, 'destroy'])->name('alerts.destroy');


});

// Routes admin — un seul groupe
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
    Route::resource('stations', StationController::class)->except(['show']);
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});



// Routes operator
Route::middleware(['auth', 'role:operator'])->prefix('operator')->name('operator.')->group(function () {
    Route::get('/dashboard', fn() => view('operator.dashboard'))->name('dashboard');
});

require __DIR__ . '/auth.php';
