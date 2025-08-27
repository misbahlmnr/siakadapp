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

        // Ambil semua mata pelajaran untuk kelas siswa
        $mataPelajaran = MataPelajaran::whereHas('jadwalPelajaran', function($q) use ($siswa) {
            $q->where('kelas_id', $siswa->kelas_id)
              ->whereHas('semesterAjaran', fn($s) => $s->where('status_aktif', true));
        })->with(['jadwalPelajaran' => function($q) use ($siswa) {
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
        $materi = MateriPelajaran::with('jadwal.mataPelajaran', 'jadwal.guru.user')->findOrFail($id);

        return Inertia::render('siswa/materi-belajar/Show', [
            'materi' => $materi
        ]);
    }
}
