<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\RekomendasiMateri;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $autoCount = RekomendasiMateri::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $manualCount = RekomendasiMateri::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $statuses = ['belum_dibaca', 'dibaca', 'selesai'];

        $adaptiveProgress = [];
        foreach ($statuses as $status) {
            $adaptiveProgress[$status] = ($autoCount[$status] ?? 0) + ($manualCount[$status] ?? 0);
        }

        // Recent user registrations (last 7 days)
        $recentUsers = User::where('created_at', '>=', Carbon::now()->subDays(7))
            ->count();

        // Attendance statistics
        $totalAttendance = Absensi::count();
        $presentAttendance = Absensi::where('status', 'hadir')->count();
        $absentAttendance = Absensi::where('status', 'tidak_hadir')->count();
        
        $attendanceStats = [
            'total' => $totalAttendance,
            'present' => $presentAttendance,
            'absent' => $absentAttendance,
            'present_percentage' => $totalAttendance > 0 ? round(($presentAttendance / $totalAttendance) * 100, 2) : 0,
            'absent_percentage' => $totalAttendance > 0 ? round(($absentAttendance / $totalAttendance) * 100, 2) : 0,
        ];

        // Grade statistics
        $gradeStats = [
            'total' => Nilai::count(),
            'average' => Nilai::avg('nilai') ?? 0,
            'excellent' => Nilai::where('nilai', '>=', 90)->count(),
            'good' => Nilai::whereBetween('nilai', [80, 89])->count(),
            'average_grade' => Nilai::whereBetween('nilai', [70, 79])->count(),
            'poor' => Nilai::where('nilai', '<', 70)->count(),
        ];

        // Recent activities (last 5 activities from various sources)
        $recentActivities = [
            [
                'type' => 'user_registration',
                'title' => 'Pendaftaran Pengguna Baru',
                'description' => $recentUsers . ' pengguna baru mendaftar dalam 7 hari terakhir',
                'time' => 'Baru saja',
                'icon' => 'users'
            ],
            [
                'type' => 'attendance',
                'title' => 'Statistik Kehadiran',
                'description' => number_format($attendanceStats['present_percentage'], 1) . '% tingkat kehadiran',
                'time' => 'Hari ini',
                'icon' => 'clipboard-check'
            ],
            [
                'type' => 'grades',
                'title' => 'Rata-rata Nilai',
                'description' => 'Nilai rata-rata: ' . number_format($gradeStats['average'], 2),
                'time' => 'Minggu ini',
                'icon' => 'trending-up'
            ],
            [
                'type' => 'adaptive',
                'title' => 'Progress Adaptive Learning',
                'description' => $adaptiveProgress['selesai'] . ' materi selesai dipelajari',
                'time' => 'Bulan ini',
                'icon' => 'book-open'
            ]
        ];

        return Inertia::render('admin/Dashboard', [
            'totalMatpel' => MataPelajaran::count(),
            'totalSiswa' => User::where('role', 'siswa')->count(),
            'totalGuru' => User::where('role', 'guru')->count(),
            'totalKelas' => Kelas::count(),
            'adaptiveProgress' => $adaptiveProgress,
            'recentUsers' => $recentUsers,
            'attendanceStats' => $attendanceStats,
            'gradeStats' => $gradeStats,
            'recentActivities' => $recentActivities
        ]);
    }
}
