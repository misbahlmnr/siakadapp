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
use Illuminate\Support\Facades\Auth;
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

        $mapelOptions = Auth::user()
            ->guruProfile
            ->mataPelajaran()
            ->orderBy('nama_mapel')
            ->pluck('nama_mapel');

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
            $query->whereHas('jadwal.guruMatpel.mataPelajaran', function ($q) use ($request) {
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
            ->addColumn('mata_pelajaran', fn($row) => $row->jadwal->guruMatpel->mataPelajaran->nama_mapel ?? '-')
            ->addColumn('tanggal', fn($row) => $row->tanggal ? Carbon::parse($row->tanggal)->translatedFormat('d F Y') : '-')
            ->addColumn('status', fn($row) => '<span class="inline-block p-0.5 px-2 rounded-full text-white text-xs ' . $this->getStatusClass($row->status) . '">' . $row->status . '</span>')
            ->rawColumns(['status'])
            ->make(true);
    }

    public function getStatusClass(string $value)
    {
        return match ($value) {
            'hadir' => 'bg-green-500',
            'izin' => 'bg-yellow-500',
            'sakit' => 'bg-blue-500',
            'alfa' => 'bg-red-500',
        };
    }

    public function create()
    {
        $guruId = Auth::user()->guruProfile->id;

        $semesterAktif = SemesterAjaran::where('status_aktif', true)->firstOrFail();
        
        // Kelas yang terkait dengan jadwal guru di semester aktif
        $kelasOptions = JadwalPelajaran::with('kelas')
            ->whereHas('guruMatpel', fn ($q) => $q->where('guru_id', $guruId))
            ->where('semester_ajaran_id', $semesterAktif->id)
            ->get()
            ->map(fn($jp) => [
                'id' => $jp->kelas->id,
                'nama_kelas' => $jp->kelas->nama_kelas,
            ])
            ->unique('id')
            ->values();

        // Mapel yang diajar guru di semester aktif
        $mapelOptions = JadwalPelajaran::with('guruMatpel.mataPelajaran')
            ->whereHas('guruMatpel', fn ($q) => $q->where('guru_id', $guruId))
            ->where('semester_ajaran_id', $semesterAktif->id)
            ->get()
            ->map(fn($jp) => [
                'id' => $jp->guruMatpel->mataPelajaran->id,
                'nama_mapel' => $jp->guruMatpel->mataPelajaran->nama_mapel,
            ])
            ->unique('id')
            ->values();

        // Siswa hanya dari kelas yang ada jadwal guru ini
        $siswaOptions = SiswaProfile::whereHas('kelas.jadwalPelajaran', function ($q) use ($guruId, $semesterAktif) {
            $q->whereHas('guruMatpel', fn ($q) => $q->where('guru_id', $guruId))
            ->where('semester_ajaran_id', $semesterAktif->id);
        })
            ->with(['user', 'kelas'])
            ->get()
            ->map(fn($s) => [
                'id' => $s->id,
                'nama' => $s->user->name,
                'kelas' => $s->kelas->nama_kelas
            ]);

        // Jadwal guru di semester aktif
        $jadwalPelajaranOptions = JadwalPelajaran::with(['kelas', 'guruMatpel.mataPelajaran'])
            ->whereHas('guruMatpel', fn ($q) => $q->where('guru_id', $guruId))
            ->where('semester_ajaran_id', $semesterAktif->id)
            ->get()
            ->map(fn($jp) => [
                'id' => $jp->id,
                'label' => $jp->guruMatpel->mataPelajaran->nama_mapel 
                . ' | ' . $jp->kelas->nama_kelas 
                . ' | ' . $jp->hari 
                . ' | ' . substr($jp->jam_mulai, 0, 5) 
                . ' - ' . substr($jp->jam_selesai, 0, 5),
            ]);

        $semesterDanTahunAjaranList = collect([$semesterAktif])->map(fn ($se) => [
            'id' => $se->id,
            'semester' => $se->semester,
            'tahun_ajaran' => $se->tahun_ajaran
        ]);

        return inertia('guru/absensi/Create', [
            'kelasOptions' => $kelasOptions,
            'mapelOptions' => $mapelOptions,
            'siswaOptions' => $siswaOptions,
            'jadwalPelajaranOptions' => $jadwalPelajaranOptions,
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
        $absensi = Absensi::with(['siswa.user', 'jadwal.guruMatpel.mataPelajaran', 'jadwal.kelas'])->findOrFail($id);

        return inertia('guru/absensi/Show', [
            'absensi' => [
                'id' => $absensi->id,
                'nama_siswa' => $absensi->siswa->user->name ?? '-',
                'nis' => $absensi->siswa->nis ?? '-',
                'kelas' => $absensi->jadwal->kelas->nama_kelas ?? '-',
                'mata_pelajaran' => $absensi->jadwal->guruMatpel->mataPelajaran->nama_mapel ?? '-',
                'pertemuan_ke' => $absensi->pertemuan_ke,
                'tanggal' => $absensi->tanggal,
                'status' => $absensi->status,
                'created_at' => $absensi->created_at,
                'updated_at' => $absensi->updated_at
            ],
        ]);
    }

    public function edit(string $id)
    {
        $absensi = Absensi::findOrFail($id);
        $guruId = Auth::user()->guruProfile->id;

        $semesterAktif = SemesterAjaran::where('status_aktif', true)->firstOrFail();
        
        // Kelas yang terkait dengan jadwal guru di semester aktif
        $kelasOptions = JadwalPelajaran::with('kelas')
            ->whereHas('guruMatpel', fn ($q) => $q->where('guru_id', $guruId))
            ->where('semester_ajaran_id', $semesterAktif->id)
            ->get()
            ->map(fn($jp) => [
                'id' => $jp->kelas->id,
                'nama_kelas' => $jp->kelas->nama_kelas,
            ])
            ->unique('id')
            ->values();

        // Mapel yang diajar guru di semester aktif
        $mapelOptions = JadwalPelajaran::with('guruMatpel.mataPelajaran')
            ->whereHas('guruMatpel', fn ($q) => $q->where('guru_id', $guruId))
            ->where('semester_ajaran_id', $semesterAktif->id)
            ->get()
            ->map(fn($jp) => [
                'id' => $jp->guruMatpel->mataPelajaran->id,
                'nama_mapel' => $jp->guruMatpel->mataPelajaran->nama_mapel,
            ])
            ->unique('id')
            ->values();

        // Siswa hanya dari kelas yang ada jadwal guru ini
        $siswaOptions = SiswaProfile::whereHas('kelas.jadwalPelajaran', function ($q) use ($guruId, $semesterAktif) {
            $q->whereHas('guruMatpel', fn ($q) => $q->where('guru_id', $guruId))
            ->where('semester_ajaran_id', $semesterAktif->id);
        })
            ->with(['user', 'kelas'])
            ->get()
            ->map(fn($s) => [
                'id' => $s->id,
                'nama' => $s->user->name,
                'kelas' => $s->kelas->nama_kelas
            ]);

        // Jadwal guru di semester aktif
        $jadwalPelajaranOptions = JadwalPelajaran::with(['kelas', 'guruMatpel.mataPelajaran'])
            ->whereHas('guruMatpel', fn ($q) => $q->where('guru_id', $guruId))
            ->where('semester_ajaran_id', $semesterAktif->id)
            ->get()
            ->map(fn($jp) => [
                'id' => $jp->id,
                'label' => $jp->guruMatpel->mataPelajaran->nama_mapel 
                . ' | ' . $jp->kelas->nama_kelas 
                . ' | ' . $jp->hari 
                . ' | ' . substr($jp->jam_mulai, 0, 5) 
                . ' - ' . substr($jp->jam_selesai, 0, 5),
            ]);

        $semesterDanTahunAjaranList = collect([$semesterAktif])->map(fn ($se) => [
            'id' => $se->id,
            'semester' => $se->semester,
            'tahun_ajaran' => $se->tahun_ajaran
        ]);

        return inertia('guru/absensi/Edit', [
            'absensi' => $absensi,
            'kelasOptions' => $kelasOptions,
            'mapelOptions' => $mapelOptions,
            'siswaOptions' => $siswaOptions,
            'jadwalPelajaranOptions' => $jadwalPelajaranOptions,
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
