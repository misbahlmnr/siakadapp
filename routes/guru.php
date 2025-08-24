<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Guru\{AbsensiController, DashboardController, EvaluasiPembelajaranController, JadwalMengajarController, MateriPelajaranController, TugasUjianController, NilaiController};

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

               // route materi pembelajaran
                Route::prefix('{jadwal_id}/materi')
                    ->name('materi.')
                    ->controller(MateriPelajaranController::class)
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::get('/data', 'get')->name('data');
                        Route::get('/create', 'create')->name('create');
                        Route::post('/', 'store')->name('store');
                        Route::get('/{materi_id}', 'show')->name('show');
                        Route::get('/{materi_id}/edit', 'edit')->name('edit');
                        Route::put('/{materi_id}', 'update')->name('update');
                        Route::delete('/{materi_id}', 'destroy')->name('destroy');
                    });

                Route::prefix('{jadwal_id}/evaluasi-pembelajaran')
                    ->name('evaluasi-pembelajaran.')
                    ->controller(EvaluasiPembelajaranController::class)
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::get('/data', 'get')->name('data');
                        Route::get('/create', 'create')->name('create');
                        Route::post('/', 'store')->name('store');
                        Route::get('/{evaluasi_id}', 'show')->name('show');
                        Route::get('/{evaluasi_id}/edit', 'edit')->name('edit');
                        Route::put('/{evaluasi_id}', 'update')->name('update');
                        Route::delete('/{evaluasi_id}', 'destroy')->name('destroy');
                    });
            });

        Route::prefix('nilai')
            ->name('nilai.')
            ->controller(NilaiController::class)
            ->group(function() {
                Route::get('/', 'index')->name('index');
                Route::get('/data', 'get')->name('data');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{nilai_id}', 'show')->name('show');
                Route::get('/{nilai_id}/edit', 'edit')->name('edit');
                Route::put('/{nilai_id}', 'update')->name('update');
                Route::delete('/{nilai_id}', 'destroy')->name('destroy');
            });

        Route::prefix('absensi')
            ->name('absensi.')
            ->controller(AbsensiController::class)
            ->group(function() {
                Route::get('/', 'index')->name('index');
                Route::get('/data', 'get')->name('data');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{id}', 'show')->name('show');
                Route::get('/{id}/edit', 'edit')->name('edit');
                Route::put('/{id}', 'update')->name('update');
                Route::delete('/{id}', 'destroy')->name('destroy');
            });
    });

