<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Siswa\{DashboardController, JadwalController, AbsensiController, MateriBelajarController, NilaiController, RekomendasiController, TugasController};

Route::prefix('siswa')
    ->name('siswa.')
    ->middleware(['auth', 'verified', 'role:siswa'])
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::prefix('materi-belajar')
            ->name('materi.')
            ->controller(MateriBelajarController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index'); 
                Route::get('/{id}', 'show')->name('show'); 
            });

        Route::prefix('tugas')
            ->name('tugas.')
            ->controller(TugasController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{id}', 'show')->name('show');
                Route::post('/{id}/submit', 'submit')->name('submit');
            });

        Route::prefix('absensi')
            ->name('absensi.')
            ->controller(AbsensiController::class)
            ->group(function() {
                Route::get('/', 'index')->name('index');
            });

        Route::prefix('nilai')
            ->name('nilai.')
            ->controller(NilaiController::class)
            ->group(function() {
                Route::get('/', 'index')->name('index');
            });

        // Route::prefix('jadwal')
        //     ->name('jadwal.')
        //     ->controller(JadwalController::class)
        //     ->group(function () {
        //         Route::get('/', 'index')->name('index');
        //         Route::get('/data', 'get')->name('data');
        //         Route::get('/{id}', 'show')->name('show');
        //     });

        // Route::prefix('absensi')
        //     ->name('absensi.')
        //     ->controller(AbsensiController::class)
        //     ->group(function() {
        //         Route::get('/', 'index')->name('index');
        //         Route::get('/data', 'get')->name('data');
        //     });

        // Route::prefix('nilai')
        //     ->name('nilai.')
        //     ->controller(NilaiController::class)
        //     ->group(function() {
        //         Route::get('/', 'index')->name('index');
        //         Route::get('/data', 'get')->name('data');
        //     });

        // Route::prefix('rekomendasi')
            // ->name('rekomendasi.')
            // ->controller(RekomendasiController::class)
            // ->group(function() {
            //     Route::get('/', 'index')->name('index');
            //     Route::get('/data', 'get')->name('data');
            //     Route::get('/{id}', 'show')->name('show');
            //     Route::put('/{id}/status', 'updateStatus')->name('update-status');
            // });
    });
