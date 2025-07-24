<?php

namespace App\Http\Controllers\Akademik;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Http\Requests\Siswa\StoreRequest;
use App\Models\Siswa;
use Yajra\DataTables\Facades\DataTables;

class SiswaController extends Controller
{
    public function get()
    {
        return DataTables::of(Siswa::query())->make(true);
    }

    public function index()
    {
        return Inertia::render('akademik/siswa/Index');
    }

    public function create()
    {
        return Inertia::render('akademik/siswa/Create');
    }

    public function store(StoreRequest $request)
    {
        Siswa::create([
            'nis' => $request->nis,
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'kelas' => $request->kelas,
            'tahun_masuk' => $request->tahun_masuk,
            'ttl' => $request->ttl,
            'alamat' => $request->alamat,
            'kontak_ortu' => $request->kontak_ortu,
            'status' => $request->status,
        ]);

        return to_route('akademik.siswa.index')->with('success', 'Data siswa berhasil ditambahkan');
    }

    public function show($id)
    {
        $siswa = Siswa::findOrFail($id);
        return Inertia::render('akademik/siswa/View', [
            'siswa' => $siswa,
        ]);
    }

    public function edit($id) {
        $siswa = Siswa::findOrFail($id);
        return Inertia::render('akademik/siswa/Edit', [
            'siswa' => $siswa,
        ]);
    }

    public function update() {
        // TODO: Handle update logic
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();

        return to_route('akademik.siswa.index')->with('success', 'Data siswa berhasil dihapus');
    }
}
