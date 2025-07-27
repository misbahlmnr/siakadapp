<?php

use App\Http\Controllers\Admin\{UserController};
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified', 'role:admin'])
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/manajemen-user/{role}', [UserController::class, 'index'])
            ->whereIn('role', ['admin', 'guru', 'siswa'])
            ->name('users.index');
        Route::get('/manajemen-user/{role}/data', [UserController::class, 'get'])->name('users.data');
    });
