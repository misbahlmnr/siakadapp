<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\JadwalPelajaran\StoreRequest;
use App\Http\Requests\JadwalPelajaran\UpdateRequest;
use App\Models\GuruMataPelajaran;
use App\Models\GuruProfile;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\SemesterAjaran;
use Carbon\Carbon;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class JadwalPelajaranController extends Controller
{
    public function get()
    {
        $jadwalPelajaran = JadwalPelajaran::query();

        return DataTables::of($jadwalPelajaran)
            ->addIndexColumn()
            ->addColumn('kelas', fn ($row) => $row->kelas->nama_kelas)
            ->addColumn('mata_pelajaran', fn ($row) => $row->guruMatpel->mataPelajaran->nama_mapel)
            ->addColumn('guru', fn ($row) => $row->guruMatpel->guru->user->name)
            ->addColumn('semester', fn ($row) => $row->semesterAjaran->semester)
            ->addColumn('tahun_ajaran', fn ($row) => $row->semesterAjaran->tahun_ajaran)
            ->editColumn('jam', fn ($row) => formatStartEndTime($row->jam_mulai, $row->jam_selesai))
            ->make(true);
    }

    public function index()
    {
        return Inertia('admin/jadwal-pelajaran/Index');
    }

    public function create()
    {
        return Inertia::render('admin/jadwal-pelajaran/Create', [
            'kelasList' => Kelas::select('id', 'nama_kelas')->get(),

            'guruMapelList' => GuruMataPelajaran::with([
                'guru.user:id,name',
                'mataPelajaran:id,nama_mapel'
            ])->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->guru->user->name . ' - ' . $item->mataPelajaran->nama_mapel
                ];
            }),

            'semesterDanTahunAjaranList' => SemesterAjaran::where('status_aktif', true)
                ->get()
                ->map(fn ($se) => [
                    'id' => $se->id,
                    'semester' => $se->semester,
                    'tahun_ajaran' => $se->tahun_ajaran
                ])
        ]);
    }


    public function store(StoreRequest $request)
    {
        $guruMapel = GuruMataPelajaran::with('guru')->findOrFail($request->guru_matpel_id);

        $guruId = $guruMapel->guru_id;

        // Cek apakah jadwal bentrok
        $isBentrok = JadwalPelajaran::where('hari', $request->hari)
            ->whereHas('guruMatpel', function ($query) use ($guruId) {
                $query->where('guru_id', $guruId);
            })
            ->where(function($query) use ($request) {
                $query->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                    ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                    ->orWhere(function($q) use ($request) {
                        $q->where('jam_mulai', '<=', $request->jam_mulai)
                            ->where('jam_selesai', '>=', $request->jam_selesai);
                    });
            })
            ->exists();

        if ($isBentrok) {
            return back()->with('error', 'Jadwal bentrok dengan jadwal lain untuk guru ini.');
        }

        JadwalPelajaran::create([
            'kelas_id' => $request->kelas_id,
            'guru_matpel_id' => $request->guru_matpel_id,
            'semester_ajaran_id' => $request->semester_ajaran_id,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return to_route('admin.jadwal-pelajaran.index')
            ->with('success', 'Jadwal pelajaran berhasil ditambahkan');
    }

    public function show(string $id)
    {
        $jadwal = JadwalPelajaran::with('kelas', 'guruMatpel.mataPelajaran', 'guruMatpel.guru.user', 'semesterAjaran')->find($id);

        return Inertia::render('admin/jadwal-pelajaran/Show', [
           'jadwal' => [
               'id' => $jadwal->id,
               'kelas' => $jadwal->kelas->nama_kelas,
               'mata_pelajaran' => $jadwal->guruMatpel->mataPelajaran->nama_mapel,
               'nama_guru' => $jadwal->guruMatpel->guru->user->name,
               'hari' => $jadwal->hari,
               'jam' => formatStartEndTime($jadwal->jam_mulai, $jadwal->jam_selesai),
               'semester' => $jadwal->semesterAjaran->semester,
               'tahun_ajaran' => $jadwal->semesterAjaran->tahun_ajaran
           ]
        ]);
    }

    public function edit(string $id)
    {
        $jadwal = JadwalPelajaran::find($id);

        return Inertia::render('admin/jadwal-pelajaran/Edit', [
            'jadwal' => [
                'id' => $jadwal->id,
                'kelas_id' => $jadwal->kelas_id,
                'guru_matpel_id' => $jadwal->guru_matpel_id,
                'semester_ajaran_id' => $jadwal->semester_ajaran_id,
                'hari' => $jadwal->hari,
                'jam_mulai' => Carbon::parse($jadwal->jam_mulai)->format('H:i'),
                'jam_selesai' => Carbon::parse($jadwal->jam_selesai)->format('H:i'),
            ],
            'kelasList' => Kelas::select('id', 'nama_kelas')->get(),
            'guruMapelList' => GuruMataPelajaran::with([
                'guru.user:id,name',
                'mataPelajaran:id,nama_mapel'
            ])->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->guru->user->name . ' - ' . $item->mataPelajaran->nama_mapel
                ];
            }),
            'semesterDanTahunAjaranList' => SemesterAjaran::where('status_aktif', true)->get()->map(fn ($se) => [
                'id' => $se->id,
                'semester' => $se->semester,
                'tahun_ajaran' => $se->tahun_ajaran
            ])
        ]);
    }

    public function update(UpdateRequest $request, int $id)
    {
        $guruMapel = GuruMataPelajaran::with('guru')->findOrFail($request->guru_matpel_id);

        $guruId = $guruMapel->guru_id;

        // Cek bentrok jadwal (kecuali jadwal yang sedang diupdate)
        $isBentrok = JadwalPelajaran::where('hari', $request->hari)
            ->whereHas('guruMatpel', function ($query) use ($guruId) {
                $query->where('guru_id', $guruId);
            })
            ->where('kelas_id', $request->kelas_id)
            ->where('id', '!=', $id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('jam_mulai', [$request->jam_mulai, $request->jam_selesai])
                    ->orWhereBetween('jam_selesai', [$request->jam_mulai, $request->jam_selesai])
                    ->orWhere(function ($q) use ($request) {
                        $q->where('jam_mulai', '<=', $request->jam_mulai)
                            ->where('jam_selesai', '>=', $request->jam_selesai);
                    });
            })
            ->exists();

        if ($isBentrok) {
            return back()->with('error', 'Jadwal bentrok dengan jadwal lain untuk guru ini.')->withInput();
        }

        JadwalPelajaran::find($id)->update([
            'kelas_id' => $request->kelas_id,
            'guru_matpel_id' => $request->guru_matpel_id,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'semester' => $request->semester,
            'tahun_ajaran' => $request->tahun_ajaran
        ]);

        return to_route('admin.jadwal-pelajaran.index')
            ->with('success', 'Jadwal pelajaran berhasil diubah');
    }

    public function destroy(int $id)
    {
        JadwalPelajaran::find($id)->delete();

        return to_route('admin.jadwal-pelajaran.index')
            ->with('success', 'Jadwal pelajaran berhasil dihapus');
    }
}
