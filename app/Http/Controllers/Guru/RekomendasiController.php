<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\RekomendasiRequest; // Assuming you will create this request
use App\Models\{RekomendasiMateri, RekomendasiMateriManual};
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class RekomendasiController extends Controller
{
    public function index()
    {
        return Inertia::render('guru/rekomendasi/Index');
    }

    public function get()
    {
        $rekomendasi = RekomendasiMateri::with(['materi', 'evaluasiPembelajaran'])->get();
        return DataTables::of($rekomendasi)
            ->addIndexColumn()
            ->make();
    }

    public function create()
    {
        return Inertia::render('guru/rekomendasi/Create');
    }

    public function store(RekomendasiRequest $request)
    {
        RekomendasiMateri::create($request->validated());
        return redirect()->route('guru.adaptive-learning.rekomendasi.index')->with('success', 'Rekomendasi berhasil ditambahkan');
    }

    public function edit($id)
    {
        $rekomendasi = RekomendasiMateri::findOrFail($id);
        return Inertia::render('guru/rekomendasi/Edit', ['rekomendasi' => $rekomendasi]);
    }

    public function update(RekomendasiRequest $request, $id)
    {
        $rekomendasi = RekomendasiMateri::findOrFail($id);
        $rekomendasi->update($request->validated());
        return redirect()->route('guru.adaptive-learning.rekomendasi.index')->with('success', 'Rekomendasi berhasil diubah');
    }

    public function destroy($id)
    {
        RekomendasiMateri::destroy($id);
        return redirect()->route('guru.adaptive-learning.rekomendasi.index')->with('success', 'Rekomendasi berhasil dihapus');
    }
}
