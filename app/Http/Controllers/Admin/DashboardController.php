<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\RekomendasiMateri;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $autoCount = RekomendasiMateri::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $manualCount = RekomendasiMateri::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $statuses = ['belum_dibaca', 'dibaca', 'selesai'];

        $adaptiveProgress = [];
        foreach ($statuses as $status) {
            $adaptiveProgress[$status] = ($autoCount[$status] ?? 0) + ($manualCount[$status] ?? 0);
        }

        return Inertia::render('admin/Dashboard', [
            'totalMatpel' => MataPelajaran::count(),
            'totalSiswa' => User::where('role', 'siswa')->count(),
            'totalGuru' => User::where('role', 'guru')->count(),
            'totalKelas' => Kelas::count(),
            'adaptiveProgress' => $adaptiveProgress
        ]);
    }
}
