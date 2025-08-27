<template>
    <AppLayout>
        <Head title="Daftar Tugas" />

        <div class="space-y-6 p-6">
            <!-- Filter Section -->
            <div class="rounded-lg bg-white p-6 shadow-sm dark:bg-gray-800">
                <div class="flex flex-col items-end gap-4 sm:flex-row">
                    <div class="flex-1">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"> Filter Mata Pelajaran </label>
                        <select
                            v-model="localFilters.mapel"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="all">Semua Mata Pelajaran</option>
                            <option v-for="mapel in mataPelajaran" :key="mapel.id" :value="mapel.id">
                                {{ mapel.nama_mapel }}
                            </option>
                        </select>
                    </div>

                    <div class="flex-1">
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"> Status Tugas </label>
                        <select
                            v-model="localFilters.status"
                            class="w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                        >
                            <option value="all">Semua Status</option>
                            <option value="belum">Belum Dikerjakan</option>
                            <option value="selesai">Sudah Dikumpulkan</option>
                        </select>
                    </div>

                    <button
                        @click="applyFilters"
                        class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    >
                        Terapkan Filter
                    </button>
                </div>
            </div>

            <!-- Tugas List -->
            <div class="overflow-hidden rounded-lg bg-white shadow-sm dark:bg-gray-800">
                <div v-if="tugas.length === 0" class="p-8 text-center">
                    <div class="text-gray-400 dark:text-gray-500">
                        <svg class="mx-auto mb-4 h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                            ></path>
                        </svg>
                        <p>Tidak ada tugas yang ditemukan</p>
                    </div>
                </div>

                <div v-else class="divide-y divide-gray-200 dark:divide-gray-700">
                    <div v-for="tugasItem in tugas" :key="tugasItem.id" class="p-6 transition-colors hover:bg-gray-50 dark:hover:bg-gray-700">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                    {{ tugasItem.judul }}
                                </h3>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    {{ tugasItem.mata_pelajaran }}
                                </p>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-500">Deadline: {{ formatDate(tugasItem.waktu_selesai) }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-500">Waktu tersisa: {{ tugasItem.waktu_tersisa }}</p>
                            </div>

                            <div class="ml-4 flex flex-col items-end space-y-2">
                                <span :class="getStatusBadgeClass(tugasItem.status)" class="rounded-full px-3 py-1 text-xs font-medium">
                                    {{ getStatusText(tugasItem.status) }}
                                </span>

                                <Link
                                    :href="route('siswa.tugas.show', tugasItem.id)"
                                    class="text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                                >
                                    Lihat Detail
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { reactive } from 'vue';

interface MataPelajaran {
    id: number;
    nama_mapel: string;
}

interface TugasItem {
    id: number;
    judul: string;
    mata_pelajaran: string;
    waktu_selesai: string;
    status: string;
    waktu_tersisa: string;
    guru: string;
    sudah_dikumpulkan: boolean;
}

// Props dari Laravel Controller
defineProps<{
    tugas: TugasItem[];
    mataPelajaran: MataPelajaran[];
    filters: {
        mapel: string;
        status: string;
    };
}>();

const localFilters = reactive({
    mapel: 'all',
    status: 'all',
});

const applyFilters = () => {
    // Redirect dengan filter yang dipilih
    window.location.href = route('siswa.tugas.index', {
        mapel: localFilters.mapel,
        status: localFilters.status,
    });
};

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const getStatusBadgeClass = (status: string) => {
    const classes: Record<string, string> = {
        belum_dikumpulkan: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
        dikumpulkan: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        telat: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
    };
    return classes[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300';
};

const getStatusText = (status: string) => {
    const texts: Record<string, string> = {
        belum_dikumpulkan: 'Belum Dikerjakan',
        dikumpulkan: 'Sudah Dikumpulkan',
        telat: 'Terlambat',
    };
    return texts[status] || status;
};
</script>
