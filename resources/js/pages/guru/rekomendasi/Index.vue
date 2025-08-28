<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { useDataTable } from '@/composables/useDataTables';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import { onMounted } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('guru.dashboard') },
    { title: 'Rekomendasi Materi', href: route('guru.rekomendasi-materi.index') },
];

onMounted(() => {
    useDataTable('#rekomendasi-materi-table', {
        ajax: route('guru.rekomendasi-materi.data'),
        editRoute: 'guru.rekomendasi-materi.edit',
        deleteRoute: 'guru.rekomendasi-materi.destroy',
        detailRoute: 'guru.rekomendasi-materi.show',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '4%' },
            { data: 'nama_siswa', name: 'siswa.user.name' },
            { data: 'mata_pelajaran', name: 'evaluasiPembelajaran.mataPelajaran.nama_mapel' },
            { data: 'nama_materi', name: 'materi.judul_materi' },
            { data: 'alasan_rekomendasi', name: 'alasan_rekomendasi' },
            { data: 'status_badge', name: 'status', width: '10%' },
        ],
    });
});
</script>

<template>
    <Head title="Rekomendasi Materi" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl px-10 py-4">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Rekomendasi Materi</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Kelola rekomendasi materi manual untuk siswa</p>
                </div>
                <Button
                    type="button"
                    class="bg-blue-600 text-white hover:bg-blue-600/90"
                    @click="router.visit(route('guru.rekomendasi-materi.create'))"
                    ><Plus class="ml-2 h-4 w-4" /> Tambah Rekomendasi
                </Button>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-[#121212]">
                    <div class="flex items-center">
                        <div class="rounded-full bg-blue-100 p-2 dark:bg-blue-900/20">
                            <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 极 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-极 text-sm font-medium dark:text-gray-400">Total Rekomendasi</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">0</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-[#121212]">
                    <div class="flex items-center">
                        <div class="rounded-full bg-gray-100 p-2 dark:bg-gray-900/20">
                            <svg class="h-6 w-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Belum Dibaca</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">0</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-[#121212]">
                    <div class="flex items-center">
                        <div class="rounded-full bg-green-100 p-2 dark:bg-green-900/20">
                            <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Dibaca</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">0</p>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-[#121212]">
                    <div class="flex items-center">
                        <div class="rounded-full bg-purple-100 p-2 dark:bg-purple-900/20">
                            <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Selesai</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">0</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div>
                <table id="rekomendasi-materi-table" class="display w-full">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Mata Pelajaran</th>
                            <th>Materi</th>
                            <th>Alasan Rekomendasi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded by DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
