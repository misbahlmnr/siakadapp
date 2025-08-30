<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use App\Models\MateriPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MateriBelajarController extends Controller
{
    public function index()
    {
        $siswa = Auth::user()->siswaProfile;

        // Ambil mata pelajaran untuk kelas siswa dengan semester aktif melalui guru_mata_pelajaran
        $mataPelajaran = MataPelajaran::whereHas('guruMataPelajaran.jadwalPelajaran', function($q) use ($siswa) {
            $q->where('kelas_id', $siswa->kelas_id)
              ->whereHas('semesterAjaran', fn($s) => $s->where('status_aktif', true));
        })->with(['guruMataPelajaran.jadwalPelajaran' => function($q) use ($siswa) {
            $q->where('kelas_id', $siswa->kelas_id)
              ->whereHas('semesterAjaran', fn($s) => $s->where('status_aktif', true))
              ->with('materi');
        }])->get();

        return Inertia::render('siswa/materi-belajar/Index', [
            'mataPelajaran' => $mataPelajaran
        ]);
    }

    public function show($id)
    {
        // Detail materi pertemuan
        $materi = MateriPelajaran::with('jadwal.guruMatpel.mataPelajaran', 'jadwal.guruMatpel.guru.user')->findOrFail($id);

        return Inertia::render('siswa/materi-belajar/Show', [
            'materi' => [
                'id' => $materi->id,
                'judul_materi' => $materi->judul_materi ?? '-',
                'pertemuan_ke' => $materi->pertemuan_ke ?? '-',
                'mata_pelajaran' => $materi->jadwal->guruMatpel->mataPelajaran->nama_mapel ?? '-',
                'kode_mapel' => $materi->jadwal->guruMatpel->mataPelajaran->kode_mapel ?? '-',
                'deskripsi' => $materi->deskripsi ?? '-',
                'file_materi' => $materi->file_materi,
                'link_materi' => $materi->link_materi,
                'nama_guru' => $materi->jadwal->guruMatpel->guru->user->name ?? '-',
                'created_at' => $materi->created_at
            ]
        ]);
    }
}
