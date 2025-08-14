<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\MateriPembelajaran\{StoreRequest, UpdateRequest};
use App\Models\JadwalPelajaran;
use App\Models\MateriPembelajaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class MateriPembelajaranController extends Controller
{
    public function get(string $jadwal_id)
    {
        $materi = MateriPembelajaran::where('jadwal_id', $jadwal_id)->orderBy('pertemuan_ke', 'asc')->get();

        return DataTables::of($materi)
            ->addIndexColumn()
            ->editColumn('pertemuan_ke', function ($row) {
                return "Pertemuan " . $row->pertemuan_ke;
            })
            ->make(true);
    }

    public function index(string $jadwal_id)
    {
        return Inertia::render('guru/materi-pembelajaran/Index', [
            'jadwal_id' => $jadwal_id,
            'nama_mapel' => JadwalPelajaran::find($jadwal_id)->mataPelajaran->nama_mapel,
            'materi' => MateriPembelajaran::where('jadwal_id', $jadwal_id)
                ->orderBy('pertemuan_ke', 'asc')
                ->get()
                ->map(function ($materi) {
                    return [
                        'id' => $materi->id,
                        'pertemuan_ke' => $materi->pertemuan_ke,
                        'judul' => $materi->judul,
                        'deskripsi' => $materi->deskripsi,
                        'file_path' => $materi->file_path,
                        'link_file' => $materi->link_file 
                    ];
                }),
        ]);
    }

    public function create(string $jadwal_id)
    {
        return Inertia::render('guru/materi-pembelajaran/Create', [
            'jadwal_id' => $jadwal_id,
            'guru_id' => Auth::user()->guruProfile->id,
        ]);
    }

    public function store(StoreRequest $request, string $jadwal_id)
    {
        $validated = $request->validated();

        // check if pertemuan ke already exists
        $exists = MateriPembelajaran::where('jadwal_id', $jadwal_id)
            ->where('pertemuan_ke', $validated['pertemuan_ke'])
            ->exists();

        if ($exists) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Pertemuan ke '.$validated['pertemuan_ke'].' untuk jadwal ini sudah ada.');
        }

        // Handle upload file if present
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $path = $file->store('materi_files', 'public');
            $validated['file_path'] = $path;
        }

        MateriPembelajaran::create($validated);

        return redirect()
            ->route('guru.jadwal-mengajar.materi.index', ['jadwal_id' => $jadwal_id])
            ->with('success', 'Materi berhasil ditambahkan!');
    }

    public function show(string $jadwal_id, string $materi_id)
    {
        return Inertia::render('guru/materi-pembelajaran/View', [
            'jadwal' => [
                'nama_mapel' => JadwalPelajaran::find($jadwal_id)->mataPelajaran->nama_mapel,
                'kelas' => JadwalPelajaran::find($jadwal_id)->kelas->nama_kelas
            ],
            'guru' => [
                'nama' => Auth::user()->name,
            ],
            'materi' => MateriPembelajaran::find($materi_id),
        ]);
    }

    public function edit(string $jadwal_id, string $materi_id)
    {
        $materi = MateriPembelajaran::find($materi_id);

        return inertia('guru/materi-pembelajaran/Edit', [
            'jadwal_id' => $jadwal_id,
            'guru_id' => $materi->guru_id,
            'materi' => [
                'id' => $materi->id,
                'pertemuan_ke' => $materi->pertemuan_ke,
                'judul' => $materi->judul,
                'deskripsi' => $materi->deskripsi,
                'file_path' => $materi->file_path,
                'link_file' => $materi->link_file,
            ],
        ]);
    }

    public function update(UpdateRequest $request, string $jadwal_id, string $materi_id)
    {
        $validated = $request->validated();

        // check duplicate pertemuan ke
        $exists = MateriPembelajaran::where('jadwal_id', $jadwal_id)
            ->where('pertemuan_ke', $validated['pertemuan_ke'])
            ->where('id', '!=', (int) $materi_id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Pertemuan ke '.$validated['pertemuan_ke'].' untuk jadwal ini sudah ada.');
        }

        $materi = MateriPembelajaran::find($materi_id);

        // if file_path is updated
        if ($request->hasFile('file_path')) {
            // drop old file
            if ($materi->file_path) {
                $oldPath = str_replace('/storage/', '', $materi->file_path);
                Storage::disk('public')->delete($oldPath);
            }

            $file = $request->file('file_path');
            $path = $file->store('materi_files', 'public');
            $validated['file_path'] = '/storage/' . $path;
        } else {
            unset($validated['file_path']);
        }

        $materi->update($validated);

        return redirect()
            ->route('guru.jadwal-mengajar.materi.index', ['jadwal_id' => $jadwal_id])
            ->with('success', 'Materi berhasil diperbarui!');
    }

    public function destroy(string $jadwal_id, string $materi_id)
    {
        $materi = MateriPembelajaran::find($materi_id);
        $materi->delete();

        return redirect()
            ->route('guru.jadwal-mengajar.materi.index', ['jadwal_id' => $jadwal_id])
            ->with('success', 'Materi berhasil dihapus!');
    }
}
