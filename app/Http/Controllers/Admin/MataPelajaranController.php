<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MataPelajaran\StoreRequest;
use App\Http\Requests\MataPelajaran\UpdateRequest;
use App\Models\GuruProfile;
use App\Models\MataPelajaran;
use Carbon\Carbon;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class MataPelajaranController extends Controller
{
    public function get()
    {
        $query = MataPelajaran::query();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama_guru', function ($row) {
                return $row->guru->user->name;
            })
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)
                    ->setTimezone('Asia/Jakarta')
                    ->format('d-m-Y H:i');
            })
            ->make(true);
    }

    public function index()
    {
        return Inertia::render('admin/mata-pelajaran/Index');
    }
    
    public function create()
    {
        $dataGuru = GuruProfile::with('user:id,name')
            ->get()
            ->map(function ($guru) {
                return [
                    'id' => $guru->id,
                    'name' => $guru->user->name
                ];
            });
        
        return Inertia::render('admin/mata-pelajaran/Create', [
            'guruOptions' => $dataGuru
        ]);
    }

    public function store(StoreRequest $request)
    {
        MataPelajaran::create([
            'kode_mapel' => $request->kode_mapel,
            'nama_mapel' => $request->nama_mapel,
            'deskripsi' => $request->deskripsi,
            'guru_id' => $request->guru_id,
        ]);

        return to_route('admin.mata-pelajaran.index')
            ->with('success', 'Mata pelajaran ' . $request->nama_mapel . ' berhasil ditambahkan');
    }

    public function show(string $id)
    {
        $mataPelajaran = MataPelajaran::with('guru.user')->findOrFail($id);

        return Inertia::render('admin/mata-pelajaran/View', [
            'mataPelajaran' => [
                'id' => $mataPelajaran->id,
                'kode_mapel' => $mataPelajaran->kode_mapel,
                'nama_mapel' => $mataPelajaran->nama_mapel,
                'deskripsi' => $mataPelajaran->deskripsi,
                'guru' => $mataPelajaran->guru ? [
                    'id' => $mataPelajaran->guru->id,
                    'name' => $mataPelajaran->guru->user->name,
                ] : null,
                'created_at' => $mataPelajaran->created_at->toDateTimeString(),
                'updated_at' => $mataPelajaran->updated_at->toDateTimeString(),
            ],
        ]);
    }

    public function edit(string $id)
    {
        $mataPelajaran = MataPelajaran::findOrFail($id);

        $dataGuru = GuruProfile::with('user:id,name')
            ->select('id', 'user_id')
            ->get()
            ->map(function ($guru) {
                return [
                    'id' => $guru->id,
                    'name' => $guru->user->name,
                ];
            });

        return Inertia::render('admin/mata-pelajaran/Edit', [
            'mataPelajaran' => $mataPelajaran,
            'guruOptions' => $dataGuru
        ]);
    }

    public function update(UpdateRequest $request, string $id)
    {
        $mataPelajaran = MataPelajaran::findOrFail($id);
        $mataPelajaran->update([
            'kode_mapel' => $request->kode_mapel,
            'nama_mapel' => $request->nama_mapel,
            'deskripsi' => $request->deskripsi,
            'guru_id' => $request->guru_id,
        ]);

        return to_route('admin.mata-pelajaran.index')
            ->with('success', 'Mata pelajaran ' . $request->nama_mapel . ' berhasil diubah');
    }

    public function destroy(string $id)
    {
        $mataPelajaran = MataPelajaran::findOrFail($id);
        $mataPelajaran->delete();

        return to_route('admin.mata-pelajaran.index')
            ->with('success', 'Mata pelajaran ' . $mataPelajaran->nama_mapel . ' berhasil dihapus');
    }
}
