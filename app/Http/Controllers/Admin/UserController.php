<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ManajemenUser\Admin\StoreRequest;
use App\Http\Requests\ManajemenUser\Admin\UpdateRequest;
use App\Models\GuruProfile;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\SiswaProfile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->route('role');

        return inertia('admin/manajemen-user/'.$role.'/Index', [
            'role' => $role
        ]);
    }

    public function get(string $role)
    {
        $query = User::where('role', $role)
            ->with($role === "siswa" ? "siswaProfile" : "guruProfile")
            ->orderBy('name');

        return DataTables::of($query)
            ->addIndexColumn()

            // Kolom untuk siswa
            ->addColumn('nisn', function ($row) use ($role) {
                return $role === 'siswa' ? ($row->siswaProfile->nisn ?? '-') : '-';
            })
            ->addColumn('kelas', function ($row) use ($role) {
                if ($role === 'siswa' && $row->siswaProfile?->kelas) {
                    return $row->siswaProfile->kelas->nama_kelas ?? '-';
                }
                return "-";
            })
            ->addColumn('tahun_masuk', function ($row) use ($role) {
                return $role === 'siswa' ? ($row->siswaProfile->tahun_masuk ?? '-') : '-';
            })
            ->addColumn('alamat', function ($row) use ($role) {
                if ($role === 'siswa') {
                    return $row->siswaProfile->alamat ?? '-';
                } elseif ($role === 'guru') {
                    return $row->guruProfile->alamat ?? '-';
                }
                return '-';
            })
            ->addColumn('kontak_ortu', function ($row) use ($role) {
                return $role === 'siswa' ? ($row->siswaProfile->kontak_ortu ?? '-') : '-';
            })
            ->addColumn('status', function ($row) use ($role) {
                return $role === 'siswa' ? ($row->siswaProfile->status ?? '-') : '-';
            })

            // Kolom untuk guru
            ->addColumn('nip', function ($row) use ($role) {
                return $role === 'guru' ? ($row->guruProfile->nip ?? '-') : '-';
            })
            ->addColumn('mapel', function ($row) use ($role) {
                if ($role === 'guru' && $row->guruProfile?->mataPelajaran) {
                    return $row->guruProfile->mataPelajaran->nama_mapel ?? '-';
                }
                return "-";
            })
            ->addColumn('no_telp', function ($row) use ($role) {
                return $role === 'guru' ? ($row->guruProfile->no_telp ?? '-') : '-';
            })
            ->addColumn('status_guru', function ($row) use ($role) {
                return $role === 'guru' ? ($row->guruProfile->status_guru ?? '-') : '-';
            })
            ->addColumn('tanggal_masuk', function ($row) use ($role) {
                return $role === 'guru'
                    ? (isset($row->guruProfile->tanggal_masuk)
                        ? Carbon::parse($row->guruProfile->tanggal_masuk)
                            ->setTimezone('Asia/Jakarta')
                            ->format('d-m-Y')
                        : '-')
                    : '-';
            })

            // Created at
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)
                    ->setTimezone('Asia/Jakarta')
                    ->format('d-m-Y H:i');
            })
            ->make(true);
    }

    public function create(Request $request)
    {
        $roleName = $request->route('role');

        $viewData = [
            'role' => $roleName
        ];

        if ($roleName === 'guru') {
            $viewData['mataPelajaran'] = MataPelajaran::all();
        } elseif ($roleName === 'siswa') {
            $viewData['kelas'] = Kelas::all();
        }

        return inertia("admin/manajemen-user/{$roleName}/Create", $viewData);
    }

    public function show(string $role, string $id)
    {
        $relation = self::getRelation($role);
        $query = User::query();

        if ($relation) {
            $query->with($relation);
        }


        $user = $query->findOrFail($id);

        // Inject data relasi ke property dinamis
        if ($role === 'siswa' && $user->siswaProfile) {
            foreach ($user->siswaProfile->getAttributes() as $key => $value) {
                $user->setAttribute($key, $value);
            }
        } elseif ($role === 'guru' && $user->guruProfile) {
            foreach ($user->guruProfile->getAttributes() as $key => $value) {
                $user->setAttribute($key, $value);
            }
        }

        return inertia('admin/manajemen-user/'.$role.'/View', [
            'role' => $role,
            'user' => $user
        ]);
    }

    public function store(StoreRequest $request, string $role)
    {
        switch ($role) {
            case 'guru': 
                User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => $request->role,
                ]);

                GuruProfile::create([
                    'user_id' => User::latest()->first()->id,
                    'nip' => $request->nip,
                    'no_telp' => $request->no_telp,
                    'alamat' => $request->alamat,
                    'status_guru' => $request->status_guru,
                    'tanggal_masuk' => $request->tanggal_masuk,
                    'matpel_id' => $request->matpel_id
                ]);

                break;

            case 'siswa':
                User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => $request->role,
                ]);

                SiswaProfile::create([
                    'user_id' => User::latest()->first()->id,
                    'kelas_id' => $request->kelas_id,
                    'nisn' => $request->nisn,
                    'tahun_masuk' => $request->tahun_masuk,
                    'alamat' => $request->alamat,
                    'kontak_ortu' => $request->kontak_ortu,
                    'status' => $request->status,
                ]);

                break;

            default: 
                User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role' => $request->role,
                ]);
        }

        return to_route('admin.users.index', $role)
            ->with('success', 'Data '.$role.' berhasil ditambahkan');
    }

    public function edit(string $role, string $id)
    {
        $relation = self::getRelation($role);
        $query = User::query();

        if ($relation) {
            $query->with($relation);
        }


        $user = $query->findOrFail($id);

        // Inject data relasi ke property dinamis
        if ($role === 'siswa' && $user->siswaProfile) {
            foreach ($user->siswaProfile->getAttributes() as $key => $value) {
                $user->setAttribute($key, $value);
            }

            return inertia('admin/manajemen-user/siswa/Edit', [
                'role' => $role,
                'user' => $user,
                'kelas' => Kelas::all()
            ]);
        } elseif ($role === 'guru' && $user->guruProfile) {
            foreach ($user->guruProfile->getAttributes() as $key => $value) {
                $user->setAttribute($key, $value);
            }
            
            return inertia('admin/manajemen-user/guru/Edit', [
                'role' => $role,
                'user' => $user,
                'mataPelajaran' => MataPelajaran::all()
            ]);
        }

        return inertia('admin/manajemen-user/'.$role.'/Edit', [
            'role' => $role,
            'user' => $user
        ]);
    }

    public function update(UpdateRequest $request, string $role, int $userId)
    {
        $user = User::findOrFail($userId);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'role' => $request->role,
        ]);

        match ($role) {
            'guru' => $user->guruProfile()->update([
                'nip' => $request->nip,
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat,
                'status_guru' => $request->status_guru,
                'tanggal_masuk' => $request->tanggal_masuk,
            ]),
            'siswa' => $user->siswaProfile()->update([
                'nisn' => $request->nisn,
                'kelas_id' => $request->kelas_id,
                'tahun_masuk' => $request->tahun_masuk,
                'alamat' => $request->alamat,
                'kontak_ortu' => $request->kontak_ortu,
                'status' => $request->status,
            ]),
            default => null,
        };

        return to_route('admin.users.index', $role)
            ->with('success', "Data {$role} berhasil diperbarui");
    }


    public function destroy(string $role, string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return to_route('admin.users.index', $role)
            ->with('success', 'Data ' . $role . ' berhasil dihapus');
    }

    /**
     * Determine the relationship name based on the user's role.
     *
     * @param string $role The role of the user.
     * @return string|null The related profile name or null if the role is unrecognized.
     */

    public function getRelation(string $role)
    {
        switch ($role) {
            case 'guru':
                return 'guruProfile';
                break;
            case 'siswa':
                return 'siswaProfile';
                break;
            default:
                return null;
        }
    }
}
