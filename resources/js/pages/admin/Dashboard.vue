<script setup lang="ts">
import AdaptiveChart from '@/components/AdaptiveChart.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

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
}>();
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 bg-gray-50 p-6 dark:bg-[#0A0A0A]">
            <!-- Ringkasan Data -->
            <div class="grid gap-6 md:grid-cols-4">
                <div
                    class="flex transform flex-col items-center justify-center rounded-xl border border-gray-200 bg-white p-6 shadow-lg transition-transform hover:scale-105 dark:border-gray-700 dark:bg-[#121212]"
                >
                    <div class="text-4xl font-bold text-blue-600 dark:text-blue-400">{{ totalGuru }}</div>
                    <div class="text-gray-500 dark:text-gray-300">Total Guru</div>
                </div>
                <div
                    class="flex transform flex-col items-center justify-center rounded-xl border border-gray-200 bg-white p-6 shadow-lg transition-transform hover:scale-105 dark:border-gray-700 dark:bg-[#121212]"
                >
                    <div class="text-4xl font-bold text-blue-600 dark:text-blue-400">{{ totalSiswa }}</div>
                    <div class="text-gray-500 dark:text-gray-300">Total Siswa</div>
                </div>
                <div
                    class="flex transform flex-col items-center justify-center rounded-xl border border-gray-200 bg-white p-6 shadow-lg transition-transform hover:scale-105 dark:border-gray-700 dark:bg-[#121212]"
                >
                    <div class="text-4xl font-bold text-blue-600 dark:text-blue-400">{{ totalMatpel }}</div>
                    <div class="text-gray-500 dark:text-gray-300">Total Mata Pelajaran</div>
                </div>
                <div
                    class="flex transform flex-col items-center justify-center rounded-xl border border-gray-200 bg-white p-6 shadow-lg transition-transform hover:scale-105 dark:border-gray-700 dark:bg-[#121212]"
                >
                    <div class="text-4xl font-bold text-blue-600 dark:text-blue-400">{{ totalKelas }}</div>
                    <div class="text-gray-500 dark:text-gray-300">Total Kelas</div>
                </div>
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

            <!-- Placeholder untuk grafik / konten tambahan -->
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
