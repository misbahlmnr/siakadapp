<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kelas\StoreRequest;
use App\Http\Requests\Kelas\UpdateRequest;
use App\Models\Kelas;
use Carbon\Carbon;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class KelasController extends Controller
{
    public function get()
    {
        $query = Kelas::query();

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
        return Inertia::render('admin/kelas/Index');
    }

    public function create()
    {
        return Inertia::render('admin/kelas/Create');
    }

    public function store(StoreRequest $request)
    {
        Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'tingkat' => $request->tingkat,
            'tahun_ajaran' => $request->tahun_ajaran,
        ]);

        return to_route('admin.kelas.index')
            ->with('success', 'Kelas ' . $request->nama_kelas . ' berhasil ditambahkan');
    }

    public function edit(string $id)
    {
        return Inertia::render('admin/kelas/Edit', [
            'kelas' => Kelas::findOrFail($id)
        ]);
    }

    public function update(UpdateRequest $request, string $id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->update([
            'nama_kelas' => $request->nama_kelas,
            'tingkat' => $request->tingkat,
            'tahun_ajaran' => $request->tahun_ajaran,
        ]);

        return to_route('admin.kelas.index')
            ->with('success', 'Kelas ' . $request->nama_kelas . ' berhasil diubah');
    }

    public function destroy(string $id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return to_route('admin.kelas.index')
            ->with('success', 'Kelas ' . $kelas->nama_kelas . ' berhasil dihapus');
    }
}
