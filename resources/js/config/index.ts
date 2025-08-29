import { Activity, BarChart, Book, BookOpen, BookUser, Calendar, CheckCircle, Home, Pencil, Settings, Sparkles, Users } from 'lucide-vue-next';

export const GlobalConfig = {
    menuByRole: {
        admin: [
            {
                title: 'Dashboard',
                href: '/admin/dashboard',
                icon: Home,
            },
            {
                title: 'Manajemen User',
                href: '#',
                icon: Users,
                children: [
                    { title: 'Data Admin', href: '/admin/manajemen-user/admin' },
                    { title: 'Data Guru', href: '/admin/manajemen-user/guru' },
                    { title: 'Data Siswa', href: '/admin/manajemen-user/siswa' },
                ],
            },
            {
                title: 'Semester & Tahun Ajaran',
                href: '/admin/semester',
                icon: Sparkles,
            },
            {
                title: 'Mata Pelajaran',
                href: '/admin/mata-pelajaran',
                icon: BookOpen,
            },
            {
                title: 'Kelas',
                href: '/admin/kelas',
                icon: Book,
            },
            {
                title: 'Manajemen Guru Mapel',
                href: '/admin/manajemen-guru-mapel',
                icon: BookUser,
            },
            {
                title: 'Jadwal Pelajaran',
                href: '/admin/jadwal-pelajaran',
                icon: Calendar,
            },
            {
                title: 'Laporan Nilai',
                href: '/admin/laporan-nilai',
                icon: BarChart,
            },
            {
                title: 'Adaptive Rules',
                href: '/admin/adaptive-rules',
                icon: Settings,
            },
        ],
        guru: [
            {
                title: 'Dashboard',
                href: '/guru/dashboard',
                icon: Home,
            },
            {
                title: 'Jadwal Mengajar',
                href: '/guru/jadwal-mengajar',
                icon: Calendar,
            },
            {
                title: 'Nilai Siswa',
                href: '/guru/nilai',
                icon: CheckCircle,
            },
            {
                title: 'Absensi Siswa',
                href: '/guru/absensi',
                icon: Users,
            },
            {
                title: 'Adaptive Learning',
                href: '#',
                icon: Activity,
                children: [
                    { title: 'Adaptive Rules', href: '/guru/adaptive-rules' },
                    { title: 'Tambah Rekomendasi Materi', href: '/guru/rekomendasi-materi' },
                ],
            },
        ],
        siswa: [
            {
                title: 'Dashboard',
                href: '/siswa/dashboard',
                icon: Home,
            },
            {
                title: 'Materi Belajar',
                href: '/siswa/materi-belajar',
                icon: Book,
            },
            {
                title: 'Tugas/Ujian',
                href: '/siswa/tugas',
                icon: Pencil,
            },
            {
                title: 'Absensi Saya',
                href: '/siswa/absensi',
                icon: Calendar,
            },
            {
                title: 'Nilai Saya',
                href: '/siswa/nilai',
                icon: BarChart,
            },
            {
                title: 'Rekomendasi Saya',
                href: '/siswa/rekomendasi',
                icon: Sparkles,
            },
        ],
    },
};
