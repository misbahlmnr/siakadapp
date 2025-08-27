<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    public function index()
    {
$grades = Nilai::where('siswa_id', Auth::user()->siswaProfile->id)
    ->with(['evaluasiPembelajaran.jadwal.mataPelajaran'])
    ->get();
return Inertia::render('siswa/nilai/Index', [
    'grades' => $grades,
]);
    }
}
