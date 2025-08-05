<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MataPelajaran\StoreRequest;
use App\Http\Requests\MataPelajaran\UpdateRequest;
use App\Models\MataPelajaran;
use App\Models\User;
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
        $dataGuru = User::where('role', 'guru')->select('id', 'name')->get();
        
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

    public function edit(string $id)
    {
        $mataPelajaran = MataPelajaran::findOrFail($id);
        $dataGuru = User::where('role', 'guru')->select('id', 'name')->get();

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
}
