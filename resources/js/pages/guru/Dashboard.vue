<script setup lang="ts">
import AdaptiveChart from '@/components/AdaptiveChart.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

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
    upcomingSchedule: {
        hari: string;
        jam_mulai: string;
        jam_selesai: string;
        nama_kelas: string;
        nama_mapel: string;
    }[];
}>();
</script>

<template>
    <Head title="Dashboard Guru" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 bg-gray-50 p-6 dark:bg-[#0A0A0A]">
            <!-- Ringkasan Data -->
            <div class="grid gap-6 md:grid-cols-4">
                <div
                    class="flex flex-col items-center justify-center rounded-xl border border-gray-200 bg-white p-6 shadow-lg transition-transform hover:scale-105 dark:border-gray-700 dark:bg-[#121212]"
                >
                    <div class="text-4xl font-bold text-blue-600 dark:text-blue-400">{{ totalKelas }}</div>
                    <div class="text-gray-500 dark:text-gray-300">Kelas Diampu</div>
                </div>
                <div
                    class="flex flex-col items-center justify-center rounded-xl border border-gray-200 bg-white p-6 shadow-lg transition-transform hover:scale-105 dark:border-gray-700 dark:bg-[#121212]"
                >
                    <div class="text-4xl font-bold text-green-600 dark:text-green-400">{{ totalMatpel }}</div>
                    <div class="text-gray-500 dark:text-gray-300">Mata Pelajaran</div>
                </div>
                <div
                    class="flex flex-col items-center justify-center rounded-xl border border-gray-200 bg-white p-6 shadow-lg transition-transform hover:scale-105 dark:border-gray-700 dark:bg-[#121212]"
                >
                    <div class="text-4xl font-bold text-purple-600 dark:text-purple-400">{{ totalMateri }}</div>
                    <div class="text-gray-500 dark:text-gray-300">Materi Diupload</div>
                </div>
                <div
                    class="flex flex-col items-center justify-center rounded-xl border border-gray-200 bg-white p-6 shadow-lg transition-transform hover:scale-105 dark:border-gray-700 dark:bg-[#121212]"
                >
                    <div class="text-4xl font-bold text-orange-600 dark:text-orange-400">{{ totalEvaluasi }}</div>
                    <div class="text-gray-500 dark:text-gray-300">Tugas/Ujian Dibuat</div>
                </div>
            </div>

            <!-- Jadwal Mengajar Berikutnya -->
            <div class="mt-6 rounded-xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-[#121212]">
                <h2 class="mb-4 text-xl font-semibold text-gray-800 dark:text-gray-100">Jadwal Mengajar Terdekat</h2>
                <div v-if="upcomingSchedule.length > 0" class="space-y-3">
                    <div
                        v-for="(item, index) in upcomingSchedule"
                        :key="index"
                        class="flex items-center justify-between rounded-lg bg-blue-50 p-4 shadow-sm dark:bg-blue-900/30"
                    >
                        <div>
                            <div class="font-semibold text-gray-700 dark:text-gray-200">{{ item.nama_mapel }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ item.nama_kelas }}</div>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-300">{{ item.hari }}, {{ item.jam_mulai }} - {{ item.jam_selesai }}</div>
                    </div>
                </div>
                <div v-else class="text-gray-500 dark:text-gray-400">Tidak ada jadwal terdekat.</div>
            </div>

            <!-- Progress Adaptive Learning -->
            <div class="mt-6 rounded-xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-[#121212]">
                <h2 class="mb-4 text-xl font-semibold text-gray-800 dark:text-gray-100">Progress Adaptive Learning</h2>
                <div class="grid gap-4 md:grid-cols-3">
                    <div class="rounded-lg bg-yellow-100 p-4 text-center text-yellow-800 shadow-md dark:bg-yellow-900 dark:text-yellow-200">
                        Belum Dibaca: {{ adaptiveProgress.belum_dibaca }}
                    </div>
                    <div class="rounded-lg bg-blue-100 p-4 text-center text-blue-800 shadow-md dark:bg-blue-900 dark:text-blue-200">
                        Dibaca: {{ adaptiveProgress.dibaca }}
                    </div>
                    <div class="rounded-lg bg-green-100 p-4 text-center text-green-800 shadow-md dark:bg-green-900 dark:text-green-200">
                        Selesai: {{ adaptiveProgress.selesai }}
                    </div>
                </div>
            </div>

            <!-- Grafik Progress -->
            <div
                class="relative mt-6 max-h-[500px] min-h-[300px] rounded-xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-[#121212]"
            >
                <AdaptiveChart
                    :labels="['Belum Dibaca', 'Dibaca', 'Selesai']"
                    :data="[adaptiveProgress.belum_dibaca, adaptiveProgress.dibaca, adaptiveProgress.selesai]"
                />
            </div>
        </div>
    </AppLayout>
</template>
