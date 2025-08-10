<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\JadwalPelajaran;
use App\Models\MateriPembelajaran;
use App\Models\NilaiSiswa;
use App\Models\SiswaProfile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $guruId = Auth::user()->guruProfile->id;
        $hariIni = Carbon::now()->locale('id')->isoFormat('dddd');

        $jadwalHariIni = JadwalPelajaran::where('guru_id', $guruId)
            ->where('hari', $hariIni)
            ->with('mataPelajaran', 'kelas')
            ->get()
            ->map(function ($jadwal) {
                return [
                    'id' => $jadwal->id,
                    'mata_pelajaran' => $jadwal->mataPelajaran->nama_mapel,
                    'kelas' => $jadwal->kelas->nama_kelas,
                    'jam_mulai' => $jadwal->jam_mulai,
                    'jam_selesai' => $jadwal->jam_selesai,
                ];
            });

        $statistik = [
            'jumlahSiswa' => SiswaProfile::count(),
            'materiMingguIni' => MateriPembelajaran::where('guru_id', $guruId)
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
            'nilaiDiinput' => NilaiSiswa::where('guru_id', $guruId)->count(),
        ];

        return Inertia::render('guru/Dashboard', [
            'userRole' => 'guru',
            'guruId' => $guruId,
            'jadwalHariIni' => $jadwalHariIni,
            'statistik' => $statistik
        ]);
    }
}
