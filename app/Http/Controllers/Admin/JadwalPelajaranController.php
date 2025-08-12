<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\JadwalPelajaran\StoreRequest;
use App\Http\Requests\JadwalPelajaran\UpdateRequest;
use App\Models\GuruProfile;
use App\Models\JadwalPelajaran;
use App\Models\Kelas;
use App\Models\MataPelajaran;
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
            ->addColumn('nama_kelas', fn ($row) => $row->kelas->nama_kelas)
            ->addColumn('mata_pelajaran', fn ($row) => $row->mataPelajaran->nama_mapel)
            ->addColumn('nama_guru', fn ($row) => $row->guru->user->name)
            ->editColumn('waktu', fn ($row) => $this->formatWaktu($row->jam_mulai, $row->jam_selesai))
            ->editColumn('created_at', fn ($row) => $this->formatCreatedAt($row->created_at))
            ->make(true);
    }

    private function formatWaktu(string $jamMulai, string $jamSelesai): string
    {
        return Carbon::parse($jamMulai)
            ->format('H:i') . ' - ' . Carbon::parse($jamSelesai)
            ->format('H:i');
    }

    // TODO: Move to helper class
    private function formatCreatedAt(string $createdAt): string
    {
        return Carbon::parse($createdAt)
            ->setTimezone('Asia/Jakarta')
            ->format('d-m-Y H:i');
    }

    public function index()
    {
        return Inertia('admin/jadwal-pelajaran/Index');
    }

    public function create()
    {
        return Inertia::render('admin/jadwal-pelajaran/Create', [
            'kelasList' => Kelas::select('id', 'nama_kelas')->get(),
            'mapelList' => MataPelajaran::select('id', 'nama_mapel')->get(),
            'guruList' => GuruProfile::with('user:id,name')->get()->map(function ($guru) {
                return [
                    'id' => $guru->id,
                    'nama' => $guru->user->name,
                ];
            }),
        ]);
    }

    public function store(StoreRequest $request)
    {
        // Cek apakah jadwal bentrok
        $isBentrok = JadwalPelajaran::where('hari', $request->hari)
            ->where('guru_id', $request->guru_id)
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

        JadwalPelajaran::create($request->all());

        return to_route('admin.jadwal-pelajaran.index')
            ->with('success', 'Jadwal pelajaran berhasil ditambahkan');
    }


    public function edit(string $id)
    {
        return Inertia::render('admin/jadwal-pelajaran/Edit', [
            'jadwal' => JadwalPelajaran::find($id),
            'kelasList' => Kelas::select('id', 'nama_kelas')->get(),
            'mapelList' => MataPelajaran::select('id', 'nama_mapel')->get(),
            'guruList' => GuruProfile::with('user:id,name')->get()->map(function ($guru) {
                return [
                    'id' => $guru->id,
                    'nama' => $guru->user->name,
                ];
            })
        ]);
    }

    public function update(UpdateRequest $request, int $id)
    {
        // Cek bentrok jadwal (kecuali jadwal yang sedang diupdate)
        $bentrok = JadwalPelajaran::where('hari', $request->hari)
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

        if ($bentrok) {
            return back()->with('error', 'Jadwal bentrok dengan jadwal lain untuk guru ini.')->withInput();
        }

        JadwalPelajaran::find($id)->update($request->all());

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
