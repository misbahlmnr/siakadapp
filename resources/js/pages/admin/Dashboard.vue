<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { BarChart3, BookOpen as BookIcon, BookOpen, ClipboardList, FileText, Settings, Users } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Dashboard', href: route('admin.dashboard') }];

defineProps<{
    totalMatpel: number;
    totalGuru: number;
    totalSiswa: number;
    totalKelas: number;
    adaptiveProgress: {
        belum_dibaca: number;
        dibaca: number;
        selesai: number;
    };
    recentUsers: number;
    attendanceStats: {
        total: number;
        present: number;
        absent: number;
        present_percentage: number;
        absent_percentage: number;
    };
    gradeStats: {
        total: number;
        average: number;
        excellent: number;
        good: number;
        average_grade: number;
        poor: number;
    };
    recentActivities: Array<{
        type: string;
        title: string;
        description: string;
        time: string;
        icon: string;
    }>;
}>();

const navigateTo = (routeName: string) => {
    router.visit(route(routeName));
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 bg-gray-50 p-6 dark:bg-[#0A0A0A]">
            <!-- Header Section -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Admin</h1>
                    <p class="text-gray-600 dark:text-gray-400">Selamat datang! Berikut ringkasan aktivitas admin.</p>
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{
                        new Date().toLocaleDateString('id-ID', {
                            weekday: 'long',
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric',
                        })
                    }}
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <div
                    class="group cursor-pointer rounded-xl border border-gray-200 bg-white p-4 shadow-sm transition-all hover:border-blue-300 hover:shadow-md dark:border-gray-700 dark:bg-[#121212] dark:hover:border-blue-600"
                    @click="navigateTo('admin.users.index')"
                >
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-blue-500 p-2">
                            <Users class="h-5 w-5 text-white" />
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 group-hover:text-blue-600 dark:text-white dark:group-hover:text-blue-400">
                                Manage Users
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Kelola pengguna sistem</p>
                        </div>
                    </div>
                </div>
                <div
                    class="group cursor-pointer rounded-xl border border-gray-200 bg-white p-4 shadow-sm transition-all hover:border-green-300 hover:shadow-md dark:border-gray-700 dark:bg-[#121212] dark:hover:border-green-600"
                    @click="navigateTo('admin.kelas.index')"
                >
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-green-500 p-2">
                            <BookIcon class="h-5 w-5 text-white" />
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 group-hover:text-green-600 dark:text-white dark:group-hover:text-green-400">
                                Manage Classes
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Kelola kelas yang ada</p>
                        </div>
                    </div>
                </div>
                <div
                    class="group cursor-pointer rounded-xl border border-gray-200 bg-white p-4 shadow-sm transition-all hover:border-purple-300 hover:shadow-md dark:border-gray-700 dark:bg-[#121212] dark:hover:border-purple-600"
                    @click="navigateTo('admin.reports.index')"
                >
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-purple-500 p-2">
                            <BarChart3 class="h-5 w-5 text-white" />
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 group-hover:text-purple-600 dark:text-white dark:group-hover:text-purple-400">
                                Data Reports
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Lihat laporan data</p>
                        </div>
                    </div>
                </div>
                <div
                    class="group cursor-pointer rounded-xl border border-gray-200 bg-white p-4 shadow-sm transition-all hover:border-orange-300 hover:shadow-md dark:border-gray-700 dark:bg-[#121212] dark:hover:border-orange-600"
                    @click="navigateTo('admin.settings.index')"
                >
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-orange-500 p-2">
                            <Settings class="h-5 w-5 text-white" />
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 group-hover:text-orange-600 dark:text-white dark:group-hover:text-orange-400">
                                System Settings
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Pengaturan sistem</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-[#121212]">
                    <div class="flex items-center gap-4">
                        <div class="rounded-lg bg-blue-100 p-3 dark:bg-blue-900/30">
                            <Users class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Pengguna</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ totalGuru + totalSiswa }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-[#121212]">
                    <div class="flex items-center gap-4">
                        <div class="rounded-lg bg-green-100 p-3 dark:bg-green-900/30">
                            <BookOpen class="h-6 w-6 text-green-600 dark:text-green-400" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Kelas</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ totalKelas }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-[#121212]">
                    <div class="flex items-center gap-4">
                        <div class="rounded-lg bg-purple-100 p-3 dark:bg-purple-900/30">
                            <FileText class="h-6 w-6 text-purple-600 dark:text-purple-400" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Mata Pelajaran</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ totalMatpel }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-[#121212]">
                    <div class="flex items-center gap-4">
                        <div class="rounded-lg bg-orange-100 p-3 dark:bg-orange-900/30">
                            <ClipboardList class="h-6 w-6 text-orange-600 dark:text-orange-400" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Kehadiran Hari Ini</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ attendanceStats.present }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid gap-6 lg:grid-cols-2">
                <!-- System Overview -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-[#121212]">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Overview Sistem</h2>
                        <ClipboardList class="h-5 w-5 text-gray-400" />
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Guru</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ totalGuru }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Siswa</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ totalSiswa }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Rata-rata Nilai</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ gradeStats.average }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Tingkat Kehadiran</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ attendanceStats.present_percentage }}%</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-[#121212]">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Aktivitas Terbaru</h2>
                        <FileText class="h-5 w-5 text-gray-400" />
                    </div>

                    <div class="space-y-4">
                        <div v-for="activity in recentActivities" :key="activity.title" class="rounded-lg bg-gray-50 p-4 dark:bg-gray-800">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ activity.title }}</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ activity.description }}</p>
                                </div>
                                <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">{{ activity.time }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Adaptive Learning Progress -->
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-[#121212]">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Progress Adaptive Learning</h2>
                    <BookOpen class="h-5 w-5 text-gray-400" />
                </div>

                <div class="mb-4 grid gap-4">
                    <div class="flex items-center justify-between rounded-lg bg-yellow-50 p-3 dark:bg-yellow-900/20">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Belum Dibaca</span>
                        </div>
                        <span class="font-bold text-yellow-800 dark:text-yellow-200">{{ adaptiveProgress.belum_dibaca }}</span>
                    </div>

                    <div class="flex items-center justify-between rounded-lg bg-blue-50 p-3 dark:bg-blue-900/20">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium text-blue-800 dark:text-blue-200">Dibaca</span>
                        </div>
                        <span class="font-bold text-blue-800 dark:text-blue-200">{{ adaptiveProgress.dibaca }}</span>
                    </div>

                    <div class="flex items-center justify-between rounded-lg bg-green-50 p-3 dark:bg-green-900/20">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium text-green-800 dark:text-green-200">Selesai</span>
                        </div>
                        <span class="font-bold text-green-800 dark:text-green-200">{{ adaptiveProgress.selesai }}</span>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.group:hover h3 {
    transition: color 0.2s ease;
}
</style>
