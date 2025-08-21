<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\JadwalPelajaran;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class JadwalMengajarController extends Controller
{
    public function get()
    {
        $jadwal = JadwalPelajaran::with(['mataPelajaran', 'kelas'])
            ->where('guru_id', Auth::user()->guruProfile->id);

        return DataTables::of($jadwal)
            ->addIndexColumn()
            ->addColumn('mata_pelajaran', fn($j) => $j->mataPelajaran->nama_mapel)
            ->addColumn('kelas', fn($j) => $j->kelas->nama_kelas)
            ->addColumn('hari', fn($j) => $j->hari)
            ->addColumn('jam', fn($j) => substr($j->jam_mulai, 0, 5) . ' - ' . substr($j->jam_selesai, 0, 5))
            ->make(true);
    }

    public function index()
    {
        return Inertia::render('guru/jadwal-mengajar/Index');
    }

    public function show(string $id)
    {
        $jadwal = JadwalPelajaran::with(['mataPelajaran', 'kelas'])->findOrFail($id);

        $materi = $jadwal->materi()->orderBy('pertemuan_ke')->get();
        $tugas = $jadwal->evaluasiPembelajaran()->orderBy('tanggal_mulai')->get();

        return Inertia::render('guru/jadwal-mengajar/View', [
            'jadwal' => $jadwal,
            'materi' => $materi,
            'tugas' => $tugas,
        ]);
    }

}
