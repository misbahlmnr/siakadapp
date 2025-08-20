<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\EvaluasiPembelajaran;
use App\Models\JadwalPelajaran;
use App\Models\MateriPelajaran;
use App\Models\RekomendasiMateri;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $guruId = $user->guruProfile->id;

        // Hitung jumlah kelas unik yang diajar guru ini
        $totalKelas = JadwalPelajaran::where('guru_id', $guruId)
            ->distinct('kelas_id')
            ->count('kelas_id');

        // Hitung jumlah mapel unik
        $totalMatpel = JadwalPelajaran::where('guru_id', $guruId)
            ->distinct('matpel_id')
            ->count('matpel_id');

        // Total materi yang sudah diupload oleh guru
        $totalMateri = MateriPelajaran::where('guru_id', $guruId)->count();

        // Total evaluasi (tugas/kuis/ujian) yang dibuat guru
        $totalEvaluasi = EvaluasiPembelajaran::where('guru_id', $guruId)->count();

        // Ambil 5 jadwal terdekat (hari ini ke depan)
        $upcomingSchedule = JadwalPelajaran::with(['kelas', 'mataPelajaran'])
            ->where('guru_id', $guruId)
            ->orderByRaw("
                FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'),
                jam_mulai ASC
            ")
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'hari' => $item->hari,
                    'jam_mulai' => substr($item->jam_mulai, 0, 5),
                    'jam_selesai' => substr($item->jam_selesai, 0, 5),
                    'nama_kelas' => $item->kelas->nama_kelas,
                    'nama_mapel' => $item->mataPelajaran->nama_mapel,
                ];
            });

        // Progress adaptive learning (jumlah rekomendasi berdasarkan status)
        $adaptiveProgress = [
            'belum_dibaca' => RekomendasiMateri::whereHas('evaluasiPembelajaran', function ($q) use ($guruId) {
                    $q->where('guru_id', $guruId);
                })->where('status', 'belum_dibaca')->count(),
            'dibaca' => RekomendasiMateri::whereHas('evaluasiPembelajaran', function ($q) use ($guruId) {
                    $q->where('guru_id', $guruId);
                })->where('status', 'dibaca')->count(),
            'selesai' => RekomendasiMateri::whereHas('evaluasiPembelajaran', function ($q) use ($guruId) {
                    $q->where('guru_id', $guruId);
                })->where('status', 'selesai')->count(),
        ];

        return Inertia::render('guru/Dashboard', [
            'totalKelas' => $totalKelas,
            'totalMatpel' => $totalMatpel,
            'totalMateri' => $totalMateri,
            'totalEvaluasi' => $totalEvaluasi,
            'upcomingSchedule' => $upcomingSchedule,
            'adaptiveProgress' => $adaptiveProgress,
        ]);
    }
}
