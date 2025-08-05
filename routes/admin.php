<?php

use App\Http\Controllers\Admin\{MataPelajaranController, UserController};
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

        Route::get('/manajemen-user/{role}/create', [UserController::class, 'create'])->name('users.create');
        Route::get('/manajemen-user/{role}/{id}/edit', [UserController::class, 'edit'])
            ->whereNumber('id')
            ->name('users.edit');

        Route::get('/manajemen-user/{role}/{id}', [UserController::class, 'show'])
            ->whereNumber('id')
            ->name('users.show');

        Route::post('/manajemen-user/{role}', [UserController::class, 'store'])->name('users.store');

        Route::put('/manajemen-user/{role}/{id}', [UserController::class, 'update'])->name('users.update');

        Route::delete('/manajemen-user/{role}/{id}', [UserController::class, 'destroy'])
            ->whereNumber('id')
            ->name('users.destroy');

        Route::prefix('mata-pelajaran')
            ->name('mata-pelajaran.')
            ->controller(MataPelajaranController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/data', 'get')->name('data');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{id}/edit', 'edit')->name('edit');
                Route::put('/{id}', 'update')->name('update');
            });
    });

