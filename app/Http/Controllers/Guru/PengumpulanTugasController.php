<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\PengumpulanTugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PengumpulanTugasController extends Controller
{
    public function show(string $jadwal_id, string $evaluasi_id, string $pengumpulan_id)
    {
        $submission = PengumpulanTugas::with(['siswa', 'evaluasiPembelajaran'])
            ->findOrFail($pengumpulan_id);

        $data = [
            'id' => $submission->id,
            'siswa' => $submission->siswa->user->name,
            'nama_tugas' => $submission->evaluasiPembelajaran->judul,
            'file_jawaban' => $submission->file_jawaban ? Storage::url($submission->file_jawaban) : null,
            'link_jawaban' => $submission->link_jawaban ? $submission->link_jawaban : null
        ];
        
        return Inertia::render('guru/jadwal-mengajar/evaluasi-pembelajaran/pengumpulan-tugas/Show', [
            'jadwal_id' => $jadwal_id,
            'evaluasi_id' => $evaluasi_id,
            'submission' => $data
        ]);
    }
}
