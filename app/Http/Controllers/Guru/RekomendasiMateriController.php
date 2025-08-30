<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\RekomendasiMateriManual\StoreRequest;
use App\Http\Requests\RekomendasiMateriManual\UpdateRequest;
use App\Models\RekomendasiMateriManual;
use App\Models\SiswaProfile;
use App\Models\EvaluasiPembelajaran;
use App\Models\MateriPelajaran;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class RekomendasiMateriController extends Controller
{
    public function index()
    {
        return Inertia::render('guru/rekomendasi/Index');
    }

    public function get()
    {
        $query = RekomendasiMateriManual::with(['siswa.user', 'evaluasiPembelajaran.jadwal.guruMatpel.mataPelajaran', 'materi'])
            ->select('rekomendasi_materi_manual.*');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama_siswa', function($item) {
                return $item->siswa->user->name ?? '-';
            })
            ->addColumn('mata_pelajaran', function($item) {
                return $item->evaluasiPembelajaran->jadwal->guruMatpel->mataPelajaran->nama_mapel ?? '-';
            })
            ->addColumn('nama_materi', function($item) {
                return $item->materi->judul_materi ?? '-';
            })
            ->addColumn('status_badge', function($item) {
                $statusClass = [
                    'pending' => 'bg-yellow-100 text-yellow-800',
                    'diterima' => 'bg-green-100 text-green-800',
                    'ditolak' => 'bg-red-100 text-red-800'
                ];
                
                return '<span class="px-2 py-1 text-xs font-medium rounded-full ' . 
                       ($statusClass[$item->status] ?? 'bg-gray-100 text-gray-800') . '">' .
                       ucfirst($item->status) . '</span>';
            })
            ->rawColumns(['status_badge'])
            ->make(true);
    }

    public function create()
    {
        $siswaOptions = SiswaProfile::with('user:id,name')->get(['id', 'user_id'])->map(function ($siswa) {
            return [
                'id' => $siswa->id,
                'name' => $siswa->user->name
            ];
        });
        
        $evaluasiOptions = EvaluasiPembelajaran::all(['id', 'judul']);
        $materiOptions = MateriPelajaran::all(['id', 'judul_materi']);

        return Inertia::render('guru/rekomendasi/Create', [
            'siswaOptions' => $siswaOptions,
            'evaluasiOptions' => $evaluasiOptions,
            'materiOptions' => $materiOptions,
        ]);
    }

    public function store(StoreRequest $request)
    {
        RekomendasiMateriManual::create($request->validated());

        return redirect()->route('guru.rekomendasi-materi.index')->with('success', 'Rekomendasi materi berhasil dibuat.');
    }

    public function show(string $id)
    {
        $rekomendasi = RekomendasiMateriManual::with([
            'siswa.user', 
            'evaluasiPembelajaran.jadwal.guruMatpel.mataPelajaran', 
            'materi'
        ])->findOrFail($id);

        return Inertia::render('guru/rekomendasi/Show', [
            'rekomendasi' => $rekomendasi,
        ]);
    }

    public function edit(string $id)
    {
        $rekomendasi = RekomendasiMateriManual::findOrFail($id);
        
        $siswaOptions = SiswaProfile::with('user:id,name')->get(['id', 'user_id'])->map(function ($siswa) {
            return [
                'id' => $siswa->id,
                'name' => $siswa->user->name
            ];
        });
        
        $evaluasiOptions = EvaluasiPembelajaran::all(['id', 'judul']);
        $materiOptions = MateriPelajaran::all(['id', 'judul_materi']);

        return Inertia::render('guru/rekomendasi/Edit', [
            'rekomendasi' => $rekomendasi,
            'siswaOptions' => $siswaOptions,
            'evaluasiOptions' => $evaluasiOptions,
            'materiOptions' => $materiOptions,
        ]);
    }

    public function update(UpdateRequest $request, string $id)
    {
        $rekomendasi = RekomendasiMateriManual::findOrFail($id);
        $rekomendasi->update($request->validated());

        return redirect()->route('guru.rekomendasi-materi.index')->with('success', 'Rekomendasi materi berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        RekomendasiMateriManual::findOrFail($id)->delete();

        return redirect()->route('guru.rekomendasi-materi.index')->with('success', 'Rekomendasi materi berhasil dihapus.');
    }
}
