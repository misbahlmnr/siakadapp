<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\MateriPelajaran\{StoreRequest, UpdateRequest};
use App\Models\JadwalPelajaran;
use App\Models\MateriPelajaran;
use App\Models\SemesterAjaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class MateriPelajaranController extends Controller
{
    public function get(string $jadwal_id)
    {
        $materi = MateriPelajaran::where('jadwal_id', $jadwal_id)->orderBy('pertemuan_ke', 'asc')->get();

        return DataTables::of($materi)
            ->addIndexColumn()
            ->editColumn('pertemuan_ke', fn ($row) => 'Pertemuan ke '.$row->pertemuan_ke)
            ->editColumn('file_materi', fn ($row) => $row->file_materi ? '<a href="'.Storage::url($row->file_materi).'" target="_blank"  class="inline-block px-2 py-1 bg-blue-500 text-white rounded-lg">Download</a>' : 'Tidak ada file')
            ->editColumn('link_materi', fn ($row) => $row->link_materi ? '<a href="'.$row->link_materi.'" target="_blank" class="inline-block px-2 py-1 bg-blue-500 text-white rounded-lg">Buka Link</a>' : 'Tidak ada link')
            ->rawColumns(['file_materi', 'link_materi'])
            ->make(true);
    }

    public function index(string $jadwal_id)
    {
        return Inertia::render('guru/jadwal-mengajar/materi/Index', [
            'jadwal_id' => $jadwal_id,
            'nama_mapel' => JadwalPelajaran::find($jadwal_id)->mataPelajaran->nama_mapel,
        ]);
    }

    public function create(string $jadwal_id)
    {
        return Inertia::render('guru/jadwal-mengajar/materi/Create', [
            'jadwal_id' => $jadwal_id,
            'guru_id' => Auth::user()->guruProfile->id,
            'semesterDanTahunAjaranList' => SemesterAjaran::where('status_aktif', true)->get()->map(fn ($se) => [
                'id' => $se->id,
                'semester' => $se->semester,
                'tahun_ajaran' => $se->tahun_ajaran
            ])
        ]);
    }

    public function store(StoreRequest $request, string $jadwal_id)
    {
        $validated = $request->validated();

        // check if pertemuan ke already exists
        $exists = MateriPelajaran::where('jadwal_id', $jadwal_id)
            ->where('pertemuan_ke', $validated['pertemuan_ke'])
            ->exists();

        if ($exists) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Pertemuan ke '.$validated['pertemuan_ke'].' untuk jadwal ini sudah ada.');
        }

        // Handle upload file if present
        if ($request->hasFile('file_materi')) {
            $file = $request->file('file_materi');
            $path = $file->store('materi_files', 'public');
            $validated['file_materi'] = $path;
        }

        MateriPelajaran::create($validated);

        return redirect()
            ->route('guru.jadwal-mengajar.materi.index', ['jadwal_id' => $jadwal_id])
            ->with('success', 'Materi berhasil ditambahkan!');
    }

    public function show(string $jadwal_id, string $materi_id)
    {
        return Inertia::render('guru/jadwal-mengajar/materi/View', [
            'jadwal' => [
                'nama_mapel' => JadwalPelajaran::find($jadwal_id)->mataPelajaran->nama_mapel,
                'kelas' => JadwalPelajaran::find($jadwal_id)->kelas->nama_kelas
            ],
            'guru' => [
                'nama' => Auth::user()->name,
            ],
            'materi' => MateriPelajaran::with('semesterAjaran')->find($materi_id),
        ]);
    }

    public function edit(string $jadwal_id, string $materi_id)
    {
        $materi = MateriPelajaran::find($materi_id);

        return Inertia::render('guru/jadwal-mengajar/materi/Edit', [
            'jadwal_id' => $jadwal_id,
            'guru_id' => $materi->guru_id,
            'materi' => [
                'id' => $materi->id,
                'semester_ajaran_id' => $materi->semester_ajaran_id,
                'pertemuan_ke' => $materi->pertemuan_ke,
                'judul_materi' => $materi->judul_materi,
                'deskripsi' => $materi->deskripsi,
                'file_materi' => $materi->file_materi ? Storage::url($materi->file_materi) : null,
                'link_materi' => $materi->link_materi,
            ],
            'semesterDanTahunAjaranList' => SemesterAjaran::where('status_aktif', true)->get()->map(fn ($se) => [
                'id' => $se->id,
                'semester' => $se->semester,
                'tahun_ajaran' => $se->tahun_ajaran
            ])
        ]);
    }

    public function update(UpdateRequest $request, string $jadwal_id, string $materi_id)
    {
        $validated = $request->validated();

        // check duplicate pertemuan ke
        $exists = MateriPelajaran::where('jadwal_id', $jadwal_id)
            ->where('pertemuan_ke', $validated['pertemuan_ke'])
            ->where('id', '!=', (int) $materi_id)
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Pertemuan ke '.$validated['pertemuan_ke'].' untuk jadwal ini sudah ada.');
        }

        $materi = MateriPelajaran::find($materi_id);

        // if file_path is updated
        if ($request->hasFile('file_materi')) {
            // drop old file
            if ($materi->file_materi) {
                $oldPath = str_replace('/storage/', '', $materi->file_materi);
                Storage::disk('public')->delete($oldPath);
            }

            $file = $request->file('file_materi');
            $path = $file->store('materi_files', 'public');
            $validated['file_materi'] = '/storage/' . $path;
        } else {
            unset($validated['file_materi']);
        }

        $materi->update($validated);

        return redirect()
            ->route('guru.jadwal-mengajar.materi.index', ['jadwal_id' => $jadwal_id])
            ->with('success', 'Materi berhasil diperbarui!');
    }

    public function destroy(string $jadwal_id, string $materi_id)
    {
        $materi = MateriPelajaran::find($materi_id);
        $materi->delete();

        return redirect()
            ->route('guru.jadwal-mengajar.materi.index', ['jadwal_id' => $jadwal_id])
            ->with('success', 'Materi berhasil dihapus!');
    }
}
