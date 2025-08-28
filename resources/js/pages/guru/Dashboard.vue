<script setup lang="ts">
import AdaptiveChart from '@/components/AdaptiveChart.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { AlertCircle, BookOpen, Calendar, CheckCircle2, ClipboardList, Clock, Eye, FileText, TrendingUp, Users } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Dashboard', href: route('guru.dashboard') }];

defineProps<{
    totalKelas: number;
    totalMatpel: number;
    totalMateri: number;
    totalEvaluasi: number;
    adaptiveProgress: {
        belum_dibaca: number;
        dibaca: number;
        selesai: number;
    };
    todaySchedule: {
        hari: string;
        jam_mulai: string;
        jam_selesai: string;
        nama_kelas: string;
        nama_mapel: string;
    }[];
}>();

const quickActions = [
    {
        title: 'Buat Materi Baru',
        description: 'Upload materi pembelajaran baru',
        icon: FileText,
        route: 'guru.jadwal-mengajar.materi.create',
        color: 'bg-blue-500',
    },
    {
        title: 'Buat Evaluasi',
        description: 'Buat tugas atau ujian baru',
        icon: ClipboardList,
        route: 'guru.jadwal-mengajar.evaluasi-pembelajaran.create',
        color: 'bg-green-500',
    },
    {
        title: 'Lihat Jadwal',
        description: 'Lihat jadwal mengajar lengkap',
        icon: Calendar,
        route: 'guru.jadwal-mengajar.index',
        color: 'bg-purple-500',
    },
    {
        title: 'Rekomendasi',
        description: 'Kelola rekomendasi materi',
        icon: TrendingUp,
        route: 'guru.rekomendasi-materi.index',
        color: 'bg-orange-500',
    },
];

const navigateTo = (routeName: string) => {
    router.visit(route(routeName));
};
</script>

<template>
    <Head title="Dashboard Guru" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 bg-gray-50 p-6 dark:bg-[#0A0A0A]">
            <!-- Header Section -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Guru</h1>
                    <p class="text-gray-600 dark:text-gray-400">Selamat datang! Berikut ringkasan aktivitas mengajar Anda.</p>
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
                    v-for="(action, index) in quickActions"
                    :key="index"
                    class="group cursor-pointer rounded-xl border border-gray-200 bg-white p-4 shadow-sm transition-all hover:border-blue-300 hover:shadow-md dark:border-gray-700 dark:bg-[#121212] dark:hover:border-blue-600"
                    @click="navigateTo(action.route)"
                >
                    <div class="flex items-center gap-3">
                        <div :class="['rounded-lg p-2', action.color]">
                            <component :is="action.icon" class="h-5 w-5 text-white" />
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 group-hover:text-blue-600 dark:text-white dark:group-hover:text-blue-400">
                                {{ action.title }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ action.description }}</p>
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
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Kelas Diampu</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ totalKelas }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-[#121212]">
                    <div class="flex items-center gap-4">
                        <div class="rounded-lg bg-green-100 p-3 dark:bg-green-900/30">
                            <BookOpen class="h-6 w-6 text-green-600 dark:text-green-400" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Mata Pelajaran</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ totalMatpel }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-[#121212]">
                    <div class="flex items-center gap-4">
                        <div class="rounded-lg bg-purple-100 p-3 dark:bg-purple-900/30">
                            <FileText class="h-6 w-6 text-purple-600 dark:text-purple-400" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Materi Diupload</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ totalMateri }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-[#121212]">
                    <div class="flex items-center gap-4">
                        <div class="rounded-lg bg-orange-100 p-3 dark:bg-orange-900/30">
                            <ClipboardList class="h-6 w-6 text-orange-600 dark:text-orange-400" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Tugas/Ujian Dibuat</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ totalEvaluasi }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid gap-6 lg:grid-cols-2">
                <!-- Jadwal Mengajar Hari Ini -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-[#121212]">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Jadwal Mengajar Hari Ini</h2>
                        <Calendar class="h-5 w-5 text-gray-400" />
                    </div>

                    <div v-if="todaySchedule.length > 0" class="space-y-3">
                        <div
                            v-for="(item, index) in todaySchedule"
                            :key="index"
                            class="flex items-center justify-between rounded-lg bg-blue-50 p-4 shadow-sm dark:bg-blue-900/20"
                        >
                            <div class="flex-1">
                                <div class="font-semibold text-gray-900 dark:text-white">{{ item.nama_mapel }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">{{ item.nama_kelas }}</div>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                                <Clock class="h-4 w-4" />
                                {{ item.jam_mulai }} - {{ item.jam_selesai }}
                            </div>
                        </div>
                    </div>
                    <div v-else class="py-8 text-center text-gray-500 dark:text-gray-400">
                        <Calendar class="mx-auto mb-2 h-12 w-12 opacity-50" />
                        <p>Tidak ada jadwal mengajar hari ini</p>
                    </div>
                </div>

                <!-- Progress Adaptive Learning -->
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-[#121212]">
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Progress Adaptive Learning</h2>
                        <TrendingUp class="h-5 w-5 text-gray-400" />
                    </div>

                    <div class="mb-4 grid gap-4">
                        <div class="flex items-center justify-between rounded-lg bg-yellow-50 p-3 dark:bg-yellow-900/20">
                            <div class="flex items-center gap-2">
                                <AlertCircle class="h-4 w-4 text-yellow-600 dark:text-yellow-400" />
                                <span class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Belum Dibaca</span>
                            </div>
                            <span class="font-bold text-yellow-800 dark:text-yellow-200">{{ adaptiveProgress.belum_dibaca }}</span>
                        </div>

                        <div class="flex items-center justify-between rounded-lg bg-blue-50 p-3 dark:bg-blue-900/20">
                            <div class="flex items-center gap-2">
                                <Eye class="h-4 w-4 text-blue-600 dark:text-blue-400" />
                                <span class="text-sm font-medium text-blue-800 dark:text-blue-200">Dibaca</span>
                            </div>
                            <span class="font-bold text-blue-800 dark:text-blue-200">{{ adaptiveProgress.dibaca }}</span>
                        </div>

                        <div class="flex items-center justify-between rounded-lg bg-green-50 p-3 dark:bg-green-900/20">
                            <div class="flex items-center gap-2">
                                <CheckCircle2 class="h-4 w-4 text-green-600 dark:text-green-400" />
                                <span class="text-sm font-medium text-green-800 dark:text-green-200">Selesai</span>
                            </div>
                            <span class="font-bold text-green-800 dark:text-green-200">{{ adaptiveProgress.selesai }}</span>
                        </div>
                    </div>

                    <!-- Grafik Progress -->
                    <div class="relative h-64 rounded-lg bg-gray-50 dark:bg-gray-800">
                        <AdaptiveChart
                            :labels="['Belum Dibaca', 'Dibaca', 'Selesai']"
                            :data="[adaptiveProgress.belum_dibaca, adaptiveProgress.dibaca, adaptiveProgress.selesai]"
                        />
                    </div>
                </div>
            </div>

            <!-- Recent Activity Section -->
            <!-- <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-[#121212]">
                <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Aktivitas Terbaru</h2>
                <div class="py-8 text-center text-gray-500 dark:text-gray-400">
                    <p>Fitur aktivitas terbaru akan segera hadir</p>
                    <p class="text-sm">(Dalam pengembangan)</p>
                </div>
            </div> -->
        </div>
    </AppLayout>
</template>

<style scoped>
.group:hover h3 {
    transition: color 0.2s ease;
}
</style>
