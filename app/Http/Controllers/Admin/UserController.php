<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ManajemenUser\Admin\StoreRequest;
use App\Http\Requests\ManajemenUser\Admin\UpdateRequest;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $userRole = $request->route('role');

        return inertia("admin/manajemen-user/{$userRole}/Index", [
            'role' => $userRole,
        ]);
    }

    public function get(string $role)
    {
        $query = User::where('role', $role)
            ->with($role === 'siswa' ? 'siswaProfile' : 'guruProfile')
            ->orderBy('name');

        return DataTables::of($query)
            ->addIndexColumn()
            
            // Columns for students
            ->addColumn('nisn', function ($user) use ($role) {
                return $role === 'siswa' ? ($user->siswaProfile->nisn ?? '-') : '-';
            })
            ->addColumn('kelas', function ($user) use ($role) {
                return $role === 'siswa' && $user->siswaProfile?->kelas ? $user->siswaProfile->kelas->nama_kelas ?? '-' : '-';
            })
            ->addColumn('tahun_masuk', function ($user) use ($role) {
                return $role === 'siswa' ? ($user->siswaProfile->tahun_masuk ?? '-') : '-';
            })
            ->addColumn('alamat', function ($user) {
                if ($user->role === 'siswa') {
                    return $user->siswaProfile->alamat ?? '-';
                } elseif ($user->role === 'guru') {
                    return $user->guruProfile->alamat ?? '-';
                }
                return '-';
            })
            ->addColumn('kontak_ortu', function ($user) use ($role) {
                return $role === 'siswa' ? ($user->siswaProfile->kontak_ortu ?? '-') : '-';
            })
            ->addColumn('status', function ($user) use ($role) {
                return $role === 'siswa' ? ($user->siswaProfile->status ?? '-') : '-';
            })

            // Columns for teachers
            ->addColumn('nip', function ($user) use ($role) {
                return $role === 'guru' ? ($user->guruProfile->nip ?? '-') : '-';
            })
            ->addColumn('mapel', function ($user) use ($role) {
                return $role === 'guru' && $user->guruProfile?->mataPelajaran ? $user->guruProfile->mataPelajaran->nama_mapel ?? '-' : '-';
            })
            ->addColumn('no_telp', function ($user) use ($role) {
                return $role === 'guru' ? ($user->guruProfile->no_telp ?? '-') : '-';
            })
            ->addColumn('status_guru', function ($user) use ($role) {
                return $role === 'guru' ? ($user->guruProfile->status_guru ?? '-') : '-';
            })
            ->addColumn('tanggal_masuk', function ($user) use ($role) {
                return $role === 'guru' && isset($user->guruProfile->tanggal_masuk)
                    ? Carbon::parse($user->guruProfile->tanggal_masuk)->setTimezone('Asia/Jakarta')->format('d-m-Y')
                    : '-';
            })

            // Created at
            ->editColumn('created_at', function ($user) {
                return Carbon::parse($user->created_at)->setTimezone('Asia/Jakarta')->format('d-m-Y H:i');
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

    public function store(StoreRequest $request, string $roleName)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $roleName,
        ]);

        match ($roleName) {
            'guru' => $user->guruProfile()->create([
                'nip' => $request->nip,
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat,
                'status_guru' => $request->status_guru,
                'tanggal_masuk' => $request->tanggal_masuk,
                'matpel_id' => $request->matpel_id,
            ]),
            'siswa' => $user->siswaProfile()->create([
                'kelas_id' => $request->kelas_id,
                'nisn' => $request->nisn,
                'tahun_masuk' => $request->tahun_masuk,
                'alamat' => $request->alamat,
                'kontak_ortu' => $request->kontak_ortu,
                'status' => $request->status,
            ]),
        };

        return to_route('admin.users.index', $roleName)
            ->with('success', 'Data '.$roleName.' berhasil ditambahkan');
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
