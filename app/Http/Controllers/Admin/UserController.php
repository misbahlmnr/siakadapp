<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
            ->addColumn('action', function ($row) use ($role) {
                return '<a href="/admin/manajemen-user/'.$role.'/'.$row->id.'/edit" class="text-blue-500">Edit</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
