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
        $jadwal = JadwalPelajaran::with(['kelas', 'mataPelajaran'])
                ->where('guru_id', Auth::user()->guruProfile->id)
                ->orderBy('hari')
                ->orderBy('jam_mulai');

        return DataTables::of($jadwal)
                ->addIndexColumn()
                ->addColumn('waktu', fn ($row) => formatStartEndTime($row->jam_mulai, $row->jam_selesai))
                ->addColumn('nama_kelas', fn ($row) => $row->kelas->nama_kelas)
                ->addColumn('mata_pelajaran', fn ($row) => $row->mataPelajaran->nama_mapel)
                ->editColumn('hari', function ($row) {
                    $hariList = [
                        1 => 'Senin',
                        2 => 'Selasa',
                        3 => 'Rabu',
                        4 => 'Kamis',
                        5 => 'Jumat',
                        6 => 'Sabtu',
                        7 => 'Minggu',
                    ];
                    return $hariList[$row->hari] ?? $row->hari;
                })
                ->make(true);
    }

    public function index()
    {
        return Inertia::render('guru/jadwal-mengajar/Index');
    }
}
