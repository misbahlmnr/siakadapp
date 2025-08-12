<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Guru\{DashboardController, JadwalMengajarController};

Route::prefix('guru')
    ->name('guru.')
    ->middleware(['auth', 'verified', 'role:guru'])
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::prefix('jadwal-mengajar')
            ->name('jadwal-mengajar.')
            ->controller(JadwalMengajarController::class)
            ->group(function () {
               Route::get('/', 'index')->name('index');
               Route::get('/data', 'get')->name('data');
               Route::get('/{id}', 'show')->name('show');
            });
    });

