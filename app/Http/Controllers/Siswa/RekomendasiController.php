<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\RekomendasiMateri;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class RekomendasiController extends Controller
{
    public function index()
    {
        return Inertia::render('siswa/rekomendasi/Index');
    }

    public function get()
    {
        $siswaId = Auth::user()->siswaProfile->id;
        
        $rekomendasi = RekomendasiMateri::with(['materi', 'evaluasiPembelajaran'])
            ->where('siswa_id', $siswaId);

        return DataTables::of($rekomendasi)
            ->addIndexColumn()
            ->addColumn('materi_nama', fn ($row) => $row->materi->nama_materi ?? '-')
            ->addColumn('evaluasi_nama', fn ($row) => $row->evaluasiPembelajaran->judul_evaluasi ?? '-')
            ->addColumn('status_badge', function ($row) {
                $badgeClass = [
                    'belum_dibaca' => 'bg-yellow-100 text-yellow-800',
                    'dibaca' => 'bg-blue-100 text-blue-800',
                    'selesai' => 'bg-green-100 text-green-800'
                ];
                
                $statusText = [
                    'belum_dibaca' => 'Belum Dibaca',
                    'dibaca' => 'Dibaca',
                    'selesai' => 'Selesai'
                ];
                
                return '<span class="px-2 py-1 text-xs font-medium rounded-full ' . $badgeClass[$row->status] . '">' . $statusText[$row->status] . '</span>';
            })
            ->rawColumns(['status_badge'])
            ->make(true);
    }

    public function show($id)
    {
        $rekomendasi = RekomendasiMateri::with(['materi', 'evaluasiPembelajaran'])
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
