<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\EvaluasiPembelajaran;
use App\Models\GuruMataPelajaran;
use App\Models\JadwalPelajaran;
use App\Models\MateriPelajaran;
use App\Models\RekomendasiMateri;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $guruId = $user->guruProfile->id;

        // Hitung jumlah kelas unik yang diajar guru ini
        $totalKelas = JadwalPelajaran::whereHas('guruMatpel', function ($q) use ($guruId) {
                $q->where('guru_id', $guruId);
            })
            ->distinct('kelas_id')
            ->count('kelas_id');

        // Hitung jumlah mapel unik yg diampu guru
        $totalMatpel = GuruMataPelajaran::where('guru_id', $guruId)
            ->distinct('matpel_id')
            ->count('matpel_id');

        // Total materi yang sudah diupload oleh guru
        $totalMateri = MateriPelajaran::whereHas('guruMatpel', function ($q) use ($guruId) {
                $q->where('guru_id', $guruId);
            })->count();

        // Total evaluasi (tugas/kuis/ujian) yang dibuat guru
        $totalEvaluasi = EvaluasiPembelajaran::whereHas('guruMatpel', function ($q) use ($guruId) {
                $q->where('guru_id', $guruId);
            })->count();

        // Ambil jadwal mengajar HARI INI
        $hariIni = ucfirst(Carbon::now()->locale('id')->dayName); 
        // pastikan format di DB sama: "Senin", "Selasa", dst.

        // Ambil semua jadwal mengajar guru
        $jadwalMengajarList = JadwalPelajaran::with(['kelas', 'guruMatpel.mataPelajaran'])
            ->whereHas('guruMatpel', function ($q) use ($guruId) {
                $q->where('guru_id', $guruId);
            })
            ->get()
            ->map(fn ($jm) => [
                'id' => $jm->id,
                'nama_jadwal' => $jm->guruMatpel->mataPelajaran->nama_mapel 
                                . ' | ' . $jm->kelas->nama_kelas 
                                . ' | ' . $jm->hari 
                                . ' | ' . substr($jm->jam_mulai, 0, 5) 
                                . ' - ' . substr($jm->jam_selesai, 0, 5),
            ]);

        // Ambil jadwal hari ini
        $todaySchedule = JadwalPelajaran::with(['kelas', 'guruMatpel.mataPelajaran'])
            ->whereHas('guruMatpel', function ($q) use ($guruId) {
                $q->where('guru_id', $guruId);
            })
            ->where('hari', $hariIni)
            ->orderBy('jam_mulai', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'hari' => $item->hari,
                    'jam_mulai' => substr($item->jam_mulai, 0, 5),
                    'jam_selesai' => substr($item->jam_selesai, 0, 5),
                    'nama_kelas' => $item->kelas->nama_kelas,
                    'nama_mapel' => $item->guruMatpel->mataPelajaran->nama_mapel,
                ];
            });

        // Progress adaptive learning (jumlah rekomendasi berdasarkan status)
        $adaptiveProgress = [
            'belum_dibaca' => RekomendasiMateri::whereHas('evaluasiPembelajaran.guruMatpel', function ($q) use ($guruId) {
                    $q->where('guru_id', $guruId);
                })->where('status', 'belum_dibaca')->count(),
            'dibaca' => RekomendasiMateri::whereHas('evaluasiPembelajaran.guruMatpel', function ($q) use ($guruId) {
                    $q->where('guru_id', $guruId);
                })->where('status', 'dibaca')->count(),
            'selesai' => RekomendasiMateri::whereHas('evaluasiPembelajaran.guruMatpel', function ($q) use ($guruId) {
                    $q->where('guru_id', $guruId);
                })->where('status', 'selesai')->count(),
        ];

        return Inertia::render('guru/Dashboard', [
            'totalKelas' => $totalKelas,
            'totalMatpel' => $totalMatpel,
            'totalMateri' => $totalMateri,
            'totalEvaluasi' => $totalEvaluasi,
            'todaySchedule' => $todaySchedule,
            'adaptiveProgress' => $adaptiveProgress,
            'jadwalMengajarList' => $jadwalMengajarList
        ]);
    }
}
