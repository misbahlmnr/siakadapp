<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MataPelajaran\StoreRequest;
use App\Http\Requests\MataPelajaran\UpdateRequest;
use App\Models\GuruProfile;
use App\Models\MataPelajaran;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class MataPelajaranController extends Controller
{
    public function get()
    {
        $query = MataPelajaran::with('guru.user');

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('guru_pengampu', fn ($row) => $row->guru->user->name ?? 'Belum ada guru')
            ->make(true);
    }

    public function index()
    {
        return Inertia::render('admin/mata-pelajaran/Index');
    }
    
    public function create()
    {
        return Inertia::render('admin/mata-pelajaran/Create', [
            'guruList' => GuruProfile::with('user:id,name')->get()->map(fn ($guru) => [
                'id' => $guru->id,
                'name' => $guru->user->name
            ])
        ]);
    }

    public function store(StoreRequest $request)
    {
        MataPelajaran::create([
            'kode_mapel' => $request->kode_mapel,
            'nama_mapel' => $request->nama_mapel,
            'guru_id' => $request->guru_id,
        ]);

        return to_route('admin.mata-pelajaran.index')
            ->with('success', 'Mata pelajaran ' . $request->nama_mapel . ' berhasil ditambahkan');
    }

    public function show(string $id)
    {
        $mataPelajaran = MataPelajaran::findOrFail($id);

        return Inertia::render('admin/mata-pelajaran/View', $mataPelajaran);
    }

    public function edit(string $id)
    {
        return Inertia::render('admin/mata-pelajaran/Edit', [
            'mataPelajaran' => MataPelajaran::findOrFail($id),
            'guruList' => GuruProfile::with('user:id,name')->get()->map(fn ($guru) => [
                'id' => $guru->id,
                'name' => $guru->user->name
            ])
        ]);
    }

    public function update(UpdateRequest $request, string $id)
    {
        $mataPelajaran = MataPelajaran::findOrFail($id);
        $mataPelajaran->update([
            'kode_mapel' => $request->kode_mapel,
            'nama_mapel' => $request->nama_mapel,
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
