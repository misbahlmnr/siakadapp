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
        Route::get('siswa/get', [SiswaController::class, 'get'])->name('siswa.get');
        Route::resource('siswa', SiswaController::class)->names('siswa');
    });
});


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
