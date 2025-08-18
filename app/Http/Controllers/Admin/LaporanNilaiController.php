<?php

namespace App\Http\Controllers\Admin;

use App\Exports\LaporanNilaiExport;
use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class LaporanNilaiController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/laporan-nilai/Index', [
            'kelas' => Kelas::all(),
            'matpel' => MataPelajaran::all(),
        ]);
    }

    public function get(Request $request)
    {
        $query = Nilai::query()
            ->with(['siswa.user', 'siswa.kelas', 'jadwal.matpel', 'guru.user'])
            ->when($request->kelas_id, fn($q) => $q->whereHas('siswa', fn($qq) => $qq->where('kelas_id', $request->kelas_id)))
            ->when($request->mapel_id, fn($q) => $q->whereHas('jadwal', fn($qq) => $qq->where('matpel_id', $request->mapel_id)))
            ->when($request->semester, fn($q) => $q->where('semester', $request->semester))
            ->when($request->tahun_ajaran, fn($q) => $q->where('tahun_ajarannya', $request->tahun_ajaran));

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama_siswa', fn($row) => $row->siswa->user->name ?? '-')
            ->addColumn('kelas', fn($row) => $row->siswa->kelas->nama_kelas ?? '-')
            ->addColumn('mapel', fn($row) => $row->jadwal->matpel->nama_mapel ?? '-')
            ->addColumn('guru', fn($row) => $row->guru->user->name ?? '-')
            ->make(true);
    }

    public function exportExcel(Request $request)
    {
        $filters = $request->only(['kelas_id','mapel_id','semester','tahun_ajaran']);
        return Excel::download(new LaporanNilaiExport($filters), 'laporan_nilai.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $filters = $request->only(['kelas_id','mapel_id','semester','tahun_ajaran']);
        $data = (new LaporanNilaiExport($filters))->view()->getData()['data'];
        $pdf = Pdf::loadView('exports.laporan_nilai', compact('data'));
        return $pdf->download('laporan_nilai.pdf');
    }
}
