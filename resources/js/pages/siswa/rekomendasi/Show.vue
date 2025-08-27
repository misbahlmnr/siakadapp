<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    rekomendasi: {
        id: number;
        materi_id: number;
        evaluasi_pembelajaran_id: number;
        siswa_id: number;
        alasan_rekomendasi: string;
        status: string;
        created_at: string;
        updated_at: string;
        materi?: {
            id: number;
            jadwal_id: number;
            guru_id: number;
            semester_ajaran_id: number;
            pertemuan_ke: number;
            judul_materi: string;
            deskripsi: string;
            file_materi: string | null;
            link_materi: string | null;
            jadwal?: {
                id: number;
                matpel_id: number;
                mataPelajaran?: {
                    id: number;
                    nama_mapel: string;
                };
            };
        };
        evaluasi_pembelajaran?: {
            id: number;
            judul_evaluasi: string;
        };
    };
}>();

const getStatusBadgeClass = (status: string) => {
    const classes: Record<string, string> = {
        belum_dibaca: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        dibaca: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        selesai: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
    };
    return `px-3 py-1 text-xs font-medium rounded-full ${classes[status] || classes.belum_dibaca}`;
};

const getStatusText = (status: string) => {
    const texts: Record<string, string> = {
        belum_dibaca: 'Belum Dibaca',
        dibaca: 'Dibaca',
        selesai: 'Selesai',
    };
    return texts[status] || 'Belum Dibaca';
};

const mataPelajaranNama = computed(() => {
    return props.rekomendasi.materi?.jadwal?.mataPelajaran?.nama_mapel || 'Mata Pelajaran Tidak Diketahui';
});
</script>

<template>
    <AppLayout title="Detail Rekomendasi Materi" description="Halaman ini menampilkan detail rekomendasi materi untuk siswa.">
        <Head title="Detail Rekomendasi Materi" />

        <div class="rounded-2xl bg-white p-6 shadow-lg transition-colors dark:bg-gray-900">
            <!-- Header Section -->
            <div class="mb-6 border-b border-gray-200 pb-4 dark:border-gray-700">
                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            {{ props.rekomendasi.materi?.judul_materi || 'Materi Tidak Ditemukan' }}
                        </h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ mataPelajaranNama }}</p>
                    </div>
                    <span :class="getStatusBadgeClass(props.rekomendasi.status)">
                        {{ getStatusText(props.rekomendasi.status) }}
                    </span>
                </div>
            </div>

            <!-- Content Section -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <h2 class="mb-3 text-lg font-semibold text-gray-900 dark:text-gray-100">Informasi Materi</h2>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Pertemuan Ke</span>
                                <span class="text-gray-900 dark:text-gray-200">{{ props.rekomendasi.materi?.pertemuan_ke || '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Mata Pelajaran</span>
                                <span class="text-gray-900 dark:text-gray-200">{{ mataPelajaranNama }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Evaluasi</span>
                                <span class="text-gray-900 dark:text-gray-200">{{
                                    props.rekomendasi.evaluasi_pembelajaran?.judul_evaluasi || 'Evaluasi Umum'
                                }}</span>
                            </div>
                        </div>
                    </div>

                    <div
                        v-if="props.rekomendasi.materi?.file_materi"
                        class="rounded-xl border border-gray-200 bg-gray-50 p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800"
                    >
                        <h2 class="mb-3 text-lg font-semibold text-gray-900 dark:text-gray-100">File Materi</h2>
                        <a
                            :href="props.rekomendasi.materi.file_materi"
                            target="_blank"
                            class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm text-white transition-colors hover:bg-blue-700 dark:hover:bg-blue-500"
                        >
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                />
                            </svg>
                            Download Materi
                        </a>
                    </div>

                    <div
                        v-if="props.rekomendasi.materi?.link_materi"
                        class="rounded-xl border border-gray-200 bg-gray-50 p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800"
                    >
                        <h2 class="mb-3 text-lg font-semibold text-gray-900 dark:text-gray-100">Link Eksternal</h2>
                        <a
                            :href="props.rekomendasi.materi.link_materi"
                            target="_blank"
                            class="inline-flex items-center rounded-lg bg-green-600 px-4 py-2 text-sm text-white transition-colors hover:bg-green-700 dark:hover:bg-green-500"
                        >
                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"
                                />
                            </svg>
                            Buka Link Materi
                        </a>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <h2 class="mb-3 text-lg font-semibold text-gray-900 dark:text-gray-100">Alasan Rekomendasi</h2>
                        <p class="text-gray-700 dark:text-gray-300">
                            {{ props.rekomendasi.alasan_rekomendasi || 'Materi ini direkomendasikan untuk meningkatkan pemahaman Anda' }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <h2 class="mb-3 text-lg font-semibold text-gray-900 dark:text-gray-100">Deskripsi Materi</h2>
                        <p class="text-gray-700 dark:text-gray-300">
                            {{ props.rekomendasi.materi?.deskripsi || 'Tidak ada deskripsi tersedia' }}
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-wrap gap-3">
                        <Link
                            :href="route('siswa.rekomendasi.index')"
                            class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-700 transition-colors hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
                        >
                            Kembali
                        </Link>

                        <Link
                            v-if="props.rekomendasi.materi"
                            :href="route('siswa.materi.show', props.rekomendasi.materi.id)"
                            class="rounded-lg bg-blue-600 px-4 py-2 text-sm text-white transition-colors hover:bg-blue-700 dark:hover:bg-blue-500"
                        >
                            Pelajari Materi
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
