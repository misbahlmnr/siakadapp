<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\{RekomendasiMateri, Nilai, SiswaProfile};
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class ProgressSiswaController extends Controller
{
    public function index()
    {
        return Inertia::render('guru/progress-siswa/Index');
    }

    public function get()
    {
        $progress = RekomendasiMateri::with(['siswa', 'materi', 'evaluasiPembelajaran'])
            ->select('siswa_id')
            ->groupBy('siswa_id')
            ->get();

        return DataTables::of($progress)
            ->addIndexColumn()
            ->addColumn('nama_siswa', fn ($row) => $row->siswa->nama_lengkap ?? '-')
            ->addColumn('total_rekomendasi', fn ($row) => RekomendasiMateri::where('siswa_id', $row->siswa_id)->count())
            ->addColumn('rekomendasi_selesai', fn ($row) => RekomendasiMateri::where('siswa_id', $row->siswa_id)->where('status', 'selesai')->count())
            ->addColumn('rekomendasi_belum_dibaca', fn ($row) => RekomendasiMateri::where('siswa_id', $row->siswa_id)->where('status', 'belum_dibaca')->count())
            ->make(true);
    }
}
