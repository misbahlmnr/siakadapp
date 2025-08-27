<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\Absensi\StoreRequest;
use App\Http\Requests\Absensi\UpdateRequest;
use App\Models\Absensi;
use App\Models\JadwalPelajaran;
use App\Models\SemesterAjaran;
use App\Models\SiswaProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class AbsensiController extends Controller
{
    public function index()
    {
        $kelasOptions = JadwalPelajaran::with('kelas')
            ->get()
            ->pluck('kelas.nama_kelas')
            ->unique()
            ->values();

        $mapelOptions = JadwalPelajaran::with('mataPelajaran')
            ->get()
            ->pluck('mataPelajaran.nama_mapel')
            ->unique()
            ->values();

        return inertia('guru/absensi/Index', [
            'kelasOptions' => $kelasOptions,
            'mapelOptions' => $mapelOptions,
        ]);
    }

    public function get(Request $request)
    {
        $query = Absensi::with(['siswa', 'jadwal']);

        // Filter kelas
        if ($request->kelas) {
            $query->whereHas('jadwal.kelas', function ($q) use ($request) {
                $q->where('nama_kelas', $request->kelas);
            });
        }

        // Filter mapel
        if ($request->mapel) {
            $query->whereHas('jadwal.mataPelajaran', function ($q) use ($request) {
                $q->where('nama_mapel', $request->mapel);
            });
        }

        // Filter status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter pertemuan
        if ($request->pertemuan) {
            $query->where('pertemuan_ke', $request->pertemuan);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('siswa', fn($row) => $row->siswa->user->name ?? '-')
            ->addColumn('kelas', fn($row) => $row->jadwal->kelas->nama_kelas ?? '-')
            ->addColumn('mata_pelajaran', fn($row) => $row->jadwal->mataPelajaran->nama_mapel ?? '-')
            ->addColumn('tanggal', fn($row) => $row->tanggal ? Carbon::parse($row->tanggal)->translatedFormat('d F Y') : '-')
            ->addColumn('status', fn($row) => ucfirst($row->status))
            ->make(true);
    }

    public function create()
    {
        // Kelas
        $kelasOptions = JadwalPelajaran::with('kelas')
            ->get()
            ->map(fn($jp) => [
                'id' => $jp->kelas->id,
                'nama_kelas' => $jp->kelas->nama_kelas,
            ])
            ->unique('id')
            ->values();

        // Mapel
        $mapelOptions = JadwalPelajaran::with('mataPelajaran')
            ->get()
            ->map(fn($jp) => [
                'id' => $jp->mataPelajaran->id,
                'nama_mapel' => $jp->mataPelajaran->nama_mapel,
            ])
            ->unique('id')
            ->values();

        // Siswa
        $siswaOptions = SiswaProfile::with('user')
            ->get()
            ->map(fn($s) => [
                'id' => $s->id,
                'nama' => $s->user->name,
            ]);

        $semesterDanTahunAjaranList = SemesterAjaran::where('status_aktif', true)->get()->map(fn ($se) => [
            'id' => $se->id,
            'semester' => $se->semester,
            'tahun_ajaran' => $se->tahun_ajaran
        ]);

        return inertia('guru/absensi/Create', [
            'kelasOptions' => $kelasOptions,
            'mapelOptions' => $mapelOptions,
            'siswaOptions' => $siswaOptions,
            'semesterDanTahunAjaranList' => $semesterDanTahunAjaranList
        ]);
    }


    public function store(StoreRequest $request)
    {
        Absensi::create($request->all());

        return redirect()->route('guru.absensi.index')->with('success', 'Absensi berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $absensi = Absensi::with(['siswa.user', 'jadwal.mataPelajaran', 'jadwal.kelas'])->findOrFail($id);

        return inertia('guru/absensi/Show', [
            'absensi' => $absensi,
        ]);
    }

    public function edit(string $id)
    {
        $absensi = Absensi::findOrFail($id);
        
        // Kelas options - get unique kelas from jadwal
        $kelasOptions = JadwalPelajaran::with('kelas')
            ->get()
            ->pluck('kelas.nama_kelas', 'kelas.id')
            ->unique()
            ->map(fn($nama, $id) => [
                'id' => $id,
                'nama_kelas' => $nama
            ])
            ->values();

        // Mapel options - get unique mata pelajaran from jadwal
        $mapelOptions = JadwalPelajaran::with('mataPelajaran')
            ->get()
            ->pluck('mataPelajaran.nama_mapel', 'mataPelajaran.id')
            ->unique()
            ->map(fn($nama, $id) => [
                'id' => $id,
                'nama_mapel' => $nama
            ])
            ->values();

        // Siswa options
        $siswaOptions = SiswaProfile::with('user')
            ->get()
            ->map(fn($s) => [
                'id' => $s->id,
                'nama' => $s->user->name,
            ]);

        // Jadwal options for frontend to filter
        $jadwalOptions = JadwalPelajaran::with(['kelas', 'mataPelajaran'])
            ->get()
            ->map(fn($jp) => [
                'id' => $jp->id,
                'kelas_id' => $jp->kelas_id,
                'matpel_id' => $jp->matpel_id,
                'kelas_nama' => $jp->kelas->nama_kelas,
                'mapel_nama' => $jp->mataPelajaran->nama_mapel,
            ]);

        $semesterDanTahunAjaranList = SemesterAjaran::where('status_aktif', true)->get()->map(fn ($se) => [
            'id' => $se->id,
            'semester' => $se->semester,
            'tahun_ajaran' => $se->tahun_ajaran
        ]);

        return inertia('guru/absensi/Edit', [
            'absensi' => $absensi,
            'kelasOptions' => $kelasOptions,
            'mapelOptions' => $mapelOptions,
            'siswaOptions' => $siswaOptions,
            'jadwalOptions' => $jadwalOptions,
            'semesterDanTahunAjaranList' => $semesterDanTahunAjaranList
        ]);
    }

    public function update(UpdateRequest $request, string $id)
    {
        $absensi = Absensi::findOrFail($id);
        $absensi->update($request->all());

        return redirect()->route('guru.absensi.index')->with('success', 'Absensi berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $absensi = Absensi::findOrFail($id);
        $absensi->delete();

        return response()->json(['message' => 'Absensi berhasil dihapus.']);
    }
}
