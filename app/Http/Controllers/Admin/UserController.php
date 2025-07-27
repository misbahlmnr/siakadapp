<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // get user role
        $role = request()->route('role');

        // get users data based on role
        $users = User::where('role', $role)
            ->orderBy('name')
            ->get();

        // check if role is guru
        if ($role === 'guru') {
            return inertia('admin/manajemen-user/guru/Index', [
                'users' => $users
            ]);
        }
        
        // check if role is siswa
        if ($role === 'siswa') {
            return inertia('admin/manajemen-user/siswa/Index', [
                'users' => $users
            ]);
        }
        
        return inertia('Admin/Users/Index', [
            'users' => $users,
            'role' => $role,
        ]);
    }
}
