<?php

namespace App\Http\Controllers\Admin;

use App\Exports\LaporanNilaiExport;
use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\SemesterAjaran;
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
            'semesterAndTahunAjaranList' => SemesterAjaran::where('status_aktif', true)->get()->map(fn ($se) => [
                'id' => $se->id,
                'semester' => $se->semester,
                'tahun_ajaran' => $se->tahun_ajaran
            ])
        ]);
    }

    public function get(Request $request)
    {
        $query = Nilai::query()
            ->with(['siswa.user', 'siswa.kelas', 'evaluasiPembelajaran.guruMatpel.mataPelajaran', 'evaluasiPembelajaran.guruMatpel.guru.user', 'evaluasiPembelajaran.semesterAjaran'])
            ->when($request->kelas_id, fn($q) => $q->whereHas('siswa', fn($qq) => $qq->where('kelas_id', $request->kelas_id)))
            ->when($request->mapel_id, fn($q) => $q->whereHas('evaluasiPembelajaran.guruMatpel', fn($qq) => $qq->where('matpel_id', $request->mapel_id)))
            ->when($request->semester, fn($q) => $q->whereHas('evaluasiPembelajaran.semesterAjaran', fn($qq) => $qq->where('semester', $request->semester)))
            ->when($request->tahun_ajaran, fn($q) => $q->whereHas('evaluasiPembelajaran.semesterAjaran', fn($qq) => $qq->where('tahun_ajaran', $request->tahun_ajaran)));

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama_siswa', fn($row) => $row->siswa->user->name ?? '-')
            ->addColumn('kelas', fn($row) => $row->siswa->kelas->nama_kelas ?? '-')
            ->addColumn('mapel', fn($row) => $row->evaluasiPembelajaran->guruMatpel->mataPelajaran->nama_mapel ?? '-')
            ->addColumn('guru', fn($row) => $row->evaluasiPembelajaran->guruMatpel->guru->user->name ?? '-')
            ->addColumn('jenis', fn($row) => $row->evaluasiPembelajaran->jenis ?? '-')
            ->addColumn('semester', fn($row) => $row->evaluasiPembelajaran->semesterAjaran->semester ?? '-')
            ->addColumn('tahun_ajaran', fn($row) => $row->evaluasiPembelajaran->semesterAjaran->tahun_ajaran ?? '-')
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
        
        $query = Nilai::with(['siswa.user', 'siswa.kelas', 'evaluasiPembelajaran.guruMatpel.mataPelajaran', 'evaluasiPembelajaran.guruMatpel.guru.user', 'evaluasiPembelajaran.semesterAjaran'])
            ->when($filters['kelas_id'] ?? null, fn($q) => $q->whereHas('siswa', fn($qq) => $qq->where('kelas_id', $filters['kelas_id'])))
            ->when($filters['mapel_id'] ?? null, fn($q) => $q->whereHas('evaluasiPembelajaran.guruMatpel', fn($qq) => $qq->where('matpel_id', $filters['mapel_id'])))
            ->when($filters['semester'] ?? null, fn($q) => $q->whereHas('evaluasiPembelajaran.semesterAjaran', fn($qq) => $qq->where('semester', $filters['semester'])))
            ->when($filters['tahun_ajaran'] ?? null, fn($q) => $q->whereHas('evaluasiPembelajaran.semesterAjaran', fn($qq) => $qq->where('tahun_ajaran', $filters['tahun_ajaran'])))
            ->get();

        $pdf = Pdf::loadView('exports.laporan_nilai', ['data' => $query]);
        return $pdf->download('laporan_nilai.pdf');
    }
}
