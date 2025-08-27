<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\RekomendasiMateri;
use App\Models\MataPelajaran; // Tambahkan import untuk MataPelajaran
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RekomendasiController extends Controller
{
    public function index()
    {
        $siswaId = Auth::user()->siswaProfile->id;

        $rekomendasi = RekomendasiMateri::with([
            'materi.jadwal.mataPelajaran', // Eager loading untuk mendapatkan mata pelajaran
            'evaluasiPembelajaran'
        ])
        ->where('siswa_id', $siswaId)
        ->get();

        $mataPelajaran = MataPelajaran::all(); // Ambil semua mata pelajaran

        return Inertia::render('siswa/rekomendasi/Index', [
            'mataPelajaranList' => $mataPelajaran, // Kirim data mata pelajaran ke frontend
            'rekomendasi' => $rekomendasi,
        ]);
    }

    public function show($id)
    {
        $rekomendasi = RekomendasiMateri::with([
            'materi.jadwal.mataPelajaran', // Eager loading untuk mendapatkan mata pelajaran
            'evaluasiPembelajaran'
        ])
            ->where('id', $id)
            ->where('siswa_id', Auth::user()->siswaProfile->id)
            ->firstOrFail();

        return Inertia::render('siswa/rekomendasi/Show', [
            'rekomendasi' => $rekomendasi
        ]);
    }

    public function updateStatus($id)
    {
        $rekomendasi = RekomendasiMateri::where('id', $id)
            ->where('siswa_id', Auth::user()->siswaProfile->id)
            ->firstOrFail();

        $rekomendasi->update([
            'status' => request('status')
        ]);

        return response()->json(['success' => true]);
    }
}
