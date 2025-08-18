<?php

namespace App\Exports;

use App\Models\Nilai;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class LaporanNilaiExport implements FromView
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function view(): View
    {
        $query = Nilai::with(['siswa.user', 'siswa.kelas', 'jadwal.matpel', 'guru.user'])
            ->when($this->filters['kelas_id'] ?? null, fn($q) => $q->whereHas('siswa', fn($qq) => $qq->where('kelas_id', $this->filters['kelas_id'])))
            ->when($this->filters['mapel_id'] ?? null, fn($q) => $q->whereHas('jadwal', fn($qq) => $qq->where('matpel_id', $this->filters['mapel_id'])))
            ->when($this->filters['semester'] ?? null, fn($q) => $q->where('semester', $this->filters['semester']))
            ->when($this->filters['tahun_ajaran'] ?? null, fn($q) => $q->where('tahun_ajarannya', $this->filters['tahun_ajaran']))
            ->get();

        return view('exports.laporan_nilai', [
            'data' => $query
        ]);
    }
}
