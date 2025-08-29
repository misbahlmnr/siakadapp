<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

// Props dari Laravel Controller
const props = defineProps<{
    absensi: {
        id: number;
        tanggal: string;
        status: string;
        pertemuan_ke: number;
        mata_pelajaran: string;
    }[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('siswa.dashboard') },
    { title: 'List Absensi', href: route('siswa.absensi.index') },
];

// Compute statistics from absensi data
const stats = computed(() => {
    return {
        hadir: props.absensi.filter((a) => a.status === 'Hadir').length,
        izin: props.absensi.filter((a) => a.status === 'Izin').length,
        sakit: props.absensi.filter((a) => a.status === 'Sakit').length,
        alpha: props.absensi.filter((a) => a.status === 'Alpha').length,
    };
});

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    });
};

const getStatusBadgeClass = (status: string) => {
    const classes: Record<string, string> = {
        Hadir: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        Izin: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
        Sakit: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
        Alpha: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
        Terlambat: 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
    };
    return classes[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Daftar Absensi" />

        <div class="space-y-6 p-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Daftar Absensi</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Riwayat kehadiran Anda di semester ini</p>
                </div>
            </div>

            <!-- Stats Summary -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
                <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800">
                    <div class="flex items-center">
                        <div class="rounded-full bg-green-100 p-3 dark:bg-green-900">
                            <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Hadir</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.hadir }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800">
                    <div class="flex items-center">
                        <div class="rounded-full bg-yellow-100 p-3 dark:bg-yellow-900">
                            <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                ></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Izin</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.izin }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800">
                    <div class="flex items-center">
                        <div class="rounded-full bg-blue-100 p-3 dark:bg-blue-900">
                            <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                                ></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Sakit</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.sakit }}</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800">
                    <div class="flex items-center">
                        <div class="rounded-full bg-red-100 p-3 dark:bg-red-900">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Alpha</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.alpha }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance List -->
            <div class="overflow-hidden rounded-lg bg-white shadow-sm dark:bg-gray-800">
                <div class="border-b border-gray-200 px-6 py-4 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Riwayat Absensi</h2>
                </div>

                <div v-if="absensi.length === 0" class="p-8 text-center">
                    <div class="text-gray-400 dark:text-gray-500">
                        <svg class="mx-auto mb-4 h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                            ></path>
                        </svg>
                        <p>Tidak ada data absensi</p>
                    </div>
                </div>

                <div v-else class="divide-y divide-gray-200 dark:divide-gray-700">
                    <div v-for="absen in absensi" :key="absen.id" class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ absen.mata_pelajaran }}
                                </h3>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    Pertemuan ke-{{ absen.pertemuan_ke }} • {{ formatDate(absen.tanggal) }}
                                </p>
                            </div>
                            <div class="ml-4">
                                <span :class="getStatusBadgeClass(absen.status)" class="rounded-full px-3 py-1 text-xs font-medium">
                                    {{ absen.status }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
