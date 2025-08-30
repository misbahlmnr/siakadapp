<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\Nilai\StoreRequest;
use App\Http\Requests\Nilai\UpdateRequest;
use App\Models\AdaptiveRules;
use App\Models\EvaluasiPembelajaran;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\RekomendasiMateri;
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
        $mapelOptions = $mapelOptions = Auth::user()
            ->guruProfile
            ->mataPelajaran()
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
                'siswa.user',
                'evaluasiPembelajaran.jadwal.guruMatpel.mataPelajaran',
                'evaluasiPembelajaran.jadwal.kelas',
            ])
            ->whereHas('evaluasiPembelajaran.jadwal.guruMatpel', function ($q) use ($guruId, $request) {
                $q->where('guru_id', $guruId);

                // Filter Kelas
                if ($request->kelas) {
                    $q->whereHas('jadwal.kelas', function ($q2) use ($request) {
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
                return $row->evaluasiPembelajaran->jadwal->guruMatpel->mataPelajaran->nama_mapel ?? '-';
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
        $siswaOptions = SiswaProfile::whereHas('kelas.jadwalPelajaran.guruMatpel', function ($q) use ($guruId, $semesterAktif) {
            $q->where('guru_id', $guruId)
            ->where('semester_ajaran_id', $semesterAktif->id);
        })->with('user')->get()->map(fn($s) => [
            'id' => $s->id,
            'nama' => $s->user->name ?? '-',
            'kelas' => $s->kelas->nama_kelas,
        ]);

        // Ambil evaluasi yang dibuat guru ini di semester aktif
        $evaluasiOptions = EvaluasiPembelajaran::whereHas('jadwal.guruMatpel', function($q) use ($guruId, $semesterAktif){
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
        // Simpan nilai
        $nilai = Nilai::create($request->all());
        
        // Cek apakah nilai memicu adaptive rules
        $this->checkAdaptiveRules($nilai);
        
        return to_route('guru.nilai.index')->with('success', 'Nilai berhasil ditambahkan');
    }
    
    private function checkAdaptiveRules(Nilai $nilai)
    {
        // Ambil evaluasi pembelajaran untuk mendapatkan mata pelajaran
        $evaluasi = EvaluasiPembelajaran::with('jadwal.guruMatpel.mataPelajaran')->find($nilai->evaluasi_id);
        
        if (!$evaluasi || !$evaluasi->jadwal || !$evaluasi->jadwal->mataPelajaran) {
            return;
        }
        
        $mataPelajaranId = $evaluasi->jadwal->mataPelajaran->id;
        
        // Cari adaptive rules untuk mata pelajaran ini
        $adaptiveRules = AdaptiveRules::where('matpel_id', $mataPelajaranId)->get();
            
        foreach ($adaptiveRules as $rule) {
            // Cek apakah nilai memenuhi kondisi rule
            if ($this->evaluateRule($rule, $nilai->nilai)) {
                // Buat rekomendasi materi
                RekomendasiMateri::create([
                    'siswa_id' => $nilai->siswa_id,
                    'evaluasi_id' => $nilai->evaluasi_id,
                    'materi_id' => $rule->materi_id,
                    'alasan_rekomendasi' => "Nilai evaluasi (" . $nilai->nilai . ") di bawah batas KKM (" . $rule->nilai_batas . ") untuk mata pelajaran " . $evaluasi->jadwal->mataPelajaran->nama_mapel,
                    'status' => 'belum_dibaca'
                ]);
            }
        }
    }
    
    private function evaluateRule(AdaptiveRules $rule, $nilai)
    {
        switch ($rule->operator) {
            case '<':
                return $nilai < $rule->nilai_batas;
            case '<=':
                return $nilai <= $rule->nilai_batas;
            case '>':
                return $nilai > $rule->nilai_batas;
            case '>=':
                return $nilai >= $rule->nilai_batas;
            case '==':
                return $nilai == $rule->nilai_batas;
            default:
                return false;
        }
    }

    public function show(string $id)
    {
        $nilai = Nilai::with(['siswa.user', 'evaluasiPembelajaran', 'evaluasiPembelajaran.guruMatpel.mataPelajaran'])->findOrFail($id);
        return Inertia::render('guru/nilai/Show', [
            'nilai' => [
                'id' => $nilai->id,
                'nama_siswa' => $nilai->siswa->user->name ?? '-',
                'nis' => $nilai->siswa->nis ?? '-',
                'kelas' => $nilai->siswa->kelas->nama_kelas ?? '-',
                'judul_evaluasi' => $nilai->evaluasiPembelajaran->judul,
                'jenis_evaluasi' => $nilai->evaluasiPembelajaran->jenis,
                'mata_pelajaran' => $nilai->evaluasiPembelajaran->guruMatpel->mataPelajaran->nama_mapel ?? '-',
                'nilai' => $nilai->nilai,
                'status' => $nilai->status,
                'feedback' => $nilai->feedback,
                'tanggal_dinilai' => $nilai->tanggal_dinilai,
                'created_at' => $nilai->created_at,
                'updated_at' => $nilai->updated_at
            ]
        ]);
    }

    public function edit(string $id)
    {
        $guruId = Auth::user()->guruProfile->id;
        
        // Find the nilai record
        $nilai = Nilai::with(['siswa.user', 'evaluasiPembelajaran'])
            ->findOrFail($id);

        // Ambil semester_ajaran aktif
        $semesterAktif = SemesterAjaran::where('status_aktif', true)->first();

        // 🔎 Cari siswa berdasarkan kelas yang ada jadwal 
        // dimana guru terkait mengajar via guru_mata_pelajaran
        $siswaOptions = SiswaProfile::whereHas('kelas.jadwalPelajaran.guruMatpel', function ($q) use ($guruId, $semesterAktif) {
            $q->where('guru_id', $guruId)
            ->where('semester_ajaran_id', $semesterAktif->id);
        })
        ->with(['user', 'kelas'])
        ->get()->map(fn($s) => [
            'id' => $s->id,
            'nama' => $s->user->name ?? '-',
            'kelas' => $s->kelas->nama_kelas,
        ]);

        // 🔎 Ambil evaluasi berdasarkan jadwal yg terhubung ke guru_mata_pelajaran
        $evaluasiOptions = EvaluasiPembelajaran::whereHas('jadwal.guruMatpel', function($q) use ($guruId, $semesterAktif){
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
        
        // Cek apakah nilai yang diupdate memicu adaptive rules
        $this->checkAdaptiveRules($nilai);
        
        return to_route('guru.nilai.index')->with('success', 'Nilai berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        $nilai = Nilai::find($id);
        $nilai->delete();
        return to_route('guru.nilai.index')->with('success', 'Nilai berhasil dihapus');
    }
}
