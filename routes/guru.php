<?php

use App\Http\Controllers\Admin\{JadwalPelajaranController, KelasController, MataPelajaranController, UserController};
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::prefix('guru')
    ->name('guru.')
    ->middleware(['auth', 'verified', 'role:guru'])
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });

