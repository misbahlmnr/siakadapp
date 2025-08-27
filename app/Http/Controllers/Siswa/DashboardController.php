<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

use App\Models\SiswaProfile;
use App\Models\JadwalPelajaran;
use App\Models\EvaluasiPembelajaran;
use App\Models\PengumpulanTugas;
use App\Models\Absensi;
use App\Models\Nilai;
use App\Models\RekomendasiMateri;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil profil siswa
        $siswa = SiswaProfile::where('user_id', $user->id)->first();

        // --- 1. Jadwal Hari Ini
        $hariIni = strtolower(Carbon::now()->locale('id')->dayName); // contoh: senin, selasa
        $jadwalHariIni = JadwalPelajaran::with('mataPelajaran', 'guru')
            ->where('kelas_id', $siswa->kelas_id)
            ->where('hari', $hariIni)
            ->get()
            ->map(function($j) {
                return [
                    'id' => $j->id,
                    'nama_mapel' => $j->mataPelajaran->nama_mapel ?? '-',
                    'guru' => $j->guru->user->name ?? '-',
                    'jam_mulai' => $j->jam_mulai,
                    'jam_selesai' => $j->jam_selesai,
                ];
            });

        // --- 2. Tugas Belum Dikumpulkan
        $tugasBelum = EvaluasiPembelajaran::with('jadwal.mataPelajaran')
            ->whereHas('jadwal', function ($q) use ($siswa) {
                $q->where('kelas_id', $siswa->kelas_id);
            })
            ->whereDate('waktu_selesai', '>=', Carbon::today())
            ->get()
            ->filter(function ($t) use ($siswa) {
                return !PengumpulanTugas::where('evaluasi_id', $t->id)
                        ->where('siswa_id', $siswa->id)
                        ->exists();
            })
            ->map(function ($t) {
                return [
                    'id' => $t->id,
                    'judul' => $t->judul,
                    'nama_mapel' => $t->jadwal->mataPelajaran->nama_mapel ?? '-',
                    'deadline' => $t->waktu_selesai,
                ];
            })
            ->values();

        // --- 3. Absensi Siswa
        $absensi = Absensi::where('siswa_id', $siswa->id)
            ->latest('tanggal')
            ->take(10)
            ->get();

        // --- 4. Nilai Terakhir
        $nilaiTerakhir = Nilai::with('evaluasiPembelajaran.jadwal.mataPelajaran')
            ->where('siswa_id', $siswa->id)
            ->latest('tanggal_dinilai')
            ->take(5)
            ->get()
            ->map(function($n) {
                return [
                    'mapel' => $n->evaluasiPembelajaran->jadwal->mataPelajaran->nama_mapel ?? '-',
                    'judul' => $n->evaluasiPembelajaran->judul ?? '-',
                    'nilai' => $n->nilai,
                    'status' => $n->status,
                ];
            });

        // --- 5. Rekomendasi Materi
        $rekomendasi = RekomendasiMateri::with('materi')
            ->where('siswa_id', $siswa->id)
            ->latest('created_at')
            ->take(5)
            ->get()
            ->map(function ($r) {
                return [
                    'id' => $r->materi->id ?? null,
                    'judul_materi' => $r->materi->judul_materi ?? '-',
                    'status' => $r->status,
                ];
            });

        return Inertia::render('siswa/Dashboard', [
            'jadwalHariIni' => $jadwalHariIni,
            'tugasBelum'    => $tugasBelum,
            'absensi'       => $absensi,
            'nilaiTerakhir' => $nilaiTerakhir,
            'rekomendasi'   => $rekomendasi,
        ]);
    }
}
