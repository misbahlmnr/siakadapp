<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\EvaluasiPembelajaran;
use App\Models\PengumpulanTugas;
use App\Models\SiswaProfile;
use App\Models\SemesterAjaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TugasController extends Controller
{
    public function index(Request $request)
    {
        // Get current active semester
        $semesterAktif = SemesterAjaran::where('status_aktif', true)->first();
        
        // Get current student profile
        $siswa = SiswaProfile::where('user_id', Auth::id())->first();
        
        if (!$siswa) {
            return Inertia::render('siswa/tugas/Index', [
                'tugas' => [],
                'mataPelajaran' => [],
                'filters' => $request->only(['mapel', 'status'])
            ]);
        }

        // Get assignments with eager loading
        $query = EvaluasiPembelajaran::with([
            'jadwal.mataPelajaran',
            'jadwal.kelas',
            'guru.user',
            'pengumpulanTugas' => function($q) use ($siswa) {
                $q->where('siswa_id', $siswa->id);
            },
            'nilai' => function($q) use ($siswa) {
                $q->where('siswa_id', $siswa->id);
            }
        ])
        ->whereHas('jadwal.kelas', function($q) use ($siswa) {
            $q->where('id', $siswa->kelas_id);
        })
        ->where('semester_ajaran_id', $semesterAktif->id ?? null)
        ->where('jenis', 'tugas');

        // Filter by subject if provided
        if ($request->has('mapel') && $request->mapel !== 'all') {
            $query->whereHas('jadwal.mataPelajaran', function($q) use ($request) {
                $q->where('id', $request->mapel);
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            if ($request->status === 'selesai') {
                $query->whereHas('pengumpulanTugas', function($q) use ($siswa) {
                    $q->where('siswa_id', $siswa->id)
                      ->whereIn('status', ['dikumpulkan', 'telat']);
                });
            } elseif ($request->status === 'belum') {
                $query->whereDoesntHave('pengumpulanTugas', function($q) use ($siswa) {
                    $q->where('siswa_id', $siswa->id);
                });
            }
        }

        $tugas = $query->orderBy('waktu_selesai', 'asc')->get();

        // Transform data for frontend
        $tugas->transform(function($item) use ($siswa) {
            $pengumpulan = $item->pengumpulanTugas->first();
            $nilai = $item->nilai->first();
            
            $status = 'belum_dikumpulkan';
            $waktuTersisa = null;
            
            if ($pengumpulan) {
                $status = $pengumpulan->status;
            }
            
            // Calculate time remaining
            $now = now();
            $deadline = \Carbon\Carbon::parse($item->waktu_selesai);
            
            if ($now->lt($deadline)) {
                $waktuTersisa = $now->diffForHumans($deadline, true);
            } else {
                $waktuTersisa = 'Terlambat';
            }

            return [
                'id' => $item->id,
                'judul' => $item->judul,
                'deskripsi' => $item->deskripsi,
                'mata_pelajaran' => $item->jadwal->mataPelajaran->nama_mapel,
                'guru' => $item->guru->user->name,
                'waktu_mulai' => $item->waktu_mulai,
                'waktu_selesai' => $item->waktu_selesai,
                'waktu_tersisa' => $waktuTersisa,
                'status' => $status,
                'file_soal' => $item->file_soal,
                'link_soal' => $item->link_soal,
                'nilai' => $nilai ? $nilai->nilai : null,
                'feedback' => $nilai ? $nilai->feedback : null,
                'sudah_dikumpulkan' => $pengumpulan !== null,
                'file_jawaban' => $pengumpulan ? $pengumpulan->file_jawaban : null,
                'link_jawaban' => $pengumpulan ? $pengumpulan->link_jawaban : null
            ];
        });

        // Get unique subjects for filter
        $mataPelajaran = EvaluasiPembelajaran::with('jadwal.mataPelajaran')
            ->whereHas('jadwal.kelas', function($q) use ($siswa) {
                $q->where('id', $siswa->kelas_id);
            })
            ->where('semester_ajaran_id', $semesterAktif->id ?? null)
            ->where('jenis', 'tugas')
            ->get()
            ->pluck('jadwal.mataPelajaran')
            ->unique('id')
            ->values();

        return Inertia::render('siswa/tugas/Index', [
            'tugas' => $tugas,
            'mataPelajaran' => $mataPelajaran,
            'filters' => $request->only(['mapel', 'status'])
        ]);
    }

    public function show($id)
    {
        $siswa = SiswaProfile::where('user_id', Auth::id())->first();
        
        $tugas = EvaluasiPembelajaran::with([
            'jadwal.mataPelajaran',
            'jadwal.kelas',
            'guru.user',
            'pengumpulanTugas' => function($q) use ($siswa) {
                $q->where('siswa_id', $siswa->id);
            },
            'nilai' => function($q) use ($siswa) {
                $q->where('siswa_id', $siswa->id);
            }
        ])->findOrFail($id);

        $pengumpulan = $tugas->pengumpulanTugas->first();
        $nilai = $tugas->nilai->first();

        return Inertia::render('siswa/tugas/Show', [
            'tugas' => [
                'id' => $tugas->id,
                'judul' => $tugas->judul,
                'deskripsi' => $tugas->deskripsi,
                'mata_pelajaran' => $tugas->jadwal->mataPelajaran->nama_mapel,
                'guru' => $tugas->guru->user->name,
                'waktu_mulai' => $tugas->waktu_mulai,
                'waktu_selesai' => $tugas->waktu_selesai,
                'file_soal' => $tugas->file_soal,
                'link_soal' => $tugas->link_soal,
                'pengumpulan' => $pengumpulan ? [
                    'file_jawaban' => $pengumpulan->file_jawaban,
                    'link_jawaban' => $pengumpulan->link_jawaban,
                    'status' => $pengumpulan->status,
                    'waktu_pengumpulan' => $pengumpulan->waktu_pengumpulan
                ] : null,
                'nilai' => $nilai ? [
                    'nilai' => $nilai->nilai,
                    'feedback' => $nilai->feedback,
                    'status' => $nilai->status
                ] : null
            ]
        ]);
    }

    public function submit(Request $request, $id)
    {
        $request->validate([
            'file_jawaban' => 'nullable|file|mimes:pdf,doc,docx,zip,rar|max:10240',
            'link_jawaban' => 'nullable|url',
        ]);

        $siswa = SiswaProfile::where('user_id', Auth::id())->first();
        $tugas = EvaluasiPembelajaran::findOrFail($id);

        // Check if already submitted
        $existing = PengumpulanTugas::where('evaluasi_id', $id)
            ->where('siswa_id', $siswa->id)
            ->first();

        $data = [
            'evaluasi_id' => $id,
            'siswa_id' => $siswa->id,
            'link_jawaban' => $request->link_jawaban,
            'status' => 'dikumpulkan',
            'waktu_pengumpulan' => now()
        ];

        // Handle file upload
        if ($request->hasFile('file_jawaban')) {
            $file = $request->file('file_jawaban');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('pengumpulan_tugas', $filename, 'public');
            $data['file_jawaban'] = $path;
        }

        if ($existing) {
            // Update existing submission
            if ($request->hasFile('file_jawaban') && $existing->file_jawaban) {
                // Delete old file
                Storage::disk('public')->delete($existing->file_jawaban);
            }
            $existing->update($data);
        } else {
            // Create new submission
            PengumpulanTugas::create($data);
        }

        return redirect()->route('siswa.tugas.index')
            ->with('success', 'Tugas berhasil dikumpulkan!');
    }
}
