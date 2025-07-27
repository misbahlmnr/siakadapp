<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ManajemenUser\Admin\StoreRequest;
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

    public function get(Request $request, string $role)
    {
        $query = User::where('role', $role)->orderBy('name');

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)
                    ->setTimezone('Asia/Jakarta')
                    ->format('d-m-Y H:i');
            })
            ->rawColumns(['action'])
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
}
