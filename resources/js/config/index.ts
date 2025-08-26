import { Activity, BarChart, Book, BookOpen, Calendar, CheckCircle, Home, Pencil, Settings, Sparkles, Users } from 'lucide-vue-next';

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
                    { title: 'Data Admin', href: route('admin.users.index', 'admin') },
                    { title: 'Data Guru', href: route('admin.users.index', 'guru') },
                    { title: 'Data Siswa', href: route('admin.users.index', 'siswa') },
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
                href: '/guru/adaptive-learning',
                icon: Activity,
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
                href: '/siswa/materi',
                icon: Book,
            },
            {
                title: 'Quiz/Ujian',
                href: '/siswa/quiz',
                icon: Pencil,
            },
            {
                title: 'Hasil Belajar',
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
