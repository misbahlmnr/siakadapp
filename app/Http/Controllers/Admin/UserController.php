<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ManajemenUser\Admin\StoreRequest;
use App\Http\Requests\ManajemenUser\Admin\UpdateRequest;
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
            ->addColumn('nip', function ($row) use ($role) {
                return $role === 'guru' ? ($row->guruProfile->nip ?? '-') : '-';
            })
            ->addColumn('mapel', function ($row) use ($role) {
                return $role === 'guru' ? ($row->guruProfile->mapel ?? '-') : '-';
            })
            ->addColumn('no_telp', function ($row) use ($role) {
                return $role === 'guru' ? ($row->guruProfile->no_telp ?? '-') : '-';
            })
            ->addColumn('alamat', function ($row) use ($role) {
                return $role === 'guru' ? ($row->guruProfile->alamat ?? '-') : '-';
            })
            ->addColumn('status_guru', function ($row) use ($role) {
                return $role === 'guru' ? ($row->guruProfile->status_guru ?? '-') : '-';
            })
            ->addColumn('tanggal_masuk', function ($row) use ($role) {
                return $role === 'guru' ? (Carbon::parse($row->guruProfile->tanggal_masuk)->setTimezone('Asia/Jakarta')->format('d-m-Y') ?? '-') : '-';
            })
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)
                    ->setTimezone('Asia/Jakarta')
                    ->format('d-m-Y H:i');
            })
            ->make(true);
    }

    public function create(Request $request)
    {
        $role = $request->route('role');

        return inertia('admin/manajemen-user/'.$role.'/Create', [
            'role' => $role
        ]);
    }

    public function store(StoreRequest $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return to_route('admin.users.index', 'admin')
            ->with('success', 'Data '.$request->role.' berhasil ditambahkan');
    }

    public function edit(string $role, string $id)
    {
        $user = User::findOrFail($id);

        return inertia('admin/manajemen-user/'.$role.'/Edit', [
            'role' => $role,
            'user' => $user
        ]);
    }

    public function update(UpdateRequest $request, string $role, string $id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            // if password is not filled, keep the old password
            'password' => $request->filled('password') 
                ? Hash::make($request->password) 
                : $user->password,
            'role' => $request->role,
        ]);

        return to_route('admin.users.index', $role)
            ->with('success', 'Data ' . $role . ' berhasil diperbarui');
    }

    public function destroy(string $role, string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return to_route('admin.users.index', $role)
            ->with('success', 'Data ' . $role . ' berhasil dihapus');
    }
}
