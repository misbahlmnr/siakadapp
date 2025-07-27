import { Activity, BarChart, Book, BookOpen, Calendar, CheckCircle, FileText, Home, List, Pencil, Settings, Sparkles, Users } from 'lucide-vue-next';

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
                title: 'Mata Pelajaran',
                href: '/admin/subjects',
                icon: BookOpen,
            },
            {
                title: 'Kelas & Jurusan',
                href: '/admin/classes',
                icon: Calendar, // kalau mau icon lain ganti saja
            },
            {
                title: 'Jadwal Pelajaran',
                href: '/admin/schedules',
                icon: Calendar,
            },
            {
                title: 'Adaptive Rules',
                href: '/admin/adaptive-rules',
                icon: Settings,
            },
            {
                title: 'Laporan Nilai',
                href: '/admin/reports',
                icon: BarChart,
            },
        ],
        guru: [
            {
                title: 'Dashboard',
                href: '/guru/dashboard',
                icon: Home,
            },
            {
                title: 'Materi Saya',
                href: '/guru/materi',
                icon: FileText,
            },
            {
                title: 'Bank Soal',
                href: '/guru/soal',
                icon: List,
            },
            {
                title: 'Penilaian',
                href: '/guru/nilai',
                icon: CheckCircle,
            },
            {
                title: 'Adaptive Tracker',
                href: '/guru/adaptive',
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
