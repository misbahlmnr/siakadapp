<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\TugasUjian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class TugasUjianController extends Controller
{
    public function get()
    {
        $guru = Auth::user()->guruProfile;

        $tugasUjian = TugasUjian::with(['jadwal.kelas', 'jadwal.mataPelajaran'])
            ->whereHas('jadwal', function($q) use ($guru) {
                $q->where('guru_id', $guru->id);
            })->get();

        return DataTables::of($tugasUjian)
            ->addIndexColumn()
            ->addColumn('kelas', fn($row) => $row->jadwal->kelas->nama_kelas)
            ->addColumn('mata_pelajaran', fn($row) => $row->jadwal->mataPelajaran->nama_mapel)
            ->addColumn('deadline', fn($row) => $row->deadline ? $row->deadline->format('d-m-Y H:i') : '-')
            ->make(true);
    }

    public function index()
    {
        return Inertia::render('guru/tugas-ujian/Index');
    }
}
