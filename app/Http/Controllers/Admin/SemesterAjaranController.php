<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SemesterAjaran\StoreRequest;
use App\Models\SemesterAjaran;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class SemesterAjaranController extends Controller
{
    public function index()
    {
        return Inertia::render('admin/semester/Index');
    }

    public function get(Request $request)
    {
        $query = SemesterAjaran::query();

        if ($request->has('status_aktif') && $request->status_aktif !== '') {
            $query->where('status_aktif', $request->status_aktif);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->make(true);
    }

    public function create()
    {
        return Inertia::render('admin/semester/Create');
    }

    public function store(StoreRequest $request)
    {
        SemesterAjaran::create($request->all());
        return to_route('admin.semester.index')->with('success', 'Semester ajaran berhasil ditambahkan');
    }

    public function edit(string $id)
    {
        $semester = SemesterAjaran::findOrFail($id);
        return Inertia::render('admin/semester/Edit', [
            'dataSemester' => $semester
        ]);
    }

    public function update(StoreRequest $request, string $id)
    {
        $semester = SemesterAjaran::findOrFail($id);
        $semester->update($request->all());
        return redirect()->route('admin.semester.index')->with('success', 'Semester ajaran berhasil diubah');
    }

    public function destroy(string $id)
    {
        $semester = SemesterAjaran::findOrFail($id);
        $semester->delete();
        return redirect()->route('admin.semester.index')->with('success', 'Semester ajaran berhasil dihapus');
    }
}
