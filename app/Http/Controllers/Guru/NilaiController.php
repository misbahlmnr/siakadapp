<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\Nilai\StoreRequest;
use App\Http\Requests\Nilai\UpdateRequest;
use App\Models\EvaluasiPembelajaran;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\SemesterAjaran;
use App\Models\SiswaProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class NilaiController extends Controller
{
    public function index()
    {
        // Ambil semua kelas yang ada
        $kelasOptions = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->pluck('nama_kelas');

        // Ambil semua mapel yang diajar guru ini
        $mapelOptions = MataPelajaran::where('guru_id', Auth::user()->guruProfile->id)
            ->orderBy('nama_mapel')
            ->pluck('nama_mapel');

        // Ambil angkatan siswa (unik)
        $angkatanOptions = SiswaProfile::select('angkatan')
            ->distinct()
            ->orderBy('angkatan', 'desc')
            ->pluck('angkatan');

        return Inertia::render('guru/nilai/Index',[
            'kelasOptions' => $kelasOptions,
            'mapelOptions' => $mapelOptions,
            'angkatanOptions' => $angkatanOptions
        ]);
    }

    public function get(Request $request)
    {
        $guruId = Auth::user()->guruProfile->id;

        $query = Nilai::with([
                'siswa',
                'evaluasiPembelajaran.jadwal.mataPelajaran',
                'evaluasiPembelajaran.jadwal.kelas',
            ])
            ->whereHas('evaluasiPembelajaran.jadwal', function ($q) use ($guruId, $request) {
                $q->where('guru_id', $guruId);

                // Filter Kelas
                if ($request->kelas) {
                    $q->whereHas('kelas', function ($q2) use ($request) {
                        $q2->where('nama_kelas', $request->kelas);
                    });
                }

                // Filter Mata Pelajaran
                if ($request->mapel) {
                    $q->whereHas('mataPelajaran', function ($q2) use ($request) {
                        $q2->where('nama_mapel', $request->mapel);
                    });
                }
            })
            ->when($request->jenis, function ($q) use ($request) {
                $q->whereHas('evaluasiPembelajaran', function ($q2) use ($request) {
                    $q2->where('jenis', $request->jenis);
                });
            })
            ->when($request->angkatan, function ($q) use ($request) {
                $q->whereHas('siswa', function ($q2) use ($request) {
                    $q2->where('angkatan', $request->angkatan);
                });
            })
            ->select('id', 'siswa_id', 'evaluasi_id', 'nilai');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('siswa', function ($row) {
                return $row->siswa->user->name ?? '-'; 
            })
            ->addColumn('kelas', function ($row) {
                return $row->evaluasiPembelajaran->jadwal->kelas->nama_kelas ?? '-';
            })
            ->addColumn('mata_pelajaran', function ($row) {
                return $row->evaluasiPembelajaran->jadwal->mataPelajaran->nama_mapel ?? '-';
            })
            ->addColumn('evaluasi', function ($row) {
                return $row->evaluasiPembelajaran->judul ?? '-';
            })
            ->addColumn('jenis', function ($row) {
                return $row->evaluasiPembelajaran->jenis ?? '-';
            })
            ->make(true);
    }

    public function create()
    {
        $guruId = Auth::user()->guruProfile->id;

        // Ambil semester_ajaran aktif
        $semesterAktif = SemesterAjaran::where('status_aktif', true)->first();

        // Ambil daftar siswa berdasarkan kelas yang diasuh guru di semester ini
        $siswaOptions = SiswaProfile::whereHas('kelas.jadwalPelajaran', function ($q) use ($guruId, $semesterAktif) {
            $q->where('guru_id', $guruId)
            ->where('semester_ajaran_id', $semesterAktif->id);
        })->with('user')->get()->map(fn($s) => [
            'id' => $s->id,
            'nama' => $s->user->name ?? '-',
            'kelas' => $s->kelas->nama_kelas,
        ]);

        // Ambil evaluasi yang dibuat guru ini di semester aktif
        $evaluasiOptions = EvaluasiPembelajaran::whereHas('jadwal', function($q) use ($guruId, $semesterAktif){
            $q->where('guru_id', $guruId)
            ->where('semester_ajaran_id', $semesterAktif->id);
        })->get(['id', 'judul', 'jenis']);

        return Inertia::render('guru/nilai/Create', [
            'siswaOptions' => $siswaOptions,
            'evaluasiOptions' => $evaluasiOptions,
            'semesterAktif' => $semesterAktif
        ]);
    }

    public function store(StoreRequest $request)
    {
        Nilai::create($request->all());
        return to_route('guru.nilai.index')->with('success', 'Nilai berhasil ditambahkan');
    }

    public function show(string $id)
    {

    }

    public function edit(string $id)
    {
        $guruId = Auth::user()->guruProfile->id;
        
        // Find the nilai record
        $nilai = Nilai::with(['siswa.user', 'evaluasiPembelajaran'])
            ->findOrFail($id);

        // Ambil semester_ajaran aktif
        $semesterAktif = SemesterAjaran::where('status_aktif', true)->first();

        // Ambil daftar siswa berdasarkan kelas yang diasuh guru di semester ini
        $siswaOptions = SiswaProfile::whereHas('kelas.jadwalPelajaran', function ($q) use ($guruId, $semesterAktif) {
            $q->where('guru_id', $guruId)
            ->where('semester_ajaran_id', $semesterAktif->id);
        })->with('user')->get()->map(fn($s) => [
            'id' => $s->id,
            'nama' => $s->user->name ?? '-',
            'kelas' => $s->kelas->nama_kelas,
        ]);

        // Ambil evaluasi yang dibuat guru ini di semester aktif
        $evaluasiOptions = EvaluasiPembelajaran::whereHas('jadwal', function($q) use ($guruId, $semesterAktif){
            $q->where('guru_id', $guruId)
            ->where('semester_ajaran_id', $semesterAktif->id);
        })->get(['id', 'judul', 'jenis']);

        return Inertia::render('guru/nilai/Edit', [
            'nilai' => $nilai,
            'siswaOptions' => $siswaOptions,
            'evaluasiOptions' => $evaluasiOptions,
            'semesterAktif' => $semesterAktif
        ]);
    }

    public function update(UpdateRequest $request, string $id)
    {
        $nilai = Nilai::findOrFail($id);
        $nilai->update($request->validated());
        
        return to_route('guru.nilai.index')->with('success', 'Nilai berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        $nilai = Nilai::find($id);
        $nilai->delete();
        return to_route('guru.nilai.index')->with('success', 'Nilai berhasil dihapus');
    }
}
