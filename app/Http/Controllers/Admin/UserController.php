<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ManajemenUser\StoreRequest;
use App\Http\Requests\ManajemenUser\UpdateRequest;
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

            // Columns for guru
            ->addColumn('nip', function ($user) use ($role) {
                return $role === 'guru' ? ($user->guruProfile->nip ?? '-') : '-';
            })
            ->addColumn('jenis_kelamin', function ($user) use ($role) {
                return $role === 'guru' ? ($user->guruProfile->jenis_kelamin ?? '-') : '-';
            })
            ->addColumn('tempat_tanggal_lahir', function ($user) use ($role) {
                if ($role === 'guru' && $user->guruProfile) {
                    $tempat = $user->guruProfile->tempat_lahir ?? '-';
                    $tanggal = $user->guruProfile->tanggal_lahir 
                        ? \Carbon\Carbon::parse($user->guruProfile->tanggal_lahir)->format('d-m-Y') 
                        : '-';
                    return $tempat . ', ' . $tanggal;
                }
                return '-';
            })
            ->addColumn('status_kepegawaian', function ($user) use ($role) {
                return $role === 'guru' ? ($user->guruProfile->status_kepegawaian ?? '-') : '-';
            })

            // Created at
            ->editColumn('created_at', function ($user) {
                return formatCreatedAt($user->created_at);
            })
            ->make(true);
    }

    public function create(Request $request)
    {
        $roleName = $request->route('role');

        $viewData = [
            'role' => $roleName
        ];

        if ($roleName === 'siswa') {
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
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'status_kepegawaian' => $request->status_kepegawaian,
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
                'guru' => $user,
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
                'jenis_kelamin' => $request->jenis_kelamin,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'status_kepegawaian' => $request->status_kepegawaian,
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
