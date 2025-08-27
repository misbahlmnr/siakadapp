<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Absensi;
use App\Models\SiswaProfile;
use App\Models\SemesterAjaran;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        // Get current student profile
        $siswa = SiswaProfile::where('user_id', Auth::id())->first();
        
        if (!$siswa) {
            return Inertia::render('siswa/absensi/Index', [
                'absensi' => []
            ]);
        }

        // Get current active semester
        $semesterAktif = SemesterAjaran::where('status_aktif', true)->first();

        // Get attendance data for current student
        $absensi = Absensi::with(['jadwal.mataPelajaran', 'semesterAjaran'])
            ->where('siswa_id', $siswa->id)
            ->where('semester_ajaran_id', $semesterAktif->id ?? null)
            ->orderBy('tanggal', 'desc')
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'tanggal' => $item->tanggal,
                    'status' => $this->getStatusText($item->status),
                    'keterangan' => $item->jadwal->mataPelajaran->nama_mapel,
                    'pertemuan_ke' => $item->pertemuan_ke,
                    'mata_pelajaran' => $item->jadwal->mataPelajaran->nama_mapel
                ];
            });

        return Inertia::render('siswa/absensi/Index', [
            'absensi' => $absensi
        ]);
    }

    private function getStatusText($status)
    {
        $statuses = [
            'H' => 'Hadir',
            'I' => 'Izin',
            'S' => 'Sakit',
            'A' => 'Alpha',
            'T' => 'Terlambat'
        ];

        return $statuses[$status] ?? $status;
    }
}
