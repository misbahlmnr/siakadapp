<?php

use App\Http\Controllers\Akademik\SiswaController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::prefix('akademik')->name('akademik.')->group(function () {
        Route::prefix('siswa')->name('siswa.')->group(function () {
            Route::get('/', [SiswaController::class, 'index'])->name('index');
            Route::get('get', [SiswaController::class, 'get'])->name('get');
            Route::get('create', [SiswaController::class, 'create'])->name('create');
            Route::post('store', [SiswaController::class, 'store'])->name('store');
        });
    });
});


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
