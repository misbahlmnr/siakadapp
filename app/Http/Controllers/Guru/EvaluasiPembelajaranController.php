<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\EvaluasiPembelajaran\StoreRequest;
use App\Models\EvaluasiPembelajaran;
use App\Models\JadwalPelajaran;
use App\Models\MataPelajaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class EvaluasiPembelajaranController extends Controller
{
    public function index(string $jadwal_id)
    {
        $mapel = JadwalPelajaran::find($jadwal_id)->mataPelajaran;
        return Inertia::render('guru/jadwal-mengajar/evaluasi-pembelajaran/Index', [
            'jadwal_id' => $jadwal_id,
            'nama_mapel' => $mapel->nama_mapel,
        ]);
    }

    public function get(string $jadwal_id)
    {
        $query = EvaluasiPembelajaran::where('jadwal_id', $jadwal_id);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('waktu', function ($row) {
            return Carbon::parse($row->waktu_mulai)->format('d-m-Y H:i') . 
                   ' s/d ' . 
                   Carbon::parse($row->waktu_selesai)->format('d-m-Y H:i');
            })
            ->editColumn('file_soal', fn ($row) => $row->file_soal ? '<a href="' . Storage::url($row->file_soal) . '" target="_blank" class="text-blue-400 hover:underline">Download</a>' : '-')
            ->editColumn('link_soal', fn ($row) => $row->link_soal ? '<a href="' . $row->link_soal . '" target="_blank">Buka Link</a>' : '-')
            ->rawColumns(['file_soal', 'link_soal'])
            ->make(true);
    }

    public function create(string $jadwal_id)
    {
        $mapel = JadwalPelajaran::find($jadwal_id)->mataPelajaran;
        return Inertia::render('guru/jadwal-mengajar/evaluasi-pembelajaran/Create', [
            'jadwal_id' => $jadwal_id,
            'guru_id' => Auth::user()->guruProfile->id,
            'nama_mapel' => $mapel->nama_mapel,
        ]);
    }

    public function store(StoreRequest $request, string $jadwal_id)
    {
        $data = $request->validated();
        $data['jadwal_id'] = $jadwal_id;
        $data['guru_id'] = Auth::user()->guruProfile->id;

        if ($request->hasFile('file_soal')) {
            $file = $request->file('file_soal');
            $path = $file->store('soal_files', 'public');
            $data['file_soal'] = $path;
        }

        EvaluasiPembelajaran::create($data);

        return redirect()->route('guru.jadwal-mengajar.evaluasi-pembelajaran.index', ['jadwal_id' => $jadwal_id])
            ->with('success', 'Evaluasi Pembelajaran berhasil ditambahkan.');
    }

    public function show(string $jadwal_id, int $evaluasi_id)
    {
        $evaluasi = EvaluasiPembelajaran::findOrFail($evaluasi_id);
        return Inertia::render('guru/jadwal-mengajar/evaluasi-pembelajaran/View', [
            'jadwal_id' => $jadwal_id,
            'evaluasi' => $evaluasi,
        ]);
    }

    public function edit(string $jadwal_id, string $evaluasi_id)
    {
        $evaluasi = EvaluasiPembelajaran::findOrFail($evaluasi_id);
        $mapel = JadwalPelajaran::find($jadwal_id)->mataPelajaran;

        return Inertia::render('guru/jadwal-mengajar/evaluasi-pembelajaran/Edit', [
            'jadwal_id' => $jadwal_id,
            'guru_id' => Auth::user()->guruProfile->id,
            'nama_mapel' => $mapel->nama_mapel,
            'evaluasi' => $evaluasi,
        ]);
    }

    public function update(StoreRequest $request, string $jadwal_id, string $evaluasi_id)
    {
        $evaluasi = EvaluasiPembelajaran::findOrFail($evaluasi_id);
        $data = $request->validated();

        if ($request->hasFile('file_soal')) {
            // drop old file
            if ($evaluasi->file_soal) {
                $oldPath = str_replace('/storage/', '', $evaluasi->file_soal);
                Storage::disk('public')->delete($oldPath);
            }

            $file = $request->file('file_soal');
            $path = $file->store('soal_files', 'public');
            $data['file_soal'] = $path;
        } else {
            unset($data['file_soal']);
        }

        $evaluasi->update($data);

        return redirect()->route('guru.jadwal-mengajar.evaluasi-pembelajaran.index', ['jadwal_id' => $jadwal_id])
            ->with('success', 'Evaluasi Pembelajaran berhasil diperbarui.');
    }

    public function destroy(string $jadwal_id, string $evaluasi_id)
    {
        $evaluasi = EvaluasiPembelajaran::findOrFail($evaluasi_id);
        $evaluasi->delete();

        return redirect()->route('guru.jadwal-mengajar.evaluasi-pembelajaran.index', ['jadwal_id' => $jadwal_id])
            ->with('success', 'Evaluasi Pembelajaran berhasil dihapus.');
    }
}
