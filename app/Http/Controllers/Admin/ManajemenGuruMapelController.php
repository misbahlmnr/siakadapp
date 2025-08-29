<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ManajemenGuruMatpel\StoreRequest;
use App\Http\Requests\ManajemenGuruMatpel\UpdateRequest;
use App\Models\GuruMataPelajaran;
use App\Models\GuruProfile;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class ManajemenGuruMapelController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/manajemen-guru-matpel/Index');
    }

    public function get()
    {
        $query = GuruMataPelajaran::with(['guru', 'mataPelajaran']);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama_guru', fn ($row) => $row->guru->user->name)
            ->addColumn('mata_pelajaran', fn ($row) => $row->mataPelajaran->nama_mapel)
            ->make(true);
    }

    public function create()
    {
        $guruList = GuruProfile::with('user:id,name')->get()->map(fn ($guru) => [
            'id' => $guru->id,
            'name' => $guru->user->name
        ]);
        $matpelList = MataPelajaran::select('id', 'nama_mapel')->get();
        
        return Inertia::render('admin/manajemen-guru-matpel/Create', [
            'guruList' => $guruList,
            'matpelList' => $matpelList
        ]);
    }

    public function store(StoreRequest $request)
    {
        GuruMataPelajaran::create([
            'guru_id' => $request->guru_id,
            'matpel_id' => $request->matpel_id,
        ]);

        return to_route('admin.manajemen-guru-mapel.index')
            ->with('success', 'Guru Mata Pelajaran berhasil ditambahkan');
    }

    public function edit(string $id)
    {
        $guruMapel = GuruMataPelajaran::findOrFail($id);
        $guruList = GuruProfile::with('user:id,name')->get()->map(fn ($guru) => [
            'id' => $guru->id,
            'name' => $guru->user->name
        ]);
        $matpelList = MataPelajaran::select('id', 'nama_mapel')->get();

        return Inertia::render('admin/manajemen-guru-matpel/Edit', [
            'guruMapel' => $guruMapel,
            'guruList' => $guruList,
            'matpelList' => $matpelList
        ]);
    }

    public function update(UpdateRequest $request, string $id)
    {
        $guruMapel = GuruMataPelajaran::findOrFail($id);
        $guruMapel->update([
            'guru_id' => $request->guru_id,
            'matpel_id' => $request->matpel_id,
        ]);

        return to_route('admin.manajemen-guru-mapel.index')
            ->with('success', 'Guru Mata Pelajaran berhasil diubah');
    }

    public function destroy(string $id)
    {
        $guruMapel = GuruMataPelajaran::findOrFail($id);
        $guruMapel->delete();

        return to_route('admin.manajemen-guru-mapel.index')
            ->with('success', 'Guru Mata Pelajaran ' . $guruMapel->mataPelajaran->nama_mapel . ' berhasil dihapus');
    }
}
