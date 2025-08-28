<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdaptiveRules\StoreRequest;
use App\Http\Requests\AdaptiveRules\UpdateRequest;
use App\Models\{AdaptiveRules, MataPelajaran, MateriPelajaran};
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class AdaptiveRulesController extends Controller
{
    public function index()
    {
        return Inertia::render('guru/adaptive-rules/Index');
    }

    public function get()
    {
        $rules = AdaptiveRules::with(['mataPelajaran','materi','dibuatOleh']);

        return DataTables::of($rules)
            ->addIndexColumn()
            ->addColumn('mata_pelajaran', fn ($row) => $row->mataPelajaran->nama_mapel ?? '-')
            ->addColumn('nama_materi', fn ($row) => $row->materi->judul_materi ?? '-')
            ->addColumn('dibuat_oleh', fn ($row) => $row->dibuatOleh->name ?? '-')
            ->make(true);
    }

    public function create()
    {
        $matpels = MataPelajaran::all();
        $materis = MateriPelajaran::all();

        return Inertia::render('guru/adaptive-rules/Create', [
            'matpels' => $matpels,
            'materis' => $materis
        ]);
    }

    public function store(StoreRequest $request)
    {
        AdaptiveRules::create([
            'matpel_id' => $request->matpel_id,
            'materi_id' => $request->materi_id,
            'dibuat_oleh' => Auth::user()->id,
            'operator' => $request->operator,
            'nilai_batas' => $request->nilai_batas,
        ]);

        return to_route('guru.adaptive-rules.index')->with('success', 'Adaptive Rule berhasil ditambahkan');
    }

    public function edit(string $id)
    {
        $rule = AdaptiveRules::with(['mataPelajaran','materi'])->findOrFail($id);
        $matpels = MataPelajaran::all();
        $materis = MateriPelajaran::all();

        return Inertia::render('guru/adaptive-rules/Edit', [
            'rule' => $rule,
            'matpels' => $matpels,
            'materis' => $materis
        ]);
    }

    public function update(UpdateRequest $request, int $id)
    {
        AdaptiveRules::find($id)->update([
            'matpel_id' => $request->matpel_id,
            'materi_id' => $request->materi_id,
            'dibuat_oleh' => Auth::user()->id,
            'operator' => $request->operator,
            'nilai_batas' => $request->nilai_batas,
        ]);

        return to_route('guru.adaptive-rules.index')->with('success', 'Adaptive Rule berhasil diubah');
    }

    public function destroy(int $id)
    {
        AdaptiveRules::find($id)->delete();

        return to_route('guru.adaptive-rules.index')->with('success', 'Adaptive Rule berhasil dihapus');
    }
}
