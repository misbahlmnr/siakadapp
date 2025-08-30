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
        $guruId = Auth::user()->guruProfile->id;

        $jadwal = JadwalPelajaran::with(['guruMatpel.mataPelajaran', 'kelas'])
            ->whereHas('guruMatpel', fn($q) => $q->where('guru_id', $guruId));

        return DataTables::of($jadwal)
            ->addIndexColumn()
            ->addColumn('mata_pelajaran', fn($j) => $j->guruMatpel->mataPelajaran->nama_mapel)
            ->addColumn('kelas', fn($j) => $j->kelas->nama_kelas)
            ->addColumn('hari', fn($j) => $j->hari)
            ->addColumn('jam', fn($j) => formatStartEndTime($j->jam_mulai, $j->jam_selesai))
            ->make(true);
    }

    public function index()
    {
        return Inertia::render('guru/jadwal-mengajar/Index');
    }

    public function show(string $id)
    {
        $jadwal = JadwalPelajaran::with(['guruMatpel.mataPelajaran', 'kelas'])->findOrFail($id);

        $materi = $jadwal->materi()->orderBy('pertemuan_ke')->get();
        $tugas = $jadwal->evaluasiPembelajaran()->orderBy('waktu_mulai')->get();

        return Inertia::render('guru/jadwal-mengajar/Show', [
            'jadwal' => [
                'id' => $jadwal->id,
                'mata_pelajaran' => $jadwal->guruMatpel->mataPelajaran->nama_mapel,
                'kelas' => $jadwal->kelas->nama_kelas,
                'hari' => $jadwal->hari,
                'jam' => formatStartEndTime($jadwal->jam_mulai, $jadwal->jam_selesai),
            ],
            'materi' => $materi->map(fn ($materi) => [
                'id' => $materi->id,
                'pertemuan_ke' => $materi->pertemuan_ke,
                'judul_materi' => $materi->judul_materi,
                'file_materi' => $materi->file_materi,
                'link_materi' => $materi->link_materi,
                'created_at' => $materi->created_at
            ]),
            'tugas' => $tugas->map(fn ($tugas) => [
                'id' => $tugas->id,
                'judul' => $tugas->judul,
                'deadline' => $tugas->waktu_mulai . ' - ' . $tugas->waktu_selesai,
                'file_tugas' => $tugas->file_tugas,
                'link_tugas' => $tugas->link_tugas
            ]),
        ]);
    }

}
